@extends('layouts.simple')

@section('title', 'TechStore - Home')

@section('content')
<style>
/* ===== NAVBAR STYLING ===== */
.navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.navbar-brand { font-weight: 600; font-size: 1.3rem; }
.nav-link { font-weight: 500; padding: 0.5rem 1rem !important; transition: all 0.3s ease; }
.nav-link:hover { color: #fff !important; background-color: rgba(255,255,255,0.1); border-radius: 5px; }
.nav-link.active { background-color: rgba(255,255,255,0.15); border-radius: 5px; }
.dropdown-menu { border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; margin-top: 0.5rem; }
.dropdown-item { padding: 0.6rem 1.2rem; transition: all 0.2s ease; }
.dropdown-item:hover { background-color: #f8f9fa; padding-left: 1.5rem; }
.dropdown-item i { opacity: 0.7; }

/* ===== HERO SECTION ===== */
.hero-section {
    background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)), url('{{ asset("storage/gambar/bg-cctv.jpg") }}');
    color: white; background-position: 50% 10%; background-size: 120%; background-repeat: no-repeat;
    padding: 80px 20px; text-align: center; margin: 0;
}
.hero-title { font-size: clamp(2rem, 5vw, 3rem); font-weight: 700; margin-bottom: 1rem; animation: fadeInUp 0.8s ease-out; }
.hero-subtitle { font-size: clamp(1rem, 2vw, 1.25rem); opacity: 0.95; animation: fadeInUp 0.8s ease-out 0.2s both; }

/* ===== SECTION COMMON ===== */
.section-title { font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 1rem; }
.section-description { color: #6c757d; font-size: 1rem; line-height: 1.6; }
.section-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; gap: 2rem; }

/* ===== BRAND CARDS ===== */
.brand-card-link { text-decoration: none; color: inherit; display: block; }
.brand-card { background: white; padding: 30px 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease; text-align: center; height: 100%; border: 2px solid transparent; }
.brand-card:hover { transform: translateY(-8px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); border-color: #007bff; }
.brand-logo { width: 180px; height: 180px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border-radius: 10px; padding: 15px; }
.brand-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }
.brand-name { font-size: 1.25rem; font-weight: 600; margin: 15px 0 10px; color: #333; }
.brand-link-text { color: #007bff; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: gap 0.3s ease; }
.brand-card:hover .brand-link-text { gap: 10px; }

/* ===== PACKAGE CARDS ===== */
.package-card { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; overflow: hidden; }
.package-card:hover { transform: translateY(-10px); box-shadow: 0 16px 32px rgba(0,0,0,0.15); }
.package-header { padding: 1.5rem 1.5rem 0; }
.package-channel { font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 0.25rem; }
.package-subtitle { color: #6c757d; font-size: 0.9rem; margin-bottom: 0; }
.package-image { padding: 0; text-align: center; display: flex; align-items: center; justify-content: center; background: #f8f9fa; width: 100%; height: 250px; overflow: hidden; margin: 15px 0; }
.package-image img { width: 100%; height: 100%; object-fit: cover; mix-blend-mode: multiply; }
.package-price { text-align: center; padding: 0 1.5rem 1rem; }
.package-price .badge { font-size: 1rem; padding: 0.5rem 1.25rem; font-weight: 600; }
.package-features { padding: 0 1.5rem 1rem; flex-grow: 1; }
.features-title { font-weight: 600; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; }
.features-list { list-style: none; padding: 0; margin: 0; }
.features-list li { display: flex; align-items: flex-start; padding: 0.4rem 0; font-size: 0.875rem; color: #555; }
.features-list i { color: #28a745; font-size: 1rem; margin-right: 0.5rem; flex-shrink: 0; margin-top: 0.1rem; }
.package-footer { padding: 1.5rem; border-top: 1px solid #f0f0f0; }

/* ===== WIFI CAMERA & ACCESS CONTROL CARDS ===== */
.wifi-camera-card, .access-card { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; overflow: hidden; }
.wifi-camera-card:hover, .access-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
.camera-image, .access-image { background: #f8f9fa; padding: 1.5rem; text-align: center; min-height: 240px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.camera-content, .access-content { padding: 1.5rem; text-align: center; }
.camera-name, .access-name { font-size: 1.25rem; font-weight: 700; color: #333; margin-bottom: 0.5rem; }
.camera-price, .access-price { color: #dc3545; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; }
.camera-description, .access-description { color: #6c757d; font-size: 0.875rem; line-height: 1.5; margin-bottom: 0; }
.camera-footer, .access-footer { padding: 0 1.5rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
.camera-brand, .access-brand { font-size: 0.75rem; font-weight: 600; color: #6c757d; text-transform: uppercase; }

/* ===== TESTIMONI / HASIL INSTALASI CARDS ===== */
.testimoni-card { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; position: relative; cursor: pointer; background: #000; }
.testimoni-card:hover { transform: translateY(-8px); box-shadow: 0 16px 32px rgba(0,0,0,0.2); }
.testimoni-image { width: 100%; height: 260px; object-fit: cover; transition: transform 0.5s ease; opacity: 0.9; }
.testimoni-card:hover .testimoni-image { transform: scale(1.08); opacity: 1; }
.testimoni-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.6), transparent); color: white; padding: 30px 20px 15px; }
.testimoni-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 5px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5); }
.testimoni-location { font-size: 0.85rem; color: #e2e8f0; display: flex; align-items: center; gap: 5px; }

/* ===== SERVICE CARDS ===== */
.service-card { background: white; border-radius: 12px; padding: 2.5rem 2rem; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease; height: 100%; }
.service-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
.service-icon { font-size: 4rem; margin-bottom: 1.5rem; }
.service-title { font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 1rem; }

/* ===== GENERAL UTILITIES ===== */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.features-grid { max-width: 1400px; margin: 0 auto 0; padding: 4rem 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; }
.feature-card { background: #f8f9fa; padding: 2rem 1.5rem; border-radius: 16px; border: 1px solid rgba(0,0,0,0.1); transition: all 0.4s ease; text-align: center;}
.feature-card:hover { transform: translateY(-8px); border-color: rgba(102, 126, 234, 0.4); box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3); }
.feature-icon { width: 64px; height: 64px; background: linear-gradient(135deg, #7c7fdb, #9d84d6); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; color: white;}
.stats { padding: 5rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; }
.stat-item h3 { font-size: 4rem; font-weight: 800; margin-bottom: 0.5rem; }

@media (max-width: 991.98px) {
    .section-header { flex-direction: column; align-items: flex-start; }
    .hero-section { padding: 60px 20px; background-size: 1200% auto; }
}
</style>

<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Selamat Datang di TechStore</h1>
        <p class="hero-subtitle">Temukan produk teknologi CCTV terbaik dengan harga terjangkau</p>
    </div>
</section>

<section class="py-5 bg-white" id="brands">
    <div class="container">
        <h2 class="section-title text-center mb-5">Brand Partner Kami</h2>
        <div class="row g-4">
            @foreach($brands as $brand)
            <div class="col-lg-3 col-md-4 col-sm-6">
                @if($brand['slug'] === 'ezviz')
                    <a href="{{ url('/wifi-cam') }}" class="brand-card-link">
                @else
                    <a href="{{ route('products.brand', $brand['slug']) }}" class="brand-card-link">
                @endif
                    <div class="brand-card">
                        <div class="brand-logo">
                            <img src="{{ asset('storage/image/brands/' . $brand['image']) }}" alt="{{ $brand['name'] }}" class="img-fluid" style="mix-blend-mode: multiply;">
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

<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Paket Kamera Analog & IP Camera</h2>
            <a href="#" class="btn btn-danger"><i class="bi bi-grid-3x3-gap me-2"></i>Paket Lainnya</a>
        </div>

        <div class="row g-4">
            @foreach($packages as $package)
            <div class="col-lg-3 col-md-6">
                <div class="package-card d-flex flex-column h-100">
                    <a href="{{ route('products.brand', $package['brand']) }}" class="text-decoration-none" style="color: inherit; display: flex; flex-direction: column; flex-grow: 1;">
                        <div class="package-header">
                            <h5 class="package-channel">{{ $package['channel'] }} Channel</h5>
                            <p class="package-subtitle">{{ $package['title'] }}</p>
                        </div>
                        <div class="package-image">
                            <img src="{{ asset($package['image']) }}" alt="{{ $package['channel'] }} Channel" class="img-fluid" style="mix-blend-mode: multiply;">
                        </div>
                        <div class="package-price">
                            <span class="badge {{ $package['badge_class'] }}">
                                IDR. {{ number_format((float)$package['price'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="package-features flex-grow-1">
                            <p class="features-title">Sudah Termasuk:</p>
                            <ul class="features-list">
                                @foreach($package['items'] as $item)
                                <li><i class="bi bi-check-circle-fill"></i><span>{{ $item }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </a>
                    <div class="package-footer mt-auto position-relative" style="z-index: 2;">
                        @php
                            $pesanWa = "Halo, saya tertarik dengan produk Paket CCTV " . ucfirst($package['brand']) . " " . $package['channel'] . " Channel - " . $package['title'];
                        @endphp
                        <a href="https://wa.me/62881025756671?text={{ urlencode($pesanWa) }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="bi bi-whatsapp me-2"></i>Order Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Kamera CCTV WiFi</h2>
                <p class="section-description">Kamera ini memungkinkan pengguna memantau langsung melalui smartphone, tablet, atau komputer.</p>
            </div>
            <a href="/wifi-cam" class="btn btn-danger"><i class="bi bi-grid me-2"></i>Produk Lainnya</a>
        </div>

        <div class="row g-4">
            @foreach($wifiCameras as $camera)
            <div class="col-lg-3 col-md-6">
                <div class="wifi-camera-card">
                    <div class="camera-image">
                        <img src="{{ asset($camera['image']) }}" alt="{{ $camera['name'] }}" class="img-fluid" style="max-height: 220px; object-fit: contain; mix-blend-mode: multiply; {{ $camera['img_style'] ?? '' }}">
                    </div>
                    <div class="camera-content">
                        <h5 class="camera-name">{{ $camera['name'] }}</h5>
                        <p class="camera-price">Rp. {{ number_format((float)$camera['price'], 0, ',', '.') }}</p>
                        <p class="camera-description">{{ $camera['desc'] }}</p>
                    </div>
                    <div class="camera-footer">
                        <a href="{{ route('wifi-cam.detail', $camera['slug']) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        <span class="camera-brand">{{ $camera['brand'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Akses Kontrol</h2>
                <p class="section-description">Sistem yang digunakan untuk mengatur dan membatasi akses ke suatu area atau fasilitas.</p>
            </div>
            <a href="/access-control" class="btn btn-danger"><i class="bi bi-grid me-2"></i>Produk Lainnya</a>
        </div>

        <div class="row g-4">
            @foreach($accessControls as $device)
            <div class="col-lg-3 col-md-6">
                <div class="access-card">
                    <div class="access-image">
                        <img src="{{ isset($device['image']) ? asset($device['image']) : 'https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' }}" 
                             alt="{{ $device['name'] }}" 
                             class="img-fluid" 
                             style="max-height: 200px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <div class="access-content">
                        <h5 class="access-name">{{ $device['name'] }}</h5>
                        <p class="access-price">Rp. {{ number_format((float)$device['price'], 0, ',', '.') }}</p>
                        <p class="access-description">{{ $device['desc'] }}</p>
                    </div>
                    <div class="access-footer">
                        <a href="{{ route('access-control.detail', $device['id']) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        <span class="access-brand">{{ $device['brand'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Hasil Instalasi & Testimoni</h2>
            <p class="section-description mx-auto" style="max-width: 600px;">Intip beberapa hasil nyata pemasangan kamera CCTV dan sistem keamanan oleh teknisi profesional kami.</p>
        </div>

        <div class="row g-4">
            {{-- Mengambil data dari variabel $testimonials yang dilempar dari Controller --}}
            @forelse($testimonials ?? [] as $testimoni)
            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card">
                    <img src="{{ asset('storage/' . $testimoni['image']) }}" alt="{{ $testimoni['title'] }}" class="testimoni-image">
                    <div class="testimoni-overlay">
                        <h5 class="testimoni-title">{{ $testimoni['title'] }}</h5>
                        @if(isset($testimoni['location']))
                        <span class="testimoni-location"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $testimoni['location'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            {{-- TAMPILAN CONTOH (DUMMY) JIKA DATA BACKEND MASIH KOSONG --}}
            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card">
                    <img src="{{ asset('storage/gambar/contoh-testimoni1.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1557597774-9d273605dfa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80'" alt="Instalasi 8 Channel" class="testimoni-image">
                    <div class="testimoni-overlay">
                        <h5 class="testimoni-title">Instalasi Dahua 8 Channel</h5>
                        <span class="testimoni-location"><i class="bi bi-geo-alt-fill text-danger"></i> Perumahan Cluster, Jakarta</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card">
                    <img src="https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Instalasi Pabrik" class="testimoni-image">
                    <div class="testimoni-overlay">
                        <h5 class="testimoni-title">Sistem Akses Kontrol & CCTV</h5>
                        <span class="testimoni-location"><i class="bi bi-geo-alt-fill text-danger"></i> Kawasan Industri Cikarang</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Instalasi Toko" class="testimoni-image">
                    <div class="testimoni-overlay">
                        <h5 class="testimoni-title">Instalasi IP Camera 4 Titik</h5>
                        <span class="testimoni-location"><i class="bi bi-geo-alt-fill text-danger"></i> Minimarket, Bekasi Raya</span>
                    </div>
                </div>
            </div>
            @endforelse
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

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Melayani Instalasi</h2>
            <p class="section-description">Mulai Dari Rumah Pribadi Hingga Jaringan Besar</p>
        </div>

        <div class="row g-4">
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
        <div class="stat-item"><h3>5000+</h3><p>Pelanggan Puas</p></div>
        <div class="stat-item"><h3>99.9%</h3><p>Uptime System</p></div>
        <div class="stat-item"><h3>24/7</h3><p>Customer Support</p></div>
        <div class="stat-item"><h3>2 Tahun</h3><p>Garansi Resmi</p></div>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="section-title mb-5">Klien Terbaik Kami</h2>
        <img src="{{ asset('storage/gambar/desktop_client.png') }}" alt="Brand Logos Desktop" class="img-fluid d-none d-md-inline">
        <img src="{{ asset('storage/gambar/mobile_client.png') }}" alt="Brand Logos Mobile" class="img-fluid d-inline d-md-none">
    </div>
</section>

{{-- ⭐ AI Assistant --}}
<x-ai-assistant />
@endsection