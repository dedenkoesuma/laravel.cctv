<!-- Brand Filter Component -->
<style>
.brand-filter-container {
    background: white;
    padding: 2rem;
    border-radius: 12px;
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

.brand-filter-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.brand-filter-btn {
    padding: 12px 32px;
    border-radius: 50px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    min-width: 120px;
    text-align: center;
}

.brand-filter-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.brand-filter-btn.active {
    background: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Brand specific colors (optional) */
.brand-filter-btn[data-brand="HIKVISION"]:hover,
.brand-filter-btn[data-brand="HIKVISION"].active {
    background: #DC143C;
    border-color: #DC143C;
}

.brand-filter-btn[data-brand="Dahua"]:hover,
.brand-filter-btn[data-brand="Dahua"].active {
    background: #003D7A;
    border-color: #003D7A;
}

.brand-filter-btn[data-brand="HiLook"]:hover,
.brand-filter-btn[data-brand="HiLook"].active {
    background: #8B4513;
    border-color: #8B4513;
}

.brand-filter-btn[data-brand="EZVIZ"]:hover,
.brand-filter-btn[data-brand="EZVIZ"].active {
    background: #4A90E2;
    border-color: #4A90E2;
}

.brand-filter-btn[data-brand="UNV"]:hover,
.brand-filter-btn[data-brand="UNV"].active {
    background: #6A0DAD;
    border-color: #6A0DAD;
}

.brand-filter-btn[data-brand="RUIJIE"]:hover,
.brand-filter-btn[data-brand="RUIJIE"].active {
    background: #00A7E1;
    border-color: #00A7E1;
}

.brand-filter-btn[data-brand="HIVIEW"]:hover,
.brand-filter-btn[data-brand="HIVIEW"].active {
    background: #8B0000;
    border-color: #8B0000;
}

/* Responsive */
@media (max-width: 768px) {
    .brand-filter-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .brand-filter-btn {
        width: 100%;
    }
}
</style>

<div class="brand-filter-container">
    <h3 class="filter-title">Filter by Brand</h3>
    <div class="brand-filter-buttons">
        <button class="brand-filter-btn active" data-brand="all" onclick="filterByBrand('all', this)">
            Semua Brand
        </button>
        <button class="brand-filter-btn" data-brand="HIKVISION" onclick="filterByBrand('HIKVISION', this)">
            HIKVISION
        </button>
        <button class="brand-filter-btn" data-brand="Dahua" onclick="filterByBrand('Dahua', this)">
            Dahua
        </button>
        <button class="brand-filter-btn" data-brand="HiLook" onclick="filterByBrand('HiLook', this)">
            HiLook
        </button>
        <button class="brand-filter-btn" data-brand="EZVIZ" onclick="filterByBrand('EZVIZ', this)">
            EZVIZ
        </button>
        <button class="brand-filter-btn" data-brand="UNV" onclick="filterByBrand('UNV', this)">
            UNV
        </button>
        <button class="brand-filter-btn" data-brand="RUIJIE" onclick="filterByBrand('RUIJIE', this)">
            RUIJIE
        </button>
        <button class="brand-filter-btn" data-brand="HIVIEW" onclick="filterByBrand('HIVIEW', this)">
            HIVIEW
        </button>
    </div>
</div>

<script>
function filterByBrand(brand, button) {
    // Get all product items
    const products = document.querySelectorAll('.product-item');
    const buttons = document.querySelectorAll('.brand-filter-btn');
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    
    // Filter products
    products.forEach(product => {
        const productBrand = product.dataset.brand;
        
        if (brand === 'all' || productBrand === brand) {
            product.style.display = 'block';
            // Animate in
            product.style.opacity = '0';
            setTimeout(() => {
                product.style.transition = 'opacity 0.3s ease';
                product.style.opacity = '1';
            }, 10);
        } else {
            product.style.display = 'none';
        }
    });
    
    // Show no results message if needed
    const visibleProducts = Array.from(products).filter(p => p.style.display !== 'none');
    
    if (visibleProducts.length === 0) {
        showNoResultsMessage(brand);
    } else {
        hideNoResultsMessage();
    }
}

function showNoResultsMessage(brand) {
    // Check if message already exists
    let message = document.getElementById('noResultsMessage');
    
    if (!message) {
        message = document.createElement('div');
        message.id = 'noResultsMessage';
        message.className = 'alert alert-info text-center';
        message.style.marginTop = '2rem';
        
        const productsContainer = document.querySelector('.row.g-4');
        if (productsContainer) {
            productsContainer.parentNode.insertBefore(message, productsContainer.nextSibling);
        }
    }
    
    message.innerHTML = `
        <i class="bi bi-info-circle me-2"></i>
        Tidak ada produk dari brand <strong>${brand}</strong> saat ini.
    `;
    message.style.display = 'block';
}

function hideNoResultsMessage() {
    const message = document.getElementById('noResultsMessage');
    if (message) {
        message.style.display = 'none';
    }
}
</script>