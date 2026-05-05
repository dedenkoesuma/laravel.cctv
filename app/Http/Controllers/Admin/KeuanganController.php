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
    // Konstan untuk membedakan ID dari tabel staff agar tidak bentrok dengan ID tabel bos
    const FINANCE_ID_OFFSET = 1000000;

    // ===== DASHBOARD =====
    public function index()
    {
        return view('admin.keuangan.index');
    }

    // ===== HELPER: MENGGABUNGKAN KALKULASI 2 TABEL =====
    private function sumData($tipe, $status = null, $bulan = null, $tahun = null, $kategori = null)
    {
        $qBos   = DB::table('keuangan_transaksi')->where('tipe', $tipe);
        $qStaff = DB::table('finances')->where('tipe', $tipe);

        if ($status) {
            $qBos->where('status', $status);
            $qStaff->where('status', $status);
        }
        if ($bulan && $tahun) {
            $qBos->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $qStaff->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }
        if ($kategori) {
            $qBos->where('kategori', $kategori);
            $qStaff->where('kategori', $kategori);
        }

        return $qBos->sum('jumlah') + $qStaff->sum('jumlah');
    }

    // ===== API: SUMMARY CARDS =====
    public function getSummary(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $pemasukan            = $this->sumData('pemasukan', 'lunas', $bulan, $tahun);
        $pengeluaran          = $this->sumData('pengeluaran', 'lunas', $bulan, $tahun);
        $totalPemasukan       = $this->sumData('pemasukan', 'lunas');
        $totalPengeluaran     = $this->sumData('pengeluaran', 'lunas');
        
        $penjualanOnline      = $this->sumData('pemasukan', 'lunas', $bulan, $tahun, 'Penjualan Online');
        
        $piutangPending       = $this->sumData('piutang', 'pending');
        $piutangLunasBulanIni = $this->sumData('piutang', 'lunas', $bulan, $tahun);
        $piutangBulanIni      = $this->sumData('piutang', null, $bulan, $tahun);

        // Hitung pending & jumlah transaksi (gabungan)
        $pendingBos   = DB::table('keuangan_transaksi')->where('status', 'pending')->sum('jumlah');
        $pendingStaff = DB::table('finances')->where('status', 'pending')->sum('jumlah');
        
        $countBos   = DB::table('keuangan_transaksi')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count();
        $countStaff = DB::table('finances')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count();

        return response()->json([
            'pemasukan_bulan'       => $pemasukan,
            'pengeluaran_bulan'     => $pengeluaran,
            'laba_bulan'            => $pemasukan - $pengeluaran,
            'saldo_total'           => $totalPemasukan - $totalPengeluaran,
            'pending'               => $pendingBos + $pendingStaff,
            'jumlah_transaksi'      => $countBos + $countStaff,
            'penjualan_online'      => $penjualanOnline,
            'piutang_pending'       => $piutangPending,
            'piutang_lunas_bulan'   => $piutangLunasBulanIni,
            'piutang_bulan'         => $piutangBulanIni,
        ]);
    }

    // ===== API: LIST TRANSAKSI =====
    public function getTransaksi(Request $request)
    {
        // 1. Ambil data dari tabel Bos (keuangan_transaksi)
        $qBos = DB::table('keuangan_transaksi');
        $this->applyFilters($qBos, $request);
        $bosData = $qBos->get();

        // 2. Ambil data dari tabel Staff (finances)
        $qStaff = DB::table('finances');
        $this->applyFilters($qStaff, $request);
        
        $staffData = $qStaff->get()->map(function($item) {
            // Shift ID agar tidak bentrok dengan ID dari tabel bos saat edit/lihat detail
            $item->id = $item->id + self::FINANCE_ID_OFFSET; 
            
            // Beri label di kode transaksi agar bos tahu ini dari staff
            $item->kode_transaksi = $item->kode_transaksi ?? 'FIN-'.$item->id;
            return $item;
        });

        // 3. Gabungkan dan urutkan berdasarkan tanggal terbaru
        $transaksi = $bosData->concat($staffData)
            ->sortByDesc('id')
            ->sortByDesc('tanggal')
            ->values();

        return response()->json(['success' => true, 'data' => $transaksi]);
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->tipe)      $query->where('tipe', $request->tipe);
        if ($request->kategori)  $query->where('kategori', $request->kategori);
        if ($request->status)    $query->where('status', $request->status);
        if ($request->platform)  $query->where('platform', $request->platform);
        if ($request->bulan)     $query->whereMonth('tanggal', $request->bulan);
        if ($request->tahun)     $query->whereYear('tanggal', $request->tahun);
        if ($request->search)    $query->where(function ($q) use ($request) {
            $q->where('deskripsi', 'like', '%' . $request->search . '%')
              ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%')
              ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%');
        });
    }

    // ===== API: CHART DATA =====
    public function getChartData(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $data = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $pemasukan    = $this->sumData('pemasukan', 'lunas', $m, $tahun);
            $pengeluaran  = $this->sumData('pengeluaran', 'lunas', $m, $tahun);
            $piutangLunas = $this->sumData('piutang', 'lunas', $m, $tahun);

            $data[] = [
                'bulan'         => date('M', mktime(0, 0, 0, $m, 1)),
                'pemasukan'     => (float) $pemasukan,
                'pengeluaran'   => (float) $pengeluaran,
                'piutang_lunas' => (float) $piutangLunas,
                'laba'          => (float) ($pemasukan + $piutangLunas - $pengeluaran),
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

        // Gabung data dulu di memori agar bisa di-group berdasarkan kategori (menghindari error UNION SQL)
        $qBos   = DB::table('keuangan_transaksi')->where('tipe', $tipe)->where('status', 'lunas')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $qStaff = DB::table('finances')->where('tipe', $tipe)->where('status', 'lunas')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        $merged = $qBos->concat($qStaff);
        
        $data = $merged->groupBy('kategori')->map(function($row, $key) {
            return [
                'kategori'         => $key,
                'total'            => $row->sum('jumlah'),
                'jumlah_transaksi' => $row->count()
            ];
        })->sortByDesc('total')->values();

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ===== SIMPAN TRANSAKSI (HANYA MASUK KE TABEL BOS) =====
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

        $kode = $this->generateKode();

        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => $request->tipe,
            'kategori'       => $request->kategori,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->referensi,
            'metode_bayar'   => $request->metode_bayar,
            'status'         => $request->status ?? 'pending',
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
            'message' => 'Transaksi Private Bos (' . $kode . ') berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===== UPDATE TRANSAKSI =====
    public function update(Request $request, $id)
    {
        // Proteksi: Jika ID melebihi batas offset, berarti ini data dari tabel Staff
        if ($id >= self::FINANCE_ID_OFFSET) {
            return response()->json(['success' => false, 'message' => '⚠️ Ditolak: Ini adalah transaksi operasional staff. Harap ubah data ini melalui menu Staff Finance agar log tetap terjaga.'], 403);
        }

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
            'platform'      => $request->kategori === 'Penjualan Online' ? $request->platform : null,
            'no_order'      => $request->kategori === 'Penjualan Online' ? $request->no_order : null,
            'updated_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi Bos berhasil diupdate!']);
    }

    // ===== HAPUS TRANSAKSI =====
    public function destroy($id)
    {
        if ($id >= self::FINANCE_ID_OFFSET) {
            return response()->json(['success' => false, 'message' => '⚠️ Ditolak: Transaksi operasional staff tidak boleh dihapus dari dashboard Bos.'], 403);
        }

        DB::table('keuangan_transaksi')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi dihapus!']);
    }

    // ===== DETAIL TRANSAKSI =====
    public function show($id)
    {
        // Deteksi dari tabel mana data ini harus ditarik
        if ($id >= self::FINANCE_ID_OFFSET) {
            $realId = $id - self::FINANCE_ID_OFFSET;
            $trx = DB::table('finances')->where('id', $realId)->first();
        } else {
            $trx = DB::table('keuangan_transaksi')->where('id', $id)->first();
        }

        if (!$trx) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        
        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== GENERATE KODE =====
    private function generateKode(): string
    {
        $tahun = date('Y');
        $count = DB::table('keuangan_transaksi')->whereYear('created_at', $tahun)->count();
        return 'BOS-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}