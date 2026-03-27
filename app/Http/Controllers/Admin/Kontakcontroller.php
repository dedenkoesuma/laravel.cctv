<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakMasuk;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function index(Request $request)
    {
        $query = KontakMasuk::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $kontak = $query->latest()->paginate(15);
        return view('admin.kontak.index', compact('kontak'));
    }

    public function show(KontakMasuk $kontak)
    {
        // Mark as read
        if ($kontak->status == 'baru') {
            $kontak->update(['status' => 'dibaca']);
        }

        return view('admin.kontak.show', compact('kontak'));
    }

    public function updateStatus(Request $request, KontakMasuk $kontak)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,dibaca,diproses,selesai',
            'catatan_admin' => 'nullable',
        ]);

        $kontak->update($validated);

        return back()->with('success', 'Status berhasil diupdate!');
    }

    public function destroy(KontakMasuk $kontak)
    {
        $kontak->delete();

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Pesan kontak berhasil dihapus!');
    }
}
