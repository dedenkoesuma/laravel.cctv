<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/LaporanController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ===== HALAMAN LAPORAN =====
    public function index()
    {
        return view('admin.finance.laporan');
    }

    // ===== API: LABA RUGI =====
    public function labaRugi(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // ─── PEMASUKAN ───────────────────────────────────────
        $pemasukan = DB::table('keuangan_transaksi')
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->where('tipe', 'pemasukan')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // Piutang lunas bulan ini = bagian pemasukan
        $piutangLunas = DB::table('keuangan_transaksi')
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->where('tipe', 'piutang')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // ─── PENGELUARAN ─────────────────────────────────────
        $pengeluaran = DB::table('keuangan_transaksi')
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->where('tipe', 'pengeluaran')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $totalPemasukan   = $pemasukan->sum('total') + $piutangLunas->sum('total');
        $totalPengeluaran = $pengeluaran->sum('total');
        $labaRugi         = $totalPemasukan - $totalPengeluaran;

        // Piutang outstanding (belum lunas)
        $piutangOutstanding = DB::table('keuangan_transaksi')
            ->where('tipe', 'piutang')
            ->where('status', 'pending')
            ->sum('jumlah');

        return response()->json([
            'success'            => true,
            'periode'            => date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)),
            'pemasukan'          => $pemasukan,
            'piutang_lunas'      => $piutangLunas,
            'pengeluaran'        => $pengeluaran,
            'total_pemasukan'    => (float) $totalPemasukan,
            'total_pengeluaran'  => (float) $totalPengeluaran,
            'laba_rugi'          => (float) $labaRugi,
            'piutang_outstanding'=> (float) $piutangOutstanding,
            'is_laba'            => $labaRugi >= 0,
        ]);
    }

    // ===== API: CASH FLOW =====
    public function cashFlow(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $data = [];
        $saldoAwal = 0;

        for ($m = 1; $m <= 12; $m++) {
            $masuk = DB::table('keuangan_transaksi')
                ->where('tipe', 'pemasukan')
                ->where('status', 'lunas')
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $piutangMasuk = DB::table('keuangan_transaksi')
                ->where('tipe', 'piutang')
                ->where('status', 'lunas')
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $keluar = DB::table('keuangan_transaksi')
                ->where('tipe', 'pengeluaran')
                ->where('status', 'lunas')
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $totalMasuk  = $masuk + $piutangMasuk;
            $netCashFlow = $totalMasuk - $keluar;
            $saldoAkhir  = $saldoAwal + $netCashFlow;

            $data[] = [
                'bulan'        => date('F', mktime(0, 0, 0, $m, 1)),
                'bulan_angka'  => $m,
                'kas_masuk'    => (float) $totalMasuk,
                'kas_keluar'   => (float) $keluar,
                'net_cashflow' => (float) $netCashFlow,
                'saldo_awal'   => (float) $saldoAwal,
                'saldo_akhir'  => (float) $saldoAkhir,
                'is_positif'   => $netCashFlow >= 0,
            ];

            $saldoAwal = $saldoAkhir;
        }

        return response()->json([
            'success' => true,
            'tahun'   => $tahun,
            'data'    => $data,
            'summary' => [
                'total_masuk'  => collect($data)->sum('kas_masuk'),
                'total_keluar' => collect($data)->sum('kas_keluar'),
                'net_total'    => collect($data)->sum('net_cashflow'),
                'saldo_akhir'  => collect($data)->last()['saldo_akhir'] ?? 0,
            ],
        ]);
    }

    // ===== EXPORT EXCEL =====
    public function exportExcel(Request $request)
    {
        $bulan  = $request->bulan  ?? date('m');
        $tahun  = $request->tahun  ?? date('Y');
        $jenis  = $request->jenis  ?? 'semua'; // semua | laba_rugi | cashflow | transaksi

        $periode = date('F_Y', mktime(0, 0, 0, $bulan, 1, $tahun));
        $filename = "Laporan_{$jenis}_{$periode}.csv";

        // Gunakan CSV sebagai fallback (tidak perlu package)
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bulan, $tahun, $jenis) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel agar bisa baca UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            $periode = date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun));

            if ($jenis === 'transaksi' || $jenis === 'semua') {
                // ── SHEET: TRANSAKSI ──
                fputcsv($file, ["LAPORAN TRANSAKSI - {$periode}"]);
                fputcsv($file, ['Kode', 'Tanggal', 'Tipe', 'Kategori', 'Deskripsi', 'Pihak Terkait', 'Metode Bayar', 'Status', 'Jumlah']);

                $transaksi = DB::table('keuangan_transaksi')
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->orderByDesc('tanggal')
                    ->get();

                foreach ($transaksi as $t) {
                    fputcsv($file, [
                        $t->kode_transaksi,
                        $t->tanggal,
                        strtoupper($t->tipe),
                        $t->kategori,
                        $t->deskripsi,
                        $t->pihak_terkait ?? '-',
                        $t->metode_bayar,
                        $t->status,
                        $t->jumlah,
                    ]);
                }
                fputcsv($file, []);
            }

            if ($jenis === 'laba_rugi' || $jenis === 'semua') {
                // ── SHEET: LABA RUGI ──
                fputcsv($file, ["LAPORAN LABA RUGI - {$periode}"]);
                fputcsv($file, []);
                fputcsv($file, ['PEMASUKAN', '', '']);
                fputcsv($file, ['Kategori', 'Jumlah']);

                $pemasukan = DB::table('keuangan_transaksi')
                    ->selectRaw('kategori, SUM(jumlah) as total')
                    ->where('tipe', 'pemasukan')->where('status', 'lunas')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->groupBy('kategori')->get();

                $piutangLunas = DB::table('keuangan_transaksi')
                    ->selectRaw('kategori, SUM(jumlah) as total')
                    ->where('tipe', 'piutang')->where('status', 'lunas')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->groupBy('kategori')->get();

                $totalPemasukan = 0;
                foreach ($pemasukan as $p) {
                    fputcsv($file, [$p->kategori, $p->total]);
                    $totalPemasukan += $p->total;
                }
                foreach ($piutangLunas as $p) {
                    fputcsv($file, [$p->kategori . ' (Piutang Lunas)', $p->total]);
                    $totalPemasukan += $p->total;
                }
                fputcsv($file, ['TOTAL PEMASUKAN', $totalPemasukan]);
                fputcsv($file, []);

                fputcsv($file, ['PENGELUARAN', '']);
                fputcsv($file, ['Kategori', 'Jumlah']);

                $pengeluaran = DB::table('keuangan_transaksi')
                    ->selectRaw('kategori, SUM(jumlah) as total')
                    ->where('tipe', 'pengeluaran')->where('status', 'lunas')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->groupBy('kategori')->get();

                $totalPengeluaran = 0;
                foreach ($pengeluaran as $p) {
                    fputcsv($file, [$p->kategori, $p->total]);
                    $totalPengeluaran += $p->total;
                }
                fputcsv($file, ['TOTAL PENGELUARAN', $totalPengeluaran]);
                fputcsv($file, []);
                fputcsv($file, ['LABA / RUGI BERSIH', $totalPemasukan - $totalPengeluaran]);
                fputcsv($file, []);
            }

            if ($jenis === 'cashflow' || $jenis === 'semua') {
                // ── SHEET: CASH FLOW ──
                fputcsv($file, ["LAPORAN CASH FLOW - TAHUN {$tahun}"]);
                fputcsv($file, ['Bulan', 'Saldo Awal', 'Kas Masuk', 'Kas Keluar', 'Net Cash Flow', 'Saldo Akhir']);

                $saldoAwal = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $masuk = DB::table('keuangan_transaksi')
                        ->where('tipe', 'pemasukan')->where('status', 'lunas')
                        ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)
                        ->sum('jumlah');
                    $piutangMasuk = DB::table('keuangan_transaksi')
                        ->where('tipe', 'piutang')->where('status', 'lunas')
                        ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)
                        ->sum('jumlah');
                    $keluar = DB::table('keuangan_transaksi')
                        ->where('tipe', 'pengeluaran')->where('status', 'lunas')
                        ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)
                        ->sum('jumlah');
                    $totalMasuk  = $masuk + $piutangMasuk;
                    $net         = $totalMasuk - $keluar;
                    $saldoAkhir  = $saldoAwal + $net;

                    fputcsv($file, [
                        date('F', mktime(0, 0, 0, $m, 1)),
                        $saldoAwal,
                        $totalMasuk,
                        $keluar,
                        $net,
                        $saldoAkhir,
                    ]);
                    $saldoAwal = $saldoAkhir;
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== EXPORT PDF (via blade print) =====
    public function exportPdf(Request $request)
    {
        $bulan  = $request->bulan ?? date('m');
        $tahun  = $request->tahun ?? date('Y');
        $jenis  = $request->jenis ?? 'laba_rugi';

        $periode = date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun));

        // Ambil data sesuai jenis
        $data = [];

        if ($jenis === 'laba_rugi') {
            $pemasukan = DB::table('keuangan_transaksi')
                ->selectRaw('kategori, SUM(jumlah) as total')
                ->where('tipe', 'pemasukan')->where('status', 'lunas')
                ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                ->groupBy('kategori')->get();

            $piutangLunas = DB::table('keuangan_transaksi')
                ->selectRaw('kategori, SUM(jumlah) as total')
                ->where('tipe', 'piutang')->where('status', 'lunas')
                ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                ->groupBy('kategori')->get();

            $pengeluaran = DB::table('keuangan_transaksi')
                ->selectRaw('kategori, SUM(jumlah) as total')
                ->where('tipe', 'pengeluaran')->where('status', 'lunas')
                ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                ->groupBy('kategori')->get();

            $totalPemasukan   = $pemasukan->sum('total') + $piutangLunas->sum('total');
            $totalPengeluaran = $pengeluaran->sum('total');

            $data = compact('pemasukan', 'piutangLunas', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran');
        }

        if ($jenis === 'cashflow') {
            $rows = [];
            $saldoAwal = 0;
            for ($m = 1; $m <= 12; $m++) {
                $masuk = DB::table('keuangan_transaksi')
                    ->where('tipe', 'pemasukan')->where('status', 'lunas')
                    ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)->sum('jumlah');
                $piutangMasuk = DB::table('keuangan_transaksi')
                    ->where('tipe', 'piutang')->where('status', 'lunas')
                    ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)->sum('jumlah');
                $keluar = DB::table('keuangan_transaksi')
                    ->where('tipe', 'pengeluaran')->where('status', 'lunas')
                    ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)->sum('jumlah');
                $totalMasuk = $masuk + $piutangMasuk;
                $net        = $totalMasuk - $keluar;
                $saldoAkhir = $saldoAwal + $net;
                $rows[]     = [
                    'bulan'        => date('F', mktime(0, 0, 0, $m, 1)),
                    'saldo_awal'   => $saldoAwal,
                    'kas_masuk'    => $totalMasuk,
                    'kas_keluar'   => $keluar,
                    'net_cashflow' => $net,
                    'saldo_akhir'  => $saldoAkhir,
                ];
                $saldoAwal = $saldoAkhir;
            }
            $data = ['rows' => $rows, 'tahun' => $tahun];
        }

        return view('admin.finance.laporan', array_merge($data, [
            'jenis'   => $jenis,
            'periode' => $periode,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ]));
    }
}