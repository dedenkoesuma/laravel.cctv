<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TentangKami;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('setting_value', 'setting_key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            if ($request->hasFile($key)) {
                // Handle file upload
                $oldSetting = Setting::where('setting_key', $key)->first();
                if ($oldSetting && $oldSetting->setting_value) {
                    Storage::disk('public')->delete($oldSetting->setting_value);
                }
                
                $path = $request->file($key)->store('settings', 'public');
                Setting::set($key, $path);
            } else {
                Setting::set($key, $value);
            }
        }

        return back()->with('success', 'Settings berhasil diupdate!');
    }

    public function tentangKami()
    {
        $tentang = TentangKami::first();
        if (!$tentang) {
            $tentang = TentangKami::create([]);
        }
        return view('admin.tentang.index', compact('tentang'));
    }

    public function updateTentangKami(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|max:200',
            'konten' => 'nullable',
            'visi' => 'nullable',
            'misi' => 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tentang = TentangKami::first();
        
        if ($request->hasFile('gambar')) {
            if ($tentang->gambar) {
                Storage::disk('public')->delete($tentang->gambar);
            }
            $path = $request->file('gambar')->store('tentang', 'public');
            $validated['gambar'] = $path;
        }

        if ($tentang) {
            $tentang->update($validated);
        } else {
            TentangKami::create($validated);
        }

        return back()->with('success', 'Tentang Kami berhasil diupdate!');
    }
}