@extends('layouts.simple')

@section('title', 'Access Control - TechStore')

@section('content')
<style>
/* ===== PAGE HEADER ===== */
.page-header {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
    padding: 80px 20px 60px;
    margin-bottom: 50px;
}

.page-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
}

.page-description {
    font-size: clamp(1rem, 2vw, 1.2rem);
    opacity: 0.95;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

/* ===== BREADCRUMB ===== */
.breadcrumb-custom {
    background: transparent;
    padding: 0;
    margin-bottom: 2rem;
}

.breadcrumb-custom .breadcrumb-item {
    color: rgba(255,255,255,0.8);
}

.breadcrumb-custom .breadcrumb-item.active {
    color: white;
}

.breadcrumb-custom .breadcrumb-item a {
    color: white;
    text-decoration: none;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 3rem;
}

.filter-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
}

.filter-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 12px 32px;
    border-radius: 50px;
    border: 2px solid #ff6b6b;
    background: white;
    color: #ff6b6b;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 120px;
    text-align: center;
}

.filter-btn:hover {
    background: #ff6b6b;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
}

.filter-btn.active {
    background: #ff6b6b;
    color: white;
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
}

/* ===== PRODUCT CARDS ===== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.product-image-wrapper {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.badge-featured {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #333;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 2;
}

.badge-sale {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 2;
}

.product-content {
    padding: 1.5rem;
}

.product-brand {
    color: #ff6b6b;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    min-height: 2.8em;
}

.product-sku {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 1rem;
}

.product-price-wrapper {
    margin-bottom: 1rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ff6b6b;
}

.product-original-price {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.product-stock {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.stock-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.stock-indicator.in-stock {
    background: #28a745;
}

.stock-indicator.low-stock {
    background: #ffc107;
}

.stock-indicator.out-of-stock {
    background: #dc3545;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-detail {
    flex: 1;
    padding: 12px;
    background: white;
    color: #ff6b6b;
    border: 2px solid #ff6b6b;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    background: #ff6b6b;
    color: white;
}

.btn-buy {
    flex: 1;
    padding: 12px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-buy:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
}

.btn-buy:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

/* ===== LOADING & EMPTY STATE ===== */
.loading-spinner {
    text-align: center;
    padding: 3rem;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff6b6b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-state-description {
    color: #666;
    margin-bottom: 1.5rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    
    .filter-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-btn {
        width: 100%;
    }
    
    .page-header {
        padding: 60px 20px 40px;
    }
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Access Control</li>
            </ol>
        </nav>
        
        <h1 class="page-title">
            <i class="bi bi-fingerprint"></i> Access Control Systems
        </h1>
        <p class="page-description">
            Professional access control solutions for enhanced security. Featuring fingerprint, face recognition, and card readers.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- Filter Section -->
    <div class="filter-section">
        <h3 class="filter-title">
            <i class="bi bi-funnel"></i> Filter by Brand
        </h3>
        <div class="filter-buttons" id="brandFilters">
            <button class="filter-btn active" data-brand="">
                All Brands
            </button>
            <button class="filter-btn" data-brand="HIKVISION">
                HIKVISION
            </button>
            <button class="filter-btn" data-brand="Dahua">
                Dahua
            </button>
            <button class="filter-btn" data-brand="EZVIZ">
                EZVIZ
            </button>
            <button class="filter-btn" data-brand="UNV">
                UNV
            </button>
            <button class="filter-btn" data-brand="ZKTeco">
                ZKTeco
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div id="productsContainer">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading products...</p>
        </div>
    </div>

</div>

<script>
let currentBrand = '';

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    
    // Setup brand filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all buttons
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            
            // Add active to clicked button
            this.classList.add('active');
            
            // Get brand and load products
            currentBrand = this.dataset.brand;
            loadProducts();
        });
    });
});

async function loadProducts() {
    const container = document.getElementById('productsContainer');
    
    // Show loading
    container.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading products...</p>
        </div>
    `;
    
    try {
        let url = '/api/access-control';
        if (currentBrand) {
            url += `?brand=${currentBrand}`;
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
        console.log('API Response:', data); // Debug
        
        if (data.success && data.products.length > 0) {
            displayProducts(data.products);
        } else {
            showEmptyState();
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showErrorState();
    }
}

function displayProducts(products) {
    const container = document.getElementById('productsContainer');
    
    let html = '<div class="products-grid">';
    
    products.forEach(product => {
        // Parse specifications if it's a JSON string
        const specifications = product.specifications ? 
            (typeof product.specifications === 'string' ? JSON.parse(product.specifications) : product.specifications) 
            : [];
        
        const hasDiscount = product.original_price && product.original_price > product.sell_price;
        const stockStatus = getStockStatus(product.stock);
        
        html += `
            <div class="product-card">
                ${product.is_featured ? '<div class="badge-featured">⭐ Featured</div>' : ''}
                ${hasDiscount ? '<div class="badge-sale">💰 SALE!</div>' : ''}
                
                <div class="product-image-wrapper">
                    <img src="${product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/300x250/f8f9fa/ff6b6b?text=Access+Control'}" 
                         alt="${product.name}" 
                         class="product-image"
                         onerror="this.src='https://via.placeholder.com/300x250/f8f9fa/ff6b6b?text=No+Image'">
                </div>
                
                <div class="product-content">
                    <div class="product-brand">${product.brand}</div>
                    <h3 class="product-title">${product.name}</h3>
                    <div class="product-sku">SKU: ${product.sku}</div>
                    
                    <div class="product-price-wrapper">
                        <span class="product-price">Rp ${parseInt(product.sell_price).toLocaleString('id-ID')}</span>
                        ${hasDiscount ? `<span class="product-original-price">Rp ${parseInt(product.original_price).toLocaleString('id-ID')}</span>` : ''}
                    </div>
                    
                    <div class="product-stock">
                        <span class="stock-indicator ${stockStatus.class}"></span>
                        <span>${stockStatus.text}</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="btn-detail" onclick="viewDetail(${product.id})">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                        <button class="btn-buy" ${product.stock === 0 ? 'disabled' : ''} onclick="buyProduct('${product.name}', '${product.sku}')">
                            <i class="bi bi-cart-plus"></i> Beli
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function getStockStatus(stock) {
    if (stock === 0) {
        return { text: 'Out of Stock', class: 'out-of-stock' };
    } else if (stock < 10) {
        return { text: `Low Stock (${stock} left)`, class: 'low-stock' };
    } else {
        return { text: 'In Stock', class: 'in-stock' };
    }
}

function showEmptyState() {
    const container = document.getElementById('productsContainer');
    container.innerHTML = `
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h3 class="empty-state-title">No Products Found</h3>
            <p class="empty-state-description">
                ${currentBrand ? `No access control products available for ${currentBrand} at the moment.` : 'No access control products available at the moment.'}
            </p>
            <button class="btn-detail" onclick="currentBrand=''; document.querySelector('.filter-btn[data-brand=\'\']').click();">
                View All Products
            </button>
        </div>
    `;
}

function showErrorState() {
    const container = document.getElementById('productsContainer');
    container.innerHTML = `
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-exclamation-triangle text-danger"></i>
            </div>
            <h3 class="empty-state-title">Error Loading Products</h3>
            <p class="empty-state-description">
                Unable to load products. Please try again later.
            </p>
            <button class="btn-detail" onclick="loadProducts();">
                <i class="bi bi-arrow-clockwise"></i> Retry
            </button>
        </div>
    `;
}

function viewDetail(productId) {
    // Redirect to detail page
    window.location.href = `/access-control/${productId}`;
}

function buyProduct(productName, sku) {
    const message = `Halo, saya tertarik dengan:\n*${productName}*\nSKU: ${sku}\n\nMohon info lebih lanjut untuk Access Control System ini.`;
    const whatsappUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
</script>

@endsection