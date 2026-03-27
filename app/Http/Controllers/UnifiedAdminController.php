<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UnifiedAdminController extends Controller
{
    /**
     * Display unified admin dashboard
     */
    public function index()
    {
        // Get statistics
        $dynamicCount = DB::table('products')->count();
        $staticCount = DB::table('static_products')->count();
        $totalCount = $dynamicCount + $staticCount;
        
        // Get recent dynamic products
        $recentDynamicProducts = DB::table('products')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard-unified-admin', compact(
            'dynamicCount',
            'staticCount',
            'totalCount',
            'recentDynamicProducts'
        ));
    }
    
    /**
     * API: Get all dynamic products
     */
    public function getDynamicProducts()
    {
        $products = DB::table('products')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }
    
    /**
     * API: Get all static products with filters
     */
    public function getStaticProducts(Request $request)
    {
        $query = DB::table('static_products');
        
        // Apply filters
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        if ($request->has('tab_category') && $request->tab_category != '') {
            $query->where('tab_category', $request->tab_category);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('brand')
            ->orderBy('tab_category')
            ->orderBy('position')
            ->get();
        
        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }
    
    /**
     * API: Get all products (combined dynamic + static)
     */
    public function getAllProducts(Request $request)
    {
        // Get dynamic products
        $dynamicQuery = DB::table('products')
            ->select(
                'id',
                'nama_produk',
                'brand',
                'sku',
                'harga_jual',
                'harga_modal',
                'stok',
                'gambar',
                DB::raw("'dynamic' as source")
            );
        
        // Apply brand filter for dynamic
        if ($request->has('brand') && $request->brand != '') {
            $dynamicQuery->where('brand', $request->brand);
        }
        
        $dynamicProducts = $dynamicQuery->get();
        
        // Get static products
        $staticQuery = DB::table('static_products')
            ->select(
                'id',
                'nama_produk',
                'brand',
                'sku',
                'harga_jual',
                'harga_modal',
                'stok',
                'gambar',
                DB::raw("'static' as source")
            );
        
        // Apply brand filter for static
        if ($request->has('brand') && $request->brand != '') {
            $staticQuery->where('brand', $request->brand);
        }
        
        $staticProducts = $staticQuery->get();
        
        // Combine both
        $allProducts = $dynamicProducts->concat($staticProducts);
        
        // Apply source filter
        if ($request->has('source') && $request->source != '') {
            $allProducts = $allProducts->where('source', $request->source)->values();
        }
        
        return response()->json([
            'success' => true,
            'products' => $allProducts,
            'count' => $allProducts->count(),
            'dynamic_count' => $dynamicProducts->count(),
            'static_count' => $staticProducts->count()
        ]);
    }
    
    /**
     * Store new dynamic product
     */
    public function storeDynamicProduct(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_modal' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = [
            'nama_produk' => $request->nama_produk,
            'brand' => $request->brand,
            'sku' => $request->sku,
            'kategori' => $request->kategori,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok ?? 0,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $imageName, 'public');
            $data['gambar'] = $path;
        }
        
        $productId = DB::table('products')->insertGetId($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added successfully',
            'product_id' => $productId
        ]);
    }
    
    /**
     * Update dynamic product
     */
    public function updateDynamicProduct(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'harga_modal' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = [
            'nama_produk' => $request->nama_produk,
            'brand' => $request->brand,
            'sku' => $request->sku,
            'kategori' => $request->kategori,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok ?? 0,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now()
        ];
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            $oldProduct = DB::table('products')->where('id', $id)->first();
            if ($oldProduct && $oldProduct->gambar) {
                Storage::disk('public')->delete($oldProduct->gambar);
            }
            
            // Upload new image
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $imageName, 'public');
            $data['gambar'] = $path;
        }
        
        DB::table('products')->where('id', $id)->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully'
        ]);
    }
    
    /**
     * Delete dynamic product
     */
    public function deleteDynamicProduct($id)
    {
        // Get product
        $product = DB::table('products')->where('id', $id)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        // Delete image
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        
        // Delete product
        DB::table('products')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
    
    /**
     * Get single dynamic product
     */
    public function showDynamicProduct($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        
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
    
    /**
     * Update static product (quick edit)
     */
    public function updateStaticProduct(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:255',
            'harga_modal' => 'sometimes|numeric|min:0',
            'harga_jual' => 'sometimes|numeric|min:0',
            'stok' => 'sometimes|integer|min:0',
            'deskripsi' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'gambar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $data = $request->only([
            'nama_produk',
            'sku',
            'harga_modal',
            'harga_jual',
            'stok',
            'deskripsi',
            'is_active'
        ]);
        
        $data['updated_at'] = now();
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            $oldProduct = DB::table('static_products')->where('id', $id)->first();
            if ($oldProduct && $oldProduct->gambar) {
                Storage::disk('public')->delete($oldProduct->gambar);
            }
            
            // Upload new image
            $image = $request->file('gambar');
            $imageName = time() . '_static_' . $image->getClientOriginalName();
            $path = $image->storeAs('static_products', $imageName, 'public');
            $data['gambar'] = $path;
        }
        
        DB::table('static_products')->where('id', $id)->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Static product updated successfully'
        ]);
    }
    
    /**
     * Toggle static product active status
     */
    public function toggleStaticProductStatus($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $newStatus = !$product->is_active;
        
        DB::table('static_products')
            ->where('id', $id)
            ->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Product status updated',
            'is_active' => $newStatus
        ]);
    }
    
    /**
     * Bulk update static products
     */
    public function bulkUpdateStaticProducts(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:static_products,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);
        
        $ids = $request->ids;
        $action = $request->action;
        
        switch ($action) {
            case 'activate':
                DB::table('static_products')
                    ->whereIn('id', $ids)
                    ->update(['is_active' => true, 'updated_at' => now()]);
                $message = count($ids) . ' products activated';
                break;
                
            case 'deactivate':
                DB::table('static_products')
                    ->whereIn('id', $ids)
                    ->update(['is_active' => false, 'updated_at' => now()]);
                $message = count($ids) . ' products deactivated';
                break;
                
            case 'delete':
                // Delete images first
                $products = DB::table('static_products')->whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    if ($product->gambar) {
                        Storage::disk('public')->delete($product->gambar);
                    }
                }
                
                // Delete products
                DB::table('static_products')->whereIn('id', $ids)->delete();
                $message = count($ids) . ' products deleted';
                break;
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    /**
     * Get dashboard statistics
     */
    public function getStatistics()
    {
        $dynamicCount = DB::table('products')->count();
        $staticCount = DB::table('static_products')->where('is_active', true)->count();
        $staticTotalCount = DB::table('static_products')->count();
        
        // Get brand distribution
        $brandDistribution = DB::table('static_products')
            ->select('brand', DB::raw('count(*) as total'))
            ->groupBy('brand')
            ->get();
        
        // Get low stock products (dynamic)
        $lowStockDynamic = DB::table('products')
            ->where('stok', '<', 5)
            ->count();
        
        // Get low stock products (static)
        $lowStockStatic = DB::table('static_products')
            ->where('stok', '<', 5)
            ->where('is_active', true)
            ->count();
        
        return response()->json([
            'success' => true,
            'statistics' => [
                'dynamic_products' => $dynamicCount,
                'static_products_active' => $staticCount,
                'static_products_total' => $staticTotalCount,
                'total_products' => $dynamicCount + $staticCount,
                'low_stock_dynamic' => $lowStockDynamic,
                'low_stock_static' => $lowStockStatic,
                'brand_distribution' => $brandDistribution
            ]
        ]);
    }
}