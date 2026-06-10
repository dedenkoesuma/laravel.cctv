<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kalkulator Modal & Keuntungan</title>
<style>
  #paket-select option {
  color: #1a1a2e;
  background: #fff;
  font-weight: 600;
}
#paket-select option:first-child {
  color: #9ca3af;
  font-weight: 400;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f4f5f7;color:#1a1a2e;font-size:14px}
.app{max-width:860px;margin:0 auto;padding:20px 16px}
.hdr{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.hdr-icon{width:40px;height:40px;background:#4f46e5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.hdr h1{font-size:18px;font-weight:700;color:#1a1a2e}
.hdr p{font-size:12px;color:#6b7280;margin-top:2px}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px}
.stat{background:white;border-radius:12px;padding:14px 18px;border:1px solid #e5e7eb}
.stat label{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;font-weight:600}
.stat .val{font-size:22px;font-weight:700;margin-top:4px}
.val.blue{color:#4f46e5}.val.green{color:#10b981}.val.red{color:#ef4444}.val.neutral{color:#1a1a2e}
.card{background:white;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:14px}
table{width:100%;border-collapse:collapse;table-layout:fixed}
thead tr{background:#f9fafb}
th{padding:10px 12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.4px;color:#6b7280;font-weight:600;border-bottom:1px solid #e5e7eb}
th.r{text-align:right}
td{padding:7px 12px;border-bottom:1px solid #f3f4f6;vertical-align:middle}
tr:last-child td{border-bottom:none}
.inp{border:1px solid #e5e7eb;border-radius:6px;padding:6px 9px;font-size:13px;width:100%;outline:none;background:#fff;color:#1a1a2e;transition:border .15s}
.inp:focus{border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.12)}
.inp.r{text-align:right}
.rnum{color:#9ca3af;font-size:12px}
.del-btn{background:none;border:none;cursor:pointer;color:#d1d5db;font-size:15px;padding:3px 6px;border-radius:4px;transition:color .15s}
.del-btn:hover{color:#ef4444;background:#fef2f2}
.actions{display:flex;gap:10px;padding:12px 0 4px}
.btn{padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s}
.btn-p{background:#4f46e5;color:white}.btn-p:hover{background:#4338ca}
.btn-g{background:white;color:#374151;border:1px solid #d1d5db}.btn-g:hover{background:#f3f4f6}
.sum-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:14px}
.sum-row:last-child{border-bottom:none}
.sum-row.tot{font-weight:700;font-size:15px;padding-top:12px}
.amount.red{color:#ef4444}.amount.blue{color:#4f46e5}
.sec-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;padding:12px 16px 10px;border-bottom:1px solid #f3f4f6;background:#f9fafb}
.hj-body{padding:20px 20px}
.hj-label{font-size:13px;color:#6b7280;margin-bottom:8px}
.hj-input-wrap{display:flex;align-items:center;gap:10px}
.hj-prefix{font-size:14px;font-weight:600;color:#6b7280}
.hj-inp{border:1.5px solid #e5e7eb;border-radius:8px;padding:10px 14px;font-size:20px;font-weight:700;width:100%;outline:none;background:#fff;color:#1a1a2e;text-align:right;transition:border .15s}
.hj-inp:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
.hj-inp.filled{border-color:#10b981}
.kbanner{border-radius:12px;padding:20px 24px;color:white;display:flex;justify-content:space-between;align-items:center;margin-top:14px;transition:background .3s}
.kbanner.profit{background:#10b981}.kbanner.loss{background:#ef4444}.kbanner.zero{background:#6b7280}
.kbanner .lval{font-size:28px;font-weight:800;margin-top:3px}
.kbanner .rsub{font-size:13px;opacity:.85;line-height:1.8;text-align:right}
.note{font-size:13px;color:#9ca3af;padding:6px 0}

/* ── MARKETPLACE ── */
.mp-wrap{padding:16px 20px;border-top:1px solid #f3f4f6}
.mp-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.mp-header-left{display:flex;align-items:center;gap:10px}
.mp-ico{font-size:22px;line-height:1}
.mp-title{font-size:14px;font-weight:700;color:#1a1a2e}
.mp-subtitle{font-size:11px;color:#9ca3af;margin-top:1px}

/* Toggle switch */
.sw{position:relative;display:inline-block;width:46px;height:25px;flex-shrink:0}
.sw input{opacity:0;width:0;height:0}
.sw-track{position:absolute;inset:0;background:#d1d5db;border-radius:25px;cursor:pointer;transition:background .25s}
.sw-track::before{content:'';position:absolute;width:19px;height:19px;left:3px;top:3px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:transform .25s}
.sw input:checked + .sw-track{background:#10b981}
.sw input:checked + .sw-track::before{transform:translateX(21px)}

.mp-body{background:#f9fafb;border-radius:10px;padding:14px 16px;transition:opacity .2s}
.mp-body.off{opacity:.35;pointer-events:none}

.mp-presets{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
.mp-preset{border:1.5px solid #e5e7eb;border-radius:20px;padding:5px 14px;font-size:12px;font-weight:600;cursor:pointer;background:white;color:#6b7280;transition:all .15s}
.mp-preset:hover{border-color:#4f46e5;color:#4f46e5}
.mp-preset.active{background:#4f46e5;border-color:#4f46e5;color:white}

.mp-input-row{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.mp-input-row label{font-size:13px;color:#6b7280;white-space:nowrap}
.mp-pct-inp{width:88px;border:1.5px solid #e5e7eb;border-radius:8px;padding:7px 10px;font-size:16px;font-weight:700;text-align:center;outline:none;color:#1a1a2e;transition:border .15s}
.mp-pct-inp:focus{border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.12)}
.mp-pct-sym{font-size:16px;font-weight:700;color:#6b7280}
.mp-plat-hint{font-size:11px;color:#9ca3af;margin-left:2px}

.mp-result{display:flex;justify-content:space-between;align-items:center;background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:10px 14px}
.mp-result-lbl{font-size:12px;font-weight:600;color:#e11d48}
.mp-result-val{font-size:16px;font-weight:800;color:#e11d48}
</style>
</head>
<body>

<div class="app">
  <div class="hdr">
    <div class="hdr-icon">
      <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    </div>
    <div>
      <h1>Kalkulator Modal & Keuntungan</h1>
      <p>Hitung total modal produk, lalu bandingkan dengan total harga jual</p>
    </div>
  </div>

  <div class="stats">
    <div class="stat"><label>Total Modal</label><div class="val blue" id="s-modal">Rp 0</div></div>
    <div class="stat"><label>Total Harga Jual</label><div class="val neutral" id="s-jual">Rp 0</div></div>
    <div class="stat"><label>Keuntungan Bersih</label><div class="val green" id="s-untung">Rp 0</div></div>
  </div>

  <div class="card">
    <table>
      <thead><tr>
        <th style="width:30px">#</th>
        <th>NAMA PRODUK</th>
        <th class="r" style="width:80px">QTY</th>
        <th class="r" style="width:140px">HARGA BELI</th>
        <th class="r" style="width:88px">DISKON %</th>
        <th class="r" style="width:110px">HEMAT</th>
        <th class="r" style="width:130px">TOTAL MODAL</th>
        <th style="width:32px"></th>
      </tr></thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>

  <div class="actions">
    <button type="button" class="btn btn-p" onclick="addRow()">+ Tambah Produk</button>
    <button type="button" class="btn btn-g" onclick="resetAll()">Reset</button>
  </div>

  <div class="card" style="margin-top:14px">
    <div id="summary-box" style="padding:14px 18px"></div>
  </div>

 <div class="card">
  <div class="sec-title" style="background:#eef2ff;color:#4f46e5;border-bottom:1px solid #e0e7ff;">
    📦 Pilih Paket Modal (Database)
  </div>
  <div style="padding:16px 20px;">
    <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">
      Pilih paket modal dari database untuk dibandingkan dengan harga jual
    </p>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
      <select id="paket-select"
        style="flex:1;min-width:200px;border:1.5px solid #e5e7eb;border-radius:8px;padding:9px 12px;font-size:14px;font-weight:600;color:#1a1a2e;outline:none;background:#fff;cursor:pointer;transition:border .15s;"
        onchange="onPaketChange(this.value)"
        onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e5e7eb'">
        <option value="" style="color:#9ca3af;font-weight:400;">— Pilih paket modal —</option>
      </select>
      <div id="paket-modal-val"
        style="font-size:22px;font-weight:800;color:#4f46e5;min-width:140px;text-align:right;">
        Rp —
      </div>
    </div>
    <p id="paket-hint" style="font-size:12px;color:#9ca3af;margin-top:8px;"></p>
  </div>
</div>
  <div class="card">
    <div class="sec-title">🏷️ Total Harga Jual</div>
    <div class="hj-body">
      <p class="hj-label">Masukkan total keseluruhan harga jual (semua produk digabung)</p>
      <div class="hj-input-wrap">
        <span class="hj-prefix">Rp</span>
        <input class="hj-inp" id="hj-inp" type="number" min="0" placeholder="0" oninput="updateHJ(this)">
      </div>
    </div>

    <div class="mp-wrap">
      <div class="mp-header">
        <div class="mp-header-left">
          <span class="mp-ico">🛒</span>
          <div>
            <div class="mp-title">Potongan Marketplace</div>
            <div class="mp-subtitle">Fee platform / komisi penjualan</div>
          </div>
        </div>
        <label class="sw">
          <input type="checkbox" id="mp-toggle" onchange="toggleMP()">
          <span class="sw-track"></span>
        </label>
      </div>

      <div class="mp-body off" id="mp-body">
        <div class="mp-presets">
          <button class="mp-preset" onclick="setPreset(this,'Tokopedia',2.5)">🟢 Tokopedia <small style="opacity:.7">2.5%</small></button>
          <button class="mp-preset" onclick="setPreset(this,'Shopee',3)">🟠 Shopee <small style="opacity:.7">3%</small></button>
          <button class="mp-preset" onclick="setPreset(this,'Lazada',2)">🔵 Lazada <small style="opacity:.7">2%</small></button>
          <button class="mp-preset" onclick="setPreset(this,'TikTok Shop',5)">⚫ TikTok Shop <small style="opacity:.7">5%</small></button>
          <button class="mp-preset" onclick="setPreset(this,'Blibli',3)">🔴 Blibli <small style="opacity:.7">3%</small></button>
          <button class="mp-preset" id="btn-custom" onclick="setPreset(this,'Custom',null)">✏️ Custom</button>
        </div>

        <div class="mp-input-row">
          <label>Fee marketplace:</label>
          <input class="mp-pct-inp" id="mp-pct" type="number" min="0" max="100" step="0.1" value="0" oninput="onMpInput()">
          <span class="mp-pct-sym">%</span>
          <span class="mp-plat-hint" id="mp-plat-hint"></span>
        </div>

        <div class="mp-result">
          <span class="mp-result-lbl">💸 Nominal Potongan Marketplace</span>
          <span class="mp-result-val" id="mp-amount">Rp 0</span>
        </div>
      </div>
    </div>
  </div>

  <div class="kbanner zero" id="kbanner">
    <div>
      <div style="font-size:13px;opacity:.85">💰 Keuntungan Bersih</div>
      <div class="lval" id="k-val">Rp 0</div>
    </div>
    <div class="rsub" id="k-detail">Isi total harga jual untuk melihat hasil</div>
  </div>
</div>

<script>
const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
let rows = [];
let nextId = 1;
let totalHargaJual = 0;
let mpEnabled = false;
let mpPct = 0;
let mpPlatform = '';

let selectedPaketModal = 0;

async function fetchPaketList() {
  const sel = document.getElementById('paket-select');
  try {
    const res  = await fetch('/api/modal-paket/list');
    const data = await res.json();
    if (data.success && data.pakets.length > 0) {
      data.pakets.forEach(p => {
        const opt = document.createElement('option');
        opt.value       = p.id;
        opt.textContent = p.nama;
         opt.style.color = '#1a1a2e';   
        sel.appendChild(opt);
      });
    } else {
      const opt = document.createElement('option');
      opt.value       = '';
      opt.textContent = 'Belum ada paket tersimpan';
      opt.disabled    = true;
      sel.appendChild(opt);
    }
  } catch (e) {
    console.error('Gagal load paket:', e);
  }
}

async function onPaketChange(id) {
  const valEl  = document.getElementById('paket-modal-val');
  const hint   = document.getElementById('paket-hint');
  const sel    = document.getElementById('paket-select');

  if (!id) {
    selectedPaketModal = 0;
    valEl.textContent  = 'Rp —';
    hint.textContent   = '';
    renderBanner();
    return;
  }

  valEl.textContent = 'Memuat...';
  hint.textContent  = '';

  try {
    const res  = await fetch(`/api/modal-paket/${id}/total`);
    const data = await res.json();
    if (data.success) {
      selectedPaketModal   = data.total_modal;
      valEl.textContent    = fmt(data.total_modal);
      hint.textContent     = `Paket: ${data.nama_paket}`;
      hint.style.color     = '#10b981';
    } else {
      selectedPaketModal = 0;
      valEl.textContent  = 'Error';
      hint.textContent   = 'Paket tidak ditemukan';
      hint.style.color   = '#ef4444';
    }
  } catch (e) {
    selectedPaketModal = 0;
    valEl.textContent  = 'Error Server';
    hint.style.color   = '#ef4444';
  }
  renderBanner();
}

/* ── ROWS ── */
function addRow() {
  rows.push({id: nextId++, nama:'', qty:0, harga:0, diskon:0});
  renderAll();
}

function delRow(id) {
  rows = rows.filter(r => r.id !== id);
  renderAll();
}

function update(id, field, val) {
  const r = rows.find(r => r.id === id);
  if (!r) return;
  if (field === 'nama') r.nama = val;
  else r[field] = parseFloat(val) || 0;
  const subtotal = r.qty * r.harga;
  const hemat = subtotal * (r.diskon / 100);
  const total = subtotal - hemat;
  const elHemat = document.getElementById(`hemat-${id}`);
  const elTotal = document.getElementById(`total-${id}`);
  if (elHemat) elHemat.innerHTML = hemat > 0 ? '−'+fmt(hemat) : '<span style="color:#9ca3af">—</span>';
  if (elTotal) elTotal.innerHTML = total > 0 ? fmt(total) : '<span style="color:#9ca3af">—</span>';
  renderSummary();
  renderBanner();
}

function updateHJ(el) {
  totalHargaJual = parseFloat(el.value) || 0;
  el.classList.toggle('filled', totalHargaJual > 0);
  document.getElementById('s-jual').textContent = fmt(totalHargaJual);
  renderBanner();
}

function getTotalModal() {
  const fromRows = rows.filter(r => r.qty > 0 && r.harga > 0)
    .reduce((s,r) => s + r.qty * r.harga * (1 - r.diskon/100), 0);
  // Pakai paket DB jika dipilih, gabungkan dengan input manual
  return fromRows + selectedPaketModal;
}

function renderAll() {
  const tbody = document.getElementById('tbody');
  tbody.innerHTML = '';
  rows.forEach((r, i) => {
    const subtotal = r.qty * r.harga;
    const hemat = subtotal * (r.diskon / 100);
    const total = subtotal - hemat;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="rnum">${i+1}</td>
      <td><input class="inp" placeholder="Nama produk..." value="${r.nama}" oninput="update(${r.id},'nama',this.value)"></td>
      <td><input class="inp r" type="number" min="0" placeholder="0" value="${r.qty||''}" oninput="update(${r.id},'qty',this.value)"></td>
      <td><input class="inp r" type="number" min="0" placeholder="0" value="${r.harga||''}" oninput="update(${r.id},'harga',this.value)"></td>
      <td><input class="inp r" type="number" min="0" max="100" placeholder="0" value="${r.diskon||''}" oninput="update(${r.id},'diskon',this.value)"></td>
      <td class="r" id="hemat-${r.id}" style="color:#ef4444;font-size:13px">${hemat > 0 ? '−'+fmt(hemat) : '<span style="color:#9ca3af">—</span>'}</td>
      <td class="r" id="total-${r.id}" style="font-weight:600;color:#4f46e5">${total > 0 ? fmt(total) : '<span style="color:#9ca3af">—</span>'}</td>
      <td><button class="del-btn" onclick="delRow(${r.id})">✕</button></td>
    `;
    tbody.appendChild(tr);
  });
  renderSummary();
  renderBanner();
}

function renderSummary() {
  const filled = rows.filter(r => r.nama && r.qty > 0 && r.harga > 0);
  const subtotal = filled.reduce((s,r) => s + r.qty * r.harga, 0);
  const totalHemat = filled.reduce((s,r) => s + r.qty * r.harga * (r.diskon/100), 0);
  const totalModal = subtotal - totalHemat;
  document.getElementById('s-modal').textContent = fmt(totalModal);
  let html = '';
  if (filled.length > 0) {
    filled.forEach(r => {
      const mpu = r.harga * (1 - r.diskon/100);
      const tot = mpu * r.qty;
      html += `<div class="sum-row"><span>${r.nama} (${r.qty} × ${fmt(r.harga)}${r.diskon>0?' − diskon '+r.diskon+'%':''})</span><span class="amount">${fmt(tot)}</span></div>`;
    });
    html += `<div style="height:1px;background:#f3f4f6;margin:8px 0"></div>`;
  } else {
    html += `<p class="note">Belum ada produk yang diisi lengkap.</p>`;
  }
  html += `
    <div class="sum-row"><span>Subtotal sebelum diskon</span><span class="amount">${fmt(subtotal)}</span></div>
    <div class="sum-row"><span>Total hemat (diskon)</span><span class="amount red">−${fmt(totalHemat)}</span></div>
    <div class="sum-row tot"><span>Total Modal Produk</span><span class="amount blue">${fmt(totalModal)}</span></div>
  `;
  document.getElementById('summary-box').innerHTML = html;
}

function renderBanner() {
  const totalModal = getTotalModal();
  const mpAmount = mpEnabled ? totalHargaJual * (mpPct / 100) : 0;
  document.getElementById('mp-amount').textContent = fmt(mpAmount);

  const untung = totalHargaJual - mpAmount - totalModal;
  const banner = document.getElementById('kbanner');
  const kval = document.getElementById('k-val');
  const kdet = document.getElementById('k-detail');
  const su = document.getElementById('s-untung');

  su.textContent = fmt(untung);
  su.className = 'val ' + (untung > 0 ? 'green' : untung < 0 ? 'red' : 'neutral');
  kval.textContent = (untung >= 0 ? '+' : '') + fmt(untung);

  if (totalHargaJual === 0) {
    banner.className = 'kbanner zero';
    kdet.innerHTML = 'Isi total harga jual untuk melihat hasil';
  } else if (untung > 0) {
    banner.className = 'kbanner profit';
    const margin = (untung / totalHargaJual * 100).toFixed(1);
    let detail = `Jual <strong>${fmt(totalHargaJual)}</strong> − modal <strong>${fmt(totalModal)}</strong>`;
    if (mpEnabled && mpAmount > 0) detail += ` − fee MP <strong>${fmt(mpAmount)}</strong>`;
    detail += `<br>Margin keuntungan <strong>${margin}%</strong>`;
    kdet.innerHTML = detail;
  } else if (untung < 0) {
    banner.className = 'kbanner loss';
    let detail = `Jual <strong>${fmt(totalHargaJual)}</strong> − modal <strong>${fmt(totalModal)}</strong>`;
    if (mpEnabled && mpAmount > 0) detail += ` − fee MP <strong>${fmt(mpAmount)}</strong>`;
    detail += `<br>⚠️ Harga jual lebih kecil dari modal!`;
    kdet.innerHTML = detail;
  } else {
    banner.className = 'kbanner zero';
    kdet.innerHTML = `Jual <strong>${fmt(totalHargaJual)}</strong> = modal. Impas!`;
  }
}

/* ── MARKETPLACE ── */
function toggleMP() {
  mpEnabled = document.getElementById('mp-toggle').checked;
  document.getElementById('mp-body').classList.toggle('off', !mpEnabled);
  renderBanner();
}

function setPreset(btn, name, pct) {
  document.querySelectorAll('.mp-preset').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  mpPlatform = name;
  if (pct !== null) {
    mpPct = pct;
    document.getElementById('mp-pct').value = pct;
    document.getElementById('mp-plat-hint').textContent = `(fee default ${name})`;
  } else {
    document.getElementById('mp-pct').value = mpPct;
    document.getElementById('mp-plat-hint').textContent = 'masukkan % sendiri';
    document.getElementById('mp-pct').focus();
  }
  renderBanner();
}

function onMpInput() {
  mpPct = parseFloat(document.getElementById('mp-pct').value) || 0;
  mpPlatform = 'Custom';
  document.getElementById('mp-plat-hint').textContent = '';
  document.querySelectorAll('.mp-preset').forEach(b => b.classList.remove('active'));
  document.getElementById('btn-custom').classList.add('active');
  renderBanner();
}

/* ── RESET ── */
function resetAll() {
  if (!confirm('Reset semua data?')) return;
  rows = []; nextId = 1; totalHargaJual = 0;
  mpEnabled = false; mpPct = 0; mpPlatform = '';
  document.getElementById('hj-inp').value = '';
  document.getElementById('hj-inp').classList.remove('filled');
  document.getElementById('s-jual').textContent = 'Rp 0';
  document.getElementById('mp-toggle').checked = false;
  document.getElementById('mp-body').classList.add('off');
  document.getElementById('mp-pct').value = 0;
  document.getElementById('mp-plat-hint').textContent = '';
  document.querySelectorAll('.mp-preset').forEach(b => b.classList.remove('active'));
  addRow(); addRow(); addRow();
}

// Inisialisasi awal saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
fetchPaketList();
  addRow(); addRow(); addRow();
});
</script>
</body>
</html>