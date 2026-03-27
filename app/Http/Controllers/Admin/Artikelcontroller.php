<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = Artikel::with('author');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_published', $request->status);
        }

        $artikel = $query->latest()->paginate(10);
        return view('admin.artikel.index', compact('artikel'));
    }

    public function create()
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:200',
            'konten' => 'required',
            'excerpt' => 'nullable',
            'gambar_featured' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori_artikel' => 'nullable|max:100',
            'tags' => 'nullable',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['judul']);
        $validated['author_id'] = Auth::guard('admin')->id();

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('gambar_featured')) {
            $path = $request->file('gambar_featured')->store('artikel', 'public');
            $validated['gambar_featured'] = $path;
        }

        Artikel::create($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Artikel $artikel)
    {
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validated = $request->validate([
            'judul' => 'required|max:200',
            'konten' => 'required',
            'excerpt' => 'nullable',
            'gambar_featured' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori_artikel' => 'nullable|max:100',
            'tags' => 'nullable',
            'is_published' => 'boolean',
        ]);

        if ($validated['judul'] != $artikel->judul) {
            $validated['slug'] = Str::slug($validated['judul']);
        }

        if ($validated['is_published'] && !$artikel->is_published) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('gambar_featured')) {
            if ($artikel->gambar_featured) {
                Storage::disk('public')->delete($artikel->gambar_featured);
            }
            $path = $request->file('gambar_featured')->store('artikel', 'public');
            $validated['gambar_featured'] = $path;
        }

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->gambar_featured) {
            Storage::disk('public')->delete($artikel->gambar_featured);
        }
        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    public function togglePublish(Artikel $artikel)
    {
        $isPublished = !$artikel->is_published;
        $artikel->update([
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null
        ]);
        
        return response()->json(['success' => true, 'is_published' => $isPublished]);
    }
}