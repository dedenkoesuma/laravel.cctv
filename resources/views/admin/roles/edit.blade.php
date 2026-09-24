<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permissions: {{ strtoupper($role->name) }} - PT TRAC Console</title>
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

        /* Module Matrix Cards */
        .module-matrix-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .module-matrix-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .module-matrix-header {
            background: #0f172a;
            color: #ffffff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .module-matrix-title {
            font-size: 13.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .perm-checkbox-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid var(--slate-200);
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .perm-checkbox-item:hover {
            border-color: #94a3b8;
            background: var(--slate-50);
        }

        .perm-checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #dc2626;
            cursor: pointer;
        }

        .perm-label-text {
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-700);
        }

        /* Sticky Action Bar */
        .sticky-save-bar {
            position: sticky;
            bottom: 24px;
            display: flex;
            justify-content: center;
            z-index: 1000;
            margin-top: 40px;
        }

        .btn-save-permissions {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14.5px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.35);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save-permissions:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4);
            color: #ffffff;
        }

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
        <a href="/admin/static-products" class="nav-item-link">
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
        <a href="{{ route('admin.roles.index') }}" class="nav-item-link active">
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
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="page-header-title mb-1">
                    <i class="bi bi-sliders me-2 text-danger"></i>Matriks Hak Akses: <span class="text-danger">{{ strtoupper($role->name) }}</span>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}" class="text-decoration-none text-muted">Roles & Permissions</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold">Edit Izin ({{ $role->name }})</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary fw-bold px-3 py-2" style="border-radius:10px; font-size:13px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Role
                </a>
            </div>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf 
            @method('PUT')

            <div class="row g-4">
                @foreach($permissions as $groupName => $groupItems)
                @php
                    $hiddenPerms = [
                        'view_bookkeeping', 'create_bookkeeping', 'edit_bookkeeping', 'delete_bookkeeping',
                        'view_sales_documents', 'create_sales_documents', 'edit_sales_documents', 'delete_sales_documents', 'manage_sales_documents',
                        'view_finance' 
                    ];
                    
                    $filteredItems = collect($groupItems)->reject(function($item) use ($hiddenPerms) {
                        return in_array($item->name, $hiddenPerms);
                    });
                @endphp

                @if($filteredItems->count() > 0)
                <div class="col-lg-6">
                    <div class="module-matrix-card h-100">
                        @php
                            $namaModul = strtoupper($groupName);
                            $moduleIcon = 'bi-box-seam';
                            if (strtolower($groupName) === 'inventory') {
                                $namaModul = 'GUDANG (INVENTORY)';
                                $moduleIcon = 'bi-boxes';
                            } elseif (strtolower($groupName) === 'bookkeeping') {
                                $namaModul = 'KEUANGAN';
                                $moduleIcon = 'bi-wallet2';
                            } elseif (strtolower($groupName) === 'testimonials') {
                                $namaModul = 'TESTIMONI & PORTOFOLIO';
                                $moduleIcon = 'bi-chat-heart';
                            } elseif (str_contains(strtolower($groupName), 'product')) {
                                $moduleIcon = 'bi-camera-video';
                            } elseif (str_contains(strtolower($groupName), 'order')) {
                                $moduleIcon = 'bi-file-earmark-check';
                            }
                        @endphp
                        
                        <div class="module-matrix-header">
                            <span class="module-matrix-title">
                                <i class="bi {{ $moduleIcon }} text-danger"></i>
                                MODUL: {{ $namaModul }}
                            </span>
                            <span class="badge" style="background: rgba(255,255,255,0.12); color:#cbd5e1; font-size:11px;">
                                {{ $filteredItems->count() }} Izin Tersedia
                            </span>
                        </div>
                        
                        <div class="p-3 card-body">
                            <div class="row g-2">
                                @foreach($filteredItems as $perm)
                                <div class="col-12 col-sm-6">
                                    <label class="perm-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                               {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                        
                                        @php
                                            $permLabel = ucwords(str_replace('_', ' ', $perm->name));
                                            $permLabel = str_ireplace('Bookkeeping', 'Keuangan', $permLabel);
                                            $permLabel = str_ireplace('Inventory', 'Gudang', $permLabel);
                                            $permLabel = str_ireplace('Delete Sales Orders', 'Batalkan Sales Order', $permLabel);
                                            $permLabel = str_ireplace('Delete Sales Order', 'Batalkan Sales Order', $permLabel);
                                        @endphp

                                        <span class="perm-label-text">
                                            {{ $permLabel }}
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Sticky Save Floating Bar -->
            <div class="sticky-save-bar">
                <button type="submit" class="btn-save-permissions">
                    <i class="bi bi-shield-check fs-5 text-danger"></i> 
                    SIMPAN PERUBAHAN HAK AKSES
                </button>
            </div>
        </form>

        <div style="height: 60px;"></div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smart JS: Selecting manage_* automatically checks all module permissions
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.value.includes('manage_')) {
                const cardBody = this.closest('.card-body');
                if (cardBody) {
                    const relatedCheckboxes = cardBody.querySelectorAll('input[type="checkbox"]');
                    relatedCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                }
            }
        });
    });
});

function hapusJejakBrowser(event) {
    event.preventDefault();
    localStorage.clear(); 
    sessionStorage.clear();
    window.location.href = '/admin/logout';
}
</script>

</body>
</html>