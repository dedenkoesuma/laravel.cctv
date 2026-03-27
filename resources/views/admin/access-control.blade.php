@extends('layouts.simple')

@section('title', 'Access Control Management - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-fingerprint"></i> Access Control Management
                    </h2>
                    <p class="text-muted mb-0">Manage access control products, images, and specifications</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statisticsCards">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary">
                                <i class="bi bi-box"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-label">Total Products</div>
                            <div class="stat-value" id="statTotal">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-label">Active</div>
                            <div class="stat-value" id="statActive">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning">
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-label">Featured</div>
                            <div class="stat-value" id="statFeatured">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-info">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-label">Brands</div>
                            <div class="stat-value" id="statBrands">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-select" id="filterBrand">
                <option value="">All Brands</option>
                <option value="hikvision">HIKVISION</option>
                <option value="dahua">Dahua</option>
                <option value="ezviz">EZVIZ</option>
                <option value="unv">UNV</option>
                <option value="zkteco">ZKTeco</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filterStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filterFeatured">
                <option value="">All Products</option>
                <option value="1">Featured Only</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="productsTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0">Loading products...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="nama_produk" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand *</label>
                            <select class="form-select" id="brand" required>
                                <option value="">Select Brand</option>
                                <option value="hikvision">HIKVISION</option>
                                <option value="dahua">Dahua</option>
                                <option value="ezviz">EZVIZ</option>
                                <option value="unv">UNV</option>
                                <option value="zkteco">ZKTeco</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU *</label>
                            <input type="text" class="form-control" id="sku" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" id="kategori" placeholder="e.g., Fingerprint, Face Recognition">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="deskripsi" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cost Price (Rp) *</label>
                            <input type="number" class="form-control" id="harga_modal" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sell Price (Rp) *</label>
                            <input type="number" class="form-control" id="harga_jual" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Original Price (Rp)</label>
                            <input type="number" class="form-control" id="original_price" min="0" placeholder="For sale badge">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" class="form-control" id="stok" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-select" id="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label d-block">Featured</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured">
                                <label class="form-check-label" for="is_featured">Mark as featured</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Main Image</label>
                        <input type="file" class="form-control" id="gambar" accept="image/*">
                        <small class="text-muted">Max 2MB. JPG, PNG, GIF</small>
                        <div id="currentImagePreview" class="mt-2" style="display:none;">
                            <img id="currentImage" src="" alt="Current" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="bi bi-check-circle"></i> Save Product
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.product-image-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.badge-featured {
    background: #ffc107;
    color: #333;
}

.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Dashboard Button Styling */
.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-outline-secondary i {
    margin-right: 0.25rem;
}
</style>

<script>
let productsData = [];
let currentEditId = null;
let modal; // Declare modal variable

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    
    // Initialize modal AFTER DOM is ready
    const modalElement = document.getElementById('productModal');
    console.log('Modal element:', modalElement);
    
    if (typeof bootstrap !== 'undefined' && modalElement) {
        modal = new bootstrap.Modal(modalElement);
        console.log('Modal initialized successfully');
    } else {
        console.error('Bootstrap or modal element not found!');
    }
    
    loadProducts();
    loadStatistics();
    
    // Setup filters
    document.getElementById('filterBrand').addEventListener('change', loadProducts);
    document.getElementById('filterStatus').addEventListener('change', loadProducts);
    document.getElementById('filterFeatured').addEventListener('change', loadProducts);
    
    // Setup search with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadProducts, 500);
    });
});

async function loadStatistics() {
    try {
        const response = await fetch('/api/admin/access-control/statistics');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('statTotal').textContent = data.statistics.total;
            document.getElementById('statActive').textContent = data.statistics.active;
            document.getElementById('statFeatured').textContent = data.statistics.featured;
            document.getElementById('statBrands').textContent = data.statistics.brands;
        }
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

async function loadProducts() {
    const brand = document.getElementById('filterBrand').value;
    const status = document.getElementById('filterStatus').value;
    const featured = document.getElementById('filterFeatured').value;
    const search = document.getElementById('searchInput').value;
    
    let url = '/api/admin/access-control?';
    if (brand) url += `brand=${brand}&`;
    if (status) url += `status=${status}&`;
    if (featured) url += `featured=${featured}&`;
    if (search) url += `search=${search}&`;
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            productsData = data.products;
            displayProducts(data.products);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showError('Failed to load products');
    }
}

function displayProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="mt-2 mb-0">No products found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = products.map(product => `
        <tr>
            <td>
                <img src="${product.gambar ? '/storage/' + product.gambar : 'https://via.placeholder.com/60x60'}" 
                     class="product-image-thumb" 
                     alt="${product.nama_produk}">
            </td>
            <td>
                <div class="fw-bold">${product.nama_produk}</div>
                ${product.is_featured ? '<span class="badge badge-featured">Featured</span>' : ''}
            </td>
            <td>${product.brand.toUpperCase()}</td>
            <td><code>${product.sku}</code></td>
            <td>Rp ${parseInt(product.harga_jual).toLocaleString('id-ID')}</td>
            <td>${product.stok}</td>
            <td>
                <span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">
                    ${product.status}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-primary btn-action" onclick="editProduct(${product.id})" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-action" onclick="deleteProduct(${product.id})" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openAddModal() {
    console.log('openAddModal called');
    console.log('Modal object:', modal);
    
    if (!modal) {
        console.error('Modal not initialized!');
        alert('Error: Modal not initialized. Please refresh the page.');
        return;
    }
    
    currentEditId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('currentImagePreview').style.display = 'none';
    
    try {
        modal.show();
        console.log('Modal shown');
    } catch (error) {
        console.error('Error showing modal:', error);
        alert('Error opening modal: ' + error.message);
    }
}

async function editProduct(id) {
    const product = productsData.find(p => p.id === id);
    if (!product) return;
    
    currentEditId = id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = id;
    document.getElementById('nama_produk').value = product.nama_produk;
    document.getElementById('brand').value = product.brand;
    document.getElementById('sku').value = product.sku;
    document.getElementById('kategori').value = product.kategori || '';
    document.getElementById('deskripsi').value = product.deskripsi || '';
    document.getElementById('harga_modal').value = product.harga_modal;
    document.getElementById('harga_jual').value = product.harga_jual;
    document.getElementById('original_price').value = product.original_price || '';
    document.getElementById('stok').value = product.stok;
    document.getElementById('status').value = product.status;
    document.getElementById('is_featured').checked = product.is_featured;
    
    if (product.gambar) {
        document.getElementById('currentImage').src = '/storage/' + product.gambar;
        document.getElementById('currentImagePreview').style.display = 'block';
    }
    
    modal.show();
}

async function saveProduct() {
    const formData = new FormData();
    
    formData.append('nama_produk', document.getElementById('nama_produk').value);
    formData.append('brand', document.getElementById('brand').value);
    formData.append('sku', document.getElementById('sku').value);
    formData.append('kategori', document.getElementById('kategori').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('harga_modal', document.getElementById('harga_modal').value);
    formData.append('harga_jual', document.getElementById('harga_jual').value);
    formData.append('original_price', document.getElementById('original_price').value);
    formData.append('stok', document.getElementById('stok').value);
    formData.append('status', document.getElementById('status').value);
    formData.append('is_featured', document.getElementById('is_featured').checked ? 1 : 0);
    
    const imageFile = document.getElementById('gambar').files[0];
    if (imageFile) {
        formData.append('gambar', imageFile);
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    try {
        const url = currentEditId ? `/api/admin/access-control/${currentEditId}` : '/api/admin/access-control';
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            modal.hide();
            loadProducts();
            loadStatistics();
            showSuccess(currentEditId ? 'Product updated successfully!' : 'Product created successfully!');
        } else {
            showError(data.message || 'Failed to save product');
        }
    } catch (error) {
        console.error('Error saving product:', error);
        showError('Failed to save product');
    }
}

async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    try {
        const response = await fetch(`/api/admin/access-control/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadProducts();
            loadStatistics();
            showSuccess('Product deleted successfully!');
        } else {
            showError(data.message || 'Failed to delete product');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        showError('Failed to delete product');
    }
}

function showSuccess(message) {
    alert(message); // Replace with toast notification
}

function showError(message) {
    alert('Error: ' + message); // Replace with toast notification
}
</script>

@endsection