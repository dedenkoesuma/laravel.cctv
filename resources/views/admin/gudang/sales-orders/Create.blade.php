<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Surat Order</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; font-size: 13px; color: #333; }
  
  .topbar { background: #fff; border-bottom: 1px solid #ddd; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; }
  .topbar h2 { font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
  .btn-back { background: #6c757d; color: #fff; border: none; padding: 6px 14px; border-radius: 4px; font-size: 12px; cursor: pointer; }
  .btn-back:hover { background: #5a6268; }

  .main { padding: 16px 20px; }
  .row { display: flex; gap: 16px; margin-bottom: 16px; }
  .col { flex: 1; }

  .card { background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
  .card-header { padding: 10px 14px; color: #fff; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .card-header.blue { background: #2d6fba; }
  .card-header.cyan { background: #17a2b8; }
  .card-header.green { background: #2e7d52; justify-content: space-between; }
  .card-body { padding: 14px; }

  .form-group { margin-bottom: 12px; }
  label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: #444; }
  .req { color: red; }
  input[type=text], input[type=email], input[type=date], input[type=number], textarea, select {
    width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 6px 10px;
    font-size: 12px; font-family: inherit; color: #333; background: #fff; outline: none; transition: border-color 0.2s;
  }
  input:focus, textarea:focus, select:focus { border-color: #2d6fba; }
  textarea { resize: vertical; min-height: 80px; }
  input[readonly] { background: #f5f5f5; color: #666; }

  .table-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 12px; }
  thead tr { background: #f8f8f8; }
  th { padding: 8px 10px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd; white-space: nowrap; }
  td { padding: 6px 8px; border-bottom: 1px solid #eee; vertical-align: middle; }
  td input, td select { padding: 5px 8px; }

  .btn-tambah { background: #fff; color: #2e7d52; border: 1px solid #fff; padding: 5px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: 600; }
  .btn-tambah:hover { background: #e8f5e9; }
  .btn-delete { background: #e53935; border: none; color: #fff; width: 28px; height: 28px; border-radius: 4px; cursor: pointer; font-size: 12px; }
  .btn-delete:hover { background: #c62828; }

  .summary-section { padding: 12px 14px; background: #fafafa; border-top: 1px solid #eee; }
  .summary-row { display: flex; justify-content: flex-end; align-items: center; margin-bottom: 8px; }
  .summary-label { width: 240px; text-align: right; padding-right: 16px; font-size: 12px; color: #555; display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
  .summary-value { width: 160px; text-align: right; font-size: 12px; font-weight: 600; }
  .summary-row.total-row { border-top: 2px solid #ddd; padding-top: 8px; margin-top: 4px; }
  .summary-row.total-row .summary-label { font-size: 13px; font-weight: 700; color: #333; }
  .summary-row.total-row .summary-value { font-size: 14px; font-weight: 700; color: #e53935; }

  .switch { position: relative; display: inline-block; width: 34px; height: 18px; flex-shrink: 0; }
  .switch input { opacity: 0; width: 0; height: 0; }
  .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #ccc; border-radius: 18px; transition: .3s; }
  .slider:before { position: absolute; content: ""; height: 12px; width: 12px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s; }
  input:checked + .slider { background: #2d6fba; }
  input:checked + .slider:before { transform: translateX(16px); }

  .ppn-badge { background: #e3f2fd; color: #1565c0; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 10px; }
  .ppn-value-green { color: #e65100; }
  .ppn-value-zero { color: #aaa; }

  .bottom-bar { margin-top: 16px; display: flex; justify-content: flex-end; gap: 10px; }
  .btn-submit { background: #2d6fba; color: #fff; border: none; padding: 8px 20px; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer; }
  .btn-submit:hover { background: #1e5799; }
  .btn-cancel { background: #fff; color: #555; border: 1px solid #ccc; padding: 8px 20px; border-radius: 4px; font-size: 13px; cursor: pointer; }
  .btn-cancel:hover { background: #f5f5f5; }
  .empty-row td { text-align: center; padding: 20px; color: #aaa; font-style: italic; }
</style>
</head>
<body>

<div class="topbar">
  <h2>📄 Buat Surat Order</h2>
  <button class="btn-back" onclick="window.location.href='{{ url('admin/gudang/sales-orders') }}'">← Kembali ke Gudang</button>
</div>

<div class="main">
  <div class="row">
    <div class="col card">
      <div class="card-header blue">👤 Informasi Customer</div>
      <div class="card-body">
        <div class="form-group">
          <label>No. SO</label>
          <input type="text" id="no_so" value="{{ $soNumber ?? 'SO-XXXX-XXXX' }}" readonly>
        </div>
        <div class="form-group">
          <label>Nama Customer <span class="req">*</span></label>
          <input type="text" id="nama_customer" placeholder="Nama lengkap customer">
        </div>
        <div class="form-group">
          <label>No. HP</label>
          <input type="text" id="no_hp" placeholder="08xxxxxxxxxx">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" placeholder="email@example.com">
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea id="alamat" placeholder="Alamat pengiriman" style="min-height:64px;"></textarea>
        </div>
      </div>
    </div>

    <div class="col card">
      <div class="card-header cyan">ℹ️ Detail Order</div>
      <div class="card-body">
        <div class="form-group">
          <label>Tanggal SO <span class="req">*</span></label>
          <input type="date" id="tanggal_so" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
          <label>Catatan</label>
          <textarea id="catatan" placeholder="Catatan tambahan (opsional)" style="min-height:120px;"></textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header green">
      <span>🛒 Item Produk</span>
      <button class="btn-tambah" onclick="tambahRow()">⊕ Tambah Produk</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:32%">Produk</th>
            <th style="width:10%">Stok Tersedia</th>
            <th style="width:9%">Qty</th>
            <th style="width:15%">Harga Satuan (Rp)</th>
            <th style="width:13%">Diskon</th>
            <th style="width:13%;text-align:right">Subtotal</th>
            <th style="width:5%;text-align:center">Aksi</th>
          </tr>
        </thead>
        <tbody id="produkBody"></tbody>
      </table>
    </div>

    <div class="summary-section">
      <div class="summary-row">
        <div class="summary-label">Subtotal Produk</div>
        <div class="summary-value" id="subtotalDisplay">Rp 0</div>
      </div>

      <div class="summary-row">
        <div class="summary-label">
          <strong>PPN</strong>
          <label class="switch">
            <input type="checkbox" id="ppnToggle" onchange="togglePPN()">
            <span class="slider"></span>
          </label>
          <select id="ppnRate" style="width:65px;font-size:11px;padding:3px;" disabled onchange="hitung()">
            <option value="11">11%</option>
            <option value="12" selected>12%</option>
            <option value="10">10%</option>
            <option value="custom">Custom</option>
          </select>
          <input type="number" id="ppnCustom" placeholder="%" min="0" max="100" style="width:55px;display:none;" oninput="hitung()">
        </div>
        <div class="summary-value ppn-value-zero" id="ppnDisplay">Rp 0</div>
      </div>

      <div class="summary-row total-row">
        <div class="summary-label"><strong>TOTAL</strong></div>
        <div class="summary-value" id="totalDisplay" style="color:#e53935;">Rp 0</div>
      </div>
    </div>
  </div>

  <div class="bottom-bar">
    <button class="btn-cancel" onclick="window.location.href='{{ url('admin/gudang/sales-orders') }}'">Batal</button>
    <button class="btn-submit" onclick="simpanSuratOrder()">💾 Simpan Surat Order</button>
  </div>
</div>

<script>
// Ambil data produk dari Controller (tabel gudang_products)
const produkList = @json($products ?? []);

let rows = [];
let counter = 0;

function tambahRow() {
  rows.push({ id: ++counter, produkId: '', qty: 1, harga: 0, diskon: 0, diskonType: 'persen' });
  renderRows();
}

function hapus(id) {
  rows = rows.filter(r => r.id !== id);
  renderRows();
}

function ubah(id, field, val) {
  const r = rows.find(r => r.id === id);
  if (!r) return;
  
  if (field === 'produkId') {
    r.produkId = val;
    // Saat produk dipilih, ambil harga_jual dari database
    const p = produkList.find(x => x.id == val);
    r.harga = p ? (p.harga_jual || 0) : 0;
  } else if (field === 'qty') {
    r.qty = Math.max(1, parseInt(val) || 1);
  } else if (field === 'diskon') {
    r.diskon = parseFloat(val) || 0;
  } else if (field === 'harga') {
    r.harga = parseFloat(val) || 0;
  } else {
    r[field] = val;
  }
  
  renderRows();
}

function hitungSubtotalRow(row) {
  let s = row.harga * row.qty; 
  if (row.diskonType === 'persen') s *= (1 - row.diskon/100);
  else s -= row.diskon;
  return Math.max(0, s);
}

function renderRows() {
  const tbody = document.getElementById('produkBody');
  tbody.innerHTML = '';
  
  if (rows.length === 0) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="7">Belum ada produk. Klik "Tambah Produk" untuk menambahkan.</td></tr>';
    hitung(); return;
  }
  
  rows.forEach(row => {
    const p = produkList.find(p => p.id == row.produkId);
    
    // Menggunakan sisa_stok sesuai struktur tabel gudang_products
    const stok = p ? (p.sisa_stok !== null && p.sisa_stok !== undefined ? p.sisa_stok : 0) : '-'; 
    
    const subtotal = hitungSubtotalRow(row);
    
    const opts = produkList.map(x => `<option value="${x.id}" ${row.produkId == x.id ? 'selected' : ''}>${x.nama_produk || x.nama}</option>`).join('');
    
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <select style="width:100%" onchange="ubah(${row.id},'produkId',this.value)">
          <option value="">-- Pilih Produk --</option>${opts}
        </select>
      </td>
      <td style="text-align:center;color:${p && stok <= 0 ? '#e53935':'#333'}; font-weight:${p && stok <= 0 ? 'bold':'normal'};">${stok}</td>
      <td>
        <input type="number" min="1" value="${row.qty}" style="width:100%" oninput="ubah(${row.id},'qty',this.value)">
      </td>
      <td>
        <input type="number" min="0" value="${row.harga}" style="width:100%" oninput="ubah(${row.id},'harga',this.value)">
      </td>
      <td>
        <div style="display:flex;gap:4px;">
          <select style="width:44px;font-size:11px;padding:3px;" onchange="ubah(${row.id},'diskonType',this.value)">
            <option value="persen" ${row.diskonType==='persen'?'selected':''}>%</option>
            <option value="nominal" ${row.diskonType==='nominal'?'selected':''}>Rp</option>
          </select>
          <input type="number" min="0" value="${row.diskon}" style="width:70px;" oninput="ubah(${row.id},'diskon',this.value)">
        </div>
      </td>
      <td style="text-align:right;font-weight:600;">Rp ${fmt(subtotal)}</td>
      <td style="text-align:center;">
        <button class="btn-delete" onclick="hapus(${row.id})">🗑</button>
      </td>`;
    tbody.appendChild(tr);
  });
  hitung();
}

function togglePPN() {
  const on = document.getElementById('ppnToggle').checked;
  const sel = document.getElementById('ppnRate');
  sel.disabled = !on;
  if (!on) {
    document.getElementById('ppnCustom').style.display = 'none';
    document.getElementById('ppnDisplay').className = 'summary-value ppn-value-zero';
  }
  hitung();
}

function hitung() {
  let subtotal = rows.reduce((a, r) => a + hitungSubtotalRow(r), 0);

  const ppnOn = document.getElementById('ppnToggle').checked;
  const ppnRateSel = document.getElementById('ppnRate').value;
  const ppnCustom = document.getElementById('ppnCustom');
  let ppnPct = 0, ppnNom = 0;
  
  if (ppnOn) {
    if (ppnRateSel === 'custom') {
      ppnCustom.style.display = 'inline-block';
      ppnPct = parseFloat(ppnCustom.value) || 0;
    } else {
      ppnCustom.style.display = 'none';
      ppnPct = parseFloat(ppnRateSel);
    }
    ppnNom = subtotal * ppnPct / 100;
    document.getElementById('ppnDisplay').className = 'summary-value ppn-value-green';
    document.getElementById('ppnDisplay').textContent = `+ Rp ${fmt(ppnNom)} (${ppnPct}%)`;
  } else {
    document.getElementById('ppnDisplay').textContent = 'Rp 0';
  }

  const total = subtotal + ppnNom;

  document.getElementById('subtotalDisplay').textContent = 'Rp ' + fmt(subtotal);
  document.getElementById('totalDisplay').textContent = 'Rp ' + fmt(total);
}

function fmt(n) { return Math.round(n).toLocaleString('id-ID'); }

function simpanSuratOrder() {
  const btn = document.querySelector('.btn-submit');
  const validItems = rows.filter(r => r.produkId !== '');
  
  if (!document.getElementById('nama_customer').value) {
    alert("Nama Customer wajib diisi!");
    return;
  }
  if (validItems.length === 0) {
    alert("Tambahkan minimal 1 produk ke dalam order!");
    return;
  }

  btn.disabled = true;
  btn.innerHTML = '⏳ Menyimpan...';

  // Format ulang item sesuai dengan format yang diminta Controller
  const formattedItems = validItems.map(item => ({
      product_id: item.produkId,
      qty: item.qty,
      harga_satuan: item.harga
  }));

  const payload = {
    customer_name: document.getElementById('nama_customer').value,
    customer_phone: document.getElementById('no_hp').value,
    customer_email: document.getElementById('email').value,
    customer_address: document.getElementById('alamat').value,
    so_date: document.getElementById('tanggal_so').value,
    notes: document.getElementById('catatan').value,
    
    // Kirim PPN
    ppn_aktif: document.getElementById('ppnToggle').checked,
    ppn_rate: document.getElementById('ppnToggle').checked ? 
             (document.getElementById('ppnRate').value === 'custom' ? document.getElementById('ppnCustom').value : document.getElementById('ppnRate').value) : 0,
             
    items: formattedItems
  };

  fetch("{{ url('admin/gudang/sales-orders') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(payload)
  })
  .then(async response => {
    const isJson = response.headers.get('content-type')?.includes('application/json');
    const data = isJson ? await response.json() : null;

    if (!response.ok) {
        if (response.status === 422) {
            const errors = data.errors || {};
            const errorMsg = Object.values(errors).map(err => err.join(', ')).join('\n');
            throw new Error("Validasi gagal:\n" + errorMsg);
        }
        throw new Error((data && data.message) || "Terjadi error pada server. Silakan cek console/tab Network.");
    }
    return data;
  })
  .then(data => {
    alert("✅ Surat Order berhasil disimpan!");
    window.location.href = "{{ url('admin/gudang/sales-orders') }}"; 
  })
  .catch(error => {
    console.error('Error:', error);
    alert(error.message); 
    btn.disabled = false;
    btn.innerHTML = '💾 Simpan Surat Order';
  });
}

// Inisialisasi awal form saat halaman dimuat
tambahRow();
</script>
</body>
</html>