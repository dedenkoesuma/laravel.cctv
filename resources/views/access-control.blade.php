@extends('layouts.simple')

@section('title', 'Katalog Akses Kontrol & Mesin Absensi - PT. MJA TEKNOLOGI')
@section('meta_description', 'Katalog produk Access Control, Mesin Absensi Fingerprint & Face Recognition dari HIKVISION, Dahua, dan ZKTeco bergaransi resmi.')

@section('content')
<style>
/* ===== ACCESS CONTROL CORPORATE STYLES ===== */
.access-hero-corp {
    background-color: #0f172a;
    color: white;
    padding: 70px 20px 85px;
    text-align: center;
    border-bottom: 1px solid var(--ts-slate-800);
}

.access-hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.access-corp-badge {
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

.access-hero-corp h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.2rem);
    font-weight: 800;
    margin-bottom: 12px;
    color: #ffffff;
    line-height: 1.2;
}

.access-hero-corp h1 span {
    color: #f87171;
}

.access-hero-corp p {
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
    max-width: 1000px;
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
    padding: 8px 20px;
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

.access-card-corp {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--ts-slate-200);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.access-card-corp:hover {
    border-color: var(--ts-slate-700);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    transform: translateY(-3px);
}

.access-img-corp {
    height: 220px;
    background: var(--ts-slate-50);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    border-bottom: 1px solid var(--ts-slate-100);
}

.access-img-corp img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.access-brand-pill {
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

.access-body-corp {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.access-title-corp {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 6px;
    line-height: 1.35;
    min-height: 42px;
}

.access-price-corp {
    font-size: 18px;
    font-weight: 800;
    color: var(--ts-primary);
    margin-bottom: 16px;
    display: block;
}

.access-footer-corp {
    margin-top: auto;
    display: flex;
    gap: 8px;
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
    border: none;
    cursor: pointer;
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
}

.btn-buy-corp:disabled {
    background-color: var(--ts-slate-200);
    color: var(--ts-slate-500);
    cursor: not-allowed;
}
</style>

<div class="access-hero-corp">
    <div class="access-hero-content container">
        <div class="access-corp-badge">
            <i class="bi bi-fingerprint me-1"></i> Biometric & RFID Security Systems
        </div>
        <h1>Sistem <span>Akses Kontrol & Absensi</span></h1>
        <p>Tingkatkan standar perlindungan pintu masuk ruangan dan fasilitas gedung Anda dengan perangkat sidik jari, pengenalan wajah, & smart RFID terdepan.</p>
    </div>
</div>

<div class="container filter-section-corp">
    <div class="filter-card-corp">
        <div class="filter-header-corp">
            <h2 class="filter-title-corp">
                <i class="bi bi-funnel-fill text-danger"></i> Filter & Cari Akses Kontrol
            </h2>
            <div class="filter-count text-muted small fw-semibold" id="filterCount">Menampilkan <strong>0</strong> produk</div>
        </div>

        <div class="search-wrapper-corp">
            <div class="search-input-corp">
                <i class="bi bi-search"></i>
                <input type="text" id="productSearch" placeholder="Cari tipe, nomor model, atau nama perangkat..." onkeyup="handleSearch()">
            </div>
        </div>

        <div class="filter-grid-corp" id="brandFilters">
            <button class="filter-btn-corp active" data-brand="HIKVISION" onclick="filterByBrand('HIKVISION', event)">HIKVISION</button>
            <button class="filter-btn-corp" data-brand="Dahua" onclick="filterByBrand('Dahua', event)">Dahua</button>
            <button class="filter-btn-corp" data-brand="ZKTeco" onclick="filterByBrand('ZKTeco', event)">ZKTeco</button>
        </div>
    </div>
</div>

<div class="container pb-5 mb-4">
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-danger" role="status"></div>
        <p class="mt-2 text-muted fw-semibold">Memuat perangkat Akses Kontrol...</p>
    </div>

    <div class="products-grid-corp" id="productsGrid" style="display: none;"></div>
</div>

<script>
let allProducts = [];
let currentBrand = 'HIKVISION';
let searchQuery = '';

document.addEventListener('DOMContentLoaded', loadProducts);

async function loadProducts() {
    try {
        const response = await fetch('/api/access-control');
        const data = await response.json();
        document.getElementById('loadingState').style.display = 'none';
        
        if (data.success) {
            allProducts = data.products || [];
            renderProducts();
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingState').innerHTML = '<p class="text-danger fw-semibold">Gagal memuat data. Silakan refresh halaman.</p>';
    }
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
    
    const filtered = allProducts.filter(p => {
        const matchesBrand = p.brand.toUpperCase() === currentBrand.toUpperCase();
        const matchesSearch = p.name.toLowerCase().includes(searchQuery) || 
                             (p.sku && p.sku.toLowerCase().includes(searchQuery));
        return matchesBrand && matchesSearch;
    });

    document.getElementById('filterCount').innerHTML = `Menampilkan <strong>${filtered.length}</strong> produk`;

    if (filtered.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-5" style="grid-column: 1/-1;">
                <i class="bi bi-search text-muted" style="font-size: 2.8rem;"></i>
                <h4 class="mt-3 fw-bold text-dark">Perangkat Tidak Ditemukan</h4>
                <p class="text-muted small">Silakan coba kata kunci lain atau pilih brand yang berbeda.</p>
            </div>`;
        return;
    }

    filtered.forEach(p => {
        const col = document.createElement('div');
        col.className = 'access-card-corp';
        col.innerHTML = `
            <div class="access-img-corp">
                <span class="access-brand-pill">${p.brand}</span>
                <img src="${p.main_image ? '/storage/' + p.main_image : 'https://via.placeholder.com/300'}" 
                     alt="${p.name}" 
                     onerror="this.src='https://via.placeholder.com/300?text=Access+Control'">
            </div>
            <div class="access-body-corp">
                <div class="access-title-corp">${p.name}</div>
                <span class="access-price-corp">Rp ${parseInt(p.sell_price).toLocaleString('id-ID')}</span>
                <div class="access-footer-corp">
                    <button class="btn-detail-corp" onclick="window.location.href='/access-control/${p.id}'">
                        <i class="bi bi-info-circle"></i> Detail
                    </button>
                    <button class="btn-buy-corp" ${p.stock === 0 ? 'disabled' : ''} onclick="buyProduct('${p.name}')">
                        <i class="bi bi-whatsapp"></i> ${p.stock === 0 ? 'Habis' : 'Pesan WA'}
                    </button>
                </div>
            </div>`;
        grid.appendChild(col);
    });
}

function buyProduct(name) {
    const msg = encodeURIComponent(`Halo TechStore, saya berminat memesan perangkat Access Control: *${name}*`);
    window.open(`https://wa.me/62881025756671?text=${msg}`, '_blank');
}
</script>
@endsection