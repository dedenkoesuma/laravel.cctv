@extends('layouts.simple')

@section('title', 'Manajemen Gudang - TechStore')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* ===== UI SINKRONISASI DASHBOARD & RUIJIE STYLE ===== */
.gudang-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 30px 80px;
    border-radius: 20px;
    margin-bottom: 0;
    text-align: left;
}

.summary-section {
    position: relative;
    margin-top: -50px;
    z-index: 10;
    padding: 0 20px;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto 30px;
}

.summary-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    text-align: center;
    border-bottom: 4px solid #667eea;
    transition: transform 0.3s ease;
}

.summary-card:hover { transform: translateY(-5px); }
.summary-card.danger  { border-bottom-color: #ef4444; }
.summary-card.warning { border-bottom-color: #f59e0b; }
.summary-card.success { border-bottom-color: #10b981; }

.summary-value { font-size: 2.2rem; font-weight: 800; color: #1f2937; line-height: 1.2; }
.summary-label { font-size: 0.85rem; color: #6b7280; margin-top: 4px; font-weight: 600; text-transform: uppercase; }

/* ===== TOOLBAR / FILTER ===== */
.toolbar-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
}

.search-input-group {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 4px 16px;
    transition: all 0.3s ease;
}

.search-input-group:focus-within {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.toolbar-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 15px;
    outline: none;
    font-weight: 600;
    color: #475569;
}

/* ===== TABLE STYLE ===== */
.table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #f1f5f9;
}

.table thead th {
    background: #f8fafc;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    padding: 15px 20px;
    border: none;
}

.table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.stok-badge {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 700;
}
.stok-badge.habis   { background: #fee2e2; color: #dc2626; }
.stok-badge.menipis { background: #fef3c7; color: #d97706; }
.stok-badge.aman    { background: #d1fae5; color: #059669; }

/* MODAL & OTHERS */
.modal-content { border-radius: 20px; border: none; }
.form-control, .form-select { border-radius: 10px; padding: 10px 12px; border: 2px solid #e2e8f0; }
.form-control:focus { border-color: #667eea; box-shadow: none; }

@media (max-width: 768px) {
    .summary-cards { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="gudang-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-1"><i class="bi bi-box-seam me-2"></i>Manajemen Gudang</h1>
                <p class="mb-0 opacity-75">Kontrol inventaris, stok masuk, dan integrasi Sales Order PT Trac.</p>
            </div>
            <div class="d-flex gap-2">
                @can('view_sales_orders')
                <a href="{{ route('admin.sales-orders.index') }}" class="btn btn-light text-primary fw-bold px-4 shadow-sm" style="border-radius:12px;">
                    <i class="bi bi-file-earmark-check me-2"></i>Sales Order
                </a>
                @endcan

                @can('create_inventory')
                <button class="btn btn-warning fw-bold px-4 shadow-sm" style="border-radius:12px;" data-bs-toggle="modal" data-bs-target="#modalBarangMasuk">
                    <i class="bi bi-plus-circle me-2"></i>Barang Masuk
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="summary-section">
    <div class="summary-cards" id="summaryCards">
        <div class="summary-card">
            <div class="summary-value" id="sumTotalProduk">-</div>
            <div class="summary-label">Jenis Produk</div>
        </div>
        <div class="summary-card success">
            <div class="summary-value" id="sumTotalStok">-</div>
            <div class="summary-label">Total Stok</div>
        </div>
        <div class="summary-card warning">
            <div class="summary-value" id="sumMenipis">-</div>
            <div class="summary-label">Stok Menipis</div>
        </div>
        <div class="summary-card danger">
            <div class="summary-value" id="sumHabis">-</div>
            <div class="summary-label">Stok Habis</div>
        </div>
    </div>

    {{-- TOOLBAR / FILTER --}}
    <div class="toolbar-card">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="search-input-group">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" id="searchInput" class="form-control border-0 bg-transparent" 
                           placeholder="Cari nama produk, brand, atau SKU..." oninput="debounceLoad()">
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterCategory" class="form-select toolbar-select" onchange="loadProducts()">
                    <option value="">Semua Kategori</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="filterStok" class="form-select toolbar-select" onchange="loadProducts()">
                    <option value="">Semua Stok</option>
                    <option value="tersedia">Aman</option>
                    <option value="menipis">Menipis</option>
                    <option value="habis">Habis</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100 fw-bold" style="border-radius:12px; padding:10px;" onclick="loadProducts()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <div id="loadingProducts" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Sinkronisasi data...</p>
        </div>
        <div id="tableContainer" style="display:none;">
            <!-- DITAMBAHKAN DIV TABLE-RESPONSIVE DI SINI AGAR BISA SCROLL HP -->
            <div class="table-responsive">
                <!-- DITAMBAHKAN CLASS TEXT-NOWRAP AGAR TEKS TIDAK BERANTAKAN -->
                <table class="table mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th>Informasi Produk</th>
                            <th>Brand / SKU</th>
                            <th class="text-center">Masuk</th>
                            <th class="text-center">Keluar</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL BARANG MASUK --}}
@can('create_inventory')
<div class="modal fade" id="modalBarangMasuk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Input Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formBarangMasuk">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Produk *</label>
                            <input type="text" class="form-control" name="nama_produk" id="namaProdukInput" list="produkSuggestions" required>
                            <datalist id="produkSuggestions"></datalist>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Brand</label>
                            <input type="text" class="form-control" name="brand">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" class="form-control" name="category">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tanggal Masuk *</label>
                            <input type="date" class="form-control" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Jumlah *</label>
                            <input type="number" class="form-control" name="jumlah" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Supplier</label>
                            <input type="text" class="form-control" name="supplier">
                        </div>
                        <div class="col-12">
                             <div class="form-check form-switch p-3 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" id="toggleUseSn">
                                <label class="form-check-label fw-bold" for="toggleUseSn">Gunakan Serial Number (SN)</label>
                                <input type="hidden" id="productUseSn" value="0">
                            </div>
                        </div>
                        <div class="col-12" id="snSection" style="display:none;">
                            <textarea id="snBulkTextarea" class="form-control font-monospace" rows="4" placeholder="Input SN di sini (pisahkan Enter)"></textarea>
                            <div id="snBulkHiddenInputs"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="simpanBarangMasuk()">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
@endcan

{{-- MODAL HISTORY --}}
<div class="modal fade" id="modalHistory" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="margin-top: 100px;">
        <div class="modal-content" style="max-height: 75vh;">
            <div class="modal-header border-bottom shadow-sm">
                <h5 class="modal-title fw-bold" id="historyTitle">Riwayat Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light" id="historyBody">
                <!-- Isi history akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<script>
let debounceTimer;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// VARIABEL PROTEKSI JAVASCRIPT
const canCreateSales = @json(auth()->check() && auth()->user()->can('create_sales_orders'));
const canDeleteInventory = @json(auth()->check() && auth()->user()->can('delete_inventory'));

document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCategories();
    loadProdukSuggestions();
});

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadProducts, 400);
}

function loadCategories() {
    fetch('/api/admin/gudang/categories')
    .then(r => r.json())
    .then(data => {
        const sel = document.getElementById('filterCategory');
        data.categories.forEach(cat => {
            sel.innerHTML += `<option value="${cat}">${cat}</option>`;
        });
    });
}

function loadProdukSuggestions() {
    fetch('/api/admin/gudang/products')
    .then(r => r.json())
    .then(data => {
        const dl = document.getElementById('produkSuggestions');
        dl.innerHTML = data.products.map(p => `<option value="${p.nama_produk}">`).join('');
    });
}

function loadProducts() {
    document.getElementById('loadingProducts').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    const search = document.getElementById('searchInput').value;
    const category = document.getElementById('filterCategory').value;
    const stok = document.getElementById('filterStok').value;

    fetch(`/api/admin/gudang/products?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&stok_filter=${stok}`)
    .then(r => r.json())
    .then(data => {
        document.getElementById('sumTotalProduk').textContent = data.summary.total_produk;
        document.getElementById('sumTotalStok').textContent = data.summary.total_stok;
        document.getElementById('sumMenipis').textContent = data.summary.produk_menipis;
        document.getElementById('sumHabis').textContent = data.summary.produk_habis;

        renderProducts(data.products);
        document.getElementById('loadingProducts').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
    });
}

function renderProducts(products) {
    const tbody = document.getElementById('productTableBody');
    if (products.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>`;
        return;
    }

    tbody.innerHTML = products.map(p => {
        let stokClass = 'aman', stokLabel = 'Tersedia';
        if (p.sisa_stok <= 0) { stokClass = 'habis'; stokLabel = 'Habis'; }
        else if (p.sisa_stok <= 5) { stokClass = 'menipis'; stokLabel = 'Menipis'; }

        // RENDER TOMBOL AKSI BERDASARKAN ROLE
        let actionButtons = '';
        
        // Tombol Buat SO
        if (canCreateSales) {
            actionButtons += `
            <a href="/admin/gudang/sales-orders/create?product_id=${p.id}" class="btn btn-sm btn-outline-danger shadow-sm" style="border-radius:8px" title="Buat Sales Order">
                <i class="bi bi-file-earmark-plus"></i> SO
            </a>`;
        }

        // Tombol Riwayat (Bisa diakses siapa saja yang punya hak view_inventory)
        actionButtons += `
        <button class="btn btn-sm btn-outline-primary shadow-sm" style="border-radius:8px" onclick="showHistory(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')" title="Lihat Riwayat">
            <i class="bi bi-clock-history"></i>
        </button>`;

        // Tombol Hapus Produk
        if (canDeleteInventory) {
            actionButtons += `
            <button class="btn btn-sm btn-light text-danger border-0" onclick="hapusProduk(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')" title="Hapus Produk">
                <i class="bi bi-trash"></i>
            </button>`;
        }

        return `
        <tr>
            <td>
                <div class="fw-bold text-dark">${p.nama_produk}</div>
                <small class="text-primary fw-bold text-uppercase" style="font-size:10px">${p.category || 'No Category'}</small>
            </td>
            <td>
                <div class="fw-bold">${p.brand || '-'}</div>
                <small class="text-muted">${p.sku || '-'}</small>
            </td>
            <td class="text-center fw-bold text-success">${p.total_masuk}</td>
            <td class="text-center fw-bold text-danger">${p.total_keluar}</td>
            <td class="text-center">
                <span class="stok-badge ${stokClass}">${p.sisa_stok} ${stokLabel}</span>
            </td>
            <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                    ${actionButtons}
                </div>
            </td>
        </tr>`;
    }).join('');
}

function hapusProduk(id, name) {
    if (!confirm(`Hapus produk "${name}"? Semua history juga akan hilang.`)) return;
    fetch(`/api/admin/gudang/products/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    }).then(() => loadProducts());
}

function showHistory(id, name) {
    document.getElementById('historyTitle').textContent = `Riwayat: ${name}`;
    new bootstrap.Modal(document.getElementById('modalHistory')).show();
    fetch(`/api/admin/gudang/history/${id}`)
    .then(r => r.json())
    .then(data => {
        let html = '<div class="list-group list-group-flush">';
        data.history.forEach(day => {
            day.items.forEach(item => {
                html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-${item.tipe === 'masuk' ? 'success' : 'danger'} me-2">${item.tipe.toUpperCase()}</span>
                        <strong>${item.jumlah} Unit</strong> - ${item.penerima || item.supplier || '-'}
                        <div class="small text-muted">${item.tanggal}</div>
                    </div>
                </div>`;
            });
        });
        html += '</div>';
        document.getElementById('historyBody').innerHTML = html;
    });
}

// SN Logic Simplified for brevity
document.getElementById('toggleUseSn')?.addEventListener('change', function() {
    document.getElementById('snSection').style.display = this.checked ? 'block' : 'none';
    document.getElementById('productUseSn').value = this.checked ? '1' : '0';
});

function simpanBarangMasuk() {
    const form = document.getElementById('formBarangMasuk');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Add SN if active
    if(document.getElementById('productUseSn').value === '1') {
        const snText = document.getElementById('snBulkTextarea').value;
        data.serial_numbers = snText.split('\n').filter(s => s.trim() !== '');
    }

    fetch('/api/admin/gudang/barang-masuk', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalBarangMasuk')).hide();
            loadProducts();
            form.reset();
        } else {
            alert(res.message);
        }
    });
}
</script>
@endsection