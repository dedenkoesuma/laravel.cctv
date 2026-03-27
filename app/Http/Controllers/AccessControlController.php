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
        $product = DB::table('access_control')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();
        
        // Check if product exists
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Decode JSON fields
        $product->specifications = json_decode($product->specifications, true) ?? [];
        $product->gallery = json_decode($product->gallery, true) ?? [];
        $product->package_includes = json_decode($product->package_includes, true) ?? [];
        
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
        $query = DB::table('access_control')
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');
        
        // Filter by brand if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        // Filter by category if provided
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }
        
        $products = $query->get();
        
        // Transform data to format expected by frontend
        $transformedProducts = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->nama_produk,
                'sku' => $product->sku,
                'brand' => $product->brand,
                'category' => $product->kategori,
                'description' => $product->deskripsi ?? '',
                'sell_price' => $product->harga_jual,
                'original_price' => $product->original_price,
                'main_image' => $product->gambar,
                'gallery_images' => $product->gallery,
                'stock' => $product->stok ?? 0,
                'is_featured' => $product->is_featured ? true : false,
                'specifications' => $product->specifications,
                'package_includes' => $product->package_includes,
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
        $brands = DB::table('access_control')
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
        $categories = DB::table('access_control')
            ->where('status', 'active')
            ->distinct()
            ->pluck('kategori');
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}