<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        // Simpan gambar ke folder storage/app/public/testimoni
        $imagePath = $request->file('image')->store('testimoni', 'public');

        Testimonial::create([
            'title'    => $request->title,
            'location' => $request->location,
            'image'    => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Testimoni & Hasil Instalasi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Hapus file gambar dari storage
        if (Storage::disk('public')->exists($testimonial->image)) {
            Storage::disk('public')->delete($testimonial->image);
        }

        // Hapus data dari database
        $testimonial->delete();

        return redirect()->back()->with('success', 'Data testimoni berhasil dihapus!');
    }
}