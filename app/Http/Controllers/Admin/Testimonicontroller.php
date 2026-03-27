<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index()
    {
        $testimoni = Testimoni::orderBy('urutan')->paginate(10);
        return view('admin.testimoni.index', compact('testimoni'));
    }

    public function create()
    {
        return view('admin.testimoni.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_klien' => 'required|max:100',
            'perusahaan' => 'nullable|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'konten_testimoni' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('testimoni', 'public');
            $validated['foto'] = $path;
        }

        Testimoni::create($validated);

        return redirect()->route('admin.testimoni.index')
            ->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit(Testimoni $testimoni)
    {
        return view('admin.testimoni.edit', compact('testimoni'));
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        $validated = $request->validate([
            'nama_klien' => 'required|max:100',
            'perusahaan' => 'nullable|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'konten_testimoni' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($testimoni->foto) {
                Storage::disk('public')->delete($testimoni->foto);
            }
            $path = $request->file('foto')->store('testimoni', 'public');
            $validated['foto'] = $path;
        }

        $testimoni->update($validated);

        return redirect()->route('admin.testimoni.index')
            ->with('success', 'Testimoni berhasil diupdate!');
    }

    public function destroy(Testimoni $testimoni)
    {
        if ($testimoni->foto) {
            Storage::disk('public')->delete($testimoni->foto);
        }
        $testimoni->delete();

        return redirect()->route('admin.testimoni.index')
            ->with('success', 'Testimoni berhasil dihapus!');
    }

    public function toggleStatus(Testimoni $testimoni)
    {
        $testimoni->update(['is_active' => !$testimoni->is_active]);
        return response()->json(['success' => true, 'is_active' => $testimoni->is_active]);
    }
}