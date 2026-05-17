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
use Spatie\Sitemap\SitemapGenerator;
use App\Http\Controllers\BookkeepingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\PenjualanLinkController;
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\QuotationPublicController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================================
// PUBLIC ROUTES
// =====================================
Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/debug-products/{brand?}', [ProductController::class, 'debugProducts']);

Route::get('/products/{brand}', [ProductController::class, 'showByBrand'])
    ->name('products.brand')
    ->where('brand', 'hikvision|dahua|hilook|ezviz|unv|ruijie|hiview|foreage|all');

Route::get('/wifi-cam', [WiFiCameraController::class, 'index'])->name('wifi-cam');
Route::get('/wifi-cam/{slug}', [WiFiCameraController::class, 'show'])->name('wifi-cam.detail');

Route::get('/wifi-cam/detail/{id}', function ($id) {
    $wifi_cam = WiFiCamera::find($id);
    if (!$wifi_cam) abort(404, 'Produk tidak ditemukan');
    return view('wifi-cam.detail', compact('wifi_cam'));
})->name('wifi-cam.detail-by-id')->where('id', '[0-9]+');

Route::get('/access-control', [AccessControlController::class, 'index'])->name('access-control');
Route::get('/access-control/{id}', [AccessControlController::class, 'show'])->name('access-control.detail');

Route::get('/products/ruijie', [RuijieController::class, 'index'])->name('products.ruijie');
Route::get('/products/ruijie/{id}', [RuijieController::class, 'show'])->name('products.ruijie.detail');

Route::get('/about', fn() => view('about'))->name('about');
Route::get('/tentang-kami', fn() => view('about'))->name('tentang-kami');
Route::get('/contact', fn() => view('contact'))->name('contact');

Route::get('/foreages/{id}', function ($id) {
    $product = \Illuminate\Support\Facades\DB::table('static_products')
        ->where('id', $id)->where('brand', 'foreage')->where('is_active', 1)->first();
    if (!$product) abort(404, 'Produk Foreage tidak ditemukan');
    return view('foreages-detail', ['product' => (array) $product]);
})->name('foreages-detail');

Route::get('/brand-products/{brand}', [BrandProductController::class, 'show'])
    ->name('brand.products.dynamic')
    ->where('brand', 'hikvision|dahua|hilook|ezviz|unv');

Route::get('/penawaran/{token}', [QuotationPublicController::class, 'show'])->name('quotation.show');
Route::post('/penawaran/{token}/respond', [QuotationPublicController::class, 'respond'])->name('quotation.respond');

Route::get('/penjualan-online/staff', [PenjualanLinkController::class, 'showFormStaff'])->name('penjualan.link.staff');
Route::post('/penjualan-online/simpan', [PenjualanLinkController::class, 'simpan'])->name('penjualan.link.simpan');

Route::post('/ai/chat', [AiAssistantController::class, 'chat'])->name('ai.chat');
Route::post('/ai/recommend', [AiAssistantController::class, 'recommend'])->name('ai.recommend');

// =====================================
// PUBLIC API ROUTES
// =====================================
Route::get('/api/wifi-cameras', [WiFiCameraController::class, 'apiIndex'])->name('api.wifi-cameras.public');
Route::get('/api/access-control', [AccessControlController::class, 'apiIndex'])->name('api.access-control.public');
Route::get('/api/access-control/brands', [AccessControlController::class, 'getBrands'])->name('api.access-control.brands');
Route::get('/api/access-control/categories', [AccessControlController::class, 'getCategories'])->name('api.access-control.categories');
Route::get('/api/ruijie-products', [RuijieController::class, 'getProducts'])->name('api.ruijie-products.public');
Route::get('/api/ruijie-products/featured', [RuijieController::class, 'getFeatured'])->name('api.ruijie-products.featured');
Route::get('/api/ruijie-products/category/{category}', [RuijieController::class, 'getByCategory'])->name('api.ruijie-products.category');
Route::get('/api/ruijie-categories', [RuijieController::class, 'getCategories'])->name('api.ruijie-categories.public');

Route::get('/api/public/static-products', function (Request $request) {
    $query = \DB::table('static_products')->where('is_active', 1);
    if ($request->has('brand') && $request->brand != '') $query->where('brand', strtolower($request->brand));
    if ($request->has('tab_category') && $request->tab_category != '') $query->where('tab_category', $request->tab_category);
    $products = $query->orderBy('position')->orderBy('id')->get();
    return response()->json(['success' => true, 'products' => $products, 'count' => $products->count()]);
})->name('api.static-products.public');

Route::prefix('api')->group(function () {
    Route::get('/brands', [ProductController::class, 'getAllBrands'])->name('api.brands');
    Route::get('/products/{brand}/{category}', [ProductController::class, 'getProducts'])
        ->name('api.products.by-category')
        ->where('brand', 'hikvision|dahua|hilook|ezviz|unv|ruijie|hiview|foreage')
        ->where('category', 'basic|audio|fullcolor|ipcamera|cooper|pro|enterprise');
    Route::get('/products/{id}', [ProductController::class, 'getProductDetail'])->name('api.product.detail')->where('id', '[0-9]+');
    Route::get('/debug/products/{brand?}', [ProductController::class, 'debugProducts'])->name('api.debug.products');
});

Route::get('/api/brand-products/{brand}/{category}', [BrandProductController::class, 'getProducts'])->name('api.brand.products.dynamic');
Route::get('/api/brand-products/{id}', function ($id) {
    try {
        $product = \App\Models\Product::findOrFail($id);
        return response()->json(['success' => true, 'product' => $product]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => 'Product not found'], 404);
    }
})->name('api.brand.product.detail');

// =====================================
// AUTH ROUTES (Laravel Auth - Baru)
// =====================================
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// =====================================
// ADMIN ROUTES (Protected - admin.auth)
// =====================================
Route::middleware(['admin.auth'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', fn() => view('dashboard'))->name('admin.dashboard');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // =====================================
    // STATIC PRODUCTS
    // =====================================
    Route::get('/admin/static-products', [StaticProductController::class, 'index'])->name('admin.static-products.index');
    Route::prefix('admin/static-products')->name('admin.static-products.')->group(function () {
        Route::get('/create', [StaticProductController::class, 'create'])->name('create');
        Route::post('/', [StaticProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StaticProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StaticProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [StaticProductController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [StaticProductController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{id}/toggle-active', [StaticProductController::class, 'toggleStatus'])->name('toggle-active');
    });

    Route::prefix('api/admin/static-products')->name('api.admin.static-products.')->group(function () {
        Route::get('/', [StaticProductController::class, 'getAll'])->name('index');
        Route::get('/statistics', [StaticProductController::class, 'getStatistics'])->name('statistics');
        Route::get('/{id}', [StaticProductController::class, 'show'])->name('show');
        Route::post('/{id}', [StaticProductController::class, 'update'])->name('update');
        Route::delete('/{id}/image', [StaticProductController::class, 'deleteImage'])->name('delete-image');
        Route::post('/{id}/toggle', [StaticProductController::class, 'toggleActive'])->name('toggle');
        Route::post('/bulk-update-prices', [StaticProductController::class, 'bulkUpdatePrices'])->name('bulk-update-prices');
    });

    // =====================================
    // WIFI CAMERAS ADMIN
    // =====================================
    Route::get('/admin/wifi-cameras', [AdminWiFiCameraController::class, 'index'])->name('admin.wifi-cameras');
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
    Route::get('/admin/access-control', [AdminAccessControlController::class, 'index'])->name('admin.access-control');
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
    // RUIJIE PRODUCTS ADMIN
    // =====================================
    Route::prefix('admin/ruijie')->name('admin.ruijie.')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.ruijie.products'))->name('index');
        Route::get('/settings', [AdminRuijieController::class, 'editSettings'])->name('settings');
        Route::post('/settings', [AdminRuijieController::class, 'updateSettings'])->name('settings.update');
        Route::get('/categories', [AdminRuijieController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminRuijieController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AdminRuijieController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminRuijieController::class, 'deleteCategory'])->name('categories.delete');
        Route::get('/products', function () {
            $products   = RuijieProduct::with('category')->orderBy('order')->get();
            $categories = RuijieCategory::withCount('products')->orderBy('order')->get();
            $statistics = [
                'total_products'    => RuijieProduct::count(),
                'active_products'   => RuijieProduct::where('is_active', true)->count(),
                'inactive_products' => RuijieProduct::where('is_active', false)->count(),
                'total_categories'  => RuijieCategory::count(),
            ];
            return view('admin.ruijie.index', compact('products', 'categories', 'statistics'));
        })->name('products');
        Route::get('/create', [AdminRuijieController::class, 'createProduct'])->name('create');
        Route::get('/products/create', [AdminRuijieController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminRuijieController::class, 'storeProduct'])->name('products.store');
        Route::get('/{id}/edit', [AdminRuijieController::class, 'editProduct'])->name('edit');
        Route::get('/products/{id}/edit', [AdminRuijieController::class, 'editProduct'])->name('products.edit');
        Route::put('/{id}', [AdminRuijieController::class, 'updateProduct'])->name('update');
        Route::put('/products/{id}', [AdminRuijieController::class, 'updateProduct'])->name('products.update');
        Route::delete('/{id}', [AdminRuijieController::class, 'deleteProduct'])->name('destroy');
        Route::delete('/products/{id}', [AdminRuijieController::class, 'deleteProduct'])->name('products.delete');
    });

    Route::prefix('api/admin/ruijie')->name('api.admin.ruijie.')->group(function () {
        Route::get('/products', function () {
            try {
                $products = RuijieProduct::with('category')->orderBy('order')->get();
                return response()->json(['success' => true, 'products' => $products, 'count' => $products->count()]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        })->name('products');
        Route::get('/products/{id}', function ($id) {
            try {
                $product = RuijieProduct::with('category')->findOrFail($id);
                return response()->json(['success' => true, 'product' => $product]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
            }
        })->name('products.show');
        Route::post('/products/{id}/toggle', function ($id) {
            try {
                $product = RuijieProduct::findOrFail($id);
                $product->is_active = !$product->is_active;
                $product->save();
                return response()->json(['success' => true, 'product' => $product]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        })->name('products.toggle');
        Route::get('/statistics', function () {
            try {
                $stats = [
                    'total_products'    => RuijieProduct::count(),
                    'active_products'   => RuijieProduct::where('is_active', true)->count(),
                    'inactive_products' => RuijieProduct::where('is_active', false)->count(),
                    'featured_products' => RuijieProduct::where('is_featured', true)->where('is_active', true)->count(),
                    'total_categories'  => RuijieCategory::count(),
                    'active_categories' => RuijieCategory::where('is_active', true)->count(),
                    'page_active'       => RuijiePageSettings::where('is_active', true)->exists(),
                ];
                return response()->json(['success' => true, 'statistics' => $stats]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        })->name('statistics');
        Route::get('/categories', function () {
            try {
                $categories = RuijieCategory::withCount('products')->orderBy('order')->get();
                return response()->json(['success' => true, 'categories' => $categories, 'count' => $categories->count()]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        })->name('categories');
        Route::get('/settings', function () {
            try {
                $settings = RuijiePageSettings::first();
                if (!$settings) {
                    $settings = RuijiePageSettings::create([
                        'title'             => 'Ruijie Networks',
                        'subtitle'          => 'Solusi networking enterprise-grade',
                        'products_count'    => 500,
                        'clients_count'     => 10000,
                        'satisfaction_rate' => 99,
                    ]);
                }
                return response()->json(['success' => true, 'settings' => $settings]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        })->name('settings');
    });

    // =====================================
    // BOOKKEEPING (Hanya superadmin/admin)
    // =====================================
    Route::get('/admin/bookkeeping', [BookkeepingController::class, 'index'])->name('admin.bookkeeping.index');
    Route::prefix('admin/bookkeeping')->name('admin.bookkeeping.')->group(function () {
        Route::get('/export', [BookkeepingController::class, 'export'])->name('export');
        Route::get('/print', [BookkeepingController::class, 'print'])->name('print');
        Route::get('/create', [BookkeepingController::class, 'create'])->name('create');
        Route::post('/', [BookkeepingController::class, 'store'])->name('store');
        Route::get('/{id}', [BookkeepingController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [BookkeepingController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [BookkeepingController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [BookkeepingController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    });
    Route::prefix('api/admin/bookkeeping')->name('api.admin.bookkeeping.')->group(function () {
        Route::get('/statistics', [BookkeepingController::class, 'getStatisticsApi'])->name('statistics');
        Route::get('/monthly-data', [BookkeepingController::class, 'getMonthlyDataApi'])->name('monthly-data');
        Route::get('/filter', [BookkeepingController::class, 'filterTransactions'])->name('filter');
    });

    // =====================================
    // KEUANGAN BOS
    // =====================================
    Route::get('/admin/keuangan', [KeuanganController::class, 'index'])->name('admin.keuangan');
    Route::prefix('api/admin/keuangan')->group(function () {
        Route::get('/summary',                 [KeuanganController::class, 'getSummary']);
        Route::get('/transaksi',               [KeuanganController::class, 'getTransaksi']);
        Route::get('/chart-data',              [KeuanganController::class, 'getChartData']);
        Route::get('/kategori-breakdown',      [KeuanganController::class, 'getKategoriBreakdown']);
        Route::get('/platform-breakdown',      [KeuanganController::class, 'getPlatformBreakdown']);
        Route::post('/transaksi',              [KeuanganController::class, 'store']);
        Route::get('/transaksi/{id}',          [KeuanganController::class, 'show']);
        Route::put('/transaksi/{id}',          [KeuanganController::class, 'update']);
        Route::patch('/transaksi/{id}/status', [KeuanganController::class, 'updateStatus']);
        Route::delete('/transaksi/{id}',       [KeuanganController::class, 'destroy']);
        Route::post('/generate-link',          [PenjualanLinkController::class, 'generateLink']);
        Route::get('/links',                   [PenjualanLinkController::class, 'getLinks']);
        Route::post('/links/{id}/toggle',      [PenjualanLinkController::class, 'toggleLink']);
        Route::delete('/links/{id}',           [PenjualanLinkController::class, 'deleteLink']);
    });

    // =====================================
    // FINANCE STAFF
    // =====================================
    Route::get('/admin/finance', [FinanceController::class, 'index'])->name('admin.finance');
    Route::get('/admin/gudang/sales-orders/{id}/invoice/download', [SalesOrderController::class, 'downloadInvoicePdf'])
        ->name('admin.finance.invoice.download');
    Route::prefix('api/admin/finance')->group(function () {
        Route::get('/summary',                 [FinanceController::class, 'getSummary']);
        Route::get('/transaksi',               [FinanceController::class, 'getTransaksi']);
        Route::post('/transaksi',              [FinanceController::class, 'store']);
        Route::get('/transaksi/{id}',          [FinanceController::class, 'show']);
        Route::put('/transaksi/{id}',          [FinanceController::class, 'update']);
        Route::patch('/transaksi/{id}/status', [FinanceController::class, 'updateStatus']);
        Route::delete('/transaksi/{id}',       [FinanceController::class, 'destroy']);
        Route::get('/invoices',                [FinanceController::class, 'getInvoices']);
        Route::get('/invoice-detail/{id}',     [FinanceController::class, 'getInvoiceDetail']);
    });

    // =====================================
    // LAPORAN KEUANGAN
    // =====================================
    Route::get('/admin/finance/laporan', [LaporanController::class, 'index'])->name('admin.finance.laporan');
    Route::get('/admin/finance/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('admin.finance.laporan.pdf');
    Route::prefix('api/admin/laporan')->group(function () {
        Route::get('/laba-rugi',    [LaporanController::class, 'labaRugi']);
        Route::get('/cash-flow',    [LaporanController::class, 'cashFlow']);
        Route::get('/export-excel', [LaporanController::class, 'exportExcel']);
    });

    // =====================================
    // NOTIFICATIONS
    // =====================================
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/api/admin/notifikasi', [NotificationController::class, 'index'])->name('api.notifikasi.index');
    Route::patch('/api/admin/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('api.notifikasi.readAll');
    Route::patch('/api/admin/notifikasi/{id}/baca', [NotificationController::class, 'markRead'])->name('api.notifikasi.read');

    // =====================================
    // QUOTATION
    // =====================================
    Route::prefix('admin/quotation')->name('admin.quotation.')->group(function () {
        Route::get('/',                 [QuotationController::class, 'index'])->name('index');
        Route::get('/create',           [QuotationController::class, 'create'])->name('create');
        Route::post('/',                [QuotationController::class, 'store'])->name('store');
        Route::get('/{id}',             [QuotationController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit',        [QuotationController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}',             [QuotationController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}',          [QuotationController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::post('/{id}/send',       [QuotationController::class, 'send'])->name('send')->where('id', '[0-9]+');
        Route::post('/{id}/convert-so', [QuotationController::class, 'convertToSO'])->name('convertSO')->where('id', '[0-9]+');
        Route::get('/{id}/pdf',         [QuotationController::class, 'pdf'])->name('pdf')->where('id', '[0-9]+');
    });

    // =====================================
    // PURCHASE ORDERS
    // =====================================
    Route::get('/admin/purchase-orders/history', [PurchaseOrderController::class, 'history'])->name('admin.po.history');
    Route::prefix('admin/purchase-orders')->name('admin.po.')->group(function () {
        Route::get('/',           [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create',     [PurchaseOrderController::class, 'create'])->name('create');
        Route::get('/{id}/edit',  [PurchaseOrderController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::get('/{id}/print', [PurchaseOrderController::class, 'printPdf'])->name('print')->where('id', '[0-9]+');
        Route::get('/export',     [PurchaseOrderController::class, 'export'])->name('export');
        Route::get('/{id}/pdf',   [PurchaseOrderController::class, 'downloadPdf'])->name('pdf')->where('id', '[0-9]+');
    });
    Route::prefix('api/admin/purchase-orders')->group(function () {
        Route::get('/',              [PurchaseOrderController::class, 'getList']);
        Route::post('/',             [PurchaseOrderController::class, 'store']);
        Route::get('/{id}',          [PurchaseOrderController::class, 'getDetail'])->where('id', '[0-9]+');
        Route::put('/{id}',          [PurchaseOrderController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/{id}',       [PurchaseOrderController::class, 'destroy'])->where('id', '[0-9]+');
        Route::patch('/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->where('id', '[0-9]+');
    });

    // =====================================
    // INVENTORY
    // =====================================
    Route::prefix('admin/inventory')->name('admin.inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/incoming', [InventoryController::class, 'incoming'])->name('incoming');
        Route::get('/incoming-continuous', fn() => view('admin.inventory.incoming'))->name('incoming.continuous');
        Route::get('/incoming-grouped', fn() => view('admin.inventory.incoming-grouped'))->name('incoming.grouped');
        Route::get('/outgoing', [InventoryController::class, 'outgoing'])->name('outgoing');
        Route::get('/outgoing-grouped', fn() => view('admin.inventory.outgoing-grouped'))->name('outgoing.grouped');
        Route::get('/grouped', fn() => view('admin.inventory.grouped-view'))->name('grouped.index');
        Route::get('/reports', [InventoryController::class, 'reports'])->name('reports');
        Route::get('/export', [InventoryController::class, 'export'])->name('export');
    });
    Route::prefix('api/admin/inventory')->name('api.admin.inventory.')->group(function () {
        Route::get('/items',                     [InventoryController::class, 'getItems'])->name('items');
        Route::get('/statistics',                [InventoryController::class, 'getStatistics'])->name('statistics');
        Route::get('/items-grouped',             [InventoryController::class, 'getItemsGrouped'])->name('items-grouped');
        Route::get('/products/{id}/serials',     [InventoryController::class, 'getProductSerials'])->name('products.serials');
        Route::post('/incoming-grouped',         [InventoryController::class, 'storeIncomingGrouped'])->name('incoming.grouped.store');
        Route::post('/outgoing-grouped',         [InventoryController::class, 'processOutgoingGrouped'])->name('outgoing.grouped.process');
        Route::post('/convert-to-grouped',       [InventoryController::class, 'convertToGrouped'])->name('convert-to-grouped');
        Route::get('/statistics-grouped',        [InventoryController::class, 'getGroupedStatistics'])->name('statistics-grouped');
        Route::get('/search-grouped',            [InventoryController::class, 'searchGroupedProducts'])->name('search-grouped');
        Route::get('/check-stock',               [InventoryController::class, 'checkStock'])->name('check-stock');
        Route::get('/stock-summary',             [InventoryController::class, 'getStockSummary'])->name('stock-summary');
        Route::post('/items/{id}/update-stock',  [InventoryController::class, 'updateStock'])->name('items.update-stock');
        Route::post('/check-serial',             [InventoryController::class, 'checkSerialNumber'])->name('check-serial');
        Route::post('/check-serial-batch',       [InventoryController::class, 'checkSerialNumbersBatch'])->name('check-serial-batch');
        Route::post('/incoming',                 [InventoryController::class, 'storeIncoming'])->name('incoming.store');
        Route::post('/incoming-batch',           [InventoryController::class, 'storeIncomingBatch'])->name('incoming.store-batch');
        Route::post('/incoming-batch-optimized', [InventoryController::class, 'storeIncomingBatchOptimized'])->name('incoming.store-batch-optimized');
        Route::post('/validate-bulk',            [InventoryController::class, 'validateBulkSerialNumbers'])->name('validate-bulk');
        Route::get('/bulk-statistics',           [InventoryController::class, 'getBulkScanStatistics'])->name('bulk-statistics');
        Route::post('/outgoing',                 [InventoryController::class, 'processOutgoing'])->name('outgoing.process');
        Route::get('/items/{id}',                [InventoryController::class, 'show'])->name('items.show');
        Route::put('/items/{id}',                [InventoryController::class, 'update'])->name('items.update');
        Route::delete('/items/{id}',             [InventoryController::class, 'destroy'])->name('items.destroy');
        Route::get('/brands',                    [InventoryController::class, 'getBrands'])->name('brands');
        Route::get('/categories',                [InventoryController::class, 'getCategories'])->name('categories');
    });

    // =====================================
    // GUDANG / SALES ORDERS
    // =====================================
    Route::get('/admin/gudang', [App\Http\Controllers\Admin\GudangController::class, 'index'])->name('admin.gudang');
    Route::prefix('admin/gudang/sales-orders')->name('admin.sales-orders.')->group(function () {
        Route::get('/',       [SalesOrderController::class, 'index'])->name('index');
        Route::get('/create', [SalesOrderController::class, 'create'])->name('create');
        Route::post('/',      [SalesOrderController::class, 'store'])->name('store');
        Route::get('/{id}',    [SalesOrderController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::delete('/{id}', [SalesOrderController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::get('/{id}/approve',  [SalesOrderController::class, 'approveForm'])->name('approve-form')->where('id', '[0-9]+');
        Route::post('/{id}/approve', [SalesOrderController::class, 'approve'])->name('approve')->where('id', '[0-9]+');
        Route::post('/{id}/deliver', [SalesOrderController::class, 'deliver'])->name('deliver')->where('id', '[0-9]+');
        Route::post('/{id}/cancel',  [SalesOrderController::class, 'cancel'])->name('cancel')->where('id', '[0-9]+');
        Route::get('/{id}/pdf',         [SalesOrderController::class, 'downloadPdf'])->name('pdf')->where('id', '[0-9]+');
        Route::get('/{id}/preview-pdf', [SalesOrderController::class, 'previewPdf'])->name('preview-pdf')->where('id', '[0-9]+');
        Route::post('/{id}/send-email', [SalesOrderController::class, 'sendEmail'])->name('send-email')->where('id', '[0-9]+');
        Route::get('/{id}/create-invoice',      [SalesOrderController::class, 'createInvoiceForm'])->name('create-invoice')->where('id', '[0-9]+');
        Route::post('/{id}/store-invoice',      [SalesOrderController::class, 'storeInvoice'])->name('store-invoice')->where('id', '[0-9]+');
        Route::post('/{id}/mark-lunas',         [SalesOrderController::class, 'markLunas'])->name('mark-lunas')->where('id', '[0-9]+');
        Route::get('/{id}/invoice/download',    [SalesOrderController::class, 'downloadInvoicePdf'])->name('invoice-download')->where('id', '[0-9]+');
        Route::get('/{id}/invoice/preview',     [SalesOrderController::class, 'previewInvoicePdf'])->name('invoice-preview')->where('id', '[0-9]+');
        Route::post('/{id}/invoice/send-email', [SalesOrderController::class, 'sendInvoiceEmail'])->name('invoice-send-email')->where('id', '[0-9]+');
    });
    Route::prefix('api/admin/gudang')->group(function () {
        Route::get('/products',              [App\Http\Controllers\Admin\GudangController::class, 'getProducts']);
        Route::get('/categories',            [App\Http\Controllers\Admin\GudangController::class, 'getCategories']);
        Route::get('/history/{id}',          [App\Http\Controllers\Admin\GudangController::class, 'getHistory']);
        Route::post('/barang-masuk',         [App\Http\Controllers\Admin\GudangController::class, 'storeBarangMasuk']);
        Route::delete('/barang-masuk/{id}',  [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangMasuk']);
        Route::post('/barang-keluar',        [App\Http\Controllers\Admin\GudangController::class, 'storeBarangKeluar']);
        Route::delete('/barang-keluar/{id}', [App\Http\Controllers\Admin\GudangController::class, 'destroyBarangKeluar']);
        Route::delete('/products/{id}',      [App\Http\Controllers\Admin\GudangController::class, 'destroyProduct']);
        Route::get('/available-serials',     [App\Http\Controllers\Admin\GudangController::class, 'getAvailableSerials']);
        Route::get('/product-use-sn',        [App\Http\Controllers\Admin\GudangController::class, 'productUseSerialNumber']);
    });

    // =====================================
    // SALES DOCUMENTS
    // =====================================
    Route::get('/admin/sales-documents', fn() => view('admin.sales-documents'))->name('admin.sales-documents');

    // =====================================
    // DYNAMIC PRODUCTS ADMIN
    // =====================================
    Route::prefix('admin/products')->name('admin.products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-status', [AdminProductController::class, 'bulkUpdateStatus'])->name('bulk-status');
        Route::get('/export', [AdminProductController::class, 'export'])->name('export');
    });

    // =====================================
    // API ADMIN (Protected)
    // =====================================
    Route::get('/api/admin/statistics', [UnifiedAdminController::class, 'getStatistics']);
    Route::get('/api/products', [UnifiedAdminController::class, 'getDynamicProducts']);
    Route::get('/api/products/{id}', [UnifiedAdminController::class, 'showDynamicProduct']);
    Route::post('/api/products', [UnifiedAdminController::class, 'storeDynamicProduct']);
    Route::post('/api/products/{id}', [UnifiedAdminController::class, 'updateDynamicProduct']);
    Route::delete('/api/products/{id}', [UnifiedAdminController::class, 'deleteDynamicProduct']);
    Route::get('/api/static-products', [UnifiedAdminController::class, 'getStaticProducts']);
    Route::post('/api/static-products/{id}', [UnifiedAdminController::class, 'updateStaticProduct']);
    Route::post('/api/static-products/{id}/toggle', [UnifiedAdminController::class, 'toggleStaticProductStatus']);
    Route::post('/api/static-products/bulk-update', [UnifiedAdminController::class, 'bulkUpdateStaticProducts']);
    Route::get('/api/all-products', [UnifiedAdminController::class, 'getAllProducts']);

    // =====================================
    // USER & ROLES MANAGEMENT
    // =====================================
    Route::group(['prefix' => 'manage-users'], function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::group(['prefix' => 'admin/roles', 'as' => 'admin.roles.'], function () {
        Route::get('/', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
    });

    // =====================================
    // KALKULATOR MODAL
    // =====================================
    Route::get('/admin/modal/kalkulator', [App\Http\Controllers\Admin\KalkulatorModalController::class, 'index'])
        ->name('admin.modal.kalkulator');

}); // END middleware admin.auth

// Pastikan ini berada di dalam group route admin Anda (misalnya yang dibungkus auth/admin)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Route Testimoni
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    
});
// =====================================
// LEGACY ROUTES
// =====================================
Route::prefix('products')->group(function () {
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
});

// =====================================
// SITEMAP
// =====================================
Route::get('/generate-sitemap', function () {
    SitemapGenerator::create('https://techstorecctv.com')->writeToFile(public_path('sitemap.xml'));
    return 'Berhasil! Sitemap.xml sudah berhasil dibuat di folder public.';
});

// =====================================
// FALLBACK (404)
// =====================================
Route::fallback(function () {
    if (view()->exists('errors.404')) {
        return response()->view('errors.404', [], 404);
    }
    return response()->view('errors.minimal', [
        'code'        => '404',
        'message'     => 'Page Not Found',
        'description' => 'The page you are looking for does not exist.',
    ], 404);
});
Route::get('/setup-roles-rahasia', function () {
    try {
        // 1. Jalankan Seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'RolePermissionSeeder'
        ]);
        
        // 2. Clear Cache Spatie
        \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
        
        return '✅ Sukses, Den! Roles, Permissions, dan akun Superadmin sudah masuk ke database online.';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});