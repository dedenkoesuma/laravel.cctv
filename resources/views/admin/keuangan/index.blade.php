{{-- resources/views/admin/keuangan/index.blade.php --}}
@extends('layouts.simple')
@section('title', 'Keuangan - Admin')
@section('content')
<style>
/* ===== HEADER ===== */
.keu-header {
    background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
    color: white; padding: 28px 24px;
    border-radius: 14px; margin-bottom: 24px;
}
/* ===== SUMMARY CARDS ===== */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 24px;
}
.sum-card {
    background: white; border-radius: 12px;
    padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    border-left: 4px solid #e5e7eb;
    transition: transform 0.2s;
}
.sum-card:hover { transform: translateY(-2px); }
.sum-card.pemasukan  { border-left-color: #10b981; }
.sum-card.pengeluaran{ border-left-color: #ef4444; }
.sum-card.laba       { border-left-color: #3b82f6; }
.sum-card.saldo      { border-left-color: #f59e0b; }
.sum-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 12px;
}
.sum-value { font-size: 1.5rem; font-weight: 800; color: #111827; }
.sum-label { font-size: 0.78rem; color: #6b7280; margin-top: 2px; }
/* ===== TOOLBAR ===== */
.toolbar {
    background: white; border-radius: 12px;
    padding: 14px 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    margin-bottom: 18px;
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
}
.toolbar select, .toolbar input {
    border: 1px solid #d1d5db; border-radius: 8px;
    padding: 7px 12px; font-size: 0.85rem;
}
.toolbar select:focus, .toolbar input:focus {
    border-color: #10b981; outline: none;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
}
/* ===== TABLE ===== */
.table-card {
    background: white; border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    overflow: hidden; margin-bottom: 24px;
}
.table-card table thead th {
    background: #f8fafc; font-size: 0.78rem;
    font-weight: 700; color: #374151;
    text-transform: uppercase; letter-spacing: 0.05em;
    padding: 12px 16px; border-bottom: 2px solid #e5e7eb;
}
.table-card table tbody td {
    padding: 12px 16px; font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6; vertical-align: middle;
}
.table-card table tbody tr:hover { background: #f9fafb; }
/* ===== BADGES ===== */
.tipe-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 0.75rem; font-weight: 700;
}
.tipe-badge.pemasukan   { background: #d1fae5; color: #065f46; }
.tipe-badge.pengeluaran { background: #fee2e2; color: #991b1b; }
.status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.status-badge.lunas   { background: #d1fae5; color: #065f46; }
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.batal   { background: #f3f4f6; color: #6b7280; }
/* ===== PLATFORM BADGES ===== */
.platform-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 12px;
    font-size: 0.72rem; font-weight: 700;
}
.platform-badge.tokopedia  { background: #fff3e0; color: #e65100; }
.platform-badge.shopee     { background: #fff1ee; color: #d84315; }
.platform-badge.tiktok     { background: #e8f5e9; color: #1b5e20; }
.platform-badge.lazada     { background: #ede7f6; color: #4527a0; }
.platform-badge.bukalapak  { background: #e3f2fd; color: #0d47a1; }
.platform-badge.website    { background: #e8eaf6; color: #283593; }
.platform-badge.instagram  { background: #fce4ec; color: #880e4f; }
.platform-badge.whatsapp   { background: #e8f5e9; color: #1b5e20; }
.platform-badge.lainnya    { background: #f3f4f6; color: #374151; }
/* ===== SECTION TOKO ONLINE DI MODAL ===== */
.online-section {
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1.5px solid #86efac;
    border-radius: 10px;
    padding: 16px;
    margin-top: 4px;
}
.online-section-title {
    font-size: 0.8rem; font-weight: 700;
    color: #065f46; margin-bottom: 12px;
    display: flex; align-items: center; gap: 6px;
}
/* ===== CHART ===== */
.chart-grid {
    display: grid; grid-template-columns: 2fr 1fr;
    gap: 16px; margin-bottom: 24px;
}
.chart-card {
    background: white; border-radius: 12px;
    padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.07);
}
.chart-title {
    font-size: 0.85rem; font-weight: 700;
    color: #374151; margin-bottom: 16px;
    text-transform: uppercase; letter-spacing: 0.05em;
}
/* ===== MODAL ===== */
.modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
.modal-header  { border-bottom: 1px solid #e5e7eb; padding: 18px 24px; }
.modal-body    { padding: 24px; }
.modal-footer  { border-top: 1px solid #e5e7eb; padding: 14px 24px; background: #f9fafb; border-radius: 0 0 14px 14px; }
.form-label   { font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 5px; }
.form-control, .form-select {
    border-radius: 8px; border: 1px solid #d1d5db;
    font-size: 0.875rem; padding: 8px 12px;
}
.form-control:focus, .form-select:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
}
/* ===== LOADING ===== */
.loading-overlay {
    text-align: center; padding: 40px; color: #9ca3af;
}
/* ===== LINK CARD ===== */
.link-card {
    border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 12px 14px; margin-bottom: 8px;
    background: white; transition: box-shadow 0.2s;
}
.link-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.link-card.nonaktif { background: #f9fafb; opacity: 0.75; }
.link-url {
    font-size: 0.78rem; color: #6b7280;
    font-family: monospace; word-break: break-all;
}
@media (max-width: 768px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .chart-grid   { grid-template-columns: 1fr; }
    .toolbar      { flex-direction: column; }
}
</style>
<div class="container py-4">
    {{-- HEADER --}}
    <div class="keu-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">💰 Sistem Keuangan</h1>
                <p class="mb-0 opacity-75">Kelola pemasukan, pengeluaran, dan laporan keuangan</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-light fw-bold" onclick="bukaModal('pemasukan')">
                    <i class="bi bi-plus-circle me-1"></i>Pemasukan
                </button>

                {{-- ✅ TOMBOL PENJUALAN ONLINE - Buka tab baru ke /penjualan-online/staff --}}
                <button class="btn fw-bold" style="background:#f97316;color:white;"
                        onclick="bukaModalOnline()">
                    🛒 Penjualan Online
                </button>

                <button class="btn btn-danger fw-bold" onclick="bukaModal('pengeluaran')">
                    <i class="bi bi-dash-circle me-1"></i>Pengeluaran
                </button>
                <button class="btn btn-outline-light fw-bold" onclick="exportLaporan()">
                    <i class="bi bi-download me-1"></i>Export
                </button>
            </div>
        </div>
    </div>

    {{-- FILTER BULAN --}}
    <div class="d-flex gap-2 mb-3 align-items-center flex-wrap">
        <select id="filterBulan" class="form-select" style="width:auto;" onchange="loadAll()">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                </option>
            @endfor
        </select>
        <select id="filterTahun" class="form-select" style="width:auto;" onchange="loadAll()">
            @for($y = date('Y'); $y >= date('Y')-3; $y--)
                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <span class="text-muted small" id="periodLabel"></span>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid">
        <div class="sum-card pemasukan">
            <div class="sum-icon" style="background:#d1fae5;">💚</div>
            <div class="sum-value text-success" id="sumPemasukan">-</div>
            <div class="sum-label">Pemasukan Bulan Ini</div>
        </div>
        <div class="sum-card pengeluaran">
            <div class="sum-icon" style="background:#fee2e2;">❤️</div>
            <div class="sum-value text-danger" id="sumPengeluaran">-</div>
            <div class="sum-label">Pengeluaran Bulan Ini</div>
        </div>
        <div class="sum-card laba">
            <div class="sum-icon" style="background:#dbeafe;">💙</div>
            <div class="sum-value text-primary" id="sumLaba">-</div>
            <div class="sum-label">Laba / Rugi Bulan Ini</div>
        </div>
        <div class="sum-card saldo">
            <div class="sum-icon" style="background:#fef3c7;">💛</div>
            <div class="sum-value text-warning" id="sumSaldo">-</div>
            <div class="sum-label">Total Saldo Keseluruhan</div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-bar-chart me-2"></i>Grafik Pemasukan vs Pengeluaran</div>
            <canvas id="chartBulanan" height="100"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="bi bi-pie-chart me-2"></i>Breakdown Pengeluaran</div>
            <canvas id="chartKategori" height="200"></canvas>
            <div id="kategoriList" class="mt-3"></div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="🔍 Cari transaksi / kode / nama / no. order..."
               style="flex:1; min-width:200px;" oninput="debounceLoad()">
        <select id="filterTipe" onchange="loadTransaksi()">
            <option value="">Semua Tipe</option>
            <option value="pemasukan">💚 Pemasukan</option>
            <option value="pengeluaran">❤️ Pengeluaran</option>
        </select>
        <select id="filterPlatform" onchange="loadTransaksi()">
            <option value="">Semua Platform</option>
            <option value="Tokopedia">🟠 Tokopedia</option>
            <option value="Shopee">🔴 Shopee</option>
            <option value="TikTok Shop">🟢 TikTok Shop</option>
            <option value="Lazada">🟣 Lazada</option>
            <option value="Bukalapak">🔵 Bukalapak</option>
            <option value="Website">🌐 Website</option>
            <option value="Instagram">📸 Instagram</option>
            <option value="WhatsApp">📱 WhatsApp</option>
            <option value="Lainnya">📦 Lainnya</option>
        </select>
        <select id="filterStatus" onchange="loadTransaksi()">
            <option value="">Semua Status</option>
            <option value="lunas">Lunas</option>
            <option value="pending">Pending</option>
            <option value="batal">Batal</option>
        </select>
        <button class="btn btn-outline-secondary btn-sm" onclick="loadTransaksi()">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>

    {{-- TABEL TRANSAKSI --}}
    <div class="table-card">
        <div id="loadingTrx" class="loading-overlay">
            <div class="spinner-border text-success" role="status"></div>
            <p class="mt-2">Memuat transaksi...</p>
        </div>
        <div id="tableContainer" style="display:none;">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Pihak Terkait</th>
                        <th>Metode</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="trxTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== MODAL TRANSAKSI ===== --}}
<div class="modal fade" id="modalTransaksi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title fw-bold" id="modalTitle">Input Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="inputTipe" onchange="updateKategori()">
                            <option value="pemasukan">💚 Pemasukan</option>
                            <option value="pengeluaran">❤️ Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="inputKategori" onchange="toggleOnlineSection()"></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="inputJumlah" placeholder="0" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="inputTanggal">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputDeskripsi" placeholder="Keterangan singkat transaksi...">
                    </div>

                    {{-- ===== SECTION TOKO ONLINE ===== --}}
                    <div class="col-12" id="onlineSection" style="display:none;">
                        <div class="online-section">
                            <div class="online-section-title">🛒 Detail Penjualan Toko Online</div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Platform <span class="text-danger">*</span></label>
                                    <select class="form-select" id="inputPlatform">
                                        <option value="">-- Pilih Platform --</option>
                                        <option value="Tokopedia">🟠 Tokopedia</option>
                                        <option value="Shopee">🔴 Shopee</option>
                                        <option value="TikTok Shop">🟢 TikTok Shop</option>
                                        <option value="Lazada">🟣 Lazada</option>
                                        <option value="Bukalapak">🔵 Bukalapak</option>
                                        <option value="Website">🌐 Website Sendiri</option>
                                        <option value="Instagram">📸 Instagram</option>
                                        <option value="WhatsApp">📱 WhatsApp</option>
                                        <option value="Lainnya">📦 Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Order</label>
                                    <input type="text" class="form-control" id="inputNoOrder"
                                           placeholder="Contoh: TKP-20260425-001">
                                    <div class="form-text">Nomor order dari marketplace</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Pihak Terkait</label>
                        <input type="text" class="form-control" id="inputPihak" placeholder="Nama customer / supplier">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Metode Bayar</label>
                        <select class="form-select" id="inputMetode">
                            <option value="cash">💵 Cash</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="kartu_kredit">💳 Kartu Kredit</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Referensi / No. Invoice</label>
                        <input type="text" class="form-control" id="inputReferensi" placeholder="SO-2026-0001">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="inputStatus">
                            <option value="lunas">✅ Lunas</option>
                            <option value="pending">⏳ Pending</option>
                            <option value="batal">❌ Batal</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="inputCatatan" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success fw-bold" id="btnSimpan" onclick="simpanTransaksi()">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL DETAIL ===== --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody"></div>
        </div>
    </div>
</div>

{{-- ===== MODAL KELOLA LINK ===== --}}
<div class="modal fade" id="modalKelolaLink" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e40af,#3b82f6);color:white;">
                <h5 class="modal-title fw-bold">🔗 Buat & Kelola Link Penjualan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 mb-4" style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;">
                    <div class="fw-bold mb-3" style="color:#1e40af;">➕ Buat Link Baru</div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Label Link</label>
                            <input type="text" class="form-control" id="linkLabel" placeholder="Contoh: Link Staff A">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Untuk (Nama Admin/Staff)</label>
                            <input type="text" class="form-control" id="linkNamaAdmin" placeholder="Nama penerima link">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Berlaku (hari)</label>
                            <input type="number" class="form-control" id="linkExpiredDays" value="30" min="1" max="90">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Maks Pakai</label>
                            <input type="number" class="form-control" id="linkMaxPakai" value="0" min="0" placeholder="0=∞">
                            <div class="form-text">0 = tidak terbatas</div>
                        </div>
                    </div>
                    <button class="btn btn-primary fw-bold mt-3" onclick="generateLink()">
                        <i class="bi bi-plus-circle me-1"></i>Generate Link
                    </button>
                </div>
                <div id="hasilLinkBox" style="display:none;"
                     class="alert alert-success d-flex align-items-center gap-2 mb-4 flex-wrap">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1">✅ Link berhasil dibuat! Salin dan bagikan ke staff:</div>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control form-control-sm" id="urlHasil" readonly
                                   style="font-family:monospace;font-size:0.82rem;">
                            <button class="btn btn-sm btn-success fw-bold" onclick="copyLink()">
                                <i class="bi bi-clipboard me-1"></i>Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">📋 Daftar Link</div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="loadLinks()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </button>
                </div>
                <div id="listLink">
                    <div class="text-center text-muted py-4">
                        <div class="spinner-border spinner-border-sm me-2 text-primary"></div>Memuat daftar link...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let debounceTimer;
let chartBulanan, chartKategori;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

const KATEGORI = {
    pemasukan: [
        'Penjualan Online','Penjualan Produk','Jasa Instalasi',
        'Jasa Maintenance','Uang Muka / DP','Pelunasan','Lain-lain',
    ],
    pengeluaran: [
        'Pembelian Stok','Operasional Kantor','Gaji Karyawan',
        'Transport & Pengiriman','Marketing & Iklan','Listrik & Internet',
        'Sewa Tempat','Pajak','Peralatan','Lain-lain',
    ],
};

const PLATFORM_CLASS = {
    'Tokopedia':'tokopedia','Shopee':'shopee','TikTok Shop':'tiktok',
    'Lazada':'lazada','Bukalapak':'bukalapak','Website':'website',
    'Instagram':'instagram','WhatsApp':'whatsapp','Lainnya':'lainnya',
};

const PLATFORM_ICON = {
    'Tokopedia':'🟠','Shopee':'🔴','TikTok Shop':'🟢',
    'Lazada':'🟣','Bukalapak':'🔵','Website':'🌐',
    'Instagram':'📸','WhatsApp':'📱','Lainnya':'📦',
};

const WARNA_KATEGORI = [
    '#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6',
    '#06b6d4','#84cc16','#f97316','#ec4899','#6b7280',
];

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];
    updateKategori();
    loadAll();
});

function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadTransaksi, 400);
}

function getBulan() { return document.getElementById('filterBulan').value; }
function getTahun() { return document.getElementById('filterTahun').value; }

function loadAll() {
    loadSummary();
    loadTransaksi();
    loadChart();
    loadKategoriBreakdown();
}

// ===== TOGGLE SECTION ONLINE =====
function toggleOnlineSection() {
    const kategori = document.getElementById('inputKategori').value;
    const section  = document.getElementById('onlineSection');
    if (kategori === 'Penjualan Online') {
        section.style.display = 'block';
        document.getElementById('inputMetode').value = 'transfer';
    } else {
        section.style.display = 'none';
        document.getElementById('inputPlatform').value = '';
        document.getElementById('inputNoOrder').value  = '';
    }
}

// ===== LOAD SUMMARY =====
async function loadSummary() {
    const res  = await fetch(`/api/admin/keuangan/summary?bulan=${getBulan()}&tahun=${getTahun()}`);
    const data = await res.json();
    document.getElementById('sumPemasukan').textContent   = formatRp(data.pemasukan_bulan);
    document.getElementById('sumPengeluaran').textContent = formatRp(data.pengeluaran_bulan);
    document.getElementById('sumSaldo').textContent       = formatRp(data.saldo_total);
    const laba   = data.laba_bulan;
    const labaEl = document.getElementById('sumLaba');
    labaEl.textContent = (laba >= 0 ? '+' : '') + formatRp(laba);
    labaEl.className   = 'sum-value ' + (laba >= 0 ? 'text-success' : 'text-danger');
}

// ===== LOAD TRANSAKSI =====
async function loadTransaksi() {
    document.getElementById('loadingTrx').style.display     = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    const search   = document.getElementById('searchInput').value;
    const tipe     = document.getElementById('filterTipe').value;
    const status   = document.getElementById('filterStatus').value;
    const platform = document.getElementById('filterPlatform').value;

    const url = `/api/admin/keuangan/transaksi?bulan=${getBulan()}&tahun=${getTahun()}&search=${encodeURIComponent(search)}&tipe=${tipe}&status=${status}&platform=${encodeURIComponent(platform)}`;
    const res  = await fetch(url);
    const data = await res.json();

    renderTransaksi(data.data);
    document.getElementById('loadingTrx').style.display     = 'none';
    document.getElementById('tableContainer').style.display = 'block';
}

function renderTransaksi(list) {
    const tbody = document.getElementById('trxTableBody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada transaksi
        </td></tr>`;
        return;
    }
    tbody.innerHTML = list.map(t => {
        const isPemasukan = t.tipe === 'pemasukan';
        const jumlahStr   = (isPemasukan ? '+' : '-') + formatRp(t.jumlah);
        const jumlahColor = isPemasukan ? 'text-success' : 'text-danger';

        const platformBadge = t.platform
            ? `<span class="platform-badge ${(PLATFORM_CLASS[t.platform] || 'lainnya')}">
                 ${PLATFORM_ICON[t.platform] || '📦'} ${t.platform}
               </span>` : '';

        const noOrderLabel = t.no_order
            ? `<div><small class="text-muted" style="font-family:monospace;">${t.no_order}</small></div>` : '';

        return `
        <tr>
            <td><span class="badge bg-light text-dark border" style="font-family:monospace">${t.kode_transaksi}</span></td>
            <td>${formatDate(t.tanggal)}</td>
            <td>
                <div class="fw-semibold">${t.deskripsi}</div>
                ${platformBadge}${noOrderLabel}
                ${t.referensi && !t.no_order ? `<small class="text-muted">${t.referensi}</small>` : ''}
            </td>
            <td><span class="tipe-badge ${t.tipe}">${isPemasukan ? '💚' : '❤️'} ${t.kategori}</span></td>
            <td>${t.pihak_terkait || '<span class="text-muted">-</span>'}</td>
            <td><span class="badge bg-light text-dark">${metodeLabel(t.metode_bayar)}</span></td>
            <td class="text-end fw-bold ${jumlahColor}">${jumlahStr}</td>
            <td class="text-center"><span class="status-badge ${t.status}">${t.status}</span></td>
            <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-xs btn-outline-primary py-0 px-2" onclick="lihatDetail(${t.id})"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-xs btn-outline-warning py-0 px-2" onclick="editTransaksi(${t.id})"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="hapusTransaksi(${t.id}, '${t.kode_transaksi}')"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ===== LOAD CHART BULANAN =====
async function loadChart() {
    const res  = await fetch(`/api/admin/keuangan/chart-data?tahun=${getTahun()}`);
    const json = await res.json();
    const data = json.data;

    if (chartBulanan) chartBulanan.destroy();
    const ctx = document.getElementById('chartBulanan').getContext('2d');
    chartBulanan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.bulan),
            datasets: [
                { label: 'Pemasukan',   data: data.map(d => d.pemasukan),   backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 4 },
                { label: 'Pengeluaran', data: data.map(d => d.pengeluaran), backgroundColor: 'rgba(239,68,68,0.7)',  borderRadius: 4 },
                {
                    label: 'Laba', data: data.map(d => d.laba), type: 'line',
                    borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)',
                    pointBackgroundColor: '#3b82f6', tension: 0.4, fill: true, yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { ticks: { callback: val => 'Rp ' + (val/1000000).toFixed(1) + 'jt' } } }
        }
    });
}

// ===== LOAD KATEGORI PIE =====
async function loadKategoriBreakdown() {
    const res  = await fetch(`/api/admin/keuangan/kategori-breakdown?bulan=${getBulan()}&tahun=${getTahun()}&tipe=pengeluaran`);
    const json = await res.json();
    const data = json.data;
    if (chartKategori) chartKategori.destroy();
    if (!data.length) {
        document.getElementById('kategoriList').innerHTML = '<p class="text-muted small text-center">Belum ada data</p>';
        return;
    }
    const ctx = document.getElementById('chartKategori').getContext('2d');
    chartKategori = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(d => d.kategori),
            datasets: [{ data: data.map(d => d.total), backgroundColor: WARNA_KATEGORI.slice(0, data.length), borderWidth: 2 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    const total = data.reduce((s, d) => s + parseFloat(d.total), 0);
    document.getElementById('kategoriList').innerHTML = data.map((d, i) => `
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="d-flex align-items-center gap-2">
                <div style="width:10px;height:10px;border-radius:2px;background:${WARNA_KATEGORI[i]}"></div>
                <span style="font-size:12px">${d.kategori}</span>
            </div>
            <div style="font-size:12px">
                <strong>${formatRp(d.total)}</strong>
                <span class="text-muted ms-1">${((d.total/total)*100).toFixed(1)}%</span>
            </div>
        </div>
    `).join('');
}

// ===== BUKA MODAL NORMAL =====
function bukaModal(tipe = 'pemasukan') {
    resetModal();
    document.getElementById('inputTipe').value   = tipe;
    document.getElementById('inputStatus').value = 'lunas';
    updateKategori();

    const isPemasukan = tipe === 'pemasukan';
    const header = document.getElementById('modalHeader');
    header.style.background = isPemasukan ? 'linear-gradient(135deg,#065f46,#10b981)' : 'linear-gradient(135deg,#7f1d1d,#dc2626)';
    header.style.color = 'white';
    document.getElementById('modalTitle').textContent = isPemasukan ? '💚 Input Pemasukan' : '❤️ Input Pengeluaran';
    document.getElementById('btnSimpan').className = isPemasukan ? 'btn btn-success fw-bold' : 'btn btn-danger fw-bold';
    new bootstrap.Modal(document.getElementById('modalTransaksi')).show();
}

// ===== ✅ BUKA PENJUALAN ONLINE - Buka tab baru ke /penjualan-online/staff =====
function bukaModalOnline() {
    window.open('{{ url("/penjualan-online/staff") }}', '_blank');
}

// ===== SALIN LINK STAFF =====
function salinLinkStaff() {
    const url = '{{ url("/penjualan-online/staff") }}';
    navigator.clipboard.writeText(url)
        .then(() => showToast('📋 Link staff berhasil disalin!', 'success'))
        .catch(() => {
            const el = document.createElement('input');
            el.value = url;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            showToast('📋 Link staff berhasil disalin!', 'success');
        });
}

// ===== GENERATE LINK BARU =====
async function generateLink() {
    const label       = document.getElementById('linkLabel').value.trim() || 'Link Penjualan';
    const namaAdmin   = document.getElementById('linkNamaAdmin').value.trim() || 'Admin';
    const expiredDays = parseInt(document.getElementById('linkExpiredDays').value) || 30;
    const maxPakai    = parseInt(document.getElementById('linkMaxPakai').value) || 0;

    if (expiredDays < 1 || expiredDays > 90) {
        showToast('❌ Berlaku harus antara 1-90 hari', 'danger');
        return;
    }

    try {
        const res = await fetch('/api/admin/keuangan/generate-link', {
            method : 'POST',
            headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
            body: JSON.stringify({ label, nama_admin:namaAdmin, expired_days:expiredDays, max_penggunaan:maxPakai }),
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('urlHasil').value = data.url;
            document.getElementById('hasilLinkBox').style.display = 'flex';
            showToast('✅ Link berhasil dibuat!', 'success');
            loadLinks();
        } else {
            showToast('❌ ' + (data.message || 'Gagal membuat link'), 'danger');
        }
    } catch (e) {
        showToast('❌ Error: ' + e.message, 'danger');
    }
}

// ===== COPY LINK =====
function copyLink() {
    const input = document.getElementById('urlHasil');
    input.select();
    input.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
        showToast('📋 Link berhasil disalin!', 'success');
    } catch (e) {
        navigator.clipboard.writeText(input.value)
            .then(() => showToast('📋 Link berhasil disalin!', 'success'))
            .catch(() => showToast('❌ Gagal menyalin, salin manual', 'warning'));
    }
}

// ===== LOAD DAFTAR LINK =====
async function loadLinks() {
    document.getElementById('listLink').innerHTML =
        `<div class="text-center text-muted py-4">
            <div class="spinner-border spinner-border-sm me-2 text-primary"></div>Memuat...
         </div>`;
    try {
        const res  = await fetch('/api/admin/keuangan/links');
        const data = await res.json();

        if (!data.data || !data.data.length) {
            document.getElementById('listLink').innerHTML =
                `<div class="text-center text-muted py-4">
                    <i class="bi bi-link-45deg fs-2 d-block mb-2"></i>Belum ada link yang dibuat.
                 </div>`;
            return;
        }

        document.getElementById('listLink').innerHTML = data.data.map(link => {
            const isExpired  = link.is_expired;
            const isInactive = !link.is_active;
            const statusBadge = isExpired
                ? `<span class="badge bg-danger">Expired</span>`
                : (isInactive ? `<span class="badge bg-secondary">Nonaktif</span>` : `<span class="badge bg-success">Aktif</span>`);

            return `
            <div class="link-card ${isInactive || isExpired ? 'nonaktif' : ''}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold">${link.label}</span>${statusBadge}
                        </div>
                        <div class="link-url mb-1">${link.url}</div>
                        <div class="small text-muted">
                            👤 ${link.nama_admin} &nbsp;·&nbsp;
                            ⏳ Exp: ${link.expired_label} &nbsp;·&nbsp;
                            🔢 Dipakai: ${link.jumlah_penggunaan}x
                            ${link.max_penggunaan > 0 ? ' / ' + link.max_penggunaan + 'x' : ' (∞)'}
                        </div>
                    </div>
                    <div class="d-flex gap-1 flex-shrink-0">
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" title="Salin link"
                                onclick="navigator.clipboard.writeText('${link.url}').then(()=>showToast('📋 Link disalin!','success'))">
                            <i class="bi bi-clipboard"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning py-0 px-2"
                                title="${link.is_active ? 'Nonaktifkan' : 'Aktifkan kembali'}"
                                onclick="toggleLink(${link.id})">
                            <i class="bi bi-${link.is_active ? 'pause-circle' : 'play-circle'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus link"
                                onclick="hapusLink(${link.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;
        }).join('');
    } catch (e) {
        document.getElementById('listLink').innerHTML =
            `<p class="text-danger small text-center py-3">❌ Gagal memuat daftar link: ${e.message}</p>`;
    }
}

// ===== TOGGLE LINK =====
async function toggleLink(id) {
    try {
        const res  = await fetch(`/api/admin/keuangan/links/${id}/toggle`, {
            method: 'POST',
            headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
        });
        const data = await res.json();
        if (data.success) { showToast('✅ ' + data.message, 'success'); loadLinks(); }
        else showToast('❌ ' + (data.message || 'Gagal'), 'danger');
    } catch (e) { showToast('❌ Error: ' + e.message, 'danger'); }
}

// ===== HAPUS LINK =====
async function hapusLink(id) {
    if (!confirm('Hapus link ini? Tindakan tidak dapat dibatalkan.')) return;
    try {
        const res  = await fetch(`/api/admin/keuangan/links/${id}`, {
            method: 'DELETE',
            headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
        });
        const data = await res.json();
        if (data.success) { showToast('✅ Link dihapus.', 'success'); loadLinks(); }
        else showToast('❌ ' + (data.message || 'Gagal menghapus'), 'danger');
    } catch (e) { showToast('❌ Error: ' + e.message, 'danger'); }
}

function resetModal() {
    document.getElementById('editId').value         = '';
    document.getElementById('inputJumlah').value    = '';
    document.getElementById('inputDeskripsi').value = '';
    document.getElementById('inputPihak').value     = '';
    document.getElementById('inputReferensi').value = '';
    document.getElementById('inputCatatan').value   = '';
    document.getElementById('inputMetode').value    = 'cash';
    document.getElementById('inputStatus').value    = 'lunas';
    document.getElementById('inputTanggal').value   = new Date().toISOString().split('T')[0];
    document.getElementById('inputPlatform').value  = '';
    document.getElementById('inputNoOrder').value   = '';
    document.getElementById('onlineSection').style.display = 'none';
    const btn = document.getElementById('btnSimpan');
    btn.style.background = '';
    btn.style.color = '';
}

function updateKategori() {
    const tipe = document.getElementById('inputTipe').value;
    const sel  = document.getElementById('inputKategori');
    sel.innerHTML = (KATEGORI[tipe] || []).map(k => `<option value="${k}">${k}</option>`).join('');
    toggleOnlineSection();
}

// ===== SIMPAN TRANSAKSI =====
async function simpanTransaksi() {
    const editId   = document.getElementById('editId').value;
    const kategori = document.getElementById('inputKategori').value;

    const payload = {
        tipe         : document.getElementById('inputTipe').value,
        kategori,
        jumlah       : document.getElementById('inputJumlah').value,
        tanggal      : document.getElementById('inputTanggal').value,
        deskripsi    : document.getElementById('inputDeskripsi').value,
        pihak_terkait: document.getElementById('inputPihak').value,
        metode_bayar : document.getElementById('inputMetode').value,
        referensi    : document.getElementById('inputReferensi').value,
        status       : document.getElementById('inputStatus').value,
        catatan      : document.getElementById('inputCatatan').value,
    };

    if (kategori === 'Penjualan Online') {
        payload.platform = document.getElementById('inputPlatform').value;
        payload.no_order = document.getElementById('inputNoOrder').value;
        if (!payload.platform) {
            showToast('❌ Platform toko online wajib dipilih!', 'danger');
            return;
        }
    }

    if (!payload.jumlah || !payload.deskripsi) {
        showToast('❌ Jumlah dan deskripsi wajib diisi!', 'danger');
        return;
    }

    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    try {
        const url    = editId ? `/api/admin/keuangan/transaksi/${editId}` : '/api/admin/keuangan/transaksi';
        const method = editId ? 'PUT' : 'POST';
        const res  = await fetch(url, {
            method,
            headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalTransaksi')).hide();
            showToast('✅ ' + data.message, 'success');
            loadAll();
        } else {
            showToast('❌ ' + (data.message || 'Gagal menyimpan'), 'danger');
        }
    } catch (e) {
        showToast('❌ Error: ' + e.message, 'danger');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i>Simpan';
    }
}

// ===== EDIT TRANSAKSI =====
async function editTransaksi(id) {
    const res  = await fetch(`/api/admin/keuangan/transaksi/${id}`);
    const data = await res.json();
    if (!data.success) return;
    const t = data.data;

    document.getElementById('editId').value        = t.id;
    document.getElementById('inputTipe').value      = t.tipe;
    updateKategori();
    document.getElementById('inputKategori').value  = t.kategori;
    toggleOnlineSection();
    document.getElementById('inputJumlah').value    = t.jumlah;
    document.getElementById('inputTanggal').value   = t.tanggal;
    document.getElementById('inputDeskripsi').value = t.deskripsi;
    document.getElementById('inputPihak').value     = t.pihak_terkait || '';
    document.getElementById('inputMetode').value    = t.metode_bayar;
    document.getElementById('inputReferensi').value = t.referensi || '';
    document.getElementById('inputStatus').value    = t.status;
    document.getElementById('inputCatatan').value   = t.catatan || '';
    document.getElementById('inputPlatform').value  = t.platform || '';
    document.getElementById('inputNoOrder').value   = t.no_order || '';

    const header = document.getElementById('modalHeader');
    header.style.background = '#1f2937';
    header.style.color = 'white';
    document.getElementById('modalTitle').textContent = '✏️ Edit Transaksi';
    new bootstrap.Modal(document.getElementById('modalTransaksi')).show();
}

// ===== LIHAT DETAIL =====
async function lihatDetail(id) {
    const res  = await fetch(`/api/admin/keuangan/transaksi/${id}`);
    const data = await res.json();
    if (!data.success) return;
    const t = data.data;
    const isPemasukan = t.tipe === 'pemasukan';

    const onlineRows = t.platform ? `
        <tr><th>Platform</th>
            <td><span class="platform-badge ${PLATFORM_CLASS[t.platform] || 'lainnya'}">
                ${PLATFORM_ICON[t.platform] || '📦'} ${t.platform}
            </span></td>
        </tr>
        ${t.no_order ? `<tr><th>No. Order</th><td><code>${t.no_order}</code></td></tr>` : ''}
    ` : '';

    document.getElementById('detailBody').innerHTML = `
        <div class="text-center mb-3">
            <div style="font-size:2.5rem">${isPemasukan ? (t.platform ? '🛒' : '💚') : '❤️'}</div>
            <div class="fw-bold fs-4 ${isPemasukan ? 'text-success' : 'text-danger'}">
                ${isPemasukan ? '+' : '-'}${formatRp(t.jumlah)}
            </div>
            <div class="text-muted small">${t.kode_transaksi}</div>
        </div>
        <table class="table table-bordered table-sm">
            <tr><th width="40%">Tipe</th><td><span class="tipe-badge ${t.tipe}">${t.tipe}</span></td></tr>
            <tr><th>Kategori</th><td>${t.kategori}</td></tr>
            ${onlineRows}
            <tr><th>Tanggal</th><td>${formatDate(t.tanggal)}</td></tr>
            <tr><th>Deskripsi</th><td>${t.deskripsi}</td></tr>
            <tr><th>Pihak Terkait</th><td>${t.pihak_terkait || '-'}</td></tr>
            <tr><th>Metode Bayar</th><td>${metodeLabel(t.metode_bayar)}</td></tr>
            <tr><th>Referensi</th><td>${t.referensi || '-'}</td></tr>
            <tr><th>Status</th><td><span class="status-badge ${t.status}">${t.status}</span></td></tr>
            ${t.catatan ? `<tr><th>Catatan</th><td>${t.catatan}</td></tr>` : ''}
        </table>
    `;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
}

// ===== HAPUS TRANSAKSI =====
async function hapusTransaksi(id, kode) {
    if (!confirm(`Hapus transaksi ${kode}?`)) return;
    const res  = await fetch(`/api/admin/keuangan/transaksi/${id}`, {
        method: 'DELETE',
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
    });
    const data = await res.json();
    if (data.success) { showToast('✅ ' + data.message, 'success'); loadAll(); }
}

// ===== EXPORT =====
function exportLaporan() {
    window.open(`/api/admin/keuangan/transaksi?bulan=${getBulan()}&tahun=${getTahun()}&export=1`, '_blank');
    showToast('📥 Export sedang diproses...', 'info');
}

// ===== HELPERS =====
function formatRp(num) {
    return 'Rp ' + parseInt(num || 0).toLocaleString('id-ID');
}
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}
function metodeLabel(m) {
    const map = { cash:'💵 Cash', transfer:'🏦 Transfer', qris:'📱 QRIS', kartu_kredit:'💳 Kartu' };
    return map[m] || m;
}
function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    el.style.zIndex = 9999;
    el.style.minWidth = '260px';
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}
</script>
@endsection