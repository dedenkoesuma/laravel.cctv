<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unified Admin Console - PT Trac & PT. MJA TEKNOLOGI</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 270px;
            --slate-950: #090d16;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50:  #f8fafc;
            --primary:   #dc2626;
            --primary-dark: #b91c1c;
            --primary-light: rgba(220, 38, 38, 0.1);
            --emerald-500: #10b981;
            --emerald-100: rgba(16, 185, 129, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--slate-900);
            min-height: 100vh;
        }

        /* ===== SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background-color: var(--slate-950);
            border-right: 1px solid var(--slate-800);
            color: #ffffff;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand-box {
            padding: 22px 20px 20px;
            border-bottom: 1px solid var(--slate-800);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #ffffff;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 11.5px;
            color: var(--slate-400);
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }

        .status-dot-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: var(--emerald-500);
            box-shadow: 0 0 8px var(--emerald-500);
            display: inline-block;
        }

        /* Sidebar Navigation Scrollable Area */
        .sidebar-nav-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px 20px;
            scrollbar-width: thin;
            scrollbar-color: var(--slate-800) transparent;
        }

        .sidebar-nav-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav-scroll::-webkit-scrollbar-thumb {
            background-color: var(--slate-800);
            border-radius: 4px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--slate-500);
            padding: 16px 12px 8px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-item-link i {
            font-size: 17px;
            color: var(--slate-400);
            width: 20px;
            text-align: center;
            transition: color 0.2s;
        }

        .nav-item-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .nav-item-link:hover i {
            color: #ffffff;
        }

        .nav-item-link.active {
            background-color: rgba(220, 38, 38, 0.15);
            color: #ffffff;
            font-weight: 600;
            border: 1px solid rgba(220, 38, 38, 0.25);
        }

        .nav-item-link.active i {
            color: #f87171;
        }

        /* Sidebar Footer / User Profile & Logout */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--slate-800);
            background-color: #060911;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding: 8px 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .user-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--slate-800);
            border: 1px solid var(--slate-700);
            color: #f87171;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-info-text {
            overflow: hidden;
            flex: 1;
        }

        .user-name-label {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .user-role-label {
            font-size: 11px;
            color: var(--slate-400);
            text-transform: capitalize;
        }

        .btn-sidebar-logout {
            width: 100%;
            padding: 9px 12px;
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.25);
            color: #fca5a5;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-sidebar-logout:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* ===== MAIN CONTENT AREA ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px 36px 60px;
            min-height: 100vh;
        }

        /* Top Bar Header */
        .top-console-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--slate-200);
        }

        .breadcrumb-corp {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .page-main-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .page-sub-title {
            color: var(--slate-500);
            font-size: 14px;
            margin: 0;
        }

        .header-actions-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge-clock-widget {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-700);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-view-site {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--slate-300);
            color: var(--slate-700);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-view-site:hover {
            border-color: var(--slate-900);
            color: var(--slate-900);
            background: var(--slate-50);
        }

        .btn-refresh-stats {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--slate-900);
            border: 1px solid var(--slate-900);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-refresh-stats:hover {
            background: var(--slate-800);
        }

        /* ===== STATS KPI GRID (TOP ROW) ===== */
        .stats-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 34px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease;
            position: relative;
        }

        .kpi-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.06);
        }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .kpi-label {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--slate-500);
        }

        .kpi-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .kpi-val-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1;
            margin-bottom: 8px;
            font-variant-numeric: tabular-nums;
        }

        .kpi-status-tag {
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ===== MODULES MANAGEMENT GRID (14 CARDS) ===== */
        .modules-section-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .modules-section-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modules-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .module-item-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
            position: relative;
        }

        .module-item-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.07);
        }

        .module-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .module-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .module-category-pill {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 4px;
            background: var(--slate-100);
            color: var(--slate-600);
            border: 1px solid var(--slate-200);
        }

        .module-info-body {
            margin-bottom: 18px;
            flex-grow: 1;
        }

        .module-title-text {
            font-size: 16px;
            font-weight: 700;
            color: var(--slate-900);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .module-desc-text {
            font-size: 13px;
            color: var(--slate-500);
            line-height: 1.5;
            margin: 0;
        }

        .module-actions-strip {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: auto;
        }

        .btn-module-primary {
            flex: 1;
            background-color: var(--slate-900);
            color: #ffffff;
            border: 1px solid var(--slate-900);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-module-primary:hover {
            background-color: #1e293b;
            color: #ffffff;
            border-color: #1e293b;
        }

        .btn-module-outline {
            flex: 1;
            background-color: transparent;
            color: var(--slate-700);
            border: 1px solid var(--slate-300);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-module-outline:hover {
            background-color: var(--slate-100);
            color: var(--slate-900);
            border-color: var(--slate-400);
        }

        /* Loading Spinner */
        .loading {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile drawer toggle */
        .mobile-menu-trigger {
            display: none;
            background: #ffffff;
            border: 1px solid var(--slate-300);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 18px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .mobile-menu-trigger {
                display: inline-block;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="adminSidebar">
    <!-- Brand Box -->
    <div class="sidebar-brand-box">
        <div class="brand-icon-box">
            <i class="bi bi-shield-check"></i>
        </div>
        <div class="brand-text">
            <h2>PT TRAC</h2>
            <p>
                <span class="status-dot-pulse"></span>
                <span>Console Active</span>
            </p>
        </div>
    </div>
    
    <!-- Nav Scrollable -->
    <div class="sidebar-nav-scroll">
        <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') || request()->is('admin/dashboard*') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard CCTV</span>
        </a>
        <a href="{{ route('dashboard') }}" class="nav-item-link" style="color: #cbd5e1;">
            <i class="bi bi-printer text-warning"></i>
            <span>Dashboard Toko Print</span>
            <span style="font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: rgba(245, 158, 11, 0.15); color: #FBBF24; margin-left: auto;">PRINT</span>
        </a>

        <!-- 1. OPERATIONS MANAGEMENT -->
        <div class="nav-section-title">Operations Management</div>
        
        @canany(['view_inventory', 'manage_inventory'])
        <a href="/admin/gudang" class="nav-item-link {{ request()->is('admin/gudang*') && !request()->is('admin/gudang/sales-orders*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Gudang (Inventory)</span>
        </a>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <a href="{{ route('admin.po.index') }}" class="nav-item-link {{ request()->routeIs('admin.po.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i>
            <span>Purchase Order (PO)</span>
        </a>
        @endcanany
        
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <a href="/admin/gudang/sales-orders" class="nav-item-link {{ request()->is('admin/gudang/sales-orders*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-check"></i>
            <span>Sales Order (SO)</span>
        </a>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <a href="{{ route('admin.quotation.index') }}" class="nav-item-link {{ request()->routeIs('admin.quotation.*') ? 'active' : '' }}">
            <i class="bi bi-file-text"></i>
            <span>Quotation (Penawaran)</span>
        </a>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <a href="/admin/keuangan" class="nav-item-link {{ request()->is('admin/keuangan*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Keuangan Boss</span>
        </a>
        @endcanany

        @can('manage_finance')
        <a href="/admin/finance" class="nav-item-link {{ request()->is('admin/finance*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i>
            <span>Finance Staff</span>
        </a>
        @endcan

        @can('view_kalkulator')
        <a href="{{ route('admin.modal.kalkulator') }}" class="nav-item-link {{ request()->routeIs('admin.modal.kalkulator') ? 'active' : '' }}">
            <i class="bi bi-calculator"></i>
            <span>Kalkulator Modal</span>
        </a>
        @endcan

        <a href="{{ route('modal-paket.index') }}" class="nav-item-link {{ request()->routeIs('modal-paket.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i>
            <span>Modal Paket</span>
        </a>

        <!-- 2. PRODUCTS MANAGEMENT -->
        <div class="nav-section-title">Products Management</div>

        @canany(['view_ruijie', 'manage_ruijie'])
        <a href="/admin/ruijie/products" class="nav-item-link {{ request()->is('admin/ruijie*') ? 'active' : '' }}">
            <i class="bi bi-router"></i>
            <span>Ruijie Networks</span>
        </a>
        @endcanany

        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <a href="/admin/wifi-cameras" class="nav-item-link {{ request()->is('admin/wifi-cameras*') ? 'active' : '' }}">
            <i class="bi bi-camera-video"></i>
            <span>WiFi Cameras</span>
        </a>
        @endcanany

        @canany(['view_access_control', 'manage_access_control'])
        <a href="/admin/access-control" class="nav-item-link {{ request()->is('admin/access-control*') ? 'active' : '' }}">
            <i class="bi bi-fingerprint"></i>
            <span>Access Control</span>
        </a>
        @endcanany

        @canany(['view_static_products', 'manage_static_products'])
        <a href="/admin/static-products" class="nav-item-link {{ request()->is('admin/static-products*') ? 'active' : '' }}">
            <i class="bi bi-box"></i>
            <span>Static Products</span>
        </a>
        @endcanany

        <!-- 3. SYSTEM & SECURITY -->
        <div class="nav-section-title">System & Security</div>

        @canany(['view_users', 'manage_users'])
        <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i>
            <span>Users Account</span>
        </a>
        @endcanany

        @can('manage_roles')
        <a href="{{ route('admin.roles.index') }}" class="nav-item-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i>
            <span>Roles & Permissions</span>
        </a>
        @endcan

        @canany(['view_testimonials', 'manage_testimonials'])
        <a href="{{ route('admin.testimonials.index') }}" class="nav-item-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i>
            <span>Testimoni & Instalasi</span>
        </a>
        @endcanany
    </div>
    
    <!-- Sidebar Bottom Profile & Logout -->
    <div class="sidebar-footer">
        <div class="user-profile-badge">
            <div class="user-avatar-circle">
                {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
            </div>
            <div class="user-info-text">
                <div class="user-name-label">{{ session('admin_name', 'Administrator') }}</div>
                <div class="user-role-label">{{ session('admin_role', 'Super Admin') }}</div>
            </div>
        </div>

        <a href="{{ route('admin.logout') }}" class="btn-sidebar-logout" onclick="hapusJejakBrowser(event)">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar (Logout)</span>
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content">
    <!-- Top Console Header -->
    <div class="top-console-header">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <button type="button" class="mobile-menu-trigger" onclick="toggleSidebar()" title="Toggle Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="breadcrumb-corp">
                    <span>Admin</span>
                    <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
                    <span>Console</span>
                    <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
                    <span class="text-dark">Overview</span>
                </div>
            </div>
            <h1 class="page-main-title">Dashboard Overview</h1>
            <p class="page-sub-title">Kelola seluruh modul operasional, gudang, transaksi, dan katalog CCTV terpusat.</p>
        </div>

        <div class="header-actions-group">
            <div class="badge-clock-widget">
                <i class="bi bi-clock-fill text-danger"></i>
                <span id="liveClockWidget">--:--:-- WIB</span>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="btn-view-site">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat Website</span>
            </a>
            <button type="button" class="btn-refresh-stats" onclick="refreshDashboardData()">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
                <span>Sync Data</span>
            </button>
        </div>
    </div>

    <!-- 5 TOP STATS KPI CARDS -->
    <div class="stats-kpi-grid">
        <!-- 1. Gudang -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Produk Gudang</span>
                <div class="kpi-icon-box" style="background-color: rgba(220, 38, 38, 0.1); color: #dc2626;">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div class="kpi-val-number" id="stat-inventory-val"><span class="loading"></span></div>
            <div class="kpi-status-tag" id="inventoryStatus">Memuat data stok...</div>
        </div>

        <!-- 2. Ruijie -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Ruijie Networks</span>
                <div class="kpi-icon-box" style="background-color: rgba(37, 99, 235, 0.1); color: #2563eb;">
                    <i class="bi bi-router"></i>
                </div>
            </div>
            <div class="kpi-val-number" id="stat-ruijie-val"><span class="loading"></span></div>
            <div class="kpi-status-tag" id="ruijieStatus">Memuat katalog...</div>
        </div>

        <!-- 3. WiFi Cameras -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">WiFi Cameras</span>
                <div class="kpi-icon-box" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-camera-video"></i>
                </div>
            </div>
            <div class="kpi-val-number" id="stat-wifi-val"><span class="loading"></span></div>
            <div class="kpi-status-tag" id="camerasStatus">Memuat unit...</div>
        </div>

        <!-- 4. Access Control -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Access Control</span>
                <div class="kpi-icon-box" style="background-color: rgba(217, 119, 6, 0.1); color: #d97706;">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <div class="kpi-val-number" id="stat-access-val"><span class="loading"></span></div>
            <div class="kpi-status-tag" id="accessStatus">Memuat sistem...</div>
        </div>

        <!-- 5. Total Seluruh Produk -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Total Portofolio</span>
                <div class="kpi-icon-box" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="kpi-val-number" id="totalProducts"><span class="loading"></span></div>
            <div class="kpi-status-tag" style="color: #059669;">
                <i class="bi bi-check-circle-fill"></i> Gabungan semua modul
            </div>
        </div>
    </div>

    <!-- MODULES MANAGEMENT -->
    <div class="modules-section-bar">
        <h2 class="modules-section-title">
            <i class="bi bi-grid-fill text-danger"></i>
            <span>Modules Management</span>
        </h2>
        <span class="text-muted small">Pilih modul kerja untuk mengelola alur bisnis</span>
    </div>
    
    <div class="modules-grid-container">
        
        <!-- 1. Gudang -->
        @canany(['view_inventory', 'manage_inventory'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <span class="module-category-pill">Logistik / Stok</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Gudang & Inventaris</h3>
                <p class="module-desc-text">
                    <span id="inventoryTotal" class="fw-bold text-dark">-</span> jenis produk • <span id="inventoryInStock" class="fw-bold text-dark">-</span> total unit fisik
                </p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/gudang" class="btn-module-primary">
                    <i class="bi bi-boxes"></i>
                    <span>Buka Gudang</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 2. Purchase Order -->
        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                    <i class="bi bi-cart-check"></i>
                </div>
                <span class="module-category-pill">Procurement</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Purchase Order (PO)</h3>
                <p class="module-desc-text">Kelola pembelian barang dan stok ke supplier resmi.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('admin.po.index') }}" class="btn-module-primary">
                    <i class="bi bi-list-ul"></i>
                    <span>Daftar PO</span>
                </a>
                @canany(['create_purchase_orders', 'manage_purchase_orders'])
                <a href="{{ route('admin.po.create') }}" class="btn-module-outline">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat Baru</span>
                </a>
                @endcanany
            </div>
        </div>
        @endcanany

        <!-- 3. Sales Order -->
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <span class="module-category-pill">Penjualan</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Sales Order (SO)</h3>
                <p class="module-desc-text">Kelola pesanan penjualan dan pengiriman unit ke klien.</p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/gudang/sales-orders" class="btn-module-primary">
                    <i class="bi bi-list-check"></i>
                    <span>Daftar SO</span>
                </a>
                @canany(['create_sales_orders', 'manage_sales_orders'])
                <a href="/admin/gudang/sales-orders/create" class="btn-module-outline">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat Baru</span>
                </a>
                @endcanany
            </div>
        </div>
        @endcanany

        <!-- 4. Quotation -->
        @canany(['view_quotation', 'manage_quotation'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                    <i class="bi bi-file-text"></i>
                </div>
                <span class="module-category-pill">Penawaran Resmi</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Quotation (Penawaran)</h3>
                <p class="module-desc-text">Penerbitan surat penawaran harga & RAB untuk customer.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('admin.quotation.index') }}" class="btn-module-primary">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Daftar SPH</span>
                </a>
                @canany(['create_quotation', 'manage_quotation'])
                <a href="{{ route('admin.quotation.create') }}" class="btn-module-outline">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat Baru</span>
                </a>
                @endcanany
            </div>
        </div>
        @endcanany

        <!-- 5. Keuangan Boss -->
        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="module-category-pill">Eksekutif Finance</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Keuangan Boss</h3>
                <p class="module-desc-text">Kelola arus kas, laba bersih, dan laporan keuangan utama.</p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/keuangan" class="btn-module-primary">
                    <i class="bi bi-cash-stack"></i>
                    <span>Buka Modul</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 6. Finance Staff -->
        @can('manage_finance')
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <span class="module-category-pill">Operasional Kasir</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Finance Staff</h3>
                <p class="module-desc-text">Input transaksi piutang harian, pengeluaran & tagihan lunas.</p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/finance" class="btn-module-primary">
                    <i class="bi bi-cash-coin"></i>
                    <span>Buka Finance Staff</span>
                </a>
            </div>
        </div>
        @endcan

        <!-- 7. Kalkulator Modal -->
        @can('view_kalkulator')
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(219, 39, 119, 0.1); color: #db2777;">
                    <i class="bi bi-calculator"></i>
                </div>
                <span class="module-category-pill">Analisa Margin</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Kalkulator Modal</h3>
                <p class="module-desc-text">Simulasi estimasi HPP barang, diskon, dan proyeksi profit.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('admin.modal.kalkulator') }}" class="btn-module-primary">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Buka Kalkulator</span>
                </a>
            </div>
        </div>
        @endcan

        <!-- 8. Modal Paket -->
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(30, 64, 175, 0.1); color: #1e40af;">
                    <i class="bi bi-bag-check"></i>
                </div>
                <span class="module-category-pill">HPP Paket</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Modal Paket CCTV</h3>
                <p class="module-desc-text">Manajemen harga pokok dan bundel paket CCTV 2/4/8/16 Channel.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('modal-paket.index') }}" class="btn-module-primary">
                    <i class="bi bi-box-seam"></i>
                    <span>Kelola Modal Paket</span>
                </a>
            </div>
        </div>

        <!-- 9. Ruijie Networks -->
        @canany(['view_ruijie', 'manage_ruijie'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                    <i class="bi bi-router"></i>
                </div>
                <span class="module-category-pill">Networking Hardware</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Ruijie Networks</h3>
                <p class="module-desc-text">
                    <span id="ruijieTotal" class="fw-bold text-dark">-</span> produk terdaftar • <span id="ruijieActive" class="fw-bold text-success">-</span> aktif tayang
                </p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/ruijie/products" class="btn-module-primary">
                    <i class="bi bi-gear-fill"></i>
                    <span>Manage</span>
                </a>
                <a href="/products/ruijie" target="_blank" class="btn-module-outline">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Katalog</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 10. WiFi Cameras -->
        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(13, 148, 136, 0.1); color: #0d9488;">
                    <i class="bi bi-camera-video"></i>
                </div>
                <span class="module-category-pill">Smart Camera</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">WiFi Cameras (Smart)</h3>
                <p class="module-desc-text">
                    <span id="camerasTotal" class="fw-bold text-dark">-</span> unit nirkabel • <span id="camerasActive" class="fw-bold text-success">-</span> aktif tayang
                </p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/wifi-cameras" class="btn-module-primary">
                    <i class="bi bi-gear-fill"></i>
                    <span>Manage</span>
                </a>
                <a href="/wifi-cam" target="_blank" class="btn-module-outline">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Katalog</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 11. Access Control -->
        @canany(['view_access_control', 'manage_access_control'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(202, 138, 4, 0.1); color: #ca8a04;">
                    <i class="bi bi-fingerprint"></i>
                </div>
                <span class="module-category-pill">Biometrik & RFID</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Access Control</h3>
                <p class="module-desc-text">
                    <span id="accessTotal" class="fw-bold text-dark">-</span> sistem kontrol pintu & mesin absensi aktif.
                </p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/access-control" class="btn-module-primary">
                    <i class="bi bi-gear-fill"></i>
                    <span>Manage</span>
                </a>
                <a href="/access-control" target="_blank" class="btn-module-outline">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Katalog</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 12. Static Products -->
        @canany(['view_static_products', 'manage_static_products'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <span class="module-category-pill">Perangkat & Brand</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Static Products</h3>
                <p class="module-desc-text">Katalog CCTV statis Hikvision, Dahua, HiLook, UNV & aksesoris.</p>
            </div>
            <div class="module-actions-strip">
                <a href="/admin/static-products" class="btn-module-primary">
                    <i class="bi bi-sliders"></i>
                    <span>Manage Produk</span>
                </a>
            </div>
        </div>
        @endcanany

        <!-- 13. User Management -->
        @canany(['view_users', 'manage_users'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(109, 40, 217, 0.1); color: #6d28d9;">
                    <i class="bi bi-person-gear"></i>
                </div>
                <span class="module-category-pill">Hak Akses & Akun</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">User Management</h3>
                <p class="module-desc-text">Kelola akun Superadmin, Admin gudang, staff & permissions.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('admin.users.index') }}" class="btn-module-primary">
                    <i class="bi bi-people-fill"></i>
                    <span>Users</span>
                </a>
                @can('manage_roles')
                <a href="{{ route('admin.roles.index') }}" class="btn-module-outline">
                    <i class="bi bi-key-fill"></i>
                    <span>Roles</span>
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        <!-- 14. Testimoni & Instalasi -->
        @canany(['view_testimonials', 'manage_testimonials'])
        <div class="module-item-card">
            <div class="module-card-top">
                <div class="module-icon-wrap" style="background: rgba(225, 29, 72, 0.1); color: #e11d48;">
                    <i class="bi bi-images"></i>
                </div>
                <span class="module-category-pill">Dokumentasi</span>
            </div>
            <div class="module-info-body">
                <h3 class="module-title-text">Testimoni & Instalasi</h3>
                <p class="module-desc-text">Kelola galeri foto hasil pemasangan CCTV lapangan.</p>
            </div>
            <div class="module-actions-strip">
                <a href="{{ route('admin.testimonials.index') }}" class="btn-module-primary">
                    <i class="bi bi-camera-fill"></i>
                    <span>Kelola Galeri</span>
                </a>
            </div>
        </div>
        @endcanany

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAllStatistics();
    startLiveClock();
});

// Live Clock WIB
function startLiveClock() {
    function updateClock() {
        const now = new Date();
        const options = { timeZone: 'Asia/Jakarta', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const timeStr = now.toLocaleTimeString('id-ID', options);
        const clockEl = document.getElementById('liveClockWidget');
        if (clockEl) {
            clockEl.textContent = `${timeStr} WIB`;
        }
    }
    updateClock();
    setInterval(updateClock, 1000);
}

// Refresh Button Action
function refreshDashboardData() {
    const icon = document.getElementById('refreshIcon');
    if (icon) icon.classList.add('bi-spin');
    loadAllStatistics().then(() => {
        setTimeout(() => {
            if (icon) icon.classList.remove('bi-spin');
        }, 600);
    });
}

// Mobile sidebar drawer
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar) sidebar.classList.toggle('active');
}

// Load statistics from APIs
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
            if(document.getElementById('inventoryStatus')) document.getElementById('inventoryStatus').textContent = `${s.total_stok || 0} unit fisik`;
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
            if(document.getElementById('ruijieStatus')) document.getElementById('ruijieStatus').textContent = `${s.active_products || 0} unit aktif`;
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
            if(document.getElementById('camerasStatus')) document.getElementById('camerasStatus').textContent = `${count} unit aktif`;
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
            if(document.getElementById('accessStatus')) document.getElementById('accessStatus').textContent = `${data.statistics.active || 0} sistem online`;
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