<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/PurchaseOrderController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    // ===== HALAMAN LIST =====
    public function index()
    {
        return view('admin.purchase-orders.index');
    }

    // ===== HALAMAN BUAT PO =====
    public function create()
    {
        $poNumber = $this->generatePoNumber();
        return view('admin.purchase-orders.create', compact('poNumber'));
    }

    // ===== HALAMAN EDIT =====
    public function edit($id)
    {
        $po    = DB::table('purchase_orders')->where('id', $id)->first();
        $items = DB::table('purchase_order_items')->where('purchase_order_id', $id)->get();

        if (!$po) abort(404);
        if ($po->status !== 'draft') {
            return redirect()->route('admin.po.index')
                ->with('error', 'Hanya PO berstatus Draft yang bisa diedit!');
        }

        return view('admin.purchase-orders.edit', compact('po', 'items'));
    }

    // ===== SIMPAN PO BARU =====
    // ===== SIMPAN PO BARU =====
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string',
            'po_date'       => 'required|date',
            'items'         => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.qty'          => 'required|numeric|min:0.1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $calc   = $this->calculate($request);
            $poNum  = $this->generatePoNumber();

            $poId = DB::table('purchase_orders')->insertGetId([
                'po_number'       => $poNum,
                'supplier_name'   => $request->supplier_name,
                'supplier_phone'  => $request->supplier_phone,
                'supplier_email'  => $request->supplier_email,
                'supplier_address'=> $request->supplier_address,
                'supplier_pic'    => $request->supplier_pic,
                'po_date'         => $request->po_date,
                'required_date'   => $request->required_date,
                'payment_method'  => $request->payment_method ?? 'transfer',
                'delivery_to'     => $request->delivery_to,
                'use_ppn'         => $calc['use_ppn'],
                'ppn_percent'     => $calc['ppn_percent'],
                'ppn_amount'      => $calc['ppn_amount'],
                'subtotal'        => $calc['subtotal'],
                'discount'        => $calc['discount'],
                'shipping_cost'   => $request->shipping_cost ?? 0,
                'total_amount'    => $calc['total'],
                'status'          => 'draft',
                'notes'           => $request->notes,
                'created_by'      => session('admin_id', 1),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Simpan items
            foreach ($request->items as $item) {
                $sub = ($item['qty'] * $item['unit_price']) - ($item['discount_item'] ?? 0);
                DB::table('purchase_order_items')->insert([
                    'purchase_order_id'  => $poId,
                    'product_name'       => $item['product_name'],
                    'product_description'=> $item['product_description'] ?? null,
                    'unit'               => $item['unit'] ?? 'pcs',
                    'qty'                => $item['qty'],
                    'qty_received'       => 0,
                    'unit_price'         => $item['unit_price'],
                    'discount_item'      => $item['discount_item'] ?? 0,
                    'subtotal'           => $sub,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // ===== INTEGRASI KE FINANCE (SEBAGAI PIUTANG DAGANG) =====
            // Generate Kode Transaksi Finance (Pakai Prefix PIU)
            $countFinance = DB::table('keuangan_transaksi')->whereYear('created_at', date('Y'))->where('tipe', 'pemasukan')->count();
            $kodeFinance = 'PIU-' . date('Y') . '-' . str_pad($countFinance + 1, 4, '0', STR_PAD_LEFT);

            DB::table('keuangan_transaksi')->insert([
                'kode_transaksi' => $kodeFinance,
                'tipe'           => 'pemasukan', // Di sistemmu, piutang disimpan sbg pemasukan + pending
                'kategori'       => 'Piutang Dagang', // <-- Berubah ke Piutang Dagang
                'jumlah'         => $calc['total'],
                'tanggal'        => $request->po_date,
                'deskripsi'      => 'Pembayaran PO: ' . $poNum,
                'referensi'      => $poNum,
                'no_order'       => $poNum,
                'metode_bayar'   => $request->payment_method ?? 'transfer',
                'status'         => 'pending', 
                'pihak_terkait'  => $request->supplier_name,
                'created_by'     => session('admin_id', 1),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            // ========================================================

            // Log
            $this->log($poId, 'created', "Draft PO {$poNum} dibuat", session('admin_name', 'Admin'));

            DB::commit();
            return response()->json([
                'success'   => true,
                'message'   => "PO {$poNum} berhasil disimpan dan disinkronisasi!",
                'po_number' => $poNum,
                'po_id'     => $poId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ===== UPDATE PO =====
    public function update(Request $request, $id)
    {
        $po = DB::table('purchase_orders')->where('id', $id)->first();
        if (!$po) return response()->json(['success' => false, 'message' => 'PO tidak ditemukan'], 404);
        if ($po->status !== 'draft') return response()->json(['success' => false, 'message' => 'Hanya Draft yang bisa diedit!'], 422);

        DB::beginTransaction();
        try {
            $calc = $this->calculate($request);

            DB::table('purchase_orders')->where('id', $id)->update([
                'supplier_name'   => $request->supplier_name,
                'supplier_phone'  => $request->supplier_phone,
                'supplier_email'  => $request->supplier_email,
                'supplier_address'=> $request->supplier_address,
                'supplier_pic'    => $request->supplier_pic,
                'po_date'         => $request->po_date,
                'required_date'   => $request->required_date,
                'payment_method'  => $request->payment_method,
                'delivery_to'     => $request->delivery_to,
                'use_ppn'         => $calc['use_ppn'],
                'ppn_percent'     => $calc['ppn_percent'],
                'ppn_amount'      => $calc['ppn_amount'],
                'subtotal'        => $calc['subtotal'],
                'discount'        => $calc['discount'],
                'shipping_cost'   => $request->shipping_cost ?? 0,
                'total_amount'    => $calc['total'],
                'notes'           => $request->notes,
                'updated_at'      => now(),
            ]);

            // Hapus lama, insert baru
            DB::table('purchase_order_items')->where('purchase_order_id', $id)->delete();
            foreach ($request->items as $item) {
                $sub = ($item['qty'] * $item['unit_price']) - ($item['discount_item'] ?? 0);
                DB::table('purchase_order_items')->insert([
                    // ... (Isi field items tetap sama persis seperti aslinya) ...
                    'purchase_order_id'  => $id,
                    'product_name'       => $item['product_name'],
                    'product_description'=> $item['product_description'] ?? null,
                    'unit'               => $item['unit'] ?? 'pcs',
                    'qty'                => $item['qty'],
                    'qty_received'       => 0,
                    'unit_price'         => $item['unit_price'],
                    'discount_item'      => $item['discount_item'] ?? 0,
                    'subtotal'           => $sub,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // ===== SYNC UPDATE KE FINANCE =====
            DB::table('keuangan_transaksi')->where('no_order', $po->po_number)->update([
                'jumlah'        => $calc['total'],
                'tanggal'       => $request->po_date,
                'pihak_terkait' => $request->supplier_name,
                'metode_bayar'  => $request->payment_method,
                'updated_at'    => now(),
            ]);
            // ===================================

            $this->log($id, 'edited', "PO diupdate oleh " . session('admin_name', 'Admin'));
            DB::commit();
            return response()->json(['success' => true, 'message' => 'PO berhasil diupdate!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ===== UPDATE STATUS =====
    public function updateStatus(Request $request, $id)
    {
        $po = DB::table('purchase_orders')->where('id', $id)->first();
        if (!$po) return response()->json(['success' => false, 'message' => 'PO tidak ditemukan'], 404);

        $status   = $request->status;
        $allowed  = ['draft', 'sent', 'confirmed', 'partial', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
        }

        DB::table('purchase_orders')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => now(),
        ]);

        // ===== SYNC BATAL KE FINANCE =====
        // Jika PO dibatalkan, batalkan juga hutangnya di finance
        if ($status === 'cancelled') {
            DB::table('keuangan_transaksi')->where('no_order', $po->po_number)->update([
                'status' => 'batal',
                'updated_at' => now()
            ]);
        }
        // ==================================

        $labels = [
            'sent'      => 'PO dikirim ke supplier',
            'confirmed' => 'PO dikonfirmasi supplier',
            'partial'   => 'Sebagian barang diterima',
            'completed' => 'Semua barang diterima — PO selesai',
            'cancelled' => 'PO dibatalkan',
        ];

        $this->log($id, $status, $labels[$status] ?? "Status diubah ke {$status}", session('admin_name', 'Admin'));

        return response()->json(['success' => true, 'message' => 'Status PO diupdate!']);
    }

    // ===== HAPUS PO =====
    public function destroy($id)
    {
        $po = DB::table('purchase_orders')->where('id', $id)->first();
        if (!$po) return response()->json(['success' => false, 'message' => 'PO tidak ditemukan'], 404);
        if ($po->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Hanya Draft yang bisa dihapus!'], 422);
        }

        // Hapus data finance terkait
        DB::table('keuangan_transaksi')->where('no_order', $po->po_number)->delete();

        DB::table('purchase_order_items')->where('purchase_order_id', $id)->delete();
        DB::table('purchase_order_logs')->where('purchase_order_id', $id)->delete();
        DB::table('purchase_orders')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'PO berhasil dihapus!']);
    }

    // ===== GET LIST (API) =====
    public function getList(Request $request)
    {
        $query = DB::table('purchase_orders');

        if ($request->search)  $query->where(function($q) use ($request) {
            $q->where('po_number', 'like', '%'.$request->search.'%')
              ->orWhere('supplier_name', 'like', '%'.$request->search.'%');
        });
        if ($request->status)  $query->where('status', $request->status);
        if ($request->bulan)   $query->whereMonth('po_date', $request->bulan);
        if ($request->tahun)   $query->whereYear('po_date', $request->tahun);

        $list = $query->orderByDesc('id')->get();

        $summary = [
            'draft'     => DB::table('purchase_orders')->where('status', 'draft')->count(),
            'sent'      => DB::table('purchase_orders')->where('status', 'sent')->count(),
            'confirmed' => DB::table('purchase_orders')->where('status', 'confirmed')->count(),
            'total_amount' => DB::table('purchase_orders')
                ->whereMonth('po_date', $request->bulan ?? date('m'))
                ->whereYear('po_date', $request->tahun ?? date('Y'))
                ->sum('total_amount'),
        ];

        return response()->json(['success' => true, 'data' => $list, 'summary' => $summary]);
    }

    // ===== GET DETAIL (API) =====
    public function getDetail($id)
    {
        $po    = DB::table('purchase_orders')->where('id', $id)->first();
        $items = DB::table('purchase_order_items')->where('purchase_order_id', $id)->get();
        $logs  = DB::table('purchase_order_logs')->where('purchase_order_id', $id)->orderBy('id')->get();

        if (!$po) return response()->json(['success' => false, 'message' => 'Tidak ditemukan'], 404);

        return response()->json(['success' => true, 'po' => $po, 'items' => $items, 'logs' => $logs]);
    }

    // ===== CETAK PDF (halaman view) =====
    public function printPdf($id)
    {
        $po    = DB::table('purchase_orders')->where('id', $id)->first();
        $items = DB::table('purchase_order_items')->where('purchase_order_id', $id)->get();
        if (!$po) abort(404);
        return view('admin.purchase-orders.pdf', compact('po', 'items'));
    }

    // ===== DOWNLOAD PDF (pakai DomPDF) =====
    public function downloadPdf($id)
    {
        $po    = DB::table('purchase_orders')->where('id', $id)->first();
        $items = DB::table('purchase_order_items')->where('purchase_order_id', $id)->get();
        if (!$po) abort(404);

        $html = view('admin.purchase-orders.pdf', compact('po', 'items'))->render();
        $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('A4', 'portrait');

        $this->log($id, 'downloaded', "PDF didownload oleh " . session('admin_name', 'Admin'));

        return $pdf->download("PO-{$po->po_number}.pdf");
    }

    // ===== HELPER: HITUNG TOTAL =====
    private function calculate(Request $request): array
    {
        $subtotal = 0;
        foreach ($request->items ?? [] as $item) {
            $sub       = ($item['qty'] * $item['unit_price']) - ($item['discount_item'] ?? 0);
            $subtotal += max(0, $sub);
        }

        $discount    = floatval($request->discount ?? 0);
        $shipping    = floatval($request->shipping_cost ?? 0);
        $usePpn      = $request->boolean('use_ppn');
        $ppnPercent  = floatval($request->ppn_percent ?? 11);
        $afterDisc   = $subtotal - $discount;
        $ppnAmount   = $usePpn ? round($afterDisc * ($ppnPercent / 100), 2) : 0;
        $total       = $afterDisc + $ppnAmount + $shipping;

        return [
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'use_ppn'     => $usePpn,
            'ppn_percent' => $ppnPercent,
            'ppn_amount'  => $ppnAmount,
            'total'       => $total,
        ];
    }

    // ===== HELPER: GENERATE NO. PO =====
    private function generatePoNumber(): string
    {
        $tahun = date('Y');
        $count = DB::table('purchase_orders')->whereYear('created_at', $tahun)->count();
        return 'PO-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    // ===== HELPER: LOG =====
    private function log($poId, $action, $desc, $actor = 'Admin')
    {
        DB::table('purchase_order_logs')->insert([
            'purchase_order_id' => $poId,
            'action'            => $action,
            'description'       => $desc,
            'actor'             => $actor,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
    public function history()
    {
        return view('admin.purchase-orders.history');
    }
    // ===== EXPORT KE CSV =====
    public function export(Request $request)
    {
        $query = DB::table('purchase_orders');

        // Filter sesuai yang ada di halaman depan
        if ($request->search)  $query->where(function($q) use ($request) {
            $q->where('po_number', 'like', '%'.$request->search.'%')
              ->orWhere('supplier_name', 'like', '%'.$request->search.'%');
        });
        if ($request->status)  $query->where('status', $request->status);
        if ($request->bulan)   $query->whereMonth('po_date', $request->bulan);
        if ($request->tahun)   $query->whereYear('po_date', $request->tahun);

        $list = $query->orderByDesc('id')->get();

        $fileName = 'Export_PO_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No PO', 'Tanggal', 'Supplier', 'Status', 'Subtotal', 'PPN', 'Diskon', 'Ongkir', 'Total'];

        $callback = function() use($list, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($list as $po) {
                fputcsv($file, [
                    $po->po_number,
                    $po->po_date,
                    $po->supplier_name,
                    strtoupper($po->status),
                    $po->subtotal,
                    $po->ppn_amount,
                    $po->discount,
                    $po->shipping_cost,
                    $po->total_amount
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}