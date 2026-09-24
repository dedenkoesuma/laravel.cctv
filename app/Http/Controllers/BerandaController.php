<?php

namespace App\Http\Controllers;

use App\Models\PesananOnline;
use App\Models\PesananOffline;
use App\Models\UangKas;

class BerandaController extends Controller
{
    public function index()
    {
        // ── Pesanan (gabungan online + offline) ──
        $pesananProsesOnline   = PesananOnline::byStatus('Proses')->count();
        $pesananProsesOffline  = PesananOffline::byStatus('Proses')->count();
        $pesananSelesaiOnline  = PesananOnline::byStatus('Selesai')->count();
        $pesananSelesaiOffline = PesananOffline::byStatus('Selesai')->count();

        $totalPesanan = PesananOnline::count() + PesananOffline::count();
        $totalLembar  = PesananOnline::sum('jumlah_lembar') + PesananOffline::sum('jumlah_lembar');

        $omsetOnlineBulan  = PesananOnline::byStatus('Selesai')->whereMonth('created_at', now()->month)->sum('total');
        $omsetOfflineBulan = PesananOffline::byStatus('Selesai')->whereMonth('created_at', now()->month)->sum('total');
        $omsetBulanIni     = $omsetOnlineBulan + $omsetOfflineBulan;

        // ── Uang Kas ──
        $masukHariIni  = UangKas::where('jenis', 'masuk')->whereDate('tanggal', today())->sum('jumlah');
        $keluarHariIni = UangKas::where('jenis', 'keluar')->whereDate('tanggal', today())->sum('jumlah');

        $totalMasukBulan  = UangKas::where('jenis', 'masuk')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('jumlah');
        $totalKeluarBulan = UangKas::where('jenis', 'keluar')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->sum('jumlah');

        $jumlahMasuk  = UangKas::where('jenis', 'masuk')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $jumlahKeluar = UangKas::where('jenis', 'keluar')->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();

        // ── Feed aktivitas kas terbaru (10 terakhir, gabungan masuk & keluar) ──
        $aktivitasKas = UangKas::latest('tanggal')
            ->take(10)
            ->get()
            ->map(fn ($k) => (object) [
                'tanggal'    => $k->tanggal,
                'keterangan' => $k->keterangan,
                'kategori'   => $k->kategori,
                'jumlah'     => $k->jumlah,
                'tipe'       => $k->jenis, // 'masuk' atau 'keluar'
            ]);

        return view('beranda', [
            'omsetBulanIni'    => $omsetBulanIni,
            'labaBersih'       => $totalMasukBulan - $totalKeluarBulan,
            'masukHariIni'     => $masukHariIni,
            'keluarHariIni'    => $keluarHariIni,
            'pesananProses'    => $pesananProsesOnline + $pesananProsesOffline,
            'pesananSelesai'   => $pesananSelesaiOnline + $pesananSelesaiOffline,
            'totalPesanan'     => $totalPesanan,
            'totalLembar'      => $totalLembar,
            'totalMasukBulan'  => $totalMasukBulan,
            'totalKeluarBulan' => $totalKeluarBulan,
            'jumlahMasuk'      => $jumlahMasuk,
            'jumlahKeluar'     => $jumlahKeluar,
            'aktivitasKas'     => $aktivitasKas,
        ]);
    }
}