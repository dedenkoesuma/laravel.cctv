<?php

namespace App\Http\Controllers;

use App\Models\PesananOffline;
use App\Models\PesananOnline;
use App\Models\UangKas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPrintingController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan-printing.index', $this->buildReportData($request));
    }

    public function pdf(Request $request)
    {
        $pdf = Pdf::loadView('laporan-printing.pdf', $this->buildReportData($request))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-printing-' . now()->format('Y-m-d') . '.pdf');
    }

    protected function resolvePeriode(Request $request): array
    {
        $periode = $request->get('periode', 'bulan');
        $now     = now();

        switch ($periode) {
            case 'hari':
                $dari    = $now->copy()->startOfDay();
                $sampai  = $now->copy()->endOfDay();
                break;
            case 'minggu':
                $dari    = $now->copy()->startOfWeek();
                $sampai  = $now->copy()->endOfWeek();
                break;
            case 'tahun':
                $dari    = $now->copy()->startOfYear();
                $sampai  = $now->copy()->endOfYear();
                break;
            case 'semua':
                $dari    = null;
                $sampai  = null;
                break;
            case 'custom':
                $dari   = $request->filled('dari')
                    ? Carbon::parse($request->get('dari'))->startOfDay()
                    : $now->copy()->startOfMonth();
                $sampai = $request->filled('sampai')
                    ? Carbon::parse($request->get('sampai'))->endOfDay()
                    : $now->copy()->endOfMonth();
                break;
            case 'bulan':
            default:
                $periode = 'bulan';
                $dari    = $now->copy()->startOfMonth();
                $sampai  = $now->copy()->endOfMonth();
                break;
        }

        return [$periode, $dari, $sampai];
    }

    protected function periodeLabel(string $periode, ?Carbon $dari, ?Carbon $sampai): string
    {
        if (!$dari || !$sampai) {
            return 'Semua data';
        }

        $rentang = $dari->format('d/m/Y') . ' - ' . $sampai->format('d/m/Y');

        return match ($periode) {
            'hari'   => 'Hari ini (' . $rentang . ')',
            'minggu' => 'Minggu ini (' . $rentang . ')',
            'bulan'  => 'Bulan ini (' . $rentang . ')',
            'tahun'  => 'Tahun ini (' . $rentang . ')',
            'custom' => 'Custom (' . $rentang . ')',
            default  => $rentang,
        };
    }

    protected function buildReportData(Request $request): array
    {
        [$periode, $dari, $sampai] = $this->resolvePeriode($request);

        $pesananOfflineQuery = PesananOffline::query()->orderByDesc('created_at');
        $pesananQuery        = PesananOnline::query()->orderByDesc('created_at');
        $uangMasukQuery      = UangKas::where('jenis', 'masuk')->orderByDesc('tanggal');
        $uangKeluarQuery     = UangKas::where('jenis', 'keluar')->orderByDesc('tanggal');

        if ($dari && $sampai) {
            $pesananOfflineQuery->whereBetween('created_at', [$dari, $sampai]);
            $pesananQuery->whereBetween('created_at', [$dari, $sampai]);
            $uangMasukQuery->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()]);
            $uangKeluarQuery->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()]);
        }

        // --- PROSES PESANAN OFFLINE ---
        $pesananOffline = $pesananOfflineQuery->get();
        // Gunakan filter untuk mengabaikan huruf besar/kecil (Selesai vs selesai)
        $pesananOfflineSelesai = $pesananOffline->filter(function ($item) {
            return strtolower($item->status) === 'selesai';
        });

        $ringkasanOffline = [
            'total_pesanan'     => $pesananOffline->count(),
            'total_selesai'     => $pesananOfflineSelesai->count(),
            'total_omzet'       => $pesananOfflineSelesai->sum('total'),
            'total_jasa_potong' => $pesananOffline->where('jasa_potong', true)->count(),
        ];

        // --- PROSES PESANAN ONLINE ---
        $pesanan = $pesananQuery->get();
        $pesananSelesai = $pesanan->filter(function ($item) {
            return strtolower($item->status) === 'selesai';
        });

        $ringkasanPesanan = [
            'total_pesanan'     => $pesanan->count(),
            'total_selesai'     => $pesananSelesai->count(),
            'total_proses'      => $pesanan->where('status', 'Proses')->count(),
            'total_batal'       => $pesanan->where('status', 'Dibatalkan')->count(),
            'total_omzet'       => $pesananSelesai->sum('total'),
            'total_jasa_potong' => $pesanan->where('jasa_potong', true)->count(),
            'per_platform'      => $pesananSelesai->groupBy('platform')->map(fn ($g) => [
                'jumlah' => $g->count(),
                'omzet'  => $g->sum('total'),
            ]),
        ];

        // --- PEMAKAIAN KERTAS (gabungan online + offline, exclude Dibatalkan) ---
        $semuaPesananUntukKertas = $pesanan->merge($pesananOffline)
            ->filter(function ($item) {
                return strtolower($item->status) !== 'dibatalkan';
            });

                       $pemakaianKertas = collect();

        $tambahKertas = function ($tipe, $jumlahLembar, $nilai) use (&$pemakaianKertas) {
            // Pastikan $tipe selalu string agar aman dipakai sebagai key
            if (is_array($tipe)) {
                $tipe = $tipe['tipe'] ?? ($tipe[0] ?? json_encode($tipe));
            }
            $tipe = (string) ($tipe ?: '-');

            $existing = $pemakaianKertas->get($tipe, [
                'jumlah_pesanan' => 0,
                'total_lembar'   => 0,
                'total_nilai'    => 0,
            ]);

            $existing['jumlah_pesanan']++;
            $existing['total_lembar'] += $jumlahLembar;
            $existing['total_nilai']  += $nilai;

            $pemakaianKertas->put($tipe, $existing);
        };

        foreach ($semuaPesananUntukKertas as $item) {
            $tipeData = $item->tipe_kertas;

            // Format array berisi banyak baris tipe kertas (mis. [['tipe'=>..,'jumlah'=>..], ...])
            if (is_array($tipeData) && isset($tipeData[0]) && is_array($tipeData[0])) {
                foreach ($tipeData as $baris) {
                    $tipe   = $baris['tipe'] ?? '-';
                    $jumlah = (int) ($baris['jumlah'] ?? 0);
                    $tambahKertas($tipe, $jumlah, 0);
                }
            } else {
                // Format teks tunggal atau array sederhana lainnya
                $tambahKertas($tipeData, $item->jumlah_lembar, $item->total);
            }
        }

        $pemakaianKertas = $pemakaianKertas->sortByDesc('total_lembar');

        $totalLembarKeseluruhan = $semuaPesananUntukKertas->sum('jumlah_lembar');

        $uangMasuk  = $uangMasukQuery->get();
        $uangKeluar = $uangKeluarQuery->get();

        $totalMasuk  = $uangMasuk->sum('jumlah');
        $totalKeluar = $uangKeluar->sum('jumlah');

        return [
            'pesananOffline'         => $pesananOffline,
            'ringkasanOffline'       => $ringkasanOffline,
            'pesanan'                => $pesanan,
            'ringkasanPesanan'       => $ringkasanPesanan,
            'pemakaianKertas'        => $pemakaianKertas,
            'totalLembarKeseluruhan' => $totalLembarKeseluruhan,
            'uangMasuk'              => $uangMasuk,
            'uangKeluar'             => $uangKeluar,
            'totalMasuk'             => $totalMasuk,
            'totalKeluar'            => $totalKeluar,
            'labaRugi'               => $totalMasuk - $totalKeluar,
            'periode'                => $periode,
            'dari'                   => $dari,
            'sampai'                 => $sampai,
            'periodeLabel'           => $this->periodeLabel($periode, $dari, $sampai),
        ];
    }
}