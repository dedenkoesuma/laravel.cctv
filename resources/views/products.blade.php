@extends('layouts.simple')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Design Variant Selector -->
<div class="variant-selector" id="variantSelector">
    <button class="variant-btn" onclick="switchVariant(1)" id="variant-btn-1">
        🌙 Dark Mode
    </button>
    <button class="variant-btn" onclick="switchVariant(2)" id="variant-btn-2">
        🎨 Colorful
    </button>
    <button class="variant-btn" onclick="switchVariant(3)" id="variant-btn-3">
        ✨ Glass
    </button>
</div>

<!-- Toggle Button -->
<button class="variant-toggle-btn" onclick="toggleVariantSelector()" id="variantToggle">
    <i class="bi bi-palette-fill"></i>
</button>

<!-- Logout Button -->
<button class="logout-btn" onclick="confirmLogout()">
    <i class="bi bi-box-arrow-right"></i>
    <span>Logout</span>
</button>

<!-- VARIANT 1: Minimalist Dark Mode -->
<div id="variant-1" class="dashboard-variant">
    <div class="dark-dashboard">
        <div class="dark-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-shop"></i>
                </div>
                <span class="sidebar-title">TechStore</span>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">Dashboard</a>
                <a href="/admin/wifi-cameras" class="nav-item">WiFi Cameras</a>
                <a href="/admin/access-control" class="nav-item">Access Control</a>
                <a href="/admin/static-products" class="nav-item">Products</a>
                <a href="#" class="nav-item">Analytics</a>
                <a href="#" class="nav-item">Settings</a>
            </nav>
        </div>

        <div class="dark-main">
            <div class="dark-header">
                <div>
                    <h1 class="dark-title">Dashboard</h1>
                    <p class="dark-subtitle">Welcome back, {{ session('admin_name', 'Admin') }}</p>
                </div>
                <div class="dark-actions">
                    <button class="dark-icon-btn"><i class="bi bi-bell"></i></button>
                    <button class="dark-icon-btn"><i class="bi bi-gear"></i></button>
                </div>
            </div>

            <div class="dark-stats">
                <div class="dark-stat-card">
                    <i class="bi bi-camera-video dark-stat-icon"></i>
                    <p class="dark-stat-label">WiFi Cameras</p>
                    <p class="dark-stat-value" id="dark-wifi-count">0</p>
                </div>
                <div class="dark-stat-card">
                    <i class="bi bi-fingerprint dark-stat-icon"></i>
                    <p class="dark-stat-label">Access Control</p>
                    <p class="dark-stat-value" id="dark-access-count">0</p>
                </div>
                <div class="dark-stat-card">
                    <i class="bi bi-box dark-stat-icon"></i>
                    <p class="dark-stat-label">Static Products</p>
                    <p class="dark-stat-value" id="dark-product-count">512</p>
                </div>
                <div class="dark-stat-card">
                    <i class="bi bi-graph-up dark-stat-icon"></i>
                    <p class="dark-stat-label">Revenue</p>
                    <p class="dark-stat-value">$24.5K</p>
                </div>
            </div>

            <div class="dark-table-card">
                <div class="dark-table-header">
                    <h2>Product Management</h2>
                </div>
                <table class="dark-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>WiFi Cameras</td>
                            <td id="dark-table-wifi">0</td>
                            <td><span class="badge-active">Active</span></td>
                            <td>
                                <a href="/admin/wifi-cameras" class="btn-action">Manage</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Access Control</td>
                            <td id="dark-table-access">0</td>
                            <td><span class="badge-inactive">Inactive</span></td>
                            <td>
                                <a href="/admin/access-control" class="btn-action">Manage</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Static Products</td>
                            <td id="dark-table-product">512</td>
                            <td><span class="badge-active">Active</span></td>
                            <td>
                                <a href="/admin/static-products" class="btn-action">Manage</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- VARIANT 2: Colorful Cards -->
<div id="variant-2" class="dashboard-variant" style="display: none;">
    <div class="colorful-dashboard">
        <div class="colorful-header">
            <div class="colorful-logo-section">
                <div class="colorful-logo">
                    <i class="bi bi-shop"></i>
                </div>
                <h1 class="colorful-brand">TechStore</h1>
            </div>
            <div class="colorful-actions">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <button class="colorful-login-btn">Login</button>
            </div>
        </div>

        <div class="colorful-content">
            <div class="colorful-hero">
                <h2 class="colorful-title">Admin Dashboard</h2>
                <p class="colorful-subtitle">Manage your store with ease</p>
            </div>

            <div class="colorful-stats">
                <div class="colorful-stat-card stat-blue">
                    <div class="colorful-stat-icon">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <p class="colorful-stat-label">WiFi Cameras</p>
                    <p class="colorful-stat-value" id="colorful-wifi-count">0</p>
                </div>
                <div class="colorful-stat-card stat-rose">
                    <div class="colorful-stat-icon">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <p class="colorful-stat-label">Access Control</p>
                    <p class="colorful-stat-value" id="colorful-access-count">0</p>
                </div>
                <div class="colorful-stat-card stat-emerald">
                    <div class="colorful-stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <p class="colorful-stat-label">Static Products</p>
                    <p class="colorful-stat-value" id="colorful-product-count">512</p>
                </div>
                <div class="colorful-stat-card stat-amber">
                    <div class="colorful-stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <p class="colorful-stat-label">Total Revenue</p>
                    <p class="colorful-stat-value">$24.5K</p>
                </div>
            </div>

            <div class="colorful-products">
                <div class="colorful-product-card product-blue">
                    <div class="colorful-product-icon">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <h3 class="colorful-product-title">WiFi Cameras</h3>
                    <p class="colorful-product-desc">Manage camera products</p>
                    <div class="colorful-product-footer">
                        <span class="colorful-product-count" id="colorful-card-wifi">0</span>
                        <a href="/admin/wifi-cameras" class="colorful-product-btn btn-blue">Manage</a>
                    </div>
                </div>
                <div class="colorful-product-card product-rose">
                    <div class="colorful-product-icon">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <h3 class="colorful-product-title">Access Control</h3>
                    <p class="colorful-product-desc">Control access systems</p>
                    <div class="colorful-product-footer">
                        <span class="colorful-product-count" id="colorful-card-access">0</span>
                        <a href="/admin/access-control" class="colorful-product-btn btn-rose">Manage</a>
                    </div>
                </div>
                <div class="colorful-product-card product-emerald">
                    <div class="colorful-product-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <h3 class="colorful-product-title">Static Products</h3>
                    <p class="colorful-product-desc">Product catalog</p>
                    <div class="colorful-product-footer">
                        <span class="colorful-product-count" id="colorful-card-product">512</span>
                        <a href="/admin/static-products" class="colorful-product-btn btn-emerald">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VARIANT 3: Glass Morphism -->
<div id="variant-3" class="dashboard-variant" style="display: none;">
    <div class="glass-dashboard">
        <div class="glass-bg">
            <div class="glass-blob glass-blob-1"></div>
            <div class="glass-blob glass-blob-2"></div>
        </div>

        <div class="glass-content">
            <div class="glass-header">
                <div class="glass-header-left">
                    <div class="glass-logo">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <h1 class="glass-title">TechStore</h1>
                        <p class="glass-subtitle">Admin Dashboard</p>
                    </div>
                </div>
                <div class="glass-header-right">
                    <button class="glass-btn-secondary">Home</button>
                    <button class="glass-btn-primary">Login</button>
                </div>
            </div>

            <div class="glass-stats">
                <div class="glass-stat-card">
                    <i class="bi bi-camera-video glass-stat-icon"></i>
                    <p class="glass-stat-label">WiFi Cameras</p>
                    <p class="glass-stat-value" id="glass-wifi-count">0</p>
                </div>
                <div class="glass-stat-card">
                    <i class="bi bi-fingerprint glass-stat-icon"></i>
                    <p class="glass-stat-label">Access Control</p>
                    <p class="glass-stat-value" id="glass-access-count">0</p>
                </div>
                <div class="glass-stat-card">
                    <i class="bi bi-box glass-stat-icon"></i>
                    <p class="glass-stat-label">Static Products</p>
                    <p class="glass-stat-value" id="glass-product-count">512</p>
                </div>
                <div class="glass-stat-card">
                    <i class="bi bi-graph-up glass-stat-icon"></i>
                    <p class="glass-stat-label">Total Sales</p>
                    <p class="glass-stat-value">$24.5K</p>
                </div>
            </div>

            <div class="glass-products">
                <h2 class="glass-products-title">Product Management</h2>
                <div class="glass-products-grid">
                    <div class="glass-product-card">
                        <i class="bi bi-camera-video glass-product-icon"></i>
                        <h3 class="glass-product-title">WiFi Cameras</h3>
                        <p class="glass-product-desc">Manage camera inventory</p>
                        <div class="glass-product-footer">
                            <span class="glass-product-count"><span id="glass-card-wifi">0</span> items</span>
                            <a href="/admin/wifi-cameras" class="glass-product-btn">Manage</a>
                        </div>
                    </div>
                    <div class="glass-product-card">
                        <i class="bi bi-fingerprint glass-product-icon"></i>
                        <h3 class="glass-product-title">Access Control</h3>
                        <p class="glass-product-desc">Access control systems</p>
                        <div class="glass-product-footer">
                            <span class="glass-product-count"><span id="glass-card-access">0</span> items</span>
                            <a href="/admin/access-control" class="glass-product-btn">Manage</a>
                        </div>
                    </div>
                    <div class="glass-product-card">
                        <i class="bi bi-box glass-product-icon"></i>
                        <h3 class="glass-product-title">Static Products</h3>
                        <p class="glass-product-desc">Product catalog</p>
                        <div class="glass-product-footer">
                            <span class="glass-product-count"><span id="glass-card-product">512</span> items</span>
                            <a href="/admin/static-products" class="glass-product-btn">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Variant Selector */
.variant-selector {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: white;
    padding: 15px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.3s ease;
    transform-origin: top right;
}

.variant-selector.hidden {
    opacity: 0;
    visibility: hidden;
    transform: scale(0.8) translateY(-20px);
}

/* Toggle Button */
.variant-toggle-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.variant-toggle-btn:hover {
    transform: scale(1.1) rotate(15deg);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.variant-toggle-btn:active {
    transform: scale(0.95);
}

.variant-toggle-btn.active {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

/* Logout Button */
.logout-btn {
    position: fixed;
    top: 20px;
    right: 90px;
    z-index: 10000;
    padding: 10px 20px;
    border-radius: 25px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border: none;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 5px 20px rgba(255, 107, 107, 0.4);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.6);
    background: linear-gradient(135deg, #ee5a6f 0%, #ff6b6b 100%);
}

.logout-btn:active {
    transform: translateY(0);
}

.logout-btn i {
    font-size: 16px;
}

/* Adjust selector position when toggle is visible */
.variant-selector {
    top: 80px;
}

.variant-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f1f3f5;
    color: #495057;
}

.variant-btn:hover {
    background: #e9ecef;
    transform: translateX(-3px);
}

.variant-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* ========================================
   VARIANT 1: DARK MODE
======================================== */
.dark-dashboard {
    display: flex;
    min-height: 100vh;
    background: #1a1a1a;
    color: white;
    margin: -20px;
}

.dark-sidebar {
    width: 260px;
    background: #111;
    padding: 30px;
    border-right: 1px solid #333;
}

.sidebar-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
}

.sidebar-logo {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.sidebar-title {
    font-size: 20px;
    font-weight: 700;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.nav-item {
    padding: 12px 16px;
    border-radius: 10px;
    color: #aaa;
    text-decoration: none;
    transition: all 0.3s;
}

.nav-item:hover {
    background: #222;
    color: white;
}

.nav-item.active {
    background: #222;
    color: white;
}

.dark-main {
    flex: 1;
    padding: 40px;
}

.dark-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.dark-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 5px;
}

.dark-subtitle {
    color: #888;
}

.dark-actions {
    display: flex;
    gap: 12px;
}

.dark-icon-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: none;
    background: #222;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
}

.dark-icon-btn:hover {
    background: #333;
}

.dark-icon-btn.logout-icon {
    background: rgba(255, 107, 107, 0.2);
    color: #ff6b6b;
}

.dark-icon-btn.logout-icon:hover {
    background: rgba(255, 107, 107, 0.3);
}

.dark-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.dark-stat-card {
    background: #222;
    padding: 30px;
    border-radius: 15px;
    border: 1px solid #333;
    transition: all 0.3s;
}

.dark-stat-card:hover {
    border-color: #444;
    transform: translateY(-5px);
}

.dark-stat-icon {
    font-size: 32px;
    color: #667eea;
    margin-bottom: 15px;
}

.dark-stat-label {
    color: #888;
    font-size: 14px;
    margin-bottom: 8px;
}

.dark-stat-value {
    font-size: 28px;
    font-weight: 700;
}

.dark-table-card {
    background: #222;
    border-radius: 15px;
    border: 1px solid #333;
    overflow: hidden;
}

.dark-table-header {
    padding: 24px;
    border-bottom: 1px solid #333;
}

.dark-table-header h2 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.dark-table {
    width: 100%;
    padding: 24px;
}

.dark-table th {
    text-align: left;
    color: #888;
    font-size: 14px;
    font-weight: 500;
    padding-bottom: 20px;
}

.dark-table td {
    padding: 16px 0;
    border-top: 1px solid #333;
}

.badge-active {
    padding: 5px 15px;
    border-radius: 20px;
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    font-size: 12px;
    font-weight: 600;
}

.badge-inactive {
    padding: 5px 15px;
    border-radius: 20px;
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
}

.btn-action {
    padding: 8px 16px;
    background: #333;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-action:hover {
    background: #444;
}

/* ========================================
   VARIANT 2: COLORFUL CARDS
======================================== */
.colorful-dashboard {
    min-height: 100vh;
    background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
    margin: -20px;
}

.colorful-header {
    background: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.colorful-logo-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.colorful-logo {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
}

.colorful-brand {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.colorful-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box i {
    position: absolute;
    left: 15px;
    color: #999;
}

.search-box input {
    padding: 10px 15px 10px 45px;
    border-radius: 25px;
    border: 1px solid #ddd;
    width: 250px;
    outline: none;
}

.search-box input:focus {
    border-color: #667eea;
}

.colorful-login-btn {
    padding: 10px 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.colorful-login-btn:hover {
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    transform: translateY(-2px);
}

.colorful-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px;
}

.colorful-hero {
    margin-bottom: 40px;
}

.colorful-title {
    font-size: 42px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.colorful-subtitle {
    font-size: 18px;
    color: #666;
}

.colorful-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.colorful-stat-card {
    padding: 30px;
    border-radius: 25px;
    transition: all 0.3s;
    cursor: pointer;
}

.colorful-stat-card:hover {
    transform: scale(1.05);
}

.stat-blue {
    background: #dbeafe;
}

.stat-rose {
    background: #ffe4e6;
}

.stat-emerald {
    background: #d1fae5;
}

.stat-amber {
    background: #fef3c7;
}

.colorful-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 26px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.stat-blue .colorful-stat-icon {
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
}

.stat-rose .colorful-stat-icon {
    background: linear-gradient(135deg, #f43f5e, #ec4899);
}

.stat-emerald .colorful-stat-icon {
    background: linear-gradient(135deg, #10b981, #14b8a6);
}

.stat-amber .colorful-stat-icon {
    background: linear-gradient(135deg, #f59e0b, #f97316);
}

.colorful-stat-label {
    color: #666;
    font-size: 14px;
    margin-bottom: 8px;
}

.colorful-stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #333;
}

.colorful-products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.colorful-product-card {
    background: white;
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.colorful-product-card:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}

.colorful-product-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 30px;
    margin-bottom: 20px;
}

.product-blue .colorful-product-icon {
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
}

.product-rose .colorful-product-icon {
    background: linear-gradient(135deg, #f43f5e, #ec4899);
}

.product-emerald .colorful-product-icon {
    background: linear-gradient(135deg, #10b981, #14b8a6);
}

.colorful-product-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.colorful-product-desc {
    color: #666;
    margin-bottom: 25px;
}

.colorful-product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.colorful-product-count {
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.colorful-product-btn {
    padding: 12px 28px;
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-blue {
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
}

.btn-rose {
    background: linear-gradient(135deg, #f43f5e, #ec4899);
}

.btn-emerald {
    background: linear-gradient(135deg, #10b981, #14b8a6);
}

.colorful-product-btn:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

/* ========================================
   VARIANT 3: GLASS MORPHISM
======================================== */
.glass-dashboard {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
    margin: -20px;
}

.glass-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.glass-blob {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    filter: blur(80px);
    animation: float 8s ease-in-out infinite;
}

.glass-blob-1 {
    width: 400px;
    height: 400px;
    top: -100px;
    left: -100px;
}

.glass-blob-2 {
    width: 500px;
    height: 500px;
    bottom: -150px;
    right: -150px;
    animation-delay: 2s;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-30px); }
}

.glass-content {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px;
}

.glass-header {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 25px;
    padding: 25px 35px;
    margin-bottom: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.glass-header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.glass-logo {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.glass-title {
    font-size: 30px;
    font-weight: 700;
    color: white;
    margin: 0;
}

.glass-subtitle {
    color: rgba(255,255,255,0.8);
    margin: 0;
}

.glass-header-right {
    display: flex;
    gap: 12px;
}

.glass-btn-secondary {
    padding: 12px 25px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 12px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.glass-btn-secondary:hover {
    background: rgba(255,255,255,0.3);
}

.glass-btn-primary {
    padding: 12px 25px;
    background: white;
    border: none;
    border-radius: 12px;
    color: #667eea;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.glass-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.glass-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.glass-stat-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 30px;
    transition: all 0.3s;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.glass-stat-card:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-5px);
}

.glass-stat-icon {
    font-size: 40px;
    color: white;
    margin-bottom: 20px;
}

.glass-stat-label {
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    margin-bottom: 10px;
}

.glass-stat-value {
    font-size: 36px;
    font-weight: 700;
    color: white;
}

.glass-products {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 25px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.glass-products-title {
    font-size: 26px;
    font-weight: 700;
    color: white;
    margin-bottom: 30px;
}

.glass-products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.glass-product-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 30px;
    transition: all 0.3s;
}

.glass-product-card:hover {
    background: rgba(255,255,255,0.15);
    transform: scale(1.05);
}

.glass-product-icon {
    font-size: 48px;
    color: white;
    margin-bottom: 20px;
}

.glass-product-title {
    font-size: 20px;
    font-weight: 700;
    color: white;
    margin-bottom: 10px;
}

.glass-product-desc {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
    margin-bottom: 25px;
}

.glass-product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.glass-product-count {
    font-size: 24px;
    font-weight: 700;
    color: white;
}

.glass-product-btn {
    padding: 10px 20px;
    background: white;
    color: #667eea;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.glass-product-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(255,255,255,0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .dark-stats,
    .colorful-stats,
    .glass-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .colorful-products,
    .glass-products-grid {
        grid-template-columns: 1fr;
    }
    
    /* Adjust button positions for smaller screens */
    .logout-btn span {
        display: none;
    }
    
    .logout-btn {
        padding: 10px;
        right: 85px;
        gap: 0;
    }
}

@media (max-width: 992px) {
    /* Stack buttons vertically on medium screens */
    .logout-btn {
        top: 80px;
        right: 20px;
        padding: 8px 16px;
        font-size: 12px;
    }
    
    .logout-btn span {
        display: inline;
    }
}

@media (max-width: 768px) {
    .dark-dashboard {
        flex-direction: column;
    }
    
    .dark-sidebar {
        width: 100%;
    }
    
    .dark-stats,
    .colorful-stats,
    .glass-stats {
        grid-template-columns: 1fr;
    }
    
    .variant-selector {
        flex-direction: row;
        top: 140px;
    }
    
    /* Mobile: Icon only buttons */
    .logout-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        padding: 0;
        justify-content: center;
        top: 80px;
        right: 20px;
    }
    
    .logout-btn span {
        display: none;
    }
    
    .logout-btn i {
        font-size: 20px;
        margin: 0;
    }
    
    .variant-toggle-btn {
        width: 45px;
        height: 45px;
    }
}

@media (max-width: 576px) {
    /* Extra small screens - compact layout */
    .logout-btn,
    .variant-toggle-btn {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .logout-btn {
        top: 75px;
    }
}
</style>

<script>
let currentVariant = 1;
let selectorVisible = false;

function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        // Create a form dynamically to submit POST request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        
        // Add CSRF token - Laravel requires this for POST requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.content;
            form.appendChild(tokenInput);
        } else {
            // Fallback: try to get token from any form on page
            const existingToken = document.querySelector('input[name="_token"]');
            if (existingToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = existingToken.value;
                form.appendChild(tokenInput);
            } else {
                console.error('CSRF token not found');
                alert('Session error. Please refresh the page and try again.');
                return;
            }
        }
        
        // Append form to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleVariantSelector() {
    const selector = document.getElementById('variantSelector');
    const toggle = document.getElementById('variantToggle');
    
    selectorVisible = !selectorVisible;
    
    if (selectorVisible) {
        selector.classList.remove('hidden');
        toggle.classList.add('active');
    } else {
        selector.classList.add('hidden');
        toggle.classList.remove('active');
    }
}

function switchVariant(variantNum) {
    // Hide all variants
    document.querySelectorAll('.dashboard-variant').forEach(v => {
        v.style.display = 'none';
    });
    
    // Show selected variant
    document.getElementById('variant-' + variantNum).style.display = 'block';
    
    // Update button states
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById('variant-btn-' + variantNum).classList.add('active');
    
    currentVariant = variantNum;
    
    // Hide selector after selection
    setTimeout(() => {
        toggleVariantSelector();
    }, 300);
    
    // Reload data for the new variant
    loadDashboardData();
}

function loadDashboardData() {
    // Load WiFi Camera count
    fetch('/api/admin/wifi-cameras')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const count = data.cameras.length;
                
                // Update all variants
                document.getElementById('dark-wifi-count').textContent = count;
                document.getElementById('dark-table-wifi').textContent = count;
                
                document.getElementById('colorful-wifi-count').textContent = count;
                document.getElementById('colorful-card-wifi').textContent = count;
                
                document.getElementById('glass-wifi-count').textContent = count;
                document.getElementById('glass-card-wifi').textContent = count;
            }
        })
        .catch(err => console.error('Error loading WiFi cameras:', err));

    // Load Access Control count
    fetch('/api/admin/access-control/statistics')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const count = data.statistics.total;
                
                // Update all variants
                document.getElementById('dark-access-count').textContent = count;
                document.getElementById('dark-table-access').textContent = count;
                
                document.getElementById('colorful-access-count').textContent = count;
                document.getElementById('colorful-card-access').textContent = count;
                
                document.getElementById('glass-access-count').textContent = count;
                document.getElementById('glass-card-access').textContent = count;
            }
        })
        .catch(err => console.error('Error loading access control:', err));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    switchVariant(1);
    // Hide selector by default
    document.getElementById('variantSelector').classList.add('hidden');
});
</script>

@endsection