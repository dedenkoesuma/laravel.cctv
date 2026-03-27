<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaticProductController extends Controller
{
    public function index(Request $request)
    {
        $query = StaticProduct::query()->orderBy('order');

        // Filter by brand
        if ($request->filled('brand') && $request->brand !== 'Semua Brand') {
            $query->where('brand', $request->brand);
        }

        // Filter by tab
        if ($request->filled('tab') && $request->tab !== 'Semua Tab') {
            $query->where('tab', $request->tab);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate(20);

        return view('admin.static-products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.static-products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|in:hikvision,dahua,hilook,unv,hiview',
            'tab' => 'required|in:basic,cooper,pro,enterprise',
            'sku' => 'required|unique:static_products',
            'nama_produk' => 'required|string|max:255',
            'channel' => 'required|string',
            'kategori' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'specs' => 'required|array',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,out_of_stock',
            'order' => 'nullable|integer'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        StaticProduct::create($validated);

        return redirect()->route('admin.static-products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        return view('admin.static-products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = DB::table('static_products')->where('id', $id)->first();
        
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        $validated = $request->validate([
            'brand' => 'required|in:hikvision,dahua,hilook,ezviz,unv,ruijie,hiview,foreage',
            'tab_category' => 'required|in:basic,cooper,pro,enterprise,audio,fullcolor,ipcamera',
            'sku' => 'required|unique:static_products,sku,' . $id,
            'product_name' => 'required|string|max:255',
            'channel' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'specifications' => 'required|array',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,out_of_stock',
            'display_order' => 'nullable|integer'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->has('remove_image') && $request->remove_image) {
            // Remove image if checkbox is checked
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        } else {
            // Keep existing image
            unset($validated['image']);
        }

        // Convert specifications array to JSON
        $validated['specifications'] = json_encode($request->specifications);
        $validated['updated_at'] = now();

        DB::table('static_products')
            ->where('id', $id)
            ->update($validated);

        return redirect()->route('admin.static-products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(StaticProduct $staticProduct)
    {
        if ($staticProduct->image) {
            Storage::disk('public')->delete($staticProduct->image);
        }

        $staticProduct->delete();

        return redirect()->route('admin.static-products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        $products = StaticProduct::whereIn('id', $ids)->get();
        
        foreach ($products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' produk berhasil dihapus'
        ]);
    }
}