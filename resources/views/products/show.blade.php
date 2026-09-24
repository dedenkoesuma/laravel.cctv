@extends('layouts.simple')

@section('title', 'Produk ' . $brandName . ' - TechStore PT. MJA TEKNOLOGI')
@section('meta_description', 'Katalog resmi produk CCTV ' . $brandName . ' bergaransi resmi PT. MJA TEKNOLOGI. Tersedia kamera analog, IP camera, audio built-in, dan full-color.')

@section('content')
<style>
/* ===== BRAND CATALOG PAGE CORPORATE STYLING ===== */
.brand-page-header {
    background-color: #0f172a;
    color: #ffffff;
    padding: 55px 0 45px;
    border-bottom: 1px solid var(--ts-slate-800);
    position: relative;
}

.brand-page-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fca5a5;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 4px;
    margin-bottom: 16px;
    letter-spacing: 0.5px;
}

.brand-pills-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 8px;
    scrollbar-width: thin;
}

.brand-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    border: 1px solid var(--ts-slate-300);
    background: #ffffff;
    color: var(--ts-slate-700);
}

.brand-nav-btn:hover {
    background: var(--ts-slate-100);
    color: var(--ts-slate-900);
    border-color: var(--ts-slate-400);
}

.brand-nav-btn.active {
    background: var(--ts-slate-900);
    color: #ffffff;
    border-color: var(--ts-slate-900);
}

/* Category Tabs */
.cat-nav-tabs {
    border-bottom: 2px solid var(--ts-slate-200);
    gap: 8px;
}

.cat-nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--ts-slate-600);
    font-weight: 600;
    font-size: 14px;
    padding: 12px 18px;
    margin-bottom: -2px;
    border-radius: 0;
    transition: all 0.2s ease;
    background: transparent;
}

.cat-nav-tabs .nav-link:hover {
    color: var(--ts-slate-900);
}

.cat-nav-tabs .nav-link.active {
    color: var(--ts-primary);
    border-bottom-color: var(--ts-primary);
    font-weight: 700;
    background: transparent;
}

/* Product Card */
.prod-item-card {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: all 0.25s ease;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.prod-item-card:hover {
    border-color: var(--ts-slate-300);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
}

.prod-item-img {
    background-color: #f8fafc;
    height: 200px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--ts-slate-100);
    position: relative;
}

.prod-item-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.25s ease;
}

.prod-item-card:hover .prod-item-img img {
    transform: scale(1.05);
}

.prod-item-body {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.prod-item-title {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 40px;
}

.prod-item-price {
    font-size: 17px;
    font-weight: 800;
    color: var(--ts-primary);
    margin-bottom: 12px;
}

.prod-item-desc {
    font-size: 12.5px;
    color: var(--ts-slate-500);
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

.prod-item-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background-color: var(--ts-slate-900);
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 14px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid var(--ts-slate-900);
}

.prod-item-btn:hover {
    background-color: #059669;
    border-color: #059669;
    color: #ffffff;
}
</style>

<!-- BRAND HEADER -->
<section class="brand-page-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/') }}#brands" class="text-white-50 text-decoration-none">Brands</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $brandName }}</li>
            </ol>
        </nav>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="brand-page-chip">
                    <i class="bi bi-shield-check"></i> Authorized Brand Partner
                </div>
                <h1 class="fw-bold mb-2" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem);">
                    {{ $brandData['name'] ?? $brandName }}
                </h1>
                <p class="text-white-50 mb-0" style="font-size: 14.5px; max-width: 650px; line-height: 1.6;">
                    {{ $brandData['description'] ?? 'Sistem keamanan dan pengawasan video berkualitas dengan garansi resmi PT. MJA TEKNOLOGI.' }}
                </p>
            </div>
            <div>
                <span class="badge px-3 py-2 rounded-2 fw-semibold" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); font-size: 13px;">
                    <i class="bi bi-check2-circle text-success me-1"></i> Garansi Resmi 2 Tahun
                </span>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="py-5" style="background-color: var(--ts-slate-50);">
    <div class="container py-2">
        <!-- BRAND SWITCHER STRIP -->
        <div class="card border-0 shadow-sm p-3 mb-4 rounded-3" style="background: #ffffff; border: 1px solid var(--ts-slate-200) !important;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Pilih Brand:</span>
                <div class="brand-pills-strip">
                    <a href="{{ url('/products/all') }}" class="brand-nav-btn">Semua Brand</a>
                    <a href="{{ url('/products/hikvision') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'hikvision' ? 'active' : '' }}">Hikvision</a>
                    <a href="{{ url('/products/dahua') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'dahua' ? 'active' : '' }}">Dahua</a>
                    <a href="{{ url('/products/hilook') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'hilook' ? 'active' : '' }}">HiLook</a>
                    <a href="{{ url('/products/unv') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'unv' ? 'active' : '' }}">UNV</a>
                    <a href="{{ url('/wifi-cam') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'ezviz' ? 'active' : '' }}">EZVIZ</a>
                    <a href="{{ url('/products/ruijie') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'ruijie' ? 'active' : '' }}">Ruijie</a>
                    <a href="{{ url('/products/hiview') }}" class="brand-nav-btn {{ strtolower($brandSlug ?? '') === 'hiview' ? 'active' : '' }}">HiView</a>
                </div>
            </div>
        </div>

        <!-- CATALOG TABS & GRID -->
        <div class="card border-0 shadow-sm p-4 rounded-3" style="background: #ffffff; border: 1px solid var(--ts-slate-200) !important;">
            @php
                $tabCategories = [
                    'basic' => 'Basic Series',
                    'audio' => 'Audio Series',
                    'fullcolor' => 'Full Color',
                    'ipcamera' => 'IP Camera'
                ];
            @endphp

            <ul class="nav cat-nav-tabs mb-4" id="brandTabList" role="tablist">
                @foreach($tabCategories as $key => $label)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                            id="{{ $key }}-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-pane-{{ $key }}" 
                            type="button" 
                            role="tab">
                        {{ $label }}
                        @if(isset($categoryCounts[$key]) && $categoryCounts[$key] > 0)
                            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $categoryCounts[$key] }}</span>
                        @endif
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="brandTabContent">
                @foreach($tabCategories as $key => $label)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                     id="tab-pane-{{ $key }}" 
                     role="tabpanel">
                    
                        @php
                            $categoryProducts = $staticProducts[$key] ?? collect([]);
                            if ($categoryProducts->isEmpty() && isset($dynamicProducts) && $dynamicProducts->isNotEmpty()) {
                                $categoryProducts = $dynamicProducts->where('tab_category', $key);
                            }
                        @endphp
                        @forelse($categoryProducts as $product)
                            @php
                                $displayPrice = 0;
                                if (isset($product->harga_jual) && $product->harga_jual > 0) {
                                    $displayPrice = is_numeric($product->harga_jual) ? $product->harga_jual : (float)str_replace([','], '', $product->harga_jual);
                                } elseif (isset($product->harga) && $product->harga > 0) {
                                    $displayPrice = is_numeric($product->harga) ? $product->harga : (float)str_replace([','], '', $product->harga);
                                } elseif (isset($product->price) && $product->price > 0) {
                                    $displayPrice = is_numeric($product->price) ? $product->price : (float)str_replace([','], '', $product->price);
                                }

                                $prodName = $product->nama_produk ?? $product->product_name ?? 'Kamera CCTV';
                                $prodDesc = $product->deskripsi ?? $product->description ?? 'Kamera pengawas bergaransi resmi.';
                                $prodImg = !empty($product->gambar) ? $product->gambar : (!empty($product->image) ? $product->image : null);

                                $waPesan = "Halo TechStore, saya berminat memesan: " . $prodName . " (" . $brandName . ") seharga Rp " . number_format($displayPrice, 0, ',', '.');
                            @endphp

                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="prod-item-card">
                                    <div class="prod-item-img">
                                        @if($prodImg)
                                            <img src="{{ asset('storage/' . $prodImg) }}" alt="{{ $prodName }}" onerror="this.onerror=null; this.src='https://via.placeholder.com/260x200?text=CCTV';">
                                        @else
                                            <img src="https://via.placeholder.com/260x200?text={{ urlencode($prodName) }}" alt="{{ $prodName }}">
                                        @endif
                                    </div>

                                    <div class="prod-item-body">
                                        <h3 class="prod-item-title" title="{{ $prodName }}">{{ $prodName }}</h3>
                                        <div class="prod-item-price">
                                            Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                        </div>
                                        <p class="prod-item-desc">{{ Str::limit(strip_tags($prodDesc), 100) }}</p>

                                        <a href="https://wa.me/62881025756671?text={{ urlencode($waPesan) }}" target="_blank" class="prod-item-btn">
                                            <i class="bi bi-whatsapp"></i>
                                            <span>Order via WhatsApp</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light text-center py-5 border">
                                    <i class="bi bi-camera-video text-muted fs-1 d-block mb-2"></i>
                                    <h5 class="fw-bold">Belum Ada Produk di Kategori Ini</h5>
                                    <p class="text-muted small mb-0">Silakan periksa kategori lain atau hubungi admin kami untuk ketersediaan tipe khusus.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- REASONS TO BUY STRIP -->
        <div class="row g-3 mt-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm p-3 text-center h-100 rounded-3">
                    <i class="bi bi-shield-check text-primary fs-3 mb-2"></i>
                    <h6 class="fw-bold mb-1" style="font-size: 13.5px;">100% Produk Asli</h6>
                    <p class="text-muted small mb-0">Garansi resmi prinsipal</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm p-3 text-center h-100 rounded-3">
                    <i class="bi bi-tools text-success fs-3 mb-2"></i>
                    <h6 class="fw-bold mb-1" style="font-size: 13.5px;">Teknisi Berpengalaman</h6>
                    <p class="text-muted small mb-0">Instalasi rapi berstandar</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm p-3 text-center h-100 rounded-3">
                    <i class="bi bi-file-earmark-text text-danger fs-3 mb-2"></i>
                    <h6 class="fw-bold mb-1" style="font-size: 13.5px;">Faktur Pajak & Invoice</h6>
                    <p class="text-muted small mb-0">Dokumen legalitas PT</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm p-3 text-center h-100 rounded-3">
                    <i class="bi bi-headset text-warning fs-3 mb-2"></i>
                    <h6 class="fw-bold mb-1" style="font-size: 13.5px;">Dukungan Purna Jual</h6>
                    <p class="text-muted small mb-0">Siaga bantuan teknis</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BOTTOM SURVEY BANNER -->
<div class="container my-5">
    <div class="p-4 p-md-5 rounded-3 text-white" style="background-color: var(--ts-slate-900); border: 1px solid var(--ts-slate-800);">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <span class="badge bg-danger px-3 py-1.5 rounded-1 mb-2" style="font-size: 11px;">
                    <i class="bi bi-patch-check-fill me-1"></i> Konsultasi Produk {{ $brandName }}
                </span>
                <h3 class="fw-bold mb-2">Perlu Estimasi Penawaran Resmi Produk Ini?</h3>
                <p class="text-white-50 mb-0 small" style="line-height: 1.6;">
                    Hubungi tim PT. MJA TEKNOLOGI untuk mendapatkan penawaran harga khusus paket borongan unit beserta jasa pasang dan konfigurasi di lokasi Anda.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="https://wa.me/62881025756671?text=Halo%20TechStore,%20saya%20ingin%20konsultasi%20pengadaan%20CCTV%20{{ urlencode($brandName) }}." target="_blank" class="btn btn-danger px-4 py-2.5 rounded-2 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 14px;">
                    <i class="bi bi-whatsapp"></i>
                    <span>Hubungi Sales via WA</span>
                </a>
            </div>
        </div>
    </div>
</div>

<x-ai-assistant />
@endsection
