@extends('layouts.simple')

@section('title', 'WiFi Camera - TechStore')

@section('content')
<style>
/* ===== PAGE HEADER ===== */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 20px 60px;
    margin-bottom: 50px;
}

.page-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
}

.page-description {
    font-size: clamp(1rem, 2vw, 1.2rem);
    opacity: 0.95;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

/* ===== BREADCRUMB ===== */
.breadcrumb-custom {
    background: transparent;
    padding: 0;
    margin-bottom: 2rem;
}

.breadcrumb-custom .breadcrumb-item {
    color: rgba(255,255,255,0.8);
}

.breadcrumb-custom .breadcrumb-item.active {
    color: white;
}

.breadcrumb-custom .breadcrumb-item a {
    color: white;
    text-decoration: none;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 3rem;
}

.filter-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
}

.filter-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 12px 32px;
    border-radius: 50px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 120px;
    text-align: center;
}

.filter-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.filter-btn.active {
    background: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Brand specific colors */
.filter-btn[data-brand="HIKVISION"]:hover,
.filter-btn[data-brand="HIKVISION"].active {
    background: #DC143C;
    border-color: #DC143C;
}

.filter-btn[data-brand="Dahua"]:hover,
.filter-btn[data-brand="Dahua"].active {
    background: #003D7A;
    border-color: #003D7A;
}

.filter-btn[data-brand="HiLook"]:hover,
.filter-btn[data-brand="HiLook"].active {
    background: #8B4513;
    border-color: #8B4513;
}

.filter-btn[data-brand="EZVIZ"]:hover,
.filter-btn[data-brand="EZVIZ"].active {
    background: #4A90E2;
    border-color: #4A90E2;
}

.filter-btn[data-brand="UNV"]:hover,
.filter-btn[data-brand="UNV"].active {
    background: #6A0DAD;
    border-color: #6A0DAD;
}

.filter-btn[data-brand="RUIJIE"]:hover,
.filter-btn[data-brand="RUIJIE"].active {
    background: #00A7E1;
    border-color: #00A7E1;
}

.filter-btn[data-brand="HIVIEW"]:hover,
.filter-btn[data-brand="HIVIEW"].active {
    background: #8B0000;
    border-color: #8B0000;
}

.filter-btn[data-brand="IMOU"]:hover,
.filter-btn[data-brand="IMOU"].active {
    background: #FF6B35;
    border-color: #FF6B35;
}

.filter-btn[data-brand="TP-LINK"]:hover,
.filter-btn[data-brand="TP-LINK"].active {
    background: #00A19D;
    border-color: #00A19D;
}

/* ===== PRODUCT CARDS ===== */
.wifi-camera-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.wifi-camera-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}


.camera-image {
    padding: 0; 
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    width: 100%;
    height: 250px; 
    overflow: hidden;
}

.camera-image img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
}

.wifi-camera-card:hover .camera-image img {
    transform: scale(1.05);
}

.badge-new {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #28a745;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

.badge-sale {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #dc3545;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

.badge-featured {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #ffc107;
    color: #333;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

.camera-content {
    padding: 1.5rem;
}

.camera-brand {
    font-size: 0.75rem;
    font-weight: 600;
    color: #667eea;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.camera-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
    min-height: 60px;
}

.camera-specs {
    list-style: none;
    padding: 0;
    margin-bottom: 1rem;
}

.camera-specs li {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.4rem;
}

.camera-specs li i {
    color: #28a745;
    margin-right: 0.5rem;
    font-size: 1rem;
}

.camera-price {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.price-current {
    font-size: 1.5rem;
    font-weight: 700;
    color: #dc3545;
}

.price-original {
    font-size: 1rem;
    color: #6c757d;
    text-decoration: line-through;
}

.camera-footer {
    padding: 0 1.5rem 1.5rem;
    display: flex;
    gap: 0.5rem;
}

.btn-detail {
    flex: 1;
    padding: 0.75rem;
    border-radius: 8px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    background: #667eea;
    color: white;
}

.btn-buy {
    flex: 2;
    padding: 0.75rem;
    border-radius: 8px;
    border: none;
    background: #667eea;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-buy:hover {
    background: #764ba2;
}

.btn-buy:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* ===== LOADING & EMPTY STATES ===== */
.loading-state {
    text-align: center;
    padding: 60px 20px;
}

.loading-state .spinner-border {
    width: 3rem;
    height: 3rem;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
}

/* ===== FEATURES SECTION ===== */
.features-section {
    background: #f8f9fa;
    padding: 4rem 0;
    margin-top: 4rem;
}

.feature-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    height: 100%;
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feature-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
}

.feature-description {
    color: #6c757d;
    font-size: 0.95rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 767px) {
    .page-header {
        padding: 60px 20px 40px;
    }
    
    .filter-buttons {
        flex-direction: column;
    }
    
    .filter-btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom justify-content-center">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">WiFi Camera</li>
            </ol>
        </nav>
        <h1 class="page-title">Kamera CCTV WiFi</h1>
        <p class="page-description">
            Kamera WiFi memungkinkan Anda memantau area yang dipilih secara langsung melalui 
            smartphone, tablet, atau komputer yang terhubung ke jaringan yang sama.
        </p>
    </div>
</div>

<div class="container">
    <!-- Filter Section -->
    <div class="filter-section">
        <h3 class="filter-title">Filter by Brand</h3>
        <div class="filter-buttons" id="brandFilters">
            <button class="filter-btn active" data-brand="" onclick="filterByBrand('', event)">
                Semua Brand
            </button>
            <button class="filter-btn" data-brand="HIKVISION" onclick="filterByBrand('HIKVISION', event)">
                HIKVISION
            </button>
            <button class="filter-btn" data-brand="Dahua" onclick="filterByBrand('Dahua', event)">
                Dahua
            </button>
            <button class="filter-btn" data-brand="HiLook" onclick="filterByBrand('HiLook', event)">
                HiLook
            </button>
            <button class="filter-btn" data-brand="EZVIZ" onclick="filterByBrand('EZVIZ', event)">
                EZVIZ
            </button>
            <button class="filter-btn" data-brand="UNV" onclick="filterByBrand('UNV', event)">
                UNV
            </button>
            <button class="filter-btn" data-brand="RUIJIE" onclick="filterByBrand('RUIJIE', event)">
                RUIJIE
            </button>
            <button class="filter-btn" data-brand="HIVIEW" onclick="filterByBrand('HIVIEW', event)">
                HIVIEW
            </button>
            <button class="filter-btn" data-brand="IMOU" onclick="filterByBrand('IMOU', event)">
                IMOU
            </button>
            <button class="filter-btn" data-brand="TP-LINK" onclick="filterByBrand('TP-LINK', event)">
                TP-LINK
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="loading-state">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Loading products...</p>
    </div>

    <!-- Products Grid -->
    <div class="row g-4 mb-5" id="productsGrid" style="display: none;">
        <!-- Products will be loaded here via JavaScript -->
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-size: 2rem; font-weight: 700;">Keunggulan WiFi Camera</h2>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3 class="feature-title">Remote Monitoring</h3>
                        <p class="feature-description">Pantau dari mana saja melalui smartphone Anda</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">🔔</div>
                        <h3 class="feature-title">Smart Alerts</h3>
                        <p class="feature-description">Notifikasi real-time saat terdeteksi gerakan</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">☁️</div>
                        <h3 class="feature-title">Cloud Storage</h3>
                        <p class="feature-description">Simpan rekaman di cloud dengan aman</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">⚙️</div>
                        <h3 class="feature-title">Easy Setup</h3>
                        <p class="feature-description">Instalasi mudah tanpa kabel rumit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allCameras = [];
let currentBrand = '';

// Load cameras on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCameras();
});

function loadCameras() {
    showLoading();
    
    fetch('/api/wifi-cameras', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            const text = await response.text();
            console.error('Got HTML instead of JSON:', text.substring(0, 500));
            throw new Error('Server returned HTML instead of JSON. Check if API route exists.');
        }
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            allCameras = data.cameras || [];
            renderProducts();
        } else {
            showError('Failed to load products: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error loading cameras:', error);
        showError('Error loading products: ' + error.message);
    });
}

function renderProducts() {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = '';
    
    // Filter products by brand only
    let filteredCameras = currentBrand 
        ? allCameras.filter(c => c.brand === currentBrand)
        : allCameras;
    
    if (filteredCameras.length === 0) {
        grid.innerHTML = `
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-camera-video"></i>
                    <h3>Tidak ada produk</h3>
                    <p class="text-muted">Coba pilih brand lain atau kembali ke semua brand</p>
                </div>
            </div>
        `;
        return;
    }
    
    filteredCameras.forEach(camera => {
        const col = document.createElement('div');
        col.className = 'col-lg-3 col-md-6 product-item';
        col.dataset.brand = camera.brand;
        
        // Check if on sale
        const onSale = camera.original_price && camera.original_price > camera.price;
        
        // Parse specifications
        let specsHtml = '';
        if (camera.specifications) {
            try {
                const specs = JSON.parse(camera.specifications);
                specsHtml = specs.slice(0, 3).map(spec => 
                    `<li><i class="bi bi-check-circle-fill"></i>${spec}</li>`
                ).join('');
            } catch (e) {
                console.error('Error parsing specs:', e);
            }
        }
        
        col.innerHTML = `
            <div class="wifi-camera-card">
                <div class="camera-image">
                    ${onSale ? '<span class="badge-sale">SALE</span>' : ''}
                    ${camera.is_featured ? '<span class="badge-featured">⭐ Featured</span>' : ''}
                    <img src="${camera.main_image ? '/storage/' + camera.main_image : 'https://via.placeholder.com/200x200/ffffff/666?text=No+Image'}" 
                         alt="${camera.name}"
                         onerror="this.src='https://via.placeholder.com/200x200/ffffff/666?text=No+Image'">
                </div>
                
                <div class="camera-content">
                    <div class="camera-brand">${camera.brand}</div>
                    <h3 class="camera-name">${camera.name}</h3>
                    
                    ${specsHtml ? `<ul class="camera-specs">${specsHtml}</ul>` : 
                      `<p class="text-muted small">${camera.subtitle || ''}</p>`
                    }
                    
                    <div class="camera-price">
                        <span class="price-current">Rp ${formatPrice(camera.price)}</span>
                        ${onSale ? `<span class="price-original">Rp ${formatPrice(camera.original_price)}</span>` : ''}
                    </div>
                </div>
                
                <div class="camera-footer">
                    <button class="btn btn-detail" onclick="showDetail(${camera.id})">
                        <i class="bi bi-eye me-1"></i> Detail
                    </button>
                    <button class="btn btn-buy" ${camera.stock === 0 ? 'disabled' : ''} onclick="buyProduct(${camera.id})">
                        <i class="bi bi-cart-plus me-1"></i> ${camera.stock === 0 ? 'Habis' : 'Beli'}
                    </button>
                </div>
            </div>
        `;
        
        grid.appendChild(col);
    });
}

function filterByBrand(brand, event) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    currentBrand = brand;
    renderProducts();
    
    // Smooth scroll animation
    const grid = document.getElementById('productsGrid');
    grid.style.opacity = '0';
    setTimeout(() => {
        grid.style.transition = 'opacity 0.3s ease';
        grid.style.opacity = '1';
    }, 10);
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('id-ID');
}

function showDetail(id) {
    // Redirect to detail page with ID
    window.location.href = `/wifi-cam/detail/${id}`;
}

function buyProduct(id) {
    alert('Fitur pembelian akan segera hadir!\nProduct ID: ' + id);
}

function showLoading() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('productsGrid').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingState').style.display = 'none';
    document.getElementById('productsGrid').style.display = 'flex';
}

function showError(message) {
    const grid = document.getElementById('productsGrid');
    grid.style.display = 'flex';
    grid.innerHTML = `
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-exclamation-triangle text-danger"></i>
                <h3>Error</h3>
                <p class="text-muted">${message}</p>
                <button class="btn btn-primary mt-3" onclick="loadCameras()">
                    <i class="bi bi-arrow-clockwise"></i> Try Again
                </button>
            </div>
        </div>
    `;
}
</script>
@endsection