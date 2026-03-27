<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filter by tab category
        if ($request->has('tab_category') && $request->tab_category != '') {
            $query->where('tab_category', $request->tab_category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by SKU or product name
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('product_name', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('brand')
                          ->orderBy('tab_category')
                          ->orderBy('channel')
                          ->paginate(20);

        // Statistics
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'out_of_stock' => Product::where('stock', 0)->count()
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $brands = ['Hikvision', 'Dahua', 'HiLook', 'UNV', 'HiView'];
        $categories = ['Basic', 'Cooper', 'Pro', 'Enterprise'];
        
        return view('admin.products.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products,sku|max:255',
            'product_name' => 'required|max:255',
            'brand' => ['required', Rule::in(['Hikvision', 'Dahua', 'HiLook', 'UNV', 'HiView'])],
            'tab_category' => ['required', Rule::in(['Basic', 'Cooper', 'Pro', 'Enterprise'])],
            'description' => 'nullable',
            'channel' => 'required|integer|min:1',
            'compression' => 'nullable|max:255',
            'max_channel' => 'nullable|integer|min:1',
            'max_resolution' => 'nullable|max:255',
            'video_format' => 'nullable|max:255',
            'sata_hdd' => 'required|integer|min:1',
            'output_resolution' => 'nullable|max:255',
            'stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle boolean fields
        $validated['intelligent_search'] = $request->has('intelligent_search');
        $validated['p2p_mobile'] = $request->has('p2p_mobile');
        $validated['ai_smd'] = $request->has('ai_smd');
        $validated['face_detection'] = $request->has('face_detection');
        $validated['perimeter_protection'] = $request->has('perimeter_protection');
        $validated['ai_database'] = $request->has('ai_database');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $brands = ['Hikvision', 'Dahua', 'HiLook', 'UNV', 'HiView'];
        $categories = ['Basic', 'Cooper', 'Pro', 'Enterprise'];
        
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'product_name' => 'required|max:255',
            'brand' => ['required', Rule::in(['Hikvision', 'Dahua', 'HiLook', 'UNV', 'HiView'])],
            'tab_category' => ['required', Rule::in(['Basic', 'Cooper', 'Pro', 'Enterprise'])],
            'description' => 'nullable',
            'channel' => 'required|integer|min:1',
            'compression' => 'nullable|max:255',
            'max_channel' => 'nullable|integer|min:1',
            'max_resolution' => 'nullable|max:255',
            'video_format' => 'nullable|max:255',
            'sata_hdd' => 'required|integer|min:1',
            'output_resolution' => 'nullable|max:255',
            'stock' => 'required|integer|min:0',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle boolean fields
        $validated['intelligent_search'] = $request->has('intelligent_search');
        $validated['p2p_mobile'] = $request->has('p2p_mobile');
        $validated['ai_smd'] = $request->has('ai_smd');
        $validated['face_detection'] = $request->has('face_detection');
        $validated['perimeter_protection'] = $request->has('perimeter_protection');
        $validated['ai_database'] = $request->has('ai_database');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada produk yang dipilih');
        }

        $products = Product::whereIn('id', $ids)->get();
        
        foreach ($products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        Product::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', count($ids) . ' produk berhasil dihapus!');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada produk yang dipilih');
        }

        Product::whereIn('id', $ids)->update(['status' => $status]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', count($ids) . ' produk berhasil diupdate!');
    }

    /**
     * Export products to CSV
     */
    public function export()
    {
        $products = Product::all();
        
        $filename = 'products_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'SKU', 'Product Name', 'Brand', 'Tab Category', 'Channel',
                'Max Channel', 'Max Resolution', 'Stock', 'Status'
            ]);

            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->product_name,
                    $product->brand,
                    $product->tab_category,
                    $product->channel,
                    $product->max_channel,
                    $product->max_resolution,
                    $product->stock,
                    $product->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}