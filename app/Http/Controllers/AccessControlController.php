<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessControlController extends Controller
{
    /**
     * Display listing of Access Control products
     */
    public function index()
    {
        return view('access-control');
    }
    
    /**
     * Display specific Access Control product detail
     */
    public function show($id)
    {
        // Get product from database by id
        $product = DB::table('access_control_products')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();
        
        // Check if product exists
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Decode JSON fields (Disesuaikan dengan nama kolom database yang benar)
        $product->specifications = is_string($product->specifications) ? json_decode($product->specifications, true) : ($product->specifications ?? []);
        $product->gallery_images = is_string($product->gallery_images) ? json_decode($product->gallery_images, true) : ($product->gallery_images ?? []);
        $product->features = is_string($product->features) ? json_decode($product->features, true) : ($product->features ?? []);
        
        // Convert to array for compatibility with view
        $productArray = (array) $product;
        
        return view('access-control-detail', ['product' => $productArray]);
    }
    
    /**
     * API endpoint untuk Access Control products (digunakan oleh frontend)
     * Endpoint: /api/access-control
     */
    public function apiIndex(Request $request)
    {
        // Get products from database
        $query = DB::table('access_control_products')
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');
        
        // Filter by brand if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        // Filter by category if provided
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('category', $request->kategori); // Disesuaikan ke 'category'
        }
        
        $products = $query->get();
        
        // Transform data to format expected by frontend
        $transformedProducts = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,                 // Disesuaikan dari nama_produk
                'sku' => $product->sku,
                'brand' => $product->brand,
                'category' => $product->category,         // Disesuaikan dari kategori
                'description' => $product->description ?? '', // Disesuaikan dari deskripsi
                'sell_price' => $product->sell_price,     // Disesuaikan dari harga_jual
                'original_price' => $product->original_price,
                'main_image' => $product->main_image,     // Disesuaikan dari gambar
                'gallery_images' => $product->gallery_images, // Disesuaikan dari gallery
                'stock' => $product->stock ?? 0,          // Disesuaikan dari stok
                'is_featured' => $product->is_featured ? true : false,
                'specifications' => $product->specifications,
                'package_includes' => $product->features, // Frontend butuh package_includes, kita ambil dari kolom features
            ];
        });
        
        return response()->json([
            'success' => true,
            'products' => $transformedProducts,
            'count' => $transformedProducts->count()
        ]);
    }
    
    /**
     * Get all brands
     */
    public function getBrands()
    {
        $brands = DB::table('access_control_products')
            ->where('status', 'active')
            ->distinct()
            ->pluck('brand');
        
        return response()->json([
            'success' => true,
            'brands' => $brands
        ]);
    }
    
    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = DB::table('access_control_products')
            ->where('status', 'active')
            ->distinct()
            ->pluck('category'); // Disesuaikan dari kategori
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}