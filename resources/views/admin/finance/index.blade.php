{{-- resources/views/admin/finance/index.blade.php --}}
@extends('layouts.finance')
@section('title', 'Finance Staff - Input Piutang & Pengeluaran')
@section('content')
<style>
.fin-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
    color: white; padding: 28px 24px;
    border-radius: 14px; margin-bottom: 24px;
}
.summary-grid {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 24px;
}
.sum-card {
    background: white; border-radius: 12px; padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    border-left: 4px solid #e5e7eb; transition: transform 0.2s;
}
.sum-card:hover { transform: translateY(-2px); }
.sum-card.piutang-all     { border-left-color: #f59e0b; }
.sum-card.piutang-pending { border-left-color: #ef4444; }
.sum-card.piutang-lunas   { border-left-color: #10b981; }
.sum-card.pengeluaran     { border-left-color: #8b5cf6; }
.sum-icon { width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:12px; }
.sum-value { font-size: 1.4rem; font-weight: 800; color: #111827; }
.sum-label { font-size: 0.78rem; color: #6b7280; margin-top: 2px; }

/* TABS */
.fin-tabs { display:flex;gap:4px;background:white;border-radius:12px;padding:6px;box-shadow:0 2px 10px rgba(0,0,0,0.07);margin-bottom:18px;flex-wrap:wrap; }
.fin-tab { padding:9px 18px;border-radius:8px;border:none;font-size:.83rem;font-weight:600;cursor:pointer;transition:all .2s;color:#6b7280;background:transparent;display:flex;align-items:center;gap:6px; }
.fin-tab:hover { color:#374151;background:#f3f4f6; }
.fin-tab.active { background:#2563eb;color:white;box-shadow:0 4px 12px rgba(37,99,235,.3); }
.fin-tab .tbadge { background:rgba(255,255,255,.25);color:white;border-radius:20px;padding:1px 7px;font-size:.7rem; }
.fin-tab:not(.active) .tbadge { background:#e5e7eb;color:#6b7280; }
.tab-pane { display:none; }
.tab-pane.active { display:block; }

/* TABLE & TOOLBAR */
.toolbar { background:white;border-radius:12px;padding:14px 18px;box-shadow:0 2px 10px rgba(0,0,0,0.07);margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center; }
.toolbar select,.toolbar input { border:1px solid #d1d5db;border-radius:8px;padding:7px 12px;font-size:.85rem; }
.table-card { background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.07);overflow:hidden;margin-bottom:24px; }
.table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; }
.table-card table th, .table-card table td { white-space: nowrap; }
.table-card table thead th { background:#f8fafc;font-size:.78rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;padding:12px 16px;border-bottom:2px solid #e5e7eb; }
.table-card table tbody td { padding:12px 16px;font-size:.875rem;border-bottom:1px solid #f3f4f6;vertical-align:middle; }
.table-card table tbody tr:hover { background:#f9fafb; }

/* BADGES */
.tipe-badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }
.tipe-badge.piutang     { background:#fef3c7;color:#92400e; }
.tipe-badge.pengeluaran { background:#ede9fe;color:#5b21b6; }
.status-badge { padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600; }
.status-badge.lunas   { background:#d1fae5;color:#065f46; }
.status-badge.pending { background:#fef3c7;color:#92400e; }
.status-badge.batal   { background:#f3f4f6;color:#6b7280; }

/* INVOICE TAB */
.inv-card { background:white;border-radius:12px;border:1px solid #e5e7eb;padding:16px;margin-bottom:12px;transition:box-shadow .2s; }
.inv-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }
.inv-card.overdue { border-left:4px solid #ef4444;background:#fff5f5; }
.inv-card.lunas   { border-left:4px solid #10b981; }
.inv-card.pending { border-left:4px solid #f59e0b; }
.sn-badge { display:inline-block;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:6px;font-size:.72rem;font-family:monospace;font-weight:700;margin:2px; }

/* MODAL */
.modal-content { border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-header  { border-bottom:1px solid #e5e7eb;padding:18px 24px; }
.modal-body    { padding:24px; }
.modal-footer  { border-top:1px solid #e5e7eb;padding:14px 24px;background:#f9fafb;border-radius:0 0 14px 14px; }
.form-label { font-size:.8rem;font-weight:600;color:#374151;margin-bottom:5px; }
.form-control,.form-select { border-radius:8px;border:1px solid #d1d5db;font-size:.875rem;padding:8px 12px; }
.info-box { background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:10px;padding:14px;font-size:.83rem;color:#1e40af; }

/* LAPORAN */
.lap-tab-btn { padding:8px 18px;border-radius:8px;border:2px solid #e5e7eb;background:white;font-weight:700;font-size:.82rem;cursor:pointer;transition:all .2s;color:#374151; }
.lap-tab-btn.active { background:#0f172a;color:white;border-color:#0f172a; }
.lr-row { display:flex;justify-content:space-between;align-items:center;padding:7px 10px;border-bottom:1px solid #f3f4f6;font-size:.85rem; }
.lr-row:last-child { border-bottom:none; }
.lr-total { display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-radius:8px;font-weight:800;margin-top:8px;font-size:.95rem; }
.lr-total.pemasukan  { background:#d1fae5;color:#065f46; }
.lr-total.pengeluaran{ background:#fee2e2;color:#991b1b; }
.lr-total.laba       { background:#dbeafe;color:#1e40af;font-size:1.05rem; }
.lr-total.rugi       { background:#fee2e2;color:#991b1b;font-size:1.05rem; }
.lr-section-title { font-size:.73rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;padding:6px 10px;border-radius:6px;margin-bottom:6px; }
.lr-section-title.pemasukan  { background:#d1fae5;color:#065f46; }
.lr-section-title.pengeluaran{ background:#fee2e2;color:#991b1b; }
.piutang-box-lap { background:#fffbeb;border:1.5px solid #fcd34d;border-radius:8px;padding:10px 14px;display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;font-size:.85rem; }
.cf-mini-table { width:100%;border-collapse:collapse;font-size:.8rem; }
.cf-mini-table thead th { background:#0f172a;color:white;padding:8px 10px;font-size:.72rem;text-transform:uppercase;letter-spacing:.04em; }
.cf-mini-table tbody td { padding:7px 10px;border-bottom:1px solid #f3f4f6; }
.cf-mini-table tfoot td { padding:8px 10px;font-weight:800;background:#f1f5f9;border-top:2px solid #e5e7eb;font-size:.82rem; }
.loading-overlay { text-align:center;padding:40px;color:#9ca3af; }

/* FIX RESPONSIVE MOBILE */
@media(max-width:768px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .sum-card { padding: 15px; }
    .sum-value { font-size: 1.1rem; }
    
    .toolbar { flex-direction: column; align-items: stretch; padding: 14px; }
    .toolbar > * { width: 100%; margin-bottom: 8px; }
    .toolbar > *:last-child { margin-bottom: 0; }
    
    .fin-header { padding: 20px 16px; }
    .fin-header .d-flex { flex-direction: column; align-items: stretch !important; text-align: center; }
    .fin-header .d-flex > div:last-child { justify-content: center; margin-top: 10px; }
    
    .fin-tabs { justify-content: center; }
}
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="fin-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📋 Finance Staff</h1>
                <p class="mb-0 opacity-75">Kelola invoice, piutang & pengeluaran — data masuk ke laporan keuangan bos</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn fw-bold" style="background:#10b981;color:white;" onclick="bukaModalLaporan()">📊 Laporan</button>
                <button class="btn fw-bold" style="background:#f59e0b;color:white;" onclick="bukaModal('piutang')">💰 Input Piutang</button>
                <button class="btn btn-danger fw-bold" onclick="bukaModal('pengeluaran')">💸 Input Pengeluaran</button>
            </div>
        </div>
    </div>

    {{-- INFO --}}
    <div class="info-box mb-4">
        ℹ️ <strong>Catatan:</strong> Invoice dari SO akan muncul di tab <strong>Invoice</strong>.
        Tandai Lunas di sini → otomatis masuk laporan keuangan bos sebagai pemasukan.
    </div>

    {{-- FILTER --}}
    <div class="d-flex gap-2 mb-3 align-items-center flex-wrap">
        <select id="filterBulan" class="form-select" style="width:auto; flex-grow: 1;" onchange="loadAll()">
            @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}" {{ $m==date('m')?'selected':'' }}>{{ DateTime::createFromFormat('!m',$m)->format('F') }}</option>
            @endfor
        </select>
        <select id="filterTahun" class="form-select" style="width:auto; flex-grow: 1;" onchange="loadAll()">
            @for($y=date('Y');$y>=date('Y')-3;$y--)
                <option value="{{ $y }}" {{ $y==date('Y')?'selected':'' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-grid">
        <div class="sum-card piutang-all">
            <div class="sum-icon" style="background:#fef3c7">💰</div>
            <div class="sum-value text-warning" id="sumPiutangBulan">-</div>
            <div class="sum-label">Total Piutang Bulan Ini</div>
        </div>
        <div class="sum-card piutang-pending">
            <div class="sum-icon" style="background:#fee2e2">⏳</div>
            <div class="sum-value text-danger" id="sumPiutangPending">-</div>
            <div class="sum-label">Piutang Belum Lunas</div>
        </div>
        <div class="sum-card piutang-lunas">
            <div class="sum-icon" style="background:#d1fae5">✅</div>
            <div class="sum-value text-success" id="sumPiutangLunas">-</div>
            <div class="sum-label">Piutang Lunas Bulan Ini</div>
        </div>
        <div class="sum-card pengeluaran">
            <div class="sum-icon" style="background:#ede9fe">💸</div>
            <div class="sum-value" style="color:#5b21b6" id="sumPengeluaran">-</div>
            <div class="sum-label">Pengeluaran Bulan Ini</div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="fin-tabs">
        <button class="fin-tab active" onclick="switchTab('invoice',this)">
            🧾 Invoice SO <span class="tbadge" id="tabInvCount">0</span>
        </button>
        <button class="fin-tab" onclick="switchTab('transaksi',this)">
            📋 Transaksi Finance
        </button>
    </div>

    {{-- ===== TAB INVOICE ===== --}}
    <div id="tab-invoice" class="tab-pane active">
        <div class="toolbar">
            <input type="text" id="searchInv" placeholder="🔍 Cari invoice / SO / customer..."
                style="flex:1;min-width:180px;" oninput="debounceInv()">
            <select id="filterInvStatus" onchange="loadInvoice()">
                <option value="">Semua Status</option>
                <option value="pending">⏳ Belum Lunas</option>
                <option value="lunas">✅ Sudah Lunas</option>
            </select>
            <button class="btn btn-outline-secondary btn-sm" onclick="loadInvoice()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>

        <div class="table-card">
            <div id="loadingInv" class="loading-overlay">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Memuat invoice...</p>
            </div>
            <div id="invContainer" style="display:none">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>SO</th>
                                <th>Customer</th>
                                <th>Tgl Invoice</th>
                                <th>Tipe Bayar</th>
                                <th>Jatuh Tempo</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="invTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB TRANSAKSI ===== --}}
    <div id="tab-transaksi" class="tab-pane">
        <div class="toolbar">
            <input type="text" id="searchInput" placeholder="🔍 Cari transaksi / kode / nama..."
                style="flex:1;min-width:180px;" oninput="debounceLoad()">
            <select id="filterTipe" onchange="loadTransaksi()">
                <option value="">Semua Tipe</option>
                <option value="piutang">💰 Piutang</option>
                <option value="pengeluaran">💸 Pengeluaran</option>
            </select>
            <select id="filterStatus" onchange="loadTransaksi()">
                <option value="">Semua Status</option>
                <option value="lunas">✅ Lunas</option>
                <option value="pending">⏳ Pending</option>
                <option value="batal">❌ Batal</option>
            </select>
            <button class="btn btn-outline-secondary btn-sm" onclick="loadTransaksi()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
        <div class="table-card">
            <div id="loadingTrx" class="loading-overlay">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Memuat data...</p>
            </div>
            <div id="tableContainer" style="display:none">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th><th>Tanggal</th><th>Tipe</th><th>Deskripsi</th>
                                <th>Pihak Terkait</th><th>Kategori</th>
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
    </div>
</div>

{{-- ===== MODAL DETAIL INVOICE ===== --}}
<div class="modal fade" id="modalDetailInv" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e3a5f,#2563eb);color:white;">
                <h5 class="modal-title fw-bold">🧾 Detail Invoice</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailInvBody"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-dark btn-sm" id="btnDownloadInv" onclick="downloadInv()">
                    <i class="bi bi-file-pdf me-1"></i>Download PDF
                </button>
                <button class="btn btn-success btn-sm" id="btnLunasInv" onclick="tandaiLunasInv()">
                    ✅ Tandai Lunas
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL CETAK SN ===== --}}
<div class="modal fade" id="modalCetakSN" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#1e40af;color:white;">
                <h5 class="modal-title fw-bold">🔢 Serial Number</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cetakSNBody"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL INPUT ===== --}}
<div class="modal fade" id="modalInput" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title fw-bold" id="modalTitle">Input Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="inputTipe" onchange="updateKategori()">
                            <option value="piutang">💰 Piutang</option>
                            <option value="pengeluaran">💸 Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="inputKategori"></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pihak Terkait <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputPihak" placeholder="Nama customer / perusahaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="inputJumlah" placeholder="0" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="inputTanggal">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" id="inputJatuhTempo">
                        <div class="form-text">Khusus piutang — opsional</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputDeskripsi" placeholder="Keterangan singkat...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Referensi / Invoice</label>
                        <input type="text" class="form-control" id="inputReferensi" placeholder="INV-2026-0001">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Metode Bayar</label>
                        <select class="form-select" id="inputMetode">
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="cash">💵 Cash</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="kartu_kredit">💳 Kartu Kredit</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="inputStatus">
                            <option value="pending">⏳ Pending</option>
                            <option value="lunas">✅ Lunas</option>
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
                <button type="button" class="btn fw-bold" id="btnSimpan" onclick="simpanTransaksi()">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL TANDAI LUNAS ===== --}}
<div class="modal fade" id="modalLunas" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#065f46,#10b981);color:white;">
                <h5 class="modal-title fw-bold">✅ Tandai Lunas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div style="font-size:3rem">💰</div>
                <div id="lunasKeterangan"></div>
                <input type="hidden" id="lunasId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success fw-bold" onclick="konfirmasiLunas()">✅ Ya, Tandai Lunas</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL LAPORAN ===== --}}
<div class="modal fade" id="modalLaporan" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#0f172a,#1e3a5f);color:white;border-radius:14px 14px 0 0;">
                <div>
                    <h5 class="modal-title fw-bold mb-0">📊 Laporan Keuangan</h5>
                    <small class="opacity-75" id="lapPeriodeLabel"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div style="background:#f8fafc;padding:14px 20px;border-bottom:1px solid #e5e7eb;">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <select id="lapBulan" class="form-select form-select-sm" style="width:auto;" onchange="lapLoadAll()">
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ $m==date('m')?'selected':'' }}>{{ DateTime::createFromFormat('!m',$m)->format('F') }}</option>
                        @endfor
                    </select>
                    <select id="lapTahun" class="form-select form-select-sm" style="width:auto;" onchange="lapLoadAll()">
                        @for($y=date('Y');$y>=date('Y')-3;$y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    <div class="ms-auto d-flex gap-2 flex-wrap">
                        <button class="lap-tab-btn active" id="lapTabLR" onclick="lapSwitchTab('lr',this)">📈 Laba Rugi</button>
                        <button class="lap-tab-btn" id="lapTabCF" onclick="lapSwitchTab('cf',this)">💧 Cash Flow</button>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="padding:20px;">
                <div id="lapLoading" class="text-center py-5 text-muted">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Memuat laporan...</p>
                </div>
                <div id="lapPanelLR" style="display:none;">
                    <div class="piutang-box-lap">
                        <div><div class="fw-bold">🧾 Piutang Belum Lunas</div><div class="small text-muted">Tidak termasuk dalam pemasukan</div></div>
                        <div class="fw-bold text-warning" id="lapPiutangOutstanding">-</div>
                    </div>
                    <div class="mb-3">
                        <div class="lr-section-title pemasukan">💚 Pemasukan Reguler</div>
                        <div id="lapListPemasukan"></div>
                        <div class="lr-section-title pemasukan mt-2">💚 Piutang Lunas (Tagihan Terbayar)</div>
                        <div id="lapListPiutangLunas"></div>
                        <div class="lr-total pemasukan mt-2"><span>TOTAL PEMASUKAN</span><span id="lapTotalPemasukan">-</span></div>
                    </div>
                    <div class="mb-3">
                        <div class="lr-section-title pengeluaran">❤️ Rincian Pengeluaran</div>
                        <div id="lapListPengeluaran"></div>
                        <div class="lr-total pengeluaran mt-2"><span>TOTAL PENGELUARAN</span><span id="lapTotalPengeluaran">-</span></div>
                    </div>
                    <div class="lr-total laba" id="lapBoxLabaRugi">
                        <span id="lapLabelLabaRugi">LABA / RUGI BERSIH</span>
                        <span id="lapNilaiLabaRugi">-</span>
                    </div>
                </div>
                <div id="lapPanelCF" style="display:none;">
                    <div class="row g-3 mb-3">
                        <div class="col-4"><div style="background:#d1fae5;border-radius:10px;padding:14px;text-align:center;"><div class="fw-bold text-success fs-5" id="lapCFMasuk">-</div><div style="font-size:.75rem;color:#065f46;">Total Kas Masuk</div></div></div>
                        <div class="col-4"><div style="background:#fee2e2;border-radius:10px;padding:14px;text-align:center;"><div class="fw-bold text-danger fs-5" id="lapCFKeluar">-</div><div style="font-size:.75rem;color:#991b1b;">Total Kas Keluar</div></div></div>
                        <div class="col-4"><div style="background:#dbeafe;border-radius:10px;padding:14px;text-align:center;"><div class="fw-bold text-primary fs-5" id="lapCFNet">-</div><div style="font-size:.75rem;color:#1e40af;">Net Cash Flow</div></div></div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="cf-mini-table">
                            <thead><tr><th>Bulan</th><th class="text-end">Saldo Awal</th><th class="text-end">Kas Masuk</th><th class="text-end">Kas Keluar</th><th class="text-end">Net</th><th class="text-end">Saldo Akhir</th></tr></thead>
                            <tbody id="lapCFBody"></tbody>
                            <tfoot><tr><td><strong>TOTAL</strong></td><td class="text-end">-</td><td class="text-end text-success fw-bold" id="lapCFFootMasuk">-</td><td class="text-end text-danger fw-bold" id="lapCFFootKeluar">-</td><td class="text-end fw-bold" id="lapCFFootNet">-</td><td class="text-end fw-bold" id="lapCFFootSaldo">-</td></tr></tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-radius:0 0 14px 14px;">
                <small class="text-muted me-auto">Data real-time dari transaksi yang sudah lunas</small>
                <button class="btn btn-outline-success btn-sm fw-bold" onclick="lapExportExcel()">📊 Export Excel</button>
                <button class="btn btn-outline-primary btn-sm fw-bold" onclick="lapExportPdf()">🖨️ Export PDF</button>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let debounceTimer, debounceInvTimer;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let currentInvId = null;

const KATEGORI = {
    piutang: ['Piutang Dagang','Piutang Jasa','Piutang Instalasi','Piutang Maintenance','Uang Muka / DP','Lain-lain'],
    pengeluaran: ['Pembelian Stok','Operasional Kantor','Gaji Karyawan','Transport & Pengiriman','Marketing & Iklan','Listrik & Internet','Sewa Tempat','Pajak','Peralatan','Lain-lain'],
};

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];
    updateKategori();
    loadAll();
});

function getBulan() { return document.getElementById('filterBulan').value; }
function getTahun() { return document.getElementById('filterTahun').value; }
function debounceLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(loadTransaksi, 400); }
function debounceInv()  { clearTimeout(debounceInvTimer); debounceInvTimer = setTimeout(loadInvoice, 400); }

function loadAll() { loadSummary(); loadInvoice(); loadTransaksi(); }

function switchTab(name, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.fin-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// ===== SUMMARY =====
async function loadSummary() {
    try {
        const res = await fetch(`/api/admin/finance/summary?bulan=${getBulan()}&tahun=${getTahun()}`);
        const d   = await res.json();
        document.getElementById('sumPiutangBulan').textContent   = formatRp(d.piutang_bulan);
        document.getElementById('sumPiutangPending').textContent = formatRp(d.piutang_pending);
        document.getElementById('sumPiutangLunas').textContent   = formatRp(d.piutang_lunas);
        document.getElementById('sumPengeluaran').textContent    = formatRp(d.pengeluaran_bulan);
    } catch(e) { console.error(e); }
}

// ===== LOAD INVOICE =====
async function loadInvoice() {
    document.getElementById('loadingInv').style.display  = 'block';
    document.getElementById('invContainer').style.display = 'none';

    const search = document.getElementById('searchInv').value;
    const status = document.getElementById('filterInvStatus').value;

    const res  = await fetch(`/api/admin/finance/invoices?search=${encodeURIComponent(search)}&status=${status}`);
    const data = await res.json();
    const list = data.data || [];

    document.getElementById('tabInvCount').textContent = list.length;
    renderInvoice(list);

    document.getElementById('loadingInv').style.display  = 'none';
    document.getElementById('invContainer').style.display = 'block';
}

function renderInvoice(list) {
    const tbody = document.getElementById('invTableBody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada invoice</td></tr>`;
        return;
    }

    tbody.innerHTML = list.map(inv => {
        const isOverdue = inv.jatuh_tempo && inv.jatuh_tempo < new Date().toISOString().split('T')[0] && inv.status === 'pending';
        const statusBadge = inv.status === 'lunas'
            ? `<span class="status-badge lunas">✅ Lunas</span>`
            : (isOverdue
                ? `<span class="status-badge pending" style="background:#fee2e2;color:#991b1b">🚨 Overdue</span>`
                : `<span class="status-badge pending">⏳ Pending</span>`);

        const tipeBadge = inv.tipe_bayar === 'tempo'
            ? `<span class="badge bg-warning text-dark">⏱ Tempo ${inv.tempo_hari}h</span>`
            : `<span class="badge bg-light text-dark">💵 Cash</span>`;

        const btnLunas = inv.status === 'pending'
            ? `<button class="btn btn-xs btn-outline-success py-0 px-2"
                onclick="bukaModalLunas(${inv.id},'${inv.invoice_number}','${inv.pihak_terkait}',${inv.jumlah},'piutang')"
                title="Tandai Lunas">✅</button>` : '';

        return `<tr>
            <td><span class="badge bg-light text-primary border" style="font-family:monospace">${inv.invoice_number||inv.kode_transaksi}</span></td>
            <td><span style="font-family:monospace;font-size:.78rem;color:#6b7280">${inv.so_number||'—'}</span></td>
            <td><div class="fw-semibold">${inv.pihak_terkait||'—'}</div></td>
            <td style="font-size:.82rem">${formatDate(inv.invoice_date||inv.tanggal)}</td>
            <td>${tipeBadge}</td>
            <td>
                ${inv.jatuh_tempo ? `<div style="font-size:.82rem">${formatDate(inv.jatuh_tempo)}</div>` : '—'}
                ${isOverdue ? `<div><span style="font-size:.7rem;color:#ef4444;font-weight:700">LEWAT JATUH TEMPO</span></div>` : ''}
            </td>
            <td class="text-end fw-bold text-success">${formatRp(inv.jumlah)}</td>
            <td class="text-center">${statusBadge}</td>
            <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-xs btn-outline-primary py-0 px-2" onclick="lihatDetailInv(${inv.id})" title="Detail & Item">
                        <i class="bi bi-eye"></i>
                    </button>
                    ${btnLunas}
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ===== LIHAT DETAIL INVOICE + ITEM + SN =====
async function lihatDetailInv(id) {
    currentInvId = id;
    const res  = await fetch(`/api/admin/finance/invoice-detail/${id}`);
    const data = await res.json();
    if (!data.success) return;

    const inv   = data.invoice;
    const so    = data.so;
    const items = data.items || [];

    const isLunas = inv.status === 'lunas';

    document.getElementById('btnLunasInv').style.display  = isLunas ? 'none' : 'inline-flex';
    document.getElementById('btnDownloadInv').href = `/admin/gudang/sales-orders/${so?.id}/invoice/download`;

    // Items dengan SN
    const itemRows = items.map(item => {
        const snBadges = (item.serials || []).map(s =>
            `<span class="sn-badge">${s.serial_number}</span>`
        ).join('');
        const snSection = item.serials?.length
            ? `<div class="mt-1">${snBadges}
               <button class="btn btn-xs btn-outline-primary py-0 px-2 ms-1" onclick="cetakSN(${JSON.stringify(item.serials).replace(/"/g,"'")}, '${item.nama_produk}')">
                   🖨️ Cetak SN
               </button></div>`
            : '';
        return `<tr>
            <td>${item.nama_produk||item.notes||'—'}</td>
            <td class="text-center">${item.qty}</td>
            <td>Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</td>
            <td class="fw-bold text-success">Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
            <td>${snSection || '<span class="text-muted small">—</span>'}</td>
        </tr>`;
    }).join('');

    const dpRows = (inv.dp_nominal > 0) ? `
        <tr>
            <td colspan="3" class="text-end text-muted">DP / Uang Muka</td>
            <td colspan="2" class="fw-bold text-warning">- ${formatRp(inv.dp_nominal)}</td>
        </tr>
        <tr style="background:#f0fdf4;">
            <td colspan="3" class="text-end fw-bold">SISA TAGIHAN</td>
            <td colspan="2" class="fw-bold text-danger fs-6">${formatRp(inv.sisa_tagihan)}</td>
        </tr>` : '';

    const dpInfoRows = (inv.dp_nominal > 0) ? `
        <tr><td class="text-muted">DP / Uang Muka</td><td><strong class="text-warning">${formatRp(inv.dp_nominal)}</strong></td></tr>
        <tr><td class="text-muted">Sisa Tagihan</td><td><strong class="text-danger">${formatRp(inv.sisa_tagihan)}</strong></td></tr>` : '';

    document.getElementById('detailInvBody').innerHTML = `
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="p-3" style="background:#f8fafc;border-radius:8px;border:1px solid #e5e7eb;">
                    <div class="fw-bold mb-2 text-primary">🧾 Info Invoice</div>
                    <table class="table table-borderless table-sm mb-0 small">
                        <tr><td class="text-muted">No. Invoice</td><td><strong style="font-family:monospace">${inv.invoice_number||inv.kode_transaksi}</strong></td></tr>
                        <tr><td class="text-muted">No. SO</td><td><span style="font-family:monospace">${inv.so_number||'—'}</span></td></tr>
                        <tr><td class="text-muted">Customer</td><td><strong>${inv.pihak_terkait||'—'}</strong></td></tr>
                        <tr><td class="text-muted">Tgl Invoice</td><td>${formatDate(inv.invoice_date||inv.tanggal)}</td></tr>
                        <tr><td class="text-muted">Tipe Bayar</td><td>${inv.tipe_bayar==='tempo'?`⏱ Tempo ${inv.tempo_hari} hari`:'💵 Cash'}</td></tr>
                        ${inv.jatuh_tempo?`<tr><td class="text-muted">Jatuh Tempo</td><td><strong>${formatDate(inv.jatuh_tempo)}</strong></td></tr>`:''}
                        <tr><td class="text-muted">Status</td><td><span class="status-badge ${inv.status}">${inv.status}</span></td></tr>
                        ${dpInfoRows}
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3" style="background:#eff6ff;border-radius:8px;border:1px solid #bfdbfe;">
                    <div class="fw-bold mb-2 text-primary">🏦 Rekening Tujuan</div>
                    <div class="fw-bold fs-5">${inv.nama_bank||'—'}</div>
                    <div style="font-family:monospace;font-size:1.1rem;font-weight:700">${inv.no_rekening||'—'}</div>
                    <div class="text-muted small">a.n. ${inv.nama_rekening||'—'}</div>
                    <div class="mt-2"><span class="badge bg-light text-dark">${inv.metode_bayar||'—'}</span></div>
                </div>
            </div>
        </div>
        <div class="fw-bold mb-2">📦 Item Produk</div>
        <div style="overflow-x:auto;">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th><th>Serial Number</th></tr>
                </thead>
                <tbody>${itemRows||'<tr><td colspan="5" class="text-center text-muted">Data item tidak tersedia</td></tr>'}</tbody>
                <tfoot class="table-light">
                    <tr><td colspan="3" class="text-end fw-bold">TOTAL</td><td colspan="2" class="fw-bold text-success">${formatRp(inv.jumlah)}</td></tr>
                    ${dpRows}
                </tfoot>
            </table>
        </div>`;

    // Kita passing 'piutang' default karena ini tab invoice
    document.getElementById('btnLunasInv').setAttribute('onclick', `bukaModalLunas(${id},'${inv.invoice_number||inv.kode_transaksi}','${inv.pihak_terkait||'-'}',${inv.jumlah},'piutang')`);
    new bootstrap.Modal(document.getElementById('modalDetailInv')).show();
}

// ===== TANDAI LUNAS DARI DETAIL MODAL =====
function tandaiLunasInv() {
    bootstrap.Modal.getInstance(document.getElementById('modalDetailInv')).hide();
    // Logic modal lunas sudah di-handle oleh onclick dari btnLunasInv
}

function downloadInv() {
    const href = document.getElementById('btnDownloadInv').href;
    window.open(href, '_blank');
}

// ===== CETAK SN =====
function cetakSN(serials, produk) {
    const snList = serials.map(s => `
        <div style="border:1px solid #e5e7eb;border-radius:6px;padding:8px 12px;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-upc-scan text-primary"></i>
            <span style="font-family:monospace;font-size:1rem;font-weight:700">${s.serial_number}</span>
        </div>`).join('');

    document.getElementById('cetakSNBody').innerHTML = `
        <div class="fw-bold mb-3">${produk}</div>
        <div class="mb-2 text-muted small">${serials.length} Serial Number</div>
        ${snList}`;

    new bootstrap.Modal(document.getElementById('modalCetakSN')).show();
}

// ===== KONFIRMASI LUNAS =====
async function konfirmasiLunas() {
    const id  = document.getElementById('lunasId').value;
    const res = await fetch(`/api/admin/finance/transaksi/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken },
        body: JSON.stringify({ status: 'lunas' }),
    });
    const data = await res.json();
    if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('modalLunas')).hide();
        showToast('✅ ' + data.message, 'success');
        loadAll();
    } else {
        showToast('❌ Gagal memperbarui status', 'danger');
    }
}

// ===== BUKA MODAL LUNAS DARI TABEL =====
function bukaModalLunas(id, kode, pihak, jumlah, tipe) {
    document.getElementById('lunasId').value = id;
    const jenis = (tipe === 'pengeluaran') ? 'HUTANG (Pengeluaran)' : 'PIUTANG';
    document.getElementById('lunasKeterangan').innerHTML = `
        <span class="badge bg-dark mb-1">${jenis}</span><br>
        ${kode} — ${pihak} — <strong>${formatRp(jumlah)}</strong><br>
        <small class="text-muted">Akan ditandai sebagai Lunas.</small>
    `;
    new bootstrap.Modal(document.getElementById('modalLunas')).show();
}

// ===== TRANSAKSI =====
async function loadTransaksi() {
    document.getElementById('loadingTrx').style.display     = 'block';
    document.getElementById('tableContainer').style.display = 'none';

    const search = document.getElementById('searchInput').value;
    const tipe   = document.getElementById('filterTipe').value;
    const status = document.getElementById('filterStatus').value;

    const res  = await fetch(`/api/admin/finance/transaksi?bulan=${getBulan()}&tahun=${getTahun()}&search=${encodeURIComponent(search)}&tipe=${tipe}&status=${status}`);
    const data = await res.json();

    renderTransaksi(data.data || []);
    document.getElementById('loadingTrx').style.display     = 'none';
    document.getElementById('tableContainer').style.display = 'block';
}

function renderTransaksi(list) {
    const tbody = document.getElementById('trxTableBody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada transaksi</td></tr>`;
        return;
    }
    tbody.innerHTML = list.map(t => {
        const isPiutang = t.tipe === 'piutang';
        const isPending = t.status === 'pending';
        // Hapus (isPiutang &&) agar tombol lunas muncul di hutang pending juga
        const btnLunas = isPending
            ? `<button class="btn btn-xs btn-outline-success py-0 px-2" onclick="bukaModalLunas(${t.id},'${t.kode_transaksi}','${t.pihak_terkait}',${t.jumlah},'${t.tipe}')" title="Tandai Lunas">✅</button>` : '';
        return `<tr>
            <td><span class="badge bg-light text-dark border" style="font-family:monospace">${t.kode_transaksi}</span></td>
            <td>${formatDate(t.tanggal)}</td>
            <td><span class="tipe-badge ${t.tipe}">${isPiutang?'💰':'💸'} ${t.tipe}</span></td>
            <td><div class="fw-semibold">${t.deskripsi}</div>${t.referensi?`<small class="text-muted">${t.referensi}</small>`:''}</td>
            <td>${t.pihak_terkait||'<span class="text-muted">-</span>'}</td>
            <td><span class="badge bg-light text-dark">${t.kategori}</span></td>
            <td class="text-end fw-bold ${isPiutang?'text-warning':'text-danger'}">${formatRp(t.jumlah)}</td>
            <td class="text-center"><span class="status-badge ${t.status}">${t.status}</span></td>
            <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                    ${btnLunas}
                    <button class="btn btn-xs btn-outline-warning py-0 px-2" onclick="editTransaksi(${t.id})"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="hapusTransaksi(${t.id},'${t.kode_transaksi}')"><i class="bi bi-trash"></i></button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ===== MODAL INPUT =====
function bukaModal(tipe='piutang') {
    resetModal();
    document.getElementById('inputTipe').value   = tipe;
    document.getElementById('inputStatus').value = tipe==='piutang'?'pending':'lunas';
    updateKategori();
    const header = document.getElementById('modalHeader');
    header.style.background = tipe==='piutang'?'linear-gradient(135deg,#78350f,#f59e0b)':'linear-gradient(135deg,#4c1d95,#8b5cf6)';
    header.style.color = 'white';
    document.getElementById('modalTitle').textContent = tipe==='piutang'?'💰 Input Piutang':'💸 Input Pengeluaran';
    const btn = document.getElementById('btnSimpan');
    btn.style.background = tipe==='piutang'?'#f59e0b':'#8b5cf6';
    btn.style.color = 'white'; btn.style.borderColor = 'transparent';
    new bootstrap.Modal(document.getElementById('modalInput')).show();
}

function updateKategori() {
    const tipe = document.getElementById('inputTipe').value;
    const sel  = document.getElementById('inputKategori');
    sel.innerHTML = (KATEGORI[tipe]||[]).map(k=>`<option value="${k}">${k}</option>`).join('');
}

async function simpanTransaksi() {
    const editId  = document.getElementById('editId').value;
    const payload = {
        tipe          : document.getElementById('inputTipe').value,
        kategori      : document.getElementById('inputKategori').value,
        pihak_terkait : document.getElementById('inputPihak').value,
        jumlah        : document.getElementById('inputJumlah').value,
        tanggal       : document.getElementById('inputTanggal').value,
        jatuh_tempo   : document.getElementById('inputJatuhTempo').value || null,
        deskripsi     : document.getElementById('inputDeskripsi').value,
        referensi     : document.getElementById('inputReferensi').value,
        metode_bayar  : document.getElementById('inputMetode').value,
        status        : document.getElementById('inputStatus').value,
        catatan       : document.getElementById('inputCatatan').value,
    };
    if (!payload.pihak_terkait||!payload.jumlah||!payload.deskripsi) {
        showToast('❌ Pihak terkait, jumlah, dan deskripsi wajib diisi!','danger'); return;
    }
    const btn = document.getElementById('btnSimpan');
    btn.disabled=true; btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
    try {
        const url    = editId?`/api/admin/finance/transaksi/${editId}`:'/api/admin/finance/transaksi';
        const method = editId?'PUT':'POST';
        const res    = await fetch(url,{method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken},body:JSON.stringify(payload)});
        const data   = await res.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalInput')).hide();
            showToast('✅ '+data.message,'success'); loadAll();
        } else showToast('❌ '+(data.message||'Gagal menyimpan'),'danger');
    } catch(e) { showToast('❌ Error: '+e.message,'danger'); }
    finally { btn.disabled=false; btn.innerHTML='<i class="bi bi-save me-1"></i>Simpan'; }
}

async function editTransaksi(id) {
    const res=await fetch(`/api/admin/finance/transaksi/${id}`);
    const data=await res.json();
    if(!data.success)return;
    const t=data.data;
    document.getElementById('editId').value=t.id;
    document.getElementById('inputTipe').value=t.tipe; updateKategori();
    document.getElementById('inputKategori').value=t.kategori;
    document.getElementById('inputPihak').value=t.pihak_terkait||'';
    document.getElementById('inputJumlah').value=t.jumlah;
    document.getElementById('inputTanggal').value=t.tanggal;
    document.getElementById('inputJatuhTempo').value=t.jatuh_tempo||'';
    document.getElementById('inputDeskripsi').value=t.deskripsi;
    document.getElementById('inputReferensi').value=t.referensi||'';
    document.getElementById('inputMetode').value=t.metode_bayar||'transfer';
    document.getElementById('inputStatus').value=t.status;
    document.getElementById('inputCatatan').value=t.catatan||'';
    const header=document.getElementById('modalHeader');
    header.style.background='#1f2937'; header.style.color='white';
    document.getElementById('modalTitle').textContent='✏️ Edit Transaksi';
    const btn=document.getElementById('btnSimpan');
    btn.style.background='#374151'; btn.style.color='white';
    new bootstrap.Modal(document.getElementById('modalInput')).show();
}

async function hapusTransaksi(id,kode) {
    if(!confirm(`Hapus transaksi ${kode}?`))return;
    const res=await fetch(`/api/admin/finance/transaksi/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':csrfToken}});
    const data=await res.json();
    if(data.success){showToast('✅ '+data.message,'success');loadAll();}
}

function resetModal() {
    ['editId','inputPihak','inputJumlah','inputDeskripsi','inputReferensi','inputCatatan'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('inputMetode').value='transfer';
    document.getElementById('inputTanggal').value=new Date().toISOString().split('T')[0];
}

// ===== LAPORAN =====
let lapCurrentTab='lr';
function lapGetBulan(){return document.getElementById('lapBulan').value}
function lapGetTahun(){return document.getElementById('lapTahun').value}
function lapUpdatePeriodeLabel(){const bn=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];document.getElementById('lapPeriodeLabel').textContent=bn[lapGetBulan()]+' '+lapGetTahun();}
function bukaModalLaporan(){lapUpdatePeriodeLabel();lapSwitchTab('lr',document.getElementById('lapTabLR'));new bootstrap.Modal(document.getElementById('modalLaporan')).show();lapLoadAll();}
function lapSwitchTab(tab,btn){lapCurrentTab=tab;document.querySelectorAll('.lap-tab-btn').forEach(b=>b.classList.remove('active'));btn.classList.add('active');document.getElementById('lapPanelLR').style.display=tab==='lr'?'block':'none';document.getElementById('lapPanelCF').style.display=tab==='cf'?'block':'none';lapLoadAll();}
function lapLoadAll(){lapUpdatePeriodeLabel();if(lapCurrentTab==='lr')lapLoadLabaRugi();if(lapCurrentTab==='cf')lapLoadCashFlow();}

async function lapLoadLabaRugi(){
    document.getElementById('lapLoading').style.display='block';
    document.getElementById('lapPanelLR').style.display='none';
    try{
        const res=await fetch(`/api/admin/laporan/laba-rugi?bulan=${lapGetBulan()}&tahun=${lapGetTahun()}`);
        const data=await res.json();
        if(!data.success)throw new Error('Gagal');
        document.getElementById('lapPiutangOutstanding').textContent=formatRp(data.piutang_outstanding);
        document.getElementById('lapListPemasukan').innerHTML=data.pemasukan.length?data.pemasukan.map(p=>`<div class="lr-row"><span>${p.kategori}</span><span class="text-success fw-semibold">${formatRp(p.total)}</span></div>`).join(''):'<div class="lr-row text-muted small">Tidak ada pemasukan</div>';
        document.getElementById('lapListPiutangLunas').innerHTML=data.piutang_lunas.length?data.piutang_lunas.map(p=>`<div class="lr-row"><span>${p.kategori}</span><span class="text-success fw-semibold">${formatRp(p.total)}</span></div>`).join(''):'<div class="lr-row text-muted small">Tidak ada piutang lunas</div>';
        document.getElementById('lapTotalPemasukan').textContent=formatRp(data.total_pemasukan);
        document.getElementById('lapListPengeluaran').innerHTML=data.pengeluaran.length?data.pengeluaran.map(p=>`<div class="lr-row"><span>${p.kategori}</span><span class="text-danger fw-semibold">${formatRp(p.total)}</span></div>`).join(''):'<div class="lr-row text-muted small">Tidak ada pengeluaran</div>';
        document.getElementById('lapTotalPengeluaran').textContent=formatRp(data.total_pengeluaran);
        const box=document.getElementById('lapBoxLabaRugi');const label=document.getElementById('lapLabelLabaRugi');const nilai=document.getElementById('lapNilaiLabaRugi');
        box.className='lr-total '+(data.is_laba?'laba':'rugi');
        label.textContent=data.is_laba?'✅ LABA BERSIH':'❌ RUGI BERSIH';
        nilai.textContent=(data.is_laba?'+':'-')+formatRp(Math.abs(data.laba_rugi));
    }catch(e){document.getElementById('lapListPemasukan').innerHTML='<div class="text-danger small p-2">Gagal memuat data</div>';}
    document.getElementById('lapLoading').style.display='none';
    document.getElementById('lapPanelLR').style.display='block';
}

async function lapLoadCashFlow(){
    document.getElementById('lapLoading').style.display='block';
    document.getElementById('lapPanelCF').style.display='none';
    try{
        const res=await fetch(`/api/admin/laporan/cash-flow?tahun=${lapGetTahun()}`);
        const data=await res.json();
        if(!data.success)throw new Error('Gagal');
        document.getElementById('lapCFMasuk').textContent=formatRp(data.summary.total_masuk);
        document.getElementById('lapCFKeluar').textContent=formatRp(data.summary.total_keluar);
        const net=data.summary.net_total;const netEl=document.getElementById('lapCFNet');
        netEl.textContent=(net>=0?'+':'')+formatRp(net);netEl.className='fw-bold fs-5 '+(net>=0?'text-success':'text-danger');
        document.getElementById('lapCFBody').innerHTML=data.data.map(row=>`<tr><td><strong>${row.bulan}</strong></td><td class="text-end">${formatRp(row.saldo_awal)}</td><td class="text-end text-success">${formatRp(row.kas_masuk)}</td><td class="text-end text-danger">${formatRp(row.kas_keluar)}</td><td class="text-end fw-bold ${row.is_positif?'text-success':'text-danger'}">${(row.is_positif?'+':'')+formatRp(row.net_cashflow)}</td><td class="text-end">${formatRp(row.saldo_akhir)}</td></tr>`).join('');
        document.getElementById('lapCFFootMasuk').textContent=formatRp(data.summary.total_masuk);
        document.getElementById('lapCFFootKeluar').textContent=formatRp(data.summary.total_keluar);
        document.getElementById('lapCFFootNet').textContent=formatRp(data.summary.net_total);
        document.getElementById('lapCFFootSaldo').textContent=formatRp(data.summary.saldo_akhir);
    }catch(e){document.getElementById('lapCFBody').innerHTML='<tr><td colspan="6" class="text-danger text-center">Gagal memuat data</td></tr>';}
    document.getElementById('lapLoading').style.display='none';
    document.getElementById('lapPanelCF').style.display='block';
}

function lapExportExcel(){window.open(`/api/admin/laporan/export-excel?bulan=${lapGetBulan()}&tahun=${lapGetTahun()}&jenis=semua`,'_blank');}
function lapExportPdf(){window.open(`/admin/finance/laporan/pdf?bulan=${lapGetBulan()}&tahun=${lapGetTahun()}&jenis=${lapCurrentTab==='lr'?'laba_rugi':'cashflow'}`,'_blank');}

// ===== HELPERS =====
function formatRp(num){return 'Rp '+parseInt(num||0).toLocaleString('id-ID')}
function formatDate(str){if(!str)return '-';return new Date(str).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'})}
function showToast(msg,type){const el=document.createElement('div');el.className=`alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;el.style.zIndex=9999;el.style.minWidth='280px';el.textContent=msg;document.body.appendChild(el);setTimeout(()=>el.remove(),3500);}
</script>
@endsection