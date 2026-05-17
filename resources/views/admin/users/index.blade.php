<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - TechStore</title>
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
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; font-weight: bold; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }

        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); color: white; }

        .main-content { margin-left: 280px; padding: 30px; min-height: 100vh; }

        /* KONTEN USER */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h1 { font-size: 32px; color: #2d3748; margin-bottom: 8px; font-weight: 700; }
        .page-header p { color: #718096; font-size: 15px; }
        
        .btn { padding: 12px 20px; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #4a5568; }
        
        .card { background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 24px; text-align: left; border-bottom: 1px solid #edf2f7; }
        th { background: #f8fafc; color: #4a5568; font-size: 13px; font-weight: 700; text-transform: uppercase; }
        
        .badge-role { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #e2e8f0; color: #4a5568; }

        /* MODAL */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center; }
        .modal.show { display: flex; }
        .modal-content { background: white; width: 100%; max-width: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #4a5568; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
        .close-btn { background: none; border: none; font-size: 28px; cursor: pointer; color: #a0aec0; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
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
    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="page-header">
        <div>
            <h1>🛡️ User Management</h1>
            <p>Kelola akun dan role Administrator untuk sistem dashboard.</p>
        </div>
        @canany(['create_users', 'manage_users'])
        <button class="btn btn-primary" onclick="openModal('addModal')">
            <i class="bi bi-person-plus-fill"></i> Tambah User Baru
        </button>
        @endcan
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    @php $userRole = $user->roles->first()->name ?? ''; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-role">{{ strtoupper($userRole ?: 'NO ROLE') }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @canany(['edit_users', 'manage_users'])
                                <button class="btn btn-outline" style="padding: 6px 12px;" onclick='openEditModal(@json($user), "{{ $userRole }}")'>
                                    <i class="bi bi-pencil" style="color: #ed8936;"></i> Edit
                                </button>
                                @endcan

                                @canany(['delete_users', 'manage_users'])
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline" style="padding: 6px 12px;">
                                        <i class="bi bi-trash" style="color: #ef4444;"></i>
                                    </button>
                                </form>
                                @endcan

                                @if(!auth()->user()->can('edit_users') && !auth()->user()->can('delete_users'))
                                    <span class="text-muted small">No Action</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center; padding: 30px;">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add & Edit --}}
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Buat Akun Baru</h3>
            <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required minlength="6"></div>
                <div class="form-group">
                    <label class="form-label">Role Akses</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px; padding: 12px;">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Data Akun</h3>
            <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" id="edit_name" name="name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Username</label><input type="text" id="edit_username" name="username" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" id="edit_email" name="email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Password (Kosongkan jika tidak ganti)</label><input type="password" name="password" class="form-control" minlength="6"></div>
                <div class="form-group">
                    <label class="form-label">Role Akses</label>
                    <select id="edit_role" name="role" class="form-control" required>
                        @foreach(\Spatie\Permission\Models\Role::all() as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px; padding: 12px;">Update Data</button>
            </div>
        </form>
    </div>
</div>

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