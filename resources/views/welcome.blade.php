@extends('layouts.simple')

@section('title', 'TechStore - Home')

@section('content')
<style>
/* ===== NAVBAR STYLING ===== */
.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.3rem;
}

.nav-link {
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: #fff !important;
    background-color: rgba(255,255,255,0.1);
    border-radius: 5px;
}

.nav-link.active {
    background-color: rgba(255,255,255,0.15);
    border-radius: 5px;
}

.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
    margin-top: 0.5rem;
}

.dropdown-item {
    padding: 0.6rem 1.2rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.5rem;
}

.dropdown-item i {
    opacity: 0.7;
}

/* ===== HERO SECTION ===== */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 20px;
    text-align: center;
    margin: 0;
}

.hero-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: 1rem;
    animation: fadeInUp 0.8s ease-out;
}

.hero-subtitle {
    font-size: clamp(1rem, 2vw, 1.25rem);
    opacity: 0.95;
    animation: fadeInUp 0.8s ease-out 0.2s both;
}
/* Brand Section Styles */
.brand-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.brand-card {
    background: white;
    padding: 30px 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
    border: 2px solid transparent;
}

.brand-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.brand-logo {
    width: 180px;
    height: 180px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}

.brand-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 15px 0 10px;
    color: #333;
}

.brand-link-text {
    color: #007bff;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.3s ease;
}

.brand-card:hover .brand-link-text {
    gap: 10px;
}

.brand-link-text i {
    font-size: 0.85rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
}

/* Responsive */
@media (max-width: 768px) {
    .brand-logo {
        width: 100px;
        height: 100px;
    }
    
    .brand-name {
        font-size: 1.1rem;
    }
}

/* ===== SECTION COMMON ===== */
.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
}

.section-description {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.6;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.5rem;
    gap: 2rem;
}

/* ===== BRAND CARDS ===== */
.brand-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.brand-card {
    background: white;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    border: 1px solid #f0f0f0;
}

.brand-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    border-color: #667eea;
}

.brand-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.brand-link-text {
    color: #667eea;
    font-size: 0.9rem;
    font-weight: 500;
}

/* ===== PACKAGE CARDS ===== */
.package-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.package-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.15);
}

.package-header {
    padding: 1.5rem 1.5rem 0;
}

.package-channel {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.25rem;
}

.package-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.package-image {
    padding: 1.5rem;
    text-align: center;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.package-price {
    text-align: center;
    padding: 0 1.5rem 1rem;
}

.package-price .badge {
    font-size: 1rem;
    padding: 0.5rem 1.25rem;
    font-weight: 600;
}

.package-features {
    padding: 0 1.5rem 1rem;
    flex-grow: 1;
}

.features-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-list li {
    display: flex;
    align-items: flex-start;
    padding: 0.4rem 0;
    font-size: 0.875rem;
    color: #555;
}

.features-list i {
    color: #28a745;
    font-size: 1rem;
    margin-right: 0.5rem;
    flex-shrink: 0;
    margin-top: 0.1rem;
}

.package-footer {
    padding: 1.5rem;
    border-top: 1px solid #f0f0f0;
}

/* ===== WIFI CAMERA CARDS ===== */
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
    background: #f8f9fa;
    padding: 2rem;
    text-align: center;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.camera-content {
    padding: 1.5rem;
    text-align: center;
}

.camera-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.camera-price {
    color: #dc3545;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.camera-description {
    color: #6c757d;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 0;
}

.camera-footer {
    padding: 0 1.5rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.camera-brand {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

/* ===== ACCESS CONTROL CARDS ===== */
.access-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.access-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.access-image {
    background: #f8f9fa;
    padding: 2rem;
    text-align: center;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.access-content {
    padding: 1.5rem;
}

.access-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.access-price {
    color: #dc3545;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.access-description {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0;
}

.access-footer {
    padding: 0 1.5rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.access-brand {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
}

/* ===== SERVICE CARDS ===== */
.service-card {
    background: white;
    border-radius: 12px;
    padding: 2.5rem 2rem;
    text-align: center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.service-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
}

.service-description {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 0;
}

/* ===== CLIENT CARDS ===== */
.client-card {
    background: white;
    padding: 1.25rem 0.75rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.client-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.client-card strong {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991.98px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-section {
        padding: 60px 20px;
    }
    
    .navbar-nav {
        padding: 1rem 0;
    }
    
    .nav-link {
        padding: 0.7rem 1rem !important;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: none;
        background-color: rgba(255,255,255,0.05);
    }
    
    .dropdown-item {
        color: rgba(255,255,255,0.8);
    }
    
    .dropdown-item:hover {
        color: #fff;
        background-color: rgba(255,255,255,0.1);
    }
}

@media (max-width: 767px) {
    .section-title {
        font-size: 1.75rem;
    }
}
   * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            padding: 0;
        }

        .features-grid {
            max-width: 1400px;
            margin: 0 auto 0;
            padding: 4rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #f8f9fa;
            padding: 2rem 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(102, 126, 234, 0.4);
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #7c7fdb, #9d84d6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 30px rgba(124, 127, 219, 0.5);
        }

        .feature-title {
            font-size: 1.35rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            color: #1a1a1a;
            position: relative;
            z-index: 1;
        }

        .feature-desc {
            color: rgba(0,0,0,0.6);
            line-height: 1.6;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .stats {
            max-width: 100%;
            margin: 0;
            padding: 5rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .stat-item h3 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .stat-item p {
            font-size: 1.3rem;
            opacity: 0.95;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: 1fr;
                padding: 2rem 1rem;
            }
            .stat-item h3 {
                font-size: 3rem;
            }
        }
</style>


<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Selamat Datang di TechStore</h1>
        <p class="hero-subtitle">Temukan produk teknologi CCTV terbaik dengan harga terjangkau</p>
    </div>
</section>

<!-- Brands Section -->
<section class="py-5 bg-white" id="brands">
    <div class="container">
        <h2 class="section-title text-center mb-5">Brand Partner Kami</h2>
        <div class="row g-4">
            @php
$brands = [
    ['name' => 'HIKVISION', 'slug' => 'hikvision', 'image' => 'hikvision.png'],
    ['name' => 'Dahua', 'slug' => 'dahua', 'image' => 'dahua.png'],
    ['name' => 'HiLook', 'slug' => 'hilook', 'image' => 'hilook.png'],
    ['name' => 'EZVIZ', 'slug' => 'ezviz', 'image' => 'ezviz.png'],
    ['name' => 'UNV', 'slug' => 'unv', 'image' => 'unv.png'],
    ['name' => 'RUIJIE', 'slug' => 'ruijie', 'image' => 'ruijie.png'],
    ['name' => 'HIVIEW', 'slug' => 'hiview', 'image' => 'hiview.png'],
];
@endphp
           @foreach($brands as $brand)
<div class="col-lg-3 col-md-4 col-sm-6">
    @if($brand['slug'] === 'ezviz')
        {{-- EZVIZ redirect ke /wifi-cam --}}
        <a href="{{ url('/wifi-cam') }}" class="brand-card-link">
    @else
        {{-- Brand lain tetap ke products.brand --}}
        <a href="{{ route('products.brand', $brand['slug']) }}" class="brand-card-link">
    @endif
        <div class="brand-card">
            <div class="brand-logo">
                <img src="{{ asset('storage/image/brands/' . $brand['image']) }}"
                     alt="{{ $brand['name'] }}" 
                     class="img-fluid">
            </div>
            <h5 class="brand-name">{{ $brand['name'] }}</h5>
            <span class="brand-link-text">Lihat Produk <i class="bi bi-arrow-right"></i></span>
        </div>
    </a>
</div>
@endforeach
        </div>
    </div>
</section>
<!-- Paket Kamera Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Paket Kamera Analog & IP Camera</h2>
            <a href="#" class="btn btn-danger">
                <i class="bi bi-grid-3x3-gap me-2"></i>Paket Lainnya
            </a>
        </div>

        <div class="row g-4">
            @php
            $packages = [
                [
                    'channel' => '2',
                    'title' => '2MP 1080P HDCVI Camera',
                    'price' => '2.899.000',
                    'image' => '2+Camera',
                    'badge_class' => 'bg-primary',
                    'items' => [
                        'Jasa Pasang & Konfigurasi',
                        'Garansi Unit 2 Tahun',
                        '1 DVR 4 Channel',
                        '2 Camera Indoor/Outdoor 2MP',
                        '1 Hardisk 500GB'
                    ],
                    'brand' => 'dahua'
                ],
                [
                    'channel' => '4',
                    'title' => '2MP 1080P HDCVI Camera',
                    'price' => '4.699.000',
                    'image' => '4+Camera',
                    'badge_class' => 'bg-success',
                    'items' => [
                        'Jasa Pasang & Konfigurasi',
                        'Garansi Unit 2 Tahun',
                        '1 DVR 4 Channel',
                        '4 Camera Indoor/Outdoor 2MP',
                        '1 Hardisk 500GB'
                    ],
                    'brand' => 'hilook'
                ],
                [
                    'channel' => '8',
                    'title' => '2MP 1080P Color Hunter',
                    'price' => '8.799.000',
                    'image' => '8+Camera',
                    'badge_class' => 'bg-info',
                    'items' => [
                        'Jasa Pasang & Konfigurasi',
                        'Garansi Unit 2 Tahun',
                        '1 DVR 8 Channel',
                        '8 Camera Indoor/Outdoor 2MP',
                        '1 Hardisk 1 TB'
                    ],
                    'brand' => 'unv'
                ],
                [
                    'channel' => '16',
                    'title' => '2MP 1080P Network Camera',
                    'price' => '29.899.000',
                    'image' => '16+Camera',
                    'badge_class' => 'bg-danger',
                    'items' => [
                        'Jasa Pasang & Konfigurasi',
                        'Garansi Unit 2 Tahun',
                        '1 NVR 16 Channel',
                        '16 Camera Indoor/Outdoor 2MP',
                        '1 Hardisk 4 TB'
                    ],
                    'brand' => 'hikvision'
                ],
            ];
            @endphp

            @foreach($packages as $package)
            <div class="col-lg-3 col-md-6">
                <div class="package-card">
                    <div class="package-header">
                        <h5 class="package-channel">{{ $package['channel'] }} Channel</h5>
                        <p class="package-subtitle">{{ $package['title'] }}</p>
                    </div>
                    
                  <div class="package-image">
                    <img src="{{ asset('storage/image/packages/' . $package['channel'] . '-channel.png') }}" 
                    alt="{{ $package['channel'] }} Channel Package" 
                     class="img-fluid" 
                         onerror="this.src='https://via.placeholder.com/200x150/f5f5f5/666?text={{ $package['image'] }}'">
                        </div>
                    
                    <div class="package-price">
                        <span class="badge {{ $package['badge_class'] }}">
                            IDR. {{ number_format((float)str_replace(['.', ','], '', $package['price']), 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="package-features">
                        <p class="features-title">Sudah Termasuk:</p>
                        <ul class="features-list">
                            @foreach($package['items'] as $item)
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="package-footer">
                        <a href="{{ route('products.brand', $package['brand']) }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-cart-plus me-2"></i>Order Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- WiFi Camera Section -->
<section class="py-5">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Kamera CCTV WiFi</h2>
                <p class="section-description">
                    Kamera ini memungkinkan pengguna untuk memantau area yang dipilih secara langsung melalui 
                    perangkat yang terhubung ke jaringan yang sama, seperti smartphone, tablet, atau komputer.
                </p>
            </div>
            <a href="#" class="btn btn-danger">
                <i class="bi bi-grid me-2"></i>Produk Lainnya
            </a>
        </div>

        <div class="row g-4">
            @php
            $wifiCameras = [
                ['name' => 'C6N', 'price' => '1.065.000', 'desc' => '2MP 1080P Smart Pan & Tilt Indoor Wifi Camera', 'brand' => 'EZVIZ'],
                ['name' => 'H8C', 'price' => '1.699.000', 'desc' => '2MP 1080P Smart Wifi Pan & Tilt Outdoor Camera', 'brand' => 'EZVIZ'],
                ['name' => 'Ranger A1', 'price' => '1.329.000', 'desc' => '3MP 2K Smart Wifi Pan & Tilt Indoor Camera', 'brand' => 'IMOU'],
                ['name' => 'Cruiser SC', 'price' => '1.879.000', 'desc' => '5MP 3K Smart Home Pan & Tilt Outdoor Wifi Camera', 'brand' => 'IMOU'],
            ];
            @endphp

            @foreach($wifiCameras as $camera)
            <div class="col-lg-3 col-md-6">
                <div class="wifi-camera-card">
                    <div class="camera-image">
                        <img src="https://via.placeholder.com/200x200/ffffff/666?text={{ urlencode($camera['name']) }}" 
                             alt="{{ $camera['name'] }}" 
                             class="img-fluid">
                    </div>
                    
                    <div class="camera-content">
                        <h5 class="camera-name">{{ $camera['name'] }}</h5>
                        <p class="camera-price">Rp. {{ number_format((float)str_replace(['.', ','], '', $camera['price']), 0, ',', '.') }}</p>
                        <p class="camera-description">{{ $camera['desc'] }}</p>
                    </div>
                    
                    <div class="camera-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </button>
                        <span class="camera-brand">{{ $camera['brand'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Access Control Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Akses Kontrol</h2>
                <p class="section-description">
                    Kontrol Akses adalah sistem yang digunakan untuk mengatur dan membatasi akses fisik atau logis
                    ke suatu area, fasilitas, atau informasi.
                </p>
            </div>
            <a href="#" class="btn btn-danger">
                <i class="bi bi-grid me-2"></i>Produk Lainnya
            </a>
        </div>

        <div class="row g-4">
            @php
            $accessControls = [
                ['name' => 'MB20', 'price' => '1.032.000', 'desc' => 'Time Attendance & Access Control', 'brand' => 'ZKTeco'],
                ['name' => 'DS-K1T343MX', 'price' => '1.989.000', 'desc' => 'Access Control', 'brand' => 'HIKVISION'],
                ['name' => 'SF300', 'price' => '2.779.000', 'desc' => 'Time Attendance & Access Control', 'brand' => 'ZKTeco'],
                ['name' => 'LH6500', 'price' => '2.589.000', 'desc' => 'Hotel Lock', 'brand' => 'ZKTeco'],
            ];
            @endphp

            @foreach($accessControls as $device)
            <div class="col-lg-3 col-md-6">
                <div class="access-card">
                    <div class="access-image">
                        <img src="https://via.placeholder.com/200x200/f8f9fa/666?text={{ urlencode($device['name']) }}" 
                             alt="{{ $device['name'] }}" 
                             class="img-fluid">
                    </div>
                    
                    <div class="access-content">
                        <h5 class="access-name">{{ $device['name'] }}</h5>
                        <p class="access-price">Rp. {{ number_format((float)str_replace(['.', ','], '', $device['price']), 0, ',', '.') }}</p>
                        <p class="access-description">{{ $device['desc'] }}</p>
                    </div>
                    
                    <div class="access-footer">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </button>
                        <span class="access-brand">{{ $device['brand'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


     <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3 class="feature-title">Ultra HD 4K</h3>
            <p class="feature-desc">Rekaman super jernih dengan resolusi hingga 4K, tangkap setiap detail dengan sempurna</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🤖</div>
            <h3 class="feature-title">AI Detection</h3>
            <p class="feature-desc">Deteksi wajah & gerakan otomatis dengan teknologi kecerdasan buatan terkini</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">☁️</div>
            <h3 class="feature-title">Cloud Storage</h3>
            <p class="feature-desc">Akses rekaman dari mana saja, kapan saja dengan penyimpanan cloud yang aman</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3 class="feature-title">Mobile App</h3>
            <p class="feature-desc">Pantau properti Anda secara real-time melalui smartphone Android & iOS</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🌙</div>
            <h3 class="feature-title">Night Vision</h3>
            <p class="feature-desc">Penglihatan malam hingga 30 meter dengan teknologi infrared terbaru</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3 class="feature-title">Instalasi Cepat</h3>
            <p class="feature-desc">Tim profesional kami siap memasang dalam waktu 1 hari dengan garansi 2 tahun</p>
        </div>
    </div>

  
<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Melayani Instalasi</h2>
            <p class="section-description">Mulai Dari Rumah Pribadi Hingga Jaringan Besar</p>
        </div>

        <div class="row g-4">
            @php
            $services = [
                [
                    'icon' => '🏠',
                    'title' => 'Rumah',
                    'description' => 'CCTV membantu mencegah tindak kejahatan seperti pencurian atau perusakan karena keberadaannya sering menjadi penghalang psikologis bagi pelaku kriminal.'
                ],
                [
                    'icon' => '🔒',
                    'title' => 'Toko',
                    'description' => 'Kamera CCTV Mencegah pencurian oleh pelanggan atau karyawan, Mengawasi area stok atau gudang untuk menghindari kehilangan barang.'
                ],
                [
                    'icon' => '🏢',
                    'title' => 'Kantor',
                    'description' => 'Keberadaan CCTV membuat karyawan, pelanggan, dan pengunjung lebih berhati-hati dalam berperilaku dan membantu menjaga lingkungan kerja yang kondusif.'
                ],
            ];
            @endphp

            @foreach($services as $service)
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">{{ $service['icon'] }}</div>
                    <h4 class="service-title">{{ $service['title'] }}</h4>
                    <p class="service-description">{{ $service['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
 <div class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>5000+</h3>
                <p>Pelanggan Puas</p>
            </div>
            <div class="stat-item">
                <h3>99.9%</h3>
                <p>Uptime System</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Customer Support</p>
            </div>
            <div class="stat-item">
                <h3>2 Tahun</h3>
                <p>Garansi Resmi</p>
            </div>
        </div>
    </div>

<!-- Clients Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Klien Terbaik Kami</h2>
        
        <div class="row g-3">
            @php
            $clients = [
                'BCA', 'BRI', 'BNI', 'EPSON', 'MRT Jakarta', 'XL AXIATA', 
                'MNC', 'INKA', 'KAI', 'PAM JAYA', 'COMMUTER', 'INDOMARET', 
                'IKEA', 'HONDA', 'INDOSAT', 'PLN', 'JAKARTA SMART CITY', 'PERTAMINA'
            ];
            @endphp

            @foreach($clients as $client)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="client-card">
                    <strong>{{ $client }}</strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection