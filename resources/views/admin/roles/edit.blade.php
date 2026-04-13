<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Permissions - PT Trac</title>
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
        .menu-item:hover { background: rgba(255,255,255,0.15); border-left-color: white; }
        .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; text-decoration: none; }
        
        .main-content { margin-left: 280px; padding: 30px; min-height: 100vh; }
        .card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #edf2f7; overflow: hidden; }
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

        <div class="menu-section-title">Inventory Management</div>
        <a href="/admin/inventory" class="menu-item"><i class="bi bi-box-seam"></i><span>Dashboard</span></a>
        <a href="/admin/inventory/incoming-continuous" class="menu-item"><i class="bi bi-box-arrow-in-down"></i><span>Barang Masuk</span><span class="badge">NEW</span></a>
        <a href="/admin/inventory/outgoing" class="menu-item"><i class="bi bi-box-arrow-up"></i><span>Barang Keluar</span></a>

        <div class="menu-section-title">Products Management</div>
        <a href="/admin/ruijie" class="menu-item"><i class="bi bi-router"></i><span>Ruijie Networks</span></a>
        <a href="/admin/wifi-cameras" class="menu-item"><i class="bi bi-camera-video"></i><span>WiFi Cameras</span></a>
        <a href="/admin/access-control" class="menu-item"><i class="bi bi-shield-lock"></i><span>Access Control</span></a>
        <a href="/admin/static-products" class="menu-item"><i class="bi bi-box"></i><span>Static Products</span></a>

        <div class="menu-section-title">Business Documents</div>
        <a href="/admin/bookkeeping" class="menu-item"><i class="bi bi-calculator"></i><span>Pembukuan</span></a>
        <a href="/admin/sales-documents" class="menu-item"><i class="bi bi-file-earmark-text"></i><span>Surat Order & Penawaran</span></a>

        <div class="menu-section-title">System</div>
        <a href="{{ route('admin.users.index') }}" class="menu-item active"><i class="bi bi-people"></i><span>Users & Roles</span><span class="badge">SECURE</span></a>
    </div>
    
    <a href="{{ route('admin.logout') }}" class="logout-btn"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
</div>

<div class="main-content">
    <div class="page-header" style="margin-bottom: 30px;">
        <a href="{{ route('admin.roles.index') }}" style="text-decoration: none; color: #667eea; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-arrow-left-circle-fill"></i> Kembali ke Daftar Role
        </a>
        <h1 style="font-size: 32px; font-weight: 800; color: #2d3748; margin-top: 15px;">🛡️ Konfigurasi Akses: <span style="color: #764ba2;">{{ strtoupper($role->name) }}</span></h1>
        <p style="color: #718096; margin-top: 5px;">Centang modul yang boleh diakses oleh role ini.</p>
    </div>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf 
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 25px;">
            
            @foreach($permissions as $groupName => $groupItems)
            <div class="card">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 25px; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 15px; letter-spacing: 0.5px;"><i class="bi bi-box-seam"></i> MODUL: {{ strtoupper($groupName) }}</span>
                    <span style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 10px; font-size: 11px;">{{ count($groupItems) }} Izin</span>
                </div>
                
                <div class="card-body" style="padding: 25px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        @foreach($groupItems as $perm)
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 12px; border-radius: 12px; border: 1px solid #f1f5f9; transition: all 0.2s;" 
                               onmouseover="this.style.borderColor='#667eea'; this.style.background='#f7faff';" 
                               onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='transparent';">
                            
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                       {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                       style="width: 20px; height: 20px; accent-color: #667eea; cursor: pointer;">
                            </div>
                            
                            <span style="font-size: 14px; color: #4a5568; font-weight: 500;">
                                {{ ucwords(str_replace('_', ' ', $perm->name)) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <div style="position: sticky; bottom: 30px; margin-top: 50px; display: flex; justify-content: center; z-index: 100;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 18px 50px; border-radius: 50px; font-weight: 800; font-size: 16px; box-shadow: 0 10px 25px rgba(102,126,234,0.4); cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 12px;">
                <i class="bi bi-check2-all" style="font-size: 20px;"></i> 
                SIMPAN PERUBAHAN HAK AKSES
            </button>
        </div>
    </form>

    <div style="height: 100px;"></div>
</div>

</body>
</html>