<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\WiFiCameraController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\Admin\AdminWiFiCameraController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\AdminAccessControlController;
use App\Http\Controllers\Admin\RuijieController as AdminRuijieController;
use App\Http\Controllers\UnifiedAdminController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\StaticProductController;
use App\Http\Controllers\RuijieController;
use App\Models\RuijieProduct;
use App\Models\RuijiePageSettings;
use App\Models\RuijieCategory;
use App\Models\WiFiCamera; 
// ⭐ NEW: Bookkeeping Controller
use App\Http\Controllers\BookkeepingController;
// ⭐ NEW: Inventory Controller
use App\Http\Controllers\InventoryController;
// ⭐ NEW: Keuangan Controller
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\PenjualanLinkController;
// ============================================
// NEW: Dynamic Product System Controllers
// ============================================
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AiAssistantController;
// ✅ NEW: Quotation Controllers
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\QuotationPublicController;
/*
|--------------------------------------------------------------------------
| Web Routes - Complete Version with Access Control & Ruijie
|--------------------------------------------------------------------------
*/
// =====================================
// PUBLIC ROUTES
// =====================================
Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
// Debug route
Route::get('/debug-products/{brand?}', [ProductController::class, 'debugProducts']);
// ⭐ UPDATED: Product routes - Now uses ProductController with more brands
Route::get('/products/{brand}', [ProductController::class, 'showByBrand'])
    ->name('products.brand')
    ->where('brand', 'hikvision|dahua|hilook|ezviz|unv|ruijie|hiview|foreage|all');
Route::get('/wifi-cam', [WiFiCameraController::class, 'index'])->name('wifi-cam');
Route::get('/wifi-cam/{slug}', [WiFiCameraController::class, 'show'])->name('wifi-cam.detail');
// ⭐ NEW: WiFi Cam Detail Page Route (Alternative URL with ID)
Route::get('/wifi-cam/detail/{id}', function($id) {
    $wifi_cam = WiFiCamera::find($id); 
    
    // Jika data tidak ditemukan, tampilkan error 404
    if (!$wifi_cam) {
        abort(404, 'Produk tidak ditemukan');
    }
    
    return view('wifi-cam.detail', compact('wifi_cam'));
})->name('wifi-cam.detail-by-id')->where('id', '[0-9]+');
// Access Control - Frontend (Public) - UPDATED
Route::get('/access-control', [AccessControlController::class, 'index'])->name('access-control');
Route::get('/access-control/{id}', [AccessControlController::class, 'show'])->name('access-control.detail');
// Ruijie Products - Frontend (Public)
Route::get('/products/ruijie', [RuijieController::class, 'index'])->name('products.ruijie');
Route::get('/products/ruijie/{id}', [RuijieController::class, 'show'])->name('products.ruijie.detail');
// Tambahkan route ini sebelum route resource purchase-orders, atau di dalam group yang sama
Route::get('/admin/purchase-orders/history', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'history'])->name('admin.po.history');
// About Page Route
Route::get('/about', function () {
    return view('about');
})->name('about');
// Tentang Kami Route (Alternative Indonesian URL)
Route::get('/tentang-kami', function () {
    return view('about');
})->name('tentang-kami');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
// =====================================
// ADMIN LOGIN & AUTH
// =====================================
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');
Route::get('/login', function () {
    if (view()->exists('login')) {
        return view('login');
    }
    return view('admin.login');
})->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->only('username', 'password');
    
    // Cari user berdasarkan username
    $admin = \DB::table('admins')
        ->where('username', $credentials['username'])
        ->first();
    
    // DEBUG: Cek apakah user ketemu
    if (!$admin) {
        return back()->withErrors(['login' => 'User tidak ditemukan di database!']);
    }
    // DEBUG: Cek kecocokan password
    if (\Hash::check($credentials['password'], $admin->password)) {
        session([
            'admin_logged_in' => true,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'admin_email' => $admin->email,
            'admin_role' => strtolower($admin->role),
        ]);
        return redirect('/admin/dashboard');
    } else {
        // Jika gagal, tampilkan pesan error yang lebih spesifik
        return back()->withErrors(['login' => 'Password salah! Pastikan tidak ada spasi atau salah ketik.']);
    }
})->name('login.post');
Route::post('/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('logout');
Route::get('/admin/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('admin.logout');
// =====================================
// ADMIN ROUTES (Protected by JavaScript in views)
// =====================================
// Unified Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('admin.dashboard');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
// =====================================
// STATIC PRODUCTS ADMIN - ENHANCED & FIXED
// =====================================
// Static Products Admin - Main Index (FIXED: Removed duplicate ->name())
Route::get('/admin/static-products', [StaticProductController::class, 'index'])
    ->name('admin.static-products.index');
// Static Products CRUD Routes (Using Controller Methods - UPDATED)
Route::prefix('admin/static-products')->name('admin.static-products.')->group(function () {
    // Create
    Route::get('/create', [StaticProductController::class, 'create'])->name('create');
    Route::post('/', [StaticProductController::class, 'store'])->name('store');
    
    // Edit & Update
    Route::get('/{id}/edit', [StaticProductController::class, 'edit'])->name('edit');
    Route::put('/{id}', [StaticProductController::class, 'update'])->name('update');
    
    // Delete
    Route::delete('/{id}', [StaticProductController::class, 'destroy'])->name('destroy');
    
    // Bulk Delete
    Route::post('/bulk-delete', [StaticProductController::class, 'bulkDelete'])->name('bulk-delete');
    
    // Toggle Active Status
    Route::post('/{id}/toggle-active', [StaticProductController::class, 'toggleStatus'])->name('toggle-active');
});
// Static Products API Routes (Admin)
Route::prefix('api/admin/static-products')->name('api.admin.static-products.')->group(function () {
    Route::get('/', [StaticProductController::class, 'getAll'])->name('index');
    Route::get('/statistics', [StaticProductController::class, 'getStatistics'])->name('statistics');
    Route::get('/{id}', [StaticProductController::class, 'show'])->name('show');
    Route::post('/{id}', [StaticProductController::class, 'update'])->name('update');
    Route::delete('/{id}/image', [StaticProductController::class, 'deleteImage'])->name('delete-image');
    Route::post('/{id}/toggle', [StaticProductController::class, 'toggleActive'])->name('toggle');
    Route::post('/bulk-update-prices', [StaticProductController::class, 'bulkUpdatePrices'])->name('bulk-update-prices');
});
// WiFi Cameras Admin
Route::get('/admin/wifi-cameras', [AdminWiFiCameraController::class, 'index'])
    ->name('admin.wifi-cameras');
Route::prefix('api/admin/wifi-cameras')->name('api.admin.wifi-cameras.')->group(function () {
    Route::get('/', [AdminWiFiCameraController::class, 'getCameras'])->name('index');
    Route::post('/', [AdminWiFiCameraController::class, 'store'])->name('store');
    Route::get('/{id}', [AdminWiFiCameraController::class, 'show'])->name('show');
    Route::post('/{id}', [AdminWiFiCameraController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminWiFiCameraController::class, 'destroy'])->name('destroy');
});
// =====================================
// ACCESS CONTROL ADMIN
// =====================================
// Access Control Admin Page
Route::get('/admin/access-control', [AdminAccessControlController::class, 'index'])
    ->name('admin.access-control');
// Access Control API Routes (Admin)
Route::prefix('api/admin/access-control')->name('api.admin.access-control.')->group(function () {
    Route::get('/', [AdminAccessControlController::class, 'getProducts'])->name('index');
    Route::get('/statistics', [AdminAccessControlController::class, 'getStatistics'])->name('statistics');
    Route::get('/{id}', [AdminAccessControlController::class, 'show'])->name('show');
    Route::post('/', [AdminAccessControlController::class, 'store'])->name('store');
    Route::post('/{id}', [AdminAccessControlController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminAccessControlController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/toggle', [AdminAccessControlController::class, 'toggleStatus'])->name('toggle');
});
// =====================================
// RUIJIE PRODUCTS ADMIN - FIXED
// =====================================
// Ruijie Admin Main Routes
Route::prefix('admin/ruijie')->name('admin.ruijie.')->group(function () {
    // Dashboard Overview - Redirect to products page
    Route::get('/', function() {
        return redirect()->route('admin.ruijie.products');
    })->name('index');
    
    // Page Settings Management
    Route::get('/settings', [AdminRuijieController::class, 'editSettings'])->name('settings');
    Route::post('/settings', [AdminRuijieController::class, 'updateSettings'])->name('settings.update');
    
    // Categories Management
    Route::get('/categories', [AdminRuijieController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminRuijieController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminRuijieController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminRuijieController::class, 'deleteCategory'])->name('categories.delete');
    
    // Products Management - FIXED: Now passes $products to view
    Route::get('/products', function() {
        $products = RuijieProduct::with('category')->orderBy('order')->get();
        $categories = RuijieCategory::withCount('products')->orderBy('order')->get();
        $statistics = [
            'total_products' => RuijieProduct::count(),
            'active_products' => RuijieProduct::where('is_active', true)->count(),
            'inactive_products' => RuijieProduct::where('is_active', false)->count(),
            'total_categories' => RuijieCategory::count(),
        ];
        
        return view('admin.ruijie.index', compact('products', 'categories', 'statistics'));
    })->name('products');
    
    Route::get('/create', [AdminRuijieController::class, 'createProduct'])->name('create');
    Route::get('/products/create', [AdminRuijieController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminRuijieController::class, 'storeProduct'])->name('products.store');
    
    // Edit routes - added both variations
    Route::get('/{id}/edit', [AdminRuijieController::class, 'editProduct'])->name('edit');
    Route::get('/products/{id}/edit', [AdminRuijieController::class, 'editProduct'])->name('products.edit');
    
    // Update routes
    Route::put('/{id}', [AdminRuijieController::class, 'updateProduct'])->name('update');
    Route::put('/products/{id}', [AdminRuijieController::class, 'updateProduct'])->name('products.update');
    
    // Delete routes
    Route::delete('/{id}', [AdminRuijieController::class, 'deleteProduct'])->name('destroy');
    Route::delete('/products/{id}', [AdminRuijieController::class, 'deleteProduct'])->name('products.delete');
});
// Ruijie API Routes (Admin)
Route::prefix('api/admin/ruijie')->name('api.admin.ruijie.')->group(function () {
    // Get all products
    Route::get('/products', function() {
        try {
            $products = RuijieProduct::with('category')->orderBy('order')->get();
            return response()->json([
                'success' => true, 
                'products' => $products,
                'count' => $products->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('products');
    
    // Get single product
    Route::get('/products/{id}', function($id) {
        try {
            $product = RuijieProduct::with('category')->findOrFail($id);
            return response()->json([
                'success' => true, 
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 404);
        }
    })->name('products.show');
    
    // Toggle product status
    Route::post('/products/{id}/toggle', function($id) {
        try {
            $product = RuijieProduct::findOrFail($id);
            $product->is_active = !$product->is_active;
            $product->save();
            return response()->json([
                'success' => true, 
                'product' => $product,
                'message' => 'Product status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('products.toggle');
    
    // Get statistics
    Route::get('/statistics', function() {
        try {
            $stats = [
                'total_products' => RuijieProduct::count(),
                'active_products' => RuijieProduct::where('is_active', true)->count(),
                'inactive_products' => RuijieProduct::where('is_active', false)->count(),
                'featured_products' => RuijieProduct::where('is_featured', true)->where('is_active', true)->count(),
                'total_categories' => RuijieCategory::count(),
                'active_categories' => RuijieCategory::where('is_active', true)->count(),
                'page_active' => RuijiePageSettings::where('is_active', true)->exists()
            ];
            
            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('statistics');
    
    // Get categories
    Route::get('/categories', function() {
        try {
            $categories = RuijieCategory::withCount('products')->orderBy('order')->get();
            return response()->json([
                'success' => true,
                'categories' => $categories,
                'count' => $categories->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('categories');
    
    // Get page settings
    Route::get('/settings', function() {
        try {
            $settings = RuijiePageSettings::first();
            if (!$settings) {
                $settings = RuijiePageSettings::create([
                    'title' => 'Ruijie Networks',
                    'subtitle' => 'Solusi networking enterprise-grade dengan teknologi terkini untuk infrastruktur jaringan yang handal, scalable, dan mudah dikelola',
                    'products_count' => 500,
                    'clients_count' => 10000,
                    'satisfaction_rate' => 99
                ]);
            }
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('settings');
});
// =====================================
// ⭐ BOOKKEEPING / PEMBUKUAN ROUTES - FIXED ORDERING
// =====================================
// Bookkeeping Admin - Main Dashboard
Route::get('/admin/bookkeeping', [BookkeepingController::class, 'index'])
    ->name('admin.bookkeeping.index');
// Bookkeeping CRUD Routes - FIXED: Specific routes BEFORE parameter routes
Route::prefix('admin/bookkeeping')->name('admin.bookkeeping.')->group(function () {
    // ⭐ SPECIFIC ROUTES FIRST (Must be before /{id} routes)
    
    // Export Transactions
    Route::get('/export', [BookkeepingController::class, 'export'])->name('export');
    
    // ⭐ NEW: Print PDF Route
    Route::get('/print', [BookkeepingController::class, 'print'])->name('print');
    
    // Create New Transaction
    Route::get('/create', [BookkeepingController::class, 'create'])->name('create');
    
    // Store New Transaction
    Route::post('/', [BookkeepingController::class, 'store'])->name('store');
    
    // ⭐ PARAMETER ROUTES LAST
    
    // View Transaction Detail
    Route::get('/{id}', [BookkeepingController::class, 'show'])
        ->name('show')
        ->where('id', '[0-9]+');
    
    // Edit Transaction
    Route::get('/{id}/edit', [BookkeepingController::class, 'edit'])
        ->name('edit')
        ->where('id', '[0-9]+');
    
    // Update Transaction
    Route::put('/{id}', [BookkeepingController::class, 'update'])
        ->name('update')
        ->where('id', '[0-9]+');
    
    // Delete Transaction
    Route::delete('/{id}', [BookkeepingController::class, 'destroy'])
        ->name('destroy')
        ->where('id', '[0-9]+');
});
// Bookkeeping API Routes (for Dashboard Integration)
Route::prefix('api/admin/bookkeeping')->name('api.admin.bookkeeping.')->group(function () {
    // Statistics for Dashboard Cards
    Route::get('/statistics', [BookkeepingController::class, 'getStatisticsApi'])->name('statistics');
    
    // Monthly Data for Charts
    Route::get('/monthly-data', [BookkeepingController::class, 'getMonthlyDataApi'])->name('monthly-data');
    
    // Filter Transactions
    Route::get('/filter', [BookkeepingController::class, 'filterTransactions'])->name('filter');
});
/// =====================================
// ⭐ KEUANGAN / FINANCE ROUTES
// =====================================
Route::get('/admin/keuangan', [KeuanganController::class, 'index'])
     ->name('admin.keuangan');
Route::prefix('api/admin/keuangan')->group(function () {
    Route::get('/summary',            [KeuanganController::class, 'getSummary']);
    Route::get('/transaksi',          [KeuanganController::class, 'getTransaksi']);
    Route::get('/chart-data',         [KeuanganController::class, 'getChartData']);
    Route::get('/kategori-breakdown', [KeuanganController::class, 'getKategoriBreakdown']);
    Route::get('/platform-breakdown', [KeuanganController::class, 'getPlatformBreakdown']); // ← tambah ini
    Route::post('/transaksi',         [KeuanganController::class, 'store']);
    Route::get('/transaksi/{id}',     [KeuanganController::class, 'show']);
    Route::put('/transaksi/{id}',     [KeuanganController::class, 'update']);
    Route::delete('/transaksi/{id}',  [KeuanganController::class, 'destroy']);
    // ✅ Route tetap untuk link penjualan
    Route::post('/generate-link',     [PenjualanLinkController::class, 'generateLink']);
    Route::get('/links',              [PenjualanLinkController::class, 'getLinks']);
    Route::post('/links/{id}/toggle', [PenjualanLinkController::class, 'toggleLink']);
    Route::delete('/links/{id}',      [PenjualanLinkController::class, 'deleteLink']);
});

// =====================================
// ⭐ FINANCE STAFF ROUTES
// =====================================
use App\Http\Controllers\Admin\FinanceController;

Route::get('/admin/finance', [FinanceController::class, 'index'])
     ->name('admin.finance');
Route::prefix('api/admin/finance')->group(function () {
    Route::get('/summary',                 [FinanceController::class, 'getSummary']);
    Route::get('/transaksi',               [FinanceController::class, 'getTransaksi']);
    Route::post('/transaksi',              [FinanceController::class, 'store']);
    Route::get('/transaksi/{id}',          [FinanceController::class, 'show']);
    Route::put('/transaksi/{id}',          [FinanceController::class, 'update']);
    Route::patch('/transaksi/{id}/status', [FinanceController::class, 'updateStatus']);
    Route::delete('/transaksi/{id}',       [FinanceController::class, 'destroy']);
    // ✅ Invoice dari SO
    Route::get('/invoices',                [FinanceController::class, 'getInvoices']);
    Route::get('/invoice-detail/{id}',     [FinanceController::class, 'getInvoiceDetail']);
});
// ✅ DIPERBAIKI: Route tetap penjualan online (tidak pakai {token} dinamis)
Route::get('/penjualan-online/staff',   [PenjualanLinkController::class, 'showFormStaff'])->name('penjualan.link.staff');
Route::post('/penjualan-online/simpan', [PenjualanLinkController::class, 'simpan'])->name('penjualan.link.simpan');

// =====================================
// ⭐ LAPORAN KEUANGAN ROUTES
// =====================================
use App\Http\Controllers\Admin\LaporanController;

Route::get('/admin/finance/laporan', [LaporanController::class, 'index'])
     ->name('admin.finance.laporan');
Route::get('/admin/finance/laporan/pdf', [LaporanController::class, 'exportPdf'])
     ->name('admin.finance.laporan.pdf');
Route::prefix('api/admin/laporan')->group(function () {
    Route::get('/laba-rugi',    [LaporanController::class, 'labaRugi']);
    Route::get('/cash-flow',    [LaporanController::class, 'cashFlow']);
    Route::get('/export-excel', [LaporanController::class, 'exportExcel']);
});

// =====================================
// ✅ QUOTATION / PENAWARAN ROUTES
// =====================================
// Admin - Kelola Penawaran
Route::prefix('admin/quotation')->name('admin.quotation.')->group(function () {
    Route::get('/',                 [QuotationController::class, 'index'])      ->name('index');
    Route::get('/create',           [QuotationController::class, 'create'])     ->name('create');
    Route::post('/',                [QuotationController::class, 'store'])      ->name('store');
    Route::get('/{id}',             [QuotationController::class, 'show'])       ->name('show')      ->where('id', '[0-9]+');
    Route::get('/{id}/edit',        [QuotationController::class, 'edit'])       ->name('edit')      ->where('id', '[0-9]+');
    Route::put('/{id}',             [QuotationController::class, 'update'])     ->name('update')    ->where('id', '[0-9]+');
    Route::delete('/{id}',          [QuotationController::class, 'destroy'])    ->name('destroy')   ->where('id', '[0-9]+');
    Route::post('/{id}/send',       [QuotationController::class, 'send'])       ->name('send')      ->where('id', '[0-9]+');
    Route::post('/{id}/convert-so', [QuotationController::class, 'convertToSO'])->name('convertSO')->where('id', '[0-9]+');
    Route::get('/{id}/pdf',         [QuotationController::class, 'pdf'])        ->name('pdf')       ->where('id', '[0-9]+');
});
// Public - Customer lihat & respond penawaran
Route::get('/penawaran/{token}',          [QuotationPublicController::class, 'show'])   ->name('quotation.show');
Route::post('/penawaran/{token}/respond', [QuotationPublicController::class, 'respond'])->name('quotation.respond');
// =====================================
// ⭐ PURCHASE ORDER (PO) ROUTES
// =====================================
// Halaman
Route::prefix('admin/purchase-orders')->name('admin.po.')->group(function () {
    Route::get('/',           [App\Http\Controllers\Admin\PurchaseOrderController::class, 'index'])->name('index');
    Route::get('/create',     [App\Http\Controllers\Admin\PurchaseOrderController::class, 'create'])->name('create');
    Route::get('/{id}/edit',  [App\Http\Controllers\Admin\PurchaseOrderController::class, 'edit'])->name('edit')->where('id','[0-9]+');
    Route::get('/{id}/print', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'printPdf'])->name('print')->where('id','[0-9]+');
    Route::get('/{id}/pdf',   [App\Http\Controllers\Admin\PurchaseOrderController::class, 'downloadPdf'])->name('pdf')->where('id','[0-9]+');
});
// API
Route::prefix('api/admin/purchase-orders')->group(function () {
    Route::get('/',                      [App\Http\Controllers\Admin\PurchaseOrderController::class, 'getList']);
    Route::post('/',                     [App\Http\Controllers\Admin\PurchaseOrderController::class, 'store']);
    Route::get('/{id}',                  [App\Http\Controllers\Admin\PurchaseOrderController::class, 'getDetail'])->where('id','[0-9]+');
    Route::put('/{id}',                  [App\Http\Controllers\Admin\PurchaseOrderController::class, 'update'])->where('id','[0-9]+');
    Route::delete('/{id}',               [App\Http\Controllers\Admin\PurchaseOrderController::class, 'destroy'])->where('id','[0-9]+');
    Route::patch('/{id}/status',         [App\Http\Controllers\Admin\PurchaseOrderController::class, 'updateStatus'])->where('id','[0-9]+');
});
// =====================================
// ⭐ NEW: INVENTORY MANAGEMENT ROUTES - UPDATED WITH GROUPED INVENTORY
// =====================================
// Inventory Admin Pages
Route::prefix('admin/inventory')->name('admin.inventory.')->group(function () {
    // Main Dashboard
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    
    // Incoming Items (Barang Masuk) - Standard Mode
    Route::get('/incoming', [InventoryController::class, 'incoming'])->name('incoming');
    
    // ⭐ NEW: Incoming Items - Continuous Scan Mode
    Route::get('/incoming-continuous', function() {
        return view('admin.inventory.incoming');
    })->name('incoming.continuous');
    
    // ⭐ NEW: Incoming Items - Grouped Mode (Tanpa Serial Number)
    Route::get('/incoming-grouped', function() {
        return view('admin.inventory.incoming-grouped');
    })->name('incoming.grouped');
    
    // Outgoing Items (Barang Keluar)
    Route::get('/outgoing', [InventoryController::class, 'outgoing'])->name('outgoing');
    
    // ⭐ NEW: Outgoing Items - Grouped Mode
    Route::get('/outgoing-grouped', function() {
        return view('admin.inventory.outgoing-grouped');
    })->name('outgoing.grouped');
    
    // ⭐ NEW: Grouped Inventory View (Lihat Stock Grouped)
    Route::get('/grouped', function() {
        return view('admin.inventory.grouped-view');
    })->name('grouped.index');
    
    // Reports
    Route::get('/reports', [InventoryController::class, 'reports'])->name('reports');
    
    // Export
    Route::get('/export', [InventoryController::class, 'export'])->name('export');
});
// Inventory API Routes
Route::prefix('api/admin/inventory')->name('api.admin.inventory.')->group(function () {
    // Get all items (Standard View)
    Route::get('/items', [InventoryController::class, 'getItems'])->name('items');
    
    // Get statistics
    Route::get('/statistics', [InventoryController::class, 'getStatistics'])->name('statistics');
    
    // ╔════════════════════════════════════════════════════════════╗
    // ║  ⭐ GROUPED INVENTORY ROUTES - NEW                         ║
    // ╚════════════════════════════════════════════════════════════╝
    
    // Get items grouped by product
    Route::get('/items-grouped', [InventoryController::class, 'getItemsGrouped'])
         ->name('items-grouped');
    
    // Get serial numbers for a specific grouped product
    Route::get('/products/{id}/serials', [InventoryController::class, 'getProductSerials'])
         ->name('products.serials');
    
    // Store incoming items - GROUPED MODE
    Route::post('/incoming-grouped', [InventoryController::class, 'storeIncomingGrouped'])
         ->name('incoming.grouped.store');
    
    // Process outgoing items - GROUPED MODE
    Route::post('/outgoing-grouped', [InventoryController::class, 'processOutgoingGrouped'])
         ->name('outgoing.grouped.process');
    
    // Convert individual items to grouped
    Route::post('/convert-to-grouped', [InventoryController::class, 'convertToGrouped'])
         ->name('convert-to-grouped');
    
    // Get grouped inventory statistics
    Route::get('/statistics-grouped', [InventoryController::class, 'getGroupedStatistics'])
         ->name('statistics-grouped');
    
    // Search grouped products (for autocomplete)
    Route::get('/search-grouped', [InventoryController::class, 'searchGroupedProducts'])
         ->name('search-grouped');
    
    // ╚════════════════════════════════════════════════════════════╝
    
    // ╔════════════════════════════════════════════════════════════╗
    // ║  ⭐ STOCK CHECKER APIs                                     ║
    // ╚════════════════════════════════════════════════════════════╝
    
    // Check Stock by Product Name (for real-time stock checking)
    Route::get('/check-stock', [InventoryController::class, 'checkStock'])
         ->name('check-stock');
    
    // Get Stock Summary (grouped by product)
    Route::get('/stock-summary', [InventoryController::class, 'getStockSummary'])
         ->name('stock-summary');
    // Update Stock (Barang Masuk/Keluar Manual)
    Route::post('/items/{id}/update-stock', [InventoryController::class, 'updateStock'])
         ->name('items.update-stock');
         
    // ╚════════════════════════════════════════════════════════════╝
    
    // Check serial number (duplicate detection) - SINGLE
    Route::post('/check-serial', [InventoryController::class, 'checkSerialNumber'])
         ->name('check-serial');
    
    // Check serial numbers (BATCH)
    Route::post('/check-serial-batch', [InventoryController::class, 'checkSerialNumbersBatch'])
         ->name('check-serial-batch');
    
    // Store incoming item - SINGLE
    Route::post('/incoming', [InventoryController::class, 'storeIncoming'])
         ->name('incoming.store');
    
    // ╔════════════════════════════════════════════════════════════╗
    // ║  ⭐ BATCH INCOMING ROUTES (OLD & NEW) - UPDATED            ║
    // ╚════════════════════════════════════════════════════════════╝
    
    // Store incoming items (BATCH) - OLD METHOD (tetap ada untuk backward compatibility)
    Route::post('/incoming-batch', [InventoryController::class, 'storeIncomingBatch'])
         ->name('incoming.store-batch');
    
    // ✅ NEW: Store incoming batch - OPTIMIZED VERSION (100x faster!)
    Route::post('/incoming-batch-optimized', [InventoryController::class, 'storeIncomingBatchOptimized'])
         ->name('incoming.store-batch-optimized');
    
    // ✅ NEW: Validate bulk serial numbers before save (preview mode)
    Route::post('/validate-bulk', [InventoryController::class, 'validateBulkSerialNumbers'])
         ->name('validate-bulk');
    
    // ✅ NEW: Get bulk scan statistics for monitoring
    Route::get('/bulk-statistics', [InventoryController::class, 'getBulkScanStatistics'])
         ->name('bulk-statistics');
    
    // ╚════════════════════════════════════════════════════════════╝
    
    // Process outgoing item (sell/damage/return) - STANDARD MODE
    Route::post('/outgoing', [InventoryController::class, 'processOutgoing'])
         ->name('outgoing.process');
    
    // CRUD operations
    Route::get('/items/{id}', [InventoryController::class, 'show'])
         ->name('items.show');
    Route::put('/items/{id}', [InventoryController::class, 'update'])
         ->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])
         ->name('items.destroy');
    
    // Get filters data
    Route::get('/brands', [InventoryController::class, 'getBrands'])
         ->name('brands');
    Route::get('/categories', [InventoryController::class, 'getCategories'])
         ->name('categories');
});
// =====================================
// ⭐ NEW: GUDANG ROUTES (DENGAN FIX API)
// =====================================
Route::prefix('admin/gudang/sales-orders')->name('admin.sales-orders.')->group(function () {
    Route::get('/',        [SalesOrderController::class, 'index'])->name('index');
    Route::get('/create',  [SalesOrderController::class, 'create'])->name('create');
    Route::post('/',       [SalesOrderController::class, 'store'])->name('store');
    // Detail & Delete
    Route::get('/{id}',    [SalesOrderController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::delete('/{id}', [SalesOrderController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    // Approve
    Route::get('/{id}/approve',  [SalesOrderController::class, 'approveForm'])->name('approve-form')->where('id', '[0-9]+');
    Route::post('/{id}/approve', [SalesOrderController::class, 'approve'])->name('approve')->where('id', '[0-9]+');
    // Deliver & Cancel
    Route::post('/{id}/deliver', [SalesOrderController::class, 'deliver'])->name('deliver')->where('id', '[0-9]+');
    Route::post('/{id}/cancel',  [SalesOrderController::class, 'cancel'])->name('cancel')->where('id', '[0-9]+');
    // PDF & Email
    Route::get('/{id}/pdf',        [SalesOrderController::class, 'downloadPdf'])->name('pdf')->where('id', '[0-9]+');
    Route::get('/{id}/preview-pdf',[SalesOrderController::class, 'previewPdf'])->name('preview-pdf')->where('id', '[0-9]+');
    Route::post('/{id}/send-email',[SalesOrderController::class, 'sendEmail'])->name('send-email')->where('id', '[0-9]+');
    
    // ✅ INVOICE - nama cukup suffix, prefix sudah dari group 'admin.sales-orders.'
Route::get('/{id}/create-invoice',      [SalesOrderController::class, 'createInvoiceForm'])->name('create-invoice')->where('id', '[0-9]+');
Route::post('/{id}/store-invoice',      [SalesOrderController::class, 'storeInvoice'])->name('store-invoice')->where('id', '[0-9]+');
Route::post('/{id}/mark-lunas',         [SalesOrderController::class, 'markLunas'])->name('mark-lunas')->where('id', '[0-9]+');
Route::get('/{id}/invoice/download',    [SalesOrderController::class, 'downloadInvoicePdf'])->name('invoice-download')->where('id', '[0-9]+');
Route::get('/{id}/invoice/preview',     [SalesOrderController::class, 'previewInvoicePdf'])->name('invoice-preview')->where('id', '[0-9]+');
Route::post('/{id}/invoice/send-email', [SalesOrderController::class, 'sendInvoiceEmail'])->name('invoice-send-email')->where('id', '[0-9]+');
    });
// Halaman Frontend Admin
Route::get('/admin/gudang', [App\Http\Controllers\Admin\GudangController::class, 'index'])->name('admin.gudang');
// Group API Gudang (Disesuaikan dengan URL fetch di blade)
Route::prefix('api/admin/gudang')->group(function () {
    // Produk & Stok Utama
    Route::get('/products',     [App\Http\Controllers\Admin\GudangController::class, 'getProducts']);
    Route::get('/categories',   [App\Http\Controllers\Admin\GudangController::class, 'getCategories']);
    Route::get('/history/{id}', [App\Http\Controllers\Admin\GudangController::class, 'getHistory']);
    
    // Barang Masuk
    Route::post('/barang-masuk',        [App\Http\Controllers\Admin\GudangController::class, 'storeBarangMasuk']);
    Route::delete('/barang-masuk/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangMasuk']);
    
    // Barang Keluar
    Route::post('/barang-keluar',        [App\Http\Controllers\Admin\GudangController::class, 'storeBarangKeluar']);
    Route::delete('/barang-keluar/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangKeluar']);
    // Manajemen Produk (Fix Delete 405)
    Route::delete('/products/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyProduct']);
    
    // Helper & Serial Number APIs
    Route::get('/available-serials', [App\Http\Controllers\Admin\GudangController::class, 'getAvailableSerials']);
    Route::get('/product-use-sn',    [App\Http\Controllers\Admin\GudangController::class, 'productUseSerialNumber']);
});
// =====================================
// ⭐ NEW: SALES DOCUMENTS ROUTES (Surat Order & Penawaran)
// =====================================
Route::get('/admin/sales-documents', function () {
    return view('admin.sales-documents');
})->name('admin.sales-documents');
// =====================================
// NEW: DYNAMIC PRODUCT SYSTEM ROUTES
// =====================================
// Frontend Routes - Brand Products (Dynamic) - ALTERNATIVE URL
Route::get('/brand-products/{brand}', [BrandProductController::class, 'show'])
    ->name('brand.products.dynamic')
    ->where('brand', 'hikvision|dahua|hilook|ezviz|unv');
// API Routes untuk Dynamic Products (Frontend)
Route::get('/api/brand-products/{brand}/{category}', [BrandProductController::class, 'getProducts'])
    ->name('api.brand.products.dynamic');
Route::get('/api/brand-products/{id}', function($id) {
    try {
        $product = \App\Models\Product::findOrFail($id);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Product not found'
        ], 404);
    }
})->name('api.brand.product.detail');
// Route untuk Detail Produk Foreage (Mengambil data dari static_products)
Route::get('/foreages/{id}', function($id) {
    $product = \Illuminate\Support\Facades\DB::table('static_products')
        ->where('id', $id)
        ->where('brand', 'foreage')
        ->where('is_active', 1)
        ->first();
        
    if (!$product) {
        abort(404, 'Produk Foreage tidak ditemukan');
    }
    
    $productArray = (array) $product;
    
    return view('foreages-detail', ['product' => $productArray]);
})->name('foreages-detail');
// ⭐ NEW: API for /products/{brand} route - Updated ProductController endpoints
Route::prefix('api')->group(function () {
    // Get all brands configuration
    Route::get('/brands', [ProductController::class, 'getAllBrands'])
        ->name('api.brands');
    
    // Get products by brand and category
    Route::get('/products/{brand}/{category}', [ProductController::class, 'getProducts'])
        ->name('api.products.by-category')
        ->where('brand', 'hikvision|dahua|hilook|ezviz|unv|ruijie|hiview|foreage')
        ->where('category', 'basic|audio|fullcolor|ipcamera|cooper|pro|enterprise');
    
    // Get single product detail (for modal) - UPDATED to use ProductController
    Route::get('/products/{id}', [ProductController::class, 'getProductDetail'])
        ->name('api.product.detail')
        ->where('id', '[0-9]+');
    
    // Debug products endpoint
    Route::get('/debug/products/{brand?}', [ProductController::class, 'debugProducts'])
        ->name('api.debug.products');
});
// Admin Routes - Dynamic Product Management (untuk tabel static_products)
Route::prefix('admin/products')->name('admin.products.')->group(function () {
    // List & Create
    Route::get('/', [AdminProductController::class, 'index'])->name('index');
    Route::get('/create', [AdminProductController::class, 'create'])->name('create');
    Route::post('/', [AdminProductController::class, 'store'])->name('store');
    
    // Edit & Update
    Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
    
    // Delete
    Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
    
    // Bulk Actions
    Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/bulk-status', [AdminProductController::class, 'bulkUpdateStatus'])->name('bulk-status');
    
    // Export
    Route::get('/export', [AdminProductController::class, 'export'])->name('export');
});
// =====================================
// API ROUTES - ADMIN (Protected)
// =====================================
// Statistics API
Route::get('/api/admin/statistics', [UnifiedAdminController::class, 'getStatistics']);
// Dynamic Products API
Route::get('/api/products', [UnifiedAdminController::class, 'getDynamicProducts']);
Route::get('/api/products/{id}', [UnifiedAdminController::class, 'showDynamicProduct']);
Route::post('/api/products', [UnifiedAdminController::class, 'storeDynamicProduct']);
Route::post('/api/products/{id}', [UnifiedAdminController::class, 'updateDynamicProduct']);
Route::delete('/api/products/{id}', [UnifiedAdminController::class, 'deleteDynamicProduct']);
// Static Products API (Admin)
Route::get('/api/static-products', [UnifiedAdminController::class, 'getStaticProducts']);
Route::post('/api/static-products/{id}', [UnifiedAdminController::class, 'updateStaticProduct']);
Route::post('/api/static-products/{id}/toggle', [UnifiedAdminController::class, 'toggleStaticProductStatus']);
Route::post('/api/static-products/bulk-update', [UnifiedAdminController::class, 'bulkUpdateStaticProducts']);
// All Products API (Combined)
Route::get('/api/all-products', [UnifiedAdminController::class, 'getAllProducts']);
// =====================================
// API ROUTES - PUBLIC (Frontend)
// =====================================
// PUBLIC WiFi Cameras API - FIXED: Menggunakan WiFiCameraController::apiIndex
Route::get('/api/wifi-cameras', [WiFiCameraController::class, 'apiIndex'])
    ->name('api.wifi-cameras.public');
// PUBLIC Access Control API - UPDATED: Menggunakan AccessControlController::apiIndex
Route::get('/api/access-control', [AccessControlController::class, 'apiIndex'])
    ->name('api.access-control.public');
Route::get('/api/access-control/brands', [AccessControlController::class, 'getBrands'])
    ->name('api.access-control.brands');
Route::get('/api/access-control/categories', [AccessControlController::class, 'getCategories'])
    ->name('api.access-control.categories');
// PUBLIC Ruijie Products API
Route::get('/api/ruijie-products', [RuijieController::class, 'getProducts'])
    ->name('api.ruijie-products.public');
Route::get('/api/ruijie-products/featured', [RuijieController::class, 'getFeatured'])
    ->name('api.ruijie-products.featured');
Route::get('/api/ruijie-products/category/{category}', [RuijieController::class, 'getByCategory'])
    ->name('api.ruijie-products.category');
Route::get('/api/ruijie-categories', [RuijieController::class, 'getCategories'])
    ->name('api.ruijie-categories.public');
// PUBLIC Static Products API  
Route::get('/api/public/static-products', function(Request $request) {
    $query = \DB::table('static_products')
        ->where('is_active', 1);
    
    // Filter by brand
    if ($request->has('brand') && $request->brand != '') {
        $query->where('brand', strtolower($request->brand));
    }
    
    // Filter by tab category
    if ($request->has('tab_category') && $request->tab_category != '') {
        $query->where('tab_category', $request->tab_category);
    }
    
    $products = $query->orderBy('position')->orderBy('id')->get();
    
    return response()->json([
        'success' => true,
        'products' => $products,
        'count' => $products->count()
    ]);
})->name('api.static-products.public');
// =====================================
// LEGACY ROUTES (Backward Compatibility)
// =====================================
Route::prefix('products')->group(function () {
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
});
// =====================================
// AI ASSISTANT ROUTES
// =====================================
Route::post('/ai/chat',      [AiAssistantController::class, 'chat']     )->name('ai.chat');
Route::post('/ai/recommend', [AiAssistantController::class, 'recommend'])->name('ai.recommend');
// =====================================
// FALLBACK (404)
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
// =====================================
// FINAL OVERRIDE: USER MANAGEMENT
// =====================================
Route::group(['prefix' => 'manage-users'], function() {
    Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
// =====================================
// ROLES & PERMISSIONS MANAGEMENT
// =====================================
Route::group(['prefix' => 'admin/roles', 'as' => 'admin.roles.'], function() {
    Route::get('/', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
});