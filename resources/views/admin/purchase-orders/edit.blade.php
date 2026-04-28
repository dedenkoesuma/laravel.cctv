{{-- resources/views/admin/purchase-orders/edit.blade.php --}}
@extends('layouts.simple')
@section('title', 'Edit PO - ' . $po->po_number)

@section('content')
<style>
.po-header{background:linear-gradient(135deg,#92400e 0%,#d97706 100%);color:white;padding:24px 26px;border-radius:14px;margin-bottom:22px}
.section-card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);padding:22px;margin-bottom:18px}
.section-title{font-size:.8rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid #f3f4f6}
.form-label{font-size:.8rem;font-weight:600;color:#374151;margin-bottom:5px}
.form-control,.form-select{border-radius:8px;border:1px solid #d1d5db;font-size:.875rem;padding:8px 12px}
.form-control:focus,.form-select:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1)}

.item-table{width:100%;border-collapse:collapse}
.item-table th{background:#f8fafc;font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;padding:9px 10px;border:1px solid #e5e7eb}
.item-table td{padding:7px 8px;border:1px solid #e5e7eb;vertical-align:middle}
.item-table input,.item-table select{width:100%;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.8rem}
.item-table input:focus,.item-table select:focus{border-color:#d97706;outline:none}
.add-row-btn{margin-top:8px;padding:8px;border-radius:8px;border:2px dashed #d1d5db;background:white;font-size:.8rem;color:#6b7280;cursor:pointer;width:100%;transition:all .2s}
.add-row-btn:hover{border-color:#d97706;color:#d97706;background:#fffbeb}

.ppn-toggle-wrap{padding:14px 16px;border-radius:10px;border:2px solid #e5e7eb;transition:all .3s;margin-bottom:14px}
.ppn-toggle-wrap.ppn-on{border-color:#3b82f6;background:#eff6ff}
.ppn-toggle-wrap.ppn-off{border-color:#e5e7eb;background:#f9fafb}

.summary-box{border-radius:10px;overflow:hidden;border:1px solid #e5e7eb}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-bottom:1px solid #f3f4f6;font-size:.875rem}
.summary-row:last-child{border-bottom:none}
.summary-row.total-row{background:linear-gradient(135deg,#92400e,#d97706);color:white;padding:14px 16px}
.summary-row.ppn-row{background:#eff6ff}
.summary-row.ppn-row.hidden{display:none}
.action-bar{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);padding:16px 22px;display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;margin-top:4px}
</style>

<div class="container py-4">
    <div class="po-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-4 fw-bold mb-1">✏️ Edit Purchase Order</h1>
                <p class="mb-0 opacity-75">Hanya PO berstatus Draft yang dapat diedit</p>
            </div>
            <div style="text-align:right">
                <div style="font-size:11px;opacity:.75">Nomor PO</div>
                <div style="font-size:1.5rem;font-weight:800;font-family:monospace">{{ $po->po_number }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title">🏢 Info Supplier / Toko</div>
                <div class="mb-3">
                    <label class="form-label">Nama Supplier / Toko <span class="text-danger">*</span></label>
                    <input type="text" id="supplierName" class="form-control" value="{{ $po->supplier_name }}" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" id="supplierPhone" class="form-control" value="{{ $po->supplier_phone }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Email</label>
                        <input type="email" id="supplierEmail" class="form-control" value="{{ $po->supplier_email }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Supplier</label>
                    <textarea id="supplierAddress" class="form-control" rows="2">{{ $po->supplier_address }}</textarea>
                </div>
                <div>
                    <label class="form-label">Nama PIC / Contact Person</label>
                    <input type="text" id="supplierPic" class="form-control" value="{{ $po->supplier_pic }}">
                </div>
            </div>

            <div class="section-card">
                <div class="section-title">📋 Detail Purchase Order</div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Tanggal PO <span class="text-danger">*</span></label>
                        <input type="date" id="poDate" class="form-control" value="{{ $po->po_date }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Dibutuhkan Sebelum</label>
                        <input type="date" id="requiredDate" class="form-control" value="{{ $po->required_date }}">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select id="paymentMethod" class="form-select">
                            @foreach(['transfer'=>'🏦 Transfer Bank','cash'=>'💵 Cash','tempo_30'=>'⏱ Tempo 30 Hari','tempo_60'=>'⏱ Tempo 60 Hari'] as $v=>$l)
                                <option value="{{ $v }}" {{ $po->payment_method==$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kirim ke Gudang</label>
                        <select id="deliveryTo" class="form-select">
                            <option {{ $po->delivery_to=='Gudang Utama'?'selected':'' }}>Gudang Utama</option>
                            <option {{ $po->delivery_to=='Gudang Cabang'?'selected':'' }}>Gudang Cabang</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Catatan</label>
                    <textarea id="notes" class="form-control" rows="3">{{ $po->notes }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title d-flex justify-content-between align-items-center">
                    <span>📦 Daftar Item</span>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="addRow()">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Item
                    </button>
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
                <button type="button" class="add-row-btn" onclick="addRow()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Baris Item
                </button>
            </div>

            <div class="section-card">
                <div class="section-title">🧾 Pajak & Total</div>
                <div class="ppn-toggle-wrap {{ $po->use_ppn ? 'ppn-on' : 'ppn-off' }} mb-3" id="ppnWrap">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold" style="font-size:.875rem">
                                <i class="bi bi-receipt me-2 text-primary"></i>Gunakan PPN?
                            </div>
                            <div class="text-muted" style="font-size:.75rem">Aktifkan jika dikenakan PPN</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            {{-- TRIGGER EVENT ONCHANGE --}}
                            <input class="form-check-input" type="checkbox" id="usePpn" role="switch" style="width:42px;height:22px;cursor:pointer" {{ $po->use_ppn ? 'checked' : '' }} onchange="togglePpn()">
                        </div>
                    </div>
                    <div id="ppnDetail" style="display:{{ $po->use_ppn ? 'block' : 'none' }};margin-top:12px;padding-top:12px;border-top:1px solid #bfdbfe">
                        <div class="row g-2 align-items-center">
                            <div class="col-5">
                                <label class="form-label mb-1">Persentase PPN</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" id="ppnPercent" class="form-control" value="{{ $po->ppn_percent ?: 11 }}" min="0" max="100" step="0.5" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()" style="text-align:center">
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
                        {{-- TRIGGER EVENT ALL --}}
                        <input type="number" id="shippingCost" class="form-control" value="{{ $po->shipping_cost }}" min="0" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()">
                    </div>
                </div>

                <div class="summary-box">
                    <div class="summary-row"><span class="text-muted">Subtotal Item</span><span class="fw-semibold" id="dispSubtotal">Rp 0</span></div>
                    <div class="summary-row"><span class="text-muted">Total Diskon Item</span><span class="fw-semibold text-danger" id="dispDiscount">- Rp 0</span></div>
                    <div class="summary-row ppn-row {{ $po->use_ppn ? '' : 'hidden' }}" id="ppnRow">
                        <span class="text-primary fw-semibold"><i class="bi bi-receipt me-1"></i>PPN <span id="dispPpnPct">{{ $po->ppn_percent }}</span>%</span>
                        <span class="fw-semibold text-primary" id="dispPpn">Rp 0</span>
                    </div>
                    <div class="summary-row"><span class="text-muted">Ongkos Kirim</span><span class="fw-semibold" id="dispShipping">Rp 0</span></div>
                    <div class="summary-row total-row"><span class="fw-bold fs-6">TOTAL</span><span class="fw-bold fs-5" id="dispTotal">Rp 0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="action-bar">
        <a href="{{ route('admin.po.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i>Batal
        </a>
        <button type="button" class="btn btn-warning fw-bold text-white" onclick="updatePO()">
            <i class="bi bi-floppy me-1"></i>Simpan Perubahan
        </button>
    </div>
</div>

<script>
const csrf   = document.querySelector('meta[name="csrf-token"]')?.content || '';
const poId   = {{ $po->id }};
let rowIndex = 0;

// ===== LOAD EXISTING ITEMS =====
const existingItems = @json($items);

document.addEventListener('DOMContentLoaded', () => {
    if (existingItems.length) {
        existingItems.forEach(item => addRow(item));
    } else {
        addRow();
    }
    calcTotal();
});

function addRow(data = null) {
    const idx = rowIndex++;
    const tr  = document.createElement('tr');
    tr.id     = `row-${idx}`;
    tr.innerHTML = `
        <td>
            <input type="text" id="pname-${idx}" value="${data?.product_name||''}" placeholder="Nama produk..." oninput="calcTotal()">
            <input type="text" id="pdesc-${idx}" value="${data?.product_description||''}" placeholder="Deskripsi..." style="margin-top:4px;font-size:11px;color:#6b7280">
        </td>
        <td><input type="number" id="qty-${idx}" value="${data?.qty||1}" min="0.1" step="0.1" style="text-align:center" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td>
            <select id="unit-${idx}">
                ${['pcs','unit','box','roll','set','meter'].map(u=>`<option ${data?.unit==u?'selected':''}>${u}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" id="price-${idx}" value="${data?.unit_price||0}" min="0" style="text-align:right" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td><input type="number" id="disc-${idx}" value="${data?.discount_item||0}" min="0" max="100" placeholder="%" style="text-align:center" oninput="calcTotal()" onkeyup="calcTotal()" onchange="calcTotal()"></td>
        <td style="text-align:center">
            <button type="button" onclick="delRow(${idx})" style="background:#fee2e2;border:none;border-radius:5px;color:#dc2626;padding:4px 9px;cursor:pointer;font-size:12px">✕</button>
        </td>`;
    document.getElementById('itemBody').appendChild(tr);
}

function delRow(idx) {
    if (document.querySelectorAll('#itemBody tr').length <= 1) { alert('Minimal 1 item!'); return; }
    document.getElementById(`row-${idx}`)?.remove();
    calcTotal();
}

function togglePpn() {
    const on = document.getElementById('usePpn').checked;
    document.getElementById('ppnWrap').className = `ppn-toggle-wrap ${on?'ppn-on':'ppn-off'} mb-3`;
    document.getElementById('ppnDetail').style.display = on ? 'block' : 'none';
    document.getElementById('ppnRow').classList.toggle('hidden', !on);
    calcTotal();
}

function calcTotal() {
    let grossSubtotal = 0;
    let totalDiscountNominal = 0;
    
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

    document.getElementById('dispSubtotal').textContent  = 'Rp ' + grossSubtotal.toLocaleString('id-ID');
    document.getElementById('dispDiscount').textContent  = '- Rp ' + totalDiscountNominal.toLocaleString('id-ID');
    document.getElementById('dispPpn').textContent       = 'Rp ' + ppnAmt.toLocaleString('id-ID');
    document.getElementById('dispPpnPct').textContent    = ppnPct;
    document.getElementById('dispShipping').textContent  = 'Rp ' + shipping.toLocaleString('id-ID');
    document.getElementById('dispTotal').textContent     = 'Rp ' + Math.round(total).toLocaleString('id-ID');

    if (document.getElementById('ppnEstimate'))
        document.getElementById('ppnEstimate').textContent = 'Rp ' + ppnAmt.toLocaleString('id-ID');
}

async function updatePO() {
    const supplierName = document.getElementById('supplierName').value.trim();
    if (!supplierName) { alert('Nama supplier wajib diisi!'); return; }
    
    const items = [];
    let valid   = true;
    document.querySelectorAll('#itemBody tr').forEach(row => {
        const id = row.id.replace('row-', '');
        const name = document.getElementById(`pname-${id}`)?.value.trim();
        if (!name) { valid = false; return; }
        
        items.push({
            product_name       : name,
            product_description: document.getElementById(`pdesc-${id}`)?.value.trim(),
            unit               : document.getElementById(`unit-${id}`)?.value,
            qty                : parseFloat(document.getElementById(`qty-${id}`)?.value || 1),
            unit_price         : parseFloat(document.getElementById(`price-${id}`)?.value || 0),
            discount_item      : parseFloat(document.getElementById(`disc-${id}`)?.value || 0),
        });
    });

    if (!valid || !items.length) { alert('Isi nama produk untuk semua item!'); return; }
    
    const usePpn = document.getElementById('usePpn').checked;
    const payload = {
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
        ppn_percent     : usePpn ? parseFloat(document.getElementById('ppnPercent').value || 11) : 0,
        discount        : 0, // Diskon global dikirim 0 (sudah pakai per-item)
        shipping_cost   : document.getElementById('shippingCost').value,
        notes           : document.getElementById('notes').value,
        items,
    };

    const btn = document.querySelector('button[onclick="updatePO()"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    try {
        const res  = await fetch(`/api/admin/purchase-orders/${poId}`, {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body   : JSON.stringify(payload),
        });
        const data = await res.json();
        
        if (data.success) {
            window.location.href = '/admin/purchase-orders?updated=1';
        } else {
            alert('❌ ' + (data.message || 'Gagal update'));
        }
    } catch(e) {
        alert('❌ Error: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Simpan Perubahan';
    }
}
</script>
@endsection