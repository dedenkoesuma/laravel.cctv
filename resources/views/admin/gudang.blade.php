@extends('layouts.simple')

@section('title', 'Gudang Mini - Admin')

@section('content')
<style>
.gudang-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%);
    color: white;
    padding: 32px 24px;
    border-radius: 14px;
    margin-bottom: 28px;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    text-align: center;
    border-left: 4px solid #667eea;
}

.summary-card.danger  { border-left-color: #ef4444; }
.summary-card.warning { border-left-color: #f59e0b; }
.summary-card.success { border-left-color: #10b981; }

.summary-value {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
}

.summary-label {
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 4px;
    font-weight: 500;
}

/* ===== TOOLBAR ===== */
.toolbar {
    background: white;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.toolbar input, .toolbar select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s;
}

.toolbar input:focus, .toolbar select:focus {
    border-color: #667eea;
}

/* ===== PRODUCT TABLE ===== */
.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    overflow: hidden;
}

.table-card table thead th {
    background: #f8fafc;
    font-size: 0.8rem;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 16px;
    border-bottom: 2px solid #e5e7eb;
}

.table-card table tbody td {
    padding: 14px 16px;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}

.table-card table tbody tr:hover {
    background: #f9fafb;
}

.stok-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}

.stok-badge.habis   { background: #fee2e2; color: #dc2626; }
.stok-badge.menipis { background: #fef3c7; color: #d97706; }
.stok-badge.aman    { background: #d1fae5; color: #059669; }

/* ===== MODAL ===== */
.modal-content {
    border-radius: 14px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 18px 24px;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    padding: 16px 24px;
    background: #f9fafb;
    border-radius: 0 0 14px 14px;
}

.form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 0.875rem;
    padding: 8px 12px;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

/* ===== HISTORY PANEL ===== */
.history-group {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    margin-bottom: 12px;
    overflow: hidden;
}

.history-group-header {
    background: #f8fafc;
    padding: 12px 16px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 0.875rem;
    border-bottom: 1px solid #e5e7eb;
}

.history-group-header:hover {
    background: #f1f5f9;
}

.history-item {
    padding: 10px 16px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
}

.history-item:last-child {
    border-bottom: none;
}

/* ===== TIPE ITEM: masuk vs keluar ===== */
.history-item.type-masuk {
    border-left: 3px solid #10b981;
    background: #f0fdf4;
}
.history-item.type-keluar {
    border-left: 3px solid #ef4444;
    background: #fff5f5;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.type-badge.masuk  { background: #d1fae5; color: #065f46; }
.type-badge.keluar { background: #fee2e2; color: #991b1b; }

.status-pill {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-pill.tersedia { background: #d1fae5; color: #059669; }
.status-pill.terjual  { background: #dbeafe; color: #2563eb; }
.status-pill.rusak    { background: #fee2e2; color: #dc2626; }
.status-pill.retur    { background: #f3e8ff; color: #7c3aed; }

/* ===== KELUAR KETERANGAN BADGE ===== */
.ket-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
}
.ket-badge.terjual { background: #dbeafe; color: #1d4ed8; }
.ket-badge.rusak   { background: #fee2e2; color: #b91c1c; }
.ket-badge.retur   { background: #f3e8ff; color: #6d28d9; }

.loading-overlay {
    text-align: center;
    padding: 40px;
    color: #9ca3af;
}

/* ===== KELUAR FORM STYLE ===== */
.keluar-section {
    background: #fff5f5;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 16px;
}

@media (max-width: 768px) {
    .summary-cards { grid-template-columns: repeat(2, 1fr); }
    .toolbar { flex-direction: column; }
    .toolbar input, .toolbar select { width: 100%; }
}
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="gudang-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📦 Gudang Mini</h1>
                <p class="mb-0 opacity-75">Kelola stok barang masuk dan kontrol inventaris</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalBarangKeluar">
                    <i class="bi bi-box-arrow-up me-2"></i>Barang Keluar
                </button>
                <button class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#modalBarangMasuk">
                    <i class="bi bi-plus-circle me-2"></i>Barang Masuk
                </button>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-cards" id="summaryCards">
        <div class="summary-card">
            <div class="summary-value" id="sumTotalProduk">-</div>
            <div class="summary-label">Total Jenis Produk</div>
        </div>
        <div class="summary-card success">
            <div class="summary-value" id="sumTotalStok">-</div>
            <div class="summary-label">Total Stok Tersedia</div>
        </div>
        <div class="summary-card warning">
            <div class="summary-value" id="sumMenipis">-</div>
            <div class="summary-label">Stok Menipis (≤5)</div>
        </div>
        <div class="summary-card danger">
            <div class="summary-value" id="sumHabis">-</div>
            <div class="summary-label">Stok Habis</div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="🔍 Cari nama produk / brand / SKU..."
               style="flex:1; min-width:200px;" oninput="debounceLoad()">
        <select id="filterCategory" onchange="loadProducts()" style="min-width:150px;">
            <option value="">Semua Kategori</option>
        </select>
        <select id="filterStok" onchange="loadProducts()" style="min-width:150px;">
            <option value="">Semua Stok</option>
            <option value="tersedia">Stok Aman</option>
            <option value="menipis">Menipis (≤5)</option>
            <option value="habis">Habis</option>
        </select>
        <button class="btn btn-outline-secondary btn-sm" onclick="loadProducts()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
    </div>

    {{-- PRODUCT TABLE --}}
    <div class="table-card">
        <div id="loadingProducts" class="loading-overlay">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Memuat data...</p>
        </div>
        <div id="tableContainer" style="display:none;">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Brand / SKU</th>
                        <th>Kategori</th>
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

{{-- ===== MODAL BARANG MASUK ===== --}}
<div class="modal fade" id="modalBarangMasuk" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Input Barang Masuk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formBarangMasuk">

                    <div class="row g-3 mb-3 p-3 rounded" style="background:#f0f4ff;">
                        <p class="fw-bold mb-1 text-primary"><i class="bi bi-info-circle me-1"></i>Info Produk</p>
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_produk"
                                   placeholder="Contoh: IP Camera 4MP" list="produkSuggestions" required>
                            <datalist id="produkSuggestions"></datalist>
                            <div class="form-text">Nama sama = stok digabung otomatis</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Hikvision">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="category" placeholder="IP Camera">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">SKU / Kode</label>
                            <input type="text" class="form-control" name="sku" placeholder="HIK-4MP-001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Beli (Rp)</label>
                            <input type="number" class="form-control" name="harga_beli" placeholder="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Jual (Rp)</label>
                            <input type="number" class="form-control" name="harga_jual" placeholder="0" min="0">
                        </div>
                    </div>

                    <div class="row g-3 mb-3 p-3 rounded" style="background:#f0fff4;">
                        <p class="fw-bold mb-1 text-success"><i class="bi bi-truck me-1"></i>Info Kedatangan</p>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_masuk"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jumlah"
                                   value="1" min="1" required>
                            <div class="form-text">Diabaikan jika pakai Serial Number</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Supplier</label>
                            <input type="text" class="form-control" name="supplier" placeholder="Nama supplier">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Serial Numbers <span class="text-muted">(Opsional, satu per baris)</span></label>
                            <textarea class="form-control" name="serial_numbers" rows="4"
                                      placeholder="SN001&#10;SN002&#10;SN003&#10;(Biarkan kosong jika tidak pakai SN)"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-control" name="catatan" placeholder="Catatan tambahan...">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold" onclick="simpanBarangMasuk()">
                    <i class="bi bi-save me-2"></i>Simpan Barang Masuk
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL BARANG KELUAR ===== --}}
<div class="modal fade" id="modalBarangKeluar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #7f1d1d, #dc2626); color:white;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-box-arrow-up me-2"></i>Input Barang Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formBarangKeluar">

                    <div class="row g-3 mb-3 p-3 rounded" style="background:#fff5f5; border:1px solid #fecaca;">
                        <p class="fw-bold mb-1 text-danger"><i class="bi bi-search me-1"></i>Pilih Produk</p>
                        <div class="col-12">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <select class="form-select" id="keluarProdukSelect" name="product_id" required onchange="onProdukKeluarChange()">
                                <option value="">-- Pilih Produk --</option>
                            </select>
                        </div>
                        <div class="col-12" id="stokInfoBox" style="display:none;">
                            <div class="d-flex gap-3 p-2 rounded" style="background:#fff;">
                                <div class="text-center px-3 py-1 rounded" style="background:#f0fdf4;">
                                    <div class="fw-bold text-success fs-5" id="infoProdukMasuk">-</div>
                                    <div class="small text-muted">Total Masuk</div>
                                </div>
                                <div class="text-center px-3 py-1 rounded" style="background:#fff5f5;">
                                    <div class="fw-bold text-danger fs-5" id="infoProdukKeluar">-</div>
                                    <div class="small text-muted">Total Keluar</div>
                                </div>
                                <div class="text-center px-3 py-1 rounded" style="background:#eff6ff;">
                                    <div class="fw-bold text-primary fs-5" id="infoProdukSisa">-</div>
                                    <div class="small text-muted">Sisa Stok</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 p-3 rounded" style="background:#fff5f5; border:1px solid #fecaca;">
                        <p class="fw-bold mb-1 text-danger"><i class="bi bi-arrow-up-circle me-1"></i>Detail Keluar</p>

                        <div class="col-md-6">
                            <label class="form-label">Keterangan / Alasan <span class="text-danger">*</span></label>
                            <select class="form-select" name="keterangan" required>
                                <option value="">-- Pilih Keterangan --</option>
                                <option value="terjual">🛒 Terjual</option>
                                <option value="rusak">💔 Rusak / Defect</option>
                                <option value="retur">↩️ Retur ke Supplier</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_keluar"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jumlah" id="keluarJumlah"
                                   value="1" min="1" required>
                            <div class="form-text text-danger" id="jumlahWarning" style="display:none;">
                                ⚠️ Jumlah melebihi sisa stok!
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga Jual (Rp) <span class="text-muted">(Opsional)</span></label>
                            <input type="number" class="form-control" name="harga_jual" placeholder="0" min="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nama Pembeli / Penerima <span class="text-muted">(Opsional)</span></label>
                            <input type="text" class="form-control" name="penerima" placeholder="Contoh: PT. Maju Jaya">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-control" name="catatan" placeholder="Catatan tambahan...">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-bold" onclick="simpanBarangKeluar()">
                    <i class="bi bi-box-arrow-up me-2"></i>Simpan Barang Keluar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL HISTORY ===== --}}
<div class="modal fade" id="modalHistory" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="historyTitle">History Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="historyBody">
                <div class="loading-overlay">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Memuat history...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let debounceTimer;
let produkList = []; // cache produk list
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCategories();
    loadProdukSuggestions();

    // Validasi jumlah keluar real-time
    document.getElementById('keluarJumlah').addEventListener('input', validateJumlahKeluar);
});

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadProducts, 400);
}

// ===== LOAD CATEGORIES =====
function loadCategories() {
    fetch('/api/admin/gudang/categories', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        const sel = document.getElementById('filterCategory');
        data.categories.forEach(cat => {
            sel.innerHTML += `<option value="${cat}">${cat}</option>`;
        });
    });
}

// ===== LOAD PRODUK SUGGESTIONS & KELUAR DROPDOWN =====
function loadProdukSuggestions() {
    fetch('/api/admin/gudang/products', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        produkList = data.products;

        // Datalist untuk form masuk
        const dl = document.getElementById('produkSuggestions');
        dl.innerHTML = data.products.map(p => `<option value="${p.nama_produk}">`).join('');

        // Dropdown untuk form keluar
        const sel = document.getElementById('keluarProdukSelect');
        sel.innerHTML = '<option value="">-- Pilih Produk --</option>';
        data.products.forEach(p => {
            sel.innerHTML += `<option value="${p.id}" 
                data-masuk="${p.total_masuk}" 
                data-keluar="${p.total_keluar}" 
                data-sisa="${p.sisa_stok}">
                ${p.nama_produk} ${p.brand ? '- ' + p.brand : ''} (Stok: ${p.sisa_stok})
            </option>`;
        });
    });
}

// ===== ON PRODUK KELUAR CHANGE =====
function onProdukKeluarChange() {
    const sel = document.getElementById('keluarProdukSelect');
    const opt = sel.options[sel.selectedIndex];

    if (!sel.value) {
        document.getElementById('stokInfoBox').style.display = 'none';
        return;
    }

    document.getElementById('stokInfoBox').style.display = 'block';
    document.getElementById('infoProdukMasuk').textContent  = opt.dataset.masuk;
    document.getElementById('infoProdukKeluar').textContent = opt.dataset.keluar;
    document.getElementById('infoProdukSisa').textContent   = opt.dataset.sisa;

    validateJumlahKeluar();
}

function validateJumlahKeluar() {
    const sel   = document.getElementById('keluarProdukSelect');
    const opt   = sel.options[sel.selectedIndex];
    const sisa  = parseInt(opt?.dataset?.sisa || 0);
    const jml   = parseInt(document.getElementById('keluarJumlah').value || 0);
    const warn  = document.getElementById('jumlahWarning');

    warn.style.display = (jml > sisa && sisa > 0) ? 'block' : 'none';
}

// ===== LOAD PRODUCTS =====
function loadProducts() {
    document.getElementById('loadingProducts').style.display = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    const search   = document.getElementById('searchInput').value;
    const category = document.getElementById('filterCategory').value;
    const stok     = document.getElementById('filterStok').value;

    let url = `/api/admin/gudang/products?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}&stok_filter=${stok}`;

    fetch(url, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        document.getElementById('sumTotalProduk').textContent = data.summary.total_produk;
        document.getElementById('sumTotalStok').textContent   = data.summary.total_stok;
        document.getElementById('sumMenipis').textContent     = data.summary.produk_menipis;
        document.getElementById('sumHabis').textContent       = data.summary.produk_habis;

        renderProducts(data.products);

        document.getElementById('loadingProducts').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
    })
    .catch(err => {
        document.getElementById('loadingProducts').innerHTML =
            `<p class="text-danger">Error: ${err.message}</p>`;
    });
}

// ===== RENDER TABLE =====
function renderProducts(products) {
    const tbody = document.getElementById('productTableBody');

    if (products.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="7" class="text-center py-5 text-muted">
                <i class="bi bi-box fs-1 d-block mb-2"></i>
                Belum ada produk. Klik "Barang Masuk" untuk menambah.
            </td></tr>`;
        return;
    }

    tbody.innerHTML = products.map(p => {
        let stokClass = 'aman', stokLabel = 'Aman';
        if (p.sisa_stok <= 0)      { stokClass = 'habis';   stokLabel = 'Habis'; }
        else if (p.sisa_stok <= 5) { stokClass = 'menipis'; stokLabel = 'Menipis'; }

        return `
        <tr>
            <td>
                <div class="fw-semibold">${p.nama_produk}</div>
                ${p.keterangan ? `<small class="text-muted">${p.keterangan}</small>` : ''}
            </td>
            <td>
                <div>${p.brand || '<span class="text-muted">-</span>'}</div>
                <small class="text-muted">${p.sku || ''}</small>
            </td>
            <td>${p.category || '<span class="text-muted">-</span>'}</td>
            <td class="text-center fw-bold text-success">${p.total_masuk}</td>
            <td class="text-center fw-bold text-danger">${p.total_keluar}</td>
            <td class="text-center">
                <span class="stok-badge ${stokClass}">${p.sisa_stok} ${stokLabel}</span>
            </td>
            <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-outline-danger" 
                        onclick="bukaKeluarDariTable(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')">
                        <i class="bi bi-box-arrow-up me-1"></i>Keluar
                    </button>
                    <button class="btn btn-sm btn-outline-primary" 
                        onclick="showHistory(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')">
                        <i class="bi bi-clock-history me-1"></i>History
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ===== BUKA MODAL KELUAR DARI TOMBOL DI TABLE =====
function bukaKeluarDariTable(productId, productName) {
    // Refresh produk list dulu supaya stok akurat
    loadProdukSuggestions();

    // Tunggu sebentar lalu set nilai select
    setTimeout(() => {
        const sel = document.getElementById('keluarProdukSelect');
        sel.value = productId;
        onProdukKeluarChange();
    }, 300);

    new bootstrap.Modal(document.getElementById('modalBarangKeluar')).show();
}

// ===== SIMPAN BARANG MASUK =====
function simpanBarangMasuk() {
    const form = document.getElementById('formBarangMasuk');
    if (!form.checkValidity()) { form.reportValidity(); return; }

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    const btn = document.querySelector('#modalBarangMasuk .btn-primary');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    fetch('/api/admin/gudang/barang-masuk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalBarangMasuk')).hide();
            form.reset();
            document.querySelector('#formBarangMasuk input[name="tanggal_masuk"]').value = new Date().toISOString().split('T')[0];
            document.querySelector('#formBarangMasuk input[name="jumlah"]').value = 1;
            loadProducts();
            loadProdukSuggestions();
            showToast('✅ ' + res.message, 'success');
        } else {
            showToast('❌ ' + (res.message || 'Gagal menyimpan'), 'danger');
        }
    })
    .catch(err => showToast('❌ Error: ' + err.message, 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Barang Masuk';
    });
}

// ===== SIMPAN BARANG KELUAR =====
function simpanBarangKeluar() {
    const form = document.getElementById('formBarangKeluar');
    if (!form.checkValidity()) { form.reportValidity(); return; }

    const sel  = document.getElementById('keluarProdukSelect');
    const sisa = parseInt(sel.options[sel.selectedIndex]?.dataset?.sisa || 0);
    const jml  = parseInt(document.getElementById('keluarJumlah').value);

    if (jml > sisa) {
        showToast('❌ Jumlah keluar melebihi sisa stok (' + sisa + ' unit)!', 'danger');
        return;
    }

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    const btn = document.querySelector('#modalBarangKeluar .btn-danger');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    fetch('/api/admin/gudang/barang-keluar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalBarangKeluar')).hide();
            form.reset();
            document.querySelector('#formBarangKeluar input[name="tanggal_keluar"]').value = new Date().toISOString().split('T')[0];
            document.getElementById('stokInfoBox').style.display = 'none';
            loadProducts();
            loadProdukSuggestions();
            showToast('✅ ' + res.message, 'success');
        } else {
            showToast('❌ ' + (res.message || 'Gagal menyimpan'), 'danger');
        }
    })
    .catch(err => showToast('❌ Error: ' + err.message, 'danger'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-box-arrow-up me-2"></i>Simpan Barang Keluar';
    });
}

// ===== SHOW HISTORY (MASUK + KELUAR) =====
function showHistory(productId, productName) {
    document.getElementById('historyTitle').textContent = `History: ${productName}`;
    document.getElementById('historyBody').innerHTML = `
        <div class="loading-overlay">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Memuat history...</p>
        </div>`;

    new bootstrap.Modal(document.getElementById('modalHistory')).show();

    fetch(`/api/admin/gudang/history/${productId}`, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            document.getElementById('historyBody').innerHTML = `<p class="text-danger">Gagal memuat data</p>`;
            return;
        }

        const p = data.product;
        let html = `
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded text-center" style="background:#f0fff4;">
                        <div class="fs-3 fw-bold text-success">${p.sisa_stok}</div>
                        <div class="small text-muted">Sisa Stok</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded text-center" style="background:#eff6ff;">
                        <div class="fs-3 fw-bold text-primary">${p.total_masuk}</div>
                        <div class="small text-muted">Total Masuk</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded text-center" style="background:#fff5f5;">
                        <div class="fs-3 fw-bold text-danger">${p.total_keluar}</div>
                        <div class="small text-muted">Total Keluar</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded text-center" style="background:#f8fafc;">
                        <div class="fs-3 fw-bold text-secondary">${data.total_history}</div>
                        <div class="small text-muted">Total Transaksi</div>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">📅 Riwayat Lengkap (Masuk & Keluar)</h6>`;

        if (data.history.length === 0) {
            html += `<p class="text-muted text-center py-4">Belum ada history</p>`;
        } else {
            data.history.forEach((group, gi) => {
                const totalMasuk  = group.items.filter(i => i.tipe === 'masuk').reduce((s, i) => s + i.jumlah, 0);
                const totalKeluar = group.items.filter(i => i.tipe === 'keluar').reduce((s, i) => s + i.jumlah, 0);

                html += `
                <div class="history-group">
                    <div class="history-group-header" onclick="toggleGroup(${gi})">
                        <div>
                            <i class="bi bi-calendar3 me-2 text-primary"></i>
                            <strong>${formatDate(group.tanggal)}</strong>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            ${totalMasuk  > 0 ? `<span class="badge bg-success">+${totalMasuk} masuk</span>` : ''}
                            ${totalKeluar > 0 ? `<span class="badge bg-danger">-${totalKeluar} keluar</span>` : ''}
                            <i class="bi bi-chevron-down" id="chevron-${gi}"></i>
                        </div>
                    </div>
                    <div id="group-${gi}">`;

                group.items.forEach(item => {
                    const isMasuk = item.tipe === 'masuk';
                    html += `
                        <div class="history-item ${isMasuk ? 'type-masuk' : 'type-keluar'}">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="type-badge ${isMasuk ? 'masuk' : 'keluar'}">
                                    <i class="bi ${isMasuk ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle'}"></i>
                                    ${isMasuk ? 'MASUK' : 'KELUAR'}
                                </span>
                                <strong>${isMasuk ? '+' : '-'}${item.jumlah} unit</strong>
                                ${!isMasuk && item.keterangan ? `<span class="ket-badge ${item.keterangan}">${item.keterangan}</span>` : ''}
                                ${isMasuk  && item.status     ? `<span class="status-pill ${item.status}">${item.status}</span>` : ''}
                                ${item.serial_number ? `<span class="text-muted small">SN: ${item.serial_number}</span>` : ''}
                                ${!isMasuk && item.penerima   ? `<span class="text-muted small">→ ${item.penerima}</span>` : ''}
                                ${isMasuk  && item.supplier   ? `<span class="text-muted small">dari: ${item.supplier}</span>` : ''}
                                ${item.harga_beli > 0 ? `<span class="text-muted small">Beli: Rp ${formatNum(item.harga_beli)}</span>` : ''}
                                ${item.harga_jual > 0 ? `<span class="text-muted small">Jual: Rp ${formatNum(item.harga_jual)}</span>` : ''}
                                ${item.catatan ? `<span class="text-muted small">| ${item.catatan}</span>` : ''}
                            </div>
                            <div class="d-flex gap-1">
                                ${isMasuk && item.status === 'tersedia' ? `
                                    <button class="btn btn-xs btn-outline-primary py-0 px-2" onclick="updateStatus(${item.id}, 'terjual', ${productId})">Terjual</button>
                                    <button class="btn btn-xs btn-outline-warning py-0 px-2" onclick="updateStatus(${item.id}, 'rusak', ${productId})">Rusak</button>
                                ` : ''}
                                <button class="btn btn-xs btn-outline-danger py-0 px-2" 
                                    onclick="${isMasuk ? `hapusItem(${item.id}, ${productId})` : `hapusKeluar(${item.id}, ${productId})`}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>`;
                });

                html += `</div></div>`;
            });
        }

        document.getElementById('historyBody').innerHTML = html;
    });
}

function toggleGroup(gi) {
    const el = document.getElementById(`group-${gi}`);
    const ch = document.getElementById(`chevron-${gi}`);
    if (el.style.display === 'none') {
        el.style.display = 'block';
        ch.className = 'bi bi-chevron-down';
    } else {
        el.style.display = 'none';
        ch.className = 'bi bi-chevron-right';
    }
}

// ===== UPDATE STATUS MASUK =====
function updateStatus(itemId, status, productId) {
    fetch(`/api/admin/gudang/barang-masuk/${itemId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showHistory(productId, document.getElementById('historyTitle').textContent.replace('History: ', ''));
            loadProducts();
            showToast('✅ Status diupdate', 'success');
        }
    });
}

// ===== HAPUS ITEM MASUK =====
function hapusItem(itemId, productId) {
    if (!confirm('Hapus record barang masuk ini?')) return;

    fetch(`/api/admin/gudang/barang-masuk/${itemId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showHistory(productId, document.getElementById('historyTitle').textContent.replace('History: ', ''));
            loadProducts();
            showToast('✅ Data dihapus', 'success');
        }
    });
}

// ===== HAPUS ITEM KELUAR =====
function hapusKeluar(itemId, productId) {
    if (!confirm('Hapus record barang keluar ini? Stok akan dikembalikan.')) return;

    fetch(`/api/admin/gudang/barang-keluar/${itemId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showHistory(productId, document.getElementById('historyTitle').textContent.replace('History: ', ''));
            loadProducts();
            loadProdukSuggestions();
            showToast('✅ Data keluar dihapus, stok dikembalikan', 'success');
        }
    });
}

// ===== HELPERS =====
function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
}

function formatNum(num) {
    return parseInt(num).toLocaleString('id-ID');
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    toast.style.zIndex = 9999;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection