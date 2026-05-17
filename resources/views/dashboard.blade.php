<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - TechStore</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }

        /* Sidebar */
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; box-shadow: 2px 0 15px rgba(0,0,0,0.1); z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 0 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.2); }
        .sidebar-header h2 { font-size: 24px; margin-bottom: 5px; font-weight: 700; }
        .sidebar-header p { font-size: 13px; opacity: 0.85; }
        .sidebar-menu { padding: 20px 0 100px; }
        .menu-section-title { padding: 20px 24px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; opacity: 0.6; letter-spacing: 1px; }
        .menu-item { padding: 14px 24px; display: flex; align-items: center; gap: 12px; color: white; text-decoration: none; transition: all 0.3s; cursor: pointer; border-left: 4px solid transparent; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; font-weight: bold; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); color: white; }

        /* Main Content */
        .main-content { margin-left: 280px; padding: 30px; min-height: 100vh; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 32px; color: #2d3748; margin-bottom: 8px; font-weight: 700; }
        .page-header p { color: #718096; font-size: 15px; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 24px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .stat-header { display: flex; align-items: center; gap: 16px; margin-bottom: 12px; position: relative; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        
        .icon-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-success { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
        .icon-warning { background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); }
        .icon-info { background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); }
        .icon-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        
        .stat-info h3 { font-size: 12px; color: #718096; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; }
        .stat-value { font-size: 28px; font-weight: 800; color: #2d3748; line-height: 1; }
        .stat-change { font-size: 12px; font-weight: 600; margin-top: 8px; color: #48bb78; }

        /* Modules Management */
        .products-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
        .product-category-card { background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s; }
        .product-category-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .category-header { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
        .category-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .category-info h3 { font-size: 20px; color: #2d3748; margin-bottom: 6px; font-weight: 700; }
        .category-count { font-size: 14px; color: #718096; font-weight: 500; }
        .category-actions { display: flex; gap: 10px; margin-top: 20px; }
        
        .btn { padding: 12px 20px; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; flex: 1; }
        .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #4a5568; flex: 1; }
        .btn-outline:hover { border-color: #667eea; background: #f7faff; color: #667eea; }
        
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; margin-top: 40px; }
        .section-header h2 { font-size: 24px; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 12px; }
        .loading { display: inline-block; width: 18px; height: 18px; border: 2px solid #f3f3f3; border-top: 2px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        @media(max-width: 992px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>🏢 PT Trac</h2>
        <p>Unified Admin Dashboard</p>
    </div>
    
    <div class="sidebar-menu">
        <a href="/dashboard" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>

        <div class="menu-section-title">Operations Management</div>
        
        @canany(['view_inventory', 'manage_inventory'])
        <a href="/admin/gudang" class="menu-item {{ request()->is('admin/gudang*') && !request()->is('admin/gudang/sales-orders*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i><span>Gudang</span></a>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <a href="{{ route('admin.po.index') }}" class="menu-item {{ request()->routeIs('admin.po.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i><span>Purchase Order</span></a>
        @endcanany
        
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <a href="/admin/gudang/sales-orders" class="menu-item {{ request()->is('admin/gudang/sales-orders*') ? 'active' : '' }}"><i class="bi bi-file-earmark-check"></i><span>Sales Order</span></a>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <a href="{{ route('admin.quotation.index') }}" class="menu-item {{ request()->routeIs('admin.quotation.*') ? 'active' : '' }}"><i class="bi bi-file-text"></i><span>Quotation</span></a>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <a href="/admin/keuangan" class="menu-item {{ request()->is('admin/keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i><span>Keuangan Boss</span></a>
        @endcanany

        @can('manage_finance')
        <a href="/admin/finance" class="menu-item {{ request()->is('admin/finance*') ? 'active' : '' }}"><i class="bi bi-receipt"></i><span>Finance Staff</span></a>
        @endcan

        @can('view_kalkulator')
        <a href="{{ route('admin.modal.kalkulator') }}" class="menu-item {{ request()->routeIs('admin.modal.kalkulator') ? 'active' : '' }}"><i class="bi bi-calculator"></i><span>Kalkulator Modal</span></a>
        @endcan

        <div class="menu-section-title">Products Management</div>
        
        @canany(['view_ruijie', 'manage_ruijie'])
        <a href="/admin/ruijie" class="menu-item {{ request()->is('admin/ruijie*') ? 'active' : '' }}"><i class="bi bi-router"></i><span>Ruijie Networks</span></a>
        @endcanany
        
        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <a href="/admin/wifi-cameras" class="menu-item {{ request()->is('admin/wifi-cameras*') ? 'active' : '' }}"><i class="bi bi-camera-video"></i><span>WiFi Cameras</span></a>
        @endcanany
        
        @canany(['view_access_control', 'manage_access_control'])
        <a href="/admin/access-control" class="menu-item {{ request()->is('admin/access-control*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span>Access Control</span></a>
        @endcanany
        
        @canany(['view_static_products', 'manage_static_products'])
        <a href="/admin/static-products" class="menu-item {{ request()->is('admin/static-products*') ? 'active' : '' }}"><i class="bi bi-box"></i><span>Static Products</span></a>
        @endcanany

        <div class="menu-section-title">System</div>
        
        @canany(['view_users', 'manage_users'])
        <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-people"></i><span>Users Account</span></a>
        @endcanany

        @can('manage_roles')
        <a href="{{ route('admin.roles.index') }}" class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span>Roles & Permissions</span><span class="badge">SECURE</span></a>
        @endcan

        {{-- MENU BARU: TESTIMONI --}}
        @canany(['view_testimonials', 'manage_testimonials'])
        <a href="{{ route('admin.testimonials.index') }}" class="menu-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"><i class="bi bi-images"></i><span>Testimoni & Instalasi</span></a>
        @endcanany
        
    </div>
    
    <a href="{{ route('admin.logout') }}" class="logout-btn" onclick="hapusJejakBrowser(event)">
        <i class="bi bi-box-arrow-right"></i><span>Logout</span>
    </a>
</div>

<div class="main-content">
    <div class="page-header">
        <h1>📊 Dashboard Overview</h1>
        <p>Selamat datang di Unified Admin Panel - Kelola semua operasional dari satu tempat</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-danger"><i class="bi bi-boxes" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Jenis Produk Gudang</h3>
                    <div class="stat-value" id="stat-inventory-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="inventoryStatus">Loading data...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-primary"><i class="bi bi-router" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Ruijie Networks</h3>
                    <div class="stat-value" id="stat-ruijie-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="ruijieStatus">Loading data...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-success"><i class="bi bi-camera-video" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>WiFi Cameras</h3>
                    <div class="stat-value" id="stat-wifi-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="camerasStatus">Loading data...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-warning"><i class="bi bi-shield-lock" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Access Control</h3>
                    <div class="stat-value" id="stat-access-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="accessStatus">Loading data...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-info"><i class="bi bi-box-seam" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Total Seluruh Produk</h3>
                    <div class="stat-value" id="totalProducts"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change">↗ Gabungan semua modul</div>
        </div>
    </div>

    <div class="section-header">
        <h2><i class="bi bi-grid-3x3"></i> Modules Management</h2>
    </div>
    
    <div class="products-overview">
        
        @canany(['view_inventory', 'manage_inventory'])
        <div class="product-category-card" style="border-left: 4px solid #ef4444;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="bi bi-box-seam" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Gudang</h3>
                    <p class="category-count"><span id="inventoryTotal">-</span> jenis produk • <span id="inventoryInStock">-</span> total stok</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/gudang" class="btn btn-primary"><i class="bi bi-speedometer2"></i><span>Buka Gudang</span></a>
            </div>
        </div>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <div class="product-category-card" style="border-left: 4px solid #805ad5;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #9f7aea 0%, #6b46c1 100%);"><i class="bi bi-cart-check" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Purchase Order (PO)</h3>
                    <p class="category-count">Kelola pembelian barang ke Supplier</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.po.index') }}" class="btn btn-primary"><i class="bi bi-list-ul"></i><span>Daftar PO</span></a>
                @canany(['create_purchase_orders', 'manage_purchase_orders'])
                <a href="{{ route('admin.po.create') }}" class="btn btn-outline"><i class="bi bi-plus-circle"></i><span>Buat Baru</span></a>
                @endcanany
            </div>
        </div>
        @endcanany

        @canany(['view_sales_orders', 'manage_sales_orders'])
        <div class="product-category-card" style="border-left: 4px solid #f6ad55;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);"><i class="bi bi-file-earmark-check" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Sales Order (SO)</h3>
                    <p class="category-count">Kelola pesanan dan pengiriman barang</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/gudang/sales-orders" class="btn btn-primary"><i class="bi bi-list-check"></i><span>Daftar SO</span></a>
                @canany(['create_sales_orders', 'manage_sales_orders'])
                <a href="/admin/gudang/sales-orders/create" class="btn btn-outline"><i class="bi bi-plus-circle"></i><span>Buat Baru</span></a>
                @endcanany
            </div>
        </div>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <div class="product-category-card" style="border-left: 4px solid #3182ce;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #63b3ed 0%, #2b6cb0 100%);"><i class="bi bi-file-text" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Quotation (Penawaran)</h3>
                    <p class="category-count">Buat penawaran harga untuk customer</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.quotation.index') }}" class="btn btn-primary"><i class="bi bi-file-text"></i><span>Daftar Penawaran</span></a>
                @canany(['create_quotation', 'manage_quotation'])
                <a href="{{ route('admin.quotation.create') }}" class="btn btn-outline"><i class="bi bi-plus-circle"></i><span>Buat Baru</span></a>
                @endcanany
            </div>
        </div>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <div class="product-category-card" style="border-left: 4px solid #48bb78;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);"><i class="bi bi-wallet2" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Keuangan Boss</h3>
                    <p class="category-count">Kelola arus kas & laporan penjualan</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/keuangan" class="btn btn-primary"><i class="bi bi-cash-stack"></i><span>Buka Modul</span></a>
            </div>
        </div>
        @endcanany

        @can('manage_finance')
        <div class="product-category-card" style="border-left: 4px solid #10b981;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"><i class="bi bi-receipt-cutoff" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Finance Staff</h3>
                    <p class="category-count">Input Piutang, Pengeluaran & Lunas</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/finance" class="btn btn-primary"><i class="bi bi-cash-coin"></i><span>Buka Finance Staff</span></a>
            </div>
        </div>
        @endcan

        @can('view_kalkulator')
        <div class="product-category-card" style="border-left: 4px solid #d53f8c;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%);"><i class="bi bi-calculator" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Kalkulator Modal</h3>
                    <p class="category-count">Hitung estimasi modal & margin profit</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.modal.kalkulator') }}" class="btn btn-primary"><i class="bi bi-calculator-fill"></i><span>Buka Kalkulator</span></a>
            </div>
        </div>
        @endcan

        @canany(['view_ruijie', 'manage_ruijie'])
        <div class="product-category-card" style="border-left: 4px solid #4299e1;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);"><i class="bi bi-router" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Ruijie Networks</h3>
                    <p class="category-count"><span id="ruijieTotal">-</span> products • <span id="ruijieActive">-</span> active</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/ruijie/products" class="btn btn-primary"><i class="bi bi-gear"></i><span>Manage</span></a>
                <a href="/products/ruijie" target="_blank" class="btn btn-outline"><i class="bi bi-eye"></i><span>View</span></a>
            </div>
        </div>
        @endcanany

        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <div class="product-category-card" style="border-left: 4px solid #38b2ac;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #4fd1c5 0%, #319795 100%);"><i class="bi bi-camera-video" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>WiFi Cameras</h3>
                    <p class="category-count"><span id="camerasTotal">-</span> products • <span id="camerasActive">-</span> active</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/wifi-cameras" class="btn btn-primary"><i class="bi bi-gear"></i><span>Manage</span></a>
                <a href="/wifi-cam" target="_blank" class="btn btn-outline"><i class="bi bi-eye"></i><span>View</span></a>
            </div>
        </div>
        @endcanany

        @canany(['view_access_control', 'manage_access_control'])
        <div class="product-category-card" style="border-left: 4px solid #ecc94b;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #ecc94b 0%, #d69e2e 100%);"><i class="bi bi-shield-lock" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Access Control</h3>
                    <p class="category-count"><span id="accessTotal">-</span> systems • Online</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/access-control" class="btn btn-primary"><i class="bi bi-gear"></i><span>Manage</span></a>
                <a href="/access-control" target="_blank" class="btn btn-outline"><i class="bi bi-eye"></i><span>View</span></a>
            </div>
        </div>
        @endcanany

        @canany(['view_static_products', 'manage_static_products'])
        <div class="product-category-card" style="border-left: 4px solid #a0aec0;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);"><i class="bi bi-box" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Static Products</h3>
                    <p class="category-count">Kelola produk statis & aksesoris</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/static-products" class="btn btn-primary"><i class="bi bi-gear"></i><span>Manage</span></a>
            </div>
        </div>
        @endcanany

        @canany(['view_users', 'manage_users'])
        <div class="product-category-card" style="border-left: 4px solid #764ba2;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><i class="bi bi-person-gear" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>User Management</h3>
                    <p class="category-count">Admin, Superadmin & Staff Access</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary"><i class="bi bi-shield-lock"></i><span>Users</span></a>
                @can('manage_roles')
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline"><i class="bi bi-key"></i><span>Roles</span></a>
                @endcan
            </div>
        </div>
        @endcanany

        {{-- CARD BARU: TESTIMONI --}}
        @canany(['view_testimonials', 'manage_testimonials'])
        <div class="product-category-card" style="border-left: 4px solid #ec4899;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #f472b6 0%, #db2777 100%);"><i class="bi bi-images" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Testimoni & Instalasi</h3>
                    <p class="category-count">Kelola foto hasil pemasangan CCTV</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-primary"><i class="bi bi-images"></i><span>Kelola Galeri</span></a>
            </div>
        </div>
        @endcanany

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAllStatistics();
});

async function loadAllStatistics() {
    let totalCount = 0;
    try {
        const res = await fetch('/api/admin/gudang/products');
        const data = await res.json();
        if (data.summary) {
            const s = data.summary;
            if(document.getElementById('stat-inventory-val')) document.getElementById('stat-inventory-val').textContent = s.total_produk || 0;
            if(document.getElementById('inventoryTotal')) document.getElementById('inventoryTotal').textContent = s.total_produk || 0;
            if(document.getElementById('inventoryInStock')) document.getElementById('inventoryInStock').textContent = s.total_stok || 0;
            if(document.getElementById('inventoryStatus')) document.getElementById('inventoryStatus').textContent = `${s.total_stok || 0} total unit stok`;
            totalCount += (s.total_produk || 0);
        }
    } catch (e) { console.error('Gudang error', e); }
    try {
        const res = await fetch('/api/admin/ruijie/statistics');
        const data = await res.json();
        if (data.success) {
            const s = data.statistics;
            if(document.getElementById('stat-ruijie-val')) document.getElementById('stat-ruijie-val').textContent = s.total_products || 0;
            if(document.getElementById('ruijieTotal')) document.getElementById('ruijieTotal').textContent = s.total_products || 0;
            if(document.getElementById('ruijieActive')) document.getElementById('ruijieActive').textContent = s.active_products || 0;
            if(document.getElementById('ruijieStatus')) document.getElementById('ruijieStatus').textContent = `${s.active_products || 0} active products`;
            totalCount += (s.total_products || 0);
        }
    } catch (e) { console.error('Ruijie error', e); }
    try {
        const res = await fetch('/api/admin/wifi-cameras');
        const data = await res.json();
        if (data.success) {
            const count = data.cameras.length;
            if(document.getElementById('stat-wifi-val')) document.getElementById('stat-wifi-val').textContent = count;
            if(document.getElementById('camerasTotal')) document.getElementById('camerasTotal').textContent = count;
            if(document.getElementById('camerasActive')) document.getElementById('camerasActive').textContent = count;
            if(document.getElementById('camerasStatus')) document.getElementById('camerasStatus').textContent = `${count} active cameras`;
            totalCount += count;
        }
    } catch (e) { console.error('WiFi error', e); }
    try {
        const res = await fetch('/api/admin/access-control/statistics');
        const data = await res.json();
        if (data.success) {
            const count = data.statistics.total;
            if(document.getElementById('stat-access-val')) document.getElementById('stat-access-val').textContent = count;
            if(document.getElementById('accessTotal')) document.getElementById('accessTotal').textContent = count;
            if(document.getElementById('accessStatus')) document.getElementById('accessStatus').textContent = `${data.statistics.active || 0} active systems`;
            totalCount += count;
        }
    } catch (e) { console.error('Access error', e); }
    if(document.getElementById('totalProducts')) {
        document.getElementById('totalProducts').textContent = totalCount;
    }
}

function hapusJejakBrowser(event) {
    event.preventDefault();
    localStorage.clear(); 
    sessionStorage.clear();
    window.location.href = '/admin/logout';
}
</script>
</body>
</html>