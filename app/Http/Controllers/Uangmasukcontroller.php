<?php

namespace App\Http\Controllers;

use App\Models\UangKas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = UangKas::jenis('masuk')->latest('tanggal');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $transaksi = $query->paginate(10)->withQueryString();

        $summary = [
            'bulan_ini' => UangKas::jenis('masuk')->bulanIni()->sum('jumlah'),
            'hari_ini'  => UangKas::jenis('masuk')->hariIni()->sum('jumlah'),
            'jumlah_transaksi' => UangKas::jenis('masuk')->bulanIni()->count(),
        ];

        $kategoriList = UangKas::KATEGORI_MASUK;

        return view('uang-masuk.index', compact('transaksi', 'summary', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = UangKas::KATEGORI_MASUK;

        return view('uang-masuk.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori'    => 'required|string|max:100',
            'keterangan'  => 'required|string|max:191',
            'jumlah'      => 'required|integer|min:1',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $validated['jenis'] = 'masuk';

        UangKas::create($validated);

        return redirect()
            ->route('uang-masuk.index')
            ->with('success', 'Transaksi uang masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi = UangKas::jenis('masuk')->findOrFail($id);

        if ($transaksi->otomatis) {
            return redirect()
                ->route('uang-masuk.index')
                ->with('error', 'Transaksi ini dibuat otomatis dari Pesanan Online. Edit datanya lewat halaman Pesanan Online.');
        }

        $kategoriList = UangKas::KATEGORI_MASUK;

        return view('uang-masuk.edit', compact('transaksi', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = UangKas::jenis('masuk')->findOrFail($id);

        if ($transaksi->otomatis) {
            return redirect()
                ->route('uang-masuk.index')
                ->with('error', 'Transaksi ini dibuat otomatis dari Pesanan Online dan tidak bisa diedit manual.');
        }

        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori'    => 'required|string|max:100',
            'keterangan'  => 'required|string|max:191',
            'jumlah'      => 'required|integer|min:1',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $transaksi->update($validated);

        return redirect()
            ->route('uang-masuk.index')
            ->with('success', 'Transaksi uang masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = UangKas::jenis('masuk')->findOrFail($id);

        if ($transaksi->otomatis) {
            return back()->with('error', 'Transaksi ini dibuat otomatis dari Pesanan Online. Batalkan pesanannya untuk menghapus entry ini.');
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}