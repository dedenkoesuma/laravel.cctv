@extends('layouts.app')

@section('title', 'Kalkulator Modal & Keuntungan')

@push('styles')
<style>
.mp-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:12px}
.mp-logo{width:40px;height:40px;background:#1F4E79;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.mp-logo svg{color:#fff;width:22px;height:22px}
.mp-title h1{font-size:18px;font-weight:600;margin:0;line-height:1.2}
.mp-title p{font-size:13px;color:#6B7280;margin:0}

.stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:1.5rem}
@media(max-width:640px){.stat-grid{grid-template-columns:1fr}}
.stat-card{background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:16px}
.stat-card label{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#9CA3AF;display:block;margin-bottom:6px}
.stat-card .val{font-size:22px;font-weight:600;color:#1F4E79}

.mp-tabs{display:flex;gap:4px;margin-bottom:1.25rem;background:#F3F4F6;border-radius:10px;padding:4px;width:fit-content}
.mp-tab{padding:7px 16px;font-size:13px;border:none;background:none;border-radius:8px;cursor:pointer;color:#6B7280;font-weight:500}
.mp-tab.active{background:#fff;color:#111827;border:1px solid #E5E7EB;box-shadow:0 1px 3px rgba(0,0,0,.06)}

.pkg-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;margin-bottom:1.5rem}
.pkg-card{background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:1rem;cursor:pointer;transition:border-color .15s,transform .1s;position:relative;overflow:hidden}
.pkg-card:hover{border-color:#1F4E79;transform:translateY(-1px)}
.pkg-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:#1F4E79;border-radius:3px 3px 0 0;opacity:0;transition:opacity .15s}
.pkg-card:hover::before{opacity:1}
.pkg-icon{width:34px;height:34px;background:#EBF3FB;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:10px}
.pkg-name{font-size:13px;font-weight:500;margin-bottom:8px;line-height:1.4;color:#111}
.pkg-modal{font-size:19px;font-weight:600;color:#1F4E79;margin-bottom:3px}
.pkg-meta{font-size:11px;color:#9CA3AF}

.btn-primary{background:#1F4E79;color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px}
.btn-primary:hover{background:#163d61}
.btn-secondary{background:#F3F4F6;color:#374151;border:1px solid #E5E7EB;padding:7px 14px;border-radius:8px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:6px}
.btn-secondary:hover{background:#E5E7EB}
.btn-danger{background:none;border:none;color:#DC2626;padding:6px 10px;border-radius:6px;cursor:pointer;font-size:13px}
.btn-danger:hover{background:#FEE2E2}

.detail-wrap{background:#fff;border:1px solid #E5E7EB;border-radius:12px;overflow:hidden;margin-bottom:1rem}
.detail-head{padding:1rem 1.25rem;border-bottom:1px solid #F3F4F6;display:flex;align-items:center;gap:10px}
.detail-head input[type=text]{flex:1;font-size:15px;font-weight:600;border:none;outline:none;background:none;color:#111}

table.items-tbl{width:100%;border-collapse:collapse;font-size:13px}
table.items-tbl th{padding:8px 12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#9CA3AF;background:#F9FAFB;border-bottom:1px solid #F3F4F6;font-weight:500}
table.items-tbl td{padding:7px 12px;border-bottom:1px solid #F9FAFB;vertical-align:middle}
table.items-tbl tr:last-child td{border-bottom:none}
table.items-tbl input{width:100%;padding:5px 8px;border-radius:6px;border:1px solid transparent;background:transparent;font-size:13px;color:#111}
table.items-tbl input:hover{border-color:#D1D5DB;background:#F9FAFB}
table.items-tbl input:focus{outline:none;border-color:#1F4E79;background:#EBF3FB}
.text-right{text-align:right!important}
.input-right input{text-align:right}

.summary-section{padding:1rem 1.25rem;background:#F9FAFB;border-top:1px solid #F3F4F6}
.srow{display:flex;justify-content:space-between;font-size:13px;padding:4px 0;color:#374151}
.srow .lbl{color:#6B7280}
.srow.total-row{font-size:15px;font-weight:600;padding-top:10px;margin-top:4px;border-top:1px solid #E5E7EB;color:#111}
.srow.total-row .total-val{color:#1F4E79}

.detail-foot{padding:12px 1.25rem;border-top:1px solid #F3F4F6;display:flex;gap:8px;align-items:center;flex-wrap:wrap}

.new-form-wrap{background:#fff;border:1px solid #E5E7EB;border-radius:12px;overflow:hidden}
.new-form-head{padding:1rem 1.25rem;border-bottom:1px solid #F3F4F6}
.new-form-head input{width:100%;font-size:15px;font-weight:600;border:none;outline:none;background:none;color:#111}
.new-form-head input::placeholder{color:#9CA3AF;font-weight:400}
.new-form-subhead{padding:10px 1.25rem;background:#F9FAFB;border-bottom:1px solid #F3F4F6;display:flex;justify-content:space-between;align-items:center}

.diskon-pill{display:inline-block;font-size:10px;background:#EBF3FB;color:#1F4E79;border-radius:4px;padding:2px 5px;font-weight:500}
.empty-state{text-align:center;padding:3rem 1rem;color:#9CA3AF;grid-column:1/-1}
.empty-state svg{width:48px;height:48px;margin:0 auto 10px;display:block;opacity:.3}

.alert-saved{display:none;align-items:center;gap:6px;font-size:13px;color:#059669;background:#ECFDF5;padding:5px 12px;border-radius:6px}
</style>
@endpush

@section('content')
<div class="container-xl py-4">

{{-- TOP BAR --}}
<div class="mp-topbar">
  <div class="d-flex align-items-center gap-3">
    <div class="mp-logo">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
      </svg>
    </div>
    <div class="mp-title">
      <h1>Kalkulator Modal &amp; Keuntungan</h1>
      <p>Manajemen harga pokok paket produk</p>
    </div>
  </div>
  <button class="btn-primary" onclick="switchTab('buat')">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Buat Paket
  </button>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid">
  <div class="stat-card">
    <label>Total Modal Semua Paket</label>
    <div class="val" id="st-modal">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
  </div>
  <div class="stat-card">
    <label>Jumlah Paket</label>
    <div class="val" id="st-paket">{{ $jumlahPaket }} paket</div>
  </div>
  <div class="stat-card">
    <label>Rata-rata Modal / Paket</label>
    <div class="val" id="st-avg">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
  </div>
</div>

{{-- TABS --}}
<div class="mp-tabs">
  <button class="mp-tab active" id="tab-btn-daftar" onclick="switchTab('daftar')">Daftar Paket</button>
  <button class="mp-tab" id="tab-btn-buat" onclick="switchTab('buat')">+ Buat Paket Baru</button>
</div>

{{-- VIEW: DAFTAR --}}
<div id="view-daftar">
  <div class="pkg-grid" id="pkg-grid">
    @forelse($pakets as $paket)
    <div class="pkg-card" onclick="openDetail({{ $paket->id }})">
      <div class="pkg-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1F4E79" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
        </svg>
      </div>
      <div class="pkg-name">{{ $paket->nama }}</div>
      <div class="pkg-modal">Rp {{ number_format($paket->total_modal, 0, ',', '.') }}</div>
      <div class="pkg-meta">{{ $paket->items->count() }} komponen</div>
    </div>
    @empty
    <div class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
      </svg>
      <p>Belum ada paket. Klik <strong>Buat Paket Baru</strong> untuk memulai.</p>
    </div>
    @endforelse
  </div>
  <div id="detail-panel"></div>
</div>

{{-- VIEW: BUAT --}}
<div id="view-buat" style="display:none">
  <div class="new-form-wrap">
    <div class="new-form-head">
      <input type="text" id="new-nama" placeholder="Nama paket (contoh: Paket CCTV 4CH Dahua Audio)" />
    </div>
    <div class="new-form-subhead">
      <span style="font-size:13px;font-weight:500">Daftar Item / Komponen</span>
      <button class="btn-secondary" onclick="newAddItem()">+ Tambah Item</button>
    </div>
    <table class="items-tbl">
      <thead>
        <tr>
          <th style="width:28px">#</th>
          <th>Nama Item</th>
          <th style="width:60px" class="text-right">Qty</th>
          <th style="width:140px" class="text-right">Harga Beli</th>
          <th style="width:80px" class="text-right">Diskon %</th>
          <th style="width:140px" class="text-right">Total Modal</th>
          <th style="width:40px"></th>
        </tr>
      </thead>
      <tbody id="new-tbody"></tbody>
    </table>
    <div class="summary-section">
      <div class="srow"><span class="lbl">Subtotal sebelum diskon</span><span id="n-sub">Rp 0</span></div>
      <div class="srow"><span class="lbl" style="color:#DC2626">Total hemat (diskon)</span><span style="color:#DC2626" id="n-hemat">-Rp 0</span></div>
      <div class="srow total-row"><span>Total Modal</span><span class="total-val" id="n-total">Rp 0</span></div>
    </div>
    <div class="detail-foot">
      <button class="btn-primary" onclick="simpanBaru()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
        Simpan Paket
      </button>
      <div class="alert-saved" id="n-ok">&#10003; Tersimpan!</div>
    </div>
  </div>
</div>

</div>
@endsection

@push('scripts')
<script>
const ROUTE_BASE = '{{ route("modal-paket.index") }}';
const CSRF       = '{{ csrf_token() }}';

const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');

const calc = items => {
    let sub = 0, hmt = 0;
    items.forEach(it => {
        const g = (+it.qty || 0) * (+it.harga_beli || 0);
        sub += g;
        hmt += g * (+it.diskon || 0) / 100;
    });
    return { sub, hmt, total: sub - hmt };
};

// ─── TAB ─────────────────────────────────────────────────────────────────────
function switchTab(t) {
    ['daftar', 'buat'].forEach(x => {
        document.getElementById('view-' + x).style.display = x === t ? 'block' : 'none';
        document.getElementById('tab-btn-' + x).classList.toggle('active', x === t);
    });
    if (t === 'buat') { newItems = []; renderNewItems(); newRecalc(); }
}

// ─── DETAIL PANEL ─────────────────────────────────────────────────────────────
async function openDetail(id) {
    const res  = await fetch(ROUTE_BASE + '/' + id);
    const data = await res.json();
    const panel = document.getElementById('detail-panel');
    panel.style.display = 'block';
    panel.dataset.id    = id;
    renderDetailPanel(data);
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function renderDetailPanel(data) {
    const { sub, hmt, total } = calc(data.items);
    const panel = document.getElementById('detail-panel');

    const rows = data.items.map((it, i) => {
        const rowTotal = (+it.qty) * (+it.harga_beli) * (1 - (+it.diskon) / 100);
        const diskonPill = it.diskon > 0
            ? '<span class="diskon-pill">' + it.diskon + '%</span> '
            : '';
        return '<tr>'
            + '<td style="color:#9CA3AF;font-size:12px;text-align:center">' + (i + 1) + '</td>'
            + '<td><input type="text" value="' + escHtml(it.nama_item) + '" oninput="dUpd(' + i + ',\'nama_item\',this.value)" /></td>'
            + '<td class="input-right"><input type="number" value="' + it.qty + '" style="width:52px" oninput="dUpd(' + i + ',\'qty\',this.value)" /></td>'
            + '<td class="input-right"><input type="number" value="' + it.harga_beli + '" oninput="dUpd(' + i + ',\'harga_beli\',this.value)" /></td>'
            + '<td class="text-right">' + diskonPill + '<input type="number" value="' + it.diskon + '" style="width:46px" oninput="dUpd(' + i + ',\'diskon\',this.value)" /></td>'
            + '<td style="text-align:right;font-weight:600;color:#1F4E79;font-size:13px">' + fmt(rowTotal) + '</td>'
            + '<td><button class="btn-danger" onclick="dDelItem(' + i + ')">&#x2715;</button></td>'
            + '</tr>';
    }).join('');

    panel.innerHTML = ''
        + '<div class="detail-wrap">'
        +   '<div class="detail-head">'
        +     '<div class="pkg-icon" style="flex-shrink:0">'
        +       '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1F4E79" stroke-width="1.8">'
        +         '<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>'
        +         '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>'
        +       '</svg>'
        +     '</div>'
        +     '<input type="text" id="d-nama" value="' + escHtml(data.nama) + '" />'
        +     '<button class="btn-danger" onclick="deletePaket(' + data.id + ')">Hapus</button>'
        +   '</div>'
        +   '<table class="items-tbl">'
        +     '<thead><tr>'
        +       '<th style="width:28px">#</th>'
        +       '<th>Nama Item</th>'
        +       '<th style="width:60px" class="text-right">Qty</th>'
        +       '<th style="width:140px" class="text-right">Harga Beli</th>'
        +       '<th style="width:110px" class="text-right">Diskon %</th>'
        +       '<th style="width:140px" class="text-right">Total Modal</th>'
        +       '<th style="width:40px"></th>'
        +     '</tr></thead>'
        +     '<tbody id="d-tbody">' + rows + '</tbody>'
        +   '</table>'
        +   '<div style="padding:10px 1.25rem;border-top:1px solid #F3F4F6">'
        +     '<button class="btn-secondary" onclick="dAddItem()">+ Tambah Item</button>'
        +   '</div>'
        +   '<div class="summary-section">'
        +     '<div class="srow"><span class="lbl">Subtotal sebelum diskon</span><span id="d-sub">' + fmt(sub) + '</span></div>'
        +     '<div class="srow"><span class="lbl" style="color:#DC2626">Total hemat (diskon)</span><span style="color:#DC2626" id="d-hmt">-' + fmt(hmt) + '</span></div>'
        +     '<div class="srow total-row"><span>Total Modal</span><span class="total-val" id="d-total">' + fmt(total) + '</span></div>'
        +   '</div>'
        +   '<div class="detail-foot">'
        +     '<button class="btn-primary" onclick="saveDetail(' + data.id + ')">Simpan Perubahan</button>'
        +     '<div class="alert-saved" id="d-ok">&#10003; Tersimpan!</div>'
        +     '<button class="btn-secondary" style="margin-left:auto" onclick="closeDetail()">Tutup</button>'
        +   '</div>'
        + '</div>';

    window._detailItems = JSON.parse(JSON.stringify(data.items));
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function dUpd(i, f, v) { window._detailItems[i][f] = v; dRecalc(); }

function dRecalc() {
    const { sub, hmt, total } = calc(window._detailItems);
    document.getElementById('d-sub').textContent   = fmt(sub);
    document.getElementById('d-hmt').textContent   = '-' + fmt(hmt);
    document.getElementById('d-total').textContent = fmt(total);
}

function dAddItem() {
    window._detailItems.push({ nama_item: 'Item baru', qty: 1, harga_beli: 0, diskon: 0 });
    const id = document.getElementById('detail-panel').dataset.id;
    renderDetailPanel({ id: parseInt(id), nama: document.getElementById('d-nama').value, items: window._detailItems });
}

function dDelItem(i) {
    window._detailItems.splice(i, 1);
    const id = document.getElementById('detail-panel').dataset.id;
    renderDetailPanel({ id: parseInt(id), nama: document.getElementById('d-nama').value, items: window._detailItems });
}

async function saveDetail(id) {
    const payload = {
        nama:  document.getElementById('d-nama').value,
        items: window._detailItems,
    };
    const res  = await fetch(ROUTE_BASE + '/' + id, {
        method:  'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    JSON.stringify(payload),
    });
    const data = await res.json();
    if (data.success) {
        const b = document.getElementById('d-ok');
        b.style.display = 'inline-flex';
        setTimeout(() => { b.style.display = 'none'; }, 2000);
        refreshGrid();
    }
}

async function deletePaket(id) {
    if (!confirm('Hapus paket ini?')) return;
    await fetch(ROUTE_BASE + '/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });
    document.getElementById('detail-panel').innerHTML = '';
    document.getElementById('detail-panel').style.display = 'none';
    refreshGrid();
}

function closeDetail() {
    document.getElementById('detail-panel').innerHTML = '';
    document.getElementById('detail-panel').style.display = 'none';
}

// ─── REFRESH GRID ─────────────────────────────────────────────────────────────
async function refreshGrid() {
    const res  = await fetch(ROUTE_BASE, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    });
    const json = await res.json();

    // Update stat cards
    document.getElementById('st-modal').textContent = 'Rp ' + json.totalModal.toLocaleString('id-ID');
    document.getElementById('st-paket').textContent = json.jumlahPaket + ' paket';
    document.getElementById('st-avg').textContent   = 'Rp ' + json.rataRata.toLocaleString('id-ID');

    // Update pkg-grid
    const grid = document.getElementById('pkg-grid');
    if (json.pakets.length === 0) {
        grid.innerHTML = '<div class="empty-state">'
            + '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">'
            + '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>'
            + '</svg>'
            + '<p>Belum ada paket. Klik <strong>Buat Paket Baru</strong> untuk memulai.</p>'
            + '</div>';
        return;
    }

    grid.innerHTML = json.pakets.map(p =>
        '<div class="pkg-card" onclick="openDetail(' + p.id + ')">'
        + '<div class="pkg-icon">'
        +   '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1F4E79" stroke-width="1.8">'
        +     '<path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>'
        +     '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>'
        +   '</svg>'
        + '</div>'
        + '<div class="pkg-name">' + escHtml(p.nama) + '</div>'
        + '<div class="pkg-modal">Rp ' + p.total_modal.toLocaleString('id-ID') + '</div>'
        + '<div class="pkg-meta">' + p.item_count + ' komponen</div>'
        + '</div>'
    ).join('');
}

// ─── BUAT BARU ────────────────────────────────────────────────────────────────
let newItems = [];

function newAddItem() {
    newItems.push({ nama_item: '', qty: 1, harga_beli: 0, diskon: 0 });
    renderNewItems();
}

function newDelItem(i) {
    newItems.splice(i, 1);
    renderNewItems();
}

function newUpd(i, f, v) {
    newItems[i][f] = v;
    newRecalc();
}

function newRecalc() {
    const { sub, hmt, total } = calc(newItems);
    document.getElementById('n-sub').textContent   = fmt(sub);
    document.getElementById('n-hemat').textContent = '-' + fmt(hmt);
    document.getElementById('n-total').textContent = fmt(total);
}

function renderNewItems() {
    document.getElementById('new-tbody').innerHTML = newItems.map((it, i) =>
        '<tr>'
        + '<td style="color:#9CA3AF;font-size:12px;text-align:center">' + (i + 1) + '</td>'
        + '<td><input type="text" placeholder="nama item" value="' + escHtml(it.nama_item) + '" oninput="newUpd(' + i + ',\'nama_item\',this.value)" /></td>'
        + '<td class="input-right"><input type="number" value="' + it.qty + '" style="width:52px" oninput="newUpd(' + i + ',\'qty\',this.value)" /></td>'
        + '<td class="input-right"><input type="number" value="' + it.harga_beli + '" placeholder="0" oninput="newUpd(' + i + ',\'harga_beli\',this.value)" /></td>'
        + '<td class="input-right"><input type="number" value="' + it.diskon + '" style="width:52px" placeholder="0" oninput="newUpd(' + i + ',\'diskon\',this.value)" /></td>'
        + '<td style="text-align:right;color:#6B7280;font-size:13px">' + fmt((+it.qty || 0) * (+it.harga_beli || 0) * (1 - (+it.diskon || 0) / 100)) + '</td>'
        + '<td><button class="btn-danger" onclick="newDelItem(' + i + ')">&#x2715;</button></td>'
        + '</tr>'
    ).join('');
    newRecalc();
}

async function simpanBaru() {
    const nama = document.getElementById('new-nama').value.trim();
    if (!nama)            { alert('Nama paket wajib diisi'); return; }
    if (!newItems.length) { alert('Tambahkan minimal 1 item'); return; }

    const res  = await fetch(ROUTE_BASE, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    JSON.stringify({ nama: nama, items: newItems }),
    });
    const data = await res.json();

    if (data.success) {
        const b = document.getElementById('n-ok');
        b.style.display = 'inline-flex';
        setTimeout(() => {
            b.style.display = 'none';
            switchTab('daftar');
            refreshGrid();
        }, 1500);
        document.getElementById('new-nama').value = '';
        newItems = [];
        renderNewItems();
    }
}
</script>
@endpush