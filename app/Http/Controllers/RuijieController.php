<?php

namespace App\Http\Controllers;

use App\Models\RuijieProduct;
use App\Models\RuijieCategory;
use App\Models\RuijiePageSettings;
use Illuminate\Http\Request;

class RuijieController extends Controller
{
    /**
     * Display public Ruijie products page
     */
    public function index()
    {
        $settings = RuijiePageSettings::first();
        
        if (!$settings) {
            $settings = (object)[
                'title' => 'Ruijie Networks',
                'subtitle' => 'Solusi networking enterprise-grade dengan teknologi terkini',
                'products_count' => 500,
                'clients_count' => 10000,
                'satisfaction_rate' => 99,
            ];
        }

        // Get all active categories - Use integer 1
        $categories = RuijieCategory::where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();

        // Get all active products - Use integer 1
        $products = RuijieProduct::with('category')
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('products.ruijie', compact('settings', 'categories', 'products'));
    }

    /**
     * Show single product detail
     */
    public function show($id)
    {
        $product = RuijieProduct::with('category')
            ->where('is_active', 1)
            ->findOrFail($id);

        $relatedProducts = RuijieProduct::with('category')
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.ruijie-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Get products for AJAX/API (used by JavaScript frontend)
     * Called by: /api/ruijie-products
     */
    public function getProducts(Request $request)
    {
        $query = RuijieProduct::with('category')
            ->where('is_active', 1); // Use integer 1

        // Filter by category
        if ($request->has('category') && $request->category !== 'semua' && $request->category !== '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->where('is_active', 1);
            });
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', 1);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        // Transform data to match JavaScript expectations
        $transformedProducts = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'short_description' => $product->short_description ?? null,
                
                // IMPORTANT: JavaScript checks 'category' (string) and 'status' (string)
                'category' => $product->category ? $product->category->name : null,
                'category_id' => $product->category_id,
                'category_slug' => $product->category ? $product->category->slug : null,
                
                // Price
                'price' => $product->price,
                'original_price' => $product->original_price ?? null,
                
                // Images
                'main_image' => $product->image,
                'images' => $product->images ?? [],
                
                // Features & Specs
                'features' => $product->features,
                'specifications' => $product->specifications,
                
                // Status flags
                'is_featured' => (bool)$product->is_featured,
                'is_active' => (bool)$product->is_active,
                'status' => $product->is_active ? 'active' : 'inactive', // JavaScript checks this!
                
                // Stock
                'stock' => $product->stock ?? 999,
                
                // Timestamps
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $transformedProducts,
            'count' => $transformedProducts->count()
        ]);
    }

    /**
     * Get featured products
     */
    public function getFeatured()
    {
        $products = RuijieProduct::with('category')
            ->where('is_active', 1) // FIXED: Use integer 1
            ->where('is_featured', 1) // FIXED: Use integer 1
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Get products by category slug
     */
    public function getByCategory($categorySlug)
    {
        $category = RuijieCategory::where('slug', $categorySlug)
            ->where('is_active', 1) // FIXED: Use integer 1
            ->firstOrFail();

        $products = RuijieProduct::where('category_id', $category->id)
            ->where('is_active', 1) // FIXED: Use integer 1
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'products' => $products
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = RuijieCategory::where('is_active', 1) // FIXED: Use integer 1
            ->withCount(['products' => function($query) {
                $query->where('is_active', 1); // FIXED: Count only active products
            }])
            ->orderBy('order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}