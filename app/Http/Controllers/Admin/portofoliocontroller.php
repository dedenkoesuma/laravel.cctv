<?php
// ============================================
// app/Http/Controllers/Admin/PortofolioController.php
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use App\Models\GaleriPortofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    public function index()
    {
        $portofolio = Portofolio::latest()->paginate(10);
        return view('admin.portofolio.index', compact('portofolio'));
    }

    public function create()
    {
        return view('admin.portofolio.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_project' => 'required|max:200',
            'klien' => 'nullable|max:100',
            'lokasi' => 'nullable|max:200',
            'tanggal_project' => 'nullable|date',
            'deskripsi' => 'nullable',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori_project' => 'nullable|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar_utama')) {
            $path = $request->file('gambar_utama')->store('portofolio', 'public');
            $validated['gambar_utama'] = $path;
        }

        $portofolio = Portofolio::create($validated);

        // Handle galeri
        if ($request->hasFile('galeri')) {
            $urutan = 1;
            foreach ($request->file('galeri') as $image) {
                $path = $image->store('portofolio/galeri', 'public');
                GaleriPortofolio::create([
                    'portofolio_id' => $portofolio->id,
                    'gambar' => $path,
                    'urutan' => $urutan++,
                ]);
            }
        }

        return redirect()->route('admin.portofolio.index')
            ->with('success', 'Portofolio berhasil ditambahkan!');
    }

    public function edit(Portofolio $portofolio)
    {
        $portofolio->load('galeri');
        return view('admin.portofolio.edit', compact('portofolio'));
    }

    public function update(Request $request, Portofolio $portofolio)
    {
        $validated = $request->validate([
            'nama_project' => 'required|max:200',
            'klien' => 'nullable|max:100',
            'lokasi' => 'nullable|max:200',
            'tanggal_project' => 'nullable|date',
            'deskripsi' => 'nullable',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori_project' => 'nullable|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar_utama')) {
            if ($portofolio->gambar_utama) {
                Storage::disk('public')->delete($portofolio->gambar_utama);
            }
            $path = $request->file('gambar_utama')->store('portofolio', 'public');
            $validated['gambar_utama'] = $path;
        }

        $portofolio->update($validated);

        // Handle new galeri
        if ($request->hasFile('galeri')) {
            $urutan = $portofolio->galeri()->count() + 1;
            foreach ($request->file('galeri') as $image) {
                $path = $image->store('portofolio/galeri', 'public');
                GaleriPortofolio::create([
                    'portofolio_id' => $portofolio->id,
                    'gambar' => $path,
                    'urutan' => $urutan++,
                ]);
            }
        }

        return redirect()->route('admin.portofolio.index')
            ->with('success', 'Portofolio berhasil diupdate!');
    }

    public function destroy(Portofolio $portofolio)
    {
        if ($portofolio->gambar_utama) {
            Storage::disk('public')->delete($portofolio->gambar_utama);
        }
        foreach ($portofolio->galeri as $g) {
            Storage::disk('public')->delete($g->gambar);
        }
        $portofolio->delete();

        return redirect()->route('admin.portofolio.index')
            ->with('success', 'Portofolio berhasil dihapus!');
    }

    public function deleteGaleri($id)
    {
        $galeri = GaleriPortofolio::findOrFail($id);
        Storage::disk('public')->delete($galeri->gambar);
        $galeri->delete();
        return response()->json(['success' => true]);
    }

    public function toggleStatus(Portofolio $portofolio)
    {
        $portofolio->update(['is_active' => !$portofolio->is_active]);
        return response()->json(['success' => true, 'is_active' => $portofolio->is_active]);
    }
}
