{{-- resources/views/admin/quotation/index.blade.php --}}
@extends('layouts.simple')
@section('title', 'Daftar Penawaran')
@section('content')
<style>
.quo-header{background:linear-gradient(135deg,#1e3a5f 0%,#2d6fba 100%);color:white;padding:22px 24px;border-radius:14px;margin-bottom:20px;}
.stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;}
.stat-card{background:white;border-radius:10px;padding:14px 16px;box-shadow:0 2px 8px rgba(0,0,0,.07);border-top:3px solid #e5e7eb;text-align:center;}
.stat-card.draft   {border-top-color:#94a3b8;}
.stat-card.sent    {border-top-color:#06b6d4;}
.stat-card.approved{border-top-color:#10b981;}
.stat-card.rejected{border-top-color:#ef4444;}
.stat-card.converted{border-top-color:#3b82f6;}
.stat-num{font-size:1.6rem;font-weight:800;color:#111;}
.stat-lbl{font-size:.72rem;color:#6b7280;margin-top:2px;}
.table-card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);overflow:hidden;}
.table-card table{width:100%;border-collapse:collapse;font-size:.82rem;}
.table-card thead th{background:#f8fafc;font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;padding:10px 14px;border-bottom:2px solid #e5e7eb;}
.table-card tbody td{padding:10px 14px;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.table-card tbody tr:hover{background:#f9fafb;}
.badge-status{padding:3px 10px;border-radius:20px;font-size:.7rem;font-weight:700;white-space:nowrap;}
.s-draft    {background:#f1f5f9;color:#475569;}
.s-sent     {background:#cffafe;color:#0e7490;}
.s-approved {background:#d1fae5;color:#065f46;}
.s-rejected {background:#fee2e2;color:#991b1b;}
.s-revised  {background:#fef3c7;color:#92400e;}
.s-expired  {background:#1f2937;color:#9ca3af;}
.s-converted{background:#dbeafe;color:#1e40af;}
.btn-xs{padding:3px 8px;font-size:.72rem;border-radius:6px;border:1px solid transparent;cursor:pointer;font-family:inherit;}
.toolbar{background:white;border-radius:12px;padding:12px 16px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="quo-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📋 Penawaran (Quotation)</h1>
                <p class="mb-0 opacity-75">Kelola surat penawaran ke customer</p>
            </div>
            <a href="{{ route('admin.quotation.create') }}" class="btn btn-light fw-bold">
                <i class="bi bi-plus-circle me-1"></i>Buat Penawaran Baru
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        @php
            $stats = $quotations->groupBy('status');
            $statMap = ['draft'=>'Draft','sent'=>'Terkirim','approved'=>'Disetujui','rejected'=>'Ditolak','converted'=>'Jadi SO'];
        @endphp
        @foreach($statMap as $key => $label)
        <div class="stat-card {{ $key }}">
            <div class="stat-num">{{ $stats->get($key)?->count() ?? 0 }}</div>
            <div class="stat-lbl">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari nama / nomor penawaran..." style="flex:1;min-width:200px;" oninput="filterTable()">
        <select id="filterStatus" class="form-select" style="width:auto;" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="sent">Terkirim</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
            <option value="revised">Minta Revisi</option>
            <option value="converted">Jadi SO</option>
            <option value="expired">Expired</option>
        </select>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        @if($quotations->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                Belum ada penawaran. <a href="{{ route('admin.quotation.create') }}">Buat sekarang</a>
            </div>
        @else
        <table id="quoTable">
            <thead>
                <tr>
                    <th>No. Penawaran</th>
                    <th>Customer</th>
                    <th>Proyek</th>
                    <th>Tanggal</th>
                    <th>Berlaku Hingga</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotations as $q)
                <tr data-status="{{ $q->status }}" data-search="{{ strtolower($q->quo_number . ' ' . $q->customer_name . ' ' . $q->project_name) }}">
                    <td>
                        <span class="fw-bold" style="font-family:monospace;font-size:.8rem;">{{ $q->quo_number }}</span>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $q->customer_name }}</div>
                        @if($q->customer_phone)
                            <small class="text-muted">{{ $q->customer_phone }}</small>
                        @endif
                    </td>
                    <td>{{ $q->project_name ?? '-' }}</td>
                    <td>{{ $q->quo_date->format('d M Y') }}</td>
                    <td>
                        {{ $q->valid_until->format('d M Y') }}
                        @if($q->is_expired)
                            <span class="badge bg-danger ms-1" style="font-size:.6rem;">Expired</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($q->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge-status s-{{ $q->status }}">
                            {{ $q->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                            {{-- Lihat --}}
                            <a href="{{ route('admin.quotation.show', $q->id) }}" class="btn btn-xs btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Edit (hanya jika draft) --}}
                            @if(in_array($q->status, ['draft', 'revised']))
                            <a href="{{ route('admin.quotation.edit', $q->id) }}" class="btn btn-xs btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            {{-- Kirim WA --}}
                            @if(in_array($q->status, ['draft','sent','revised']) && $q->customer_phone)
                            <button class="btn btn-xs btn-outline-success" onclick="kirimWA({{ $q->id }})" title="Kirim via WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </button>
                            @endif
                            {{-- Salin Link --}}
                            <button class="btn btn-xs btn-outline-secondary" onclick="salinLink('{{ $q->public_url }}')" title="Salin link penawaran">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                            {{-- Konversi ke SO --}}
                            @if($q->status === 'approved' && !$q->sales_order_id)
                            <button class="btn btn-xs btn-outline-primary" onclick="konversiSO({{ $q->id }}, '{{ $q->quo_number }}')" title="Konversi ke Sales Order">
                                <i class="bi bi-arrow-right-circle"></i> SO
                            </button>
                            @endif
                            {{-- PDF --}}
                            <a href="{{ route('admin.quotation.pdf', $q->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="Download PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            {{-- Hapus --}}
                            @if($q->status === 'draft')
                            <button class="btn btn-xs btn-outline-danger" onclick="hapus({{ $q->id }}, '{{ $q->quo_number }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    document.querySelectorAll('#quoTable tbody tr').forEach(tr => {
        const matchSearch = !search || tr.dataset.search.includes(search);
        const matchStatus = !status || tr.dataset.status === status;
        tr.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}

async function kirimWA(id) {
    const res  = await fetch(`/admin/quotation/${id}/send`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.success) {
        window.open(data.whatsapp_url, '_blank');
        toast('✅ ' + data.message, 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function salinLink(url) {
    navigator.clipboard.writeText(url).then(() => toast('🔗 Link berhasil disalin!', 'success'));
}

async function konversiSO(id, kode) {
    if (!confirm(`Konversi penawaran ${kode} menjadi Sales Order?`)) return;
    const res  = await fetch(`/admin/quotation/${id}/convert-so`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    });
    const data = await res.json();
    toast((data.success ? '✅ ' : '❌ ') + data.message, data.success ? 'success' : 'danger');
    if (data.success) setTimeout(() => location.reload(), 1500);
}

async function hapus(id, kode) {
    if (!confirm(`Hapus penawaran ${kode}?`)) return;
    const res  = await fetch(`/admin/quotation/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.success) { toast('✅ ' + data.message, 'success'); setTimeout(() => location.reload(), 1000); }
}

function toast(msg, type) {
    const el = document.createElement('div');
    el.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    el.style.zIndex = 9999;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}
</script>
@endsection