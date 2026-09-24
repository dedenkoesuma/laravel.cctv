<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static Products Management - PT TRAC Console</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary: #dc2626;
            --primary-hover: #b91c1c;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-400: #94a3b8;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50:  #f8fafc;
            --sidebar-bg: #090D16;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--font-main);
            background-color: var(--slate-50);
            color: var(--slate-800);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR STYLING ===== */
        .sidebar {
            width: 270px;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand-box {
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            margin: 0;
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
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
            display: inline-block;
        }

        .sidebar-nav-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px 20px;
            scrollbar-width: thin;
            scrollbar-color: var(--slate-800) transparent;
        }

        .sidebar-nav-scroll::-webkit-scrollbar { width: 4px; }
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

        .nav-item-link:hover i { color: #ffffff; }

        .nav-item-link.active {
            background-color: rgba(220, 38, 38, 0.15);
            color: #ffffff;
            font-weight: 600;
            border: 1px solid rgba(220, 38, 38, 0.25);
        }

        .nav-item-link.active i { color: #f87171; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .user-avatar-mini {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 700;
        }

        .user-info-text { overflow: hidden; }
        .user-name-label {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role-label {
            font-size: 11px;
            color: var(--slate-400);
            text-transform: capitalize;
        }

        .btn-sidebar-logout {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-sidebar-logout:hover {
            background: #dc2626;
            color: #ffffff;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 270px;
            padding: 30px;
            min-height: 100vh;
        }

        .page-header-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        /* Brand Quick Access Banner */
        .brand-quick-box {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .brand-quick-btn {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .brand-quick-btn:hover {
            background: #ffffff;
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        /* Modern KPI Cards */
        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        .kpi-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kpi-num {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        .kpi-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Filter Card & Table */
        .data-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .data-card-header {
            padding: 18px 24px;
            background: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table th {
            background: var(--slate-100);
            font-size: 11.5px;
            font-weight: 700;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--slate-200);
        }

        .table td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid var(--slate-200);
            font-size: 13.5px;
        }

        .table tbody tr:hover {
            background-color: var(--slate-50);
        }

        .prod-thumb-box {
            width: 46px;
            height: 46px;
            border-radius: 8px;
            border: 1px solid var(--slate-200);
            background: #ffffff;
            object-fit: cover;
            padding: 2px;
        }

        .badge-pill-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-pill-status.active { background: #dcfce7; color: #15803d; }
        .badge-pill-status.inactive { background: #f1f5f9; color: #64748b; }
        .badge-pill-status.out { background: #fee2e2; color: #b91c1c; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 18px; }
        }
    </style>
</head>
<body>

<!-- EXECUTIVE SIDEBAR -->
<aside class="sidebar" id="adminSidebar">
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
    
    <div class="sidebar-nav-scroll">
        <a href="/dashboard" class="nav-item-link">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard Overview</span>
        </a>

        <!-- 1. OPERATIONS MANAGEMENT -->
        <div class="nav-section-title">Operations Management</div>
        
        @canany(['view_inventory', 'manage_inventory'])
        <a href="/admin/gudang" class="nav-item-link">
            <i class="bi bi-box-seam"></i>
            <span>Gudang (Inventory)</span>
        </a>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <a href="{{ route('admin.po.index') }}" class="nav-item-link">
            <i class="bi bi-cart-check"></i>
            <span>Purchase Order (PO)</span>
        </a>
        @endcanany
        
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <a href="/admin/gudang/sales-orders" class="nav-item-link">
            <i class="bi bi-file-earmark-check"></i>
            <span>Sales Order (SO)</span>
        </a>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <a href="{{ route('admin.quotation.index') }}" class="nav-item-link">
            <i class="bi bi-file-text"></i>
            <span>Quotation (Penawaran)</span>
        </a>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <a href="/admin/keuangan" class="nav-item-link">
            <i class="bi bi-wallet2"></i>
            <span>Keuangan Boss</span>
        </a>
        @endcanany

        @can('manage_finance')
        <a href="/admin/finance" class="nav-item-link">
            <i class="bi bi-cash-coin"></i>
            <span>Finance Staff</span>
        </a>
        @endcan

        @can('view_kalkulator')
        <a href="{{ route('admin.modal.kalkulator') }}" class="nav-item-link">
            <i class="bi bi-calculator"></i>
            <span>Kalkulator Modal</span>
        </a>
        @endcan

        <a href="{{ route('modal-paket.index') }}" class="nav-item-link">
            <i class="bi bi-bag-check"></i>
            <span>Modal Paket</span>
        </a>

        <!-- 2. PRODUCTS MANAGEMENT -->
        <div class="nav-section-title">Products Management</div>

        @canany(['view_ruijie', 'manage_ruijie'])
        <a href="/admin/ruijie/products" class="nav-item-link">
            <i class="bi bi-router"></i>
            <span>Ruijie Networks</span>
        </a>
        @endcanany

        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <a href="/admin/wifi-cameras" class="nav-item-link">
            <i class="bi bi-camera-video"></i>
            <span>WiFi Cameras</span>
        </a>
        @endcanany

        @canany(['view_access_control', 'manage_access_control'])
        <a href="/admin/access-control" class="nav-item-link">
            <i class="bi bi-fingerprint"></i>
            <span>Access Control</span>
        </a>
        @endcanany

        @canany(['view_static_products', 'manage_static_products'])
        <a href="/admin/static-products" class="nav-item-link active">
            <i class="bi bi-box"></i>
            <span>Static Products</span>
        </a>
        @endcanany

        <!-- 3. SYSTEM & SECURITY -->
        <div class="nav-section-title">System & Security</div>

        @canany(['view_users', 'manage_users'])
        <a href="{{ route('admin.users.index') }}" class="nav-item-link">
            <i class="bi bi-person-gear"></i>
            <span>Users Account</span>
        </a>
        @endcanany

        @can('manage_roles')
        <a href="{{ route('admin.roles.index') }}" class="nav-item-link">
            <i class="bi bi-shield-lock"></i>
            <span>Roles & Permissions</span>
        </a>
        @endcan

        @canany(['view_testimonials', 'manage_testimonials'])
        <a href="{{ route('admin.testimonials.index') }}" class="nav-item-link">
            <i class="bi bi-chat-heart"></i>
            <span>Testimonials</span>
        </a>
        @endcanany
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-profile-badge">
            <div class="user-avatar-mini">
                {{ strtoupper(substr(session('admin_username') ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info-text">
                <div class="user-name-label">{{ session('admin_username') ?? 'Administrator' }}</div>
                <div class="user-role-label">{{ session('admin_role') ?? 'Super Admin' }}</div>
            </div>
        </div>
        <a href="{{ route('admin.logout') }}" class="btn-sidebar-logout" onclick="hapusJejakBrowser(event)" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content">
    <div class="container-fluid p-0">
        <!-- Top Title & Action Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="page-header-title mb-1"><i class="bi bi-box-seam me-2 text-danger"></i>Static Products Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold">Static Products</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                @can('create_static_products')
                <a href="{{ route('admin.static-products.create') }}" class="btn btn-danger fw-bold px-3 py-2" style="border-radius:10px; font-size:13.5px;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                </a>
                @endcan
            </div>
        </div>

        <!-- Brand Quick Access Banner -->
        <div class="brand-quick-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-link-45deg text-danger fs-5"></i>
                    <span class="text-white fw-bold fs-6">Akses Cepat Halaman Katalog Publik</span>
                </div>
                <span class="badge" style="background: rgba(255,255,255,0.12); color:#94a3b8; font-size:11.5px;">5 Brand Terdaftar</span>
            </div>
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <a href="/products/hikvision" target="_blank" class="brand-quick-btn">
                        <i class="bi bi-camera-video"></i> Hikvision
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/products/dahua" target="_blank" class="brand-quick-btn">
                        <i class="bi bi-camera-video"></i> Dahua
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/products/hilook" target="_blank" class="brand-quick-btn">
                        <i class="bi bi-camera-video"></i> HiLook
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/products/unv" target="_blank" class="brand-quick-btn">
                        <i class="bi bi-camera-video"></i> UNV
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/products/hiview" target="_blank" class="brand-quick-btn">
                        <i class="bi bi-camera-video"></i> HiView
                    </a>
                </div>
                <div class="col-12 col-md-2">
                    <a href="/products" target="_blank" class="brand-quick-btn" style="background:#dc2626; color:#ffffff; border-color:#dc2626;">
                        <i class="bi bi-grid-fill"></i> Semua Katalog
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 KPI Metrics -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-title">Total Products</div>
                        <div class="kpi-num">{{ $statistics['total'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #eef2ff; color:#4f46e5;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-title">Active Products</div>
                        <div class="kpi-num text-success">{{ $statistics['active'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #ecfdf5; color:#059669;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-title">Inactive Products</div>
                        <div class="kpi-num text-secondary">{{ $statistics['inactive'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #f8fafc; color:#64748b;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="kpi-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-title">Out of Stock</div>
                        <div class="kpi-num text-danger">{{ $statistics['out_of_stock'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon-wrap" style="background: #fef2f2; color:#dc2626;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius:12px; background:#ecfdf5; color:#065f46;">
            <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius:12px; background:#fef2f2; color:#991b1b;">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Filter Card -->
        <div class="data-card mb-4">
            <div class="data-card-header">
                <span class="fw-bold" style="font-size:14.5px; color:var(--slate-900);">
                    <i class="bi bi-funnel text-danger me-2"></i>Filter & Pencarian Produk
                </span>
            </div>
            <div class="p-3">
                <form action="{{ route('admin.static-products.index') }}" method="GET">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Brand</label>
                            <select name="brand" class="form-select bg-light border-0" style="border-radius:8px; font-size:13.5px;">
                                <option value="">Semua Brand</option>
                                <option value="hikvision" {{ request('brand') == 'hikvision' ? 'selected' : '' }}>Hikvision</option>
                                <option value="dahua" {{ request('brand') == 'dahua' ? 'selected' : '' }}>Dahua</option>
                                <option value="hilook" {{ request('brand') == 'hilook' ? 'selected' : '' }}>HiLook</option>
                                <option value="unv" {{ request('brand') == 'unv' ? 'selected' : '' }}>UNV</option>
                                <option value="hiview" {{ request('brand') == 'hiview' ? 'selected' : '' }}>HiView</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Tab Category</label>
                            <select name="tab_category" class="form-select bg-light border-0" style="border-radius:8px; font-size:13.5px;">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('tab_category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Status</label>
                            <select name="status" class="form-select bg-light border-0" style="border-radius:8px; font-size:13.5px;">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Kata Kunci</label>
                            <input type="text" name="search" class="form-control bg-light border-0" placeholder="SKU atau nama produk..." value="{{ request('search') }}" style="border-radius:8px; font-size:13.5px;">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100 fw-bold" style="height:38px; border-radius:8px; font-size:13px; margin-top:23px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table Card -->
        <div class="data-card">
            <div class="data-card-header">
                <span class="fw-bold" style="font-size:14.5px; color:var(--slate-900);">
                    <i class="bi bi-list-check me-2 text-danger"></i>Daftar Produk Statis ({{ $products->total() }})
                </span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th style="width: 70px;">Gambar</th>
                            <th style="width: 140px;">SKU / Model</th>
                            <th>Nama Produk</th>
                            <th style="width: 110px;">Brand</th>
                            <th style="width: 90px;">Stok</th>
                            <th style="width: 120px;">Harga Jual</th>
                            <th class="text-center" style="width: 110px;">Status</th>
                            <th class="pe-4 text-center" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $item)
                        <tr>
                            <td class="ps-4 text-muted fw-semibold">
                                {{ $products->firstItem() + $loop->index }}
                            </td>
                            <td>
                                @if(isset($item->image) && $item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama_produk ?? $item->product_name ?? 'Produk' }}" class="prod-thumb-box" onerror="this.src='/storage/gambar/logo-mja.png'">
                                @elseif(isset($item->gambar) && $item->gambar)
                                    <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama_produk ?? $item->product_name ?? 'Produk' }}" class="prod-thumb-box" onerror="this.src='/storage/gambar/logo-mja.png'">
                                @else
                                    <div class="prod-thumb-box d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <code style="color: #dc2626; background: #fef2f2; padding: 2px 6px; border-radius: 4px; font-weight: 600; font-size: 12px;">
                                    {{ $item->sku ?? '-' }}
                                </code>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_produk ?? $item->product_name ?? 'N/A' }}</div>
                                @if(isset($item->kategori) || isset($item->category) || isset($item->tab_category))
                                <div class="small text-muted">
                                    <i class="bi bi-tag me-1"></i>{{ $item->kategori ?? $item->category ?? '-' }}
                                    @if(isset($item->tab_category))
                                    <span class="mx-1">•</span> Tab: {{ ucfirst($item->tab_category) }}
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $brandName = strtolower($item->brand ?? '');
                                    $brandBadge = match($brandName) {
                                        'hikvision' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'color' => '#dc2626', 'border' => 'rgba(239, 68, 68, 0.25)'],
                                        'dahua' => ['bg' => 'rgba(37, 99, 235, 0.1)', 'color' => '#2563eb', 'border' => 'rgba(37, 99, 235, 0.25)'],
                                        'hilook' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#059669', 'border' => 'rgba(16, 185, 129, 0.25)'],
                                        'unv' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#d97706', 'border' => 'rgba(245, 158, 11, 0.25)'],
                                        'hiview' => ['bg' => 'rgba(6, 182, 212, 0.1)', 'color' => '#0891b2', 'border' => 'rgba(6, 182, 212, 0.25)'],
                                        default => ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0']
                                    };
                                @endphp
                                <span class="badge" style="background: {{ $brandBadge['bg'] }}; color: {{ $brandBadge['color'] }}; border: 1px solid {{ $brandBadge['border'] }}; font-weight: 700; font-size: 11px; text-transform: uppercase;">
                                    {{ $item->brand ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @php $stock = $item->stok ?? $item->stock ?? 0; @endphp
                                @if($stock > 10)
                                    <span class="badge bg-success rounded-pill px-2 py-1">{{ $stock }}</span>
                                @elseif($stock > 0)
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">{{ $stock }}</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-2 py-1">{{ $stock }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                    Rp {{ number_format($item->harga_jual ?? $item->price ?? 0, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $itemStatus = $item->status ?? (isset($item->is_active) && $item->is_active ? 'active' : 'inactive');
                                    if (($item->stok ?? $item->stock ?? 1) <= 0) {
                                        $itemStatus = 'out_of_stock';
                                    }
                                @endphp
                                @if($itemStatus === 'out_of_stock')
                                    <span class="badge-pill-status out"><i class="bi bi-x-circle-fill"></i> Habis</span>
                                @elseif($itemStatus === 'active')
                                    <span class="badge-pill-status active"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                                @else
                                    <span class="badge-pill-status inactive"><i class="bi bi-pause-circle-fill"></i> Nonaktif</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group btn-group-sm shadow-sm" role="group">
                                    <a href="/products/{{ strtolower($item->brand ?? '') }}" target="_blank" class="btn btn-light border" title="Lihat Halaman Publik">
                                        <i class="bi bi-eye text-primary"></i>
                                    </a>
                                    @can('edit_static_products')
                                    <a href="{{ route('admin.static-products.edit', $item->id) }}" class="btn btn-light border" title="Edit">
                                        <i class="bi bi-pencil text-warning"></i>
                                    </a>
                                    @endcan
                                    @can('delete_static_products')
                                    <button type="button" class="btn btn-light border" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_produk ?? $item->product_name ?? 'Product') }}')" title="Hapus">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="mb-3"><i class="bi bi-inbox fs-1 text-muted opacity-50"></i></div>
                                <h6 class="text-dark fw-bold">Belum Ada Data Produk Statis</h6>
                                <p class="text-muted small mb-3">Tidak ditemukan data yang sesuai dengan filter.</p>
                                @can('create_static_products')
                                <a href="{{ route('admin.static-products.create') }}" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $products->firstItem() }}</strong> - <strong>{{ $products->lastItem() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header bg-danger text-white border-0" style="border-radius:16px 16px 0 0; padding:20px;">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3"><i class="bi bi-trash text-danger" style="font-size:48px;"></i></div>
                <h5 class="mb-2 fw-bold text-dark">Hapus Produk Ini?</h5>
                <p class="text-dark fw-bold mb-1 p-2 bg-light rounded border" id="deleteProductName"></p>
                <p class="text-muted small mb-0 mt-3">Semua data terkait produk ini akan dihapus permanen dari sistem. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">Ya, Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/static-products/${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function hapusJejakBrowser(event) {
    event.preventDefault();
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = '/admin/logout'; 
}

// Auto dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

</body>
</html>