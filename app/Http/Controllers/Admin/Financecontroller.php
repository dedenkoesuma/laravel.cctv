<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/FinanceController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    // ===== HALAMAN STAFF FINANCE =====
    public function index()
    {
        return view('admin.finance.index');
    }

    // ===== API: SUMMARY KHUSUS FINANCE =====
    public function getSummary(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Piutang bulan ini
        $piutangBulan = DB::table('keuangan_transaksi')
            ->where('tipe', 'piutang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // Piutang pending (belum lunas) — semua waktu
        $piutangPending = DB::table('keuangan_transaksi')
            ->where('tipe', 'piutang')
            ->where('status', 'pending')
            ->sum('jumlah');

        // Piutang lunas bulan ini
        $piutangLunas = DB::table('keuangan_transaksi')
            ->where('tipe', 'piutang')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // Pengeluaran bulan ini (input oleh staff finance)
        $pengeluaranBulan = DB::table('keuangan_transaksi')
            ->where('tipe', 'pengeluaran')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // Total transaksi bulan ini
        $totalTransaksi = DB::table('keuangan_transaksi')
            ->whereIn('tipe', ['piutang', 'pengeluaran'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        return response()->json([
            'piutang_bulan'    => $piutangBulan,
            'piutang_pending'  => $piutangPending,
            'piutang_lunas'    => $piutangLunas,
            'pengeluaran_bulan'=> $pengeluaranBulan,
            'total_transaksi'  => $totalTransaksi,
        ]);
    }

    // ===== API: LIST TRANSAKSI FINANCE =====
    public function getTransaksi(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereIn('tipe', ['piutang', 'pengeluaran']); // hanya tampilkan piutang & pengeluaran

        if ($request->tipe)   $query->where('tipe', $request->tipe);
        if ($request->status) $query->where('status', $request->status);
        if ($request->bulan)  $query->whereMonth('tanggal', $request->bulan);
        if ($request->tahun)  $query->whereYear('tanggal', $request->tahun);

        if ($request->search) $query->where(function ($q) use ($request) {
            $q->where('deskripsi', 'like', '%' . $request->search . '%')
              ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%')
              ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%');
        });

        $transaksi = $query->orderByDesc('tanggal')->orderByDesc('id')->get();

        return response()->json(['success' => true, 'data' => $transaksi]);
    }

    // ===== API: LIST INVOICE (dari keuangan_transaksi yang punya invoice_number) =====
    public function getInvoices(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereNotNull('invoice_number'); // hanya yang punya invoice dari SO

        if ($request->status) $query->where('status', $request->status);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('no_order',      'like', '%' . $request->search . '%') // <-- FIXED (dulu so_number)
                  ->orWhere('pihak_terkait',  'like', '%' . $request->search . '%')
                  ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderByDesc('id')->get();

        // Map data agar nama propertinya tetap terbaca 'so_number' di frontend
        $data->transform(function($inv) {
            $inv->so_number = $inv->no_order; 
            return $inv;
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===== API: DETAIL INVOICE + ITEM SO + SERIAL NUMBER =====
    public function getInvoiceDetail($id)
    {
        $invoice = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice tidak ditemukan'], 404);
        }

        // Tambahkan alias agar frontend tidak rusak
        $invoice->so_number = $invoice->no_order;

        // Cari SO berdasarkan no_order
        $so    = null;
        $items = collect();

        if ($invoice->no_order) { // <-- FIXED (dulu so_number)
            $so = DB::table('sales_orders')
                ->where('so_number', $invoice->no_order) // <-- FIXED (dulu so_number)
                ->first();

            if ($so) {
                $items = DB::table('sales_order_items')
                    ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
                    ->where('sales_order_items.sales_order_id', $so->id)
                    ->select(
                        'sales_order_items.*',
                        'gudang_products.nama_produk',
                        'gudang_products.sku'
                    )
                    ->get()
                    ->map(function ($item) {
                        // Fallback nama produk
                        if (!$item->nama_produk && $item->notes) {
                            $item->nama_produk = $item->notes;
                        }

                        // Ambil serial number jika ada
                        $item->serials = $item->product_id
                            ? DB::table('barang_masuk')
                                ->where('product_id', $item->product_id)
                                ->where('status', 'terjual')
                                ->whereNotNull('serial_number')
                                ->where('serial_number', '!=', '')
                                ->select('id', 'serial_number')
                                ->get()
                            : collect();

                        return $item;
                    });
            }
        }

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'so'      => $so,
            'items'   => $items,
        ]);
    }

    // ===== SIMPAN TRANSAKSI (Piutang / Pengeluaran) =====
    public function store(Request $request)
    {
        $request->validate([
            'tipe'         => 'required|in:piutang,pengeluaran',
            'kategori'     => 'required|string',
            'jumlah'       => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'required|string',
            'pihak_terkait'=> 'required|string', // wajib untuk piutang
            'metode_bayar' => 'nullable|in:cash,transfer,qris,kartu_kredit',
        ]);

        $kode = $this->generateKode($request->tipe);

        // Piutang default pending, pengeluaran default lunas
        $defaultStatus = $request->tipe === 'piutang' ? 'pending' : 'lunas';

        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => $request->tipe,
            'kategori'       => $request->kategori,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->referensi,
            'metode_bayar'   => $request->metode_bayar ?? 'transfer',
            'status'         => $request->status ?? $defaultStatus,
            'pihak_terkait'  => $request->pihak_terkait,
            'catatan'        => $request->catatan,
            'created_by'     => session('admin_id', 1),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => ($request->tipe === 'piutang' ? 'Piutang' : 'Pengeluaran') . ' ' . $kode . ' berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===== UPDATE STATUS PIUTANG (lunas / batal) =====
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:lunas,pending,batal']);

        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        $pesan = match($request->status) {
            'lunas' => 'Piutang ditandai LUNAS ✅ — otomatis masuk laporan pemasukan.',
            'batal' => 'Piutang dibatalkan.',
            default => 'Status diperbarui.',
        };

        return response()->json(['success' => true, 'message' => $pesan]);
    }

    // ===== UPDATE TRANSAKSI =====
    public function update(Request $request, $id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'tipe'          => $request->tipe,
            'kategori'      => $request->kategori,
            'jumlah'        => $request->jumlah,
            'tanggal'       => $request->tanggal,
            'deskripsi'     => $request->deskripsi,
            'referensi'     => $request->referensi,
            'metode_bayar'  => $request->metode_bayar,
            'status'        => $request->status,
            'pihak_terkait' => $request->pihak_terkait,
            'catatan'       => $request->catatan,
            'updated_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate!']);
    }

    // ===== HAPUS =====
    public function destroy($id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi dihapus!']);
    }

    // ===== DETAIL =====
    public function show($id)
    {
        $trx = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$trx) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== GENERATE KODE =====
    private function generateKode(string $tipe): string
    {
        $tahun  = date('Y');
        $prefix = $tipe === 'piutang' ? 'PIU' : 'EXP';
        $count  = DB::table('keuangan_transaksi')
            ->where('tipe', $tipe)
            ->whereYear('created_at', $tahun)
            ->count();

        return $prefix . '-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}