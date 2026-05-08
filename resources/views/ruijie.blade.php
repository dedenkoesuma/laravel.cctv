@extends('layouts.app')

@section('title', 'Detail Produk Ruijie - TechStore')

@section('content')
<style>
/* ===== REUSE SAME STYLES FROM LISTING PAGE ===== */
.page-header {
    background: linear-gradient(135deg, #00A7E1 0%, #005A8D 100%);
    color: white;
    padding: 60px 20px 40px;
    margin-bottom: 40px;
}

.page-title {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-align: center;
}

.breadcrumb-custom {
    background: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.breadcrumb-custom a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-custom a:hover {
    color: white;
}

.breadcrumb-custom span {
    color: rgba(255,255,255,0.6);
}

/* ===== DETAIL CARD - SAME STYLE AS LISTING ===== */
.detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px 60px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    color: #00A7E1;
    border: 2px solid #00A7E1;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.back-button:hover {
    background: #00A7E1;
    color: white;
    transform: translateX(-5px);
}

.ruijie-detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.product-main-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

.product-image-section {
    background: #f8f9fa;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}

.badge-featured {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #ffc107;
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.main-product-image {
    width: 100%;
    max-width: 400px;
    margin-bottom: 20px;
}

.main-product-image img {
    width: 100%;
    height: auto;
    max-height: 350px;
    object-fit: contain;
}

.thumbnail-list {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.thumbnail {
    width: 70px;
    height: 70px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
}

.thumbnail:hover,
.thumbnail.active {
    border-color: #00A7E1;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
}

.product-info-section {
    padding: 3rem;
}

.product-category {
    font-size: 0.75rem;
    font-weight: 600;
    color: #00A7E1;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
    display: inline-block;
    background: #E6F7FF;
    padding: 6px 16px;
    border-radius: 20px;
}

.product-name {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.product-description {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.product-price-box {
    background: linear-gradient(135deg, #00A7E1 0%, #005A8D 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.price-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.product-price {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
}

.price-original {
    font-size: 1.125rem;
    text-decoration: line-through;
    opacity: 0.8;
    margin-left: 12px;
}

/* ===== FEATURES & SPECS - SAME STYLE ===== */
.product-features {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #00A7E1;
}

.features-list {
    list-style: none;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.features-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #333;
}

.features-list li i {
    color: #28a745;
    font-size: 1.125rem;
    flex-shrink: 0;
}

/* ===== ACTION BUTTONS - SAME STYLE ===== */
.product-actions {
    display: flex;
    gap: 12px;
}

.btn-action {
    flex: 1;
    padding: 14px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-buy {
    background: #00A7E1;
    color: white;
    border: none;
}

.btn-buy:hover {
    background: #005A8D;
    transform: translateY(-2px);
}

.btn-contact {
    background: white;
    color: #00A7E1;
    border: 2px solid #00A7E1;
}

.btn-contact:hover {
    background: #00A7E1;
    color: white;
    transform: translateY(-2px);
}

/* ===== SPECIFICATIONS TAB ===== */
.specs-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.specs-table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table tr {
    border-bottom: 1px solid #e9ecef;
}

.specs-table tr:last-child {
    border-bottom: none;
}

.specs-table td {
    padding: 16px 0;
    vertical-align: top;
}

.specs-table td:first-child {
    font-weight: 600;
    color: #495057;
    width: 30%;
}

.specs-table td:last-child {
    color: #6c757d;
}

/* ===== RELATED PRODUCTS - SAME STYLE AS LISTING ===== */
.related-section {
    margin-top: 60px;
}

.related-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.ruijie-product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.ruijie-product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.product-image {
    background: #f8f9fa;
    padding: 2rem;
    text-align: center;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    max-width: 100%;
    max-height: 150px;
    object-fit: contain;
}

.product-content {
    padding: 1.5rem;
}

.product-content .product-category {
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
}

.product-content .product-name {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    min-height: 48px;
    line-height: 1.4;
}

.product-content .product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #dc3545;
    background: none;
    padding: 0;
    border-radius: 0;
    margin-bottom: 1rem;
}

.product-footer {
    padding: 0 1.5rem 1.5rem;
    display: flex;
    gap: 8px;
}

.btn-detail-small {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 2px solid #00A7E1;
    background: white;
    color: #00A7E1;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-detail-small:hover {
    background: #00A7E1;
    color: white;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .product-main-section {
        grid-template-columns: 1fr;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .product-image-section,
    .product-info-section {
        padding: 1.5rem;
    }
    
    .product-name {
        font-size: 1.5rem;
    }
    
    .features-list {
        grid-template-columns: 1fr;
    }
    
    .product-actions {
        flex-direction: column;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <a href="{{ url('/products/ruijie') }}">Ruijie Networks</a>
            <span>/</span>
            <span style="color: white;">Detail Produk</span>
        </div>
        <h1 class="page-title" id="pageTitle">Loading...</h1>
    </div>
</div>

<div class="detail-container">
    <!-- Back Button -->
    <a href="{{ url('/products/ruijie') }}" class="back-button">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Produk Ruijie</span>
    </a>

    <!-- Main Product Card -->
    <div class="ruijie-detail-card">
        <div class="product-main-section">
            <!-- Left: Product Image -->
            <div class="product-image-section">
                <span class="badge-featured" id="featuredBadge" style="display: none;">
                    ⭐ Featured
                </span>
                
                <div class="main-product-image">
                    <img src="https://via.placeholder.com/400x300/f8f9fa/00A7E1?text=Loading..." 
                         alt="Product" 
                         id="mainImage">
                </div>
                
                <div class="thumbnail-list" id="thumbnailList">
                    <!-- Thumbnails will be loaded here -->
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="product-info-section">
                <div class="product-category" id="productCategory">Loading...</div>
                
                <h2 class="product-name" id="productName">Loading...</h2>
                
                <p class="product-description" id="productDescription">Loading product information...</p>
                
                <div class="product-price-box">
                    <p class="price-label">Harga Satuan:</p>
                    <div class="product-price">
                        <span id="productPrice">Rp 0</span>
                        <span class="price-original" id="originalPrice" style="display: none;">Rp 0</span>
                    </div>
                </div>

                <div class="product-features">
                    <h3 class="section-title">Fitur Utama</h3>
                    <ul class="features-list" id="featuresList">
                        <li><i class="bi bi-hourglass"></i> Loading...</li>
                    </ul>
                </div>

                <div class="product-actions">
                    <button class="btn-action btn-buy" onclick="buyProduct()">
                        <i class="bi bi-cart-plus"></i>
                        <span>Beli Sekarang</span>
                    </button>
                    <button class="btn-action btn-contact" onclick="contactWhatsApp()">
                        <i class="bi bi-whatsapp"></i>
                        <span>Hubungi Kami</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications Section -->
    <div class="specs-section">
        <h3 class="section-title">Spesifikasi Detail</h3>
        <table class="specs-table" id="specsTable">
            <tbody>
                <tr>
                    <td>Loading...</td>
                    <td>Please wait...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Related Products -->
    <div class="related-section">
        <h3 class="related-title">Produk Ruijie Lainnya</h3>
        <div class="related-grid" id="relatedGrid">
            <!-- Related products will be loaded here -->
        </div>
    </div>
</div>

<script>
// Get product ID from URL
const productId = window.location.pathname.split('/').pop();

// Load product data
document.addEventListener('DOMContentLoaded', function() {
    loadProductDetail();
});

function loadProductDetail() {
    fetch('/api/ruijie-products')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products) {
                const product = data.products.find(p => p.id == productId);
                if (product) {
                    renderProductDetail(product);
                    loadRelatedProducts(product.category);
                } else {
                    showError('Produk tidak ditemukan');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Gagal memuat data produk');
        });
}

function renderProductDetail(product) {
    // Update page title
    document.getElementById('pageTitle').textContent = product.name;
    
    // Update category
    document.getElementById('productCategory').textContent = product.category || 'Ruijie';
    
    // Update name
    document.getElementById('productName').textContent = product.name;
    
    // Update description
    document.getElementById('productDescription').textContent = product.description || 'Solusi networking enterprise-grade dengan performa tinggi dan keamanan maksimal untuk berbagai kebutuhan bisnis Anda.';
    
    // Update price
    document.getElementById('productPrice').textContent = 'Rp ' + formatPrice(product.price);
    
    // Show original price if exists
    if (product.original_price && product.original_price > product.price) {
        const originalPriceEl = document.getElementById('originalPrice');
        originalPriceEl.textContent = 'Rp ' + formatPrice(product.original_price);
        originalPriceEl.style.display = 'inline';
    }
    
    // Update main image
    const mainImage = document.getElementById('mainImage');
    mainImage.src = product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/400x300/f8f9fa/00A7E1?text=Ruijie';
    mainImage.alt = product.name;
    
    // Show featured badge
    if (product.is_featured) {
        document.getElementById('featuredBadge').style.display = 'block';
    }
    
    // Render thumbnails
    renderThumbnails(product);
    
    // Render features
    renderFeatures(product);
    
    // Render specifications
    renderSpecifications(product);
}

function renderThumbnails(product) {
    const container = document.getElementById('thumbnailList');
    const imageSrc = product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/150/f8f9fa/00A7E1?text=Ruijie';
    
    container.innerHTML = '';
    
    // Create 3 thumbnails (same image for demo)
    for (let i = 0; i < 3; i++) {
        const thumb = document.createElement('div');
        thumb.className = 'thumbnail' + (i === 0 ? ' active' : '');
        thumb.onclick = () => changeMainImage(imageSrc, thumb);
        thumb.innerHTML = `<img src="${imageSrc}" alt="View ${i+1}">`;
        container.appendChild(thumb);
    }
}

function renderFeatures(product) {
    const list = document.getElementById('featuresList');
    list.innerHTML = '';
    
    let features = [];
    if (product.features) {
        try {
            features = typeof product.features === 'string' ? JSON.parse(product.features) : product.features;
        } catch (e) {
            features = getDefaultFeatures(product.category);
        }
    } else {
        features = getDefaultFeatures(product.category);
    }
    
    features.forEach(feature => {
        const li = document.createElement('li');
        li.innerHTML = `<i class="bi bi-check-circle-fill"></i><span>${feature}</span>`;
        list.appendChild(li);
    });
}

function renderSpecifications(product) {
    const tbody = document.getElementById('specsTable').querySelector('tbody');
    tbody.innerHTML = '';
    
    const specs = {
        'Model': product.name,
        'Kategori': product.category || 'Networking',
        'Brand': 'Ruijie Networks',
        'Status': 'Ready Stock',
        'Garansi': '2 Tahun Resmi',
        'Kondisi': '100% Original'
    };
    
    Object.entries(specs).forEach(([key, value]) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${key}</td>
            <td>${value}</td>
        `;
        tbody.appendChild(row);
    });
}

function loadRelatedProducts(category) {
    fetch('/api/ruijie-products')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products) {
                let related = data.products.filter(p => p.category === category && p.id != productId);
                related = related.slice(0, 4);
                renderRelatedProducts(related);
            }
        });
}

function renderRelatedProducts(products) {
    const grid = document.getElementById('relatedGrid');
    grid.innerHTML = '';
    
    products.forEach(product => {
        const card = document.createElement('div');
        card.innerHTML = `
            <div class="ruijie-product-card">
                <div class="product-image">
                    <img src="${product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/200x150/f8f9fa/00A7E1?text=Ruijie'}" 
                         alt="${product.name}">
                </div>
                <div class="product-content">
                    <div class="product-category">${product.category || 'Ruijie'}</div>
                    <h3 class="product-name">${product.name}</h3>
                    <div class="product-price">Rp ${formatPrice(product.price)}</div>
                </div>
                <div class="product-footer">
                    <button class="btn-detail-small" onclick="window.location.href='/products/ruijie/${product.id}'">
                        <i class="bi bi-eye"></i> Detail
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function changeMainImage(src, thumbnail) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('id-ID');
}

function getDefaultFeatures(category) {
    return [
        'High-performance networking dengan throughput maksimal',
        'Enterprise-grade security dengan firewall built-in',
        'Cloud management untuk monitoring real-time',
        'Easy deployment dan konfigurasi mudah',
        'Scalable architecture untuk pertumbuhan bisnis',
        'Support 24/7 dan garansi resmi'
    ];
}

function buyProduct() {
    const productName = document.getElementById('productName').textContent;
    const price = document.getElementById('productPrice').textContent;
    const message = `Halo, saya tertarik untuk membeli:\n\n${productName}\nHarga: ${price}\n\nMohon informasi lebih lanjut.`;
    
    window.open('https://wa.me/62881025756671?text=' + encodeURIComponent(message), '_blank');
}

function contactWhatsApp() {
    const productName = document.getElementById('productName').textContent;
    const message = `Halo, saya ingin bertanya tentang produk ${productName}`;
    
    window.open('https://wa.me/62881025756671?text=' + encodeURIComponent(message), '_blank');
}

function showError(message) {
    document.getElementById('productName').textContent = 'Error';
    document.getElementById('productDescription').innerHTML = `
        <p style="color: #dc3545;">${message}</p>
        <button class="btn-action btn-buy" onclick="window.history.back()" style="margin-top: 20px; width: auto;">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>
    `;
}
</script>
@endsection