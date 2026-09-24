@extends('layouts.simple')

@section('title', 'TechStore - Solusi Pengadaan & Jasa Pasang CCTV Profesional')
@section('meta_description', 'PT. MJA TEKNOLOGI melayani jasa pasang paket CCTV Hikvision, Dahua, HiLook, UNV, WiFi Cam, dan Akses Kontrol bergaransi resmi 2 tahun dengan teknisi berpengalaman.')

@section('content')
<style>
/* ===== DESIGN SYSTEM & CORPORATE FOUNDATION ===== */
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--ts-primary);
    background-color: var(--ts-primary-light);
    border: 1px solid rgba(220, 38, 38, 0.2);
    padding: 5px 12px;
    border-radius: 4px;
    margin-bottom: 12px;
}

.section-heading {
    font-size: clamp(1.75rem, 3vw, 2.3rem);
    font-weight: 800;
    color: var(--ts-slate-900);
    letter-spacing: -0.02em;
    margin-bottom: 10px;
    line-height: 1.25;
}

.section-subheading {
    color: var(--ts-slate-600);
    font-size: clamp(0.95rem, 1.4vw, 1.05rem);
    line-height: 1.6;
    max-width: 680px;
    margin-bottom: 0;
}

/* ===== HERO SECTION ===== */
.hero-corporate {
    background-color: #0f172a;
    color: #ffffff;
    padding: 80px 20px 75px;
    position: relative;
    border-bottom: 1px solid var(--ts-slate-800);
}

.hero-corp-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fca5a5;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 4px;
    margin-bottom: 22px;
    letter-spacing: 0.5px;
}

.hero-corp-title {
    font-size: clamp(2.2rem, 4.8vw, 3.4rem);
    font-weight: 800;
    letter-spacing: -0.025em;
    line-height: 1.18;
    margin-bottom: 18px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
    color: #ffffff;
}

.hero-corp-title span.highlight {
    color: #f87171;
}

.hero-corp-desc {
    font-size: clamp(1rem, 1.8vw, 1.15rem);
    color: #cbd5e1;
    max-width: 740px;
    margin: 0 auto 34px;
    line-height: 1.65;
    font-weight: 400;
}

.hero-corp-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 0;
}

.btn-corp-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: var(--ts-primary);
    color: #ffffff;
    font-size: 14.5px;
    font-weight: 700;
    padding: 13px 28px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-corp-primary:hover {
    background-color: var(--ts-primary-dark);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35);
}

.btn-corp-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-size: 14.5px;
    font-weight: 600;
    padding: 13px 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-corp-secondary:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: #ffffff;
    color: #ffffff;
}

/* ===== BRAND STRIP SECTION (STREAMLINED PLACEMENT) ===== */
.brands-strip-section {
    background-color: #ffffff;
    padding: 36px 0;
    border-bottom: 1px solid var(--ts-slate-200);
}

.brands-grid-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 14px;
    align-items: center;
}

.brand-strip-card {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 8px;
    padding: 14px 12px;
    text-align: center;
    height: 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
}

.brand-strip-card:hover {
    border-color: var(--ts-slate-700);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    transform: translateY(-2px);
}

.brand-strip-card img {
    max-width: 90%;
    max-height: 40px;
    object-fit: contain;
    margin-bottom: 6px;
}

.brand-strip-name {
    font-size: 11px;
    font-weight: 700;
    color: var(--ts-slate-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== PAKET KAMERA ANALOG & IP (PRICING & SPECS) ===== */
.packages-section {
    background-color: var(--ts-slate-50);
    padding: 70px 0;
}

.package-card-corp {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 12px;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: all 0.25s ease;
}

.package-card-corp:hover {
    border-color: var(--ts-slate-700);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    transform: translateY(-4px);
}

.package-card-corp.popular {
    border: 2px solid var(--ts-primary);
    box-shadow: 0 8px 24px rgba(220, 38, 38, 0.08);
}

.popular-badge {
    position: absolute;
    top: -12px;
    right: 20px;
    background-color: var(--ts-primary);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 12px;
    border-radius: 4px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.package-corp-header {
    padding: 24px 22px 14px;
    border-bottom: 1px solid var(--ts-slate-100);
}

.package-corp-channel {
    font-size: 12px;
    font-weight: 700;
    color: var(--ts-slate-600);
    background: var(--ts-slate-100);
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 10px;
}

.package-card-corp.popular .package-corp-channel {
    background: var(--ts-primary-light);
    color: var(--ts-primary);
}

.package-corp-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--ts-slate-900);
    margin-bottom: 4px;
}

.package-corp-sub {
    font-size: 13px;
    color: var(--ts-slate-500);
    margin: 0;
}

.package-corp-img-box {
    background-color: var(--ts-slate-50);
    height: 180px;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--ts-slate-100);
}

.package-corp-img-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.package-corp-price-box {
    padding: 18px 22px 14px;
    background-color: #ffffff;
    border-bottom: 1px solid var(--ts-slate-100);
}

.price-label-corp {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ts-slate-500);
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.price-value-corp {
    font-size: 23px;
    font-weight: 800;
    color: var(--ts-slate-900);
    line-height: 1.1;
}

.price-note-corp {
    font-size: 12px;
    font-weight: 600;
    color: var(--ts-green);
    margin-top: 4px;
}

.package-corp-items {
    padding: 18px 22px;
    flex-grow: 1;
}

.items-label-corp {
    font-size: 12px;
    font-weight: 700;
    color: var(--ts-slate-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.items-list-corp {
    list-style: none;
    padding: 0;
    margin: 0;
}

.items-list-corp li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 13px;
    color: var(--ts-slate-600);
    padding: 4px 0;
    line-height: 1.4;
}

.items-list-corp li i {
    color: var(--ts-green);
    font-size: 14px;
    margin-top: 2px;
    flex-shrink: 0;
}

.package-corp-footer {
    padding: 16px 22px 22px;
    margin-top: auto;
}

.btn-order-corp {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 11px 16px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    background-color: var(--ts-slate-900);
    color: #ffffff;
    transition: all 0.2s ease;
}

.btn-order-corp:hover {
    background-color: var(--ts-primary);
    color: #ffffff;
}

.package-card-corp.popular .btn-order-corp {
    background-color: var(--ts-primary);
}

.package-card-corp.popular .btn-order-corp:hover {
    background-color: var(--ts-primary-dark);
}

/* ===== ALUR PEMESANAN 4 LANGKAH (NEW UX PLACEMENT) ===== */
.workflow-section {
    background-color: #ffffff;
    padding: 65px 0;
    border-top: 1px solid var(--ts-slate-200);
    border-bottom: 1px solid var(--ts-slate-200);
}

.workflow-card {
    background: var(--ts-slate-50);
    border: 1px solid var(--ts-slate-200);
    border-radius: 10px;
    padding: 24px 20px;
    height: 100%;
    position: relative;
}

.workflow-num {
    font-size: 13px;
    font-weight: 800;
    color: var(--ts-primary);
    background-color: var(--ts-primary-light);
    display: inline-block;
    padding: 3px 10px;
    border-radius: 4px;
    margin-bottom: 12px;
}

.workflow-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 8px;
}

.workflow-desc {
    font-size: 13px;
    color: var(--ts-slate-600);
    line-height: 1.55;
    margin: 0;
}

/* ===== PRODUCT CARDS (WIFI CAM & ACCESS CONTROL) ===== */
.product-card-corp {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.25s ease;
}

.product-card-corp:hover {
    border-color: var(--ts-slate-700);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    transform: translateY(-3px);
}

.prod-img-box-corp {
    background-color: var(--ts-slate-50);
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    border-bottom: 1px solid var(--ts-slate-100);
}

.prod-img-box-corp img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.prod-brand-pill {
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

.prod-body-corp {
    padding: 18px 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.prod-title-corp {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 6px;
    line-height: 1.35;
    min-height: 42px;
}

.prod-price-corp {
    font-size: 18px;
    font-weight: 800;
    color: var(--ts-primary);
    margin-bottom: 8px;
}

.prod-desc-corp {
    font-size: 12.5px;
    color: var(--ts-slate-500);
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prod-actions-corp {
    margin-top: auto;
    display: flex;
    gap: 8px;
}

.btn-prod-detail {
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
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-prod-detail:hover {
    background-color: var(--ts-slate-200);
    color: var(--ts-slate-900);
}

.btn-prod-buy {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: var(--ts-green);
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 12px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-prod-buy:hover {
    background-color: var(--ts-green-dark);
    color: #ffffff;
}

/* ===== TESTIMONI & PORTOFOLIO ===== */
.testimoni-card-corp {
    border-radius: 12px;
    overflow: hidden;
    height: 250px;
    position: relative;
    border: 1px solid var(--ts-slate-200);
    background-color: var(--ts-slate-900);
}

.testimoni-card-corp img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.85;
    transition: transform 0.4s ease;
}

.testimoni-card-corp:hover img {
    transform: scale(1.05);
    opacity: 1;
}

.testimoni-overlay-corp {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 65%, transparent 100%);
    padding: 20px 18px 14px;
    color: #ffffff;
}

.testimoni-tag-corp {
    font-size: 10.5px;
    font-weight: 700;
    color: #86efac;
    text-transform: uppercase;
    margin-bottom: 4px;
    display: inline-block;
}

.testimoni-title-corp {
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 2px;
}

.testimoni-loc-corp {
    font-size: 12px;
    color: #cbd5e1;
}

/* ===== TECHNICAL STANDARDS ===== */
.keunggulan-card {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 12px;
    padding: 28px 24px;
    height: 100%;
    transition: all 0.25s ease;
}

.keunggulan-card:hover {
    border-color: var(--ts-slate-400);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
    transform: translateY(-3px);
}

.keunggulan-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background-color: var(--ts-slate-100);
    color: var(--ts-slate-800);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
    border: 1px solid var(--ts-slate-200);
}

.keunggulan-card:hover .keunggulan-icon {
    background-color: var(--ts-primary-light);
    color: var(--ts-primary);
    border-color: rgba(220, 38, 38, 0.2);
}

.keunggulan-title {
    font-size: 16.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 8px;
}

.keunggulan-desc {
    font-size: 13.5px;
    color: var(--ts-slate-600);
    line-height: 1.6;
    margin: 0;
}

/* ===== SECTOR CARDS ===== */
.sector-card-corp {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 12px;
    padding: 32px 24px;
    text-align: center;
    height: 100%;
    transition: all 0.25s ease;
}

.sector-card-corp:hover {
    border-color: var(--ts-slate-400);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
}

.sector-icon-corp {
    font-size: 42px;
    margin-bottom: 16px;
    display: inline-block;
}

.sector-title-corp {
    font-size: 18px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 8px;
}

.sector-desc-corp {
    font-size: 13.5px;
    color: var(--ts-slate-600);
    line-height: 1.6;
    margin: 0;
}

/* ===== STATS STRIP ===== */
.stats-strip-corp {
    background-color: #0f172a;
    color: #ffffff;
    border-radius: 12px;
    padding: 45px 20px;
    margin: 30px auto;
    max-width: 1320px;
}

.stat-item-corp {
    text-align: center;
    padding: 10px;
}

.stat-num-corp {
    font-size: clamp(2rem, 3.5vw, 2.8rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
    margin-bottom: 6px;
}

.stat-text-corp {
    font-size: 13px;
    color: #94a3b8;
    font-weight: 600;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ===== CTA BANNER ===== */
.cta-banner-corp {
    background-color: #0f172a;
    color: #ffffff;
    border-radius: 14px;
    padding: 50px 40px;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

@media (max-width: 768px) {
    .cta-banner-corp {
        padding: 30px 20px;
        text-align: center;
    }
}
</style>

<!-- 1. HERO SECTION (PROFESIONAL & CORPORATE) -->
<section class="hero-corporate text-center">
    <div class="container">
        <div class="hero-corp-chip">
            <i class="bi bi-shield-check text-danger me-1"></i> PT. MJA TEKNOLOGI • Kontraktor & Distributor Sistem CCTV
        </div>
        
        <h1 class="hero-corp-title">
            Spesialis Pengadaan & Jasa Pasang CCTV <span class="highlight">Bergaransi Resmi</span>
        </h1>
        
        <p class="hero-corp-desc">
            Melayani instalasi kamera CCTV analog, IP Camera, WiFi Camera, dan Akses Kontrol untuk rumah, perkantoran, ruko, serta kawasan industri dengan teknisi bersertifikat dan jaminan pengerjaan rapi.
        </p>

        <div class="hero-corp-actions">
            <a href="#packages" class="btn-corp-primary">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Lihat Paket Pemasangan</span>
            </a>
            @php
                $waHeroMsg = "Halo PT. MJA TEKNOLOGI (TechStore), saya ingin konsultasi kebutuhan pasang CCTV untuk properti saya.";
            @endphp
            <a href="https://wa.me/62881025756671?text={{ urlencode($waHeroMsg) }}" target="_blank" class="btn-corp-secondary">
                <i class="bi bi-whatsapp text-success"></i>
                <span>Konsultasi Teknis via WhatsApp</span>
            </a>
        </div>
    </div>
</section>

<!-- 2. STREAMLINED BRAND STRIP (OPTIMIZED PLACEMENT) -->
<section class="brands-strip-section" id="brands">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="fw-bold text-uppercase small text-secondary">
                <i class="bi bi-patch-check-fill text-danger me-1"></i> Authorized Brand Partners
            </div>
            <span class="text-muted small d-none d-md-inline">Pilih brand untuk melihat spesifikasi produk & katalog resmi</span>
        </div>

        <div class="brands-grid-strip">
            @foreach($brands as $brand)
                @if($brand['slug'] === 'ezviz')
                    <a href="{{ url('/wifi-cam') }}" class="brand-strip-card" title="Lihat Produk {{ $brand['name'] }}">
                @else
                    <a href="{{ route('products.brand', $brand['slug']) }}" class="brand-strip-card" title="Lihat Produk {{ $brand['name'] }}">
                @endif
                    <img src="{{ asset('storage/image/brands/' . $brand['image']) }}" 
                         alt="{{ $brand['name'] }}" 
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/140x50?text={{ urlencode($brand['name']) }}';">
                    <span class="brand-strip-name">{{ $brand['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. PAKET INSTALASI CCTV ANALOG & IP CAMERA (CORE PRODUCTS) -->
<section class="packages-section" id="packages">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <span class="section-tag">Paket Pemasangan Siap Pakai</span>
                <h2 class="section-heading">Paket Pemasangan CCTV Komplit</h2>
                <p class="section-subheading">Sudah mencakup unit kamera resolusi tajam, perekam DVR/NVR, harddisk surveillance, kabel coaxial, konektor, hingga jasa pasang profesional.</p>
            </div>
            <a href="{{ url('/products/all') }}" class="btn btn-outline-dark px-4 py-2 rounded-2 fw-semibold text-nowrap" style="font-size: 13.5px;">
                <i class="bi bi-grid-3x3-gap me-2"></i>Katalog Semua Brand
            </a>
        </div>

        <div class="row g-4 align-items-stretch">
            @foreach($packages as $package)
            @php
                $isPopular = ($package['channel'] == '4');
            @endphp
            <div class="col-xl-3 col-lg-6 col-md-6 d-flex">
                <div class="package-card-corp {{ $isPopular ? 'popular' : '' }} w-100">
                    @if($isPopular)
                        <div class="popular-badge">Paling Banyak Dipilih</div>
                    @endif

                    <div class="package-corp-header">
                        <span class="package-corp-channel">{{ $package['channel'] }} Channel System</span>
                        <div class="package-corp-title">{{ $package['channel'] }} Kamera CCTV</div>
                        <p class="package-corp-sub">{{ $package['title'] }}</p>
                    </div>

                    <div class="package-corp-img-box">
                        <img src="{{ asset($package['image']) }}" 
                             alt="{{ $package['channel'] }} Channel" 
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Paket+CCTV';">
                    </div>

                    <div class="package-corp-price-box">
                        <div class="price-label-corp">Harga Paket All-In</div>
                        <div class="price-value-corp">
                            Rp {{ number_format((float)$package['price'], 0, ',', '.') }}
                        </div>
                        <div class="price-note-corp">
                            <i class="bi bi-check-circle-fill"></i> Sudah Termasuk Jasa Pasang & Setting
                        </div>
                    </div>

                    <div class="package-corp-items">
                        <div class="items-label-corp">Kelengkapan Paket:</div>
                        <ul class="items-list-corp">
                            @foreach($package['items'] as $item)
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="package-corp-footer">
                        @php
                            $pesanWa = "Halo TechStore, saya berminat memesan Paket CCTV " . ucfirst($package['brand']) . " " . $package['channel'] . " Channel (" . $package['title'] . ") seharga Rp " . number_format((float)$package['price'], 0, ',', '.');
                        @endphp
                        <a href="https://wa.me/62881025756671?text={{ urlencode($pesanWa) }}" target="_blank" class="btn-order-corp">
                            <i class="bi bi-whatsapp"></i>
                            <span>Pesan Paket via WA</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. ALUR & CARA PEMASANGAN (ENHANCED UX TRUST) -->
<section class="workflow-section">
    <div class="container">
        <div class="text-center mb-4">
            <span class="section-tag">Proses Pengerjaan</span>
            <h2 class="section-heading">4 Langkah Mudah Pasang CCTV di TechStore</h2>
            <p class="section-subheading mx-auto">Kami memastikan proses pemesanan hingga instalasi berjalan cepat, transparan, dan tanpa kerepotan bagi Anda.</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="workflow-card">
                    <span class="workflow-num">Langkah 1</span>
                    <h3 class="workflow-title">Konsultasi & Survey</h3>
                    <p class="workflow-desc">Diskusikan kebutuhan jumlah kamera dan denah lokasi bersama tim teknisi kami secara gratis.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="workflow-card">
                    <span class="workflow-num">Langkah 2</span>
                    <h3 class="workflow-title">Estimasi Penawaran</h3>
                    <p class="workflow-desc">Kami kirimkan rincian spesifikasi perangkat dan surat penawaran harga resmi tanpa biaya siluman.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="workflow-card">
                    <span class="workflow-num">Langkah 3</span>
                    <h3 class="workflow-title">Instalasi Rapi</h3>
                    <p class="workflow-desc">Teknisi bersertifikat datang memasang unit dengan proteksi pipa conduit sesuai standar keselamatan.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="workflow-card">
                    <span class="workflow-num">Langkah 4</span>
                    <h3 class="workflow-title">Setting HP & Garansi</h3>
                    <p class="workflow-desc">Konfigurasi live streaming di smartphone Anda, uji rekaman, dan penyerahan kartu garansi resmi 2 tahun.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. STANDAR MUTU & KEUNGGULAN PENGERJAAN -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="text-center mb-4">
            <span class="section-tag">Standar Mutu Pengerjaan</span>
            <h2 class="section-heading">Keunggulan Layanan PT. MJA TEKNOLOGI</h2>
            <p class="section-subheading mx-auto">Kami mengedepankan kualitas teknis dan ketahanan sistem pengawasan untuk kepuasan jangka panjang.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="keunggulan-title">Standar Pipa Conduit Rapi</div>
                    <p class="keunggulan-desc">Seluruh tarikan kabel dilindungi pipa conduit PVC berkualitas untuk mencegah gigitan hewan pengerat, kerusakan cuaca, dan risiko bahaya korsleting.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="keunggulan-title">Setting Online Smartphone</div>
                    <p class="keunggulan-desc">Teknisi kami mengonfigurasi aplikasi pemantauan di HP Anda hingga siap pakai, memudahkan pemantauan live streaming dari luar kota tanpa biaya bulanan.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-camera-video"></i>
                    </div>
                    <div class="keunggulan-title">Kamera Resolusi HD & 4K</div>
                    <p class="keunggulan-desc">Menggunakan sensor optik berstandar industri dengan output gambar tajam, mempermudah identifikasi wajah pelaku dan nomor plat kendaraan dengan jelas.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-moon-stars"></i>
                    </div>
                    <div class="keunggulan-title">Night Vision & Full-Color</div>
                    <p class="keunggulan-desc">Teknologi sensor sensitif cahaya dan spotlight internal memungkinkan rekaman tetap berwarna dan terang saat malam hari dalam kondisi minim pencahayaan.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="keunggulan-title">Garansi Resmi Penuh 2 Tahun</div>
                    <p class="keunggulan-desc">Jaminan unit kamera dan DVR/NVR asli dari prinsipal dengan layanan klaim garansi servis cepat dan penggantian unit jika terjadi kerusakan pabrik.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="keunggulan-card">
                    <div class="keunggulan-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div class="keunggulan-title">Legalitas Resmi Perusahaan</div>
                    <p class="keunggulan-desc">Didukung oleh PT. MJA TEKNOLOGI dengan kelengkapan faktur pajak, surat penawaran resmi, dan invoice terstandarisasi untuk instansi maupun korporat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. KAMERA CCTV WIFI NIRKABEL -->
<section class="py-5" style="background-color: var(--ts-slate-50);" id="wifi-cam">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <span class="section-tag">Kamera Pintar Nirkabel</span>
                <h2 class="section-heading">Kamera CCTV WiFi & Smart Home</h2>
                <p class="section-subheading">Kamera nirkabel praktis untuk pemantauan mandiri via smartphone, dilengkapi rotasi 360 derajat, audio dua arah, dan deteksi gerak otomatis.</p>
            </div>
            <a href="{{ url('/wifi-cam') }}" class="btn btn-outline-dark px-4 py-2 rounded-2 fw-semibold text-nowrap" style="font-size: 13.5px;">
                <i class="bi bi-arrow-right-circle me-1"></i> Lihat Semua WiFi Cam
            </a>
        </div>

        <div class="row g-4 align-items-stretch">
            @foreach($wifiCameras as $camera)
            <div class="col-xl-3 col-lg-6 col-md-6 d-flex">
                <div class="product-card-corp w-100">
                    <div class="prod-img-box-corp">
                        <span class="prod-brand-pill">{{ $camera['brand'] }}</span>
                        <img src="{{ asset($camera['image']) }}" 
                             alt="{{ $camera['name'] }}" 
                             style="{{ $camera['img_style'] ?? '' }}"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/260x200?text={{ urlencode($camera['name']) }}';">
                    </div>
                    <div class="prod-body-corp">
                        <div class="prod-title-corp">{{ $camera['name'] }}</div>
                        <div class="prod-price-corp">
                            Rp {{ number_format((float)$camera['price'], 0, ',', '.') }}
                        </div>
                        <p class="prod-desc-corp">{{ $camera['desc'] }}</p>
                        
                        <div class="prod-actions-corp">
                            <a href="{{ route('wifi-cam.detail', $camera['slug']) }}" class="btn-prod-detail">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                            @php
                                $waCamMsg = "Halo TechStore, saya tertarik dengan CCTV WiFi: " . $camera['name'] . " (Rp " . number_format((float)$camera['price'], 0, ',', '.') . ")";
                            @endphp
                            <a href="https://wa.me/62881025756671?text={{ urlencode($waCamMsg) }}" target="_blank" class="btn-prod-buy">
                                <i class="bi bi-whatsapp"></i> Beli
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 7. AKSES KONTROL PINTU SECTION -->
<section class="py-5 bg-white" id="access-control">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <span class="section-tag">Security & Attendance System</span>
                <h2 class="section-heading">Sistem Akses Kontrol & Mesin Absensi</h2>
                <p class="section-subheading">Kontrol akses masuk pintu ruangan dan fasilitas kantor dengan verifikasi biometrik sidik jari, face recognition, dan kartu RFID presisi.</p>
            </div>
            <a href="{{ url('/access-control') }}" class="btn btn-outline-dark px-4 py-2 rounded-2 fw-semibold text-nowrap" style="font-size: 13.5px;">
                <i class="bi bi-arrow-right-circle me-1"></i> Lihat Semua Akses Kontrol
            </a>
        </div>

        <div class="row g-4 align-items-stretch">
            @foreach($accessControls as $device)
            <div class="col-xl-3 col-lg-6 col-md-6 d-flex">
                <div class="product-card-corp w-100">
                    <div class="prod-img-box-corp">
                        <span class="prod-brand-pill">{{ $device['brand'] }}</span>
                        <img src="{{ isset($device['image']) ? asset($device['image']) : 'https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' }}" 
                             alt="{{ $device['name'] }}"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';">
                    </div>
                    <div class="prod-body-corp">
                        <div class="prod-title-corp">{{ $device['name'] }}</div>
                        <div class="prod-price-corp">
                            Rp {{ number_format((float)$device['price'], 0, ',', '.') }}
                        </div>
                        <p class="prod-desc-corp">{{ $device['desc'] }}</p>
                        
                        <div class="prod-actions-corp">
                            <a href="{{ route('access-control.detail', $device['id']) }}" class="btn-prod-detail">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                            @php
                                $waAccessMsg = "Halo TechStore, saya tertarik dengan produk Access Control " . $device['brand'] . " " . $device['name'] . " (Rp " . number_format((float)$device['price'], 0, ',', '.') . ")";
                            @endphp
                            <a href="https://wa.me/62881025756671?text={{ urlencode($waAccessMsg) }}" target="_blank" class="btn-prod-buy">
                                <i class="bi bi-whatsapp"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 8. MELAYANI BERBAGAI SEKTOR -->
<section class="py-5" style="background-color: var(--ts-slate-50);">
    <div class="container py-3">
        <div class="text-center mb-4">
            <span class="section-tag">Cakupan Layanan</span>
            <h2 class="section-heading">Penerapan Sistem Sesuai Kebutuhan</h2>
            <p class="section-subheading mx-auto">Kami merancang skema tata letak kamera berdasarkan denah dan kebutuhan spesifik properti Anda.</p>
        </div>

        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="sector-card-corp">
                    <div class="sector-icon-corp">{{ $service['icon'] }}</div>
                    <div class="sector-title-corp">{{ $service['title'] }}</div>
                    <p class="sector-desc-corp">{{ $service['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 9. PORTOFOLIO & DOKUMENTASI HASIL PEMASANGAN -->
<section class="py-5 bg-white" id="portfolio">
    <div class="container py-3">
        <div class="text-center mb-4">
            <span class="section-tag">Dokumentasi Lapangan</span>
            <h2 class="section-heading">Portofolio Hasil Instalasi</h2>
            <p class="section-subheading mx-auto">Dokumentasi pengerjaan rapi oleh tim teknisi kami di perumahan residensial, pertokoan retail, dan kawasan industri.</p>
        </div>

        <div class="row g-4">
            @forelse($testimonials ?? [] as $testimoni)
            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card-corp">
                    <img src="{{ asset('storage/' . $testimoni['image']) }}" 
                         alt="{{ $testimoni['title'] }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1557597774-9d273605dfa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';">
                    <div class="testimoni-overlay-corp">
                        <span class="testimoni-tag-corp"><i class="bi bi-check-circle-fill"></i> Selesai Terpasang</span>
                        <div class="testimoni-title-corp">{{ $testimoni['title'] }}</div>
                        @if(isset($testimoni['location']))
                        <div class="testimoni-loc-corp">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $testimoni['location'] }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card-corp">
                    <img src="{{ asset('storage/gambar/contoh-testimoni1.jpg') }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1557597774-9d273605dfa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80'" 
                         alt="Instalasi 8 Channel">
                    <div class="testimoni-overlay-corp">
                        <span class="testimoni-tag-corp"><i class="bi bi-check-circle-fill"></i> Selesai Terpasang</span>
                        <div class="testimoni-title-corp">Instalasi CCTV Dahua 8 Channel</div>
                        <div class="testimoni-loc-corp">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Perumahan Cluster, Jakarta
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card-corp">
                    <img src="https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Instalasi Pabrik">
                    <div class="testimoni-overlay-corp">
                        <span class="testimoni-tag-corp"><i class="bi bi-check-circle-fill"></i> Selesai Terpasang</span>
                        <div class="testimoni-title-corp">Sistem Akses Kontrol & CCTV Pabrik</div>
                        <div class="testimoni-loc-corp">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kawasan Industri Cikarang
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimoni-card-corp">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                         alt="Instalasi Toko">
                    <div class="testimoni-overlay-corp">
                        <span class="testimoni-tag-corp"><i class="bi bi-check-circle-fill"></i> Selesai Terpasang</span>
                        <div class="testimoni-title-corp">Instalasi IP Camera 4 Titik Minimarket</div>
                        <div class="testimoni-loc-corp">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Retail & Gudang, Bekasi
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 10. REKAM JEJAK & KEPERCAYAAN MITRA (INTEGRATED TRUST BLOCK) -->
<section class="py-5" style="background-color: var(--ts-slate-50);">
    <div class="container py-2">
        <!-- STATS COUNTER STRIP -->
        <div class="stats-strip-corp mb-5">
            <div class="row g-3 text-center">
                <div class="col-lg-3 col-6">
                    <div class="stat-item-corp">
                        <div class="stat-num-corp">5.000+</div>
                        <p class="stat-text-corp">Titik CCTV Terpasang</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item-corp">
                        <div class="stat-num-corp">99.9%</div>
                        <p class="stat-text-corp">Kepuasan Klien</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item-corp">
                        <div class="stat-num-corp">2 Tahun</div>
                        <p class="stat-text-corp">Garansi Resmi Penuh</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="stat-item-corp">
                        <div class="stat-num-corp">24/7</div>
                        <p class="stat-text-corp">Siaga Dukungan Teknis</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CLIENT LOGOS SHOWCASE -->
        <div class="text-center pt-2">
            <span class="section-tag">Kepercayaan Mitra</span>
            <h2 class="section-heading mb-4">Dipercaya Oleh Berbagai Perusahaan</h2>
            <div>
                <img src="{{ asset('storage/gambar/desktop_client.png') }}" 
                     alt="Klien Terbaik Kami" 
                     class="img-fluid d-none d-md-inline"
                     onerror="this.style.display='none';">
                <img src="{{ asset('storage/gambar/mobile_client.png') }}" 
                     alt="Klien Terbaik Kami" 
                     class="img-fluid d-inline d-md-none"
                     onerror="this.style.display='none';">
            </div>
        </div>
    </div>
</section>

<!-- 11. CTA BANNER BAWAH -->
<div class="container my-5">
    <div class="cta-banner-corp">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <span class="badge bg-danger px-3 py-2 rounded-1 mb-3" style="font-size: 12px;">
                    <i class="bi bi-shield-check me-1"></i> Konsultasi & Survey Lokasi Gratis
                </span>
                <h2 class="h3 fw-bold mb-2">Konsultasikan Kebutuhan Keamanan Anda Hari Ini</h2>
                <p class="text-white-50 mb-0" style="font-size: 14px; line-height: 1.6;">
                    Hubungi tim konsultan PT. MJA TEKNOLOGI untuk mendapatkan rancangan denah titik kamera dan estimasi anggaran terbaik tanpa ikatan.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @php
                    $waCtaMsg = "Halo PT. MJA TEKNOLOGI, saya ingin survey lokasi dan konsultasi paket CCTV.";
                @endphp
                <a href="https://wa.me/62881025756671?text={{ urlencode($waCtaMsg) }}" target="_blank" class="btn btn-danger btn-lg px-4 py-3 rounded-2 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 14.5px;">
                    <i class="bi bi-whatsapp fs-5"></i>
                    <span>Hubungi Teknisi via WA</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ⭐ AI Assistant Component --}}
<x-ai-assistant />
@endsection