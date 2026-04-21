<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WiFiCameraController extends Controller
{
    /**
     * Helper: decode JSON yang tahan emoji/surrogate pairs
     */
    private function safeJsonDecode($value)
    {
        if (empty($value)) return [];

        // Coba decode langsung dulu
        $result = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return is_array($result) ? $result : [];
        }

        // Bersihkan surrogate pairs yang tidak valid lalu coba lagi
        $cleaned = preg_replace('/\\\\ud[89ab][0-9a-f]{2}\\\\ud[c-f][0-9a-f]{2}/i', '', $value);
        $result = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return is_array($result) ? $result : [];
        }

        // Kalau masih gagal, hapus semua escape sequence unicode lalu coba lagi
        $cleaned = preg_replace('/\\\\u[0-9a-fA-F]{4}/', '', $value);
        $result = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return is_array($result) ? $result : [];
        }

        return [];
    }

    /**
     * Display listing of WiFi Cameras
     */
    public function index()
    {
        return view('wifi-cam');
    }

    /**
     * Display specific WiFi Camera detail
     */
    public function show($slug)
    {
        // Get product from database by slug
        $product = DB::table('wifi_cameras')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        // Check if product exists
        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }

        // Decode semua JSON fields (pakai safeJsonDecode agar tahan emoji)
        $product->specifications   = $this->safeJsonDecode($product->specifications);
        $product->package_includes = $this->safeJsonDecode($product->package_includes);
        $product->gallery_images   = $this->safeJsonDecode($product->gallery_images);
        $product->features         = $this->safeJsonDecode($product->features);

        // Build images array (main + gallery) untuk blade yang pakai $product['images']
        $allImages = [];
        if ($product->main_image) {
            $allImages[] = '/storage/' . $product->main_image;
        }
        foreach ($product->gallery_images as $img) {
            $allImages[] = '/storage/' . $img;
        }

        // Convert ke array untuk blade yang pakai $product['key']
        $productArray = (array) $product;
        $productArray['images'] = $allImages;

        // Produk serupa dari brand yang sama
        $similarProducts = DB::table('wifi_cameras')
            ->where('brand', $product->brand)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Jika tidak ada produk dari brand yang sama, ambil produk random
        if ($similarProducts->isEmpty()) {
            $similarProducts = DB::table('wifi_cameras')
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('wifi-cam-detail', [
            'wifi_cam'        => $product,       // object -> $wifi_cam->features
            'product'         => $productArray,  // array  -> $product['images']
            'similarProducts' => $similarProducts
        ]);
    }

    /**
     * API endpoint untuk WiFi Cameras (digunakan oleh frontend)
     * Endpoint: /api/wifi-cameras
     */
    public function apiIndex()
    {
        $cameras = DB::table('wifi_cameras')
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $transformedCameras = $cameras->map(function($camera) {
            return [
                'id'             => $camera->id,
                'name'           => $camera->name,
                'brand'          => $camera->brand,
                'subtitle'       => $camera->subtitle,
                'price'          => $camera->price,
                'original_price' => $camera->original_price,
                'main_image'     => $camera->main_image,
                'stock'          => $camera->stock ?? 100,
                'is_featured'    => $camera->is_featured ? true : false,
                'specifications' => $camera->specifications,
                'slug'           => $camera->slug,
            ];
        });

        return response()->json([
            'success' => true,
            'cameras' => $transformedCameras,
        ]);
    }
}