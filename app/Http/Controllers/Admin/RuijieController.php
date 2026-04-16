<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuijieProduct;
use App\Models\RuijieCategory;
use App\Models\RuijiePageSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RuijieController extends Controller
{
    /**
     * Display admin dashboard for Ruijie management
     */
    public function index()
    {
        $stats = [
            'total_products' => RuijieProduct::count(),
            'active_products' => RuijieProduct::where('is_active', true)->count(),
            'total_categories' => RuijieCategory::count(),
            'featured_products' => RuijieProduct::where('is_featured', true)->count(),
        ];

        $recentProducts = RuijieProduct::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.ruijie.index', compact('stats', 'recentProducts'));
    }

    /**
     * Display page settings form
     */
    public function editSettings()
    {
        $settings = RuijiePageSettings::first();
        
        if (!$settings) {
            $settings = RuijiePageSettings::create([
                'title' => 'Ruijie Networks',
                'subtitle' => 'Solusi networking enterprise-grade',
                'products_count' => 500,
                'clients_count' => 10000,
                'satisfaction_rate' => 99,
                'is_active' => true
            ]);
        }

        return view('admin.ruijie.settings', compact('settings'));
    }

    /**
     * Update page settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'products_count' => 'nullable|integer',
            'clients_count' => 'nullable|integer',
            'satisfaction_rate' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $settings = RuijiePageSettings::first();
        
        if (!$settings) {
            $settings = RuijiePageSettings::create($validated);
        } else {
            $settings->update($validated);
        }

        return redirect()->route('admin.ruijie.settings')
            ->with('success', 'Settings updated successfully');
    }

    /**
     * Display categories management
     */
    public function categories()
    {
        $categories = RuijieCategory::withCount('products')
            ->orderBy('order')
            ->get();

        return view('admin.ruijie.categories', compact('categories'));
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:ruijie_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = RuijieCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, $id)
    {
        $category = RuijieCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:ruijie_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    /**
     * Delete category
     */
    public function deleteCategory($id)
    {
        $category = RuijieCategory::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with products'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Display products list
     */
    public function products()
    {
        $products = RuijieProduct::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = RuijieCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.ruijie.products', compact('products', 'categories'));
    }

    /**
     * Show create product form
     */
    public function createProduct()
    {
        $categories = RuijieCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.ruijie.products-create', compact('categories'));
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        // 1. Validasi Input (Menambahkan validasi stock)
        $validated = $request->validate([
            'category_id' => 'required|exists:ruijie_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // Max 5MB
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer|min:0', // Validasi stok
            'features' => 'nullable|string',     
            'specifications' => 'nullable|string',
        ]);

        // 2. Generate unique slug
        $baseSlug = !empty($validated['slug']) ? $validated['slug'] : Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        while (RuijieProduct::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // 3. Konversi Textarea menjadi Array (karena Model nge-cast jadi array/json)
        $featuresArray = [];
        if (!empty($validated['features'])) {
            // Pecah berdasarkan Enter, hapus spasi berlebih, hilangkan baris kosong
            $featuresArray = array_values(array_filter(array_map('trim', explode("\n", $validated['features']))));
        }

        $specsArray = [];
        if (!empty($validated['specifications'])) {
            $specsArray = array_values(array_filter(array_map('trim', explode("\n", $validated['specifications']))));
        }

        // 4. Siapkan Data untuk disimpan
        $data = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'stock' => $validated['stock'] ?? 0, // Ambil dari input stock
            'order' => 0, // Set default 0 karena kita hapus dari form
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'specifications' => $specsArray, 
            'features' => $featuresArray,
            'image' => null
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ruijie-products', 'public');
        }

        // Create product
        $product = RuijieProduct::create($data);

        $redirectTo = $request->input('redirect_to', 'admin');
        
        if ($redirectTo === 'admin') {
            return redirect()->route('admin.ruijie.products')
                ->with('success', 'Product "' . $product->name . '" created successfully!');
        } else {
            return redirect()->route('products.ruijie')
                ->with('success', 'Product "' . $product->name . '" created successfully!');
        }
    }

    /**
     * Show edit product form
     */
    public function editProduct($id)
    {
        $product = RuijieProduct::findOrFail($id);
        
        $categories = RuijieCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.ruijie.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, $id)
    {
        $product = RuijieProduct::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:ruijie_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:ruijie_products,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'specifications' => 'nullable|string',
        ]);

        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['stock'] = $validated['stock'] ?? 0;

        // Konversi text area ke array saat update
        if (!empty($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", $validated['features']))));
        } else {
            $validated['features'] = [];
        }

        if (!empty($validated['specifications'])) {
            $validated['specifications'] = array_values(array_filter(array_map('trim', explode("\n", $validated['specifications']))));
        } else {
            $validated['specifications'] = [];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('ruijie-products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.ruijie.products')
            ->with('success', 'Product "' . $product->name . '" updated successfully!');
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        $product = RuijieProduct::findOrFail($id);
        
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}