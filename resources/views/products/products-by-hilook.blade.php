@extends('layouts.simple')

@section('title', 'Produk ' . $brandName . ' - TechStore')

@section('content')

@php 
    // Set variabel warna utama menjadi biru muda (#00A7E1) untuk halaman HiLook
    $themeColor = '#00A7E1'; 
@endphp

<div class="container py-5">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none" style="color: {{ $themeColor }};">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/') }}#brands" class="text-decoration-none" style="color: {{ $themeColor }};">Brands</a></li>
                    <li class="breadcrumb-item active">{{ $brandName }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="brand-header p-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, {{ $themeColor }}15 0%, {{ $themeColor }}05 100%); border-left: 5px solid {{ $themeColor }};">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3" style="color: {{ $themeColor }}">
                            {{ $brandData['name'] }}
                        </h1>
                        <p class="lead text-muted mb-0">
                            {{ $brandData['description'] }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge fs-6 px-4 py-2" style="background-color: {{ $themeColor }}; color: white;">
                            <i class="bi bi-shield-check"></i> Authorized Dealer
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-4">
                <i class="bi bi-grid-3x3-gap" style="color: {{ $themeColor }}"></i> Kategori Produk {{ $brandName }}
            </h2>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card h-100 border-0 shadow-sm product-category-card">
                <div class="card-body text-center">
                    <div class="category-icon mb-3" style="background-color: {{ $themeColor }}15; width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-camera-video fs-1" style="color: {{ $themeColor }}"></i>
                    </div>
                    <h5 class="card-title fw-bold">Basic Series</h5>
                    <p class="card-text text-muted small">
                        Kamera CCTV series basic dengan kualitas HD
                    </p>
                    <span class="badge" style="background-color: {{ $themeColor }}; color: white;">
                        {{ $categoryCounts['basic'] ?? 0 }} Produk
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card h-100 border-0 shadow-sm product-category-card">
                <div class="card-body text-center">
                    <div class="category-icon mb-3" style="background-color: {{ $themeColor }}15; width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-mic fs-1" style="color: {{ $themeColor }}"></i>
                    </div>
                    <h5 class="card-title fw-bold">Audio Series</h5>
                    <p class="card-text text-muted small">
                        Kamera CCTV dengan fitur audio built-in
                    </p>
                    <span class="badge" style="background-color: {{ $themeColor }}; color: white;">
                        {{ $categoryCounts['audio'] ?? 0 }} Produk
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card h-100 border-0 shadow-sm product-category-card">
                <div class="card-body text-center">
                    <div class="category-icon mb-3" style="background-color: {{ $themeColor }}15; width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-palette fs-1" style="color: {{ $themeColor }}"></i>
                    </div>
                    <h5 class="card-title fw-bold">Full Color</h5>
                    <p class="card-text text-muted small">
                        Kamera full color dengan night vision
                    </p>
                    <span class="badge" style="background-color: {{ $themeColor }}; color: white;">
                        {{ $categoryCounts['fullcolor'] ?? 0 }} Produk
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card h-100 border-0 shadow-sm product-category-card">
                <div class="card-body text-center">
                    <div class="category-icon mb-3" style="background-color: {{ $themeColor }}15; width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-wifi fs-1" style="color: {{ $themeColor }}"></i>
                    </div>
                    <h5 class="card-title fw-bold">IP Camera</h5>
                    <p class="card-text text-muted small">
                        Kamera IP dengan koneksi jaringan
                    </p>
                    <span class="badge" style="background-color: {{ $themeColor }}; color: white;">
                        {{ $categoryCounts['ipcamera'] ?? 0 }} Produk
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h5 class="mb-2 fw-bold">Pilih Brand Lain Nya Disini</h5>
                        <p class="text-muted mb-0 small"></p>
                    </div>
                    
                    <div class="brand-filter-nav d-flex flex-wrap gap-3">
                        @php
                            $brands = [
                                'hikvision' => ['name' => 'Hikvision', 'color' => '#E62B2E'],
                                'hilook'    => ['name' => 'HiLook',    'color' => '#00A7E1'],
                                'dahua'     => ['name' => 'Dahua',     'color' => '#0066B3'],
                                'hiview'    => ['name' => 'Hiview',    'color' => '#deb01f'],
                                'unv'       => ['name' => 'UNV',       'color' => '#9C27B0'],
                            ];
                            $currentBrand = strtolower($brandName);
                        @endphp
                        
                        @foreach($brands as $brandKey => $brandInfo)
                        <a href="{{ url('/products/' . $brandKey) }}" 
                           class="brand-filter-btn {{ $brandKey === $currentBrand ? 'active' : '' }}"
                           style="background-color: {{ $brandInfo['color'] }}; border-color: {{ $brandInfo['color'] }}; color: white; --brand-color: {{ $brandInfo['color'] }};">
                            {{ $brandInfo['name'] }}
                            @if($brandKey === $currentBrand)
                            <i class="bi bi-check-circle-fill ms-1"></i>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-camera-fill" style="color: {{ $themeColor }}"></i> 
                        Katalog Produk {{ $brandName }}
                    </h3>

                    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                        @foreach(['basic' => 'Basic', 'audio' => 'Audio', 'fullcolor' => 'Full Color', 'ipcamera' => 'IP Camera'] as $key => $label)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="{{ $key }}-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ $key }}" 
                                    type="button">
                                {{ $label }}
                                @if(isset($categoryCounts[$key]) && $categoryCounts[$key] > 0)
                                    <span class="badge ms-1" style="background-color: {{ $themeColor }}; color: white;">{{ $categoryCounts[$key] }}</span>
                                @endif
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="productTabContent">
                        @foreach(['basic' => 'Basic', 'audio' => 'Audio', 'fullcolor' => 'Full Color', 'ipcamera' => 'IP Camera'] as $key => $label)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="{{ $key }}" 
                             role="tabpanel">
                            <div class="row g-3">
                                @forelse($staticProducts[$key] ?? [] as $product)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card h-100 product-card border">
                                        <div class="card-header bg-light text-center p-0 position-relative">
                                            @if(!empty($product->gambar) || !empty($product->image))
                                            <div class="product-image-container">
                                                <img src="{{ asset('storage/' . ($product->gambar ?? $product->image)) }}" 
                                                     class="product-image" 
                                                     alt="{{ $product->nama_produk ?? $product->product_name }}">
                                            </div>
                                            @else
                                            <div class="product-image-container">
                                                <img src="https://via.placeholder.com/200x200?text={{ urlencode($product->nama_produk ?? $product->product_name) }}" 
                                                     class="product-image" 
                                                     alt="{{ $product->nama_produk ?? $product->product_name }}">
                                            </div>
                                            @endif
                                        </div>

                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3 text-center">{{ $product->nama_produk ?? $product->product_name }}</h6>
                                            
                                            @php
                                                $displayPrice = 0;
                                                if (isset($product->harga_jual) && $product->harga_jual > 0) {
                                                    $displayPrice = is_numeric($product->harga_jual) ? $product->harga_jual : (float)str_replace([','], '', $product->harga_jual);
                                                } elseif (isset($product->harga) && $product->harga > 0) {
                                                    $displayPrice = is_numeric($product->harga) ? $product->harga : (float)str_replace([','], '', $product->harga);
                                                } elseif (isset($product->price) && $product->price > 0) {
                                                    $displayPrice = is_numeric($product->price) ? $product->price : (float)str_replace([','], '', $product->price);
                                                }
                                            @endphp
                                            <div class="text-center mb-3">
                                                <div class="price-badge px-3 py-2" style="background-color: {{ $themeColor }}15; border: 2px solid {{ $themeColor }}; border-radius: 8px;">
                                                    <small class="d-block text-muted" style="font-size: 0.75rem;">Harga</small>
                                                    <span class="fw-bold" style="color: {{ $themeColor }}; font-size: 1.1rem;">
                                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if(!empty($product->deskripsi) || !empty($product->description))
                                            <div class="product-description mb-3">
                                                @php
                                                    $description = $product->deskripsi ?? $product->description;
                                                    $lines = array_filter(array_map('trim', explode("\n", $description)));
                                                @endphp
                                                @if(count($lines) > 1)
                                                    <div class="mb-2">
                                                        <strong class="d-block mb-2" style="font-size: 0.9rem;">Sudah Termasuk:</strong>
                                                    </div>
                                                    <ul class="list-unstyled small description-list">
                                                        @foreach($lines as $line)
                                                        <li><i class="bi bi-check-circle-fill" style="color: {{ $themeColor }}"></i> {{ $line }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted small mb-0" style="line-height: 1.5;">
                                                        {{ Str::limit($description, 100) }}
                                                    </p>
                                                @endif
                                            </div>
                                            @endif

                                            @if(!empty($product->specifications) && is_array($product->specifications))
                                            <ul class="list-unstyled small specs-list mb-3">
                                                @foreach(array_slice($product->specifications, 0, 3) as $key => $value)
                                                <li><i class="bi bi-check-circle-fill" style="color: {{ $themeColor }}"></i> {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-white border-top text-center">
                                            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->nama_produk ?? $product->product_name) }}%20-%20{{ urlencode($product->sku ?? '') }}" 
                                            target="_blank"
                                            class="btn btn-sm w-100" 
                                            style="background-color: {{ $themeColor }}; color: white; border: none;"
                                            onmouseover="this.style.opacity='0.8'" 
                                            onmouseout="this.style.opacity='1'">
                                                <i class="bi bi-whatsapp"></i> Order Sekarang 
                                            </a>
                                        </div>
                                        <script type="application/ld+json">
                                    {
                                      "@context": "https://schema.org/",
                                      "@type": "Product",
                                      "name": "{{ $product->nama_produk ?? $product->product_name }}",
                                      "image": "{{ !empty($product->gambar) || !empty($product->image) ? asset('storage/' . ($product->gambar ?? $product->image)) : 'https://via.placeholder.com/200x200' }}",
                                      "description": "{{ strip_tags($product->deskripsi ?? $product->description ?? 'Kamera CCTV ' . $brandName . ' berkualitas.') }}",
                                      "sku": "{{ $product->sku ?? '' }}",
                                      "brand": {
                                        "@type": "Brand",
                                        "name": "{{ $brandName }}"
                                      },
                                      "offers": {
                                        "@type": "Offer",
                                        "url": "{{ url()->current() }}",
                                        "priceCurrency": "IDR",
                                        "price": "{{ $displayPrice }}",
                                        "availability": "https://schema.org/InStock",
                                        "itemCondition": "https://schema.org/NewCondition"
                                      }
                                    }
                                    </script>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert text-center" style="background-color: {{ $themeColor }}15; border-color: {{ $themeColor }}30; color: {{ $themeColor }};">
                                        <i class="bi bi-info-circle"></i> 
                                        Belum ada produk di kategori {{ $label }}
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-star-fill" style="color: {{ $themeColor }}"></i> Kenapa Pilih Kami?
            </h3>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-award fs-1 mb-2" style="color: {{ $themeColor }}"></i>
                <h6 class="fw-bold">Produk Original</h6>
                <p class="small text-muted mb-0">100% produk asli dengan garansi resmi</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-tools fs-1 mb-2" style="color: {{ $themeColor }}"></i>
                <h6 class="fw-bold">Instalasi Profesional</h6>
                <p class="small text-muted mb-0">Tim teknisi berpengalaman</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-headset fs-1 mb-2" style="color: {{ $themeColor }}"></i>
                <h6 class="fw-bold">Support 24/7</h6>
                <p class="small text-muted mb-0">Layanan pelanggan siap membantu</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-truck fs-1 mb-2" style="color: {{ $themeColor }}"></i>
                <h6 class="fw-bold">Pengiriman Cepat</h6>
                <p class="small text-muted mb-0">Pengiriman ke seluruh Indonesia</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="cta-box p-5 rounded-3 text-center" style="background: linear-gradient(135deg, {{ $themeColor }} 0%, #007CA8 100%); color: white;">
                <h3 class="fw-bold mb-3">Tertarik dengan Produk {{ $brandName }}?</h3>
                <p class="lead mb-4">Konsultasikan kebutuhan keamanan Anda dengan tim ahli kami</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ url('/contact') }}" class="btn btn-light btn-lg" style="color: {{ $themeColor }};">
                        <i class="bi bi-envelope"></i> Hubungi Kami
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success btn-lg">
                        <i class="bi bi-whatsapp"></i> Chat WhatsApp
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-house"></i> Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== BRAND FILTER - CLEAN & PROFESSIONAL (BIGGER SIZE) ===== */
.brand-filter-nav {
    display: flex;
    align-items: center;
}
.brand-filter-btn {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border-radius: 8px;
    border: 2px solid transparent;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.brand-filter-btn:hover {
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    opacity: 0.85;
    color: white; 
}

.brand-filter-btn.active {
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transform: scale(1.05); 
}
.brand-filter-btn.active:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.brand-filter-btn i {
    font-size: 0.9rem;
}

/* ===== EXISTING PRODUCT STYLES ===== */
.price-badge {
    display: inline-block;
    transition: all 0.3s ease;
}

.price-badge:hover {
    transform: scale(1.05);
}

.product-category-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}

.product-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 8px;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.15) !important;
}

.product-card .card-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}

.product-image-container {
    height: 280px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    overflow: hidden;
    padding: 10px;
}

.product-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    object-position: center;
}

.description-list {
    font-size: 0.85rem;
    margin-bottom: 0;
}

.description-list li {
    padding: 3px 0;
    line-height: 1.4;
    display: flex;
    align-items: flex-start;
}

.description-list i {
    margin-right: 8px;
    font-size: 0.7em;
    margin-top: 4px;
    flex-shrink: 0;
}

.specs-list {
    font-size: 0.85rem;
}

.specs-list li {
    padding: 4px 0;
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
}

.specs-list i {
    margin-right: 8px;
    font-size: 0.75em;
    margin-top: 4px;
    flex-shrink: 0;
}

.product-description p {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
}

.feature-box {
    transition: all 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-5px);
}

.cta-box {
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
}

/* CSS TABS DIRUBAH JADI #00A7E1 SESUAI TEMA */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
    padding-bottom: 0.75rem;
}

.nav-tabs .nav-link:hover {
    border-color: transparent transparent #00A7E1;
    color: #00A7E1;
}

.nav-tabs .nav-link.active {
    color: #00A7E1;
    background-color: transparent;
    border-color: transparent transparent #00A7E1;
    font-weight: 600;
}

.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .brand-filter-nav {
        width: 100%;
        justify-content: center;
    }
    
    .product-card .card-body h6 {
        font-size: 0.95rem;
    }
    
    .specs-list, .description-list {
        font-size: 0.8rem;
    }
}

@media (max-width: 767px) {
    .brand-filter-btn {
        font-size: 0.9rem;
        padding: 10px 18px;
    }
    
    .product-image-container {
        height: 200px;
    }
    
    .brand-header .col-md-4 {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .cta-box {
        padding: 2rem !important;
    }
    
    .cta-box h3 {
        font-size: 1.5rem;
    }
}
</style>
@endsection