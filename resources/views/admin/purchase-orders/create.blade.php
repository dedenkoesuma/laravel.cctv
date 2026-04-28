{{-- resources/views/admin/purchase-orders/create.blade.php --}}
@extends('layouts.simple')
@section('title', 'Buat PO Baru')

@section('content')
<style>
.po-header{background:linear-gradient(135deg,#1e3a5f 0%,#2d6a9f 100%);color:white;padding:24px 26px;border-radius:14px;margin-bottom:22px}
.section-card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);padding:22px;margin-bottom:18px}
.section-title{font-size:.8rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid #f3f4f6;display:flex;align-items:center;gap:8px}
.form-label{font-size:.8rem;font-weight:600;color:#374151;margin-bottom:5px}
.form-control,.form-select{border-radius:8px;border:1px solid #d1d5db;font-size:.875rem;padding:8px 12px}
.form-control:focus,.form-select:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.item-table{width:100%;border-collapse:collapse;margin-top:4px}
.item-table th{background:#f8fafc;font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;padding:9px 10px;border:1px solid #e5e7eb}
.item-table td{padding:7px 8px;border:1px solid #e5e7eb;vertical-align:middle}
.item-table input,.item-table select{width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.8rem}
.item-table input:focus,.item-table select:focus{border-color:#2563eb;outline:none}
.add-row-btn{margin-top:8px;padding:8px;border-radius:8px;border:2px dashed #d1d5db;background:white;font-size:.8rem;color:#6b7280;cursor:pointer;width:100%;transition:all .2s}
.add-row-btn:hover{border-color:#2563eb;color:#2563eb;background:#eff6ff}
.ppn-toggle-wrap{padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;transition:all .3s;margin-bottom:14px}
.ppn-toggle-wrap.ppn-on{border-color:#3b82f6;background:#eff6ff}
.ppn-toggle-wrap.ppn-off{border-color:#e5e7eb;background:#f9fafb}
.summary-box{border-radius:10px;overflow:hidden;border:1px solid #e5e7eb}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-bottom:1px solid #f3f4f6;font-size:.875rem}
.summary-row:last-child{border-bottom:none}
.summary-row.total-row{background:linear-gradient(135deg,#1e3a5f,#2d6a9f);color:white;padding:14px 16px}
.summary-row.ppn-row{background:#eff6ff}
.summary-row.ppn-row.hidden{display:none}
.action-bar{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);padding:16px 22px;display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;margin-top:4px}
</style>

<div class="container py-4">
    <div class="po-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-4 fw-bold mb-1">➕ Buat Draft Purchase Order</h1>
                <p class="mb-0 opacity-75">Isi form lengkap lalu simpan sebagai draft atau langsung kirim</p>
            </div>
            <div style="text-align:right">
                <div style="font-size:11px;opacity:.75">Nomor PO Otomatis</div>
                <div style="font-size:1.5rem;font-weight:800;font-family:monospace">{{ $poNumber }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- KOLOM KIRI --}}
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title">🏢 Info Supplier / Toko</div>
                <div class="mb-3">
                    <label class="form-label">Nama Supplier / Toko <span class="text-danger">*</span></label>
                    <input type="text" id="supplierName" class="form-control" placeholder="Contoh: PT Hikvision Indonesia" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="form-label">No. Telepon</label><input type="text" id="supplierPhone" class="form-control"></div>
                    <div class="col-6"><label class="form-label">Email</label><input type="email" id="supplierEmail" class="form-control"></div>
                </div>
                <div class="mb-3"><label class="form-label">Alamat Supplier</label><textarea id="supplierAddress" class="form-control" rows="2"></textarea></div>
                <div class="mb-0"><label class="form-label">Nama PIC</label><input type="text" id="supplierPic" class="form-control"></div>
            </div>

            <div class="section-card">
                <div class="section-title">📋 Detail Purchase Order</div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="form-label">Tanggal PO <span class="text-danger">*</span></label><input type="date" id="poDate" class="form-control" value="{{ date('Y-m-d') }}"></div>
                    <div class="col-6"><label class="form-label">Dibutuhkan Sebelum</label><input type="date" id="requiredDate" class="form-control"></div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select id="paymentMethod" class="form-select">
                            <option value="transfer">🏦 Transfer Bank</option><option value="cash">💵 Cash</option>
                            <option value="tempo_30">⏱ Tempo 30 Hari</option><option value="tempo_60">⏱ Tempo 60 Hari</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kirim ke Gudang</label>
                        <select id="deliveryTo" class="form-select">
                            <option value="Gudang Utama">Gudang Utama</option><option value="Gudang Cabang">Gudang Cabang</option>
                        </select>
                    </div>
                </div>
                <div><label class="form-label">Catatan / Syarat Khusus</label><textarea id="notes" class="form-control" rows="3"></textarea></div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title d-flex justify-content-between align-items-center">
                    <span>📦 Daftar Item yang Dipesan</span>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRow()"><i class="bi bi-plus-circle me-1"></i>Tambah</button>
                </div>
                <div class="table-responsive">
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th width="30%">Nama Produk</th>
                                <th width="15%">Qty</th>
                                <th width="12%">Satuan</th>
                                <th width="22%">Harga</th>
                                <th width="15%">Diskon (%)</th>
                                <th width="6%"></th>
                            </tr>
                        </thead>
                        <tbody id="itemBody"></tbody>
                    </table>
                </div>
                <button type="button" class="add-row-btn" onclick="addRow()"><i class="bi bi-plus-circle me-1"></i> Tambah Baris Item</button>
            </div>

            <div class="section-card">
                <div class="section-title">🧾 Pajak & Total</div>
                <div class="ppn-toggle-wrap ppn-off mb-3" id="ppnWrap">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold" style="font-size:.875rem"><i class="bi bi-receipt me-2 text-primary"></i>Gunakan PPN?</div>
                            <div class="text-muted" style="font-size:.75rem">Aktifkan jika transaksi ini dikenakan Pajak</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="usePpn" role="switch" style="width:42px;height:22px;cursor:pointer" onchange="togglePpn()">
                        </div>
                    </div>
                    <div id="ppnDetail" style="display:none;margin-top:12px;padding-top:12px;border-top:1px solid #bfdbfe">
                        <div class="row g-2 align-items-center">
                            <div class="col-5">
                                <label class="form-label mb-1">Persentase PPN</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" id="ppnPercent" class="form-control" value="11" min="0" max="100" step="0.5" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()" style="text-align:center">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="p-2 rounded" style="background:#dbeafe;font-size:.8rem">
                                    <div class="text-muted">Estimasi PPN</div>
                                    <div class="fw-bold text-primary" id="ppnEstimate">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <label class="form-label">Ongkos Kirim (Rp)</label>
                        <input type="number" id="shippingCost" class="form-control" value="0" min="0" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()">
                    </div>
                </div>

                <div class="summary-box">
                    <div class="summary-row"><span class="text-muted">Subtotal Item</span><span class="fw-semibold" id="dispSubtotal">Rp 0</span></div>
                    <div class="summary-row"><span class="text-muted">Total Diskon Item</span><span class="fw-semibold text-danger" id="dispDiscount">- Rp 0</span></div>
                    <div class="summary-row ppn-row hidden" id="ppnRow">
                        <span class="text-primary fw-semibold"><i class="bi bi-receipt me-1"></i>PPN <span id="dispPpnPct">11</span>%</span>
                        <span class="fw-semibold text-primary" id="dispPpn">Rp 0</span>
                    </div>
                    <div class="summary-row"><span class="text-muted">Ongkos Kirim</span><span class="fw-semibold" id="dispShipping">Rp 0</span></div>
                    <div class="summary-row total-row"><span class="fw-bold fs-6">TOTAL</span><span class="fw-bold fs-5" id="dispTotal">Rp 0</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('admin.po.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Batal</a>
        <button type="button" class="btn btn-secondary" onclick="simpanPO('draft')"><i class="bi bi-floppy me-1"></i>Simpan Draft</button>
        <button type="button" class="btn btn-primary fw-bold" onclick="simpanPO('sent')"><i class="bi bi-send me-1"></i>Simpan & Kirim</button>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
let rowIndex = 0;

document.addEventListener('DOMContentLoaded', () => { addRow(); calcTotal(); });

function addRow() {
    const idx = rowIndex++;
    const tr  = document.createElement('tr');
    tr.id     = `row-${idx}`;
    tr.innerHTML = `
        <td>
            <input type="text" placeholder="Nama produk..." id="pname-${idx}" oninput="calcTotal()">
            <input type="text" placeholder="Deskripsi..." id="pdesc-${idx}" style="margin-top:4px;font-size:11px;color:#6b7280">
        </td>
        <td><input type="number" id="qty-${idx}" value="1" min="0.1" step="0.1" style="text-align:center" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td><select id="unit-${idx}"><option>pcs</option><option>unit</option><option>box</option><option>roll</option><option>set</option><option>meter</option></select></td>
        <td><input type="number" id="price-${idx}" value="0" min="0" style="text-align:right" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td><input type="number" id="disc-${idx}" value="0" min="0" max="100" placeholder="%" style="text-align:center" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td style="text-align:center"><button type="button" onclick="delRow(${idx})" style="background:#fee2e2;border:none;border-radius:5px;color:#dc2626;padding:4px 9px;cursor:pointer;font-size:12px">✕</button></td>`;
    document.getElementById('itemBody').appendChild(tr);
}

function delRow(idx) {
    if (document.querySelectorAll('#itemBody tr').length <= 1) { alert('Minimal 1 item!'); return; }
    document.getElementById(`row-${idx}`)?.remove();
    calcTotal();
}

function togglePpn() {
    const on     = document.getElementById('usePpn').checked;
    const wrap   = document.getElementById('ppnWrap');
    const detail = document.getElementById('ppnDetail');
    const row    = document.getElementById('ppnRow');
    wrap.className   = on ? 'ppn-toggle-wrap ppn-on mb-3' : 'ppn-toggle-wrap ppn-off mb-3';
    detail.style.display = on ? 'block' : 'none';
    row.classList.toggle('hidden', !on);
    calcTotal();
}

function calcTotal() {
    let grossSubtotal = 0;
    let totalDiscountNominal = 0;
    
    // Looping semua item pesanan
    document.querySelectorAll('#itemBody tr').forEach(row => {
        const id    = row.id.replace('row-', '');
        const qty   = parseFloat(document.getElementById(`qty-${id}`)?.value || 0);
        const price = parseFloat(document.getElementById(`price-${id}`)?.value || 0);
        const discPct = parseFloat(document.getElementById(`disc-${id}`)?.value || 0);
        
        const gross = qty * price;
        const discNominal = gross * (discPct / 100);
        
        grossSubtotal += gross;
        totalDiscountNominal += discNominal;
    });

    const netSubtotal = Math.max(0, grossSubtotal - totalDiscountNominal);
    const shipping  = parseFloat(document.getElementById('shippingCost')?.value || 0);
    
    const usePpn    = document.getElementById('usePpn')?.checked;
    const ppnPct    = parseFloat(document.getElementById('ppnPercent')?.value || 11);
    const ppnAmt    = usePpn ? Math.round(netSubtotal * (ppnPct / 100)) : 0;
    const total     = netSubtotal + ppnAmt + shipping;

    // Update UI Summary
    document.getElementById('dispSubtotal').textContent = 'Rp ' + grossSubtotal.toLocaleString('id-ID');
    document.getElementById('dispDiscount').textContent = '- Rp ' + totalDiscountNominal.toLocaleString('id-ID');
    document.getElementById('dispPpn').textContent      = 'Rp ' + ppnAmt.toLocaleString('id-ID');
    document.getElementById('dispPpnPct').textContent   = ppnPct;
    document.getElementById('dispShipping').textContent = 'Rp ' + shipping.toLocaleString('id-ID');
    document.getElementById('dispTotal').textContent    = 'Rp ' + Math.round(total).toLocaleString('id-ID');

    if (document.getElementById('ppnEstimate'))
        document.getElementById('ppnEstimate').textContent = 'Rp ' + ppnAmt.toLocaleString('id-ID');
}

function collectItems() {
    const items = [];
    let valid = true;
    document.querySelectorAll('#itemBody tr').forEach(row => {
        const id    = row.id.replace('row-', '');
        const name  = document.getElementById(`pname-${id}`)?.value.trim();
        const qty   = parseFloat(document.getElementById(`qty-${id}`)?.value || 0);
        const price = parseFloat(document.getElementById(`price-${id}`)?.value || 0);
        
        if (!name) { valid = false; return; }
        items.push({
            product_name       : name,
            product_description: document.getElementById(`pdesc-${id}`)?.value.trim(),
            unit               : document.getElementById(`unit-${id}`)?.value,
            qty, unit_price    : price,
            discount_item      : parseFloat(document.getElementById(`disc-${id}`)?.value || 0),
        });
    });
    return { items, valid };
}

async function simpanPO(status = 'draft') {
    const supplierName = document.getElementById('supplierName').value.trim();
    if (!supplierName) { alert('Nama supplier wajib diisi!'); document.getElementById('supplierName').focus(); return; }
    
    const { items, valid } = collectItems();
    if (!valid || items.length === 0) { alert('Isi nama produk untuk semua item!'); return; }
    
    const usePpn   = document.getElementById('usePpn').checked;
    const payload  = {
        supplier_name   : supplierName,
        supplier_phone  : document.getElementById('supplierPhone').value,
        supplier_email  : document.getElementById('supplierEmail').value,
        supplier_address: document.getElementById('supplierAddress').value,
        supplier_pic    : document.getElementById('supplierPic').value,
        po_date         : document.getElementById('poDate').value,
        required_date   : document.getElementById('requiredDate').value,
        payment_method  : document.getElementById('paymentMethod').value,
        delivery_to     : document.getElementById('deliveryTo').value,
        use_ppn         : usePpn,
        ppn_percent     : usePpn ? parseFloat(document.getElementById('ppnPercent')?.value || 11) : 0,
        discount        : 0, // Diskon global diset 0 karena hanya dari item
        shipping_cost   : document.getElementById('shippingCost').value,
        notes           : document.getElementById('notes').value,
        items,
    };

    const btnDraft = document.querySelector('button[onclick="simpanPO(\'draft\')"]');
    const btnSend  = document.querySelector('button[onclick="simpanPO(\'sent\')"]');
    [btnDraft, btnSend].forEach(b => { if(b) b.disabled = true; });
    btnSend.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    try {
        const res  = await fetch('/api/admin/purchase-orders', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body   : JSON.stringify(payload),
        });
        const data = await res.json();
        if (data.success) {
            if (status === 'sent') {
                await fetch(`/api/admin/purchase-orders/${data.po_id}/status`, {
                    method : 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body   : JSON.stringify({ status: 'sent' }),
                });
            }
            window.location.href = '/admin/purchase-orders?success=' + encodeURIComponent(data.po_number);
        } else alert('❌ ' + (data.message || 'Gagal menyimpan'));
    } catch(e) { alert('❌ Error: ' + e.message); } 
    finally {
        [btnDraft, btnSend].forEach(b => { if(b) b.disabled = false; });
        btnSend.innerHTML = '<i class="bi bi-send me-1"></i>Simpan & Kirim';
    }
}
</script>
@endsection