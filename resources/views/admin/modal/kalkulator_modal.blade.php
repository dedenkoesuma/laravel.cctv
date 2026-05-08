<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kalkulator Modal</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #f4f5f7; color: #1a1a2e; font-size: 14px; }

  .app { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

  /* Header */
  .header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
  .header-icon { width: 44px; height: 44px; background: #4f46e5; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .header-icon svg { width: 22px; height: 22px; fill: white; }
  .header h1 { font-size: 20px; font-weight: 700; color: #1a1a2e; }
  .header p { font-size: 13px; color: #6b7280; margin-top: 2px; }

  /* Stats */
  .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
  .stat { background: white; border-radius: 10px; padding: 16px 20px; border: 1px solid #e5e7eb; }
  .stat label { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; font-weight: 600; }
  .stat .val { font-size: 22px; font-weight: 700; margin-top: 4px; color: #1a1a2e; }
  .stat .val.red { color: #ef4444; }
  .stat .val.blue { color: #4f46e5; }
  .stat .val.green { color: #10b981; }

  /* Card */
  .card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 16px; }

  /* Table */
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #f9fafb; }
  th { padding: 11px 14px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .4px; color: #6b7280; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
  th.right { text-align: right; }
  td { padding: 8px 14px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafafa; }

  /* Inputs in table */
  .inp { border: 1px solid #e5e7eb; border-radius: 6px; padding: 6px 10px; font-size: 13px; width: 100%; outline: none; transition: border .15s; background: #fff; }
  .inp:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
  .inp.right { text-align: right; }
  .inp-sm { max-width: 80px; }
  td.right { text-align: right; }
  .muted { color: #9ca3af; }
  .num { font-variant-numeric: tabular-nums; }

  .row-num { color: #9ca3af; font-size: 12px; width: 28px; }
  .del-btn { background: none; border: none; cursor: pointer; color: #d1d5db; font-size: 16px; line-height: 1; padding: 4px 6px; border-radius: 4px; transition: color .15s, background .15s; }
  .del-btn:hover { color: #ef4444; background: #fef2f2; }

  /* Action buttons */
  .actions { display: flex; gap: 10px; padding: 16px 0 4px; }
  .btn { padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: all .15s; }
  .btn-primary { background: #4f46e5; color: white; }
  .btn-primary:hover { background: #4338ca; }
  .btn-ghost { background: white; color: #374151; border: 1px solid #d1d5db; }
  .btn-ghost:hover { background: #f3f4f6; }

  /* Summary */
  .summary { padding: 4px 0; }
  .summary-note { font-size: 13px; color: #9ca3af; padding: 12px 0; }
  .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
  .summary-row:last-child { border-bottom: none; }
  .summary-row.total { padding-top: 14px; font-weight: 700; font-size: 16px; }
  .summary-row .label { color: #374151; }
  .summary-row .amount { font-weight: 600; }
  .summary-row .amount.red { color: #ef4444; }
  .summary-row .amount.blue { color: #4f46e5; }
  .summary-row .amount.green { color: #10b981; }

  /* ── HARGA JUAL SECTION ── */
  .hargajual-section { margin-top: 0; }
  .section-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: #6b7280; padding: 14px 20px 10px;
    border-bottom: 1px solid #f3f4f6; background: #f9fafb;
    display: flex; align-items: center; gap: 8px;
  }
  .hargajual-body { padding: 16px 20px; }

  /* Harga Jual Table */
  .hj-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  .hj-table th {
    padding: 9px 12px; font-size: 11px; text-transform: uppercase;
    letter-spacing: .4px; color: #9ca3af; font-weight: 600;
    border-bottom: 1px solid #f3f4f6; text-align: left;
  }
  .hj-table th.right { text-align: right; }
  .hj-table td { padding: 8px 12px; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
  .hj-table tr:last-child td { border-bottom: none; }
  .hj-table tr:hover td { background: #fafafa; }

  .badge-profit { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
  .badge-profit.pos { background: #d1fae5; color: #065f46; }
  .badge-profit.neg { background: #fee2e2; color: #991b1b; }
  .badge-profit.zero { background: #f3f4f6; color: #6b7280; }

  .add-hj-btn { display: flex; align-items: center; gap: 6px; background: none; border: 1px dashed #d1d5db; border-radius: 8px; padding: 8px 14px; font-size: 13px; color: #6b7280; cursor: pointer; width: 100%; justify-content: center; transition: all .15s; margin-top: 12px; }
  .add-hj-btn:hover { border-color: #10b981; color: #10b981; background: #f0fdf4; }

  /* Profit Summary */
  .profit-summary-card {
    background: white; border-radius: 12px; border: 1px solid #e5e7eb;
    overflow: hidden; margin-bottom: 16px;
  }
  .profit-summary-body { padding: 16px 20px; }
  .profit-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
  .profit-row:last-child { border-bottom: none; }
  .profit-row.big { font-weight: 700; font-size: 17px; padding-top: 14px; }
  .profit-row .lbl { color: #374151; }
  .profit-row .amt { font-weight: 600; }
  .profit-row .amt.green { color: #10b981; }
  .profit-row .amt.red { color: #ef4444; }
  .profit-row .amt.blue { color: #4f46e5; }

  /* Grand Total */
  .grand-total-card {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 12px; padding: 20px 24px; color: white;
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 16px;
  }
  .grand-total-card .left .label { font-size: 13px; opacity: .8; }
  .grand-total-card .left .val { font-size: 28px; font-weight: 800; margin-top: 4px; }
  .grand-total-card .right { text-align: right; font-size: 13px; opacity: .85; line-height: 1.6; }
  .grand-total-card .right strong { opacity: 1; font-size: 15px; }

  /* Keuntungan Banner */
  .keuntungan-banner {
    border-radius: 12px; padding: 20px 24px; color: white;
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 16px; transition: background .3s;
  }
  .keuntungan-banner.profit { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
  .keuntungan-banner.loss   { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
  .keuntungan-banner.zero   { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
  .keuntungan-banner .left .label { font-size: 13px; opacity: .85; }
  .keuntungan-banner .left .val { font-size: 28px; font-weight: 800; margin-top: 4px; }
  .keuntungan-banner .right { text-align: right; font-size: 13px; opacity: .85; line-height: 1.7; }
  .keuntungan-banner .right strong { font-size: 15px; opacity: 1; }

  .divider { height: 1px; background: #f3f4f6; margin: 4px 0; }

  /* Margin badge next to name in hj table */
  .margin-pct { font-size: 11px; color: #6b7280; margin-left: 6px; }
</style>
</head>
<body>
<div class="app">

  <!-- Header -->
  <div class="header">
    <div class="header-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    </div>
    <div>
      <h1>Kalkulator Modal & Keuntungan</h1>
      <p>Hitung total modal, harga jual, dan keuntungan bersih per produk</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats">
    <div class="stat">
      <label>Total Produk</label>
      <div class="val" id="stat-produk">3</div>
    </div>
    <div class="stat">
      <label>Total Unit</label>
      <div class="val" id="stat-unit">0</div>
    </div>
    <div class="stat">
      <label>Total Modal</label>
      <div class="val blue" id="stat-modal">Rp 0</div>
    </div>
    <div class="stat">
      <label>Total Keuntungan</label>
      <div class="val green" id="stat-untung">Rp 0</div>
    </div>
  </div>

  <!-- Tabel Produk -->
  <div class="card">
    <table id="tbl">
      <thead>
        <tr>
          <th style="width:32px">#</th>
          <th>NAMA PRODUK</th>
          <th class="right" style="width:90px">QTY</th>
          <th class="right" style="width:140px">HARGA BELI</th>
          <th class="right" style="width:100px">DISKON %</th>
          <th class="right" style="width:120px">HEMAT</th>
          <th class="right" style="width:130px">TOTAL MODAL</th>
          <th style="width:36px"></th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>

  <div class="actions">
    <button class="btn btn-primary" onclick="tambahBaris()">+ Tambah Produk</button>
    <button class="btn btn-ghost" onclick="resetAll()">Reset</button>
  </div>

  <!-- Ringkasan Produk -->
  <div class="card" style="margin-top:16px">
    <div class="summary" id="summary-box" style="padding:16px 20px"></div>
  </div>

  <!-- Harga Jual Produk -->
  <div class="card hargajual-section">
    <div class="section-title">
      🏷️ Harga Jual Produk
    </div>
    <div class="hargajual-body">
      <table class="hj-table" id="hj-table">
        <thead>
          <tr>
            <th>NAMA PRODUK</th>
            <th class="right" style="width:160px">MODAL / UNIT</th>
            <th class="right" style="width:160px">HARGA JUAL</th>
            <th class="right" style="width:130px">KEUNTUNGAN</th>
            <th style="width:36px"></th>
          </tr>
        </thead>
        <tbody id="hj-tbody"></tbody>
      </table>
      <button class="add-hj-btn" onclick="tambahHargaJual()">
        <span style="font-size:18px;line-height:1">+</span> Tambah Produk Lain
      </button>
    </div>
  </div>

  <!-- Ringkasan Keuntungan -->
  <div class="profit-summary-card">
    <div class="section-title" style="background:#f9fafb">📊 Ringkasan Keuntungan</div>
    <div class="profit-summary-body" id="profit-summary-body">
      <p class="summary-note" style="color:#9ca3af;font-size:13px">Isi data produk dan harga jual untuk melihat keuntungan.</p>
    </div>
  </div>

  <!-- Grand Total Modal -->
  <div class="grand-total-card">
    <div class="left">
      <div class="label">Total Modal Keseluruhan</div>
      <div class="val" id="grand-total-val">Rp 0</div>
    </div>
    <div class="right" id="grand-total-detail">
      Modal produk keseluruhan
    </div>
  </div>

  <!-- Keuntungan Banner -->
  <div class="keuntungan-banner zero" id="keuntungan-banner">
    <div class="left">
      <div class="label">💰 Total Keuntungan Bersih</div>
      <div class="val" id="keuntungan-val">Rp 0</div>
    </div>
    <div class="right" id="keuntungan-detail">
      Total penjualan − total modal
    </div>
  </div>

</div>

<script>
  const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
  let rows = [];
  let hjList = []; // harga jual list: { id, nama, modalPerUnit, hargaJual, fromRowId? }
  let nextId = 1;
  let nextHjId = 1;

  // ── Baris Produk ────────────────────────────────────────────────
  function tambahBaris() {
    const id = nextId++;
    rows.push({ id, nama: '', qty: 0, harga: 0, diskon: 0 });
    renderTable();
  }

  function hapusBaris(id) {
    rows = rows.filter(r => r.id !== id);
    // Remove linked hj rows
    hjList = hjList.filter(h => h.fromRowId !== id);
    renderTable();
    renderHargaJual();
  }

  function updateBaris(id, field, val) {
    const r = rows.find(r => r.id === id);
    if (!r) return;
    if (field === 'nama') r.nama = val;
    else r[field] = parseFloat(val) || 0;
    syncHJFromRows();
    renderSummary();
  }

  function renderTable() {
    const tbody = document.getElementById('tbody');
    tbody.innerHTML = '';
    rows.forEach((r, i) => {
      const subtotal = r.qty * r.harga;
      const hemat    = subtotal * (r.diskon / 100);
      const total    = subtotal - hemat;

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="row-num">${i+1}</td>
        <td><input class="inp" placeholder="Nama produk..." value="${r.nama}"
          oninput="updateBaris(${r.id},'nama',this.value)"></td>
        <td><input class="inp right inp-sm" type="number" min="0" value="${r.qty||''}" placeholder="0"
          oninput="updateBaris(${r.id},'qty',this.value)"></td>
        <td><input class="inp right" type="number" min="0" value="${r.harga||''}" placeholder="0"
          oninput="updateBaris(${r.id},'harga',this.value)"></td>
        <td><input class="inp right inp-sm" type="number" min="0" max="100" value="${r.diskon||''}" placeholder="0"
          oninput="updateBaris(${r.id},'diskon',this.value)"></td>
        <td class="right num ${hemat>0?'red':'muted'}">${hemat>0 ? '- '+fmt(hemat) : '—'}</td>
        <td class="right num" style="font-weight:600">${total>0 ? fmt(total) : '—'}</td>
        <td><button class="del-btn" onclick="hapusBaris(${r.id})" title="Hapus">✕</button></td>
      `;
      tbody.appendChild(tr);
    });
    renderSummary();
  }

  // ── Sync HJ from rows (auto-populate) ───────────────────────────
  function syncHJFromRows() {
    rows.forEach(r => {
      if (!r.nama || r.qty <= 0 || r.harga <= 0) return;
      const modalPerUnit = r.harga * (1 - r.diskon/100);
      const existing = hjList.find(h => h.fromRowId === r.id);
      if (existing) {
        existing.nama = r.nama;
        existing.modalPerUnit = modalPerUnit;
      } else {
        hjList.push({ id: nextHjId++, fromRowId: r.id, nama: r.nama, modalPerUnit, hargaJual: 0 });
      }
    });
    renderHargaJual();
  }

  // ── Ringkasan ────────────────────────────────────────────────────
  function renderSummary() {
    const filled = rows.filter(r => r.nama && r.qty > 0 && r.harga > 0);
    const subtotalSebelum = filled.reduce((s,r) => s + r.qty*r.harga, 0);
    const totalHemat      = filled.reduce((s,r) => s + r.qty*r.harga*(r.diskon/100), 0);
    const totalModal      = subtotalSebelum - totalHemat;
    const totalUnit       = rows.reduce((s,r) => s + (r.qty||0), 0);

    document.getElementById('stat-produk').textContent = rows.length;
    document.getElementById('stat-unit').textContent   = totalUnit;
    document.getElementById('stat-modal').textContent  = fmt(totalModal);

    const box = document.getElementById('summary-box');
    if (filled.length === 0) {
      box.innerHTML = '<p class="summary-note">Belum ada produk yang diisi lengkap.</p>' + summaryBottom(subtotalSebelum, totalHemat, totalModal);
    } else {
      let html = '';
      filled.forEach(r => {
        const sub   = r.qty * r.harga;
        const hemat = sub * (r.diskon / 100);
        const tot   = sub - hemat;
        html += `<div class="summary-row">
          <span class="label">${r.nama} (${r.qty} × ${fmt(r.harga)}${r.diskon>0?' — diskon '+r.diskon+'%':''})</span>
          <span class="amount">${fmt(tot)}</span>
        </div>`;
      });
      box.innerHTML = html + summaryBottom(subtotalSebelum, totalHemat, totalModal);
    }

    document.getElementById('grand-total-val').textContent = fmt(totalModal);
    renderProfitSummary();
  }

  function summaryBottom(subtotal, hemat, modal) {
    return `
      <div class="divider" style="margin:8px 0"></div>
      <div class="summary-row">
        <span class="label">Subtotal Sebelum Diskon</span>
        <span class="amount">${fmt(subtotal)}</span>
      </div>
      <div class="summary-row">
        <span class="label">Total Hemat (Diskon)</span>
        <span class="amount red">${hemat>0?'- '+fmt(hemat):'- Rp 0'}</span>
      </div>
      <div class="summary-row total">
        <span class="label">Total Modal Produk</span>
        <span class="amount blue">${fmt(modal)}</span>
      </div>
    `;
  }

  // ── Harga Jual ───────────────────────────────────────────────────
  function tambahHargaJual() {
    hjList.push({ id: nextHjId++, fromRowId: null, nama: '', modalPerUnit: 0, hargaJual: 0 });
    renderHargaJual();
  }

  function hapusHJ(id) {
    hjList = hjList.filter(h => h.id !== id);
    renderHargaJual();
  }

  function updateHJ(id, field, val) {
    const h = hjList.find(h => h.id === id);
    if (!h) return;
    if (field === 'nama') h.nama = val;
    else if (field === 'modalPerUnit') h.modalPerUnit = parseFloat(val) || 0;
    else h.hargaJual = parseFloat(val) || 0;
    renderHargaJual();
  }

  function renderHargaJual() {
    const tbody = document.getElementById('hj-tbody');
    tbody.innerHTML = '';

    hjList.forEach(h => {
      const keuntungan = h.hargaJual - h.modalPerUnit;
      const pct = h.modalPerUnit > 0 ? ((keuntungan / h.modalPerUnit) * 100).toFixed(1) : 0;
      const badgeClass = keuntungan > 0 ? 'pos' : keuntungan < 0 ? 'neg' : 'zero';
      const badgeText = keuntungan > 0 ? `+${pct}%` : keuntungan < 0 ? `${pct}%` : '0%';

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <input class="inp" placeholder="Nama produk..." value="${h.nama}"
            oninput="updateHJ(${h.id},'nama',this.value)"
            ${h.fromRowId ? '' : ''}>
        </td>
        <td class="right">
          <input class="inp right" type="number" min="0" value="${h.modalPerUnit||''}" placeholder="0"
            oninput="updateHJ(${h.id},'modalPerUnit',this.value)"
            style="${h.fromRowId ? 'background:#f9fafb;color:#6b7280' : ''}">
        </td>
        <td>
          <input class="inp right" type="number" min="0" value="${h.hargaJual||''}" placeholder="Isi harga jual..."
            oninput="updateHJ(${h.id},'hargaJual',this.value)"
            style="border-color:${h.hargaJual>0?'#10b981':''}">
        </td>
        <td class="right">
          ${h.hargaJual > 0 || h.modalPerUnit > 0
            ? `<span class="badge-profit ${badgeClass}">${keuntungan >= 0 ? '+' : ''}${fmt(keuntungan)}</span>
               <span class="margin-pct">${badgeText}</span>`
            : '<span class="muted">—</span>'}
        </td>
        <td><button class="del-btn" onclick="hapusHJ(${h.id})" title="Hapus">✕</button></td>
      `;
      tbody.appendChild(tr);
    });

    renderProfitSummary();
  }

  // ── Profit Summary ───────────────────────────────────────────────
  function getTotalModal() {
    const filled = rows.filter(r => r.nama && r.qty > 0 && r.harga > 0);
    const subtotal = filled.reduce((s,r) => s + r.qty*r.harga, 0);
    const hemat    = filled.reduce((s,r) => s + r.qty*r.harga*(r.diskon/100), 0);
    return subtotal - hemat;
  }

  function renderProfitSummary() {
    const totalModal = getTotalModal();
    const hjFilled = hjList.filter(h => h.hargaJual > 0);
    const totalJual = hjFilled.reduce((s,h) => s + h.hargaJual, 0);
    const totalModalHJ = hjFilled.reduce((s,h) => s + h.modalPerUnit, 0);
    const totalUntung = totalJual - totalModalHJ;

    // Update stat
    const stat = document.getElementById('stat-untung');
    stat.textContent = fmt(totalUntung);
    stat.className = 'val ' + (totalUntung > 0 ? 'green' : totalUntung < 0 ? 'red' : 'blue');

    const body = document.getElementById('profit-summary-body');
    if (hjFilled.length === 0) {
      body.innerHTML = '<p class="summary-note" style="color:#9ca3af;font-size:13px">Isi harga jual produk di atas untuk melihat ringkasan keuntungan.</p>';
    } else {
      let html = '';
      hjFilled.forEach(h => {
        const k = h.hargaJual - h.modalPerUnit;
        const pct = h.modalPerUnit > 0 ? ((k/h.modalPerUnit)*100).toFixed(1) : 0;
        html += `<div class="profit-row">
          <span class="lbl">${h.nama || 'Produk tanpa nama'}</span>
          <span class="amt ${k>=0?'green':'red'}">${k>=0?'+':''}${fmt(k)} <small style="font-weight:400;opacity:.7">(${pct}%)</small></span>
        </div>`;
      });
      html += `
        <div class="divider" style="margin:8px 0"></div>
        <div class="profit-row">
          <span class="lbl">Total Modal (dari harga jual)</span>
          <span class="amt blue">${fmt(totalModalHJ)}</span>
        </div>
        <div class="profit-row">
          <span class="lbl">Total Harga Jual</span>
          <span class="amt">${fmt(totalJual)}</span>
        </div>
        <div class="profit-row big">
          <span class="lbl">Total Keuntungan Bersih</span>
          <span class="amt ${totalUntung>=0?'green':'red'}">${totalUntung>=0?'+':''}${fmt(totalUntung)}</span>
        </div>
      `;
      body.innerHTML = html;
    }

    // Keuntungan banner
    const banner = document.getElementById('keuntungan-banner');
    const bannerVal = document.getElementById('keuntungan-val');
    const bannerDetail = document.getElementById('keuntungan-detail');

    bannerVal.textContent = (totalUntung >= 0 ? '+' : '') + fmt(totalUntung);
    if (totalUntung > 0) {
      banner.className = 'keuntungan-banner profit';
      const margin = totalJual > 0 ? ((totalUntung/totalJual)*100).toFixed(1) : 0;
      bannerDetail.innerHTML = `Harga jual <strong>${fmt(totalJual)}</strong><br>− modal <strong>${fmt(totalModalHJ)}</strong> | margin <strong>${margin}%</strong>`;
    } else if (totalUntung < 0) {
      banner.className = 'keuntungan-banner loss';
      bannerDetail.innerHTML = `Harga jual <strong>${fmt(totalJual)}</strong><br>− modal <strong>${fmt(totalModalHJ)}</strong> | ⚠️ rugi!`;
    } else {
      banner.className = 'keuntungan-banner zero';
      bannerDetail.innerHTML = `Total penjualan − total modal`;
    }
  }

  function resetAll() {
    if (!confirm('Reset semua data?')) return;
    rows = []; hjList = []; nextId = 1; nextHjId = 1;
    tambahBaris(); tambahBaris(); tambahBaris();
    renderHargaJual();
  }

  // Init 3 baris kosong
  tambahBaris(); tambahBaris(); tambahBaris();
</script>
</body>
</html>