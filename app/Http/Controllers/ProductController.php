<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{
    // Brand configuration
    private $brands = [
        'hikvision' => [
            'name' => 'HIKVISION',
            'color' => '#DC143C',
            'colorDark' => '#8B0000',
            'description' => 'Pemimpin global dalam solusi keamanan dan pengawasan video',
        ],
        'dahua' => [
            'name' => 'Dahua',
            'color' => '#003D7A',
            'colorDark' => '#001f3f',
            'description' => 'Solusi keamanan canggih untuk melindungi properti Anda',
        ],
        'hilook' => [
            'name' => 'HiLook',
            'color' => '#8B4513',
            'colorDark' => '#654321',
            'description' => 'Solusi keamanan berkualitas dengan harga terjangkau',
        ],
        'ezviz' => [
            'name' => 'EZVIZ',
            'color' => '#4A90E2',
            'colorDark' => '#2E5C8A',
            'description' => 'Kamera WiFi pintar untuk rumah dan bisnis modern',
        ],
        'unv' => [
            'name' => 'UNV',
            'color' => '#6A0DAD',
            'colorDark' => '#4B0082',
            'description' => 'Uniview - Teknologi keamanan inovatif dan handal',
        ],
        'ruijie' => [
            'name' => 'RUIJIE',
            'color' => '#00A7E1',
            'colorDark' => '#0077A3',
            'description' => 'Solusi jaringan dan keamanan terintegrasi',
        ],
        'hiview' => [
            'name' => 'HIVIEW',
            'color' => '#8B0000',
            'colorDark' => '#5C0000',
            'description' => 'Sistem pengawasan profesional dengan teknologi terkini',
        ],
        'foreage' => [
            'name' => 'Foreage',
            'color' => '#2C5F2D',
            'colorDark' => '#1A3A1B',
            'description' => 'Solusi keamanan terpercaya untuk bisnis Anda',
        ],
    ];
    
    /**
     * Show products by brand
     */
    public function showByBrand($brand)
    {
        // Normalize brand slug
        $brandSlug = strtolower($brand);
        
        // Check if brand exists
        if ($brandSlug !== 'all' && !isset($this->brands[$brandSlug])) {
            abort(404, 'Brand tidak ditemukan');
        }
        
        // Handle "all" brands
        if ($brandSlug === 'all') {
            $dynamicProducts = DB::table('products')
                ->orderBy('created_at', 'desc')
                ->get();
                
            // Get all static products
            $allStaticProducts = DB::table('static_products')
                ->where('is_active', 1)
                ->orderBy('id')
                ->get();
                
            return view('products.products-all', [
                'brandSlug' => 'all',
                'brand' => 'all',
                'brandName' => 'Semua Brand',
                'brandData' => [
                    'name' => 'Semua Brand',
                    'color' => '#667eea',
                    'colorDark' => '#764ba2',
                    'description' => 'Lihat semua produk dari berbagai brand',
                ],
                'products' => $allStaticProducts->groupBy('tab_category'),
                'staticProducts' => $allStaticProducts->groupBy('tab_category'),
                'dynamicProducts' => $dynamicProducts,
            ]);
        }
        
        // Get brand data
        $brandData = $this->brands[$brandSlug];
        
        // Get DYNAMIC products from database (Database Products tab)
        $dynamicProducts = DB::table('products')
            ->where('brand', $brandData['name'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ Query static products dengan CASE-INSENSITIVE search
        try {
            // ✅ FIX: Gunakan LOWER() untuk case-insensitive comparison
            $allStaticProducts = DB::table('static_products')
                ->whereRaw('LOWER(brand) = ?', [strtolower($brandSlug)])
                ->where('is_active', 1)
                ->orderBy('position')
                ->orderBy('id')
                ->get();
            
            // ✅ Debug: Log jumlah produk yang ditemukan
            \Log::info("=== STATIC PRODUCTS QUERY ===");
            \Log::info("Brand Slug: {$brandSlug}");
            \Log::info("Total Products Found: " . $allStaticProducts->count());
            
            // Group by tab category - LIMIT 16 products per category
            $staticProducts = [
                'basic' => $allStaticProducts->where('tab_category', 'basic')->take(16)->values(),
                'audio' => $allStaticProducts->where('tab_category', 'audio')->take(16)->values(),
                'fullcolor' => $allStaticProducts->where('tab_category', 'fullcolor')->take(16)->values(),
                'ipcamera' => $allStaticProducts->where('tab_category', 'ipcamera')->take(16)->values(),
                'cooper' => $allStaticProducts->where('tab_category', 'cooper')->take(16)->values(),
                'pro' => $allStaticProducts->where('tab_category', 'pro')->take(16)->values(),
                'enterprise' => $allStaticProducts->where('tab_category', 'enterprise')->take(16)->values(),
            ];
            
            // Parse JSON specifications for each product
            foreach ($staticProducts as $tab => $products) {
                $staticProducts[$tab] = $products->map(function ($product) {
                    if (is_string($product->specifications ?? null)) {
                        $product->specs = json_decode($product->specifications, true) ?? [];
                        $product->specifications = json_decode($product->specifications, true) ?? [];
                    } else {
                        $product->specs = $product->specifications ?? [];
                    }
                    return $product;
                });
            }
            
            // Count products per category
            $categoryCounts = [
                'basic' => $staticProducts['basic']->count(),
                'audio' => $staticProducts['audio']->count(),
                'fullcolor' => $staticProducts['fullcolor']->count(),
                'ipcamera' => $staticProducts['ipcamera']->count(),
                'cooper' => $staticProducts['cooper']->count(),
                'pro' => $staticProducts['pro']->count(),
                'enterprise' => $staticProducts['enterprise']->count(),
            ];
            
        } catch (\Exception $e) {
            // ✅ Jika tabel static_products belum ada atau error, gunakan array kosong
            \Log::error('Static products query error: ' . $e->getMessage());
            $staticProducts = [
                'basic' => collect([]),
                'audio' => collect([]),
                'fullcolor' => collect([]),
                'ipcamera' => collect([]),
                'cooper' => collect([]),
                'pro' => collect([]),
                'enterprise' => collect([]),
            ];
            $categoryCounts = [
                'basic' => 0,
                'audio' => 0,
                'fullcolor' => 0,
                'ipcamera' => 0,
                'cooper' => 0,
                'pro' => 0,
                'enterprise' => 0,
            ];
        }
        
        // ✅ Debug log untuk troubleshooting
        \Log::info('=== Product Controller Debug ===');
        \Log::info('Brand Slug: ' . $brandSlug);
        \Log::info('Brand Name: ' . $brandData['name']);
        \Log::info('Dynamic Products: ' . $dynamicProducts->count());
        \Log::info('Static Basic: ' . $staticProducts['basic']->count());
        \Log::info('Static Audio: ' . $staticProducts['audio']->count());
        \Log::info('Static FullColor: ' . $staticProducts['fullcolor']->count());
        \Log::info('Static IPCamera: ' . $staticProducts['ipcamera']->count());
        \Log::info('Static Cooper: ' . $staticProducts['cooper']->count());
        \Log::info('Static Pro: ' . $staticProducts['pro']->count());
        \Log::info('Static Enterprise: ' . $staticProducts['enterprise']->count());
        
        // ✅ Sample data untuk debugging
        if ($staticProducts['basic']->count() > 0) {
            \Log::info('Sample Basic Product: ' . json_encode($staticProducts['basic']->first()));
        }
        
        // ✅ PENTING: Cek view mana yang akan digunakan
        $viewName = 'products.products-by-' . $brandSlug;
        
        // Jika view spesifik tidak ada, gunakan view generic
        if (!view()->exists($viewName)) {
            $viewName = 'products.show'; // Fallback ke view generic
            \Log::info('Using fallback view: products.show');
        }
        
        // Return view dengan data lengkap
        return view($viewName, [
            'brandSlug' => $brandSlug,
            'brand' => $brandSlug,
            'brandName' => $brandData['name'],
            'brandData' => [
                'name' => $brandData['name'],
                'color' => $brandData['color'],
                'colorDark' => $brandData['colorDark'] ?? $brandData['color'],
                'description' => $brandData['description'],
            ],
            // ✅ Untuk backward compatibility - kirim dengan 3 nama variable
            'products' => $staticProducts, // Untuk view baru (artifact yang baru dibuat)
            'staticProducts' => $staticProducts, // Untuk view lama (yang sudah ada)
            'dynamicProducts' => $dynamicProducts, // Dynamic products dari database
            'categoryCounts' => $categoryCounts, // Category counts
        ]);
    }
    
    /**
     * Get all brands (for API or components)
     */
    public function getAllBrands()
    {
        return response()->json([
            'success' => true,
            'data' => $this->brands
        ]);
    }
    
    /**
     * Get products for a specific brand and category via API
     */
    public function getProducts(Request $request, $brand, $category)
    {
        $brandSlug = strtolower($brand);
        
        if (!isset($this->brands[$brandSlug])) {
            return response()->json([
                'success' => false,
                'message' => 'Brand tidak ditemukan'
            ], 404);
        }
        
        try {
            $products = DB::table('static_products')
                ->whereRaw('LOWER(brand) = ?', [$brandSlug])
                ->where('tab_category', $category)
                ->where('is_active', 1)
                ->orderBy('position')
                ->orderBy('id')
                ->get();
            
            // Parse specifications
            $products = $products->map(function ($product) {
                if (is_string($product->specifications ?? null)) {
                    $product->specifications = json_decode($product->specifications, true) ?? [];
                }
                return $product;
            });
            
            return response()->json([
                'success' => true,
                'data' => $products,
                'count' => $products->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get single product detail via API
     */
    public function getProductDetail($id)
    {
        try {
            $product = DB::table('static_products')
                ->where('id', $id)
                ->where('is_active', 1)
                ->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }
            
            // Parse specifications
            if (is_string($product->specifications ?? null)) {
                $product->specifications = json_decode($product->specifications, true) ?? [];
            }
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error mengambil detail produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Debug: Check database products
     */
    public function debugProducts($brand = null)
    {
        try {
            $staticProducts = DB::table('static_products');
            
            if ($brand) {
                // ✅ FIX: Gunakan case-insensitive search
                $staticProducts->whereRaw('LOWER(brand) = ?', [strtolower($brand)]);
            }
            
            $staticProducts = $staticProducts->get();
            
            $products = DB::table('products')->get();
            
            // Parse specifications JSON
            $staticProducts = $staticProducts->map(function ($product) {
                if (is_string($product->specifications ?? null)) {
                    $product->specifications_parsed = json_decode($product->specifications, true);
                }
                return $product;
            });
            
            return response()->json([
                'success' => true,
                'dynamic_products' => [
                    'total' => $products->count(),
                    'products' => $products->take(5),
                    'brands_in_db' => $products->pluck('brand')->unique()->values(),
                ],
                'static_products' => [
                    'total' => $staticProducts->count(),
                    'by_brand' => $staticProducts->groupBy('brand')->map->count(),
                    'by_tab' => $staticProducts->groupBy('tab_category')->map->count(),
                    'sample_data' => $staticProducts->take(5),
                    'all_brands_in_db' => DB::table('static_products')->distinct()->pluck('brand'),
                ],
                'table_columns' => [
                    'static_products_columns' => DB::select("SHOW COLUMNS FROM static_products"),
                ],
                'query_test' => [
                    'brand_search' => $brand ?? 'all',
                    'basic_count' => DB::table('static_products')
                        ->whereRaw('LOWER(brand) = ?', [strtolower($brand ?? 'hikvision')])
                        ->where('tab_category', 'basic')
                        ->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Tabel static_products mungkin belum ada. Jalankan migration terlebih dahulu.'
            ], 500);
        }
    }
}