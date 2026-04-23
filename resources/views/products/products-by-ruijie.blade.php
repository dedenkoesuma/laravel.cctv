@extends('layouts.simple')

@section('title', 'Ruijie Products')

@section('content')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* ===== RUIJIE PAGE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #f8fafc;
    color: #1e293b;
}

:root {
    --ruijie-primary: #00A7E1;
    --ruijie-dark: #005A8D;
    --ruijie-light: #E6F7FF;
    --gradient-primary: linear-gradient(135deg, #00A7E1 0%, #0088B8 100%);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== HERO SECTION ===== */
.hero-section {
    position: relative;
    background-image: url("{{ asset('storage/gambar/ruijie.jpg') }}");
    color: white;
    padding: 80px 0 120px;
    overflow: hidden;
    background-size:cover;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: black;
    opacity: 0.5;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    padding: 0 20px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 10px 24px;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 24px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: fadeInDown 0.6s ease-out;
}

.hero-title {
    font-size: clamp(2.5rem, 8vw, 4.5rem);
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.1;
    letter-spacing: -0.02em;
    animation: fadeInUp 0.6s ease-out 0.1s backwards;
}

.hero-subtitle {
    font-size: clamp(1rem, 2vw, 1.25rem);
    opacity: 0.95;
    line-height: 1.7;
    margin-bottom: 40px;
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 60px;
    animation: fadeInUp 0.6s ease-out 0.3s backwards;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    display: block;
    line-height: 1;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    font-weight: 500;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    position: relative;
    margin-top: -60px;
    margin-bottom: 60px;
    z-index: 10;
}

.filter-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.filter-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.filter-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
}

.filter-count {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.filter-count strong {
    color: var(--ruijie-primary);
    font-weight: 700;
}

/* ===== SEARCH BAR ===== */
.search-wrapper {
    margin-bottom: 20px;
}

.search-input-group {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 4px 16px;
    gap: 10px;
    transition: var(--transition);
}

.search-input-group:focus-within {
    border-color: var(--ruijie-primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(0, 167, 225, 0.1);
}

.search-input-group > i {
    color: #94a3b8;
    font-size: 1.1rem;
    flex-shrink: 0;
    transition: color 0.2s;
}

.search-input-group:focus-within > i {
    color: var(--ruijie-primary);
}

.search-input-group input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 0;
    font-size: 0.9375rem;
    color: #0f172a;
    outline: none;
    font-family: inherit;
}

.search-input-group input::placeholder {
    color: #94a3b8;
}

.search-clear-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 4px;
    border-radius: 6px;
    display: none;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    font-size: 1rem;
    line-height: 1;
}

.search-clear-btn:hover {
    color: #ef4444;
    background: #fee2e2;
}

.no-results-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}

.no-results-state i {
    font-size: 3.5rem;
    color: #cbd5e1;
    margin-bottom: 16px;
    display: block;
}

.no-results-state h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.no-results-state p {
    color: #64748b;
    margin-bottom: 20px;
}

.btn-reset-search {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    background: var(--ruijie-primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btn-reset-search:hover {
    background: var(--ruijie-dark);
    transform: translateY(-2px);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.filter-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 20px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #475569;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.filter-btn:hover {
    border-color: var(--ruijie-primary);
    color: var(--ruijie-primary);
    background: var(--ruijie-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.filter-btn.active {
    border-color: var(--ruijie-primary);
    background: var(--ruijie-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(0, 167, 225, 0.25);
}

.filter-btn i {
    font-size: 1.1rem;
}

/* ===== PRODUCTS SECTION ===== */
.products-section {
    padding: 0 20px 80px;
    max-width: 1400px;
    margin: 0 auto;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 28px;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--ruijie-light);
}

.product-image-wrapper {
    position: relative;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 40px 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 260px;
}

.product-image {
    width: 100%;
    max-height: 200px;
    object-fit: contain;
    transition: var(--transition);
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    gap: 8px;
}

.badge-sale {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.badge-featured {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #78350f;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
}

.badge-stock {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.badge-stock.in-stock { color: #10b981; }
.badge-stock.low-stock { color: #f59e0b; }
.badge-stock.out-stock { color: #ef4444; }

.product-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-category {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--ruijie-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.product-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
    line-height: 1.4;
    min-height: 56px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-description {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-features {
    list-style: none;
    margin: 0 0 20px 0;
    padding: 0;
}

.product-features li {
    font-size: 0.8125rem;
    color: #475569;
    margin-bottom: 8px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
}

.product-features li i {
    color: #10b981;
    font-size: 0.875rem;
    margin-top: 3px;
    flex-shrink: 0;
}

.product-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

.product-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.product-price {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.price-current {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--ruijie-primary);
}

.price-original {
    font-size: 0.875rem;
    color: #94a3b8;
    text-decoration: line-through;
}

.discount-badge {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
}

.product-actions {
    display: flex;
    gap: 8px;
}

.btn-detail,
.btn-buy {
    flex: 1;
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
}

.btn-detail {
    border: 2px solid var(--ruijie-primary);
    background: white;
    color: var(--ruijie-primary);
}

.btn-detail:hover {
    background: var(--ruijie-light);
    transform: translateY(-2px);
}

.btn-buy {
    border: none;
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(0, 167, 225, 0.25);
}

.btn-buy:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 167, 225, 0.35);
}

.btn-buy:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    box-shadow: none;
}

/* ===== FEATURES SECTION ===== */
.features-section {
    background: white;
    padding: 80px 20px;
    margin: 60px 0;
}

.features-container {
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
    letter-spacing: -0.02em;
}

.section-subtitle {
    font-size: 1.125rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 32px;
}

.feature-card {
    text-align: center;
    padding: 32px 24px;
    border-radius: 16px;
    background: #f8fafc;
    transition: var(--transition);
}

.feature-card:hover {
    background: white;
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}

.feature-icon {
    width: 72px;
    height: 72px;
    background: var(--gradient-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 20px rgba(0, 167, 225, 0.25);
}

.feature-icon i {
    font-size: 2rem;
    color: white;
}

.feature-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
}

.feature-text {
    font-size: 0.9375rem;
    color: #64748b;
    line-height: 1.6;
}

/* ===== SOLUTIONS SECTION ===== */
.solutions-section {
    padding: 80px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.solutions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
    margin-top: 50px;
}

.solution-card {
    background: white;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    border: 2px solid #f1f5f9;
    transition: var(--transition);
}

.solution-card:hover {
    border-color: var(--ruijie-primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.solution-icon {
    font-size: 3rem;
    display: block;
    margin-bottom: 16px;
}

.solution-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.solution-text {
    font-size: 0.9375rem;
    color: #64748b;
    line-height: 1.6;
}

/* ===== CTA SECTION ===== */
.cta-section {
    background: var(--gradient-primary);
    border-radius: 24px;
    padding: 60px 40px;
    text-align: center;
    margin: 60px 20px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 20px 40px rgba(0, 167, 225, 0.25);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.cta-content {
    position: relative;
    z-index: 1;
}

.cta-icon {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.cta-title {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 12px;
}

.cta-text {
    font-size: 1.125rem;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 32px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta {
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    border: none;
}

.btn-cta-primary {
    background: white;
    color: var(--ruijie-primary);
    border: 2px solid white;
}

.btn-cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
}

.btn-cta-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

/* ===== LOADING & EMPTY STATES ===== */
.loading-state,
.empty-state {
    text-align: center;
    padding: 100px 20px;
}

.spinner {
    width: 48px;
    height: 48px;
    margin: 0 auto 20px;
    border: 4px solid #f1f5f9;
    border-top: 4px solid var(--ruijie-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-text {
    font-size: 1rem;
    color: #64748b;
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.empty-text {
    font-size: 1rem;
    color: #64748b;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .hero-section { padding: 60px 20px 100px; }
    .hero-stats { gap: 40px; }
    .stat-number { font-size: 2.5rem; }
    .filter-section { margin-top: -50px; }
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .filter-btn { font-size: 0.8125rem; padding: 12px 16px; }
    .products-grid { grid-template-columns: 1fr; }
    .features-grid, .solutions-grid { grid-template-columns: 1fr; }
    .cta-section { padding: 40px 24px; margin: 40px 16px; }
    .cta-title { font-size: 1.75rem; }
    .cta-buttons { flex-direction: column; }
    .btn-cta { width: 100%; justify-content: center; }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .products-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
    .filter-grid { grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); }
}
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="bi bi-star-fill"></i>
            <span>Enterprise Networking Solutions</span>
        </div>
        
        <h1 class="hero-title">Ruijie Networks</h1>
        
        <p class="hero-subtitle">
            Solusi networking enterprise-grade dengan teknologi terkini untuk infrastruktur 
            jaringan yang handal, scalable, dan mudah dikelola
        </p>
        
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number">500</span>
                <span class="stat-label">+ Products</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">10K</span>
                <span class="stat-label">+ Clients</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">99</span>
                <span class="stat-label">% Satisfaction</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-container">
        <div class="filter-card">
            <div class="filter-header">
                <h2 class="filter-title">Filter Produk</h2>
                <div class="filter-count" id="filterCount">
                    Menampilkan <strong>0</strong> produk
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-wrapper">
                <div class="search-input-group">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        id="productSearch"
                        placeholder="Cari produk (contoh: RG-ES08G, 24 Port, PoE Switch...)"
                        oninput="handleSearch(this.value)"
                        autocomplete="off"
                    >
                    <button class="search-clear-btn" id="searchClearBtn" onclick="clearSearch()" title="Hapus pencarian">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>
            
            <div class="filter-grid" id="filterGrid">
                <button class="filter-btn active" data-category="" onclick="filterByCategory('', event)">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Semua</span>
                </button>
                <button class="filter-btn" data-category="Switch" onclick="filterByCategory('Switch', event)">
                    <i class="bi bi-hdd-network"></i>
                    <span>Switch</span>
                </button>
                <button class="filter-btn" data-category="Router" onclick="filterByCategory('Router', event)">
                    <i class="bi bi-router"></i>
                    <span>Router</span>
                </button>
                <button class="filter-btn" data-category="Access Point" onclick="filterByCategory('Access Point', event)">
                    <i class="bi bi-wifi"></i>
                    <span>Access Point</span>
                </button>
                <button class="filter-btn" data-category="Wireless Controller" onclick="filterByCategory('Wireless Controller', event)">
                    <i class="bi bi-controller"></i>
                    <span>Controller</span>
                </button>
                <button class="filter-btn" data-category="Gateway" onclick="filterByCategory('Gateway', event)">
                    <i class="bi bi-diagram-3"></i>
                    <span>Gateway</span>
                </button>
                <button class="filter-btn" data-category="Firewall" onclick="filterByCategory('Firewall', event)">
                    <i class="bi bi-shield-check"></i>
                    <span>Firewall</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loadingState" class="loading-state">
    <div class="spinner"></div>
    <p class="loading-text">Memuat produk...</p>
</div>

<!-- Products Section -->
<div class="products-section" id="productsSection" style="display: none;">
    <div class="products-grid" id="productsGrid">
        <!-- Products will be loaded here -->
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="features-container">
        <div class="section-header">
            <h2 class="section-title">Keunggulan Produk Ruijie</h2>
            <p class="section-subtitle">Teknologi terdepan untuk performa dan keamanan maksimal</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-speedometer2"></i></div>
                <h3 class="feature-title">High Performance</h3>
                <p class="feature-text">Performa tinggi dengan throughput maksimal untuk kebutuhan enterprise yang demanding</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-cloud-check"></i></div>
                <h3 class="feature-title">Cloud Management</h3>
                <p class="feature-text">Kelola seluruh infrastruktur jaringan dari mana saja dengan platform cloud terpusat</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                <h3 class="feature-title">Enterprise Security</h3>
                <p class="feature-text">Keamanan tingkat enterprise dengan firewall, IPS/IDS, dan advanced threat protection</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <h3 class="feature-title">Scalable Solution</h3>
                <p class="feature-text">Mudah dikembangkan sesuai dengan pertumbuhan bisnis dan kebutuhan pengguna</p>
            </div>
        </div>
    </div>
</div>

<!-- Solutions Section -->
<div class="solutions-section">
    <div class="section-header">
        <h2 class="section-title">Solusi Untuk Berbagai Industri</h2>
        <p class="section-subtitle">Aplikasi networking yang fleksibel untuk setiap kebutuhan bisnis</p>
    </div>
    
    <div class="solutions-grid">
        <div class="solution-card">
            <span class="solution-icon">🏢</span>
            <h3 class="solution-title">Enterprise Office</h3>
            <p class="solution-text">Jaringan handal untuk kantor modern dengan ratusan hingga ribuan user</p>
        </div>
        <div class="solution-card">
            <span class="solution-icon">🏨</span>
            <h3 class="solution-title">Retail & Hospitality</h3>
            <p class="solution-text">WiFi berkualitas tinggi untuk toko, restoran, hotel, dan guest experience</p>
        </div>
        <div class="solution-card">
            <span class="solution-icon">🎓</span>
            <h3 class="solution-title">Education</h3>
            <p class="solution-text">Infrastruktur networking stabil untuk kampus, sekolah, dan e-learning</p>
        </div>
        <div class="solution-card">
            <span class="solution-icon">🏥</span>
            <h3 class="solution-title">Healthcare</h3>
            <p class="solution-text">Jaringan aman dan reliable untuk sistem informasi rumah sakit</p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <span class="cta-icon">📞</span>
        <h2 class="cta-title">Butuh Konsultasi Networking?</h2>
        <p class="cta-text">
            Tim ahli kami siap membantu Anda memilih dan merancang solusi Ruijie 
            yang tepat untuk kebutuhan infrastruktur jaringan bisnis Anda
        </p>
        <div class="cta-buttons">
            <button class="btn-cta btn-cta-primary" onclick="contactWhatsApp()">
                <i class="bi bi-whatsapp"></i>
                <span>Chat via WhatsApp</span>
            </button>
            <button class="btn-cta btn-cta-secondary" onclick="contactEmail()">
                <i class="bi bi-envelope"></i>
                <span>Email Kami</span>
            </button>
        </div>
    </div>
</div>

<script>
// ─── State ────────────────────────────────────────────────────────────────────
let allProducts    = [];
let currentCategory = '';
let currentSearch  = '';

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    loadProducts();
});

// ─── Load from API ────────────────────────────────────────────────────────────
function loadProducts() {
    showLoading();

    fetch('/api/ruijie-products', { headers: { 'Accept': 'application/json' } })
        .then(async response => {
            const ct = response.headers.get('content-type');
            if (ct && ct.includes('application/json')) return response.json();
            throw new Error('Server returned invalid response');
        })
        .then(data => {
            hideLoading();
            if (data.success) {
                allProducts = data.products || [];
                renderProducts();
                updateFilterCount();
            } else {
                showError('Gagal memuat produk');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat produk');
        });
}

// ─── Render ───────────────────────────────────────────────────────────────────
function renderProducts() {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = '';

    // 1. Filter aktif
    let filtered = allProducts.filter(p => p.status === 'active');

    // 2. Filter kategori
    if (currentCategory) {
        filtered = filtered.filter(p => p.category === currentCategory);
    }

    // 3. Filter pencarian (nama + deskripsi + kategori)
    if (currentSearch) {
        const q = currentSearch.toLowerCase();
        filtered = filtered.filter(p =>
            p.name.toLowerCase().includes(q) ||
            (p.description && p.description.toLowerCase().includes(q)) ||
            (p.category   && p.category.toLowerCase().includes(q))
        );
    }

    if (filtered.length === 0) {
        showEmptyState();
        return;
    }

    filtered.forEach(product => {
        const card = document.createElement('div');

        const onSale   = product.original_price && product.original_price > product.price;
        const discount = onSale ? Math.round(((product.original_price - product.price) / product.original_price) * 100) : 0;

        let stockClass = 'in-stock', stockText = '✓ Tersedia';
        if      (product.stock === 0) { stockClass = 'out-stock';  stockText = '✗ Habis';    }
        else if (product.stock < 5)   { stockClass = 'low-stock';  stockText = '⚠ Terbatas'; }

        // Highlight keyword di nama & deskripsi
        const highlight = (text) => {
            if (!currentSearch || !text) return text;
            return text.replace(
                new RegExp(`(${escapeRegex(currentSearch)})`, 'gi'),
                '<mark style="background:#E6F7FF;color:var(--ruijie-primary);border-radius:3px;padding:0 2px;">$1</mark>'
            );
        };

        let contentHtml = '';
        if (product.description) {
            contentHtml = `<p class="product-description">${highlight(product.description)}</p>`;
        } else if (product.features) {
            try {
                const features = typeof product.features === 'string'
                    ? JSON.parse(product.features)
                    : product.features;
                contentHtml = '<ul class="product-features">' +
                    features.slice(0, 3).map(f =>
                        `<li><i class="bi bi-check-circle-fill"></i><span>${f}</span></li>`
                    ).join('') +
                    '</ul>';
            } catch (e) { contentHtml = ''; }
        }

        card.className = 'product-card';
        card.innerHTML = `
            <div class="product-image-wrapper">
                <div class="product-badges">
                    <div>${onSale ? `<span class="badge-sale">-${discount}%</span>` : ''}</div>
                    <div>${product.is_featured ? '<span class="badge-featured">⭐ Featured</span>' : ''}</div>
                </div>
                <img src="${product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/300x200/00A7E1/ffffff?text=Ruijie'}"
                     alt="${product.name}"
                     class="product-image"
                     onerror="this.src='https://via.placeholder.com/300x200/00A7E1/ffffff?text=Ruijie'">
                <span class="badge-stock ${stockClass}">${stockText}</span>
            </div>

            <div class="product-content">
                <div class="product-category">
                    <i class="bi ${getCategoryIcon(product.category)}"></i>
                    <span>${product.category}</span>
                </div>

                <h3 class="product-name">${highlight(product.name)}</h3>

                ${contentHtml}

                <div class="product-footer">
                    <div class="product-price-row">
                        <div class="product-price">
                            <span class="price-current">Rp ${formatPrice(product.price)}</span>
                            ${onSale ? `<span class="price-original">Rp ${formatPrice(product.original_price)}</span>` : ''}
                        </div>
                        ${onSale ? `<span class="discount-badge">-${discount}%</span>` : ''}
                    </div>
                    <div class="product-actions">
                        <button class="btn-detail" onclick="showDetail(${product.id})">
                            <i class="bi bi-eye"></i><span>Detail</span>
                        </button>
                        <button class="btn-buy" ${product.stock === 0 ? 'disabled' : ''} onclick="buyProduct(${product.id})">
                            <i class="bi bi-cart-plus"></i>
                            <span>${product.stock === 0 ? 'Habis' : 'Beli'}</span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        grid.appendChild(card);
    });
}

// ─── Search handlers ──────────────────────────────────────────────────────────
function handleSearch(value) {
    currentSearch = value.trim();
    const clearBtn = document.getElementById('searchClearBtn');
    clearBtn.style.display = currentSearch ? 'flex' : 'none';
    renderProducts();
    updateFilterCount();
}

function clearSearch() {
    currentSearch = '';
    document.getElementById('productSearch').value = '';
    document.getElementById('searchClearBtn').style.display = 'none';
    renderProducts();
    updateFilterCount();
    document.getElementById('productSearch').focus();
}

function escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// ─── Category filter ──────────────────────────────────────────────────────────
function filterByCategory(category, event) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.filter-btn').classList.add('active');

    currentCategory = category;
    renderProducts();
    updateFilterCount();

    document.getElementById('productsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function getCategoryIcon(category) {
    const icons = {
        'Switch': 'bi-hdd-network', 'Router': 'bi-router',
        'Access Point': 'bi-wifi',  'Wireless Controller': 'bi-controller',
        'Gateway': 'bi-diagram-3',  'Firewall': 'bi-shield-check'
    };
    return icons[category] || 'bi-box';
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('id-ID');
}

function updateFilterCount() {
    let filtered = allProducts.filter(p => p.status === 'active');
    if (currentCategory) filtered = filtered.filter(p => p.category === currentCategory);
    if (currentSearch) {
        const q = currentSearch.toLowerCase();
        filtered = filtered.filter(p =>
            p.name.toLowerCase().includes(q) ||
            (p.description && p.description.toLowerCase().includes(q)) ||
            (p.category    && p.category.toLowerCase().includes(q))
        );
    }

    let label = `Menampilkan <strong>${filtered.length}</strong> produk`;
    if (currentCategory) label += ` &nbsp;·&nbsp; <span style="color:#475569">${currentCategory}</span>`;
    if (currentSearch)   label += ` &nbsp;·&nbsp; "<span style="color:var(--ruijie-primary)">${currentSearch}</span>"`;

    document.getElementById('filterCount').innerHTML = label;
}

// ─── Actions ──────────────────────────────────────────────────────────────────
function showDetail(id) {
    window.location.href = `/products/ruijie/${id}`;
}

function buyProduct(id) {
    const product = allProducts.find(p => p.id === id);
    const waNumber = "6281234567890"; // GANTI DENGAN NOMOR WA KAMU

    if (product) {
        const price   = parseInt(product.price).toLocaleString('id-ID');
        const message = `Halo Admin TechStore, saya tertarik untuk membeli produk Ruijie:\n\n*${product.name}*\nHarga: Rp ${price}\n\nApakah stoknya masih tersedia?`;
        window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');
    } else {
        window.open(`https://wa.me/${waNumber}?text=Halo%20Admin,%20saya%20tertarik%20membeli%20produk%20Ruijie.`, '_blank');
    }
}

function contactWhatsApp() {
    window.open('https://wa.me/6281234567890?text=Halo, saya tertarik dengan produk Ruijie Networks', '_blank');
}

function contactEmail() {
    window.location.href = 'mailto:sales@techstore.com?subject=Konsultasi Ruijie Networks';
}

// ─── UI states ────────────────────────────────────────────────────────────────
function showLoading() {
    document.getElementById('loadingState').style.display    = 'block';
    document.getElementById('productsSection').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingState').style.display    = 'none';
    document.getElementById('productsSection').style.display = 'block';
}

function showEmptyState() {
    const isSearching = currentSearch !== '';
    document.getElementById('productsGrid').innerHTML = `
        <div class="no-results-state">
            <i class="bi bi-${isSearching ? 'search' : 'inbox'}"></i>
            <h3>${isSearching ? `Produk "${currentSearch}" tidak ditemukan` : 'Tidak ada produk'}</h3>
            <p>${isSearching
                ? 'Coba kata kunci yang berbeda atau hapus filter kategori'
                : 'Coba pilih kategori lain atau kembali ke semua produk'}</p>
            ${isSearching ? `<button class="btn-reset-search" onclick="clearSearch()">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Pencarian
            </button>` : ''}
        </div>
    `;
}

function showError(message) {
    document.getElementById('productsSection').style.display = 'block';
    document.getElementById('productsGrid').innerHTML = `
        <div style="grid-column: 1 / -1;">
            <div class="empty-state">
                <i class="bi bi-exclamation-triangle empty-icon" style="color:#ef4444;"></i>
                <h3 class="empty-title">Terjadi Kesalahan</h3>
                <p class="empty-text">${message}</p>
                <button class="btn-cta btn-cta-primary" onclick="loadProducts()" style="margin-top:24px;">
                    <i class="bi bi-arrow-clockwise"></i><span>Coba Lagi</span>
                </button>
            </div>
        </div>
    `;
}
</script>
@endsection