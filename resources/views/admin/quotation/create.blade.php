{{-- resources/views/admin/quotation/create.blade.php --}}
@extends('layouts.simple')
@section('title', isset($quo) ? 'Edit Penawaran' : 'Buat Penawaran Baru')
@section('content')
<style>
.quo-header{background:linear-gradient(135deg,#1e3a5f 0%,#2d6fba 100%);color:white;padding:22px 24px;border-radius:14px;margin-bottom:20px;}
.card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);margin-bottom:16px;overflow:hidden;}
.card-hdr{padding:14px 20px;font-weight:700;font-size:.85rem;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:8px;}
.card-hdr.blue{background:#eff6ff;color:#1e40af;}
.card-hdr.green{background:#f0fdf4;color:#065f46;}
.card-hdr.orange{background:#fff7ed;color:#9a3412;}
.card-bd{padding:20px;}
.fg{margin-bottom:14px;}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
label{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:4px;}
.req{color:#ef4444;}
input,select,textarea{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;font-size:.875rem;font-family:inherit;outline:none;transition:border-color .2s;}
input:focus,select:focus,textarea:focus{border-color:#2d6fba;box-shadow:0 0 0 3px rgba(45,111,186,.1);}
textarea{resize:vertical;}
.it{width:100%;border-collapse:collapse;font-size:.82rem;}
.it thead th{background:#f8fafc;padding:9px 8px;text-align:left;font-size:.7rem;font-weight:700;color:#374151;text-transform:uppercase;border-bottom:2px solid #e5e7eb;white-space:nowrap;}
.it tbody td{padding:6px 5px;border-bottom:1px solid #f3f4f6;vertical-align:middle;}
.it input,.it select{padding:5px 7px;font-size:.8rem;}
.btn-add{background:#f0fdf4;color:#065f46;border:1.5px dashed #6ee7b7;padding:8px;border-radius:8px;cursor:pointer;font-size:.82rem;font-weight:700;font-family:inherit;width:100%;margin-top:8px;}
.btn-add:hover{background:#dcfce7;}
.btn-del{background:#fee2e2;border:none;color:#dc2626;width:26px;height:26px;border-radius:6px;cursor:pointer;font-size:.75rem;}
.sum-box{background:#f8fafc;border-radius:10px;padding:16px;border:1px solid #e5e7eb;}
.sr{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:.85rem;}
.sr.tot{border-top:2px solid #e5e7eb;margin-top:8px;padding-top:10px;font-weight:800;font-size:1rem;color:#1e3a5f;}
.sw{position:relative;display:inline-block;width:34px;height:18px;}
.sw input{opacity:0;width:0;height:0;}
.sl{position:absolute;cursor:pointer;inset:0;background:#ccc;border-radius:18px;transition:.3s;}
.sl::before{position:absolute;content:"";height:12px;width:12px;left:3px;bottom:3px;background:white;border-radius:50%;transition:.3s;}
input:checked+.sl{background:#2d6fba;}
input:checked+.sl::before{transform:translateX(16px);}
.btn-save{background:linear-gradient(135deg,#1e3a5f,#2d6fba);color:white;border:none;padding:12px 24px;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;}
.btn-save:hover{opacity:.9;}
.btn-batal{background:white;color:#6b7280;border:1px solid #d1d5db;padding:12px 18px;border-radius:10px;font-size:.9rem;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-block;}
</style>

<div class="container py-4">
    <div class="quo-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">{{ isset($quo) ? '✏️ Edit Penawaran' : '📋 Buat Penawaran Baru' }}</h1>
                <p class="mb-0 opacity-75">{{ isset($quo) ? $quo->quo_number : 'Isi form untuk membuat surat penawaran digital' }}</p>
            </div>
            <a href="{{ route('admin.quotation.index') }}" class="btn btn-light fw-bold">← Kembali</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            {{-- INFO CUSTOMER --}}
            <div class="card">
                <div class="card-hdr blue">👤 Informasi Customer</div>
                <div class="card-bd">
                    <div class="fr">
                        <div class="fg"><label>Nama Customer <span class="req">*</span></label>
                            <input type="text" id="cName" placeholder="Nama lengkap" value="{{ $quo->customer_name ?? '' }}"></div>
                        <div class="fg"><label>No. HP / WhatsApp</label>
                            <input type="text" id="cPhone" placeholder="08xxxxxxxxxx" value="{{ $quo->customer_phone ?? '' }}"></div>
                    </div>
                    <div class="fr">
                        <div class="fg"><label>Email</label>
                            <input type="email" id="cEmail" placeholder="email@example.com" value="{{ $quo->customer_email ?? '' }}"></div>
                        <div class="fg"><label>Nama Proyek / Keperluan</label>
                            <input type="text" id="projName" placeholder="Contoh: Pasang CCTV Kantor" value="{{ $quo->project_name ?? '' }}"></div>
                    </div>
                    <div class="fg"><label>Alamat</label>
                        <textarea id="cAddr" rows="2" placeholder="Alamat lengkap">{{ $quo->customer_address ?? '' }}</textarea></div>
                </div>
            </div>

            {{-- ITEM --}}
            <div class="card">
                <div class="card-hdr green">🛒 Item Penawaran</div>
                <div class="card-bd">
                    <div style="overflow-x:auto;">
                        <table class="it">
                            <thead><tr>
                                <th style="width:28%">Nama Item</th>
                                <th style="width:16%">Keterangan</th>
                                <th style="width:7%">Qty</th>
                                <th style="width:8%">Satuan</th>
                                <th style="width:16%">Harga Satuan (Rp)</th>
                                <th style="width:8%">Diskon%</th>
                                <th style="width:13%;text-align:right">Subtotal</th>
                                <th style="width:4%"></th>
                            </tr></thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                    <button class="btn-add" onclick="tambahItem()">+ Tambah Item</button>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="card">
                <div class="card-hdr orange">📝 Catatan & Syarat</div>
                <div class="card-bd">
                    <div class="fg"><label>Catatan untuk Customer</label>
                        <textarea id="notes" rows="3" placeholder="Contoh: Harga sudah termasuk pemasangan...">{{ $quo->notes ?? '' }}</textarea></div>
                    <div class="fg"><label>Syarat & Ketentuan</label>
                        <textarea id="terms" rows="5">{{ $quo->terms ?? "1. Penawaran berlaku sesuai tanggal yang tertera.\n2. Harga belum termasuk ongkos kirim kecuali disebutkan.\n3. Pembayaran sesuai kesepakatan.\n4. Garansi produk sesuai kebijakan pabrik.\n5. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan." }}</textarea></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- DETAIL --}}
            <div class="card">
                <div class="card-hdr blue">📅 Detail Penawaran</div>
                <div class="card-bd">
                    <div class="fg"><label>Tanggal Penawaran <span class="req">*</span></label>
                        <input type="date" id="quoDate" value="{{ isset($quo) ? $quo->quo_date->format('Y-m-d') : date('Y-m-d') }}"></div>
                    <div class="fg"><label>Berlaku Hingga <span class="req">*</span></label>
                        <input type="date" id="validUntil" value="{{ isset($quo) ? $quo->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+14 days')) }}"></div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="card">
                <div class="card-hdr green">💰 Ringkasan Harga</div>
                <div class="card-bd">
                    <div class="sum-box">
                        <div class="sr"><span style="color:#6b7280">Subtotal</span><span id="dSub" style="font-weight:600">Rp 0</span></div>
                        <div class="sr" style="align-items:center;">
                            <span style="color:#6b7280">Diskon Global (Rp)</span>
                            <input type="number" id="discGlobal" value="{{ $quo->discount_global ?? 0 }}" min="0" style="width:100px;font-size:.78rem;" oninput="hitung()">
                        </div>
                        <div class="sr" style="align-items:center;margin-top:6px;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="color:#6b7280;font-size:.85rem;">PPN</span>
                                <label class="sw"><input type="checkbox" id="ppnOn" {{ isset($quo) && $quo->ppn_enabled ? 'checked' : '' }} onchange="hitung()"><span class="sl"></span></label>
                                <select id="ppnRate" style="width:60px;font-size:.75rem;padding:3px;" onchange="hitung()">
                                    <option value="11" {{ isset($quo) && $quo->ppn_rate==11 ? 'selected':'' }}>11%</option>
                                    <option value="12" {{ isset($quo) && $quo->ppn_rate==12 ? 'selected':'' }}>12%</option>
                                    <option value="10" {{ isset($quo) && $quo->ppn_rate==10 ? 'selected':'' }}>10%</option>
                                </select>
                            </div>
                            <span id="dPpn" style="font-weight:600;color:#d97706;">Rp 0</span>
                        </div>
                        <div class="sr tot"><span>TOTAL</span><span id="dTotal">Rp 0</span></div>
                    </div>
                    <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
                        <a href="{{ route('admin.quotation.index') }}" class="btn-batal">Batal</a>
                        <button class="btn-save" id="btnSave" onclick="simpan()">
                            <i class="bi bi-save me-1"></i>{{ isset($quo) ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const EDIT_ID   = {{ isset($quo) ? $quo->id : 'null' }};
const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content || '';
const PRODUCTS  = @json($products ?? []);
const EDIT_ITEMS= @json(isset($quo) ? $quo->items : []);
let rowCount = 0;

document.addEventListener('DOMContentLoaded', () => {
    if (EDIT_ITEMS.length) EDIT_ITEMS.forEach(i => tambahItem(i));
    else tambahItem();
    hitung();
});

function tambahItem(d = null) {
    const id  = ++rowCount;
    const tr  = document.createElement('tr');
    tr.id     = 'r' + id;
    const opts = PRODUCTS.map(p =>
        `<option value="${p.nama_produk}" data-h="${p.harga_jual||0}"
            ${d && d.nama_item===p.nama_produk?'selected':''}>${p.nama_produk}</option>`
    ).join('');
    const sats = ['unit','pcs','set','meter','roll','box','buah'];
    tr.innerHTML = `
        <td>
            <select onchange="pilihProduk(${id},this)" style="width:100%;margin-bottom:3px;">
                <option value="">-- Dari Produk --</option>${opts}
            </select>
            <input type="text" id="n${id}" placeholder="Nama item..." value="${d?d.nama_item:''}">
        </td>
        <td><input type="text" id="d${id}" placeholder="Opsional" value="${d?d.deskripsi||'':''}"></td>
        <td><input type="number" id="q${id}" value="${d?d.qty:1}" min="1" oninput="hr(${id})"></td>
        <td><select id="s${id}">${sats.map(s=>`<option value="${s}" ${d&&d.satuan===s?'selected':''}>${s}</option>`).join('')}</select></td>
        <td><input type="number" id="h${id}" value="${d?d.harga_satuan:0}" min="0" oninput="hr(${id})"></td>
        <td><input type="number" id="dc${id}" value="${d?d.discount:0}" min="0" max="100" oninput="hr(${id})"></td>
        <td style="text-align:right;font-weight:700;" id="sub${id}">Rp 0</td>
        <td><button class="btn-del" onclick="document.getElementById('r${id}').remove();hitung()">🗑</button></td>`;
    document.getElementById('itemsBody').appendChild(tr);
    hr(id);
}

function pilihProduk(id, sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('n'+id).value = opt.value;
        document.getElementById('h'+id).value = opt.dataset.h || 0;
        hr(id);
    }
}

function hr(id) {
    const q  = parseFloat(document.getElementById('q'+id)?.value)||0;
    const h  = parseFloat(document.getElementById('h'+id)?.value)||0;
    const dc = parseFloat(document.getElementById('dc'+id)?.value)||0;
    const s  = h * q * (1 - dc/100);
    const el = document.getElementById('sub'+id);
    if(el) el.textContent = 'Rp ' + fmt(s);
    hitung();
}

function hitung() {
    let sub = 0;
    document.querySelectorAll('#itemsBody tr').forEach(tr => {
        const id = tr.id.replace('r','');
        const q  = parseFloat(document.getElementById('q'+id)?.value)||0;
        const h  = parseFloat(document.getElementById('h'+id)?.value)||0;
        const dc = parseFloat(document.getElementById('dc'+id)?.value)||0;
        sub += h * q * (1-dc/100);
    });
    const dg   = parseFloat(document.getElementById('discGlobal').value)||0;
    const afd  = Math.max(0, sub - dg);
    const ppnOn= document.getElementById('ppnOn').checked;
    const pct  = parseFloat(document.getElementById('ppnRate').value)||0;
    const ppn  = ppnOn ? afd * pct/100 : 0;
    document.getElementById('dSub').textContent  = 'Rp ' + fmt(sub);
    document.getElementById('dPpn').textContent  = ppnOn ? `+ Rp ${fmt(ppn)} (${pct}%)` : 'Rp 0';
    document.getElementById('dTotal').textContent= 'Rp ' + fmt(afd + ppn);
}

function kumpulItems() {
    const items = [];
    document.querySelectorAll('#itemsBody tr').forEach(tr => {
        const id   = tr.id.replace('r','');
        const nama = document.getElementById('n'+id)?.value?.trim();
        if (!nama) return;
        items.push({
            nama_item   : nama,
            deskripsi   : document.getElementById('d'+id)?.value||'',
            qty         : parseInt(document.getElementById('q'+id)?.value)||1,
            satuan      : document.getElementById('s'+id)?.value||'unit',
            harga_satuan: parseFloat(document.getElementById('h'+id)?.value)||0,
            discount    : parseFloat(document.getElementById('dc'+id)?.value)||0,
        });
    });
    return items;
}

async function simpan() {
    if (!document.getElementById('cName').value.trim()) return alert('⚠️ Nama customer wajib diisi!');
    const items = kumpulItems();
    if (!items.length) return alert('⚠️ Minimal satu item harus diisi!');

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Menyimpan...';

    const payload = {
        customer_name   : document.getElementById('cName').value,
        customer_phone  : document.getElementById('cPhone').value,
        customer_email  : document.getElementById('cEmail').value,
        customer_address: document.getElementById('cAddr').value,
        project_name    : document.getElementById('projName').value,
        quo_date        : document.getElementById('quoDate').value,
        valid_until     : document.getElementById('validUntil').value,
        ppn_enabled     : document.getElementById('ppnOn').checked,
        ppn_rate        : document.getElementById('ppnRate').value,
        discount_global : document.getElementById('discGlobal').value,
        notes           : document.getElementById('notes').value,
        terms           : document.getElementById('terms').value,
        items,
    };

    try {
        const url    = EDIT_ID ? `/admin/quotation/${EDIT_ID}` : '/admin/quotation';
        const method = EDIT_ID ? 'PUT' : 'POST';
        const res    = await fetch(url, {
            method,
            headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (data.success) window.location.href = `/admin/quotation/${data.id || ''}`;
        else alert('❌ ' + (data.message||'Gagal menyimpan'));
    } catch(e) {
        alert('❌ Error: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i>{{ isset($quo) ? "Update" : "Simpan" }}';
    }
}
function fmt(n){return Math.round(n).toLocaleString('id-ID');}
</script>
@endsection