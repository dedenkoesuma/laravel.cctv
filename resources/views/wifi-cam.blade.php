@extends('layouts.simple')

@section('title', 'Katalog Kamera CCTV WiFi - PT. MJA TEKNOLOGI')
@section('meta_description', 'Katalog lengkap kamera CCTV WiFi nirkabel Dahua, EZVIZ, IMOU, HIVIEW, dan TP-Link bergaransi resmi 2 tahun dengan setting online smartphone gratis.')

@section('content')
<style>
/* ===== CORPORATE WIFI CAM PAGE ===== */
.wifi-hero-corp {
    background-color: #0f172a;
    color: white;
    padding: 70px 20px 85px;
    text-align: center;
    border-bottom: 1px solid var(--ts-slate-800);
}

.wifi-hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.wifi-corp-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fca5a5;
    font-size: 12.5px;
    font-weight: 600;
    padding: 5px 16px;
    border-radius: 4px;
    margin-bottom: 16px;
}

.wifi-hero-corp h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.2rem);
    font-weight: 800;
    margin-bottom: 12px;
    color: #ffffff;
    line-height: 1.2;
}

.wifi-hero-corp h1 span {
    color: #f87171;
}

.wifi-hero-corp p {
    font-size: clamp(0.95rem, 1.6vw, 1.1rem);
    color: #cbd5e1;
    line-height: 1.6;
    margin: 0 auto;
}

/* Filter Card */
.filter-section-corp {
    position: relative;
    margin-top: -45px;
    margin-bottom: 40px;
    z-index: 10;
}

.filter-card-corp {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    max-width: 1100px;
    margin: 0 auto;
    border: 1px solid var(--ts-slate-200);
}

.filter-header-corp {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-title-corp {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Search Bar */
.search-wrapper-corp {
    margin-bottom: 16px;
}

.search-input-corp {
    display: flex;
    align-items: center;
    background: var(--ts-slate-50);
    border: 1px solid var(--ts-slate-200);
    border-radius: 8px;
    padding: 4px 16px;
    transition: border-color 0.2s ease;
}

.search-input-corp:focus-within {
    border-color: var(--ts-slate-800);
    background: white;
}

.search-input-corp i {
    color: var(--ts-slate-500);
    font-size: 15px;
    margin-right: 10px;
}

.search-input-corp input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 9px 0;
    outline: none;
    font-size: 14px;
    color: var(--ts-slate-800);
}

/* Filter Grid */
.filter-grid-corp {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-btn-corp {
    padding: 8px 18px;
    border-radius: 6px;
    border: 1px solid var(--ts-slate-200);
    background: #ffffff;
    color: var(--ts-slate-700);
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}

.filter-btn-corp:hover {
    border-color: var(--ts-slate-700);
    color: var(--ts-slate-900);
    background-color: var(--ts-slate-100);
}

.filter-btn-corp.active {
    background-color: var(--ts-slate-900);
    border-color: var(--ts-slate-900);
    color: white;
}

/* Products Grid */
.products-grid-corp {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 24px;
}

.wifi-card-corp {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--ts-slate-200);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.wifi-card-corp:hover {
    border-color: var(--ts-slate-700);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    transform: translateY(-3px);
}

.wifi-img-corp {
    height: 220px;
    background: var(--ts-slate-50);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    border-bottom: 1px solid var(--ts-slate-100);
}

.wifi-img-corp img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.wifi-brand-pill {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    font-size: 11px;
    font-weight: 700;
    color: var(--ts-slate-700);
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}

.wifi-body-corp {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.wifi-title-corp {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 6px;
    line-height: 1.35;
    min-height: 42px;
}

.wifi-price-corp {
    font-size: 18px;
    font-weight: 800;
    color: var(--ts-primary);
    margin-bottom: 16px;
}

.btn-detail-corp {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: var(--ts-slate-100);
    color: var(--ts-slate-700);
    font-size: 13px;
    font-weight: 600;
    padding: 9px 12px;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    transition: all 0.15s ease;
}

.btn-detail-corp:hover {
    background-color: var(--ts-slate-200);
    color: var(--ts-slate-900);
}

.btn-buy-corp {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: var(--ts-green);
    color: white;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-buy-corp:hover {
    background-color: var(--ts-green-dark);
    color: white;
}
</style>

<div class="wifi-hero-corp">
    <div class="wifi-hero-content container">
        <div class="wifi-corp-badge">
            <i class="bi bi-broadcast me-1"></i> Smart Wireless Camera System
        </div>
        <h1>Kamera CCTV <span>WiFi Praktis</span></h1>
        <p>Solusi pemantauan nirkabel langsung via smartphone dengan fitur rotasi pintar, komunikasi suara dua arah, serta rekaman tajam siang dan malam.</p>
    </div>
</div>

<div class="container filter-section-corp">
    <div class="filter-card-corp">
        <div class="filter-header-corp">
            <h2 class="filter-title-corp">
                <i class="bi bi-funnel-fill text-danger"></i> Filter & Cari Perangkat
            </h2>
            <div id="filterCount" class="text-muted small fw-semibold">Menampilkan <strong>0</strong> produk</div>
        </div>

        <div class="search-wrapper-corp">
            <div class="search-input-corp">
                <i class="bi bi-search"></i>
                <input type="text" id="productSearch" placeholder="Cari tipe, resolusi, atau seri kamera..." onkeyup="handleSearch()">
            </div>
        </div>

        <div class="filter-grid-corp" id="brandFilters">
            <button class="filter-btn-corp active" data-brand="Dahua" onclick="filterByBrand('Dahua', event)">Dahua</button>
            <button class="filter-btn-corp" data-brand="EZVIZ" onclick="filterByBrand('EZVIZ', event)">EZVIZ</button>
            <button class="filter-btn-corp" data-brand="HIVIEW" onclick="filterByBrand('HIVIEW', event)">HIVIEW</button>
            <button class="filter-btn-corp" data-brand="IMOU" onclick="filterByBrand('IMOU', event)">IMOU</button>
            <button class="filter-btn-corp" data-brand="TP-LINK" onclick="filterByBrand('TP-LINK', event)">TP-LINK</button>
        </div>
    </div>
</div>

<div class="container pb-5 mb-4">
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-danger" role="status"></div>
        <p class="mt-2 text-muted fw-semibold">Memuat katalog WiFi Camera...</p>
    </div>
    <div class="products-grid-corp" id="productsGrid" style="display: none;"></div>
</div>

<script>
let allCameras = [];
let currentBrand = 'Dahua';
let searchQuery = '';

document.addEventListener('DOMContentLoaded', loadCameras);

function loadCameras() {
    fetch('/api/wifi-cameras').then(res => res.json()).then(data => {
        document.getElementById('loadingState').style.display = 'none';
        if (data.success) {
            allCameras = data.cameras || [];
            renderProducts();
        }
    }).catch(err => {
        document.getElementById('loadingState').innerHTML = '<p class="text-danger fw-semibold">Gagal memuat produk. Silakan coba lagi.</p>';
    });
}

function handleSearch() {
    searchQuery = document.getElementById('productSearch').value.toLowerCase();
    renderProducts();
}

function filterByBrand(brand, event) {
    document.querySelectorAll('.filter-btn-corp').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
    currentBrand = brand;
    renderProducts();
}

function renderProducts() {
    const grid = document.getElementById('productsGrid');
    grid.style.display = 'grid';
    grid.innerHTML = '';
    
    const filtered = allCameras.filter(c => {
        const matchesBrand = c.brand.toUpperCase() === currentBrand.toUpperCase();
        const matchesSearch = c.name.toLowerCase().includes(searchQuery);
        return matchesBrand && matchesSearch;
    });

    document.getElementById('filterCount').innerHTML = `Menampilkan <strong>${filtered.length}</strong> produk`;

    if (filtered.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-5" style="grid-column: 1/-1;">
                <i class="bi bi-search text-muted" style="font-size: 2.8rem;"></i>
                <h4 class="mt-3 fw-bold text-dark">Produk Tidak Ditemukan</h4>
                <p class="text-muted small">Silakan coba kata kunci lain atau pilih brand kamera yang berbeda.</p>
            </div>`;
        return;
    }

    filtered.forEach(camera => {
        const col = document.createElement('div');
        col.className = 'wifi-card-corp';
        col.innerHTML = `
            <div class="wifi-img-corp">
                <span class="wifi-brand-pill">${camera.brand}</span>
                <img src="${camera.main_image ? '/storage/' + camera.main_image : 'https://via.placeholder.com/300'}" 
                     alt="${camera.name}"
                     onerror="this.src='https://via.placeholder.com/300?text=WiFi+Camera'">
            </div>
            <div class="wifi-body-corp">
                <div class="wifi-title-corp">${camera.name}</div>
                <div class="wifi-price-corp">Rp ${parseInt(camera.price).toLocaleString('id-ID')}</div>
                <div class="d-flex gap-2 mt-auto">
                    <button class="btn-detail-corp" onclick="window.location.href='/wifi-cam/${camera.slug}'">
                        <i class="bi bi-info-circle"></i> Detail
                    </button>
                    <button class="btn-buy-corp" onclick="buyProduct(${camera.id})">
                        <i class="bi bi-whatsapp"></i> Order WA
                    </button>
                </div>
            </div>`;
        grid.appendChild(col);
    });
}

function buyProduct(id) {
    const p = allCameras.find(c => c.id === id);
    if (!p) return;
    const msg = encodeURIComponent(`Halo TechStore, saya berminat memesan kamera CCTV WiFi: *${p.name}* seharga Rp ${parseInt(p.price).toLocaleString('id-ID')}`);
    window.open(`https://wa.me/62881025756671?text=${msg}`, '_blank');
}
</script>
@endsection