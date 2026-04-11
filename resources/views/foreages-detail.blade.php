@extends('layouts.simple')

@section('title', 'Detail Produk Foreage - TechStore')

@section('content')

@php
    // --- NORMALISASI DATA ANTI-ERROR ---
    // Memastikan semua data terbaca dengan benar dari tabel static_products
    $p = (object) $product; 
    
    $name = $p->nama_produk ?? $p->product_name ?? $p->name ?? 'Produk Foreage';
    $price = $p->harga_jual ?? $p->price ?? $p->harga ?? 0;
    $image = $p->gambar ?? $p->main_image ?? '';
    $desc = $p->deskripsi ?? $p->description ?? '';
    $cat = $p->tab_category ?? $p->kategori ?? $p->category ?? 'Foreage';
    $sku = $p->sku ?? '';
    
    $galleryRaw = $p->gallery ?? $p->gallery_images ?? '[]';
    $gallery = is_string($galleryRaw) ? json_decode($galleryRaw, true) : $galleryRaw;
    if(!is_array($gallery)) $gallery = [];
    
    $specsRaw = $p->specifications ?? '[]';
    $specs = is_string($specsRaw) ? json_decode($specsRaw, true) : $specsRaw;
    if(!is_array($specs)) $specs = [];
    
    $includesRaw = $p->package_includes ?? $p->features ?? '[]';
    $includes = is_string($includesRaw) ? json_decode($includesRaw, true) : $includesRaw;
    if(!is_array($includes)) $includes = [];
@endphp

<style>
/* ===== DETAIL PAGE STYLES (FOREAGE THEME) ===== */
:root {
    --foreage-primary: #FF6B35;
    --foreage-dark: #F7931E;
    --foreage-light: #FFF5F2;
}

.detail-container {
    padding: 40px 0;
    background: #f8fafc;
    min-height: calc(100vh - 100px);
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    color: var(--foreage-primary);
    border: 2px solid var(--foreage-primary);
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.back-button:hover {
    background: var(--foreage-primary);
    color: white;
    transform: translateX(-5px);
}

.product-detail-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* ===== PRODUCT IMAGES ===== */
.product-images {
    padding: 40px;
    background: #f8fafc;
    height: 100%;
}

.main-image {
    background: white;
    border-radius: 12px;
    padding: 40px;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.main-image img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
}

.thumbnail-images {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.thumbnail:hover, .thumbnail.active {
    border-color: var(--foreage-primary);
    transform: scale(1.05);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}

/* ===== PRODUCT INFO ===== */
.product-info {
    padding: 40px;
}

.resolution-badges {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.badge-resolution {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-resolution.active {
    background: linear-gradient(135deg, var(--foreage-primary) 0%, var(--foreage-dark) 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
}

.badge-resolution.inactive {
    background: var(--foreage-light);
    color: var(--foreage-primary);
}

.product-title {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
    line-height: 1.2;
}

.product-subtitle {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--foreage-primary);
    margin-bottom: 20px;
    letter-spacing: 0.5px;
}

.product-desc {
    color: #475569;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 30px;
}

/* ===== SPECIFICATIONS ===== */
.section-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--foreage-primary);
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.spec-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.spec-item i {
    color: var(--foreage-primary);
    font-size: 1.2rem;
    margin-top: 2px;
}

.spec-text {
    color: #333;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* ===== PACKAGE INCLUDES ===== */
.package-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.package-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: var(--foreage-light);
    border-radius: 8px;
    border: 1px solid rgba(255, 107, 53, 0.1);
}

.package-item i {
    color: var(--foreage-primary);
    font-size: 1.2rem;
}

.package-text {
    color: #333;
    font-size: 0.95rem;
    font-weight: 500;
}

/* ===== PRICE & ORDER ===== */
.price-section {
    background: linear-gradient(135deg, var(--foreage-primary) 0%, var(--foreage-dark) 100%);
    padding: 30px;
    border-radius: 16px;
    color: white;
    margin-top: 40px;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.25);
}

.price-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 5px;
    font-weight: 500;
}

.price-value {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1;
}

.btn-order {
    background: white;
    color: var(--foreage-primary);
    border: none;
    padding: 16px 40px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 800;
    width: 100%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-order:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    background: #f8fafc;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 767px) {
    .product-images { padding: 20px; }
    .product-info { padding: 20px; }
    .product-title { font-size: 1.5rem; }
    .specs-grid { grid-template-columns: 1fr; }
    .price-value { font-size: 2rem; }
}
</style>

<div class="detail-container">
    <div class="container">
        <a href="{{ url('/products/foreage') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Produk Foreage
        </a>

        <div class="product-detail-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="product-images">
                        <div class="main-image">
                            <img src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/400x400/FF6B35/ffffff?text=Foreage' }}" alt="{{ $name }}" id="mainImage">
                        </div>

                        @if(!empty($gallery))
                        <div class="thumbnail-images">
                            @foreach($gallery as $index => $img)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('storage/' . $img) }}', this)">
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $name }} view {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="product-info">
                        
                        <div class="resolution-badges">
                            <span class="badge-resolution active">
                                FOREAGE
                            </span>
                            @if(!empty($cat))
                            <span class="badge-resolution inactive">
                                {{ ucfirst($cat) }}
                            </span>
                            @endif
                        </div>

                        <h1 class="product-title">{{ $name }}</h1>
                        @if(!empty($sku))
                            <p class="product-subtitle"><i class="bi bi-upc-scan"></i> SKU: {{ $sku }}</p>
                        @endif
                        
                        @if(!empty($desc))
                        <div class="product-desc">
                            {{ $desc }}
                        </div>
                        @endif

                        @if(!empty($specs))
                        <h2 class="section-title">Spesifikasi Detail</h2>
                        <div class="specs-grid">
                            @foreach($specs as $key => $val)
                            <div class="spec-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span class="spec-text">
                                    @if(is_string($key))
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                    @endif
                                    {{ $val }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if(!empty($includes))
                        <h2 class="section-title">Kelengkapan / Sudah Termasuk</h2>
                        <div class="package-list">
                            @foreach($includes as $item)
                            <div class="package-item">
                                <i class="bi bi-box-seam"></i>
                                <span class="package-text">{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="price-section">
                            <div class="price-label">Harga Spesial:</div>
                            <div class="price-value">IDR. {{ number_format(is_numeric($price) ? $price : (float)str_replace(',', '', $price), 0, ',', '.') }}</div>
                            
                            <button class="btn btn-order" onclick="orderNow()">
                                <i class="bi bi-whatsapp fs-5"></i>
                                Pesan Sekarang via WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

function orderNow() {
    const productName = '{{ addslashes($name) }}';
    const price = 'IDR. {{ number_format(is_numeric($price) ? $price : (float)str_replace(',', '', $price), 0, ',', '.') }}';
    
    const message = `Halo TechStore, saya tertarik dengan produk Foreage:\n*${productName}*\nHarga: ${price}\n\nApakah barang ini ready stock?`;
    
    window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(message), '_blank');
}
</script>
@endsection