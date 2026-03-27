<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BrandProductController extends Controller
{
    /**
     * Display products by brand
     */
    public function show($brand)
    {
        // Validasi brand name (case-insensitive)
        $validBrands = ['hikvision', 'dahua', 'hilook', 'ezviz', 'unv'];
        $brandLower = strtolower($brand);
        
        if (!in_array($brandLower, $validBrands)) {
            abort(404, 'Brand tidak ditemukan');
        }

        // Ambil semua produk berdasarkan brand yang aktif
        $products = Product::where('brand', $brandLower)
            ->where('is_active', 1)
            ->orderBy('tab_category')
            ->orderBy('position')
            ->get();

        // Group products by tab category
        $productsByTab = $products->groupBy('tab_category');

        // Hitung total produk per kategori
        $categoryCounts = [
            'basic' => $productsByTab->get('basic', collect())->count(),
            'audio' => $productsByTab->get('audio', collect())->count(),
            'full_color' => $productsByTab->get('full_color', collect())->count(),
            'ip_camera' => $productsByTab->get('ip_camera', collect())->count(),
        ];

        // Data brand untuk header
        $brandData = [
            'hikvision' => [
                'name' => 'Hikvision',
                'color' => '#E63946',
                'description' => 'Leading provider of innovative video surveillance products and solutions. Trusted by millions worldwide for security and protection.'
            ],
            'dahua' => [
                'name' => 'Dahua Technology',
                'color' => '#1B4B8C',
                'description' => 'World-leading video-centric smart IoT solution and service provider. Delivering cutting-edge technology for safer communities.'
            ],
            'hilook' => [
                'name' => 'HiLook',
                'color' => '#00A8E1',
                'description' => 'Cost-effective security solutions with professional features. Perfect for small to medium businesses.'
            ],
            'ezviz' => [
                'name' => 'EZVIZ',
                'color' => '#00B050',
                'description' => 'Smart home security cameras with AI-powered features. Easy to install and use.'
            ],
            'unv' => [
                'name' => 'Uniview',
                'color' => '#FF6B35',
                'description' => 'Advanced surveillance technology with smart features. Affordable yet powerful security solutions.'
            ],
        ];

        return view('brand-products', [
            'brand' => ucfirst($brandLower),
            'brandData' => $brandData[$brandLower],
            'products' => $products,
            'productsByTab' => $productsByTab,
            'categoryCounts' => $categoryCounts
        ]);
    }

    /**
     * Get products for a specific brand and category via API
     */
    public function getProducts(Request $request, $brand, $category)
    {
        $brandLower = strtolower($brand);
        
        $products = Product::where('brand', $brandLower)
            ->where('tab_category', $category)
            ->where('is_active', 1)
            ->orderBy('position')
            ->get();

        return response()->json($products);
    }
}