<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unified Admin Dashboard - TechStore</title>
    
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
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; }
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
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 28px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 120px; height: 120px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%); border-radius: 0 0 0 120px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .stat-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; position: relative; }
        .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
        .icon-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .icon-success { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
        .icon-warning { background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); }
        .icon-info { background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); }
        .icon-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .stat-info h3 { font-size: 14px; color: #718096; margin-bottom: 4px; text-transform: uppercase; font-weight: 600; }
        .stat-value { font-size: 36px; font-weight: 800; color: #2d3748; line-height: 1; }
        .stat-change { font-size: 13px; font-weight: 600; margin-top: 8px; color: #48bb78; }

        /* Products Overview Cards */
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
        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

@php
    // Memastikan nama role selalu huruf kecil agar tidak error saat dicari di database
    $adminRoleName = strtolower(session('admin_role'));
    $currentRole = \Spatie\Permission\Models\Role::where('name', $adminRoleName)->first();
    
    // Logika ngecek izin murni HANYA dari centangan di database
    $canAccess = function($permissionName) use ($currentRole) {
        return $currentRole ? $currentRole->hasPermissionTo($permissionName) : false;
    };
@endphp

<div class="sidebar">
    <div class="sidebar-header">
        <h2>🏢 PT Trac</h2>
        <p>Unified Admin Dashboard</p>
    </div>
    
    <div class="sidebar-menu">
        <a href="/dashboard" class="menu-item active"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>

        @if($canAccess('view_inventory'))
        <div class="menu-section-title">Inventory Management</div>
        <a href="/admin/inventory" class="menu-item"><i class="bi bi-box-seam"></i><span>Dashboard Inventory</span></a>
        <a href="/admin/inventory/incoming-continuous" class="menu-item"><i class="bi bi-box-arrow-in-down"></i><span>Barang Masuk</span></a>
        <a href="/admin/inventory/outgoing" class="menu-item"><i class="bi bi-box-arrow-up"></i><span>Barang Keluar</span></a>
        @endif

        <div class="menu-section-title">Products Management</div>
        
        @if($canAccess('view_ruijie'))
        <a href="/admin/ruijie" class="menu-item"><i class="bi bi-router"></i><span>Ruijie Networks</span></a>
        @endif
        
        @if($canAccess('view_wifi_cameras'))
        <a href="/admin/wifi-cameras" class="menu-item"><i class="bi bi-camera-video"></i><span>WiFi Cameras</span></a>
        @endif
        
        @if($canAccess('view_access_control'))
        <a href="/admin/access-control" class="menu-item"><i class="bi bi-shield-lock"></i><span>Access Control</span></a>
        @endif
        
        @if($canAccess('view_static_products'))
        <a href="/admin/static-products" class="menu-item"><i class="bi bi-box"></i><span>Static Products</span></a>
        @endif

        <div class="menu-section-title">Business Documents</div>
        
        @if($canAccess('view_bookkeeping'))
        <a href="/admin/bookkeeping" class="menu-item"><i class="bi bi-calculator"></i><span>Pembukuan</span></a>
        @endif
        
        @if($canAccess('view_sales_documents'))
        <a href="/admin/sales-documents" class="menu-item"><i class="bi bi-file-earmark-text"></i><span>Surat Order & Penawaran</span></a>
        @endif

        <div class="menu-section-title">System</div>
        <a href="#" class="menu-item"><i class="bi bi-graph-up"></i><span>Analytics</span></a>
        
        @if($canAccess('view_users'))
        <a href="{{ route('admin.users.index') }}" class="menu-item"><i class="bi bi-people"></i><span>Users Account</span></a>
        <a href="{{ route('admin.roles.index') }}" class="menu-item"><i class="bi bi-shield-lock"></i><span>Roles & Permissions</span><span class="badge">SECURE</span></a>
        @endif
        
        <a href="#" class="menu-item"><i class="bi bi-gear"></i><span>Settings</span></a>
    </div>
    
    <a href="{{ route('admin.logout') }}" class="logout-btn" onclick="hapusJejakBrowser(event)">
        <i class="bi bi-box-arrow-right"></i><span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<div class="main-content">
    <div class="page-header">
        <h1>📊 Dashboard Overview</h1>
        <p>Selamat datang di Unified Admin Panel - Kelola semua products dari satu tempat</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-danger"><i class="bi bi-boxes" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Inventory Items</h3>
                    <div class="stat-value" id="stat-inventory-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="inventoryStatus">Loading...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-primary"><i class="bi bi-router" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Ruijie Networks</h3>
                    <div class="stat-value" id="stat-ruijie-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="ruijieStatus">Loading...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-success"><i class="bi bi-camera-video" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>WiFi Cameras</h3>
                    <div class="stat-value" id="stat-wifi-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="camerasStatus">Loading...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-warning"><i class="bi bi-shield-lock" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Access Control</h3>
                    <div class="stat-value" id="stat-access-val"><span class="loading"></span></div>
                </div>
            </div>
            <div class="stat-change" id="accessStatus">Loading...</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon icon-info"><i class="bi bi-box-seam" style="color: white;"></i></div>
                <div class="stat-info">
                    <h3>Total Products</h3>
                    <div class="stat-value" id="totalProducts">0</div>
                </div>
            </div>
            <div class="stat-change">↗ All categories combined</div>
        </div>
    </div>

    <div class="section-header">
        <h2><i class="bi bi-grid-3x3"></i> Modules Management</h2>
    </div>
    
    <div class="products-overview">
        @if($canAccess('view_inventory'))
        <div class="product-category-card" style="border-left: 4px solid #ef4444;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="bi bi-boxes" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>Inventory Management</h3>
                    <p class="category-count"><span id="inventoryTotal">-</span> items • <span id="inventoryInStock">-</span> in stock</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="/admin/inventory" class="btn btn-primary"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="/admin/inventory/incoming-continuous" class="btn btn-outline"><i class="bi bi-box-arrow-in-down"></i><span>Scan</span></a>
            </div>
        </div>
        @endif

        @if($canAccess('view_ruijie'))
        <div class="product-category-card">
            <div class="category-header">
                <div class="category-icon"><i class="bi bi-router" style="color: white;"></i></div>
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
        @endif

        @if($canAccess('view_wifi_cameras'))
        <div class="product-category-card">
            <div class="category-header">
                <div class="category-icon"><i class="bi bi-camera-video" style="color: white;"></i></div>
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
        @endif

        @if($canAccess('view_users'))
        <div class="product-category-card" style="border-left: 4px solid #764ba2;">
            <div class="category-header">
                <div class="category-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><i class="bi bi-person-gear" style="color: white;"></i></div>
                <div class="category-info">
                    <h3>User Management</h3>
                    <p class="category-count">Kelola akses Admin, Superadmin & Staff</p>
                </div>
            </div>
            <div class="category-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary"><i class="bi bi-shield-lock"></i><span>Manage Access</span></a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAllStatistics();
});

async function loadAllStatistics() {
    let totalProducts = 0;
    
    try {
        const inventoryRes = await fetch('/api/admin/inventory/statistics');
        const inventoryData = await inventoryRes.json();
        if (inventoryData.success) {
            const stats = inventoryData.statistics;
            if(document.getElementById('stat-inventory-val')) document.getElementById('stat-inventory-val').textContent = stats.total_items || 0;
            if(document.getElementById('inventoryTotal')) document.getElementById('inventoryTotal').textContent = stats.total_items || 0;
            if(document.getElementById('inventoryInStock')) document.getElementById('inventoryInStock').textContent = stats.in_stock || 0;
            if(document.getElementById('inventoryStatus')) document.getElementById('inventoryStatus').textContent = `${stats.in_stock || 0} items in stock • ${stats.sold || 0} sold`;
            totalProducts += (stats.total_items || 0);
        }
    } catch (error) { console.error('Error loading Inventory stats:', error); }
    
    try {
        const ruijieRes = await fetch('/api/admin/ruijie/statistics');
        const ruijieData = await ruijieRes.json();
        if (ruijieData.success) {
            const stats = ruijieData.statistics;
            if(document.getElementById('stat-ruijie-val')) document.getElementById('stat-ruijie-val').textContent = stats.total_products || 0;
            if(document.getElementById('ruijieTotal')) document.getElementById('ruijieTotal').textContent = stats.total_products || 0;
            if(document.getElementById('ruijieActive')) document.getElementById('ruijieActive').textContent = stats.active_products || 0;
            if(document.getElementById('ruijieStatus')) document.getElementById('ruijieStatus').textContent = `${stats.active_products || 0} active products`;
            totalProducts += (stats.total_products || 0);
        }
    } catch (error) { console.error('Error loading Ruijie stats:', error); }

    try {
        const wifiRes = await fetch('/api/admin/wifi-cameras');
        const wifiData = await wifiRes.json();
        if (wifiData.success) {
            const count = wifiData.cameras.length;
            if(document.getElementById('stat-wifi-val')) document.getElementById('stat-wifi-val').textContent = count;
            if(document.getElementById('camerasTotal')) document.getElementById('camerasTotal').textContent = count;
            if(document.getElementById('camerasActive')) document.getElementById('camerasActive').textContent = count;
            if(document.getElementById('camerasStatus')) document.getElementById('camerasStatus').textContent = `${count} active cameras`;
            totalProducts += count;
        }
    } catch (error) { console.error('Error loading WiFi stats:', error); }

    try {
        const accessRes = await fetch('/api/admin/access-control/statistics');
        const accessData = await accessRes.json();
        if (accessData.success) {
            const count = accessData.statistics.total;
            if(document.getElementById('stat-access-val')) document.getElementById('stat-access-val').textContent = count;
            if(document.getElementById('accessStatus')) document.getElementById('accessStatus').textContent = `${accessData.statistics.active} system online`;
            totalProducts += count;
        }
    } catch (error) { console.error('Error loading Access stats:', error); }

    if(document.getElementById('totalProducts')) document.getElementById('totalProducts').textContent = totalProducts;
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