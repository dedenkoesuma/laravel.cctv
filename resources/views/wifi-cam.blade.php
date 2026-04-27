@extends('layouts.simple')

@section('title', 'WiFi Camera - TechStore')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* ===== UI RUIJIE ADAPTATION ===== */
:root {
    --brand-primary: #667eea;
    --brand-light: #f0f3ff;
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-header {
    background-image: linear-gradient(rgba(0, 20, 40, 0.6), rgba(0, 40, 70, 0.7)), url("{{ asset('storage/gambar/wifi-cam.png') }}");
    color: white;
    padding: 80px 0 120px;
    background-size: cover;
    background-position: center;
    text-align: center;
}

.filter-section {
    position: relative;
    margin-top: -60px;
    margin-bottom: 60px;
    z-index: 10;
}

.filter-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    max-width: 1100px;
    margin: 0 auto;
}

.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.filter-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; }

/* SEARCH BAR */
.search-wrapper { margin-bottom: 20px; }
.search-input-group {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 4px 16px;
    transition: var(--transition);
}
.search-input-group:focus-within {
    border-color: var(--brand-primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}
.search-input-group input {
    flex: 1; border: none; background: transparent; padding: 10px; outline: none;
}

/* FILTER GRID */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}
.filter-btn {
    padding: 14px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: white;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.filter-btn.active {
    background: var(--brand-primary);
    border-color: var(--brand-primary);
    color: white;
}

/* PRODUCT CARD (Keep your existing card structure but cleaner) */
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 28px; }
.wifi-camera-card {
    background: white; border-radius: 16px; overflow: hidden;
    border: 1px solid #e2e8f0; transition: var(--transition);
}
.wifi-camera-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); }
.camera-image { height: 250px; background: #f8fafc; display: flex; align-items: center; justify-content: center; }
.camera-image img { width: 100%; height: 100%; object-fit: cover; }
.camera-content { padding: 20px; }
</style>

<div class="page-header">
    <div class="container">
        <h1 style="font-size: 3rem; font-weight: 800;">WiFi Camera</h1>
        <p>Solusi pemantauan cerdas berbasis nirkabel.</p>
    </div>
</div>

<div class="container filter-section">
    <div class="filter-card">
        <div class="filter-header">
            <h2 class="filter-title">Filter Produk</h2>
            <div id="filterCount" class="text-muted small">Menampilkan <strong>0</strong> produk</div>
        </div>

        <div class="search-wrapper">
            <div class="search-input-group">
                <i class="bi bi-search"></i>
                <input type="text" id="productSearch" placeholder="Cari tipe atau nama kamera..." onkeyup="handleSearch()">
            </div>
        </div>

        <div class="filter-grid" id="brandFilters">
            <button class="filter-btn active" data-brand="Dahua" onclick="filterByBrand('Dahua', event)">Dahua</button>
            <button class="filter-btn" data-brand="EZVIZ" onclick="filterByBrand('EZVIZ', event)">EZVIZ</button>
            <button class="filter-btn" data-brand="HIVIEW" onclick="filterByBrand('HIVIEW', event)">HIVIEW</button>
            <button class="filter-btn" data-brand="IMOU" onclick="filterByBrand('IMOU', event)">IMOU</button>
            <button class="filter-btn" data-brand="TP-LINK" onclick="filterByBrand('TP-LINK', event)">TP-LINK</button>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
    </div>
    <div class="products-grid" id="productsGrid" style="display: none;"></div>
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
    });
}

function handleSearch() {
    searchQuery = document.getElementById('productSearch').value.toLowerCase();
    renderProducts();
}

function filterByBrand(brand, event) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
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
        grid.innerHTML = `<div class="text-center py-5" style="grid-column: 1/-1;"><h3>Produk tidak ditemukan</h3></div>`;
        return;
    }

    filtered.forEach(camera => {
        const onSale = camera.original_price && camera.original_price > camera.price;
        const col = document.createElement('div');
        col.innerHTML = `
            <div class="wifi-camera-card">
                <div class="camera-image">
                    <img src="${camera.main_image ? '/storage/' + camera.main_image : 'https://via.placeholder.com/300'}" alt="${camera.name}">
                </div>
                <div class="camera-content">
                    <div style="color:var(--brand-primary); font-size: 0.7rem; font-weight: 800;">${camera.brand}</div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin: 10px 0;">${camera.name}</h3>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #dc3545;">Rp ${parseInt(camera.price).toLocaleString('id-ID')}</div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-outline-primary w-100" onclick="window.location.href='/wifi-cam/${camera.slug}'">Detail</button>
                        <button class="btn btn-primary w-100" onclick="buyProduct(${camera.id})">Beli</button>
                    </div>
                </div>
            </div>`;
        grid.appendChild(col);
    });
}

function buyProduct(id) {
    const p = allCameras.find(c => c.id === id);
    const msg = encodeURIComponent(`Halo, saya tertarik: *${p.name}*`);
    window.open(`https://wa.me/6281234567890?text=${msg}`, '_blank');
}
</script>
@endsection