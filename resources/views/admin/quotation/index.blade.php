{{-- resources/views/admin/quotation/index.blade.php --}}
@extends('layouts.simple')
@section('title', 'Daftar Penawaran')
@section('content')
<style>
.quo-header {
    background: #090D16;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    color: white;
    padding: 32px 28px;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}
.stat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 22px; }
.stat-card { background: white; border-radius: 14px; padding: 18px 16px; box-shadow: 0 2px 10px rgba(0,0,0,.03); border: 1px solid #e2e8f0; border-top: 4px solid #0f172a; text-align: center; transition: all 0.2s ease; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0,0,0,0.06); }
.stat-card.draft    { border-top-color: #64748b; }
.stat-card.sent     { border-top-color: #06b6d4; }
.stat-card.approved { border-top-color: #10b981; }
.stat-card.rejected { border-top-color: #ef4444; }
.stat-card.converted{ border-top-color: #3b82f6; }
.stat-num { font-size: 1.8rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
.stat-lbl { font-size: .75rem; color: #64748b; margin-top: 2px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.table-card { background: white; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,.03); overflow: hidden; border: 1px solid #e2e8f0; }
.table-card table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.table-card thead th { background: #f8fafc; font-size: .72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; padding: 14px 18px; border-bottom: 1px solid #e2e8f0; }
.table-card tbody td { padding: 12px 18px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 13.5px; }
.table-card tbody tr:hover { background: #f8fafc; }
.badge-status { padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
.s-draft    { background: #f1f5f9; color: #475569; }
.s-sent     { background: #cffafe; color: #0e7490; }
.s-approved { background: #d1fae5; color: #065f46; }
.s-rejected { background: #fee2e2; color: #991b1b; }
.s-revised  { background: #fef3c7; color: #92400e; }
.s-expired  { background: #1f2937; color: #9ca3af; }
.s-converted{ background: #dbeafe; color: #1e40af; }
.btn-xs { padding: 4px 10px; font-size: .75rem; border-radius: 6px; border: 1px solid transparent; cursor: pointer; font-family: inherit; font-weight: 600; }
.toolbar { background: white; border-radius: 14px; padding: 16px 20px; box-shadow: 0 2px 8px rgba(0,0,0,.03); margin-bottom: 18px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; border: 1px solid #e2e8f0; }
.toolbar input, .toolbar select { border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: .875rem; outline: none; }
.toolbar input:focus, .toolbar select:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="quo-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge" style="background: rgba(220,38,38,0.2); color:#fca5a5; border:1px solid rgba(220,38,38,0.3); font-size:11px; font-weight:700;">
                        COMMERCIAL PROPOSALS
                    </span>
                </div>
                <h1 class="fs-3 fw-bold mb-1"><i class="bi bi-file-text me-2 text-danger"></i>Penawaran Harga (Quotation)</h1>
                <p class="mb-0 text-white-50 small">Kelola surat penawaran, status approval customer, dan konversi otomatis menjadi Sales Order.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/dashboard" class="btn btn-outline-light fw-bold px-3 py-2" style="border-radius:10px; font-size:13px; border-color:rgba(255,255,255,0.2);">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                @can('manage_quotation')
                <a href="{{ route('admin.quotation.create') }}" class="btn btn-danger fw-bold px-3 py-2 shadow-sm" style="border-radius:10px; font-size:13px;">
                    <i class="bi bi-plus-circle me-1"></i> Buat Penawaran Baru
                </a>
                @endcan
            </div>
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
                Belum ada penawaran. 
                @can('manage_quotation')
                <a href="{{ route('admin.quotation.create') }}">Buat sekarang</a>
                @endcan
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
                            {{-- Lihat (Semua yang punya akses View boleh melihat) --}}
                            <a href="{{ route('admin.quotation.show', $q->id) }}" class="btn btn-xs btn-outline-primary" title="Lihat Penawaran">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            {{-- Edit (Hanya yang punya izin Manage) --}}
                            @if(in_array($q->status, ['draft', 'revised']))
                                @can('manage_quotation')
                                <a href="{{ route('admin.quotation.edit', $q->id) }}" class="btn btn-xs btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                            @endif
                            
                            {{-- Kirim WA (Hanya yang punya izin Manage) --}}
                            @if(in_array($q->status, ['draft','sent','revised']) && $q->customer_phone)
                                @can('manage_quotation')
                                <button class="btn btn-xs btn-outline-success" onclick="kirimWA({{ $q->id }})" title="Kirim via WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </button>
                                @endcan
                            @endif
                            
                            {{-- Salin Link (Bebas untuk yang punya akses View) --}}
                            <button class="btn btn-xs btn-outline-secondary" onclick="salinLink('{{ $q->public_url }}')" title="Salin link penawaran">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                            
                            {{-- Konversi ke SO (Hanya yang punya izin Manage) --}}
                            @if($q->status === 'approved' && !$q->sales_order_id)
                                @can('manage_quotation')
                                <button class="btn btn-xs btn-outline-primary" onclick="konversiSO({{ $q->id }}, '{{ $q->quo_number }}')" title="Konversi ke Sales Order">
                                    <i class="bi bi-arrow-right-circle"></i> SO
                                </button>
                                @endcan
                            @endif
                            
                            {{-- PDF (Bebas untuk yang punya akses View) --}}
                            <a href="{{ route('admin.quotation.pdf', $q->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="Download PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            
                            {{-- Hapus (Hanya yang punya izin Manage) --}}
                            @if($q->status === 'draft')
                                @can('manage_quotation')
                                <button class="btn btn-xs btn-outline-danger" onclick="hapus({{ $q->id }}, '{{ $q->quo_number }}')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endcan
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