<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts Management - PT TRAC Console</title>
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

        /* Data Card & Table */
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
            padding: 14px 20px;
            border-bottom: 1px solid var(--slate-200);
        }

        .table td {
            padding: 14px 20px;
            vertical-align: middle;
            border-bottom: 1px solid var(--slate-200);
            font-size: 13.5px;
        }

        .table tbody tr:hover {
            background-color: var(--slate-50);
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .role-pill-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .role-pill-badge.superadmin, .role-pill-badge.admin {
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }
        .role-pill-badge.finance {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .role-pill-badge.gudang {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .role-pill-badge.default {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* ===== MODAL ===== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1060;
            align-items: center;
            justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-content-card {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--slate-200);
            animation: modalFadeIn 0.2s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--slate-50);
        }
        .modal-card-header h3 {
            font-size: 17px;
            font-weight: 800;
            color: var(--slate-900);
            margin: 0;
        }
        .modal-card-body { padding: 24px; }
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 12.5px;
            color: var(--slate-700);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--slate-300);
            border-radius: 10px;
            font-size: 13.5px;
            color: var(--slate-900);
            background: #ffffff;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--slate-400);
            line-height: 1;
            transition: color 0.2s;
        }
        .close-btn:hover { color: var(--slate-800); }

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
        <a href="{{ route('admin.users.index') }}" class="nav-item-link active">
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
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="page-header-title mb-1"><i class="bi bi-person-gear me-2 text-danger"></i>User Accounts Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 13px;">
                        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold">Users Account</li>
                    </ol>
                </nav>
            </div>
            <div>
                @canany(['create_users', 'manage_users'])
                <button class="btn btn-danger fw-bold px-3 py-2" style="border-radius:10px; font-size:13.5px;" onclick="openModal('addModal')">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
                </button>
                @endcan
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

        <!-- Users Table Card -->
        <div class="data-card">
            <div class="data-card-header">
                <span class="fw-bold" style="font-size:14.5px; color:var(--slate-900);">
                    <i class="bi bi-people-fill me-2 text-danger"></i>Daftar Administrator Terdaftar ({{ count($users) }})
                </span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 60px;">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Alamat Email</th>
                            <th>Hak Akses / Role</th>
                            <th class="pe-4 text-center" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        @php 
                            $userRole = $user->roles->first()->name ?? '';
                            $roleLower = strtolower($userRole);
                            $badgeClass = 'default';
                            if (str_contains($roleLower, 'super') || str_contains($roleLower, 'admin')) {
                                $badgeClass = 'superadmin';
                            } elseif (str_contains($roleLower, 'finan') || str_contains($roleLower, 'uang')) {
                                $badgeClass = 'finance';
                            } elseif (str_contains($roleLower, 'gudang') || str_contains($roleLower, 'inven')) {
                                $badgeClass = 'gudang';
                            }
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-circle">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="small text-muted">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code style="color: #0f172a; background: #f1f5f9; padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 12.5px;">
                                    {{ $user->username }}
                                </code>
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>
                                <span class="role-pill-badge {{ $badgeClass }}">
                                    <i class="bi bi-shield-check me-1"></i>{{ strtoupper($userRole ?: 'NO ROLE') }}
                                </span>
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group btn-group-sm shadow-sm" role="group">
                                    @canany(['edit_users', 'manage_users'])
                                    <button class="btn btn-light border" onclick='openEditModal(@json($user), "{{ $userRole }}")' title="Edit Akun">
                                        <i class="bi bi-pencil text-warning"></i>
                                    </button>
                                    @endcanany

                                    @canany(['delete_users', 'manage_users'])
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user {{ $user->name }} secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light border" title="Hapus User">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @endcanany

                                    @cannot('edit_users')
                                        @cannot('manage_users')
                                            @cannot('delete_users')
                                                <span class="text-muted small px-2 py-1">-</span>
                                            @endcannot
                                        @endcannot
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3"><i class="bi bi-people fs-1 text-muted opacity-50"></i></div>
                                <h6 class="text-dark fw-bold">Belum Ada User Terdaftar</h6>
                                <p class="text-muted small mb-0">Klik tombol di atas untuk mendaftarkan akun baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Add User -->
<div id="addModal" class="modal">
    <div class="modal-content-card">
        <div class="modal-card-header">
            <h3><i class="bi bi-person-plus-fill me-2 text-danger"></i>Buat Akun Administrator</h3>
            <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: John Doe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: jdoe" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@domain.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                </div>
                <div class="mb-4">
                    <label class="form-label">Hak Akses / Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role Akses --</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 fw-bold" onclick="closeModal('addModal')">Batal</button>
                    <button type="submit" class="btn btn-danger w-50 fw-bold shadow-sm">Simpan Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editModal" class="modal">
    <div class="modal-content-card">
        <div class="modal-card-header">
            <h3><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Administrator</h3>
            <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-card-body">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" id="edit_username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru <span class="text-muted fw-normal font-monospace">(Kosongkan bila tidak diubah)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6" placeholder="Biarkan kosong jika tetap">
                </div>
                <div class="mb-4">
                    <label class="form-label">Hak Akses / Role</label>
                    <select id="edit_role" name="role" class="form-select" required>
                        @foreach(\Spatie\Permission\Models\Role::all() as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 fw-bold" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-dark w-50 fw-bold shadow-sm">Update Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
    
    function openEditModal(user, userRole) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_email').value = user.email;
        
        let roleSelect = document.getElementById('edit_role');
        for (let i = 0; i < roleSelect.options.length; i++) {
            if (roleSelect.options[i].value === userRole) {
                roleSelect.selectedIndex = i;
                break;
            }
        }

        document.getElementById('editForm').action = "/manage-users/" + user.id;
        openModal('editModal');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
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