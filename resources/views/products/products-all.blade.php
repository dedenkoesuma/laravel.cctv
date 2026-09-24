@extends('layouts.simple')

@section('title', 'Katalog Semua Produk & Brand CCTV - TechStore PT. MJA TEKNOLOGI')
@section('meta_description', 'Katalog lengkap perangkat CCTV Hikvision, Dahua, HiLook, UNV, EZVIZ, dan Ruijie dengan harga resmi dan garansi 2 tahun PT. MJA TEKNOLOGI.')

@section('content')
<style>
/* ===== CATALOG ALL CORPORATE STYLING ===== */
.catalog-header {
    background-color: #0f172a;
    color: #ffffff;
    padding: 55px 0 45px;
    border-bottom: 1px solid var(--ts-slate-800);
}

.catalog-chip {
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

.brand-pills-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 8px;
    scrollbar-width: thin;
}

.brand-pill-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
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

.brand-pill-btn:hover {
    background: var(--ts-slate-100);
    color: var(--ts-slate-900);
    border-color: var(--ts-slate-400);
}

.brand-pill-btn.active {
    background: var(--ts-slate-900);
    color: #ffffff;
    border-color: var(--ts-slate-900);
}

.prod-grid-card {
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

.prod-grid-card:hover {
    border-color: var(--ts-slate-300);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
}

.prod-img-wrap {
    background-color: #f8fafc;
    height: 200px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--ts-slate-100);
    position: relative;
}

.prod-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.25s ease;
}

.prod-grid-card:hover .prod-img-wrap img {
    transform: scale(1.05);
}

.prod-brand-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #ffffff;
    color: var(--ts-slate-800);
    border: 1px solid var(--ts-slate-200);
    padding: 3px 8px;
    border-radius: 4px;
}

.prod-category-tag {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 10.5px;
    font-weight: 600;
    background: rgba(15, 23, 42, 0.85);
    color: #ffffff;
    padding: 3px 8px;
    border-radius: 4px;
}

.prod-info-wrap {
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.prod-title {
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

.prod-price {
    font-size: 17px;
    font-weight: 800;
    color: var(--ts-primary);
    margin-bottom: 12px;
}

.prod-desc {
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

.prod-btn-order {
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

.prod-btn-order:hover {
    background-color: #059669;
    border-color: #059669;
    color: #ffffff;
}
</style>

<!-- HEADER -->
<section class="catalog-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/') }}#brands" class="text-white-50 text-decoration-none">Brands</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Semua Brand</li>
            </ol>
        </nav>
        
        <div class="catalog-chip">
            <i class="bi bi-grid-3x3-gap-fill"></i> Authorized Security Hardware
        </div>
        <h1 class="fw-bold mb-2" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem);">Katalog Lengkap CCTV & Keamanan</h1>
        <p class="text-white-50 mb-0" style="font-size: 15px; max-width: 700px; line-height: 1.6;">
            Temukan seluruh jajaran kamera pengawas resolusi tinggi, perekam DVR/NVR, serta sistem keamanan jaringan terintegrasi bergaransi resmi PT. MJA TEKNOLOGI.
        </p>
    </div>
</section>

<!-- MAIN CATALOG -->
<section class="py-5" style="background-color: var(--ts-slate-50);">
    <div class="container py-2">
        <!-- BRAND SWITCHER PILLS -->
        <div class="card border-0 shadow-sm p-3 mb-4 rounded-3" style="background: #ffffff; border: 1px solid var(--ts-slate-200) !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="brand-pills-bar">
                    <a href="{{ url('/products/all') }}" class="brand-pill-btn active">
                        <i class="bi bi-grid-fill"></i> Semua Brand
                    </a>
                    <a href="{{ url('/products/hikvision') }}" class="brand-pill-btn">Hikvision</a>
                    <a href="{{ url('/products/dahua') }}" class="brand-pill-btn">Dahua</a>
                    <a href="{{ url('/products/hilook') }}" class="brand-pill-btn">HiLook</a>
                    <a href="{{ url('/products/unv') }}" class="brand-pill-btn">UNV</a>
                    <a href="{{ url('/wifi-cam') }}" class="brand-pill-btn">EZVIZ (WiFi Cam)</a>
                    <a href="{{ url('/products/ruijie') }}" class="brand-pill-btn">Ruijie / Reyee</a>
                    <a href="{{ url('/products/foreage') }}" class="brand-pill-btn">Foreages</a>
                    <a href="{{ url('/products/hiview') }}" class="brand-pill-btn">HiView</a>
                </div>

                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: var(--ts-slate-300);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="productSearchInput" class="form-control border-start-0 ps-0" placeholder="Cari nama kamera..." style="font-size: 13.5px; border-color: var(--ts-slate-300);" onkeyup="filterCatalogProducts()">
                </div>
            </div>
        </div>

        @php
            // Gabungkan produk static atau dynamic tanpa mengubah data database
            $allProductsList = collect([]);

            if (isset($staticProducts)) {
                foreach($staticProducts as $tab => $items) {
                    if (is_iterable($items)) {
                        foreach($items as $item) {
                            $allProductsList->push($item);
                        }
                    }
                }
            }

            if ($allProductsList->isEmpty() && isset($dynamicProducts)) {
                $allProductsList = $dynamicProducts;
            }
        @endphp

        <!-- PRODUCTS GRID -->
        <div class="row g-4" id="catalogProductGrid">
            @forelse($allProductsList as $prod)
                @php
                    $displayPrice = 0;
                    if (isset($prod->harga_jual) && $prod->harga_jual > 0) {
                        $displayPrice = is_numeric($prod->harga_jual) ? $prod->harga_jual : (float)str_replace([','], '', $prod->harga_jual);
                    } elseif (isset($prod->harga) && $prod->harga > 0) {
                        $displayPrice = is_numeric($prod->harga) ? $prod->harga : (float)str_replace([','], '', $prod->harga);
                    } elseif (isset($prod->price) && $prod->price > 0) {
                        $displayPrice = is_numeric($prod->price) ? $prod->price : (float)str_replace([','], '', $prod->price);
                    }

                    $prodName = $prod->nama_produk ?? $prod->product_name ?? 'Kamera CCTV';
                    $prodBrand = $prod->brand ?? 'TechStore';
                    $prodCategory = $prod->tab_category ?? $prod->category ?? 'Surveillance';
                    $prodDesc = $prod->deskripsi ?? $prod->description ?? 'Kamera CCTV berkualitas bergaransi resmi.';
                    $prodImg = !empty($prod->gambar) ? $prod->gambar : (!empty($prod->image) ? $prod->image : null);

                    $waOrderMsg = "Halo TechStore, saya berminat memesan: " . $prodName . " (" . ucfirst($prodBrand) . ") seharga Rp " . number_format($displayPrice, 0, ',', '.');
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 product-card-col" data-product-name="{{ strtolower($prodName) }}" data-product-brand="{{ strtolower($prodBrand) }}">
                    <div class="prod-grid-card">
                        <div class="prod-img-wrap">
                            <span class="prod-brand-tag">{{ $prodBrand }}</span>
                            <span class="prod-category-tag">{{ ucfirst($prodCategory) }}</span>
                            @if($prodImg)
                                <img src="{{ asset('storage/' . $prodImg) }}" alt="{{ $prodName }}" onerror="this.onerror=null; this.src='https://via.placeholder.com/260x200?text=CCTV+Camera';">
                            @else
                                <img src="https://via.placeholder.com/260x200?text={{ urlencode($prodName) }}" alt="{{ $prodName }}">
                            @endif
                        </div>

                        <div class="prod-info-wrap">
                            <h3 class="prod-title" title="{{ $prodName }}">{{ $prodName }}</h3>
                            <div class="prod-price">
                                Rp {{ number_format($displayPrice, 0, ',', '.') }}
                            </div>
                            <p class="prod-desc">{{ Str::limit(strip_tags($prodDesc), 100) }}</p>

                            <a href="https://wa.me/62881025756671?text={{ urlencode($waOrderMsg) }}" target="_blank" class="prod-btn-order">
                                <i class="bi bi-whatsapp"></i>
                                <span>Pesan via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center py-5 border">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h5 class="fw-bold">Belum Ada Produk Tersedia</h5>
                        <p class="text-muted small mb-3">Silakan pilih menu brand spesifik pada navigasi di atas.</p>
                        <a href="{{ url('/') }}" class="btn btn-outline-dark btn-sm">Kembali ke Beranda</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div id="noSearchResults" class="text-center py-5 d-none">
            <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
            <h5 class="fw-bold">Produk Tidak Ditemukan</h5>
            <p class="text-muted small">Coba masukkan kata kunci pencarian yang lain.</p>
        </div>
    </div>
</section>

<!-- TRUST & SURVEY CTA -->
<div class="container my-5">
    <div class="p-4 p-md-5 rounded-3 text-white" style="background-color: var(--ts-slate-900); border: 1px solid var(--ts-slate-800);">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <span class="badge bg-danger px-3 py-1.5 rounded-1 mb-2" style="font-size: 11px;">
                    <i class="bi bi-patch-check-fill me-1"></i> PT. MJA TEKNOLOGI
                </span>
                <h3 class="fw-bold mb-2">Butuh Bantuan Memilih Perangkat yang Tepat?</h3>
                <p class="text-white-50 mb-0 small" style="line-height: 1.6;">
                    Konsultasikan luas area, jarak jangkauan pantau, dan resolusi yang Anda butuhkan bersama konsultan teknis kami. Gratis survey dan estimasi penawaran resmi.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="https://wa.me/62881025756671?text=Halo%20TechStore,%20saya%20ingin%20konsultasi%20pemilihan%20produk%20CCTV." target="_blank" class="btn btn-danger px-4 py-2.5 rounded-2 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 14px;">
                    <i class="bi bi-whatsapp"></i>
                    <span>Tanya Teknisi via WA</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function filterCatalogProducts() {
    const input = document.getElementById('productSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.product-card-col');
    let visibleCount = 0;

    cards.forEach(card => {
        const name = card.getAttribute('data-product-name') || '';
        const brand = card.getAttribute('data-product-brand') || '';
        if (name.includes(input) || brand.includes(input)) {
            card.classList.remove('d-none');
            visibleCount++;
        } else {
            card.classList.add('d-none');
        }
    });

    const noResults = document.getElementById('noSearchResults');
    if (visibleCount === 0 && cards.length > 0) {
        noResults.classList.remove('d-none');
    } else {
        noResults.classList.add('d-none');
    }
}
</script>

<x-ai-assistant />
@endsection
