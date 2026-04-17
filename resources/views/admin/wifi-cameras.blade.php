@extends('layouts.simple')

@section('title', 'Manage WiFi Camera - Admin Dashboard')

@section('content')
<style>
/* Reuse dashboard styles */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.product-img-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.badge-stock {
    padding: 0.4rem 0.8rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.image-preview-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.image-preview {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e0e0e0;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview .remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.spec-input-group {
    margin-bottom: 10px;
}

.spec-input-wrapper {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.spec-input-wrapper input {
    flex: 1;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
}

.modal-lg-custom {
    max-width: 900px;
}
</style>
@php
    $adminRoleName = session('admin_role');
    $currentRole = \Spatie\Permission\Models\Role::where('name', $adminRoleName)->first();
    
    // Fungsi ngecek izin murni HANYA dari centangan di database
    $canAccess = function($permissionName) use ($currentRole) {
        return $currentRole ? $currentRole->hasPermissionTo($permissionName) : false;
    };
@endphp
<div id="dashboardContent">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-shop me-2"></i>TechStore Admin
            </a>
            <div class="ms-auto">
                <a class="nav-link text-white" href="{{ url('/dashboard') }}">
                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Manage WiFi Camera</h1>
            <p class="dashboard-subtitle">Kelola produk WiFi Camera: tambah, edit, hapus, dan upload gambar</p>
        </div>

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="section-title mb-0">Daftar WiFi Camera</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCameraModal">
                <i class="bi bi-plus-circle me-2"></i>Tambah WiFi Camera
            </button>
        </div>

        <div class="table-card mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchCamera" placeholder="Cari nama produk..." oninput="filterTable()">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterBrand" onchange="loadCameras()">
                                <option value="DAHUA">DAHUA</option>
                                <option value="HIVIEW">HIVIEW</option>
                                <option value="EZVIZ">EZVIZ</option>
                                <option value="IMOU">IMOU</option>
                                <option value="TP-LINK">TP-LINK</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus" onchange="loadCameras()">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary w-100" onclick="loadCameras()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div id="loadingSpinner" class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>
            
            <div id="tableContainer" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Brand</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cameraTableBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah WiFi Camera Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCameraForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand *</label>
                            <select class="form-select" name="brand" required>
                                <option value="">Pilih Brand</option>
                                <option value="DAHUA">DAHUA</option>
                                <option value="HIVIEW">HIVIEW</option>
                                <option value="EZVIZ">EZVIZ</option>
                                <option value="IMOU">IMOU</option>
                                <option value="TP-LINK">TP-LINK</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle/Deskripsi Singkat *</label>
                        <input type="text" class="form-control" name="subtitle" 
                               placeholder="Contoh: Smart Pan & Tilt Indoor WiFi Camera" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Jual (Rp) *</label>
                            <input type="number" class="form-control" name="price" min="0" step="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Asli/Coret (Rp)</label>
                            <input type="number" class="form-control" name="original_price" min="0" step="1">
                            <small class="text-muted">Kosongkan jika tidak ada diskon</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stock" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" placeholder="WIFI-001">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Utama *</label>
                        <input type="file" class="form-control" name="main_image" accept="image/*" 
                               onchange="previewMainImage(this, 'addMainPreview')" required>
                        <div id="addMainPreview" class="image-preview-container"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Galeri Gambar (Maksimal 5)</label>
                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*" 
                               multiple onchange="previewGalleryImages(this, 'addGalleryPreview')">
                        <div id="addGalleryPreview" class="image-preview-container"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Spesifikasi</label>
                        <div id="specificationsContainer">
                            <div class="spec-input-wrapper">
                                <input type="text" class="form-control" name="specifications[]" 
                                       placeholder="Contoh: 2MP 1080P Resolution">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeSpec(this)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addSpecField()">
                            <i class="bi bi-plus"></i> Tambah Spesifikasi
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Paket Termasuk</label>
                        <div id="packageContainer">
                            <div class="spec-input-wrapper">
                                <input type="text" class="form-control" name="package_includes[]" 
                                       placeholder="Contoh: 1 Kamera C6N 2MP">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removePackage(this)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addPackageField()">
                            <i class="bi bi-plus"></i> Tambah Item
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                            <label class="form-check-label" for="isFeatured">
                                Tampilkan sebagai produk unggulan
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveCamera()">
                    <i class="bi bi-save me-2"></i>Simpan Camera
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit WiFi Camera</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCameraForm" enctype="multipart/form-data">
                    <input type="hidden" id="editId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="editName">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand</label>
                            <select class="form-select" id="editBrand">
                                <option value="DAHUA">DAHUA</option>
                                <option value="EZVIZ">EZVIZ</option>
                                <option value="IMOU">IMOU</option>
                                <option value="TP-LINK">TP-LINK</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="editSubtitle">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Jual (Rp)</label>
                            <input type="number" class="form-control" id="editPrice" min="0" step="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Asli (Rp)</label>
                            <input type="number" class="form-control" id="editOriginalPrice" min="0" step="1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" id="editStock" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" id="editSku">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Gambar Baru</label>
                        <input type="file" class="form-control" id="editMainImage" accept="image/*"
                               onchange="previewMainImage(this, 'editMainPreview')">
                        <div id="editCurrentImage" class="mt-2"></div>
                        <div id="editMainPreview" class="image-preview-container"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="editIsFeatured">
                                <label class="form-check-label" for="editIsFeatured">
                                    Produk Unggulan
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateCamera()">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cameras = [];

// Load cameras on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCameras();
});

function loadCameras() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';
    
    const brand = document.getElementById('filterBrand').value;
    const status = document.getElementById('filterStatus').value;
    
    let url = '/api/admin/wifi-cameras?';
    if (brand) url += `brand=${brand}&`;
    if (status) url += `status=${status}`;
    
    fetch(url, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            const text = await response.text();
            console.error('Got HTML instead of JSON:', text.substring(0, 500));
            throw new Error('API returned HTML instead of JSON');
        }
    })
    .then(data => {
        if (data.success) {
            cameras = data.cameras || [];
            renderTable();
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('cameraTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5 text-danger">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <p class="mt-3">Error loading cameras: ${error.message}</p>
                    <button class="btn btn-primary" onclick="loadCameras()">Try Again</button>
                </td>
            </tr>
        `;
    });
}

function renderTable() {
    const tbody = document.getElementById('cameraTableBody');
    tbody.innerHTML = '';
    
    if (cameras.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="bi bi-camera-video" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 mb-2"><strong>Belum ada produk WiFi Camera</strong></p>
                    <p class="text-muted">Klik tombol "Tambah WiFi Camera" untuk menambah produk pertama</p>
                </td>
            </tr>
        `;
        return;
    }
    
    cameras.forEach((camera, index) => {
        const row = `
            <tr>
                <td>${camera.id}</td>
                <td>
                    ${camera.main_image ? 
                        `<img src="/storage/${camera.main_image}" class="product-img-thumb" alt="">` : 
                        `<div class="bg-light d-flex align-items-center justify-content-center product-img-thumb">
                            <i class="bi bi-image text-muted"></i>
                        </div>`
                    }
                </td>
                <td>
                    <strong>${camera.name}</strong><br>
                    <small class="text-muted">${camera.subtitle || ''}</small>
                </td>
                <td><span class="badge bg-primary">${camera.brand}</span></td>
                <td>Rp ${formatNumber(camera.price)}</td>
                <td><span class="badge ${camera.stock > 0 ? 'bg-success' : 'bg-danger'}">${camera.stock}</span></td>
                <td>
                    <span class="badge ${camera.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                        ${camera.status}
                    </span>
                </td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-primary btn-action" onclick="editCamera(${camera.id})">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger btn-action" onclick="deleteCamera(${camera.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterTable() {
    const search = document.getElementById('searchCamera').value.toLowerCase();
    const rows = document.querySelectorAll('#cameraTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
}

function previewMainImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="image-preview">
                    <img src="${e.target.result}" alt="Preview">
                </div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGalleryImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).slice(0, 5).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery ${index + 1}">
                    <button type="button" class="remove-image" onclick="removeGalleryImage(this)">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeGalleryImage(button) {
    button.closest('.image-preview').remove();
}

function addSpecField() {
    const container = document.getElementById('specificationsContainer');
    const wrapper = document.createElement('div');
    wrapper.className = 'spec-input-wrapper';
    wrapper.innerHTML = `
        <input type="text" class="form-control" name="specifications[]" placeholder="Masukkan spesifikasi">
        <button type="button" class="btn btn-sm btn-danger" onclick="removeSpec(this)">
            <i class="bi bi-x"></i>
        </button>
    `;
    container.appendChild(wrapper);
}

function removeSpec(button) {
    const container = document.getElementById('specificationsContainer');
    if (container.children.length > 1) {
        button.closest('.spec-input-wrapper').remove();
    } else {
        alert('Minimal harus ada 1 spesifikasi');
    }
}

function addPackageField() {
    const container = document.getElementById('packageContainer');
    const wrapper = document.createElement('div');
    wrapper.className = 'spec-input-wrapper';
    wrapper.innerHTML = `
        <input type="text" class="form-control" name="package_includes[]" placeholder="Masukkan item paket">
        <button type="button" class="btn btn-sm btn-danger" onclick="removePackage(this)">
            <i class="bi bi-x"></i>
        </button>
    `;
    container.appendChild(wrapper);
}

function removePackage(button) {
    const container = document.getElementById('packageContainer');
    if (container.children.length > 1) {
        button.closest('.spec-input-wrapper').remove();
    } else {
        alert('Minimal harus ada 1 item paket');
    }
}

function saveCamera() {
    const form = document.getElementById('addCameraForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const saveBtn = document.querySelector('#addCameraModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    fetch('/api/admin/wifi-cameras', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            const text = await response.text();
            console.error('Got HTML:', text.substring(0, 500));
            throw new Error('Server returned HTML instead of JSON');
        }
    })
    .then(data => {
        if (data.success) {
            alert('✅ Camera berhasil ditambahkan!');
            bootstrap.Modal.getInstance(document.getElementById('addCameraModal')).hide();
            form.reset();
            document.getElementById('addMainPreview').innerHTML = '';
            document.getElementById('addGalleryPreview').innerHTML = '';
            loadCameras();
        } else {
            alert('❌ Error: ' + (data.message || 'Gagal menambahkan camera'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error: ' + error.message);
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function editCamera(id) {
    const camera = cameras.find(c => c.id === id);
    if (!camera) return;
    
    document.getElementById('editId').value = camera.id;
    document.getElementById('editName').value = camera.name;
    document.getElementById('editBrand').value = camera.brand;
    document.getElementById('editSubtitle').value = camera.subtitle || '';
    document.getElementById('editPrice').value = camera.price;
    document.getElementById('editOriginalPrice').value = camera.original_price || '';
    document.getElementById('editStock').value = camera.stock;
    document.getElementById('editSku').value = camera.sku || '';
    document.getElementById('editStatus').value = camera.status;
    document.getElementById('editIsFeatured').checked = camera.is_featured;
    
    const currentImageDiv = document.getElementById('editCurrentImage');
    if (camera.main_image) {
        currentImageDiv.innerHTML = `
            <div class="alert alert-info">
                <strong>Current Image:</strong><br>
                <img src="/storage/${camera.main_image}" class="img-thumbnail" style="max-width: 200px;">
            </div>
        `;
    } else {
        currentImageDiv.innerHTML = '<div class="alert alert-secondary">No image</div>';
    }
    
    document.getElementById('editMainPreview').innerHTML = '';
    
    new bootstrap.Modal(document.getElementById('editCameraModal')).show();
}

function updateCamera() {
    const id = document.getElementById('editId').value;
    const formData = new FormData();
    
    formData.append('name', document.getElementById('editName').value);
    formData.append('brand', document.getElementById('editBrand').value);
    formData.append('subtitle', document.getElementById('editSubtitle').value);
    formData.append('price', document.getElementById('editPrice').value);
    formData.append('original_price', document.getElementById('editOriginalPrice').value);
    formData.append('stock', document.getElementById('editStock').value);
    formData.append('sku', document.getElementById('editSku').value);
    formData.append('status', document.getElementById('editStatus').value);
    formData.append('is_featured', document.getElementById('editIsFeatured').checked ? 1 : 0);
    
    const fileInput = document.getElementById('editMainImage');
    if (fileInput.files.length > 0) {
        formData.append('main_image', fileInput.files[0]);
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const saveBtn = document.querySelector('#editCameraModal .btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    fetch(`/api/admin/wifi-cameras/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            const text = await response.text();
            console.error('Got HTML:', text.substring(0, 500));
            throw new Error('Server returned HTML instead of JSON');
        }
    })
    .then(data => {
        if (data.success) {
            alert('✅ Camera berhasil diupdate!');
            bootstrap.Modal.getInstance(document.getElementById('editCameraModal')).hide();
            loadCameras();
        } else {
            alert('❌ Error: ' + (data.message || 'Gagal update camera'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error: ' + error.message);
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function deleteCamera(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus camera ini?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    fetch(`/api/admin/wifi-cameras/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Camera berhasil dihapus!');
            loadCameras();
        } else {
            alert('❌ Error: ' + (data.message || 'Gagal hapus camera'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error: ' + error.message);
    });
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endsection