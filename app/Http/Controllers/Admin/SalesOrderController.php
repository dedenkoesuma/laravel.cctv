<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

    // ===== GENERATE NOMOR INVOICE =====
    private function generateInvoiceNumber(): string
    {
        $year   = now()->year;
        $prefix = "INV-{$year}-";
        $last   = DB::table('keuangan_transaksi')
            ->where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->value('invoice_number');
        $nextNum = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    // ===== GENERATE KODE TRANSAKSI =====
    private function generateKodeTransaksi(): string
    {
        $year   = now()->year;
        $prefix = "TRX-{$year}-";
        $last   = DB::table('keuangan_transaksi')
            ->where('kode_transaksi', 'like', "{$prefix}%")
            ->orderBy('kode_transaksi', 'desc')
            ->value('kode_transaksi');
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
        $salesOrders->getCollection()->transform(function($so) {
            $so->items = DB::table('sales_order_items')
                ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
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
        if (!session('admin_logged_in') || !session('admin_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $products = DB::table('gudang_products')->orderBy('nama_produk')->get();
        $soNumber = $this->generateSoNumber();

        return view('admin.gudang.sales-orders.create', compact('products', 'soNumber'));
    }

    // ===== SIMPAN SO =====
    public function store(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return response()->json(['success' => false, 'message' => 'Sesi expired. Silakan login ulang.'], 401);
        }

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

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['qty'] * $item['harga_satuan']);
            }

            $ppn_aktif   = $request->ppn_aktif ? 1 : 0;
            $ppn_rate    = $ppn_aktif ? $request->ppn_rate : 0;
            $ppn_nominal = $ppn_aktif ? ($subtotal * ($ppn_rate / 100)) : 0;
            $grand_total = $subtotal + $ppn_nominal;

            $soId = DB::table('sales_orders')->insertGetId([
                'so_number'        => $soNumber,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_email'   => $request->customer_email,
                'so_date'          => $request->so_date,
                'status'           => 'draft',
                'notes'            => $request->notes,
                'ppn_aktif'        => $ppn_aktif,
                'ppn_rate'         => $ppn_rate,
                'ppn_nominal'      => $ppn_nominal,
                'total_amount'     => $grand_total,
                'created_by'       => $adminId,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

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
            return response()->json(['success' => true, 'message' => "SO {$soNumber} berhasil dibuat."]);
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
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku', 'gudang_products.category')
            ->get();

        foreach ($salesOrder->items as $item) {
            if (!$item->nama_produk && $item->notes) {
                $item->nama_produk = $item->notes;
            }

            $item->serials = $item->product_id
                ? DB::table('barang_masuk')
                    ->where('product_id', $item->product_id)
                    ->where('status', 'terjual')
                    ->whereNotNull('serial_number')
                    ->where('serial_number', '!=', '')
                    ->select('id', 'serial_number')
                    ->get()
                : collect();
        }

        $salesOrder->creator_name = DB::table('admins')->where('id', $salesOrder->created_by)->value('name') ?? '-';
        $salesOrder->status_label = $this->statusLabel($salesOrder->status);
        $salesOrder->status_color = $this->statusColor($salesOrder->status);

        try {
            $salesOrder->invoice = DB::table('keuangan_transaksi')
                ->where('no_order', $salesOrder->so_number)
                ->first();

            if ($salesOrder->invoice) {
                $salesOrder->invoice->so_number = $salesOrder->invoice->no_order;
            }
        } catch (\Exception $e) {
            $salesOrder->invoice = null;
        }

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
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku', 'gudang_products.sisa_stok')
            ->get();

        foreach ($salesOrder->items as $item) {
            if (!$item->nama_produk && $item->notes) {
                $item->nama_produk = $item->notes;
            }
        }

        foreach ($salesOrder->items as $item) {
            if (!$item->product_id) continue;
            if ($item->sisa_stok < $item->qty) {
                return back()->with('error', "Stok {$item->nama_produk} tidak cukup. Stok: {$item->sisa_stok}, Butuh: {$item->qty}");
            }
        }

        $availableSerials = [];
        foreach ($salesOrder->items as $item) {
            if (!$item->product_id) continue;
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
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sisa_stok')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (!$item->product_id) continue;
                $serialIds = $request->serials[$item->id] ?? [];

                if (!empty($serialIds)) {
                    foreach ($serialIds as $snId) {
                        $sn = DB::table('barang_masuk')->where('id', $snId)->first();
                        if (!$sn || $sn->status !== 'tersedia') {
                            throw new \Exception("SN tidak valid atau sudah tidak tersedia.");
                        }

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

                        DB::table('barang_masuk')->where('id', $snId)->update([
                            'status'     => 'terjual',
                            'updated_at' => now(),
                        ]);
                    }
                } else {
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

    // ===== FORM BUAT INVOICE =====
    public function createInvoiceForm($id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder || $salesOrder->status !== 'approved') {
            return back()->with('error', 'SO harus berstatus Disetujui untuk membuat Invoice.');
        }

        try {
            $existingInvoice = DB::table('keuangan_transaksi')
                ->where('no_order', $salesOrder->so_number)
                ->first();

            if ($existingInvoice) {
                return back()->with('error', "Invoice sudah dibuat: {$existingInvoice->invoice_number}");
            }
        } catch (\Exception $e) {
            // Abaikan
        }

        $salesOrder->items = DB::table('sales_order_items')
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku')
            ->get();

        $invoiceNumber = $this->generateInvoiceNumber();
        return view('admin.gudang.sales-orders.invoice-form', compact('salesOrder', 'invoiceNumber'));
    }

    // ===== SIMPAN INVOICE =====
    public function storeInvoice(Request $request, $id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder || $salesOrder->status !== 'approved') {
            return back()->with('error', 'SO tidak valid.');
        }

        $request->validate([
            'tipe_bayar'    => 'required|in:cash,tempo',
            'tempo_hari'    => 'nullable|required_if:tipe_bayar,tempo|integer|min:1',
            'nama_bank'     => 'required|string|max:100',
            'no_rekening'   => 'required|string|max:50',
            'nama_rekening' => 'required|string|max:100',
            'metode_bayar'  => 'required|in:cash,transfer,qris,kartu_kredit',
            'dp_nominal'    => 'nullable|numeric|min:0',  // ✅ TAMBAHAN
            'catatan'       => 'nullable|string',
        ]);

        try {
            $exists = DB::table('keuangan_transaksi')
                ->where('no_order', $salesOrder->so_number)
                ->exists();
            if ($exists) {
                return back()->with('error', 'Invoice untuk SO ini sudah dibuat.');
            }
        } catch (\Exception $e) {
            // Abaikan
            dd('TERNYATA INI ERRORNYA BANG: ' . $e->getMessage(), 'Terjadi di baris: ' . $e->getLine());
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = $this->generateInvoiceNumber();
            $kodeTransaksi = $this->generateKodeTransaksi();
            $invoiceDate   = now()->toDateString();

            $jatuhTempo = $request->tipe_bayar === 'tempo'
                ? now()->addDays((int) $request->tempo_hari)->toDateString()
                : null;

            // ✅ Hitung dp_nominal & sisa_tagihan
            $dp_nominal   = (float) ($request->dp_nominal ?? 0);
            $sisa_tagihan = $salesOrder->total_amount - $dp_nominal;

            DB::table('keuangan_transaksi')->insert([
                'kode_transaksi' => $kodeTransaksi,
                'tipe'           => 'pemasukan',
                'kategori'       => 'Penjualan Produk',
                'sub_kategori'   => 'SO',
                'jumlah'         => $salesOrder->total_amount,
                'tanggal'        => $invoiceDate,
                'deskripsi'      => "Invoice {$invoiceNumber} - {$salesOrder->customer_name}",
                'referensi'      => $salesOrder->so_number,
                'metode_bayar'   => $request->metode_bayar,
                'status'         => 'pending',
                'pihak_terkait'  => $salesOrder->customer_name,
                'catatan'        => $request->catatan,
                'no_order'       => $salesOrder->so_number,
                'invoice_number' => $invoiceNumber,
                'invoice_date'   => $invoiceDate,
                'tipe_bayar'     => $request->tipe_bayar,
                'tempo_hari'     => $request->tipe_bayar === 'tempo' ? $request->tempo_hari : null,
                'jatuh_tempo'    => $jatuhTempo,
                'nama_bank'      => $request->nama_bank,
                'no_rekening'    => $request->no_rekening,
                'nama_rekening'  => $request->nama_rekening,
                'dp_nominal'     => $dp_nominal,    // ✅ TAMBAHAN
                'sisa_tagihan'   => $sisa_tagihan,  // ✅ TAMBAHAN
                'created_by'     => session('admin_id'),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // ✅ NOTIFIKASI OTOMATIS JIKA PEMBAYARAN TEMPO
            if ($request->tipe_bayar === 'tempo' && $jatuhTempo) {
                $this->buatNotifikasiInvoiceTempo(
                    $invoiceNumber,
                    $salesOrder->customer_name,
                    $salesOrder->total_amount,
                    $jatuhTempo,
                    (int) $request->tempo_hari
                );
            }

            DB::commit();
            return redirect("/admin/gudang/sales-orders/{$id}")
                ->with('success', "Invoice {$invoiceNumber} berhasil dibuat. Tandai Lunas setelah pembayaran diterima.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Invoice: ' . $e->getMessage());
        }
    }

    // ===== HELPER: BUAT NOTIFIKASI INVOICE TEMPO =====
    private function buatNotifikasiInvoiceTempo(
        string $invoiceNumber,
        string $customerName,
        float  $totalAmount,
        string $jatuhTempo,
        int    $tempoHari
    ): void {
        $total   = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $tglFmt  = Carbon::parse($jatuhTempo)->format('d/m/Y');
        $selisih = (int) now()->startOfDay()->diffInDays(Carbon::parse($jatuhTempo)->startOfDay(), false);

        if ($selisih < 0) {
            \App\Models\Notification::create([
                'tipe'       => 'overdue',
                'judul'      => "Piutang overdue — {$invoiceNumber}",
                'pesan'      => "Invoice {$invoiceNumber} atas nama {$customerName} senilai {$total} telah melewati jatuh tempo ({$tglFmt}).",
                'invoice_id' => null,
            ]);
        } elseif ($selisih === 1) {
            \App\Models\Notification::create([
                'tipe'       => 'h1',
                'judul'      => "Jatuh tempo besok! — {$invoiceNumber}",
                'pesan'      => "Invoice {$invoiceNumber} atas nama {$customerName} senilai {$total} jatuh tempo besok ({$tglFmt}). Segera follow up.",
                'invoice_id' => null,
            ]);
        } elseif ($selisih <= 3) {
            \App\Models\Notification::create([
                'tipe'       => 'h3',
                'judul'      => "Jatuh tempo {$selisih} hari lagi — {$invoiceNumber}",
                'pesan'      => "Invoice {$invoiceNumber} atas nama {$customerName} senilai {$total} akan jatuh tempo pada {$tglFmt}.",
                'invoice_id' => null,
            ]);
        } else {
            \App\Models\Notification::create([
                'tipe'       => 'h3',
                'judul'      => "Invoice tempo dibuat — {$invoiceNumber}",
                'pesan'      => "Invoice {$invoiceNumber} atas nama {$customerName} senilai {$total} dengan jatuh tempo {$tglFmt} ({$tempoHari} hari).",
                'invoice_id' => null,
            ]);
        }
    }

    // ===== TANDAI LUNAS =====
    public function markLunas($id)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $id)->first();
        if (!$salesOrder) abort(404);

        try {
            $invoice = DB::table('keuangan_transaksi')
                ->where('no_order', $salesOrder->so_number)
                ->where('status', 'pending')
                ->first();

            if (!$invoice) {
                return back()->with('error', 'Invoice tidak ditemukan atau sudah lunas.');
            }

            DB::table('keuangan_transaksi')->where('id', $invoice->id)->update([
                'status'     => 'lunas',
                'tanggal'    => now()->toDateString(),
                'updated_at' => now(),
            ]);

            return back()->with('success', "Invoice {$invoice->invoice_number} ditandai LUNAS. Transaksi masuk ke pembukuan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    // ===== DOWNLOAD PDF INVOICE =====
    public function downloadInvoicePdf($id)
    {
        $data = $this->getInvoiceData($id);
        if (!$data) return back()->with('error', 'Data tidak ditemukan / Database tidak sesuai.');

        $pdf = Pdf::loadView('admin.gudang.sales-orders.invoice-pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download("INV-{$data['invoice']->invoice_number}.pdf");
    }

    // ===== PREVIEW PDF INVOICE =====
    public function previewInvoicePdf($id)
    {
        $data = $this->getInvoiceData($id);
        if (!$data) return back()->with('error', 'Data tidak ditemukan / Database tidak sesuai.');

        $pdf = Pdf::loadView('admin.gudang.sales-orders.invoice-pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream("INV-{$data['invoice']->invoice_number}.pdf");
    }

    // ===== KIRIM EMAIL INVOICE =====
    public function sendInvoiceEmail(Request $request, $id)
    {
        $request->validate(['email' => 'required|email']);

        $data = $this->getInvoiceData($id);
        if (!$data) return back()->with('error', 'Data tidak ditemukan / Database tidak sesuai.');

        $pdf = Pdf::loadView('admin.gudang.sales-orders.invoice-pdf', $data)
            ->setPaper('A4', 'portrait');

        \Mail::send(
            'admin.gudang.sales-orders.invoice-email',
            $data,
            function ($mail) use ($request, $data, $pdf) {
                $mail->to($request->email)
                     ->subject("Invoice {$data['invoice']->invoice_number} - TechStore")
                     ->attachData($pdf->output(), "INV-{$data['invoice']->invoice_number}.pdf", [
                         'mime' => 'application/pdf',
                     ]);
            }
        );
        return back()->with('success', "Invoice berhasil dikirim ke {$request->email}.");
    }

    // ===== DELIVER =====
    public function deliver($id)
    {
        $so = DB::table('sales_orders')->where('id', $id)->first();
        if (!$so || $so->status !== 'approved') {
            return back()->with('error', 'SO belum disetujui.');
        }

        DB::table('sales_orders')->where('id', $id)->update(['status' => 'delivered', 'updated_at' => now()]);
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
                    if (!$item->product_id) continue;

                    $terjualSns = DB::table('barang_masuk')
                        ->where('product_id', $item->product_id)
                        ->where('status', 'terjual')
                        ->whereNotNull('serial_number')
                        ->where('serial_number', '!=', '')
                        ->limit($item->qty)
                        ->get();

                    foreach ($terjualSns as $sn) {
                        DB::table('barang_masuk')->where('id', $sn->id)
                            ->update(['status' => 'tersedia', 'updated_at' => now()]);
                    }

                    DB::table('barang_keluar')
                        ->where('product_id', $item->product_id)
                        ->where('catatan', 'like', "%{$so->so_number}%")
                        ->delete();

                    $this->recalculateStock($item->product_id);
                }
            }

            DB::table('sales_orders')->where('id', $id)->update(['status' => 'cancelled', 'updated_at' => now()]);

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

            return redirect('/admin/gudang/sales-orders')->with('success', "SO {$so->so_number} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus SO: ' . $e->getMessage());
        }
    }

    // ===== DOWNLOAD PDF SO =====
    public function downloadPdf($id)
    {
        $salesOrder = $this->getSoForPdf($id);
        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download("SO-{$salesOrder->so_number}.pdf");
    }

    // ===== PREVIEW PDF SO =====
    public function previewPdf($id)
    {
        $salesOrder = $this->getSoForPdf($id);
        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'Arial')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream("SO-{$salesOrder->so_number}.pdf");
    }

    // ===== KIRIM EMAIL SO =====
    public function sendEmail(Request $request, $id)
    {
        $request->validate(['email' => 'required|email']);

        $salesOrder = $this->getSoForPdf($id);
        $pdf = Pdf::loadView('admin.gudang.sales-orders.pdf', compact('salesOrder'))->setPaper('A4', 'portrait');

        \Mail::send('admin.gudang.sales-orders.email', compact('salesOrder'),
            function ($mail) use ($request, $salesOrder, $pdf) {
                $mail->to($request->email)
                     ->subject("Surat Order {$salesOrder->so_number} - TechStore")
                     ->attachData($pdf->output(), "SO-{$salesOrder->so_number}.pdf", ['mime' => 'application/pdf']);
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
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $id)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku', 'gudang_products.category')
            ->get();

        foreach ($so->items as $item) {
            if (!$item->nama_produk && $item->notes) {
                $item->nama_produk = $item->notes;
            }

            $item->serials = $item->product_id
                ? DB::table('barang_masuk')
                    ->where('product_id', $item->product_id)
                    ->where('status', 'terjual')
                    ->whereNotNull('serial_number')
                    ->where('serial_number', '!=', '')
                    ->select('serial_number')
                    ->get()
                : collect();
        }

        $so->creator_name = DB::table('admins')->where('id', $so->created_by)->value('name') ?? 'Admin';
        $so->status_label = $this->statusLabel($so->status);
        $so->status_color = $this->statusColor($so->status);

        return $so;
    }

    // ===== HELPER: Data Invoice untuk PDF =====
    private function getInvoiceData($soId)
    {
        $salesOrder = DB::table('sales_orders')->where('id', $soId)->first();
        if (!$salesOrder) abort(404);

        try {
            $invoice = DB::table('keuangan_transaksi')
                ->where('no_order', $salesOrder->so_number)
                ->first();

            if (!$invoice) abort(404, 'Invoice belum dibuat untuk SO ini.');

            $invoice->so_number = $invoice->no_order;

        } catch (\Exception $e) {
            return null;
        }

        $salesOrder->items = DB::table('sales_order_items')
            ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
            ->where('sales_order_items.sales_order_id', $soId)
            ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku')
            ->get();

        foreach ($salesOrder->items as $item) {
            if (!$item->nama_produk && $item->notes) {
                $item->nama_produk = $item->notes;
            }
        }

        return compact('salesOrder', 'invoice');
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