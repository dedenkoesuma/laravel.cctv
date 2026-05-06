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

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
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
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'product' => $product]);
    }

    // === CREATE NEW PRODUCT ===
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan key yang dikirim dari Javascript
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'sku' => 'required|string|unique:access_control_products,sku',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'is_featured' => 'nullable', 
            'status' => 'required|in:active,inactive',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('main_image')) {
            $imagePath = $request->file('main_image')->store('access-control', 'public');
        }

        $productId = DB::table('access_control_products')->insertGetId([
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'sku' => $validated['sku'],
            'description' => $validated['description'] ?? null,
            'cost_price' => $validated['cost_price'],
            'sell_price' => $validated['sell_price'],
            'original_price' => $validated['original_price'] ?? null,
            'stock' => $validated['stock'],
            'category' => $validated['category'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $request->is_featured ? 1 : 0, // Ambil dari request langsung
            'main_image' => $imagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => DB::table('access_control_products')->find($productId)
        ]);
    }

    // === UPDATE PRODUCT ===
    public function update(Request $request, $id)
    {
        $product = DB::table('access_control_products')->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        // Validasi disesuaikan dengan key yang dikirim dari Javascript
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'brand' => 'nullable|string',
            'sku' => 'nullable|string|unique:access_control_products,sku,' . $id,
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [];

        // Masukkan data yang ada di request ke array update
        $fields = ['name', 'brand', 'sku', 'description', 'cost_price', 'sell_price', 'original_price', 'stock', 'category', 'status'];
        
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        // Handle is_featured (bisa berupa string '1' atau '0')
        if ($request->has('is_featured')) {
            $updateData['is_featured'] = $request->is_featured ? 1 : 0;
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $updateData['main_image'] = $request->file('main_image')->store('access-control', 'public');
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
    public function destroy($id)
    {
        $product = DB::table('access_control_products')->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

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

        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    // Get statistics
    public function getStatistics()
    {
        $total = DB::table('access_control_products')->count();
        $active = DB::table('access_control_products')->where('status', 'active')->count();
        $featured = DB::table('access_control_products')->where('is_featured', 1)->count();
        $brands = DB::table('access_control_products')->distinct('brand')->count('brand');
        
        // Menggunakan nama kolom yang benar: 'stock'
        $lowStock = DB::table('access_control_products')->where('stock', '<', 10)->where('status', 'active')->count();

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