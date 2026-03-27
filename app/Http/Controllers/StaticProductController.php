<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaticProductController extends Controller
{
    /**
     * Display the static products management page
     * FIXED: Added $product = null to prevent undefined variable error
     */
    public function index(Request $request)
    {
        $query = DB::table('static_products');
        
        // Filter by brand if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        // Filter by tab_category if provided
        if ($request->has('tab_category') && $request->tab_category != '') {
            $query->where('tab_category', $request->tab_category);
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by product name or SKU
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_produk', 'like', '%' . $request->search . '%');
            });
        }
        
        // Order by position and id only (NO display_order)
        try {
            $products = $query->orderBy('position', 'asc')
                             ->orderBy('id', 'asc')
                             ->paginate(20);
        } catch (\Exception $e) {
            // If position column doesn't exist, just use id
            $products = $query->orderBy('id', 'asc')->paginate(20);
        }
        
        // Parse JSON specifications for each product (if exists)
        foreach ($products as $product) {
            if (isset($product->specifications) && is_string($product->specifications)) {
                $product->specifications = json_decode($product->specifications, true) ?? [];
            }
        }
        
        // Get statistics with error handling for different column names
        $statistics = [
            'total' => DB::table('static_products')->count(),
        ];
        
        // Try 'status' column first, then fall back to 'is_active'
        try {
            $statistics['active'] = DB::table('static_products')->where('status', 'active')->count();
            $statistics['inactive'] = DB::table('static_products')->where('status', 'inactive')->count();
            $statistics['out_of_stock'] = DB::table('static_products')->where('status', 'out_of_stock')->count();
        } catch (\Exception $e) {
            // If 'status' column doesn't exist, try 'is_active'
            try {
                $statistics['active'] = DB::table('static_products')->where('is_active', true)->count();
                $statistics['inactive'] = DB::table('static_products')->where('is_active', false)->count();
                $statistics['out_of_stock'] = 0;
            } catch (\Exception $e2) {
                // If neither exist, set to 0
                $statistics['active'] = 0;
                $statistics['inactive'] = 0;
                $statistics['out_of_stock'] = 0;
            }
        }
        
        // Get brands list - UPDATED: hilangkan ezviz, tambahkan hilook dan hiview
        $brands = ['hikvision', 'dahua', 'hilook', 'unv', 'hiview'];
        
        // Get categories list
        $categories = DB::table('static_products')
                       ->select('tab_category')
                       ->distinct()
                       ->orderBy('tab_category')
                       ->pluck('tab_category');
        
        // Initialize $product as null for the view (prevents undefined variable error)
        $product = null;
        
        return view('admin.static-products', compact('products', 'statistics', 'brands', 'categories', 'product'));
    }
    
    /**
     * Get all static products (API endpoint)
     * EXISTING METHOD - NOT MODIFIED
     */
    public function getAll(Request $request)
    {
        $query = DB::table('static_products');
        
        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }
        
        // Filter by tab category
        if ($request->has('tab_category') && $request->tab_category != '') {
            $query->where('tab_category', $request->tab_category);
        }
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $products = $query->orderBy('brand')
            ->orderBy('tab_category')
            ->orderBy('position')
            ->get();
        
        return response()->json([
            'success' => true,
            'products' => $products,
            'total' => $products->count()
        ]);
    }
    
    /**
     * Get single product by ID
     * EXISTING METHOD - NOT MODIFIED
     */
    public function show($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
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
     * Show the form for creating a new product
     * EXISTING METHOD - NOT MODIFIED
     */
    public function create()
    {
        return view('admin.static-products.create');
    }
    
    /**
     * Store a newly created product
     * EXISTING METHOD - NOT MODIFIED
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|in:hikvision,dahua,hilook,ezviz,unv,ruijie,hiview,foreage',
            'tab_category' => 'required|in:basic,cooper,pro,enterprise,audio,fullcolor,ipcamera',
            'sku' => 'required|unique:static_products,sku',
            'product_name' => 'required|string|max:255',
            'nama_produk' => 'nullable|string|max:255',
            'channel' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'gambar' => 'nullable|image|max:2048',
            'specifications' => 'nullable|array',
            'deskripsi' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'stok' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive,out_of_stock',
            'is_active' => 'nullable|boolean',
            'position' => 'nullable|integer'
        ]);

        // Handle image upload (both 'image' and 'gambar' field names)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('static_products', 'public');
        }

        // Handle specifications JSON encoding
        if (isset($validated['specifications']) && is_array($validated['specifications'])) {
            $validated['specifications'] = json_encode($validated['specifications']);
        }

        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        DB::table('static_products')->insert($validated);

        return redirect()->route('admin.static-products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }
    
    /**
     * Show the form for editing the specified product
     * EXISTING METHOD - NOT MODIFIED
     */
    public function edit($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Parse specifications if it's JSON string
        if (isset($product->specifications) && is_string($product->specifications)) {
            $product->specifications = json_decode($product->specifications, true) ?? [];
        }
        
        return view('admin.static-products.edit', compact('product'));
    }
    
    /**
     * Update product
     * MODIFIED: Allow updating product_name, price, description, and image
     * FIXED: Handle both product_name and nama_produk columns
     * FIXED: Handle numeric range for price columns
     */
    public function update(Request $request, $id)
    {
        // Validasi 4 field yang bisa diubah (termasuk gambar)
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0|max:999999999', // Max ~1 billion
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);
        
        // Check if product exists
        $product = DB::table('static_products')->where('id', $id)->first();
        if (!$product) {
            return redirect()
                ->route('admin.static-products.index')
                ->with('error', 'Produk tidak ditemukan');
        }
        
        // Prepare update data - handle both column name variations
        $updateData = [
            'updated_at' => now()
        ];
        
        // Check which columns exist and update accordingly
        $columns = DB::getSchemaBuilder()->getColumnListing('static_products');
        
        // Update product name (handle both product_name and nama_produk)
        if (in_array('product_name', $columns)) {
            $updateData['product_name'] = $validated['product_name'];
        }
        if (in_array('nama_produk', $columns)) {
            $updateData['nama_produk'] = $validated['product_name'];
        }
        
        // Update price (handle both price and harga) - with NULL check
        $priceValue = !empty($validated['price']) ? floatval($validated['price']) : null;
        
        if (in_array('price', $columns)) {
            $updateData['price'] = $priceValue;
        }
        if (in_array('harga', $columns)) {
            $updateData['harga'] = $priceValue;
        }
        if (in_array('harga_jual', $columns)) {
            $updateData['harga_jual'] = $priceValue;
        }
        
        // Update description (handle both description and deskripsi)
        if (in_array('description', $columns)) {
            $updateData['description'] = $validated['description'];
        }
        if (in_array('deskripsi', $columns)) {
            $updateData['deskripsi'] = $validated['description'];
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (isset($product->image) && $product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            if (isset($product->gambar) && $product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            
            // Update both image columns if they exist
            if (in_array('image', $columns)) {
                $updateData['image'] = $imagePath;
            }
            if (in_array('gambar', $columns)) {
                $updateData['gambar'] = $imagePath;
            }
        }
        
        // Handle image removal (checkbox)
        if ($request->has('remove_image') && $request->remove_image == '1' && !$request->hasFile('image')) {
            // Delete old image
            if (isset($product->image) && $product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            if (isset($product->gambar) && $product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            
            // Set both to null if they exist
            if (in_array('image', $columns)) {
                $updateData['image'] = null;
            }
            if (in_array('gambar', $columns)) {
                $updateData['gambar'] = null;
            }
        }
        
        // Update product
        DB::table('static_products')
            ->where('id', $id)
            ->update($updateData);
        
        return redirect()
            ->route('admin.static-products.index')
            ->with('success', 'Produk "' . $validated['product_name'] . '" berhasil diupdate!');
    }
    
    /**
     * Remove the specified product
     * EXISTING METHOD - NOT MODIFIED
     */
    public function destroy($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        // Delete image if exists (check both 'image' and 'gambar' fields)
        if (isset($product->image) && $product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        if (isset($product->gambar) && $product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        DB::table('static_products')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
    
    /**
     * Delete product image
     * EXISTING METHOD - NOT MODIFIED
     */
    public function deleteImage($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }
        
        DB::table('static_products')
            ->where('id', $id)
            ->update(['gambar' => null, 'updated_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
    
    /**
     * Toggle product active status
     * EXISTING METHOD - NOT MODIFIED
     */
    public function toggleActive($id)
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
            ->update(['is_active' => $newStatus, 'updated_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_active' => $newStatus
        ]);
    }
    
    /**
     * Toggle product status (alias for toggleActive)
     * EXISTING METHOD - NOT MODIFIED
     */
    public function toggleStatus($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        // Toggle status field if exists, otherwise toggle is_active
        if (isset($product->status)) {
            $newStatus = $product->status === 'active' ? 'inactive' : 'active';
            
            DB::table('static_products')
                ->where('id', $id)
                ->update(['status' => $newStatus, 'updated_at' => now()]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Status berhasil diubah'
            ]);
        } else {
            return $this->toggleActive($id);
        }
    }
    
    /**
     * Bulk update prices
     * EXISTING METHOD - NOT MODIFIED
     */
    public function bulkUpdatePrices(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'nullable|string',
            'tab_category' => 'nullable|string',
            'percentage' => 'required|numeric',
            'type' => 'required|in:increase,decrease'
        ]);
        
        $query = DB::table('static_products');
        
        if ($request->brand) {
            $query->where('brand', $request->brand);
        }
        
        if ($request->tab_category) {
            $query->where('tab_category', $request->tab_category);
        }
        
        $multiplier = $request->type === 'increase' 
            ? (1 + $request->percentage / 100) 
            : (1 - $request->percentage / 100);
        
        $affected = $query->update([
            'harga_jual' => DB::raw("harga_jual * {$multiplier}"),
            'harga_modal' => DB::raw("harga_modal * {$multiplier}"),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "{$affected} products updated successfully"
        ]);
    }
    
    /**
     * Bulk delete products
     * EXISTING METHOD - NOT MODIFIED
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk yang dipilih'
            ], 400);
        }
        
        $products = DB::table('static_products')->whereIn('id', $ids)->get();
        
        foreach ($products as $product) {
            if (isset($product->image) && $product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            if (isset($product->gambar) && $product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
        }
        
        DB::table('static_products')->whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' produk berhasil dihapus'
        ]);
    }
    
    /**
     * Get statistics
     * EXISTING METHOD - NOT MODIFIED
     */
    public function getStatistics()
    {
        $total = DB::table('static_products')->count();
        $active = DB::table('static_products')->where('is_active', true)->count();
        $inactive = $total - $active;
        $withImages = DB::table('static_products')->whereNotNull('gambar')->count();
        
        $byBrand = DB::table('static_products')
            ->select('brand', DB::raw('count(*) as total'))
            ->groupBy('brand')
            ->get();
        
        $byTab = DB::table('static_products')
            ->select('tab_category', DB::raw('count(*) as total'))
            ->groupBy('tab_category')
            ->get();
        
        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'with_images' => $withImages,
                'by_brand' => $byBrand,
                'by_tab' => $byTab
            ]
        ]);
    }
}