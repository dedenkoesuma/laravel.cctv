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
        $query = DB::table('access_control_products');

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
        $product = DB::table('access_control_products')->find($id);

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
    // Create new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'brand' => 'required|string',
            'sku' => 'required|string|unique:access_control_products,sku',
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

        $productId = DB::table('access_control_products')->insertGetId([
            'name' => $validated['nama_produk'],
            'brand' => $validated['brand'],
            'sku' => $validated['sku'],
            'description' => $validated['deskripsi'] ?? null,
            'cost_price' => $validated['harga_modal'],
            'sell_price' => $validated['harga_jual'], // Mapping benar ke sell_price
            'original_price' => $validated['original_price'] ?? null,
            'main_image' => $imagePath, // Mapping ke main_image
            'gallery_images' => !empty($galleryPaths) ? json_encode($galleryPaths) : null, // Mapping ke gallery_images
            'specifications' => isset($validated['specifications']) ? json_encode($validated['specifications']) : null,
            'features' => isset($validated['package_includes']) ? json_encode($validated['package_includes']) : null, // Masuk ke kolom features
            'stock' => $validated['stok'],
            'category' => $validated['kategori'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => DB::table('access_control_products')->find($productId)
        ]);
    }

    // Update product
    // Update product
    public function update(Request $request, $id)
    {
        $product = DB::table('access_control_products')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validated = $request->validate([
            'nama_produk' => 'nullable|string|max:255',
            'brand' => 'nullable|string',
            'sku' => 'nullable|string|unique:access_control_products,sku,' . $id,
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

        // Mapping yang benar: 'Nama_di_DB' => 'Nama_di_Form'
        $fieldMapping = [
            'name' => 'nama_produk',
            'brand' => 'brand',
            'sku' => 'sku',
            'description' => 'deskripsi',
            'cost_price' => 'harga_modal',
            'sell_price' => 'harga_jual',
            'original_price' => 'original_price',
            'stock' => 'stok',
            'category' => 'kategori',
            'status' => 'status'
        ];

        foreach ($fieldMapping as $dbColumn => $formField) {
            if (isset($validated[$formField])) {
                $updateData[$dbColumn] = $validated[$formField];
            }
        }

        if (isset($validated['is_featured'])) {
            $updateData['is_featured'] = $validated['is_featured'];
        }

        if (isset($validated['specifications'])) {
            $updateData['specifications'] = json_encode($validated['specifications']);
        }

        if (isset($validated['package_includes'])) {
            $updateData['features'] = json_encode($validated['package_includes']); // Masuk ke kolom features
        }

        // Handle main image upload
        if ($request->hasFile('gambar')) {
            // Delete old image using the correct DB column name
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $updateData['main_image'] = $request->file('gambar')->store('access-control', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            // Delete old gallery images using the correct DB column name
            if ($product->gallery_images) {
                $oldGallery = json_decode($product->gallery_images, true);
                if(is_array($oldGallery)) {
                    foreach ($oldGallery as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                if (count($galleryPaths) < 5) {
                    $galleryPaths[] = $file->store('access-control/gallery', 'public');
                }
            }
            $updateData['gallery_images'] = json_encode($galleryPaths);
        }

        $updateData['updated_at'] = now();

        DB::table('access_control_products')->where('id', $id)->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => DB::table('access_control_products')->find($id)
        ]);
    }

    // Delete product
    // Delete product
    public function destroy($id)
    {
        $product = DB::table('access_control_products')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Delete images using correct DB column names
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        if ($product->gallery_images) {
            $gallery = json_decode($product->gallery_images, true);
            if(is_array($gallery)) {
                foreach ($gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        DB::table('access_control_products')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $product = DB::table('access_control_products')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $newStatus = $product->status === 'active' ? 'inactive' : 'active';

        DB::table('access_control_products')->where('id', $id)->update([
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
        $query = DB::table('access_control_products')
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
        $total = DB::table('access_control_products')->count();
        $active = DB::table('access_control_products')->where('status', 'active')->count();
        $featured = DB::table('access_control_products')->where('is_featured', true)->count();
        $brands = DB::table('access_control_products')->distinct('brand')->count('brand');
        $lowStock = DB::table('access_control_products')->where('stok', '<', 10)->where('status', 'active')->count();

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