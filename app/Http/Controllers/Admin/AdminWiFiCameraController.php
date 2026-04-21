<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminWiFiCameraController extends Controller
{
    /**
     * Display the WiFi Camera admin page
     */
    public function index()
    {
        // Return the admin view for WiFi cameras
        return view('admin.wifi-cameras');
    }
    
    /**
     * Get all WiFi cameras (API endpoint for ADMIN)
     */
    public function getCameras(Request $request)
    {
        $query = DB::table('wifi_cameras');
        
        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $cameras = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'cameras' => $cameras,
            'total' => $cameras->count()
        ]);
    }
    
    /**
     * Get active WiFi cameras for PUBLIC DISPLAY (Frontend /wifi-cam page)
     * ⭐ NEW METHOD - This is what /wifi-cam page calls
     */
    public function getPublicCameras(Request $request)
    {
        $query = DB::table('wifi_cameras')
            ->where('status', 'active') // Only show active products
            ->orderBy('is_featured', 'desc') // Featured products first
            ->orderBy('created_at', 'desc'); // Then by newest
        
        // Filter by brand if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        $cameras = $query->get();
        
        return response()->json([
            'success' => true,
            'cameras' => $cameras,
            'count' => $cameras->count()
        ]);
    }
    
    /**
     * Get single camera by ID
     */
    public function show($id)
    {
        $camera = DB::table('wifi_cameras')->where('id', $id)->first();
        
        if (!$camera) {
            return response()->json([
                'success' => false,
                'message' => 'Camera not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'camera' => $camera
        ]);
    }
    
    /**
     * Store new WiFi camera
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'brand'                 => 'required|string|max:255',
            'subtitle'              => 'required|string|max:500',
            'price'                 => 'required|numeric|min:0',
            'original_price'        => 'nullable|numeric|min:0',
            'stock'                 => 'nullable|integer|min:0',
            'sku'                   => 'nullable|string|max:100',
            'status'                => 'nullable|in:active,inactive',
            'is_featured'           => 'nullable|boolean',
            'main_image'            => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'gallery_images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            // ===== SUDAH DIPERBAIKI: Hapus max:500 agar teks bisa panjang =====
            'features'              => 'nullable|array',
            'features.*'            => 'nullable|string', 
            // ==================================================================
            'specifications'        => 'nullable|array',
            'package_includes'      => 'nullable|array'
        ]);
        
        // Generate slug
        $slug = Str::slug($validated['name']) . '-' . time();
        
        // Handle main image upload
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('wifi_cameras', 'public');
        }
        
        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('wifi_cameras/gallery', 'public');
                $galleryImages[] = $path;
            }
        }
        
        // Prepare data
        $data = [
            'name'            => $validated['name'],
            'slug'            => $slug,
            'brand'           => $validated['brand'],
            'subtitle'        => $validated['subtitle'],
            'price'           => $validated['price'],
            'original_price'  => $validated['original_price'] ?? null,
            'stock'           => $validated['stock'] ?? 0,
            'sku'             => $validated['sku'] ?? null,
            'status'          => $validated['status'] ?? 'active',
            'is_featured'     => $request->has('is_featured') ? 1 : 0,
            'main_image'      => $mainImagePath,
            'gallery_images'  => !empty($galleryImages) ? json_encode($galleryImages) : null,
            // ===== TAMBAHAN =====
            'features'        => !empty($validated['features']) ? json_encode(array_values(array_filter($validated['features']))) : null,
            // ====================
            'specifications'  => !empty($validated['specifications']) ? json_encode(array_filter($validated['specifications'])) : null,
            'package_includes'=> !empty($validated['package_includes']) ? json_encode(array_filter($validated['package_includes'])) : null,
            'created_at'      => now(),
            'updated_at'      => now()
        ];
        
        $cameraId = DB::table('wifi_cameras')->insertGetId($data);
        
        return response()->json([
            'success' => true,
            'message' => 'WiFi Camera berhasil ditambahkan',
            'camera_id' => $cameraId
        ]);
    }
    
    /**
     * Update WiFi camera
     */
    public function update(Request $request, $id)
    {
        // Check if camera exists
        $camera = DB::table('wifi_cameras')->where('id', $id)->first();
        if (!$camera) {
            return response()->json([
                'success' => false,
                'message' => 'Camera not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'brand'                 => 'required|string|max:255',
            'subtitle'              => 'required|string|max:500',
            'price'                 => 'required|numeric|min:0',
            'original_price'        => 'nullable|numeric|min:0',
            'stock'                 => 'nullable|integer|min:0',
            'sku'                   => 'nullable|string|max:100',
            'status'                => 'nullable|in:active,inactive',
            'is_featured'           => 'nullable|boolean',
            'main_image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            // ===== SUDAH DIPERBAIKI: Hapus max:500 agar teks bisa panjang =====
            'features'              => 'nullable|array',
            'features.*'            => 'nullable|string',
            // ==================================================================
            'specifications'        => 'nullable|array',
            'package_includes'      => 'nullable|array'
        ]);
        
        // Prepare update data
        $data = [
            'name'            => $validated['name'],
            'brand'           => $validated['brand'],
            'subtitle'        => $validated['subtitle'],
            'price'           => $validated['price'],
            'original_price'  => $validated['original_price'] ?? null,
            'stock'           => $validated['stock'] ?? 0,
            'sku'             => $validated['sku'] ?? null,
            'status'          => $validated['status'] ?? 'active',
            'is_featured'     => $request->has('is_featured') ? 1 : 0,
            // ===== TAMBAHAN =====
            'features'        => !empty($validated['features']) ? json_encode(array_values(array_filter($validated['features']))) : null,
            // ====================
            'specifications'  => !empty($validated['specifications']) ? json_encode(array_filter($validated['specifications'])) : null,
            'package_includes'=> !empty($validated['package_includes']) ? json_encode(array_filter($validated['package_includes'])) : null,
            'updated_at'      => now()
        ];
        
        // Handle main image update
        if ($request->hasFile('main_image')) {
            // Delete old image
            if ($camera->main_image && Storage::disk('public')->exists($camera->main_image)) {
                Storage::disk('public')->delete($camera->main_image);
            }
            // Upload new image
            $data['main_image'] = $request->file('main_image')->store('wifi_cameras', 'public');
        }
        
        // Handle gallery images update
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($camera->gallery_images) {
                $oldImages = json_decode($camera->gallery_images, true);
                foreach ($oldImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            // Upload new gallery images
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('wifi_cameras/gallery', 'public');
                $galleryImages[] = $path;
            }
            $data['gallery_images'] = json_encode($galleryImages);
        }
        
        // Update camera
        DB::table('wifi_cameras')->where('id', $id)->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'WiFi Camera berhasil diupdate',
            'camera' => DB::table('wifi_cameras')->where('id', $id)->first()
        ]);
    }
    
    /**
     * Delete WiFi camera
     */
    public function destroy($id)
    {
        $camera = DB::table('wifi_cameras')->where('id', $id)->first();
        
        if (!$camera) {
            return response()->json([
                'success' => false,
                'message' => 'Camera not found'
            ], 404);
        }
        
        // Delete main image
        if ($camera->main_image && Storage::disk('public')->exists($camera->main_image)) {
            Storage::disk('public')->delete($camera->main_image);
        }
        
        // Delete gallery images
        if ($camera->gallery_images) {
            $galleryImages = json_decode($camera->gallery_images, true);
            foreach ($galleryImages as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
        
        // Delete camera record
        DB::table('wifi_cameras')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'WiFi Camera berhasil dihapus'
        ]);
    }
    
    /**
     * Toggle camera status
     */
    public function toggleStatus($id)
    {
        $camera = DB::table('wifi_cameras')->where('id', $id)->first();
        
        if (!$camera) {
            return response()->json([
                'success' => false,
                'message' => 'Camera not found'
            ], 404);
        }
        
        $newStatus = $camera->status === 'active' ? 'inactive' : 'active';
        
        DB::table('wifi_cameras')
            ->where('id', $id)
            ->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate',
            'status' => $newStatus
        ]);
    }
    
    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $total = DB::table('wifi_cameras')->count();
        $active = DB::table('wifi_cameras')->where('status', 'active')->count();
        $inactive = $total - $active;
        $featured = DB::table('wifi_cameras')->where('is_featured', true)->count();
        
        $byBrand = DB::table('wifi_cameras')
            ->select('brand', DB::raw('count(*) as total'))
            ->groupBy('brand')
            ->get();
        
        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'featured' => $featured,
                'by_brand' => $byBrand
            ]
        ]);
    }
}