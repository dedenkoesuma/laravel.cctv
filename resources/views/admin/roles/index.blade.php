<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles Management - Tech Store</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* CSS GLOBAL & SIDEBAR */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }

        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; box-shadow: 2px 0 15px rgba(0,0,0,0.1); z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 0 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.2); }
        .sidebar-header h2 { font-size: 24px; margin-bottom: 5px; font-weight: 700; }
        .sidebar-header p { font-size: 13px; opacity: 0.85; }
        .sidebar-menu { padding: 20px 0 100px; }
        .menu-section-title { padding: 20px 24px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; opacity: 0.6; letter-spacing: 1px; }
        .menu-item { padding: 14px 24px; display: flex; align-items: center; gap: 12px; color: white; text-decoration: none; transition: all 0.3s; cursor: pointer; border-left: 4px solid transparent; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); color: white; }

        .main-content { margin-left: 280px; padding: 30px; min-height: 100vh; }
        .card { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden; }
        .btn { padding: 10px 20px; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        
        /* Modal CSS */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center; }
        .modal.show { display: flex; }
        .modal-content { background: white; width: 100%; max-width: 450px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
        .close-btn { background: none; border: none; font-size: 28px; cursor: pointer; color: #a0aec0; line-height: 1; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>🏢 PT Trac</h2>
        <p>Unified Admin Dashboard</p>
    </div>
    
    <div class="sidebar-menu">
        <a href="/dashboard" class="menu-item"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>

        <div class="menu-section-title">Operations Management</div>
        
        @canany(['view_inventory', 'manage_inventory'])
        <a href="/admin/gudang" class="menu-item"><i class="bi bi-box-seam"></i><span>Gudang</span></a>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <a href="{{ route('admin.po.index') }}" class="menu-item"><i class="bi bi-cart-check"></i><span>Purchase Order</span></a>
        @endcanany
        
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <a href="/admin/gudang/sales-orders" class="menu-item"><i class="bi bi-file-earmark-check"></i><span>Sales Order</span></a>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <a href="{{ route('admin.quotation.index') }}" class="menu-item"><i class="bi bi-file-text"></i><span>Quotation</span></a>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <a href="/admin/keuangan" class="menu-item"><i class="bi bi-wallet2"></i><span>Keuangan Boss</span></a>
        @endcanany

        {{-- FINANCE STAFF MURNI MANAGE --}}
        @can('manage_finance')
        <a href="/admin/finance" class="menu-item"><i class="bi bi-receipt"></i><span>Finance Staff</span></a>
        @endcan

        @can('view_kalkulator')
        <a href="{{ route('admin.modal.kalkulator') }}" class="menu-item"><i class="bi bi-calculator"></i><span>Kalkulator Modal</span></a>
        @endcan

        <div class="menu-section-title">Products Management</div>
        
        @canany(['view_ruijie', 'manage_ruijie'])
        <a href="/admin/ruijie" class="menu-item"><i class="bi bi-router"></i><span>Ruijie Networks</span></a>
        @endcanany
        
        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <a href="/admin/wifi-cameras" class="menu-item"><i class="bi bi-camera-video"></i><span>WiFi Cameras</span></a>
        @endcanany
        
        @canany(['view_access_control', 'manage_access_control'])
        <a href="/admin/access-control" class="menu-item"><i class="bi bi-shield-lock"></i><span>Access Control</span></a>
        @endcanany
        
        @canany(['view_static_products', 'manage_static_products'])
        <a href="/admin/static-products" class="menu-item"><i class="bi bi-box"></i><span>Static Products</span></a>
        @endcanany

        <div class="menu-section-title">System</div>
        
        @canany(['view_users', 'manage_users'])
        <a href="{{ route('admin.users.index') }}" class="menu-item"><i class="bi bi-people"></i><span>Users Account</span></a>
        <a href="{{ route('admin.roles.index') }}" class="menu-item active"><i class="bi bi-shield-lock"></i><span>Roles & Permissions</span><span class="badge">SECURE</span></a>
        @endcanany
        
    </div>
    
    <a href="{{ route('admin.logout') }}" class="logout-btn" onclick="hapusJejakBrowser(event)">
        <i class="bi bi-box-arrow-right"></i><span>Logout</span>
    </a>
</div>

<div class="main-content">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 700; color: #2d3748;">🔐 Roles & Permissions</h1>
            <p style="color: #718096;">Kelola tingkatan akses dan hak izin untuk setiap pengguna.</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addRoleModal')">
            <i class="bi bi-plus-circle"></i> Tambah Role Baru
        </button>
    </div>

    @if(session('success'))
        <div style="padding: 15px; background: #f0fdf4; color: #166534; border-radius: 10px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 16px 24px; text-align: left; color: #4a5568; font-size: 13px; text-transform: uppercase;">Role Name</th>
                    <th style="padding: 16px 24px; text-align: left; color: #4a5568; font-size: 13px; text-transform: uppercase;">Guard</th>
                    <th style="padding: 16px 24px; text-align: left; color: #4a5568; font-size: 13px; text-transform: uppercase;">Permissions Assigned</th>
                    <th style="padding: 16px 24px; text-align: center; color: #4a5568; font-size: 13px; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr style="border-bottom: 1px solid #edf2f7;">
                    <td style="padding: 16px 24px;"><strong style="color: #2d3748;">{{ strtoupper($role->name) }}</strong></td>
                    <td style="padding: 16px 24px;"><span style="background: #e2e8f0; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; color: #4a5568;">{{ $role->guard_name }}</span></td>
                    <td style="padding: 16px 24px;">
                        <span style="color: #667eea; font-weight: 800; font-size: 16px;">{{ $role->permissions->count() }}</span> 
                        <span style="color: #718096; font-size: 13px; margin-left: 5px;">Akses Aktif</span>
                    </td>
                    <td style="padding: 16px 24px; text-align: center;">
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn" style="border: 2px solid #667eea; background: transparent; color: #667eea;">
                            <i class="bi bi-shield-lock"></i> Atur Hak Akses
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="addRoleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="font-size: 20px; color: #2d3748; font-weight: 700;">Buat Role Baru</h3>
            <button class="close-btn" onclick="closeModal('addRoleModal')">&times;</button>
        </div>
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #4a5568;">Nama Role</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: kasir, manager, teknisi">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 15px;">
                    <i class="bi bi-save"></i> Simpan Role
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
    
    // Tutup modal kalau klik area gelap di luar modal
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }

    // Fungsi untuk hapus jejak login
    function hapusJejakBrowser(event) {
        event.preventDefault();
        localStorage.clear();
        sessionStorage.clear();
        window.location.href = '/admin/logout'; 
    }
</script>

</body>
</html>