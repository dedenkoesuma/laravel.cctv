@extends('layouts.simple')

@section('title', 'Detail Produk - TechStore')

@section('content')
<style>
/* ===== DETAIL PAGE STYLES ===== */
.detail-container {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: calc(100vh - 100px);
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.back-button:hover {
    color: #764ba2;
    transform: translateX(-5px);
}

.product-detail-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* ===== PRODUCT IMAGES ===== */
.product-images {
    padding: 40px;
    background: #f8f9fa;
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
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
}

.thumbnail:hover, .thumbnail.active {
    border-color: #667eea;
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

.brand-logo {
    max-width: 120px;
    margin-bottom: 20px;
}

.resolution-badges {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.badge-resolution {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-resolution.active {
    background: #dc3545;
    color: white;
}

.badge-resolution.inactive {
    background: #e9ecef;
    color: #6c757d;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
}

.product-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 30px;
}

/* ===== SECTION TITLES ===== */
.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
}

/* ===== SPESIFIKASI GRID ===== */
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
    color: #667eea;
    font-size: 1.2rem;
    margin-top: 2px;
}

.spec-text {
    color: #333;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* ===== PACKAGE INCLUDES & FEATURES GRID (GAYA RUIJIE) ===== */
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
    background: #f8f9fa;
    border-radius: 8px;
}

/* KHUSUS UNTUK FITUR YANG PUNYA LABEL DAN VALUE (GAYA RUIJIE) */
.package-item.feature-box {
    align-items: flex-start;
}

.feature-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.feature-label {
    font-weight: 700;
    font-size: 0.85rem;
    color: #4b5563;
    text-transform: uppercase;
}

.package-item i {
    color: #28a745;
    font-size: 1.2rem;
}

.package-item i.text-warning {
    color: #ffc107 !important;
}

.package-text {
    color: #333;
    font-size: 0.95rem;
}

/* ===== PRICE & ORDER ===== */
.price-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 12px;
    color: white;
    margin-top: 30px;
}

.price-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 5px;
}

.price-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.btn-order {
    background: white;
    color: #667eea;
    border: none;
    padding: 15px 40px;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    width: 100%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-order:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* ===== SIMILAR PRODUCTS ===== */
.similar-products-section {
    margin-top: 50px;
}

.similar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.similar-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.btn-view-all {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-view-all:hover {
    color: #764ba2;
    text-decoration: underline;
}

.similar-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #eee;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.similar-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transform: translateY(-5px);
}

.similar-img {
    height: 150px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.similar-img img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.similar-name {
    font-weight: 700;
    color: #333;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.similar-subtitle {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 15px;
    min-height: 40px; 
}

.similar-price {
    font-weight: 700;
    font-size: 1.1rem;
    color: #333;
    margin-top: auto;
    margin-bottom: 15px;
}

.btn-detail-similar {
    color: #667eea;
    font-size: 0.9rem;
    text-decoration: none;
}

.btn-detail-similar:hover {
    color: #764ba2;
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
        <a href="{{ url('/wifi-cam') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke WiFi Camera
        </a>

        <div class="product-detail-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="product-images">
                        @if(isset($product['brand_logo']))
                        <img src="{{ $product['brand_logo'] }}" alt="{{ $product['brand'] ?? 'Logo' }}" class="brand-logo">
                        @endif

                        <div class="main-image">
                            <img src="{{ $product['images'][0] ?? ($product['main_image'] ?? 'https://via.placeholder.com/400') }}" alt="{{ $product['name'] ?? 'Product Image' }}" id="mainImage">
                        </div>

                        @if(isset($product['images']) && count($product['images']) > 0)
                        <div class="thumbnail-images">
                            @foreach($product['images'] as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ $image }}', this)">
                                <img src="{{ $image }}" alt="{{ $product['name'] ?? 'Product' }} view {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="product-info">
                        @if(isset($product['resolutions']))
                        <div class="resolution-badges">
                            @foreach($product['resolutions'] as $res)
                            <span class="badge-resolution {{ $res['active'] ? 'active' : 'inactive' }}">
                                {{ $res['label'] }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <h1 class="product-title">{{ $product['name'] ?? 'Nama Produk' }}</h1>
                        <p class="product-subtitle">{{ $product['subtitle'] ?? '' }}</p>

                        <h2 class="section-title">Spesifikasi</h2>
                        <div class="specs-grid">
                            @php
                                $specs = $product['specifications'] ?? [];
                                if (is_string($specs)) {
                                    $specs = json_decode($specs, true);
                                }
                            @endphp

                            @if(!empty($specs) && is_array($specs))
                                @foreach($specs as $spec)
                                    @php
                                        if (is_array($spec)) {
                                            $key = array_key_first($spec);
                                            $displayText = $key . ': ' . $spec[$key];
                                        } elseif (is_string($spec)) {
                                            $displayText = $spec;
                                        } else {
                                            $displayText = json_encode($spec);
                                        }
                                    @endphp
                                    <div class="spec-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span class="spec-text">{{ $displayText }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada spesifikasi khusus.</p>
                            @endif
                        </div>

                        <h2 class="section-title">Fitur Utama</h2>
                        <div class="package-list">
                            @php
                                $features = $product['features'] ?? [];
                                while (is_string($features)) {
                                    $decoded = json_decode($features, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $features = $decoded;
                                    } else {
                                        break; 
                                    }
                                }
                            @endphp

                            @if(!empty($features) && is_array($features))
                                @foreach($features as $label => $value)
                                    @php
                                        $actualLabel = null;
                                        $actualValue = $value;

                                        // Logika membaca Filament Repeater
                                        if (is_array($value)) {
                                            if (isset($value['feature_name'])) {
                                                $actualLabel = $value['feature_name'];
                                            }
                                            if (isset($value['feature_value'])) {
                                                $actualValue = $value['feature_value'];
                                            }
                                        } else if (!is_numeric($label)) {
                                            // Jika pakai key-value biasa, labelnya adalah key-nya
                                            $actualLabel = $label;
                                        }
                                    @endphp
                                    
                                    <div class="package-item {{ $actualLabel ? 'feature-box' : '' }}">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        
                                        @if($actualLabel)
                                        <div class="feature-content">
                                            <span class="feature-label">{{ $actualLabel }}</span>
                                            <span class="package-text">{{ is_array($actualValue) ? implode(', ', $actualValue) : $actualValue }}</span>
                                        </div>
                                        @else
                                        <span class="package-text">{{ is_array($actualValue) ? implode(', ', $actualValue) : $actualValue }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada fitur yang ditambahkan.</p>
                            @endif
                        </div>

                        <h2 class="section-title">Sudah Termasuk</h2>
                        <div class="package-list">
                            @php
                                $packages = $product['package_includes'] ?? [];
                                if (is_string($packages)) {
                                    $packages = json_decode($packages, true);
                                }
                            @endphp
                            
                            @if(!empty($packages) && is_array($packages))
                                @foreach($packages as $item)
                                    @php
                                        $packageText = is_array($item) ? implode(', ', $item) : $item;
                                    @endphp
                                    <div class="package-item">
                                        <i class="bi bi-box-seam"></i>
                                        <span class="package-text">{{ $packageText }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Kamera, Kabel Power, Adaptor (Standar Pabrik)</p>
                            @endif
                        </div>

                        <div class="price-section">
                            <div class="price-label">Harga/Unit:</div>
                            <div class="price-value">IDR. {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</div>
                            
                            <button class="btn btn-order" onclick="orderNow()">
                                <i class="bi bi-cart-check"></i>
                                Order Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(isset($similarProducts) && $similarProducts->count() > 0)
        <div class="similar-products-section">
            <div class="similar-header">
                <h3 class="similar-title">Produk Serupa</h3>
                <a href="{{ url('/wifi-cam') }}" class="btn-view-all">Produk Lainnya &rarr;</a>
            </div>
            
            <div class="row g-3">
                @foreach($similarProducts as $similar)
                <div class="col-6 col-md-3">
                    <a href="{{ url('/wifi-cam/' . $similar->slug) }}" style="text-decoration: none;">
                        <div class="similar-card">
                            <div class="similar-img">
                                <img src="{{ $similar->main_image ? '/storage/' . $similar->main_image : 'https://via.placeholder.com/150' }}" alt="{{ $similar->name }}">
                            </div>
                            <div class="similar-name">{{ $similar->name }}</div>
                            <div class="similar-subtitle">{{ Str::limit($similar->subtitle, 50) }}</div>
                            <div class="similar-price">Rp. {{ number_format($similar->price, 0, ',', '.') }}</div>
                            <div class="btn-detail-similar">Lihat Detail &rarr;</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script>
function changeImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

function orderNow() {
    const waNumber = "6281234567890";
    const productName = "{{ $product['name'] ?? 'Produk' }}";
    const productPrice = "Rp {{ isset($product['price']) ? number_format($product['price'], 0, ',', '.') : '0' }}";
    
    const message = `Halo Admin TechStore, saya tertarik dengan produk:\n\n*${productName}*\nHarga: ${productPrice}\n\nApakah stoknya masih tersedia?`;
    const encodedMessage = encodeURIComponent(message);
    
    const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;
    window.open(waUrl, '_blank');
}
</script>
@endsection