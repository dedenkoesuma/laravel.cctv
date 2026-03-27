@extends('layouts.simple')

@section('title', 'Detail Produk WiFi Cam - TechStore')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #f5f5f5;
    color: #333;
}

.detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 30px;
    transition: color 0.3s;
}

.back-button:hover {
    color: #333;
}

.back-button i {
    font-size: 1.2rem;
}

.product-main {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 40px;
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}

/* Image Gallery */
.product-gallery {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-image {
    width: 100%;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 40px;
    background: #fafafa;
    text-align: center;
}

.main-image img {
    width: 100%;
    max-width: 350px;
    height: auto;
    object-fit: contain;
}

.thumbnail-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.thumbnail {
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.3s;
    background: white;
    text-align: center;
}

.thumbnail:hover,
.thumbnail.active {
    border-color: #667eea;
}

.thumbnail img {
    width: 100%;
    height: auto;
    object-fit: contain;
}

/* Product Info */
.product-info {
    display: flex;
    flex-direction: column;
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
}

.product-badges {
    display: flex;
    gap: 8px;
}

.badge {
    padding: 6px 16px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.badge-sale {
    background: #ef4444;
    color: white;
}

.badge-spec {
    background: #f3f4f6;
    color: #374151;
}

.brand-logo {
    width: 100px;
    height: auto;
}

.product-title-section {
    margin-bottom: 30px;
}

.product-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 8px;
}

.product-subtitle {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 400;
}

.section-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f3f4f6;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px 32px;
    margin-bottom: 30px;
}

.spec-item {
    display: flex;
    align-items: start;
    gap: 8px;
    font-size: 0.875rem;
}

.spec-item i {
    color: #10b981;
    margin-top: 3px;
    flex-shrink: 0;
}

.included-section {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.included-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.included-item {
    display: flex;
    align-items: start;
    gap: 8px;
    font-size: 0.875rem;
}

.included-item i {
    color: #3b82f6;
    margin-top: 3px;
    flex-shrink: 0;
}

.price-section {
    margin-bottom: 30px;
}

.price-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.price {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1f2937;
}

.order-button {
    width: 100%;
    padding: 16px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
}

.order-button:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Similar Products */
.similar-section {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}

.similar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.similar-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.view-all {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.view-all:hover {
    text-decoration: underline;
}

.similar-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.similar-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    background: white;
}

.similar-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-4px);
}

.similar-card img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    margin-bottom: 12px;
}

.similar-card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.similar-card-desc {
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 8px;
    line-height: 1.4;
}

.similar-card-price {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.similar-card-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #667eea;
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 8px;
}

/* Terms Section */
.terms-section {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.terms-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 20px;
}

.terms-list {
    list-style: none;
    padding: 0;
}

.terms-list li {
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: start;
    gap: 12px;
}

.terms-list li:last-child {
    border-bottom: none;
}

.terms-list li i {
    color: #10b981;
    margin-top: 3px;
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .product-main {
        grid-template-columns: 1fr;
        padding: 20px;
    }
    
    .specs-grid,
    .included-grid {
        grid-template-columns: 1fr;
    }
    
    .similar-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .price {
        font-size: 2rem;
    }
}
</style>

<div class="detail-container">
    <a href="{{ url('/wifi-cam') }}" class="back-button">
        <i class="bi bi-chevron-left"></i>
        <span>C6N</span>
    </a>
    
    <div class="product-main">
        <div class="product-gallery">
            <div class="main-image" id="mainImage">
                <img src="https://via.placeholder.com/400x400/ffffff/cccccc?text=EZVIZ+C6N" alt="EZVIZ C6N">
            </div>
            
            <div class="thumbnail-grid">
                <div class="thumbnail active">
                    <img src="https://via.placeholder.com/120x120/ffffff/cccccc?text=C6N+1" alt="View 1">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/120x120/ffffff/cccccc?text=C6N+2" alt="View 2">
                </div>
                <div class="thumbnail">
                    <img src="https://via.placeholder.com/120x120/ffffff/cccccc?text=C6N+3" alt="View 3">
                </div>
            </div>
        </div>
        
        <div class="product-info">
            <div class="product-header">
                <div class="product-badges">
                    <span class="badge badge-sale">SALE</span>
                    <span class="badge badge-spec">4MP</span>
                </div>
                <img src="https://via.placeholder.com/100x40/ffffff/667eea?text=EZVIZ" alt="EZVIZ" class="brand-logo">
            </div>
            
            <div class="product-title-section">
                <h1 class="product-title">C6N</h1>
                <p class="product-subtitle">Smart Pan & Tilt Indoor WiFi Camera</p>
            </div>
            
            <div class="specs-section">
                <h3 class="section-title">Spesifikasi</h3>
                <div class="specs-grid">
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>2MP 1080P Resolution</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>WiFi 2.4 Ghz</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Motion Detection</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Motorized Pan & Tilt 360°</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Smart Night Vision with Smart IR (up to 10m)</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>MicroSD Slot Up to 256GB</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Smart Tracking</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Two-way Talk Audio</span>
                    </div>
                    <div class="spec-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Sleep Mode for Privacy Protection</span>
                    </div>
                </div>
            </div>
            
            <div class="included-section">
                <h3 class="section-title">Sudah Termasuk</h3>
                <div class="included-grid">
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>1 Kamera C6N 2MP</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Memory Card 32GB</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Kabel Power 5v</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Training Cloud</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Terminal 1 Lubang 1 Unit</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Jasa Pasang & Konfigurasi</span>
                    </div>
                    <div class="included-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Sticker 1 Unit</span>
                    </div>
                </div>
            </div>
            
            <div class="price-section">
                <p class="price-label">Harga/Unit:</p>
                <p class="price">IDR. 1.065.000</p>
            </div>
            
            <button class="order-button" onclick="orderNow()">Order Sekarang</button>
        </div>
    </div>
    
    <!-- Similar Products -->
    <div class="similar-section">
        <div class="similar-header">
            <h2 class="similar-title">Produk Serupa</h2>
            <a href="{{ url('/wifi-cam') }}" class="view-all">Produk Lainnya →</a>
        </div>
        
        <div class="similar-grid">
            <div class="similar-card">
                <img src="https://via.placeholder.com/200x200/ffffff/cccccc?text=H6C+4MP" alt="H6C 4MP">
                <h4 class="similar-card-title">H6C 4MP</h4>
                <p class="similar-card-desc">4MP 2K 4MP WiFi Smart Pan & Tilt Indoor Camera</p>
                <p class="similar-card-price">Rp. 1.350.000</p>
                <a href="#" class="similar-card-link">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="similar-card">
                <img src="https://via.placeholder.com/200x200/ffffff/cccccc?text=H3C+4MP" alt="H3C 4MP">
                <h4 class="similar-card-title">H3C 4MP</h4>
                <p class="similar-card-desc">4MP 2K 4MP WiFi Smart Home Outdoor Camera</p>
                <p class="similar-card-price">Rp. 1.850.000</p>
                <a href="#" class="similar-card-link">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="similar-card">
                <img src="https://via.placeholder.com/200x200/ffffff/cccccc?text=H8C" alt="H8C">
                <h4 class="similar-card-title">H8C</h4>
                <p class="similar-card-desc">2MP 1080P Smart WiFi Pan & Tilt Indoor Camera</p>
                <p class="similar-card-price">Rp. 1.688.000</p>
                <a href="#" class="similar-card-link">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="similar-card">
                <img src="https://via.placeholder.com/200x200/ffffff/cccccc?text=H1C" alt="H1C">
                <h4 class="similar-card-title">H1C</h4>
                <p class="similar-card-desc">2MP 1080P Smart WiFi Pan Camera</p>
                <p class="similar-card-price">Rp. 1.050.000</p>
                <a href="#" class="similar-card-link">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Terms and Conditions -->
    <div class="terms-section">
        <h2 class="terms-title">Syarat dan Ketentuan</h2>
        <ul class="terms-list">
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Survei lokasi & demo produk gratis jika diperlukan.</span>
            </li>
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Harga belum termasuk monitor/TV.</span>
            </li>
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Garansi DVR & Kamera 2 Tahun. HDD 1 Tahun. Garansi Instalasi 1 bulan.</span>
            </li>
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Kekurangan kabel Coaxial akan dikenakan biaya ép. 5.000 / Meter</span>
            </li>
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Kekurangan kabel UTP akan dikenakan biaya sebesar Rp. 7.000,-/m.</span>
            </li>
            <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Harga belum termasuk pipa conduit.</span>
            </li>
        </ul>
    </div>
</div>

<script>
function orderNow() {
    const productName = 'EZVIZ C6N - Smart Pan & Tilt Indoor WiFi Camera';
    const price = 'IDR 1.065.000';
    const message = `Halo, saya tertarik dengan produk ${productName} dengan harga ${price}. Mohon informasi lebih lanjut.`;
    
    window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(message), '_blank');
}

// Thumbnail click handler
document.querySelectorAll('.thumbnail').forEach(thumb => {
    thumb.addEventListener('click', function() {
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
@endsection