@extends('layouts.simple')

@section('title', 'Detail Produk Access Control - TechStore')

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
    flex-wrap: wrap;
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

.resolution-badges {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
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
    margin-bottom: 10px;
}

.product-subtitle {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 20px;
    font-family: monospace;
}

/* ===== SPECIFICATIONS (Deskripsi Berpoin) ===== */
.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
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
    color: #667eea; /* Warna biru untuk centang */
    font-size: 1.2rem;
    margin-top: 2px;
}

.spec-text {
    color: #333;
    font-size: 0.95rem;
    line-height: 1.5;
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
    .product-images {
        padding: 20px;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
    
    .price-value {
        font-size: 2rem;
    }
}
</style>

<div class="detail-container">
    <div class="container">
        <a href="{{ url('/access-control') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Access Control
        </a>

        <div class="product-detail-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="product-images">
                        <div class="main-image">
                            <img src="{{ !empty($product['main_image']) ? asset('storage/' . $product['main_image']) : 'https://via.placeholder.com/400x400/ffffff/cccccc?text=No+Image' }}" alt="{{ $product['name'] }}" id="mainImage">
                        </div>

                        @php
                            $gallery = [];
                            if (!empty($product['gallery_images'])) {
                                $gallery = is_string($product['gallery_images']) ? json_decode($product['gallery_images'], true) : $product['gallery_images'];
                            }
                        @endphp

                        @if(!empty($gallery) && is_array($gallery))
                        <div class="thumbnail-images">
                            @foreach($gallery as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('storage/' . $image) }}', this)">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product['name'] }} view {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="product-info">
                        <div class="resolution-badges">
                            @if(!empty($product['brand']))
                            <span class="badge-resolution active">
                                {{ strtoupper($product['brand']) }}
                            </span>
                            @endif
                            
                            @if(!empty($product['category']))
                            <span class="badge-resolution inactive">
                                {{ $product['category'] }}
                            </span>
                            @endif
                        </div>

                        <h1 class="product-title">{{ $product['name'] }}</h1>
                        <h2 class="section-title">Spesifikasi</h2>
                        
                        <div class="specs-grid">
                            @php
                                $descLines = [];
                                if (!empty($product['description'])) {
                                    // Memecah deskripsi menjadi array berdasarkan baris baru (Enter)
                                    $parts = explode("\n", $product['description']);
                                    foreach($parts as $part) {
                                        // Membersihkan spasi di awal/akhir dan memastikan baris tidak kosong
                                        if(trim($part) !== '') {
                                            $descLines[] = trim($part);
                                        }
                                    }
                                }
                            @endphp

                            @if(count($descLines) > 0)
                                @foreach($descLines as $line)
                                <div class="spec-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="spec-text">{{ $line }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada deskripsi/spesifikasi yang tercantum.</p>
                            @endif
                        </div>

                        <div class="price-section">
                            <div class="price-label">Harga/Unit:</div>
                            <div class="price-value">IDR. {{ number_format($product['sell_price'] ?? 0, 0, ',', '.') }}</div>
                            
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
                <a href="{{ url('/access-control') }}" class="btn-view-all">Produk Lainnya &rarr;</a>
            </div>
            
            <div class="row g-3">
                @foreach($similarProducts as $similar)
                <div class="col-6 col-md-3">
                    <a href="{{ url('/access-control/' . $similar->id) }}" style="text-decoration: none;">
                        <div class="similar-card">
                            <div class="similar-img">
                                <img src="{{ !empty($similar->main_image) ? asset('storage/' . $similar->main_image) : 'https://via.placeholder.com/150' }}" alt="{{ $similar->name }}">
                            </div>
                            <div class="similar-name">{{ $similar->name }}</div>
                            <div class="similar-price">Rp. {{ number_format($similar->sell_price ?? 0, 0, ',', '.') }}</div>
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
// Change main image when thumbnail clicked
function changeImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

// Order now function to WhatsApp
function orderNow() {
    const waNumber = "62881025756671"; // Ganti dengan nomor WA admin
    const productName = "{{ addslashes($product['name'] ?? '') }}";
    const sku = "{{ addslashes($product['sku'] ?? '') }}";
    // Sesuaikan link WA ke sell_price
    const productPrice = "Rp {{ number_format($product['sell_price'] ?? 0, 0, ',', '.') }}";
    
    const message = `Halo Admin TechStore, saya tertarik dengan produk Access Control:\n\n*${productName}*\nSKU: ${sku}\nHarga: ${productPrice}\n\nApakah stoknya masih tersedia?`;
    const encodedMessage = encodeURIComponent(message);
    
    const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;
    window.open(waUrl, '_blank');
}
</script>
@endsection