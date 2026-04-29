{{-- resources/views/admin/finance/laporan.blade.php --}}
@extends('layouts.simple')
@section('title', 'Laporan Keuangan')
@section('content')
<style>
.lap-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    color: white; padding: 28px 24px;
    border-radius: 14px; margin-bottom: 24px;
}
.tab-nav {
    display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap;
}
.tab-btn {
    padding: 10px 20px; border-radius: 10px; border: 2px solid #e5e7eb;
    background: white; font-weight: 700; font-size: 0.85rem;
    cursor: pointer; transition: all 0.2s; color: #374151;
}
.tab-btn.active {
    background: #1e3a5f; color: white; border-color: #1e3a5f;
}
.tab-btn:hover:not(.active) { border-color: #1e3a5f; color: #1e3a5f; }
.card {
    background: white; border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    padding: 24px; margin-bottom: 24px;
}
.card-title {
    font-size: 0.85rem; font-weight: 700; color: #374151;
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
/* ── LABA RUGI ── */
.lr-section { margin-bottom: 20px; }
.lr-section-title {
    font-size: 0.78rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.08em; padding: 8px 12px; border-radius: 6px;
    margin-bottom: 8px;
}
.lr-section-title.pemasukan  { background: #d1fae5; color: #065f46; }
.lr-section-title.pengeluaran{ background: #fee2e2; color: #991b1b; }
.lr-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem;
}
.lr-row:hover { background: #f9fafb; }
.lr-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px; border-radius: 8px; font-weight: 800;
    margin-top: 8px; font-size: 1rem;
}
.lr-total.pemasukan  { background: #d1fae5; color: #065f46; }
.lr-total.pengeluaran{ background: #fee2e2; color: #991b1b; }
.lr-total.laba       { background: #dbeafe; color: #1e40af; font-size: 1.1rem; }
.lr-total.rugi       { background: #fee2e2; color: #991b1b; font-size: 1.1rem; }
/* ── CASH FLOW ── */
.cf-table { width: 100%; border-collapse: collapse; }
.cf-table thead th {
    background: #0f172a; color: white;
    padding: 10px 12px; font-size: 0.78rem;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.cf-table tbody td {
    padding: 10px 12px; font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}
.cf-table tbody tr:hover { background: #f9fafb; }
.cf-table tfoot td {
    padding: 10px 12px; font-weight: 800;
    background: #f1f5f9; border-top: 2px solid #e5e7eb;
}
.positif { color: #059669; }
.negatif { color: #dc2626; }
/* ── EXPORT BAR ── */
.export-bar {
    background: #f8fafc; border: 1px solid #e5e7eb;
    border-radius: 10px; padding: 14px 18px;
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
    margin-bottom: 24px;
}
.export-bar select {
    border: 1px solid #d1d5db; border-radius: 8px;
    padding: 7px 12px; font-size: 0.85rem; background: white;
}
/* ── SUMMARY BOXES ── */
.summary-row {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 16px; margin-bottom: 24px;
}
.sum-box {
    background: white; border-radius: 12px; padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    border-top: 4px solid #e5e7eb; text-align: center;
}
.sum-box.masuk   { border-top-color: #10b981; }
.sum-box.keluar  { border-top-color: #ef4444; }
.sum-box.net     { border-top-color: #3b82f6; }
.sum-box-value   { font-size: 1.5rem; font-weight: 800; }
.sum-box-label   { font-size: 0.78rem; color: #6b7280; margin-top: 4px; }
/* ── PIUTANG BOX ── */
.piutang-box {
    background: #fffbeb; border: 1.5px solid #fcd34d;
    border-radius: 10px; padding: 16px;
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
}
@media (max-width: 768px) {
    .summary-row { grid-template-columns: 1fr; }
    .tab-nav { flex-direction: column; }
}
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="lap-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📊 Laporan Keuangan</h1>
                <p class="mb-0 opacity-75">Laba Rugi · Cash Flow · Export Excel & PDF</p>
            </div>
            <a href="/admin/finance" class="btn btn-outline-light fw-bold">
                ← Kembali ke Finance
            </a>
        </div>
    </div>

    {{-- FILTER PERIODE --}}
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
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
        <span class="badge bg-primary" id="periodeLabel"></span>
    </div>

    {{-- TAB NAVIGASI --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('laba-rugi', this)">📈 Laba Rugi</button>
        <button class="tab-btn" onclick="switchTab('cash-flow', this)">💧 Cash Flow</button>
        <button class="tab-btn" onclick="switchTab('export', this)">📥 Export</button>
    </div>

    {{-- ===== TAB: LABA RUGI ===== --}}
    <div id="tab-laba-rugi">
        <div class="card" id="loadingLR" style="text-align:center;padding:40px;color:#9ca3af;">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Memuat laporan laba rugi...</p>
        </div>
        <div id="kontenLR" style="display:none;">
            {{-- Piutang outstanding --}}
            <div class="piutang-box" id="piutangBox">
                <div>
                    <div class="fw-bold">🧾 Piutang Belum Lunas</div>
                    <div class="small text-muted">Belum termasuk dalam pemasukan</div>
                </div>
                <div class="fw-bold fs-5 text-warning" id="piutangOutstanding">-</div>
            </div>

            {{-- Pemasukan --}}
            <div class="card">
                <div class="card-title">💚 Pemasukan</div>
                <div class="lr-section">
                    <div class="lr-section-title pemasukan">Pemasukan Reguler</div>
                    <div id="listPemasukan"></div>
                </div>
                <div class="lr-section">
                    <div class="lr-section-title pemasukan">Piutang Lunas (Tagihan Terbayar)</div>
                    <div id="listPiutangLunas"></div>
                </div>
                <div class="lr-total pemasukan">
                    <span>TOTAL PEMASUKAN</span>
                    <span id="totalPemasukan">-</span>
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="card">
                <div class="card-title">❤️ Pengeluaran</div>
                <div class="lr-section">
                    <div class="lr-section-title pengeluaran">Rincian Pengeluaran</div>
                    <div id="listPengeluaran"></div>
                </div>
                <div class="lr-total pengeluaran">
                    <span>TOTAL PENGELUARAN</span>
                    <span id="totalPengeluaran">-</span>
                </div>
            </div>

            {{-- Laba/Rugi --}}
            <div class="lr-total laba" id="boxLabaRugi">
                <span id="labelLabaRugi">LABA / RUGI BERSIH</span>
                <span id="nilaiLabaRugi">-</span>
            </div>
        </div>
    </div>

    {{-- ===== TAB: CASH FLOW ===== --}}
    <div id="tab-cash-flow" style="display:none;">
        {{-- Summary --}}
        <div class="summary-row">
            <div class="sum-box masuk">
                <div class="sum-box-value text-success" id="cfTotalMasuk">-</div>
                <div class="sum-box-label">Total Kas Masuk</div>
            </div>
            <div class="sum-box keluar">
                <div class="sum-box-value text-danger" id="cfTotalKeluar">-</div>
                <div class="sum-box-label">Total Kas Keluar</div>
            </div>
            <div class="sum-box net">
                <div class="sum-box-value text-primary" id="cfNetTotal">-</div>
                <div class="sum-box-label">Net Cash Flow</div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="card">
            <div class="card-title"><i class="bi bi-bar-chart me-2"></i>Grafik Cash Flow Tahunan</div>
            <canvas id="chartCashFlow" height="80"></canvas>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-title">📋 Rincian Cash Flow Per Bulan</div>
            <div class="table-responsive">
                <table class="cf-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-end">Saldo Awal</th>
                            <th class="text-end">Kas Masuk</th>
                            <th class="text-end">Kas Keluar</th>
                            <th class="text-end">Net Cash Flow</th>
                            <th class="text-end">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody id="cfTableBody"></tbody>
                    <tfoot>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td class="text-end">-</td>
                            <td class="text-end text-success fw-bold" id="cfFootMasuk">-</td>
                            <td class="text-end text-danger fw-bold" id="cfFootKeluar">-</td>
                            <td class="text-end fw-bold" id="cfFootNet">-</td>
                            <td class="text-end fw-bold" id="cfFootSaldo">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== TAB: EXPORT ===== --}}
    <div id="tab-export" style="display:none;">
        <div class="card">
            <div class="card-title">📥 Export Laporan</div>

            <div class="row g-4">
                {{-- Export Excel --}}
                <div class="col-md-6">
                    <div style="border:2px solid #d1fae5;border-radius:12px;padding:24px;">
                        <div class="fw-bold fs-5 mb-1">📊 Export Excel (CSV)</div>
                        <p class="text-muted small mb-3">
                            File CSV yang bisa dibuka di Excel/Google Sheets.<br>
                            Berisi data transaksi, laba rugi, dan cash flow.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Laporan</label>
                            <select class="form-select" id="excelJenis">
                                <option value="semua">Semua (Transaksi + Laba Rugi + Cash Flow)</option>
                                <option value="transaksi">Daftar Transaksi</option>
                                <option value="laba_rugi">Laba Rugi</option>
                                <option value="cashflow">Cash Flow</option>
                            </select>
                        </div>
                        <button class="btn btn-success fw-bold w-100" onclick="exportExcel()">
                            <i class="bi bi-file-earmark-excel me-2"></i>Download Excel (CSV)
                        </button>
                    </div>
                </div>

                {{-- Export PDF --}}
                <div class="col-md-6">
                    <div style="border:2px solid #dbeafe;border-radius:12px;padding:24px;">
                        <div class="fw-bold fs-5 mb-1">🖨️ Export PDF</div>
                        <p class="text-muted small mb-3">
                            Buka halaman print-friendly, lalu gunakan<br>
                            Ctrl+P / Cmd+P untuk simpan sebagai PDF.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Laporan</label>
                            <select class="form-select" id="pdfJenis">
                                <option value="laba_rugi">Laba Rugi</option>
                                <option value="cashflow">Cash Flow</option>
                            </select>
                        </div>
                        <button class="btn btn-primary fw-bold w-100" onclick="exportPdf()">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Buka Halaman PDF
                        </button>
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        ℹ️ <strong>Tips:</strong> Export menggunakan filter bulan & tahun yang dipilih di atas.
                        Cash flow selalu menampilkan data 1 tahun penuh.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartCF;
let currentTab = 'laba-rugi';

function getBulan() { return document.getElementById('filterBulan').value; }
function getTahun() { return document.getElementById('filterTahun').value; }

document.addEventListener('DOMContentLoaded', () => {
    updatePeriodeLabel();
    loadAll();
});

function updatePeriodeLabel() {
    const bulanNames = ['','Januari','Februari','Maret','April','Mei','Juni',
                        'Juli','Agustus','September','Oktober','November','Desember'];
    document.getElementById('periodeLabel').textContent =
        bulanNames[getBulan()] + ' ' + getTahun();
}

function loadAll() {
    updatePeriodeLabel();
    if (currentTab === 'laba-rugi') loadLabaRugi();
    if (currentTab === 'cash-flow') loadCashFlow();
}

function switchTab(tab, btn) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-laba-rugi').style.display  = tab === 'laba-rugi'  ? 'block' : 'none';
    document.getElementById('tab-cash-flow').style.display  = tab === 'cash-flow'  ? 'block' : 'none';
    document.getElementById('tab-export').style.display     = tab === 'export'     ? 'block' : 'none';
    loadAll();
}

// ===== LOAD LABA RUGI =====
async function loadLabaRugi() {
    document.getElementById('loadingLR').style.display = 'block';
    document.getElementById('kontenLR').style.display  = 'none';

    const res  = await fetch(`/api/admin/laporan/laba-rugi?bulan=${getBulan()}&tahun=${getTahun()}`);
    const data = await res.json();
    if (!data.success) return;

    // Piutang outstanding
    document.getElementById('piutangOutstanding').textContent = formatRp(data.piutang_outstanding);

    // Pemasukan reguler
    document.getElementById('listPemasukan').innerHTML = data.pemasukan.length
        ? data.pemasukan.map(p => `
            <div class="lr-row">
                <span>${p.kategori}</span>
                <span class="text-success fw-semibold">${formatRp(p.total)}</span>
            </div>`).join('')
        : '<div class="lr-row text-muted">Tidak ada pemasukan</div>';

    // Piutang lunas
    document.getElementById('listPiutangLunas').innerHTML = data.piutang_lunas.length
        ? data.piutang_lunas.map(p => `
            <div class="lr-row">
                <span>${p.kategori}</span>
                <span class="text-success fw-semibold">${formatRp(p.total)}</span>
            </div>`).join('')
        : '<div class="lr-row text-muted">Tidak ada piutang lunas</div>';

    document.getElementById('totalPemasukan').textContent = formatRp(data.total_pemasukan);

    // Pengeluaran
    document.getElementById('listPengeluaran').innerHTML = data.pengeluaran.length
        ? data.pengeluaran.map(p => `
            <div class="lr-row">
                <span>${p.kategori}</span>
                <span class="text-danger fw-semibold">${formatRp(p.total)}</span>
            </div>`).join('')
        : '<div class="lr-row text-muted">Tidak ada pengeluaran</div>';

    document.getElementById('totalPengeluaran').textContent = formatRp(data.total_pengeluaran);

    // Laba Rugi
    const box   = document.getElementById('boxLabaRugi');
    const label = document.getElementById('labelLabaRugi');
    const nilai = document.getElementById('nilaiLabaRugi');
    box.className = 'lr-total ' + (data.is_laba ? 'laba' : 'rugi');
    label.textContent = data.is_laba ? '✅ LABA BERSIH' : '❌ RUGI BERSIH';
    nilai.textContent = (data.is_laba ? '+' : '-') + formatRp(Math.abs(data.laba_rugi));

    document.getElementById('loadingLR').style.display = 'none';
    document.getElementById('kontenLR').style.display  = 'block';
}

// ===== LOAD CASH FLOW =====
async function loadCashFlow() {
    const res  = await fetch(`/api/admin/laporan/cash-flow?tahun=${getTahun()}`);
    const data = await res.json();
    if (!data.success) return;

    // Summary
    document.getElementById('cfTotalMasuk').textContent  = formatRp(data.summary.total_masuk);
    document.getElementById('cfTotalKeluar').textContent = formatRp(data.summary.total_keluar);
    const net = data.summary.net_total;
    const netEl = document.getElementById('cfNetTotal');
    netEl.textContent = (net >= 0 ? '+' : '') + formatRp(net);
    netEl.className = 'sum-box-value ' + (net >= 0 ? 'text-success' : 'text-danger');

    // Tabel
    document.getElementById('cfTableBody').innerHTML = data.data.map(row => `
        <tr>
            <td><strong>${row.bulan}</strong></td>
            <td class="text-end">${formatRp(row.saldo_awal)}</td>
            <td class="text-end text-success">${formatRp(row.kas_masuk)}</td>
            <td class="text-end text-danger">${formatRp(row.kas_keluar)}</td>
            <td class="text-end fw-bold ${row.is_positif ? 'positif' : 'negatif'}">
                ${(row.is_positif ? '+' : '')}${formatRp(row.net_cashflow)}
            </td>
            <td class="text-end fw-semibold">${formatRp(row.saldo_akhir)}</td>
        </tr>
    `).join('');

    document.getElementById('cfFootMasuk').textContent  = formatRp(data.summary.total_masuk);
    document.getElementById('cfFootKeluar').textContent = formatRp(data.summary.total_keluar);
    document.getElementById('cfFootNet').textContent    = formatRp(data.summary.net_total);
    document.getElementById('cfFootSaldo').textContent  = formatRp(data.summary.saldo_akhir);

    // Chart
    if (chartCF) chartCF.destroy();
    chartCF = new Chart(document.getElementById('chartCashFlow').getContext('2d'), {
        type: 'bar',
        data: {
            labels: data.data.map(d => d.bulan),
            datasets: [
                {
                    label: 'Kas Masuk', data: data.data.map(d => d.kas_masuk),
                    backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 4,
                },
                {
                    label: 'Kas Keluar', data: data.data.map(d => d.kas_keluar),
                    backgroundColor: 'rgba(239,68,68,0.7)', borderRadius: 4,
                },
                {
                    label: 'Saldo Akhir', type: 'line',
                    data: data.data.map(d => d.saldo_akhir),
                    borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)',
                    tension: 0.4, fill: true, pointBackgroundColor: '#3b82f6',
                },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } }
            },
        },
    });
}

// ===== EXPORT =====
function exportExcel() {
    const jenis  = document.getElementById('excelJenis').value;
    const bulan  = getBulan();
    const tahun  = getTahun();
    window.open(`/api/admin/laporan/export-excel?bulan=${bulan}&tahun=${tahun}&jenis=${jenis}`, '_blank');
}

function exportPdf() {
    const jenis  = document.getElementById('pdfJenis').value;
    const bulan  = getBulan();
    const tahun  = getTahun();
    window.open(`/admin/laporan/pdf?bulan=${bulan}&tahun=${tahun}&jenis=${jenis}`, '_blank');
}

function formatRp(num) {
    return 'Rp ' + parseInt(num || 0).toLocaleString('id-ID');
}
</script>
@endsection