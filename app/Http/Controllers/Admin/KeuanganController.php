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

        // Pemasukan Sah = Tipe Pemasukan & Status Lunas
        $pemasukan = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // Pengeluaran = Tipe Pengeluaran & Status Lunas
        $pengeluaran = DB::table('keuangan_transaksi')
            ->where('tipe', 'pengeluaran')->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // Total Saldo Keseluruhan
        $totalPemasukan   = DB::table('keuangan_transaksi')->where('tipe', 'pemasukan')->where('status', 'lunas')->sum('jumlah');
        $totalPengeluaran = DB::table('keuangan_transaksi')->where('tipe', 'pengeluaran')->where('status', 'lunas')->sum('jumlah');

        // Piutang (Dari Staff) = Tipe Pemasukan & Status Pending
        $piutangPending = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')->where('status', 'pending')
            ->sum('jumlah');

        $piutangLunasBulanIni = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah'); // *Catatan: Ini akan sama dengan total pemasukan jika semua pemasukan dianggap pelunasan piutang

        $jumlahTransaksi = DB::table('keuangan_transaksi')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->count();

        return response()->json([
            'pemasukan_bulan'       => $pemasukan,
            'pengeluaran_bulan'     => $pengeluaran,
            'laba_bulan'            => $pemasukan - $pengeluaran,
            'saldo_total'           => $totalPemasukan - $totalPengeluaran,
            'jumlah_transaksi'      => $jumlahTransaksi,
            'piutang_pending'       => $piutangPending,
            'piutang_lunas_bulan'   => $piutangLunasBulanIni,
            'pending'               => $piutangPending,
        ]);
    }

    // ===== API: LIST TRANSAKSI =====
    public function getTransaksi(Request $request)
    {
        $query = DB::table('keuangan_transaksi');

        // Mapping filter Tipe untuk Bos agar sesuai dengan DB Staff
        if ($request->tipe) {
            if ($request->tipe === 'piutang') {
                $query->where('tipe', 'pemasukan')->where('status', 'pending');
            } else {
                $query->where('tipe', $request->tipe);
            }
        }
        
        if ($request->kategori)  $query->where('kategori', $request->kategori);
        if ($request->status)    $query->where('status', $request->status);
        if ($request->platform)  $query->where('platform', $request->platform);
        if ($request->bulan)     $query->whereMonth('tanggal', $request->bulan);
        if ($request->tahun)     $query->whereYear('tanggal', $request->tahun);
        if ($request->search)    $query->where(function ($q) use ($request) {
            $q->where('deskripsi', 'like', '%' . $request->search . '%')
              ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%')
              ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%')
              ->orWhere('no_order', 'like', '%' . $request->search . '%');
        });

        $transaksi = $query->orderByDesc('tanggal')->orderByDesc('id')->get();

        // Penyesuaian label tipe untuk frontend Bos
        $transaksi->transform(function ($t) {
            if ($t->tipe === 'pemasukan' && $t->status === 'pending') {
                $t->tipe = 'piutang'; // Ubah label saat ditampilkan
            }
            return $t;
        });

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
                'bulan'        => date('M', mktime(0, 0, 0, $m, 1)),
                'pemasukan'    => (float) $pemasukan,
                'pengeluaran'  => (float) $pengeluaran,
                'laba'         => (float) ($pemasukan - $pengeluaran),
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

    // ===== SIMPAN TRANSAKSI DARI BOS =====
    public function store(Request $request)
    {
        $request->validate([
            'tipe'         => 'required|in:pemasukan,pengeluaran,piutang',
            'kategori'     => 'required|string',
            'jumlah'       => 'required|numeric|min:1',
            'tanggal'      => 'required|date',
            'deskripsi'    => 'required|string',
            'metode_bayar' => 'required|in:cash,transfer,qris,kartu_kredit',
        ]);

        $kode   = $this->generateKode();
        // Sesuaikan dengan format DB
        $tipeDb = $request->tipe === 'piutang' ? 'pemasukan' : $request->tipe;
        $status = $request->tipe === 'piutang' ? 'pending' : ($request->status ?? 'lunas');

        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => $tipeDb,
            'kategori'       => $request->kategori,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->referensi,
            'metode_bayar'   => $request->metode_bayar,
            'status'         => $status,
            'pihak_terkait'  => $request->pihak_terkait,
            'catatan'        => $request->catatan,
            'platform'       => $request->kategori === 'Penjualan Online' ? $request->platform : null,
            'no_order'       => $request->kategori === 'Penjualan Online' ? $request->no_order : null,
            'created_by'     => session('admin_id', 1),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===== UPDATE TRANSAKSI =====
    public function update(Request $request, $id)
    {
        $tipeDb = $request->tipe === 'piutang' ? 'pemasukan' : $request->tipe;

        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'tipe'          => $tipeDb,
            'kategori'      => $request->kategori,
            'jumlah'        => $request->jumlah,
            'tanggal'       => $request->tanggal,
            'deskripsi'     => $request->deskripsi,
            'referensi'     => $request->referensi,
            'metode_bayar'  => $request->metode_bayar,
            'status'        => $request->status,
            'pihak_terkait' => $request->pihak_terkait,
            'catatan'       => $request->catatan,
            'platform'      => $request->kategori === 'Penjualan Online' ? $request->platform : null,
            'no_order'      => $request->kategori === 'Penjualan Online' ? $request->no_order : null,
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
        
        // Sesuaikan tipe untuk form edit Bos
        if ($trx->tipe === 'pemasukan' && $trx->status === 'pending') {
            $trx->tipe = 'piutang';
        }

        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== UPDATE STATUS (TANDAI LUNAS) =====
    public function updateStatus(Request $request, $id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Status transaksi berhasil diperbarui.']);
    }

    // ===== GENERATE KODE KHUSUS BOS =====
    private function generateKode(): string
    {
        $tahun = date('Y');
        $count = DB::table('keuangan_transaksi')->whereYear('created_at', $tahun)->count();
        // Pakai prefix BOS- agar tahu ini diinput dari halaman Bos
        return 'BOS-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}