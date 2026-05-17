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
        .menu-item:hover { background: rgba(255,255,255,0.15); border-left-color: white; color: white; }
        .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; font-weight: bold; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: gap: 8px; font-weight: 600; text-decoration: none; transition: 0.3s;}
        .logout-btn:hover { background: rgba(255,255,255,0.3); }
        
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
            <div class="card">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 25px; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                    
                    @php
                        $namaModul = strtoupper($groupName);
                        if (strtolower($groupName) === 'inventory') {
                            $namaModul = 'GUDANG';
                        } elseif (strtolower($groupName) === 'bookkeeping') {
                            $namaModul = 'KEUANGAN';
                        } elseif (strtolower($groupName) === 'testimonials') {
                            $namaModul = 'TESTIMONI';
                        }
                    @endphp

                    <span style="font-size: 15px; letter-spacing: 0.5px;"><i class="bi bi-box-seam"></i> MODUL: {{ $namaModul }}</span>
                    <span style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 10px; font-size: 11px;">{{ $filteredItems->count() }} Izin</span>
                </div>
                
                <div class="card-body" style="padding: 25px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        @foreach($filteredItems as $perm)
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 12px; border-radius: 12px; border: 1px solid #f1f5f9; transition: all 0.2s;" 
                               onmouseover="this.style.borderColor='#667eea'; this.style.background='#f7faff';" 
                               onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='transparent';">
                            
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                       {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                       style="width: 20px; height: 20px; accent-color: #667eea; cursor: pointer;">
                            </div>
                            
                            @php
                                $permLabel = ucwords(str_replace('_', ' ', $perm->name));
                                $permLabel = str_ireplace('Bookkeeping', 'Keuangan', $permLabel);
                                $permLabel = str_ireplace('Inventory', 'Gudang', $permLabel);
                                $permLabel = str_ireplace('Delete Sales Orders', 'Batalkan Sales Order', $permLabel);
                                $permLabel = str_ireplace('Delete Sales Order', 'Batalkan Sales Order', $permLabel);
                            @endphp

                            <span style="font-size: 14px; color: #4a5568; font-weight: 500;">
                                {{ $permLabel }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // JS Pintar: Jika kotak "Manage..." dicentang, otomatis centang View, Create, Edit, Delete di modul yang sama
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Cek apakah permission yang diklik mengandung kata "manage_"
            if (this.value.includes('manage_')) {
                // Cari area card pembungkusnya
                const cardBody = this.closest('.card-body');
                if (cardBody) {
                    const relatedCheckboxes = cardBody.querySelectorAll('input[type="checkbox"]');
                    relatedCheckboxes.forEach(cb => {
                        // Samakan status checked dengan kotak "manage_"
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