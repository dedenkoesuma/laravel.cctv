<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderController extends Controller
{
    // ===== GENERATE NOMOR SO =====
    private function generateSoNumber(): string
    {
        $year   = now()->year;
        $prefix = "SO-{$year}-";
        $last   = DB::table('sales_orders')
            ->where('so_number', 'like', "{$prefix}%")
            ->orderBy('so_number', 'desc')
            ->value('so_number');

        $nextNum = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    // ===== LIST SO =====
    public function index(Request $request)
    {
        $query = DB::table('sales_orders')
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('so_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $salesOrders = $query->paginate(15);

        // Attach items ke setiap SO
        $salesOrders->getCollection()->transform(function($so) {
            $so->items = DB::table('sales_order_items')
                ->join('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
                ->where('sales_order_items.sales_order_id', $so->id)
                ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku')
                ->get();
            $so->status_label = $this->statusLabel($so->status);
            $so->status_color = $this->statusColor($so->status);
            return $so;
        });

        return view('admin.gudang.sales-orders.index', compact('salesOrders'));
    }

    // ===== FORM BUAT SO =====
    public function create()
    {
        // Cek session dulu sebelum tampil form
        if (!session('admin_logged_in') || !session('admin_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $products = DB::table('gudang_products')
            ->where('sisa_stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        $soNumber = $this->generateSoNumber();

        return view('admin.gudang.sales-orders.create', compact('products', 'soNumber'));
    }

    // ===== SIMPAN SO =====
  // ===== SIMPAN SO =====
    public function store(Request $request)
    {
        // ✅ Cek session
        $adminId = session('admin_id');
        if (!$adminId) {
            return response()->json(['success' => false, 'message' => 'Sesi expired. Silakan login ulang.'], 401);
        }

        // ✅ Validasi admin masih ada di DB
        $adminExists = DB::table('admins')->where('id', $adminId)->exists();
        if (!$adminExists) {
            session()->flush();
            return response()->json(['success' => false, 'message' => 'Akun tidak valid. Silakan login ulang.'], 401);
        }

        $request->validate([
            'customer_name'        => 'required|string|max:255',
            'customer_phone'       => 'nullable|string|max:20',
            'customer_address'     => 'nullable|string',
            'customer_email'       => 'nullable|email|max:255',
            'so_date'              => 'required|date',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:gudang_products,id',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $soNumber = $this->generateSoNumber();

            // 1. Hitung Subtotal Produk dari items
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['qty'] * $item['harga_satuan']);
            }

            // 2. Hitung PPN
            // Tangkap nilai dari JavaScript, pastikan formatnya benar
            $ppn_aktif = $request->ppn_aktif ? 1 : 0;
            $ppn_rate = $ppn_aktif ? $request->ppn_rate : 0;
            $ppn_nominal = $ppn_aktif ? ($subtotal * ($ppn_rate / 100)) : 0;

            // 3. Grand Total
            $grand_total = $subtotal + $ppn_nominal;

            // 4. Insert SO ke Database beserta data PPN-nya
            $soId = DB::table('sales_orders')->insertGetId([
                'so_number'        => $soNumber,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_email'   => $request->customer_email,
                'so_date'          => $request->so_date,
                'status'           => 'draft',
                'notes'            => $request->notes,
                
                // Masukkan data PPN yang sudah dihitung
                'ppn_aktif'        => $ppn_aktif,
                'ppn_rate'         => $ppn_rate,
                'ppn_nominal'      => $ppn_nominal,
                
                'total_amount'     => $grand_total,
                'created_by'       => $adminId,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // 5. Insert Items
            foreach ($request->items as $item) {
                $subtotal_item = $item['qty'] * $item['harga_satuan'];

                DB::table('sales_order_items')->insert([
                    'sales_order_id' => $soId,
                    'product_id'     => $item['product_id'],
                    'qty'            => $item['qty'],
                    'harga_satuan'   => $item['harga_satuan'],
                    'subtotal'       => $subtotal_item,
                    'notes'          => $item['notes'] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::commit();
            
            // Return Response JSON agar ditangkap dengan baik oleh Fetch API di Blade
            return response()->json([
                'success' => true, 
                'message' => "SO {$soNumber} berhasil dibuat."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal membuat SO: ' . $e->getMessage()], 500);
        }
    }

    // ===== DETAIL SO =====
    public function show($id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder) abort(404);

        $salesOrder->items = DB::table('sales_order_items')
            ->join('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select(
                'sales_order_items.*',
                'gudang_products.nama_produk',
                'gudang_products.sku',
                'gudang_products.category'
            )
            ->get();

        // ✅ FIX: Ambil SN dari barang_masuk (status terjual), bukan dari barang_keluar
        foreach ($salesOrder->items as $item) {
            $item->serials = DB::table('barang_masuk')
                ->where('product_id', $item->product_id)
                ->where('status', 'terjual')
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', '')
                ->select('id', 'serial_number')
                ->get();
        }

        $salesOrder->creator_name = DB::table('admins')->where('id', $salesOrder->created_by)->value('name') ?? '-';
        $salesOrder->status_label = $this->statusLabel($salesOrder->status);
        $salesOrder->status_color = $this->statusColor($salesOrder->status);

        return view('admin.gudang.sales-orders.show', compact('salesOrder'));
    }

    // ===== FORM APPROVE =====
    public function approveForm($id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder || $salesOrder->status !== 'draft') {
            return back()->with('error', 'SO tidak ditemukan atau sudah diproses.');
        }

        $salesOrder->items = DB::table('sales_order_items')
            ->join('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku', 'gudang_products.sisa_stok')
            ->get();

        // Cek stok cukup
        foreach ($salesOrder->items as $item) {
            if ($item->sisa_stok < $item->qty) {
                return back()->with('error',
                    "Stok {$item->nama_produk} tidak cukup. Stok: {$item->sisa_stok}, Butuh: {$item->qty}");
            }
        }

        // SN available per item (dari barang_masuk)
        $availableSerials = [];
        foreach ($salesOrder->items as $item) {
            $hasSn = DB::table('barang_masuk')
                ->where('product_id', $item->product_id)
                ->where('status', 'tersedia')
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', '')
                ->exists();

            if ($hasSn) {
                $availableSerials[$item->id] = DB::table('barang_masuk')
                    ->where('product_id', $item->product_id)
                    ->where('status', 'tersedia')
                    ->whereNotNull('serial_number')
                    ->where('serial_number', '!=', '')
                    ->select('id', 'serial_number')
                    ->orderBy('serial_number')
                    ->get();
            }
        }

        $salesOrder->status_label = $this->statusLabel($salesOrder->status);

        return view('admin.gudang.sales-orders.approve', compact('salesOrder', 'availableSerials'));
    }

    // ===== APPROVE SO =====
    public function approve(Request $request, $id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder || $salesOrder->status !== 'draft') {
            return back()->with('error', 'SO tidak ditemukan atau sudah diproses.');
        }

        $items = DB::table('sales_order_items')
            ->join('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sisa_stok')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $serialIds = $request->serials[$item->id] ?? [];

                if (!empty($serialIds)) {
                    // Produk pakai SN — keluar per SN
                    foreach ($serialIds as $snId) {
                        $sn = DB::table('barang_masuk')->where('id', $snId)->first();
                        if (!$sn || $sn->status !== 'tersedia') {
                            throw new \Exception("SN tidak valid atau sudah tidak tersedia.");
                        }

                        // Catat barang keluar
                        DB::table('barang_keluar')->insert([
                            'product_id'     => $item->product_id,
                            'jumlah'         => 1,
                            'keterangan'     => 'terjual',
                            'harga_jual'     => $item->harga_satuan,
                            'penerima'       => $salesOrder->customer_name,
                            'tanggal_keluar' => $salesOrder->so_date,
                            'catatan'        => "SO: {$salesOrder->so_number}",
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);

                        // Update status SN jadi terjual
                        DB::table('barang_masuk')->where('id', $snId)->update([
                            'status'     => 'terjual',
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Produk tanpa SN — keluar langsung
                    DB::table('barang_keluar')->insert([
                        'product_id'     => $item->product_id,
                        'jumlah'         => $item->qty,
                        'keterangan'     => 'terjual',
                        'harga_jual'     => $item->harga_satuan,
                        'penerima'       => $salesOrder->customer_name,
                        'tanggal_keluar' => $salesOrder->so_date,
                        'catatan'        => "SO: {$salesOrder->so_number}",
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                // Recalculate stok
                $this->recalculateStock($item->product_id);
            }

            DB::table('sales_orders')->where('id', $id)->update([
                'status'     => 'approved',
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect("/admin/gudang/sales-orders/{$id}")
                ->with('success', "SO {$salesOrder->so_number} disetujui, stok berhasil dikurangi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    // ===== DELIVER =====
    public function deliver($id)
    {
        $so = DB::table('sales_orders')->where('id', $id)->first();
        if (!$so || $so->status !== 'approved') {
            return back()->with('error', 'SO belum disetujui.');
        }
        DB::table('sales_orders')->where('id', $id)->update([
            'status'     => 'delivered',
            'updated_at' => now(),
        ]);
        return back()->with('success', "SO {$so->so_number} ditandai terkirim.");
    }

    // ===== CANCEL =====
    public function cancel($id)
    {
        $so = DB::table('sales_orders')->where('id', $id)->first();
        if (!$so || in_array($so->status, ['delivered', 'cancelled'])) {
            return back()->with('error', 'SO tidak bisa dibatalkan.');
        }

        DB::beginTransaction();
        try {
            if ($so->status === 'approved') {
                $items = DB::table('sales_order_items')->where('sales_order_id', $id)->get();
                foreach ($items as $item) {
                    // Kembalikan SN yang terkait (dari barang_masuk status terjual + catatan SO ini)
                    $keluarItems = DB::table('barang_keluar')
                        ->where('product_id', $item->product_id)
                        ->where('catatan', 'like', "%{$so->so_number}%")
                        ->get();

                    // Kembalikan status SN ke tersedia di barang_masuk
                    // (ambil dari barang_masuk yg status terjual, cocokkan qty)
                    $terjualSns = DB::table('barang_masuk')
                        ->where('product_id', $item->product_id)
                        ->where('status', 'terjual')
                        ->whereNotNull('serial_number')
                        ->where('serial_number', '!=', '')
                        ->limit($item->qty)
                        ->get();

                    foreach ($terjualSns as $sn) {
                        DB::table('barang_masuk')
                            ->where('id', $sn->id)
                            ->update(['status' => 'tersedia', 'updated_at' => now()]);
                    }

                    // Hapus barang_keluar terkait SO ini
                    DB::table('barang_keluar')
                        ->where('product_id', $item->product_id)
                        ->where('catatan', 'like', "%{$so->so_number}%")
                        ->delete();

                    $this->recalculateStock($item->product_id);
                }
            }

            DB::table('sales_orders')->where('id', $id)->update([
                'status'     => 'cancelled',
                'updated_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', "SO {$so->so_number} dibatalkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal batalkan: ' . $e->getMessage());
        }
    }

    // ===== HAPUS SO =====
    public function destroy($id)
    {
        $so = DB::table('sales_orders')->where('id', $id)->first();
        if (!$so) abort(404);

        if (!in_array($so->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'Hanya SO berstatus Draft atau Dibatalkan yang bisa dihapus.');
        }

        DB::beginTransaction();
        try {
            DB::table('sales_order_items')->where('sales_order_id', $id)->delete();
            DB::table('sales_orders')->where('id', $id)->delete();
            DB::commit();

            return redirect('/admin/gudang/sales-orders')
                ->with('success', "SO {$so->so_number} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus SO: ' . $e->getMessage());
        }
    }

    // ===== DOWNLOAD PDF =====
    public function downloadPdf($id)
    {
        $salesOrder = $this->getSoForPdf($id);
        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download("SO-{$salesOrder->so_number}.pdf");
    }

    // ===== PREVIEW PDF =====
    public function previewPdf($id)
    {
        $salesOrder = $this->getSoForPdf($id);
        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream("SO-{$salesOrder->so_number}.pdf");
    }

    // ===== KIRIM EMAIL =====
    public function sendEmail(Request $request, $id)
    {
        $request->validate(['email' => 'required|email']);
        $salesOrder = $this->getSoForPdf($id);

        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))
            ->setPaper('A4', 'portrait');

        \Mail::send('admin.gudang.sales-orders.email', compact('salesOrder'),
            function ($mail) use ($request, $salesOrder, $pdf) {
                $mail->to($request->email)
                     ->subject("Surat Order {$salesOrder->so_number} - TechStore")
                     ->attachData($pdf->output(), "SO-{$salesOrder->so_number}.pdf", [
                         'mime' => 'application/pdf',
                     ]);
            }
        );

        return back()->with('success', "SO berhasil dikirim ke {$request->email}.");
    }

    // ===== API: GET AVAILABLE SERIALS =====
    public function getAvailableSerials(Request $request)
    {
        $serials = DB::table('barang_masuk')
            ->where('product_id', $request->product_id)
            ->where('status', 'tersedia')
            ->whereNotNull('serial_number')
            ->where('serial_number', '!=', '')
            ->select('id', 'serial_number')
            ->orderBy('serial_number')
            ->get();

        return response()->json($serials);
    }

    // ===== HELPER: Data SO untuk PDF =====
    private function getSoForPdf($id)
    {
        $so = DB::table('sales_orders')->where('id', $id)->first();
        if (!$so) abort(404);

        $so->items = DB::table('sales_order_items')
            ->join('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select(
                'sales_order_items.*',
                'gudang_products.nama_produk',
                'gudang_products.sku',
                'gudang_products.category'
            )
            ->get();

        // ✅ FIX: Ambil SN dari barang_masuk (status terjual), bukan dari barang_keluar
        foreach ($so->items as $item) {
            $item->serials = DB::table('barang_masuk')
                ->where('product_id', $item->product_id)
                ->where('status', 'terjual')
                ->whereNotNull('serial_number')
                ->where('serial_number', '!=', '')
                ->select('serial_number')
                ->get();
        }

        $so->creator_name = DB::table('admins')->where('id', $so->created_by)->value('name') ?? 'Admin';
        $so->status_label = $this->statusLabel($so->status);
        $so->status_color = $this->statusColor($so->status);

        return $so;
    }

    // ===== HELPER: Recalculate stok =====
    private function recalculateStock($productId)
    {
        $totalMasuk  = DB::table('barang_masuk')->where('product_id', $productId)->sum('jumlah');
        $totalKeluar = DB::table('barang_keluar')->where('product_id', $productId)->sum('jumlah');

        DB::table('gudang_products')->where('id', $productId)->update([
            'total_masuk'  => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'sisa_stok'    => $totalMasuk - $totalKeluar,
            'updated_at'   => now(),
        ]);
    }

    // ===== HELPER: Status label & color =====
    private function statusLabel($status): string
    {
        return match($status) {
            'draft'     => 'Draft',
            'approved'  => 'Disetujui',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($status),
        };
    }

    private function statusColor($status): string
    {
        return match($status) {
            'draft'     => 'secondary',
            'approved'  => 'success',
            'delivered' => 'primary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}