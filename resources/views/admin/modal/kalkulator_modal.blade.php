<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kalkulator Modal & Keuntungan</title>
<style>
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
    <div class="sec-title">🏷️ Total Harga Jual</div>
    <div class="hj-body">
      <p class="hj-label">Masukkan total keseluruhan harga jual (semua produk digabung)</p>
      <div class="hj-input-wrap">
        <span class="hj-prefix">Rp</span>
        <input class="hj-inp" id="hj-inp" type="number" min="0" placeholder="0" oninput="updateHJ(this)">
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
  
  // Hitung ulang khusus untuk baris ini
  const subtotal = r.qty * r.harga;
  const hemat = subtotal * (r.diskon / 100);
  const total = subtotal - hemat;

  // Update elemen teks hemat dan total modal di baris ini
  const elHemat = document.getElementById(`hemat-${id}`);
  const elTotal = document.getElementById(`total-${id}`);
  
  if (elHemat) {
      elHemat.innerHTML = hemat > 0 ? '−'+fmt(hemat) : '<span style="color:#9ca3af">—</span>';
  }
  if (elTotal) {
      elTotal.innerHTML = total > 0 ? fmt(total) : '<span style="color:#9ca3af">—</span>';
  }

  // Render ulang ringkasan di bawah (summary & banner)
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
  return rows.filter(r => r.qty > 0 && r.harga > 0)
    .reduce((s,r) => s + r.qty * r.harga * (1 - r.diskon/100), 0);
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
  const untung = totalHargaJual - totalModal;
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
    kdet.innerHTML = `Jual <strong>${fmt(totalHargaJual)}</strong> − modal <strong>${fmt(totalModal)}</strong><br>Margin keuntungan <strong>${margin}%</strong>`;
  } else if (untung < 0) {
    banner.className = 'kbanner loss';
    kdet.innerHTML = `Jual <strong>${fmt(totalHargaJual)}</strong> − modal <strong>${fmt(totalModal)}</strong><br>⚠️ Harga jual lebih kecil dari modal!`;
  } else {
    banner.className = 'kbanner zero';
    kdet.innerHTML = `Jual <strong>${fmt(totalHargaJual)}</strong> = modal. Impas!`;
  }
}

function resetAll() {
  if (!confirm('Reset semua data?')) return;
  rows = []; nextId = 1; totalHargaJual = 0;
  document.getElementById('hj-inp').value = '';
  document.getElementById('hj-inp').classList.remove('filled');
  document.getElementById('s-jual').textContent = 'Rp 0';
  addRow(); addRow(); addRow();
}

addRow(); addRow(); addRow();
</script>
</body>
</html>