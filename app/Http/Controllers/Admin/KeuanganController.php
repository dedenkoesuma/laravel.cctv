<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/KeuanganController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    // ===== DASHBOARD =====
    public function index()
    {
        return view('admin.keuangan.index');
    }

    // ===== API: SUMMARY CARDS =====
    public function getSummary(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $pemasukan = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $pengeluaran = DB::table('keuangan_transaksi')
            ->where('tipe', 'pengeluaran')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalPemasukan   = DB::table('keuangan_transaksi')->where('tipe', 'pemasukan')->where('status', 'lunas')->sum('jumlah');
        $totalPengeluaran = DB::table('keuangan_transaksi')->where('tipe', 'pengeluaran')->where('status', 'lunas')->sum('jumlah');

        $pending         = DB::table('keuangan_transaksi')->where('status', 'pending')->sum('jumlah');
        $jumlahTransaksi = DB::table('keuangan_transaksi')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        // Ringkasan penjualan toko online bulan ini
        $penjualanOnline = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->where('kategori', 'Penjualan Online')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        return response()->json([
            'pemasukan_bulan'    => $pemasukan,
            'pengeluaran_bulan'  => $pengeluaran,
            'laba_bulan'         => $pemasukan - $pengeluaran,
            'saldo_total'        => $totalPemasukan - $totalPengeluaran,
            'pending'            => $pending,
            'jumlah_transaksi'   => $jumlahTransaksi,
            'penjualan_online'   => $penjualanOnline, // tambahan info toko online
        ]);
    }

    // ===== API: LIST TRANSAKSI =====
    public function getTransaksi(Request $request)
    {
        $query = DB::table('keuangan_transaksi');

        if ($request->tipe)      $query->where('tipe', $request->tipe);
        if ($request->kategori)  $query->where('kategori', $request->kategori);
        if ($request->status)    $query->where('status', $request->status);
        if ($request->platform)  $query->where('platform', $request->platform); // filter per platform
        if ($request->bulan)     $query->whereMonth('tanggal', $request->bulan);
        if ($request->tahun)     $query->whereYear('tanggal', $request->tahun);
        if ($request->search)    $query->where(function ($q) use ($request) {
            $q->where('deskripsi', 'like', '%' . $request->search . '%')
              ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%')
              ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%')
              ->orWhere('no_order', 'like', '%' . $request->search . '%'); // bisa cari by no order
        });

        $transaksi = $query->orderByDesc('tanggal')->orderByDesc('id')->get();

        return response()->json(['success' => true, 'data' => $transaksi]);
    }

    // ===== API: CHART DATA =====
    public function getChartData(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $pemasukan = DB::table('keuangan_transaksi')
                ->where('tipe', 'pemasukan')->where('status', 'lunas')
                ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $pengeluaran = DB::table('keuangan_transaksi')
                ->where('tipe', 'pengeluaran')->where('status', 'lunas')
                ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $data[] = [
                'bulan'       => date('M', mktime(0, 0, 0, $m, 1)),
                'pemasukan'   => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
                'laba'        => (float) ($pemasukan - $pengeluaran),
            ];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===== API: KATEGORI BREAKDOWN =====
    public function getKategoriBreakdown(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $tipe  = $request->tipe  ?? 'pengeluaran';

        $data = DB::table('keuangan_transaksi')
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as jumlah_transaksi')
            ->where('tipe', $tipe)
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===== API: RINGKASAN PER PLATFORM TOKO ONLINE =====
    public function getPlatformBreakdown(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = DB::table('keuangan_transaksi')
            ->selectRaw('platform, SUM(jumlah) as total, COUNT(*) as jumlah_order')
            ->where('tipe', 'pemasukan')
            ->where('kategori', 'Penjualan Online')
            ->where('status', 'lunas')
            ->whereNotNull('platform')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('platform')
            ->orderByDesc('total')
            ->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===== SIMPAN TRANSAKSI =====
    public function store(Request $request)
    {
        $request->validate([
            'tipe'         => 'required|in:pemasukan,pengeluaran',
            'kategori'     => 'required|string',
            'jumlah'       => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'required|string',
            'metode_bayar' => 'required|in:cash,transfer,qris,kartu_kredit',
            // Validasi khusus toko online
            'platform'     => 'nullable|string|max:50',
            'no_order'     => 'nullable|string|max:100',
        ]);

        $kode = $this->generateKode();

        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => $request->tipe,
            'kategori'       => $request->kategori,
            'sub_kategori'   => $request->sub_kategori,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->referensi,
            'metode_bayar'   => $request->metode_bayar,
            'status'         => $request->status ?? 'lunas',
            'pihak_terkait'  => $request->pihak_terkait,
            'catatan'        => $request->catatan,
            // Kolom toko online (null jika bukan penjualan online)
            'platform'       => $request->kategori === 'Penjualan Online' ? $request->platform : null,
            'no_order'       => $request->kategori === 'Penjualan Online' ? $request->no_order : null,
            'created_by'     => session('admin_id', 1),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi ' . $kode . ' berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===== UPDATE TRANSAKSI =====
    public function update(Request $request, $id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'tipe'          => $request->tipe,
            'kategori'      => $request->kategori,
            'sub_kategori'  => $request->sub_kategori,
            'jumlah'        => $request->jumlah,
            'tanggal'       => $request->tanggal,
            'deskripsi'     => $request->deskripsi,
            'referensi'     => $request->referensi,
            'metode_bayar'  => $request->metode_bayar,
            'status'        => $request->status,
            'pihak_terkait' => $request->pihak_terkait,
            'catatan'       => $request->catatan,
            // Kolom toko online
            'platform'      => $request->kategori === 'Penjualan Online' ? $request->platform : null,
            'no_order'      => $request->kategori === 'Penjualan Online' ? $request->no_order : null,
            'updated_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate!']);
    }

    // ===== HAPUS TRANSAKSI =====
    public function destroy($id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi dihapus!']);
    }

    // ===== DETAIL TRANSAKSI =====
    public function show($id)
    {
        $trx = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$trx) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== GENERATE KODE =====
    private function generateKode(): string
    {
        $tahun = date('Y');
        $count = DB::table('keuangan_transaksi')->whereYear('created_at', $tahun)->count();
        return 'TRX-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}