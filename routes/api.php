<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =====================================
// ⭐ NEW: GUDANG API ROUTES
// =====================================
Route::prefix('admin/gudang')->group(function () {
    // Produk & stok
    Route::get('/products',     [App\Http\Controllers\Admin\GudangController::class, 'getProducts']);
    Route::get('/categories',   [App\Http\Controllers\Admin\GudangController::class, 'getCategories']);
    Route::get('/history/{id}', [App\Http\Controllers\Admin\GudangController::class, 'getHistory']);
    // Barang masuk
    Route::post('/barang-masuk',        [App\Http\Controllers\Admin\GudangController::class, 'storeBarangMasuk']);
    Route::patch('/barang-masuk/{id}',  [App\Http\Controllers\Admin\GudangController::class, 'updateStatus']);
    Route::delete('/barang-masuk/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangMasuk']);
    // ⭐ NEW: Barang keluar
    Route::post('/barang-keluar',        [App\Http\Controllers\Admin\GudangController::class, 'storeBarangKeluar']);
    Route::delete('/barang-keluar/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangKeluar']);
});