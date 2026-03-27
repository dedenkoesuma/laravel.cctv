<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('produk')->latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['nama_kategori']);

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $filename = time() . '_' . Str::slug($validated['nama_kategori']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('kategori', $filename, 'public');
            $validated['icon'] = $path;
        }

        Kategori::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($validated['nama_kategori'] != $kategori->nama_kategori) {
            $validated['slug'] = Str::slug($validated['nama_kategori']);
        }

        if ($request->hasFile('icon')) {
            if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
                Storage::disk('public')->delete($kategori->icon);
            }

            $image = $request->file('icon');
            $filename = time() . '_' . Str::slug($validated['nama_kategori']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('kategori', $filename, 'public');
            $validated['icon'] = $path;
        }

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produk()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk!');
        }

        if ($kategori->icon && Storage::disk('public')->exists($kategori->icon)) {
            Storage::disk('public')->delete($kategori->icon);
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function toggleStatus(Kategori $kategori)
    {
        $kategori->update(['is_active' => !$kategori->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $kategori->is_active
        ]);
    }
}
