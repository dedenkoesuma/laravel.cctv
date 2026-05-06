@extends('layouts.simple')

@section('title', 'Manage WiFi Camera - Admin Dashboard')

@section('content')
<style>
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* ===== FORM STYLES (screenshot style) ===== */
.form-section {
    margin-bottom: 1.25rem;
}

.form-section label.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
    display: block;
}

.form-section .form-control,
.form-section .form-select {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    color: #111827;
    background-color: #fff;
    transition: border-color 0.15s ease;
}

.form-section .form-control:focus,
.form-section .form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    outline: none;
}

.form-section .form-control::placeholder {
    color: #9ca3af;
    font-size: 0.875rem;
}

.form-section .helper-text {
    font-size: 0.775rem;
    color: #6b7280;
    margin-top: 0.3rem;
}

.form-section textarea.form-control {
    resize: vertical;
    min-height: 90px;
}

.modal-lg-custom {
    max-width: 620px;
}

.modal-content {
    border-radius: 14px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
}

.modal-header.form-modal-header {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 1.25rem 1.5rem 1rem;
    border-radius: 14px 14px 0 0;
}

.modal-header.form-modal-header .modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
}

.modal-body {
    padding: 1.5rem;
    background: #fff;
}

.modal-footer {
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    border-radius: 0 0 14px 14px;
    padding: 1rem 1.5rem;
}

.image-preview-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.image-preview {
    position: relative;
    width: 90px;
    height: 90px;
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
    top: 4px;
    right: 4px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
}

/* Required star */
.req {
    color: #ef4444;
    margin-left: 2px;
}
</style>

@php
    $adminRoleName = session('admin_role');
    $currentRole = \Spatie\Permission\Models\Role::where('name', $adminRoleName)->first();
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
                    <!-- Event diubah ke renderTable() agar tidak loading -->
                    <input type="text" class="form-control" id="searchCamera" placeholder="Cari nama produk..." oninput="renderTable()">
                </div>
                <div class="col-md-3">
                    <!-- Event diubah ke renderTable() agar tidak loading -->
                    <select class="form-select" id="filterBrand" onchange="renderTable()">
                        <option value="">Semua Brand</option>
                        <option value="DAHUA">DAHUA</option>
                        <option value="HIVIEW">HIVIEW</option>
                        <option value="EZVIZ">EZVIZ</option>
                        <option value="IMOU">IMOU</option>
                        <option value="TP-LINK">TP-LINK</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <!-- Event diubah ke renderTable() agar tidak loading -->
                    <select class="form-select" id="filterStatus" onchange="renderTable()">
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
                        <tbody id="cameraTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL TAMBAH CAMERA ===== -->
<div class="modal fade" id="addCameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header form-modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-camera-video me-2 text-primary"></i>Tambah WiFi Camera Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addCameraForm" enctype="multipart/form-data">

                    <div class="form-section">
                        <label class="form-label">Product Name <span class="req">*</span></label>
                        <input type="text" class="form-control" name="name"
                               placeholder="e.g., EZVIZ C6N 2MP WiFi Camera" required>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Model Number</label>
                        <input type="text" class="form-control" name="sku"
                               placeholder="e.g., CS-C6N-A0-1C2WFR">
                    </div>

                    <div class="form-section">
                        <label class="form-label">URL Slug <span class="req">*</span></label>
                        <input type="text" class="form-control" name="slug" id="addSlug"
                               placeholder="e.g., ezviz-c6n-2mp-wifi-camera" required>
                        <div class="helper-text">URL friendly version (lowercase, no spaces, auto generated)</div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Brand <span class="req">*</span></label>
                        <select class="form-select" name="brand" required>
                            <option value="">Select Brand</option>
                            <option value="DAHUA">DAHUA</option>
                            <option value="HIVIEW">HIVIEW</option>
                            <option value="EZVIZ">EZVIZ</option>
                            <option value="IMOU">IMOU</option>
                            <option value="TP-LINK">TP-LINK</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control" name="price"
                                       placeholder="e.g., 350000" min="0" step="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Stok Barang <span class="req">*</span></label>
                                <input type="number" class="form-control" name="stock"
                                       value="10" min="0" required>
                                <div class="helper-text">Jumlah produk yang tersedia saat ini</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mt-3">
                        <label class="form-label">Harga Asli / Coret (Rp)</label>
                        <input type="number" class="form-control" name="original_price"
                               placeholder="Kosongkan jika tidak ada diskon" min="0" step="1">
                    </div>

                    <div class="form-section">
                        <label class="form-label">Subtitle / Tagline</label>
                        <input type="text" class="form-control" name="subtitle"
                               placeholder="e.g., Smart Pan & Tilt Indoor WiFi Camera 1080P">
                    </div>

                    <div class="form-section">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Enter product description..."></textarea>
                    </div>

                    <!-- Features -->
                    <div class="form-section">
                        <label class="form-label">Features <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" name="features_text" rows="4"
                                  placeholder="2MP 1080P Full HD&#10;Pan 340° / Tilt 90°&#10;Two-Way Audio&#10;Night Vision 10m"></textarea>
                        <div class="helper-text">Setiap baris yang dipisah Enter akan otomatis menjadi list (bullet point).</div>
                    </div>

                    <!-- Specifications -->
                    <div class="form-section">
                        <label class="form-label">Specifications <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" name="specifications_text" rows="4"
                                  placeholder="Resolution: 2MP 1080P&#10;Compression: H.265+&#10;Night Vision: 10m&#10;Power: DC 5V/1A"></textarea>
                        <div class="helper-text">Format: Key: Value (Satu per baris dengan Enter)</div>
                    </div>

                    <!-- Paket Termasuk -->
                    <div class="form-section">
                        <label class="form-label">Paket Termasuk <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" name="package_text" rows="3"
                                  placeholder="1x Kamera WiFi&#10;1x Adaptor DC 5V&#10;1x Baut &amp; Fisher&#10;1x Panduan Cepat"></textarea>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Gambar Utama <span class="req">*</span></label>
                        <input type="file" class="form-control" name="main_image" accept="image/*"
                               onchange="previewMainImage(this, 'addMainPreview')" required>
                        <div id="addMainPreview" class="image-preview-container"></div>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Galeri Gambar <span class="helper-text d-inline">(Maks. 5)</span></label>
                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*"
                               multiple onchange="previewGalleryImages(this, 'addGalleryPreview')">
                        <div id="addGalleryPreview" class="image-preview-container"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                                <label class="form-check-label" for="isFeatured">
                                    Produk Unggulan
                                </label>
                            </div>
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

<!-- ===== MODAL EDIT CAMERA ===== -->
<div class="modal fade" id="editCameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header form-modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit WiFi Camera
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCameraForm" enctype="multipart/form-data">
                    <input type="hidden" id="editId">

                    <div class="form-section">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editName" placeholder="e.g., EZVIZ C6N 2MP">
                    </div>

                    <div class="form-section">
                        <label class="form-label">Model Number (SKU)</label>
                        <input type="text" class="form-control" id="editSku" placeholder="e.g., CS-C6N-A0-1C2WFR">
                    </div>

                    <div class="form-section">
                        <label class="form-label">Brand</label>
                        <select class="form-select" id="editBrand">
                            <option value="DAHUA">DAHUA</option>
                            <option value="HIVIEW">HIVIEW</option>
                            <option value="EZVIZ">EZVIZ</option>
                            <option value="IMOU">IMOU</option>
                            <option value="TP-LINK">TP-LINK</option>
                        </select>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="editSubtitle">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control" id="editPrice" min="0" step="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Stok Barang</label>
                                <input type="number" class="form-control" id="editStock" min="0">
                                <div class="helper-text">Jumlah produk yang tersedia saat ini</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section mt-3">
                        <label class="form-label">Harga Asli (Rp)</label>
                        <input type="number" class="form-control" id="editOriginalPrice" min="0" step="1">
                    </div>

                    <!-- ===== TAMBAHAN: Features ===== -->
                    <div class="form-section">
                        <label class="form-label">Features <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" id="editFeaturesText" rows="4"
                                  placeholder="2MP 1080P Full HD&#10;Pan 340° / Tilt 90°&#10;Two-Way Audio&#10;Night Vision 10m"></textarea>
                        <div class="helper-text">Setiap baris akan otomatis menjadi bullet point.</div>
                    </div>

                    <!-- ===== TAMBAHAN: Specifications ===== -->
                    <div class="form-section">
                        <label class="form-label">Specifications <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" id="editSpecificationsText" rows="4"
                                  placeholder="Resolution: 2MP 1080P&#10;Compression: H.265+&#10;Night Vision: 10m&#10;Power: DC 5V/1A"></textarea>
                        <div class="helper-text">Format: Key: Value (Satu per baris)</div>
                    </div>

                    <!-- ===== TAMBAHAN: Paket Termasuk ===== -->
                    <div class="form-section">
                        <label class="form-label">Paket Termasuk <span class="helper-text d-inline">(Satu per baris)</span></label>
                        <textarea class="form-control" id="editPackageText" rows="3"
                                  placeholder="1x Kamera WiFi&#10;1x Adaptor DC 5V&#10;1x Baut &amp; Fisher&#10;1x Panduan Cepat"></textarea>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Upload Gambar Baru</label>
                        <input type="file" class="form-control" id="editMainImage" accept="image/*"
                               onchange="previewMainImage(this, 'editMainPreview')">
                        <div id="editCurrentImage" class="mt-2"></div>
                        <div id="editMainPreview" class="image-preview-container"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-section mb-0">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="editIsFeatured">
                                <label class="form-check-label" for="editIsFeatured">Produk Unggulan</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateCamera()">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cameras = [];

// Variabel pengecekan akses untuk dirender di tabel
const canEdit = @json(auth()->check() && auth()->user()->can('edit_wifi_cameras'));
const canDelete = @json(auth()->check() && auth()->user()->can('delete_wifi_cameras'));

// ===== HELPER: Parse JSON field dari API (bisa string atau array) =====
function parseJsonField(value) {
    if (!value) return [];
    if (Array.isArray(value)) return value;
    if (typeof value === 'string') {
        try {
            const parsed = JSON.parse(value);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }
    return [];
}

// ===== HELPER: Parse specifications (bisa object atau array of strings) =====
function parseSpecsToText(value) {
    const arr = parseJsonField(value);
    if (arr.length === 0) return '';

    // Kalau isinya object {key: value}, convert ke "key: value"
    return arr.map(item => {
        if (typeof item === 'object' && item !== null) {
            return Object.entries(item).map(([k, v]) => `${k}: ${v}`).join('\n');
        }
        return item;
    }).join('\n');
}

document.addEventListener('DOMContentLoaded', function() {
    loadCameras();

    // Auto-generate slug from product name
    const nameInput = document.querySelector('#addCameraForm input[name="name"]');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
            document.getElementById('addSlug').value = slug;
        });
    }
});

// Ambil SEMUA data sekali saja tanpa parameter
function loadCameras() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    fetch('/api/admin/wifi-cameras', { 
        headers: { 'Accept': 'application/json' } 
    })
    .then(async response => {
        const ct = response.headers.get('content-type');
        if (ct && ct.includes('application/json')) return response.json();
        const text = await response.text();
        throw new Error('API returned HTML instead of JSON');
    })
    .then(data => {
        if (data.success) {
            cameras = data.cameras || [];
            renderTable(); // Jalankan fungsi filtering dan render
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
        }
    })
    .catch(error => {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('cameraTableBody').innerHTML = `
            <tr><td colspan="8" class="text-center py-5 text-danger">
                <i class="bi bi-exclamation-triangle fs-1"></i>
                <p class="mt-3">Error: ${error.message}</p>
                <button class="btn btn-primary" onclick="loadCameras()">Try Again</button>
            </td></tr>`;
    });
}

// Render data dengan filter client-side tanpa reload server
function renderTable() {
    const tbody = document.getElementById('cameraTableBody');
    tbody.innerHTML = '';

    // Ambil nilai dari kotak pencarian & dropdown
    const brandVal = document.getElementById('filterBrand').value;
    const statusVal = document.getElementById('filterStatus').value;
    const searchVal = document.getElementById('searchCamera').value.toLowerCase();

    // Saring data dari memori
    const filteredCameras = cameras.filter(camera => {
        const matchBrand = brandVal === "" || camera.brand === brandVal;
        const matchStatus = statusVal === "" || camera.status === statusVal;
        const matchSearch = camera.name.toLowerCase().includes(searchVal) || 
                            (camera.sku && camera.sku.toLowerCase().includes(searchVal));
        
        return matchBrand && matchStatus && matchSearch;
    });

    if (filteredCameras.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="8" class="text-center py-5">
                <i class="bi bi-camera-video" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 mb-2"><strong>Tidak ada produk yang cocok</strong></p>
            </td></tr>`;
        return;
    }

    filteredCameras.forEach(camera => {
        let actionButtons = '';
        
        if(canEdit) {
            actionButtons += `<button class="btn btn-sm btn-primary btn-action" onclick="editCamera(${camera.id})"><i class="bi bi-pencil"></i> Edit</button>`;
        }
        
        if(canDelete) {
            actionButtons += `<button class="btn btn-sm btn-danger btn-action" onclick="deleteCamera(${camera.id})"><i class="bi bi-trash"></i></button>`;
        }

        tbody.innerHTML += `
            <tr>
                <td>${camera.id}</td>
                <td>
                    ${camera.main_image
                        ? `<img src="/storage/${camera.main_image}" class="product-img-thumb" alt="">`
                        : `<div class="bg-light d-flex align-items-center justify-content-center product-img-thumb">
                                <i class="bi bi-image text-muted"></i>
                           </div>`}
                </td>
                <td>
                    <strong>${camera.name}</strong><br>
                    <small class="text-muted">${camera.subtitle || ''}</small>
                </td>
                <td><span class="badge bg-primary">${camera.brand}</span></td>
                <td>Rp ${formatNumber(camera.price)}</td>
                <td><span class="badge ${camera.stock > 0 ? 'bg-success' : 'bg-danger'}">${camera.stock}</span></td>
                <td><span class="badge ${camera.status === 'active' ? 'bg-success' : 'bg-secondary'}">${camera.status}</span></td>
                <td class="action-buttons">${actionButtons}</td>
            </tr>`;
    });
}

function previewMainImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<div class="image-preview"><img src="${e.target.result}" alt="Preview"></div>`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGalleryImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    if (input.files) {
        Array.from(input.files).slice(0, 5).forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'image-preview';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery ${i+1}">
                    <button type="button" class="remove-image" onclick="this.closest('.image-preview').remove()">
                        <i class="bi bi-x"></i>
                    </button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function saveCamera() {
    const form = document.getElementById('addCameraForm');
    if (!form.checkValidity()) { form.reportValidity(); return; }

    const formData = new FormData(form);

    // Convert textarea lines to arrays for backend
    const featuresText = formData.get('features_text') || '';
    const specsText = formData.get('specifications_text') || '';
    const packageText = formData.get('package_text') || '';

    formData.delete('features_text');
    formData.delete('specifications_text');
    formData.delete('package_text');

    featuresText.split('\n').filter(l => l.trim()).forEach(l => formData.append('features[]', l.trim()));
    specsText.split('\n').filter(l => l.trim()).forEach(l => formData.append('specifications[]', l.trim()));
    packageText.split('\n').filter(l => l.trim()).forEach(l => formData.append('package_includes[]', l.trim()));

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
        const ct = response.headers.get('content-type');
        if (ct && ct.includes('application/json')) return response.json();
        throw new Error('Server returned HTML instead of JSON');
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
    .catch(error => { alert('❌ Error: ' + error.message); })
    .finally(() => { saveBtn.disabled = false; saveBtn.innerHTML = originalText; });
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
    document.getElementById('editIsFeatured').checked = camera.is_featured == 1;

    // ===== TAMBAHAN: Parse JSON string dari DB ke textarea =====
    const features = parseJsonField(camera.features);
    const specs    = parseJsonField(camera.specifications);
    const pkg      = parseJsonField(camera.package_includes);

    document.getElementById('editFeaturesText').value      = features.join('\n');
    document.getElementById('editSpecificationsText').value = parseSpecsToText(camera.specifications);
    document.getElementById('editPackageText').value       = pkg.join('\n');
    // ==========================================================

    const currentImageDiv = document.getElementById('editCurrentImage');
    currentImageDiv.innerHTML = camera.main_image
        ? `<div class="alert alert-info py-2"><strong>Current Image:</strong><br>
               <img src="/storage/${camera.main_image}" class="img-thumbnail mt-1" style="max-width: 160px;"></div>`
        : '<div class="alert alert-secondary py-2">No image</div>';

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

    // ===== TAMBAHAN: Kirim features, specs, package =====
    const featuresText = document.getElementById('editFeaturesText').value || '';
    const specsText    = document.getElementById('editSpecificationsText').value || '';
    const packageText  = document.getElementById('editPackageText').value || '';

    featuresText.split('\n').filter(l => l.trim()).forEach(l => formData.append('features[]', l.trim()));
    specsText.split('\n').filter(l => l.trim()).forEach(l => formData.append('specifications[]', l.trim()));
    packageText.split('\n').filter(l => l.trim()).forEach(l => formData.append('package_includes[]', l.trim()));
    // ====================================================

    const fileInput = document.getElementById('editMainImage');
    if (fileInput.files.length > 0) formData.append('main_image', fileInput.files[0]);

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
        const ct = response.headers.get('content-type');
        if (ct && ct.includes('application/json')) return response.json();
        throw new Error('Server returned HTML instead of JSON');
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
    .catch(error => { alert('❌ Error: ' + error.message); })
    .finally(() => { saveBtn.disabled = false; saveBtn.innerHTML = originalText; });
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
        if (data.success) { alert('✅ Camera berhasil dihapus!'); loadCameras(); }
        else alert('❌ Error: ' + (data.message || 'Gagal hapus camera'));
    })
    .catch(error => { alert('❌ Error: ' + error.message); });
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endsection