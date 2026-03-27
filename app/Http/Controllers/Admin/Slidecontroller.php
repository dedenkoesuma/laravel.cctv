<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('urutan')->paginate(10);
        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|max:200',
            'subjudul' => 'nullable',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $filename = time() . '_slider.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('slider', $filename, 'public');
            $validated['gambar'] = $path;
        }

        Slider::create($validated);

        return redirect()->route('admin.slider.index')
            ->with('success', 'Slider berhasil ditambahkan!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'judul' => 'nullable|max:200',
            'subjudul' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }

            $image = $request->file('gambar');
            $filename = time() . '_slider.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('slider', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $slider->update($validated);

        return redirect()->route('admin.slider.index')
            ->with('success', 'Slider berhasil diupdate!');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }

        $slider->delete();

        return redirect()->route('admin.slider.index')
            ->with('success', 'Slider berhasil dihapus!');
    }

    public function toggleStatus(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $slider->is_active
        ]);
    }
}