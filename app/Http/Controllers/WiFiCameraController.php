<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WiFiCameraController extends Controller
{
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
        
        // Decode JSON fields
        $product->specifications = json_decode($product->specifications, true) ?? [];
        $product->package_includes = json_decode($product->package_includes, true) ?? [];
        $product->gallery_images = json_decode($product->gallery_images, true) ?? [];
        
        // Convert to array for compatibility with view
        $productArray = (array) $product;
        
        return view('wifi-cam-detail', ['product' => $productArray]);
    }
    
    /**
     * API endpoint untuk WiFi Cameras (digunakan oleh frontend)
     * Endpoint: /api/wifi-cameras
     */
    public function apiIndex()
    {
        // Get cameras from database
        $cameras = DB::table('wifi_cameras')
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Transform data to format expected by frontend
        $transformedCameras = $cameras->map(function($camera) {
            return [
                'id' => $camera->id,
                'name' => $camera->name,
                'brand' => $camera->brand,
                'subtitle' => $camera->subtitle,
                'price' => $camera->price,
                'original_price' => $camera->original_price,
                'main_image' => $camera->main_image,
                'stock' => $camera->stock ?? 100,
                'is_featured' => $camera->is_featured ? true : false,
                'specifications' => $camera->specifications,
                'slug' => $camera->slug,
            ];
        });
        
        return response()->json([
            'success' => true,
            'cameras' => $transformedCameras,
        ]);
    }
}