@extends('layouts.simple')

@section('title', 'Access Control - TechStore')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* ===== UI RUIJIE STYLE ADAPTATION ===== */
:root {
    --access-primary: #ff6b6b;
    --access-dark: #ee5a6f;
    --access-light: #fff5f5;
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== HERO SECTION ===== */
.hero-section {
    position: relative;
    background-image: url("{{ asset('storage/gambar/access-control.jpeg') }}");
    color: white;
    padding: 100px 0 140px;
    background-size: cover;
    background-position: center;
    text-align: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.6);
}

.hero-content { position: relative; z-index: 1; }

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

/* ===== FILTER SECTION (Floating Card) ===== */
.filter-section {
    position: relative;
    margin-top: -70px;
    margin-bottom: 60px;
    z-index: 10;
}

.filter-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    box-shadow: var(--shadow-lg);
    max-width: 1000px;
    margin: 0 auto;
    border: 1px solid rgba(0,0,0,0.05);
}

.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.filter-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; }
.filter-count { font-size: 0.875rem; color: #64748b; }
.filter-count strong { color: var(--access-primary); }

/* SEARCH BAR */
.search-wrapper { margin-bottom: 24px; }
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
    border-color: var(--access-primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
}

.search-input-group input {
    flex: 1; border: none; background: transparent; padding: 10px 0;
    font-size: 0.95rem; outline: none;
}

/* FILTER GRID */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.filter-btn {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; padding: 14px; border-radius: 12px;
    border: 2px solid #e2e8f0; background: white;
    color: #475569; font-weight: 700; font-size: 0.9rem;
    cursor: pointer; transition: var(--transition);
}

.filter-btn:hover { border-color: var(--access-primary); color: var(--access-primary); }

.filter-btn.active {
    background: var(--access-primary);
    border-color: var(--access-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.25);
}

/* ===== PRODUCTS GRID ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 28px;
    padding-bottom: 80px;
}

.product-card {
    background: white; border-radius: 16px; overflow: hidden;
    border: 1px solid #e2e8f0; transition: var(--transition);
    display: flex; flex-direction: column;
}

.product-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); }

.product-image-wrapper {
    height: 260px; background: #f8fafc;
    display: flex; align-items: center; justify-content: center;
    position: relative;
}

.product-image { width: 100%; height: 100%; object-fit: cover; }

.product-content { padding: 24px; flex: 1; display: flex; flex-direction: column; }
.product-brand { font-size: 0.75rem; font-weight: 800; color: var(--access-primary); text-transform: uppercase; margin-bottom: 8px; }
.product-name { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 12px; min-height: 50px; }

.price-current { font-size: 1.5rem; font-weight: 800; color: var(--access-primary); }
.product-footer { margin-top: auto; padding-top: 15px; display: flex; gap: 10px; }

.btn-detail { flex: 1; padding: 10px; border-radius: 10px; border: 2px solid var(--access-primary); color: var(--access-primary); background: white; font-weight: 700; }
.btn-buy { flex: 1; padding: 10px; border-radius: 10px; background: var(--access-primary); color: white; border: none; font-weight: 700; }
</style>

<div class="hero-section">
    <div class="hero-content container">
        <h1 class="hero-title">Access Control Systems</h1>
        <p class="hero-subtitle">Keamanan akses cerdas dengan teknologi biometric terdepan.</p>
    </div>
</div>

<div class="container filter-section">
    <div class="filter-card">
        <div class="filter-header">
            <h2 class="filter-title">Filter Produk</h2>
            <div class="filter-count" id="filterCount">Menampilkan <strong>0</strong> produk</div>
        </div>

        <div class="search-wrapper">
            <div class="search-input-group">
                <i class="bi bi-search"></i>
                <input type="text" id="productSearch" placeholder="Cari tipe atau nama access control..." onkeyup="handleSearch()">
            </div>
        </div>

        <div class="filter-grid" id="brandFilters">
            <button class="filter-btn active" data-brand="HIKVISION" onclick="filterByBrand('HIKVISION', event)">HIKVISION</button>
            <button class="filter-btn" data-brand="Dahua" onclick="filterByBrand('Dahua', event)">Dahua</button>
            <button class="filter-btn" data-brand="ZKTeco" onclick="filterByBrand('ZKTeco', event)">ZKTeco</button>
        </div>
    </div>
</div>

<div class="container">
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-danger" role="status"></div>
        <p class="mt-2 text-muted">Memuat produk...</p>
    </div>

    <div class="products-grid" id="productsGrid" style="display: none;"></div>
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
        document.getElementById('loadingState').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
    }
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
    
    const filtered = allProducts.filter(p => {
        const matchesBrand = p.brand.toUpperCase() === currentBrand.toUpperCase();
        const matchesSearch = p.name.toLowerCase().includes(searchQuery) || 
                             (p.sku && p.sku.toLowerCase().includes(searchQuery));
        return matchesBrand && matchesSearch;
    });

    document.getElementById('filterCount').innerHTML = `Menampilkan <strong>${filtered.length}</strong> produk`;

    if (filtered.length === 0) {
        grid.innerHTML = `<div class="text-center py-5" style="grid-column: 1/-1;">
            <i class="bi bi-search" style="font-size: 3rem; color: #ddd;"></i>
            <h3 class="mt-3">Produk tidak ditemukan</h3>
        </div>`;
        return;
    }

    filtered.forEach(p => {
        const hasDiscount = p.original_price && p.original_price > p.sell_price;
        const col = document.createElement('div');
        col.className = 'product-card';
        col.innerHTML = `
            <div class="product-image-wrapper">
                <img src="${p.main_image ? '/storage/' + p.main_image : 'https://via.placeholder.com/300'}" 
                     class="product-image" alt="${p.name}" onerror="this.src='https://via.placeholder.com/300'">
            </div>
            <div class="product-content">
                <div class="product-brand">${p.brand}</div>
                <h3 class="product-name">${p.name}</h3>
                <div class="product-price-wrapper">
                    <span class="price-current">Rp ${parseInt(p.sell_price).toLocaleString('id-ID')}</span>
                </div>
                <div class="product-footer">
                    <button class="btn-detail" onclick="window.location.href='/access-control/${p.id}'">Detail</button>
                    <button class="btn-buy" ${p.stock === 0 ? 'disabled' : ''} onclick="buyProduct('${p.name}')">
                        ${p.stock === 0 ? 'Habis' : 'Beli'}
                    </button>
                </div>
            </div>`;
        grid.appendChild(col);
    });
}

function buyProduct(name) {
    const msg = encodeURIComponent(`Halo Admin TechStore, saya tertarik dengan produk Access Control: *${name}*`);
    window.open(`https://wa.me/6281234567890?text=${msg}`, '_blank');
}
</script>
@endsection