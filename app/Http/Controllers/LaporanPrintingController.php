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
            'total_pesanan' => $pesananOffline->count(),
            'total_selesai' => $pesananOfflineSelesai->count(),
            // BUG FIX: Ubah 'total' menjadi nama kolom database kamu yang sebenarnya ('total_harga')
            'total_omzet'   => $pesananOfflineSelesai->sum('total'),
        ];

        // --- PROSES PESANAN ONLINE ---
        $pesanan = $pesananQuery->get();
        $pesananSelesai = $pesanan->filter(function ($item) {
            return strtolower($item->status) === 'selesai';
        });

        $ringkasanPesanan = [
            'total_pesanan' => $pesanan->count(),
            'total_selesai' => $pesananSelesai->count(),
            'total_proses'  => $pesanan->where('status', 'Proses')->count(),
            'total_batal'   => $pesanan->where('status', 'Dibatalkan')->count(),
            'total_omzet'   => $pesananSelesai->sum('total'), // Asumsi di tabel online namanya 'total'
            'per_platform'  => $pesananSelesai->groupBy('platform')->map(fn ($g) => [
                'jumlah' => $g->count(),
                'omzet'  => $g->sum('total'),
            ]),
        ];

        $uangMasuk  = $uangMasukQuery->get();
        $uangKeluar = $uangKeluarQuery->get();

        $totalMasuk  = $uangMasuk->sum('jumlah');
        $totalKeluar = $uangKeluar->sum('jumlah');

        return [
            'pesananOffline'   => $pesananOffline,
            'ringkasanOffline' => $ringkasanOffline,
            'pesanan'          => $pesanan,
            'ringkasanPesanan' => $ringkasanPesanan,
            'uangMasuk'        => $uangMasuk,
            'uangKeluar'       => $uangKeluar,
            'totalMasuk'       => $totalMasuk,
            'totalKeluar'      => $totalKeluar,
            'labaRugi'         => $totalMasuk - $totalKeluar,
            'periode'          => $periode,
            'dari'             => $dari,
            'sampai'           => $sampai,
            'periodeLabel'     => $this->periodeLabel($periode, $dari, $sampai),
        ];
    }
}