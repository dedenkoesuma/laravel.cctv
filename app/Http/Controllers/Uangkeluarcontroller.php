<?php

namespace App\Http\Controllers;

use App\Models\UangKas;
use Illuminate\Http\Request;

class UangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = UangKas::jenis('keluar')->latest('tanggal');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $transaksi = $query->paginate(10)->withQueryString();

        $summary = [
            'bulan_ini' => UangKas::jenis('keluar')->bulanIni()->sum('jumlah'),
            'hari_ini'  => UangKas::jenis('keluar')->hariIni()->sum('jumlah'),
            'jumlah_transaksi' => UangKas::jenis('keluar')->bulanIni()->count(),
        ];

        $kategoriList = UangKas::KATEGORI_KELUAR;

        return view('uang-keluar.index', compact('transaksi', 'summary', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = UangKas::KATEGORI_KELUAR;

        return view('uang-keluar.create', compact('kategoriList'));
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

        $validated['jenis'] = 'keluar';

        UangKas::create($validated);

        return redirect()
            ->route('uang-keluar.index')
            ->with('success', 'Transaksi uang keluar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi    = UangKas::jenis('keluar')->findOrFail($id);
        $kategoriList = UangKas::KATEGORI_KELUAR;

        return view('uang-keluar.edit', compact('transaksi', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = UangKas::jenis('keluar')->findOrFail($id);

        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'kategori'    => 'required|string|max:100',
            'keterangan'  => 'required|string|max:191',
            'jumlah'      => 'required|integer|min:1',
            'catatan'     => 'nullable|string|max:500',
        ]);

        $transaksi->update($validated);

        return redirect()
            ->route('uang-keluar.index')
            ->with('success', 'Transaksi uang keluar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = UangKas::jenis('keluar')->findOrFail($id);
        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}