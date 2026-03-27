<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminAccessControlController extends Controller
{
    // Admin page view
    public function index()
    {
        return view('admin.access-control');
    }

    // Get all access control products for admin
    public function getProducts(Request $request)
    {
        $query = DB::table('access_control');

        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    // Get single product
    public function show($id)
    {
        $product = DB::table('access_control')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    // Create new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'brand' => 'required|string',
            'sku' => 'required|string|unique:access_control,sku',
            'deskripsi' => 'nullable|string',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori' => 'nullable|string',
            'specifications' => 'nullable|array',
            'package_includes' => 'nullable|array',
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle main image upload
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('access-control', 'public');
        }

        // Handle gallery images
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if (count($galleryPaths) < 5) { // Max 5 images
                    $galleryPaths[] = $file->store('access-control/gallery', 'public');
                }
            }
        }

        $productId = DB::table('access_control')->insertGetId([
            'nama_produk' => $validated['nama_produk'],
            'brand' => $validated['brand'],
            'sku' => $validated['sku'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_modal' => $validated['harga_modal'],
            'harga_jual' => $validated['harga_jual'],
            'original_price' => $validated['original_price'] ?? null,
            'gambar' => $imagePath,
            'gallery' => !empty($galleryPaths) ? json_encode($galleryPaths) : null,
            'specifications' => isset($validated['specifications']) ? json_encode($validated['specifications']) : null,
            'package_includes' => isset($validated['package_includes']) ? json_encode($validated['package_includes']) : null,
            'stok' => $validated['stok'],
            'kategori' => $validated['kategori'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => DB::table('access_control')->find($productId)
        ]);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = DB::table('access_control')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validated = $request->validate([
            'nama_produk' => 'nullable|string|max:255',
            'brand' => 'nullable|string',
            'sku' => 'nullable|string|unique:access_control,sku,' . $id,
            'deskripsi' => 'nullable|string',
            'harga_modal' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'kategori' => 'nullable|string',
            'specifications' => 'nullable|array',
            'package_includes' => 'nullable|array',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = [];

        foreach (['nama_produk', 'brand', 'sku', 'deskripsi', 'harga_modal', 'harga_jual', 'original_price', 'stok', 'kategori', 'status'] as $field) {
            if (isset($validated[$field])) {
                $updateData[$field] = $validated[$field];
            }
        }

        if (isset($validated['is_featured'])) {
            $updateData['is_featured'] = $validated['is_featured'];
        }

        if (isset($validated['specifications'])) {
            $updateData['specifications'] = json_encode($validated['specifications']);
        }

        if (isset($validated['package_includes'])) {
            $updateData['package_includes'] = json_encode($validated['package_includes']);
        }

        // Handle main image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $updateData['gambar'] = $request->file('gambar')->store('access-control', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($product->gallery) {
                $oldGallery = json_decode($product->gallery, true);
                foreach ($oldGallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                if (count($galleryPaths) < 5) {
                    $galleryPaths[] = $file->store('access-control/gallery', 'public');
                }
            }
            $updateData['gallery'] = json_encode($galleryPaths);
        }

        $updateData['updated_at'] = now();

        DB::table('access_control')->where('id', $id)->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => DB::table('access_control')->find($id)
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = DB::table('access_control')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Delete images
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        if ($product->gallery) {
            $gallery = json_decode($product->gallery, true);
            foreach ($gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        DB::table('access_control')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $product = DB::table('access_control')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $newStatus = $product->status === 'active' ? 'inactive' : 'active';

        DB::table('access_control')->where('id', $id)->update([
            'status' => $newStatus,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $newStatus
        ]);
    }

    // Get public access control products (for frontend)
    public function getPublicProducts(Request $request)
    {
        $query = DB::table('access_control')
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');
        
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        $products = $query->get();
        
        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    // Get statistics
    public function getStatistics()
    {
        $total = DB::table('access_control')->count();
        $active = DB::table('access_control')->where('status', 'active')->count();
        $featured = DB::table('access_control')->where('is_featured', true)->count();
        $brands = DB::table('access_control')->distinct('brand')->count('brand');
        $lowStock = DB::table('access_control')->where('stok', '<', 10)->where('status', 'active')->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $total,
                'active' => $active,
                'featured' => $featured,
                'brands' => $brands,
                'low_stock' => $lowStock
            ]
        ]);
    }
}