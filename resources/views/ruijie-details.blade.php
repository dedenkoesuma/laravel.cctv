@extends('layouts.simple')

@section('title', 'Detail Produk Ruijie - TechStore')

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
    background: #00A7E1; /* Warna Ruijie */
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
    margin-bottom: 5px;
}

.product-subtitle {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 15px;
    font-family: monospace;
}

.product-desc {
    color: #555;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 30px;
}

/* ===== SPECIFICATIONS ===== */
.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #00A7E1;
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
    color: #00A7E1;
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
    background: #f8f9fa;
    border-radius: 8px;
}

.package-item i {
    color: #f59e0b;
    font-size: 1.2rem;
}

.package-text {
    color: #333;
    font-size: 0.95rem;
}

/* ===== PRICE & ORDER (BARU: BANNER TANYA HARGA) ===== */
.price-section {
    background: #f0fdf4;
    border: 2px dashed #22c55e;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    margin-top: 30px;
}

.price-label {
    font-size: 1.3rem;
    font-weight: 800;
    color: #166534;
    margin-bottom: 5px;
}

.price-value {
    font-size: 1rem;
    font-weight: 600;
    color: #15803d;
    margin-bottom: 20px;
}

.btn-order {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
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
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
}

.btn-order:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.35);
    color: white;
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
}
</style>

<div class="detail-container">
    <div class="container">
        <a href="{{ url('/products/ruijie') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Ruijie Networks
        </a>

        <div class="product-detail-card">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="product-images">

                        <div class="main-image">
                            {{-- Menggunakan kolom ->image sesuai database Ruijie --}}
                            <img src="{{ !empty($product->image) ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x400/ffffff/cccccc?text=Ruijie' }}" alt="{{ $product->name }}" id="mainImage">
                        </div>

                        @php
                            // Menggunakan kolom ->images
                            $gallery = is_string($product->images ?? '') ? json_decode($product->images, true) : ($product->images ?? []);
                        @endphp
                        
                        @if(!empty($gallery))
                        <div class="thumbnail-images">
                            @foreach($gallery as $index => $img)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage('{{ asset('storage/' . $img) }}', this)">
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }} view {{ $index + 1 }}">
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
                                RUIJIE
                            </span>
                            {{-- Memanggil nama kategori dari objek relasi --}}
                            @if($product->category)
                            <span class="badge-resolution inactive">
                                {{ $product->category->name ?? $product->category }}
                            </span>
                            @endif
                        </div>

                        <h1 class="product-title">{{ $product->name }}</h1>
                        
                       @if(!empty($product->description))
                        <div class="product-desc">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                        @endif
                            @php
                            $specs = [];
                            if (!empty($product->specifications)) {
                                // Coba decode sebagai JSON terlebih dahulu
                                $decodedSpecs = is_string($product->specifications) ? json_decode($product->specifications, true) : $product->specifications;
                                
                                if (is_array($decodedSpecs) && json_last_error() === JSON_ERROR_NONE) {
                                    $specs = $decodedSpecs; // Jika valid JSON, gunakan hasilnya
                                } else {
                                    // Jika BUKAN JSON (teks biasa dari textarea), pisahkan berdasarkan enter (baris baru)
                                    $specs = array_filter(explode("\n", str_replace("\r", "", $product->specifications)));
                                }
                            }
                        @endphp
                        @if(!empty($specs) && count($specs) > 0)
                        <h2 class="section-title">Spesifikasi</h2>
                        <div class="specs-grid">
                            @foreach($specs as $spec)
                                @if(trim($spec) !== '' && trim($spec) !== '[]') {{-- Hindari menampilkan array kosong dari placeholder form --}}
                                <div class="spec-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="spec-text">{{ trim($spec) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        {{-- ===== FEATURES ===== --}}
                        @php
                            $features = [];
                            if (!empty($product->features)) {
                                // Coba decode sebagai JSON terlebih dahulu
                                $decodedFeatures = is_string($product->features) ? json_decode($product->features, true) : $product->features;
                                
                                if (is_array($decodedFeatures) && json_last_error() === JSON_ERROR_NONE) {
                                    $features = $decodedFeatures; // Jika valid JSON, gunakan hasilnya
                                } else {
                                    // Jika BUKAN JSON (teks biasa dari textarea), pisahkan berdasarkan enter (baris baru)
                                    $features = array_filter(explode("\n", str_replace("\r", "", $product->features)));
                                }
                            }
                        @endphp
                        
                        @if(!empty($features) && count($features) > 0)
                        <h2 class="section-title">Fitur Utama</h2>
                        <div class="package-list">
                            @foreach($features as $item)
                                @if(trim($item) !== '' && trim($item) !== '[]') {{-- Hindari menampilkan array kosong dari placeholder form --}}
                                <div class="package-item">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="package-text">{{ trim($item) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <div class="price-section">
                            <div class="price-label">🏷️ Penawaran Harga Spesial</div>
                            <div class="price-value">Chat Admin untuk diskon & harga project</div>
                            
                            <button class="btn btn-order" onclick="orderNow()">
                                <i class="bi bi-whatsapp"></i>
                                Chat Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

// Order now function
function orderNow() {
    const productName = '{{ addslashes($product->name) }}';
    
    // Pesan disamakan dengan index Ruijie
    const message = `Halo Admin TechStore, saya tertarik dengan produk Ruijie berikut:\n\n*${productName}*\n\nBisa minta penawaran harga terbaik dan info ketersediaan stoknya? Terima kasih.`;
    
    window.open('https://wa.me/62881025756671?text=' + encodeURIComponent(message), '_blank');
}
</script>
@endsection