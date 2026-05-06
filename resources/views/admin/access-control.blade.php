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
                    
                    {{-- PERBAIKAN: Gunakan canany agar tombol muncul jika punya izin Create ATAU Manage --}}
                    @canany(['create_access_control', 'manage_access_control'])
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </button>
                    @endcanany
                </div>
            </div>
        </div>
    </div>

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

    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-select" id="filterBrand" onchange="renderTable()">
                <option value="">All Brands</option>
                <option value="hikvision">HIKVISION</option>
                <option value="dahua">Dahua</option>
                <option value="zkteco">ZKTeco</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filterStatus" onchange="renderTable()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filterFeatured" onchange="renderTable()">
                <option value="">All Products</option>
                <option value="1">Featured Only</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search products..." oninput="renderTable()">
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap" id="productsTable">
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
                        <tr id="loadingRow">
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

{{-- MODAL (Digunakan bersama untuk Add & Edit) --}}
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header shadow-sm">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_produk" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                            <select class="form-select" id="brand" required>
                                <option value="">Select Brand</option>
                                <option value="hikvision">HIKVISION</option>
                                <option value="dahua">Dahua</option>
                                <option value="zkteco">ZKTeco</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
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
                            <label class="form-label">Cost Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="harga_modal" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sell Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="harga_jual" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Original Price (Rp)</label>
                            <input type="number" class="form-control" id="original_price" min="0" placeholder="For sale badge">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stok" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label d-block">Featured</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_featured">
                                <label class="form-check-label" for="is_featured">Mark as featured</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Main Image</label>
                        <input type="file" class="form-control" id="gambar" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Max 2MB. JPG, PNG, GIF</small>
                        <div id="currentImagePreview" class="mt-2" style="display:none;">
                            <img id="currentImage" src="" alt="Preview" style="max-width: 150px; border-radius: 8px; border: 2px solid #eee;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="bi bi-save me-1"></i> Save Product
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
let modal; 

// PERBAIKAN: Jika user punya 'manage_access_control', izinkan juga Edit dan Delete di Javascript!
const canEdit = @json(auth()->check() && (auth()->user()->can('edit_access_control') || auth()->user()->can('manage_access_control')));
const canDelete = @json(auth()->check() && (auth()->user()->can('delete_access_control') || auth()->user()->can('manage_access_control')));

document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('productModal');
    if (typeof bootstrap !== 'undefined' && modalElement) {
        modal = new bootstrap.Modal(modalElement);
    }
    
    loadProducts();
    loadStatistics();
});

async function loadStatistics() {
    try {
        const response = await fetch('/api/admin/access-control/statistics', {
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        });
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
    const loadingRow = document.getElementById('loadingRow');
    if(loadingRow) loadingRow.style.display = 'table-row';
    
    try {
        const response = await fetch('/api/admin/access-control', {
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        });
        
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") === -1) {
            throw new Error('Server mengembalikan HTML, bukan JSON. Cek Network/Console (F12).');
        }

        const data = await response.json();
        
        if (data.success) {
            productsData = data.products || [];
            renderTable();
        } else {
            alert('Gagal mengambil data dari API.');
        }
    } catch (error) {
        console.error('Error loading products:', error);
        alert('Failed to load products: ' + error.message);
    }
}

function renderTable() {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';

    const brandVal = document.getElementById('filterBrand').value.toLowerCase();
    const statusVal = document.getElementById('filterStatus').value;
    const featuredVal = document.getElementById('filterFeatured').value;
    const searchVal = document.getElementById('searchInput').value.toLowerCase();

    const filteredProducts = productsData.filter(product => {
        const namaProdukSafe = product.name ? product.name.toLowerCase() : "";
        const skuSafe = product.sku ? product.sku.toLowerCase() : "";
        const brandSafe = product.brand ? product.brand.toLowerCase() : "";

        const matchBrand = brandVal === "" || brandSafe === brandVal;
        const matchStatus = statusVal === "" || product.status === statusVal;
        const matchFeatured = featuredVal === "" || product.is_featured == featuredVal;
        const matchSearch = namaProdukSafe.includes(searchVal) || skuSafe.includes(searchVal);
        
        return matchBrand && matchStatus && matchFeatured && matchSearch;
    });

    if (filteredProducts.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="mt-2 mb-0">No products found matching your filters</p>
                </td>
            </tr>`;
        return;
    }

    filteredProducts.forEach(product => {
        let actionButtons = '';
        
        // Tombol akan muncul karena variabel canEdit dan canDelete sudah di-update di atas
        if (canEdit) {
            actionButtons += `
                <button class="btn btn-sm btn-primary btn-action me-1" onclick="editProduct(${product.id})" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>`;
        }
        
        if (canDelete) {
            actionButtons += `
                <button class="btn btn-sm btn-danger btn-action" onclick="deleteProduct(${product.id})" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>`;
        }

        tbody.innerHTML += `
            <tr>
                <td>
                    <img src="${product.main_image ? '/storage/' + product.main_image : 'https://via.placeholder.com/60x60'}" 
                         class="product-image-thumb" 
                         alt="${product.name || 'No Name'}">
                </td>
                <td>
                    <div class="fw-bold text-wrap" style="max-width: 250px;">${product.name || '-'}</div>
                    ${product.is_featured ? '<span class="badge badge-featured mt-1">Featured</span>' : ''}
                </td>
                <td><span class="badge bg-secondary">${product.brand ? product.brand.toUpperCase() : '-'}</span></td>
                <td><code>${product.sku || '-'}</code></td>
                <td class="fw-bold text-success">Rp ${parseFloat(product.sell_price || 0).toLocaleString('id-ID')}</td>
                <td><span class="badge bg-${product.stock > 0 ? 'info' : 'danger'}">${product.stock || 0}</span></td>
                <td>
                    <span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">
                        ${product.status ? product.status.toUpperCase() : 'UNKNOWN'}
                    </span>
                </td>
                <td>${actionButtons}</td>
            </tr>`;
    });
}

function openAddModal() {
    if (!modal) return alert('Error: Modal not initialized.');
    
    currentEditId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('currentImagePreview').style.display = 'none';
    
    modal.show();
}

function editProduct(id) {
    const product = productsData.find(p => p.id === id);
    if (!product) return;
    
    currentEditId = id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = id;
    
    document.getElementById('nama_produk').value = product.name || '';
    document.getElementById('brand').value = product.brand ? product.brand.toLowerCase() : '';
    document.getElementById('sku').value = product.sku || '';
    document.getElementById('kategori').value = product.category || '';
    document.getElementById('deskripsi').value = product.description || '';
    
    document.getElementById('harga_modal').value = product.cost_price ? Math.floor(product.cost_price) : 0;
    document.getElementById('harga_jual').value = product.sell_price ? Math.floor(product.sell_price) : 0;
    document.getElementById('original_price').value = product.original_price ? Math.floor(product.original_price) : '';
    
    document.getElementById('stok').value = product.stock || 0;
    document.getElementById('status').value = product.status || 'active';
    document.getElementById('is_featured').checked = product.is_featured == 1;
    
    if (product.main_image) {
        document.getElementById('currentImage').src = '/storage/' + product.main_image;
        document.getElementById('currentImagePreview').style.display = 'block';
    } else {
        document.getElementById('currentImagePreview').style.display = 'none';
    }
    
    modal.show();
}

function previewImage(input) {
    const preview = document.getElementById('currentImagePreview');
    const img = document.getElementById('currentImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

async function saveProduct() {
    const form = document.getElementById('productForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData();
    
    formData.append('name', document.getElementById('nama_produk').value);
    formData.append('brand', document.getElementById('brand').value);
    formData.append('sku', document.getElementById('sku').value);
    formData.append('category', document.getElementById('kategori').value);
    formData.append('description', document.getElementById('deskripsi').value);
    formData.append('cost_price', document.getElementById('harga_modal').value);
    formData.append('sell_price', document.getElementById('harga_jual').value);
    formData.append('original_price', document.getElementById('original_price').value);
    formData.append('stock', document.getElementById('stok').value);
    formData.append('status', document.getElementById('status').value);
    formData.append('is_featured', document.getElementById('is_featured').checked ? 1 : 0);
    
    const imageFile = document.getElementById('gambar').files[0];
    if (imageFile) {
        formData.append('main_image', imageFile);
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    const submitBtn = document.querySelector('#productModal .btn-primary');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    submitBtn.disabled = true;
    
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
        
        if (!response.ok) {
            const err = await response.json();
            let errMsg = 'Failed to save product:\n';
            if (err.errors) {
                for (let key in err.errors) errMsg += `- ${err.errors[key][0]}\n`;
            } else {
                errMsg = err.message || 'Error occurred';
            }
            alert(errMsg);
            throw new Error('Validation failed');
        }
        
        const data = await response.json();
        
        if (data.success) {
            modal.hide();
            loadProducts();
            loadStatistics();
            document.getElementById('gambar').value = ""; 
        } else {
            alert(data.message || 'Failed to save product');
        }
    } catch (error) {
        console.error('Error saving product:', error);
    } finally {
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
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
        } else {
            alert(data.message || 'Failed to delete product');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Failed to delete product');
    }
}
</script>
@endsection