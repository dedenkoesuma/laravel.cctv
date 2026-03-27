@extends('layouts.simple')

@section('title', 'Admin Dashboard - TechStore')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-speedometer2 text-primary"></i> Dashboard Admin
            </h2>
            <p class="text-muted">Kelola produk, penjualan, dan analisis profit TechStore</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex gap-2 justify-content-end align-items-center">
                <span class="badge bg-success px-3 py-2">
                    <i class="bi bi-person-circle"></i> {{ session('admin_name', 'admin') }}
                </span>
                <span class="badge bg-info px-3 py-2">
                    <i class="bi bi-clock"></i> Login: {{ now()->format('H:i') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-box-seam fs-1 text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="totalProducts">0</h3>
                    <small class="text-muted">Total Produk</small>
                    <div class="mt-2">
                        <small class="text-success">Dynamic + Static</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-database fs-1 text-success"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="dynamicProducts">0</h3>
                    <small class="text-muted">Dynamic Products</small>
                    <div class="mt-2">
                        <small class="text-info">From Dashboard</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-grid-3x3 fs-1 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="staticProducts">512</h3>
                    <small class="text-muted">Static Products</small>
                    <div class="mt-2">
                        <small class="text-info">From Catalog</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-shop fs-1 text-danger"></i>
                    </div>
                    <h3 class="fw-bold mb-0">8</h3>
                    <small class="text-muted">Total Brands</small>
                    <div class="mt-2">
                        <small class="text-info">HIKVISION to Foreage</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-lightning-charge text-warning"></i> Quick Actions
                    </h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <button class="btn btn-primary" onclick="showAddDynamicModal()">
                            <i class="bi bi-plus-circle"></i> Tambah Dynamic Product
                        </button>
                        <a href="/admin/static-products" class="btn btn-success">
                            <i class="bi bi-pencil-square"></i> Edit Static Products (512 items)
                        </a>
                        <button class="btn btn-info" onclick="refreshStats()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Statistics
                        </button>
                        <button class="btn btn-warning" onclick="bulkActions()">
                            <i class="bi bi-stack"></i> Bulk Actions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Management Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-boxes text-primary"></i> Products Management
                    </h5>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="dynamic-tab" data-bs-toggle="tab" data-bs-target="#dynamic" type="button">
                                <i class="bi bi-database"></i> Dynamic Products
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="static-tab" data-bs-toggle="tab" data-bs-target="#static" type="button">
                                <i class="bi bi-grid-3x3"></i> Static Products
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                                <i class="bi bi-list-ul"></i> All Products
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        
                        <!-- DYNAMIC PRODUCTS TAB -->
                        <div class="tab-pane fade show active" id="dynamic" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Dynamic Products (<span id="dynamicCount">0</span>)</h6>
                                    <small class="text-muted">Products added via dashboard</small>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="showAddDynamicModal()">
                                    <i class="bi bi-plus-circle"></i> Add New
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="dynamicProductsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">ID</th>
                                            <th width="80">Image</th>
                                            <th>Name</th>
                                            <th width="100">Brand</th>
                                            <th width="100">SKU</th>
                                            <th width="120">Price</th>
                                            <th width="80">Stock</th>
                                            <th width="150">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dynamicProductsBody">
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="text-muted mt-2">Loading products...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- STATIC PRODUCTS TAB -->
                        <div class="tab-pane fade" id="static" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Static Products (512)</h6>
                                    <small class="text-muted">Catalog products (editable)</small>
                                </div>
                                <a href="/admin/static-products" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil-square"></i> Manage Static Products
                                </a>
                            </div>

                            <!-- Quick Filters -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="staticBrandFilter" onchange="loadStaticProducts()">
                                        <option value="">All Brands</option>
                                        <option value="hikvision">HIKVISION</option>
                                        <option value="dahua">Dahua</option>
                                        <option value="hilook">HiLook</option>
                                        <option value="ezviz">EZVIZ</option>
                                        <option value="unv">UNV</option>
                                        <option value="ruijie">RUIJIE</option>
                                        <option value="hiview">HIVIEW</option>
                                        <option value="foreage">Foreage</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="staticTabFilter" onchange="loadStaticProducts()">
                                        <option value="">All Tabs</option>
                                        <option value="basic">Basic</option>
                                        <option value="audio">Audio</option>
                                        <option value="fullcolor">Full Color</option>
                                        <option value="ipcamera">IP Camera</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-sm" id="staticSearchInput" placeholder="Search..." oninput="filterStaticTable()">
                                </div>
                                <div class="col-md-3 text-end">
                                    <span class="badge bg-info">Showing: <span id="staticCount">512</span> items</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="staticProductsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">ID</th>
                                            <th>Brand</th>
                                            <th>Tab</th>
                                            <th>SKU</th>
                                            <th>Name</th>
                                            <th width="100">Price</th>
                                            <th width="60">Stock</th>
                                            <th width="80">Status</th>
                                            <th width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="staticProductsBody">
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="spinner-border text-success" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="text-muted mt-2">Loading static products...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <nav aria-label="Static products pagination">
                                <ul class="pagination pagination-sm justify-content-center" id="staticPagination">
                                    <!-- Pagination will be generated here -->
                                </ul>
                            </nav>
                        </div>

                        <!-- ALL PRODUCTS TAB -->
                        <div class="tab-pane fade" id="all" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Combined View:</strong> This shows all products from both dynamic (dashboard) and static (catalog) sources.
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="allBrandFilter" onchange="loadAllProducts()">
                                        <option value="">All Brands</option>
                                        <option value="hikvision">HIKVISION</option>
                                        <option value="dahua">Dahua</option>
                                        <option value="hilook">HiLook</option>
                                        <option value="ezviz">EZVIZ</option>
                                        <option value="unv">UNV</option>
                                        <option value="ruijie">RUIJIE</option>
                                        <option value="hiview">HIVIEW</option>
                                        <option value="foreage">Foreage</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="allSourceFilter" onchange="loadAllProducts()">
                                        <option value="">All Sources</option>
                                        <option value="dynamic">Dynamic Only</option>
                                        <option value="static">Static Only</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" id="allSearchInput" placeholder="Search all products..." oninput="filterAllTable()">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="60">Source</th>
                                            <th>Name</th>
                                            <th>Brand</th>
                                            <th>SKU</th>
                                            <th width="100">Price</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="allProductsBody">
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="spinner-border text-warning" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="text-muted mt-2">Loading all products...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Dynamic Product Modal -->
<div class="modal fade" id="dynamicProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-plus-circle"></i> Add Dynamic Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="dynamicProductForm" enctype="multipart/form-data">
                    <input type="hidden" id="productId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                            <select class="form-select" id="productBrand" required>
                                <option value="">Select Brand</option>
                                <option value="HIKVISION">HIKVISION</option>
                                <option value="Dahua">Dahua</option>
                                <option value="HiLook">HiLook</option>
                                <option value="EZVIZ">EZVIZ</option>
                                <option value="UNV">UNV</option>
                                <option value="RUIJIE">RUIJIE</option>
                                <option value="HIVIEW">HIVIEW</option>
                                <option value="Foreage">Foreage</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">SKU</label>
                            <input type="text" class="form-control" id="productSKU">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <input type="text" class="form-control" id="productCategory" placeholder="e.g., DVR, NVR, Camera">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Purchase Price (IDR)</label>
                            <input type="number" class="form-control" id="productPurchasePrice" min="0" step="1000">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Selling Price (IDR)</label>
                            <input type="number" class="form-control" id="productSellingPrice" min="0" step="1000">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Stock</label>
                            <input type="number" class="form-control" id="productStock" min="0" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description / Specs</label>
                        <textarea class="form-control" id="productDescription" rows="4" placeholder="Enter product specifications (one per line)"></textarea>
                        <small class="text-muted">Tip: One spec per line for better display</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Image</label>
                        <input type="file" class="form-control" id="productImage" accept="image/*" onchange="previewDynamicImage(this)">
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveDynamicProduct()">
                    <i class="bi bi-save"></i> Save Product
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Load stats on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadDynamicProducts();
    loadStaticProducts();
});

// Load statistics
function loadStatistics() {
    // Load dynamic products count
    fetch('/api/products')
        .then(response => response.json())
        .then(data => {
            const dynamicCount = data.products ? data.products.length : 0;
            document.getElementById('dynamicProducts').textContent = dynamicCount;
            document.getElementById('dynamicCount').textContent = dynamicCount;
            document.getElementById('totalProducts').textContent = dynamicCount + 512;
        });
    
    // Static products count is fixed at 512
    document.getElementById('staticProducts').textContent = '512';
    document.getElementById('staticCount').textContent = '512';
}

// Load dynamic products
function loadDynamicProducts() {
    fetch('/api/products')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('dynamicProductsBody');
            tbody.innerHTML = '';
            
            if (data.products && data.products.length > 0) {
                data.products.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td>
                                ${product.gambar ? 
                                    `<img src="/storage/${product.gambar}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` : 
                                    '<div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 10px;">No Image</div>'
                                }
                            </td>
                            <td>${product.nama_produk}</td>
                            <td><span class="badge bg-primary">${product.brand}</span></td>
                            <td><code>${product.sku || '-'}</code></td>
                            <td>Rp ${formatNumber(product.harga_jual || 0)}</td>
                            <td><span class="badge ${product.stok > 0 ? 'bg-success' : 'bg-danger'}">${product.stok}</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editDynamicProduct(${product.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteDynamicProduct(${product.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No dynamic products yet. Click "Add New" to create one!</p>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading dynamic products:', error);
        });
}

// Load static products
function loadStaticProducts() {
    const brand = document.getElementById('staticBrandFilter').value;
    const tab = document.getElementById('staticTabFilter').value;
    
    let url = '/api/static-products?';
    if (brand) url += `brand=${brand}&`;
    if (tab) url += `tab_category=${tab}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('staticProductsBody');
            tbody.innerHTML = '';
            
            if (data.products && data.products.length > 0) {
                document.getElementById('staticCount').textContent = data.products.length;
                
                // Show first 50 items only (for performance)
                const items = data.products.slice(0, 50);
                
                items.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td><span class="badge bg-primary text-uppercase">${product.brand}</span></td>
                            <td><span class="badge bg-info">${product.tab_category}</span></td>
                            <td><code class="small">${product.sku}</code></td>
                            <td class="small">${product.nama_produk}</td>
                            <td class="small">Rp ${formatNumber(product.harga_jual || 0)}</td>
                            <td><span class="badge bg-success">${product.stok}</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" ${product.is_active ? 'checked' : ''} disabled>
                                </div>
                            </td>
                            <td>
                                <a href="/admin/static-products" class="btn btn-sm btn-success" title="Edit in Static Admin">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
                
                if (data.products.length > 50) {
                    tbody.innerHTML += `
                        <tr>
                            <td colspan="9" class="text-center bg-light">
                                <small class="text-muted">
                                    Showing 50 of ${data.products.length} items. 
                                    <a href="/admin/static-products">View all in Static Products Manager</a>
                                </small>
                            </td>
                        </tr>
                    `;
                }
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <p class="text-muted">No static products found with current filters.</p>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading static products:', error);
        });
}

// Show add product modal
function showAddDynamicModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-plus-circle"></i> Add Dynamic Product';
    document.getElementById('dynamicProductForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    new bootstrap.Modal(document.getElementById('dynamicProductModal')).show();
}

// Preview image
function previewDynamicImage(input) {
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="alert alert-success">
                    <strong>Image Preview:</strong><br>
                    <img src="${e.target.result}" class="img-thumbnail mt-2" style="max-width: 200px;">
                </div>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Save dynamic product
function saveDynamicProduct() {
    // Implementation for saving product
    alert('Save product functionality - to be connected to your existing save logic');
    // You can reuse your existing save product code here
}

// Edit dynamic product
function editDynamicProduct(id) {
    alert('Edit product ID: ' + id);
    // Load product data and show modal
}

// Delete dynamic product
function deleteDynamicProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        alert('Delete product ID: ' + id);
        // Implement delete logic
    }
}

// Refresh statistics
function refreshStats() {
    loadStatistics();
    loadDynamicProducts();
    loadStaticProducts();
}

// Format number
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Bulk actions
function bulkActions() {
    alert('Bulk actions - coming soon!');
}

// Filter static table
function filterStaticTable() {
    const searchTerm = document.getElementById('staticSearchInput').value.toLowerCase();
    const tbody = document.getElementById('staticProductsBody');
    const rows = tbody.getElementsByTagName('tr');
    
    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    }
}

// Load all products (combined view)
function loadAllProducts() {
    // Implementation for loading all products
    const tbody = document.getElementById('allProductsBody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><p class="text-muted">Combined view - loading...</p></td></tr>';
}

// Filter all products table
function filterAllTable() {
    // Implementation for filtering all products
}
</script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 600;
}

.badge {
    font-weight: 500;
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.85em;
}
</style>
@endsection