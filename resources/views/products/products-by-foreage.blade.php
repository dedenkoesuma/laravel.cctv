@extends('layouts.simple')

@section('title', 'Produk Foreage - TechStore')

@section('content')
<style>
:root {
    --foreage-primary: #FF6B35;
    --foreage-dark: #C44D2C;
    --foreage-light: #FFF5F2;
    --gradient-primary: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

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

/* ===== HERO SECTION ===== */
.hero-section {
    position: relative;
    background: var(--gradient-primary);
    color: white;
    padding: 80px 0 120px;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
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
    color: var(--foreage-primary);
    font-weight: 700;
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
    border-color: var(--foreage-primary);
    color: var(--foreage-primary);
    background: var(--foreage-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.filter-btn.active {
    border-color: var(--foreage-primary);
    background: var(--foreage-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.25);
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
    border-color: var(--foreage-light);
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

.badge-category {
    background: var(--gradient-primary);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
}

.badge-stock {
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

.product-sku {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--foreage-primary);
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

.product-actions {
    display: flex;
    gap: 8px;
}

.btn-detail {
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
    border: 2px solid var(--foreage-primary);
    background: white;
    color: var(--foreage-primary);
}

.btn-detail:hover {
    background: var(--foreage-light);
    transform: translateY(-2px);
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
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.25);
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
    box-shadow: 0 20px 40px rgba(255, 107, 53, 0.25);
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
}

.btn-cta-primary {
    background: white;
    color: var(--foreage-primary);
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
    border-top: 4px solid var(--foreage-primary);
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
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .hero-section {
        padding: 60px 20px 100px;
    }
    
    .hero-stats {
        gap: 40px;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .filter-section {
        margin-top: -50px;
    }
    
    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .filter-btn {
        font-size: 0.8125rem;
        padding: 12px 16px;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-section {
        padding: 40px 24px;
        margin: 40px 16px;
    }
    
    .cta-title {
        font-size: 1.75rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .btn-cta {
        width: 100%;
        justify-content: center;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
    
    .filter-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }
}
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="bi bi-star-fill"></i>
            <span>Professional CCTV Solutions</span>
        </div>
        
        <h1 class="hero-title">Foreage</h1>
        
        <p class="hero-subtitle">
            Solusi CCTV profesional dengan teknologi terkini untuk keamanan maksimal 
            di rumah, kantor, dan bisnis Anda dengan harga terjangkau
        </p>
        
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number">300+</span>
                <span class="stat-label">Products</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">5K+</span>
                <span class="stat-label">Clients</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">Satisfaction</span>
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
            
            <div class="filter-grid" id="filterGrid">
                <button class="filter-btn active" data-category="" onclick="filterByCategory('', event)">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Semua</span>
                </button>
                <button class="filter-btn" data-category="basic" onclick="filterByCategory('basic', event)">
                    <i class="bi bi-camera-video"></i>
                    <span>Basic</span>
                </button>
                <button class="filter-btn" data-category="audio" onclick="filterByCategory('audio', event)">
                    <i class="bi bi-volume-up"></i>
                    <span>Audio</span>
                </button>
                <button class="filter-btn" data-category="fullcolor" onclick="filterByCategory('fullcolor', event)">
                    <i class="bi bi-palette"></i>
                    <span>Full Color</span>
                </button>
                <button class="filter-btn" data-category="ipcamera" onclick="filterByCategory('ipcamera', event)">
                    <i class="bi bi-camera"></i>
                    <span>IP Camera</span>
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
            <h2 class="section-title">Keunggulan Produk Foreage</h2>
            <p class="section-subtitle">Teknologi canggih untuk keamanan dan kenyamanan Anda</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="feature-title">Kualitas Terjamin</h3>
                <p class="feature-text">Produk berkualitas tinggi dengan garansi resmi dan layanan purna jual terbaik</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3 class="feature-title">High Resolution</h3>
                <p class="feature-text">Resolusi hingga 8MP untuk gambar yang tajam dan detail maksimal</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-moon-stars"></i>
                </div>
                <h3 class="feature-title">Night Vision</h3>
                <p class="feature-text">Teknologi infrared dan full color untuk pemantauan 24/7 dalam berbagai kondisi</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-phone"></i>
                </div>
                <h3 class="feature-title">Mobile Access</h3>
                <p class="feature-text">Akses mudah melalui smartphone dari mana saja kapan saja</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <span class="cta-icon">📞</span>
        <h2 class="cta-title">Butuh Konsultasi CCTV?</h2>
        <p class="cta-text">
            Tim ahli kami siap membantu Anda memilih sistem CCTV Foreage yang tepat 
            sesuai kebutuhan keamanan rumah atau bisnis Anda
        </p>
        
        <div class="cta-buttons">
            <button class="btn-cta btn-cta-primary" onclick="contactWhatsApp()">
                <i class="bi bi-whatsapp"></i>
                <span>Chat via WhatsApp</span>
            </button>
            <button class="btn-cta btn-cta-secondary" onclick="window.location.href='{{ url('/') }}'">
                <i class="bi bi-house"></i>
                <span>Kembali ke Home</span>
            </button>
        </div>
    </div>
</div>

<script>
let allProducts = {
    basic: @json($staticProducts['basic'] ?? []),
    audio: @json($staticProducts['audio'] ?? []),
    fullcolor: @json($staticProducts['fullcolor'] ?? []),
    ipcamera: @json($staticProducts['ipcamera'] ?? [])
};
let currentCategory = '';

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        hideLoading();
        renderProducts();
        updateFilterCount();
    }, 500);
});

function renderProducts() {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = '';
    
    let filteredProducts = [];
    if (currentCategory) {
        filteredProducts = allProducts[currentCategory] || [];
    } else {
        filteredProducts = [
            ...allProducts.basic,
            ...allProducts.audio,
            ...allProducts.fullcolor,
            ...allProducts.ipcamera
        ];
    }
    
    if (filteredProducts.length === 0) {
        showEmptyState();
        return;
    }
    
    filteredProducts.forEach((product) => {
        const card = document.createElement('div');
        
        let features = [];
        if (product.deskripsi) {
            features = product.deskripsi.split('\n').filter(f => f.trim());
        }
        
        let stockClass = 'in-stock';
        let stockText = '✓ Tersedia';
        
        card.className = 'product-card';
        card.innerHTML = `
            <div class="product-image-wrapper">
                <div class="product-badges">
                    <span class="badge-category">${getCategoryName(product.kategori)}</span>
                    <span class="badge-stock ${stockClass}">${stockText}</span>
                </div>
                
                <img src="${product.gambar ? '/storage/' + product.gambar : 'https://via.placeholder.com/300x200/FF6B35/ffffff?text=Foreage'}" 
                     alt="${product.nama_produk}"
                     class="product-image"
                     onerror="this.src='https://via.placeholder.com/300x200/FF6B35/ffffff?text=Foreage'">
            </div>
            
            <div class="product-content">
                <div class="product-sku">
                    <i class="bi bi-upc-scan"></i>
                    <span>${product.sku || 'SKU-' + product.id}</span>
                </div>
                
                <h3 class="product-name">${product.nama_produk}</h3>
                
                <ul class="product-features">
                    ${features.slice(0, 6).map(f => 
                        `<li><i class="bi bi-check-circle-fill"></i><span>${f}</span></li>`
                    ).join('')}
                </ul>
                
                <div class="product-footer">
                    <div class="product-actions">
                        <button class="btn-detail" onclick="viewDetail(${product.id})">
                            <i class="bi bi-eye"></i>
                            <span>Lihat Detail</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        grid.appendChild(card);
    });
}

function filterByCategory(category, event) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.filter-btn').classList.add('active');
    
    currentCategory = category;
    renderProducts();
    updateFilterCount();
    
    document.getElementById('productsSection').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

function getCategoryName(category) {
    const names = {
        'basic': 'Basic CCTV',
        'audio': 'Audio CCTV',
        'fullcolor': 'Full Color',
        'ipcamera': 'IP Camera'
    };
    return names[category] || category;
}

function updateFilterCount() {
    let count = 0;
    if (currentCategory) {
        count = (allProducts[currentCategory] || []).length;
    } else {
        count = allProducts.basic.length + allProducts.audio.length + 
                allProducts.fullcolor.length + allProducts.ipcamera.length;
    }
    
    const categoryText = currentCategory ? ` - ${getCategoryName(currentCategory)}` : '';
    document.getElementById('filterCount').innerHTML = 
        `Menampilkan <strong>${count}</strong> produk${categoryText}`;
}

function viewDetail(id) {
    alert('Detail produk #' + id + ' - Coming soon!');
}

function contactWhatsApp() {
    const message = 'Halo, saya tertarik dengan produk Foreage CCTV. Mohon informasi lebih lanjut.';
    window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(message), '_blank');
}

function showLoading() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('productsSection').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingState').style.display = 'none';
    document.getElementById('productsSection').style.display = 'block';
}

function showEmptyState() {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = `
        <div style="grid-column: 1 / -1;">
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h3 class="empty-title">Tidak ada produk</h3>
                <p class="empty-text">Kategori ini belum memiliki produk. Silakan pilih kategori lain.</p>
            </div>
        </div>
    `;
}
</script>
@endsection