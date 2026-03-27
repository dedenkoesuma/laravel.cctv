<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WiFiCameraController;
use App\Http\Controllers\Admin\AdminWiFiCameraController;

// =====================================
// PUBLIC ROUTES (Tanpa Login)
// =====================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products by Brand - PERBAIKAN: Tambahkan nama view yang spesifik
Route::get('/products/{brand}', [ProductController::class, 'showByBrand'])->name('products.brand');

// WiFi Camera Pages
Route::get('/wifi-cam', [WiFiCameraController::class, 'index'])->name('wifi-cam');
Route::get('/wifi-cam/{slug}', [WiFiCameraController::class, 'show'])->name('wifi-cam.detail');

// Access Control Page
Route::get('/access-control', function () {
    return view('access-control');
})->name('access-control');

// About & Contact Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// =====================================
// ADMIN LOGIN PAGE (Public - untuk login)
// =====================================

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// =====================================
// DASHBOARD ADMIN (Protected by JavaScript)
// =====================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// =====================================
// ADMIN WiFi CAMERA MANAGEMENT (Protected by JavaScript)
// =====================================

// Admin Page - Manage WiFi Cameras
Route::get('/admin/wifi-cameras', [AdminWiFiCameraController::class, 'index'])->name('admin.wifi-cameras');

// API Routes for WiFi Camera Management
Route::prefix('api/admin/wifi-cameras')->name('api.admin.wifi-cameras.')->group(function () {
    Route::get('/', [AdminWiFiCameraController::class, 'getCameras'])->name('index');
    Route::post('/', [AdminWiFiCameraController::class, 'store'])->name('store');
    Route::get('/{id}', [AdminWiFiCameraController::class, 'show'])->name('show');
    Route::post('/{id}', [AdminWiFiCameraController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminWiFiCameraController::class, 'destroy'])->name('destroy');
});

// =====================================
// CONVENIENCE REDIRECTS
// =====================================

Route::redirect('/login', '/admin/login');
Route::redirect('/admin/dashboard', '/dashboard');

// =====================================
// FALLBACK (404 Page)
// =====================================

Route::fallback(function () {
    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }
    
    return response()->view('errors.minimal', [
        'code' => '404',
        'message' => 'Page Not Found',
        'description' => 'The page you are looking for does not exist.',
    ], 404);
});