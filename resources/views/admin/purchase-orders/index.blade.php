{{-- resources/views/admin/purchase-orders/index.blade.php --}}
@extends('layouts.simple')
@section('title', 'Purchase Order - Admin')

@section('content')
<style>
.po-header{background:linear-gradient(135deg,#1e3a5f 0%,#2d6a9f 100%);color:white;padding:28px 24px;border-radius:14px;margin-bottom:24px}
.summary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.sum-card{background:white;border-radius:12px;padding:18px;box-shadow:0 2px 10px rgba(0,0,0,.07);border-left:4px solid #e5e7eb}
.sum-card.c1{border-left-color:#6b7280}.sum-card.c2{border-left-color:#3b82f6}
.sum-card.c3{border-left-color:#10b981}.sum-card.c4{border-left-color:#f59e0b}
.sum-val{font-size:1.6rem;font-weight:800;color:#111827;margin-bottom:3px}
.sum-lbl{font-size:.75rem;color:#6b7280;font-weight:600;text-transform:uppercase}
.toolbar{background:white;border-radius:12px;padding:14px 18px;box-shadow:0 2px 10px rgba(0,0,0,.07);margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.toolbar input,.toolbar select{border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:.875rem}
.toolbar input:focus,.toolbar select:focus{border-color:#2563eb;outline:none}
.table-card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);overflow:hidden}
.table-card table thead th{background:#f8fafc;font-size:.75rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;padding:12px 16px;border-bottom:2px solid #e5e7eb}
.table-card table tbody td{padding:12px 16px;font-size:.875rem;border-bottom:1px solid #f3f4f6;vertical-align:middle}
.table-card table tbody tr:hover{background:#f9fafb}
.status-badge{padding:4px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap}
.status-badge.draft{background:#f3f4f6;color:#374151}
.status-badge.sent{background:#dbeafe;color:#1d4ed8}
.status-badge.confirmed{background:#d1fae5;color:#065f46}
.status-badge.partial{background:#fef3c7;color:#92400e}
.status-badge.completed{background:#dcfce7;color:#14532d}
.status-badge.cancelled{background:#fee2e2;color:#991b1b}
.ppn-tag{display:inline-flex;align-items:center;padding:2px 7px;border-radius:8px;font-size:.68rem;font-weight:700}
.ppn-tag.yes{background:#dbeafe;color:#1d4ed8}
.ppn-tag.no{background:#f3f4f6;color:#6b7280}
.loading-overlay{text-align:center;padding:40px;color:#9ca3af}
@media(max-width:768px){.summary-grid{grid-template-columns:repeat(2,1fr)}.toolbar{flex-direction:column}}
</style>

<div class="container py-4">
    <div class="po-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📦 Purchase Order (PO)</h1>
                <p class="mb-0 opacity-75">Kelola pemesanan barang ke distributor & supplier</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.po.history') }}" class="btn btn-warning fw-bold text-dark">
                    <i class="bi bi-clock-history me-1"></i>History Tracking
                </a>
                <a href="{{ route('admin.po.create') }}" class="btn btn-light fw-bold">
                    <i class="bi bi-plus-circle me-1"></i>Buat PO Baru
                </a>
                <button class="btn btn-outline-light fw-bold" onclick="exportExcel()">
                    <i class="bi bi-download me-1"></i>Export
                </button>
            </div>
        </div>
    </div>

    {{-- FILTER & SUMMARY SAMA SEPERTI SEBELUMNYA --}}
    <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
        <select id="filterBulan" class="form-select" style="width:auto" onchange="loadAll()">
            @for($m=1;$m<=12;$m++)
                <option value="{{$m}}" {{$m==date('m')?'selected':''}}>{{DateTime::createFromFormat('!m',$m)->format('F')}}</option>
            @endfor
        </select>
        <select id="filterTahun" class="form-select" style="width:auto" onchange="loadAll()">
            @for($y=date('Y');$y>=date('Y')-3;$y--)
                <option value="{{$y}}" {{$y==date('Y')?'selected':''}}>{{$y}}</option>
            @endfor
        </select>
    </div>

    <div class="summary-grid">
        <div class="sum-card c1"><div class="sum-val" id="sumDraft">-</div><div class="sum-lbl">📝 Draft</div></div>
        <div class="sum-card c2"><div class="sum-val" id="sumSent">-</div><div class="sum-lbl">📤 Terkirim</div></div>
        <div class="sum-card c3"><div class="sum-val" id="sumConfirmed">-</div><div class="sum-lbl">✅ Disetujui</div></div>
        <div class="sum-card c4"><div class="sum-val" id="sumTotal">-</div><div class="sum-lbl">💰 Total Bulan Ini</div></div>
    </div>

    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="🔍 Cari No. PO / supplier..." style="flex:1;min-width:200px" oninput="debounceLoad()">
        <select id="filterStatus" onchange="loadList()">
            <option value="">Semua Status</option>
            <option value="draft">📝 Draft</option>
            <option value="sent">📤 Terkirim</option>
            <option value="confirmed">✅ Dikonfirmasi</option>
            <option value="partial">⏳ Sebagian</option>
            <option value="completed">🎉 Selesai</option>
            <option value="cancelled">❌ Batal</option>
        </select>
        <button class="btn btn-outline-secondary btn-sm" onclick="loadAll()"><i class="bi bi-arrow-clockwise"></i></button>
    </div>

    <div class="table-card">
        <div id="loadingPo" class="loading-overlay"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Memuat data...</p></div>
        <div id="tableContainer" style="display:none">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Tanggal</th>
                        <th>Supplier / Toko</th>
                        <th class="text-center">PPN</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="poTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SAMA SEPERTI SEBELUMNYA --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:14px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2)">
            <div class="modal-header" style="border-bottom:1px solid #e5e7eb;padding:16px 24px">
                <h5 class="modal-title fw-bold" id="detailTitle">Detail PO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="detailBody"></div>
        </div>
    </div>
</div>

<script>
// SCRIPT SAMA PERSIS DENGAN YANG SEBELUMNYA
let debounceTimer; const csrf = document.querySelector('meta[name="csrf-token"]')?.content||'';
document.addEventListener('DOMContentLoaded', loadAll);
function debounceLoad(){clearTimeout(debounceTimer);debounceTimer=setTimeout(loadList,400)}
function getBulan(){return document.getElementById('filterBulan').value}
function getTahun(){return document.getElementById('filterTahun').value}
async function loadAll(){loadSummary();loadList()}

async function loadSummary(){
    const res=await fetch(`/api/admin/purchase-orders?bulan=${getBulan()}&tahun=${getTahun()}`);
    const d=await res.json();
    if(!d.success)return;
    document.getElementById('sumDraft').textContent=d.summary.draft;
    document.getElementById('sumSent').textContent=d.summary.sent;
    document.getElementById('sumConfirmed').textContent=d.summary.confirmed;
    document.getElementById('sumTotal').textContent='Rp '+parseInt(d.summary.total_amount||0).toLocaleString('id-ID');
}

async function loadList(){
    document.getElementById('loadingPo').style.display='block';
    document.getElementById('tableContainer').style.display='none';
    const search=document.getElementById('searchInput').value;
    const status=document.getElementById('filterStatus').value;
    const res=await fetch(`/api/admin/purchase-orders?bulan=${getBulan()}&tahun=${getTahun()}&search=${encodeURIComponent(search)}&status=${status}`);
    const d=await res.json();
    if(d.success) renderTable(d.data);
    document.getElementById('loadingPo').style.display='none';
    document.getElementById('tableContainer').style.display='block';
}

function renderTable(list){
    const tbody=document.getElementById('poTableBody');
    if(!list.length){ tbody.innerHTML=`<tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada PO</td></tr>`; return; }
    const sMap={draft:'📝 Draft',sent:'📤 Terkirim',confirmed:'✅ Dikonfirmasi',partial:'⏳ Sebagian',completed:'🎉 Selesai',cancelled:'❌ Dibatalkan'};
    tbody.innerHTML=list.map(po=>`
    <tr>
        <td><span style="font-family:monospace;font-weight:700;color:#1e3a5f">${po.po_number}</span></td>
        <td style="color:#6b7280">${new Date(po.po_date).toLocaleDateString('id-ID')}</td>
        <td><div class="fw-semibold">${po.supplier_name}</div></td>
        <td class="text-center">${po.use_ppn?`<span class="ppn-tag yes">PPN ${po.ppn_percent}%</span>`:`<span class="ppn-tag no">Non PPN</span>`}</td>
        <td class="text-end fw-bold" style="color:#1e3a5f">Rp ${parseInt(po.total_amount).toLocaleString('id-ID')}</td>
        <td class="text-center"><span class="status-badge ${po.status}">${sMap[po.status]||po.status}</span></td>
        <td class="text-center">
            <div class="d-flex gap-1 justify-content-center flex-wrap">
                <button class="btn btn-xs btn-outline-primary py-0 px-2" onclick="lihatDetail(${po.id})"><i class="bi bi-eye"></i></button>
                ${po.status==='draft'?`<a href="/admin/purchase-orders/${po.id}/edit" class="btn btn-xs btn-outline-warning py-0 px-2"><i class="bi bi-pencil"></i></a>`:''}
                <a href="/admin/purchase-orders/${po.id}/pdf" target="_blank" class="btn btn-xs btn-outline-secondary py-0 px-2"><i class="bi bi-filetype-pdf"></i></a>
            </div>
        </td>
    </tr>`).join('');
}
function exportExcel() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('filterStatus').value;
    const bulan = getBulan();
    const tahun = getTahun();
    
    // Arahkan browser ke route export dengan query parameter
    const url = `/admin/purchase-orders/export?bulan=${bulan}&tahun=${tahun}&search=${encodeURIComponent(search)}&status=${status}`;
    window.open(url, '_blank');
}

async function lihatDetail(id){
    document.getElementById('detailBody').innerHTML=`<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>`;
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
    const res=await fetch(`/api/admin/purchase-orders/${id}`);
    const d=await res.json();
    if(!d.success) return;
    const po=d.po; const items=d.items; const logs=d.logs || [];
    document.getElementById('detailTitle').textContent=`Detail PO — ${po.po_number}`;
    
    let itemsHtml=items.map((item,i)=>`
        <tr>
            <td class="text-center text-muted">${i+1}</td>
            <td><div class="fw-semibold">${item.product_name}</div></td>
            <td class="text-center">${parseFloat(item.qty)}</td>
            <td class="text-center">${item.unit}</td>
            <td class="text-end">Rp ${parseInt(item.unit_price).toLocaleString('id-ID')}</td>
            ${parseFloat(item.discount_item)>0?`<td class="text-center text-danger">${parseFloat(item.discount_item)}%</td>`:'<td class="text-center text-muted">-</td>'}
            <td class="text-end fw-bold">Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</td>
        </tr>`).join('');

    document.getElementById('detailBody').innerHTML=`
    <div class="p-4">
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light"><tr><th width="5%">#</th><th>Produk</th><th width="10%">Qty</th><th width="10%">Satuan</th><th width="15%">Harga</th><th width="10%">Diskon</th><th width="15%">Subtotal</th></tr></thead>
                <tbody>${itemsHtml}</tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mb-4">
            <table style="width:300px">
                <tr><td class="text-muted small pb-1">Subtotal</td><td class="text-end fw-semibold pb-1">Rp ${parseInt(po.subtotal).toLocaleString('id-ID')}</td></tr>
                ${parseFloat(po.discount)>0?`<tr><td class="text-muted small pb-1">Diskon Keseluruhan</td><td class="text-end text-danger pb-1">${parseFloat(po.discount)}%</td></tr>`:''}
                ${po.use_ppn?`<tr><td class="text-muted small pb-1">PPN ${po.ppn_percent}%</td><td class="text-end fw-semibold pb-1">Rp ${parseInt(po.ppn_amount).toLocaleString('id-ID')}</td></tr>`:''}
                <tr style="border-top:2px solid #1e3a5f"><td class="fw-bold pt-2" style="color:#1e3a5f">TOTAL</td><td class="text-end fw-bold pt-2" style="font-size:1.1rem;color:#1e3a5f">Rp ${parseInt(po.total_amount).toLocaleString('id-ID')}</td></tr>
            </table>
        </div>
    </div>`;
}
</script>
@endsection