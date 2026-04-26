{{-- =====================================================
     FILE: resources/views/penjualan/form-publik.blade.php
     URL:  /penjualan-online/staff
     ===================================================== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Input Penjualan Online - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0fdf4; font-family: inherit; }

        .top-bar {
            background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
            color: white; padding: 16px 20px;
        }
        .top-bar .brand { font-size: 1.2rem; font-weight: 800; }
        .top-bar .sub   { font-size: 0.8rem; opacity: 0.85; }

        .form-card {
            border-radius: 14px; border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .section-label {
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: #065f46; margin-bottom: 14px;
            display: flex; align-items: center; gap: 8px;
        }
        .section-label::after {
            content: ''; flex: 1; height: 1px; background: #d1fae5;
        }
        .form-label { font-size: 0.8rem; font-weight: 600; color: #374151; }
        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }

        .total-box {
            background: linear-gradient(135deg, #f0fdf4, #d1fae5);
            border: 1.5px solid #6ee7b7; border-radius: 12px; padding: 16px;
        }
        .total-row {
            display: flex; justify-content: space-between;
            font-size: 0.875rem; padding: 4px 0; color: #374151;
        }
        .total-row.grand {
            border-top: 1.5px solid #6ee7b7; margin-top: 8px;
            padding-top: 10px; font-weight: 800; font-size: 1.05rem; color: #065f46;
        }

        .btn-submit {
            background: linear-gradient(135deg, #065f46, #10b981);
            border: none; font-weight: 700; font-size: 1rem;
            padding: 14px; border-radius: 10px;
            box-shadow: 0 4px 14px rgba(16,185,129,0.35);
            transition: all 0.2s;
        }
        .btn-submit:hover { transform: translateY(-1px); filter: brightness(1.05); }

        /* Sukses overlay */
        #successScreen {
            display: none; position: fixed; inset: 0;
            background: rgba(240,253,244,0.97); z-index: 9999;
            flex-direction: column; align-items: center;
            justify-content: center; text-align: center; padding: 24px;
        }
        #successScreen.show { display: flex; }
        .success-kode {
            background: white; border: 2px solid #d1fae5;
            border-radius: 12px; padding: 14px 32px; margin-top: 20px;
        }
        .success-kode strong { display: block; font-size: 1.5rem; color: #10b981; letter-spacing: 1px; }
    </style>
</head>
<body>

{{-- TOP BAR --}}
<div class="top-bar d-flex justify-content-between align-items-center">
    <div>
        <div class="brand">🏠 TechStore</div>
        <div class="sub">Form Input Penjualan Online</div>
    </div>
    <span class="badge" style="background:rgba(255,255,255,0.2);font-size:0.75rem;">🔒 Link Aman</span>
</div>

{{-- TOKEN BAR DIHAPUS --}}

{{-- MAIN --}}
<div class="container py-4" style="max-width:680px;">

    {{-- INTRO --}}
    <div class="alert d-flex gap-3 align-items-start mb-4"
         style="background:white;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <div style="font-size:2rem;">📦</div>
        <div>
            <div class="fw-bold">Form Input Penjualan Online</div>
            <div class="text-muted small">Isi data transaksi dengan lengkap. Data akan langsung masuk ke sistem keuangan TechStore sebagai pemasukan.</div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="form-card card">
        <div class="card-body p-4">

            {{-- SECTION: TRANSAKSI --}}
            <div class="section-label"><i class="bi bi-receipt"></i> Informasi Transaksi</div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="inputTanggal">
                </div>
                <div class="col-6">
                    <label class="form-label">Platform <span class="text-danger">*</span></label>
                    <select class="form-select" id="inputPlatform">
                        <option value="">-- Pilih --</option>
                        <option value="Tokopedia">🟠 Tokopedia</option>
                        <option value="Shopee">🔴 Shopee</option>
                        <option value="TikTok Shop">🟢 TikTok Shop</option>
                        <option value="Lazada">🟣 Lazada</option>
                        <option value="Bukalapak">🔵 Bukalapak</option>
                        <option value="Website">🌐 Website</option>
                        <option value="Instagram">📸 Instagram</option>
                        <option value="WhatsApp">📱 WhatsApp</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">No. Order / Resi</label>
                    <input type="text" class="form-control" id="inputNoOrder"
                           placeholder="Contoh: INV-20260425-001 / TKP-12345">
                </div>
            </div>

            {{-- SECTION: DETAIL PENJUALAN --}}
            <div class="section-label"><i class="bi bi-cart3"></i> Detail Penjualan</div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Keterangan / Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="inputDeskripsi"
                           placeholder="Contoh: Penjualan Tokopedia - Router WiFi AC1200 x2">
                </div>
                <div class="col-6">
                    <label class="form-label">Nama Pembeli</label>
                    <input type="text" class="form-control" id="inputPihak"
                           placeholder="Nama customer">
                </div>
                <div class="col-6">
                    <label class="form-label">Metode Bayar <span class="text-danger">*</span></label>
                    <select class="form-select" id="inputMetode">
                        <option value="transfer">🏦 Transfer Bank</option>
                        <option value="cash">💵 Cash / COD</option>
                        <option value="qris">📱 QRIS</option>
                        <option value="kartu_kredit">💳 Kartu Kredit</option>
                    </select>
                </div>
            </div>

            {{-- SECTION: JUMLAH --}}
            <div class="section-label"><i class="bi bi-cash-coin"></i> Jumlah & Status</div>
            <div class="row g-3 mb-4">
                <div class="col-7">
                    <label class="form-label">Total Penjualan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-lg fw-bold"
                           id="inputJumlah" placeholder="0" min="1" oninput="updateRingkasan()"
                           style="font-size:1.1rem;color:#065f46;">
                </div>
                <div class="col-5">
                    <label class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="inputStatus">
                        <option value="lunas">✅ Lunas</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="batal">❌ Batal</option>
                    </select>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="total-box mb-4">
                <div class="total-row">
                    <span>Platform</span>
                    <span id="rsm-platform" class="fw-semibold">-</span>
                </div>
                <div class="total-row">
                    <span>Status Bayar</span>
                    <span id="rsm-status" class="fw-semibold">-</span>
                </div>
                <div class="total-row grand">
                    <span>TOTAL</span>
                    <span id="rsm-jumlah">Rp 0</span>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="section-label"><i class="bi bi-pencil"></i> Catatan</div>
            <div class="mb-4">
                <textarea class="form-control" id="inputCatatan" rows="2"
                          placeholder="Catatan tambahan (opsional)..."></textarea>
            </div>

            {{-- SUBMIT --}}
            <button type="button" class="btn btn-success btn-submit w-100 text-white" onclick="kirimData()">
                <i class="bi bi-send-fill me-2"></i>Kirim Data Penjualan
            </button>
            <p class="text-center text-muted small mt-2">
                <i class="bi bi-shield-check me-1"></i>Data langsung masuk ke sistem keuangan TechStore
            </p>

        </div>
    </div>
</div>

{{-- SUCCESS SCREEN --}}
<div id="successScreen">
    <div style="font-size:4rem;">🎉</div>
    <h2 class="fw-bold text-success mt-2">Berhasil Dikirim!</h2>
    <p class="text-muted" style="max-width:320px;">
        Data penjualan sudah masuk ke sistem keuangan TechStore. Terima kasih!
    </p>
    <div class="success-kode">
        Kode Transaksi:
        <strong id="kodeHasil">-</strong>
    </div>
    <button class="btn btn-outline-success mt-4" onclick="resetForm()">
        <i class="bi bi-plus-circle me-1"></i>Input Transaksi Lagi
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Set default tanggal hari ini
document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];

// Update ringkasan realtime
document.getElementById('inputPlatform').addEventListener('change', updateRingkasan);
document.getElementById('inputStatus').addEventListener('change', updateRingkasan);

function updateRingkasan() {
    const platform = document.getElementById('inputPlatform').value || '-';
    const status   = document.getElementById('inputStatus').value || '-';
    const jumlah   = parseFloat(document.getElementById('inputJumlah').value) || 0;

    const PLATFORM_ICON = {
        'Tokopedia':'🟠','Shopee':'🔴','TikTok Shop':'🟢',
        'Lazada':'🟣','Bukalapak':'🔵','Website':'🌐',
        'Instagram':'📸','WhatsApp':'📱','Lainnya':'📦',
    };
    const STATUS_LABEL = { lunas:'✅ Lunas', pending:'⏳ Pending', batal:'❌ Batal' };

    document.getElementById('rsm-platform').textContent = platform !== '-'
        ? (PLATFORM_ICON[platform] || '') + ' ' + platform : '-';
    document.getElementById('rsm-status').textContent = STATUS_LABEL[status] || status;
    document.getElementById('rsm-jumlah').textContent  = 'Rp ' + Math.round(jumlah).toLocaleString('id-ID');
}

// Kirim data ke server
async function kirimData() {
    const platform  = document.getElementById('inputPlatform').value;
    const jumlah    = document.getElementById('inputJumlah').value;
    const tanggal   = document.getElementById('inputTanggal').value;
    const deskripsi = document.getElementById('inputDeskripsi').value.trim();
    const metode    = document.getElementById('inputMetode').value;

    if (!platform)  return showAlert('❌ Platform wajib dipilih!', 'danger');
    if (!jumlah || parseFloat(jumlah) < 1) return showAlert('❌ Jumlah penjualan wajib diisi!', 'danger');
    if (!tanggal)   return showAlert('❌ Tanggal wajib diisi!', 'danger');
    if (!deskripsi) return showAlert('❌ Keterangan / nama produk wajib diisi!', 'danger');

    const btn = document.querySelector('.btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    try {
        const res = await fetch('/penjualan-online/simpan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept'      : 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({
                platform      : platform,
                jumlah        : jumlah,
                tanggal       : tanggal,
                deskripsi     : deskripsi,
                metode_bayar  : metode,
                no_order      : document.getElementById('inputNoOrder').value,
                pihak_terkait : document.getElementById('inputPihak').value,
                status        : document.getElementById('inputStatus').value,
                catatan       : document.getElementById('inputCatatan').value,
            }),
        });

        const data = await res.json();
        if (data.success) {
            document.getElementById('kodeHasil').textContent = data.kode;
            document.getElementById('successScreen').classList.add('show');
        } else {
            showAlert('❌ ' + (data.message || 'Gagal menyimpan'), 'danger');
        }
    } catch (e) {
        showAlert('❌ Error: ' + e.message, 'danger');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Kirim Data Penjualan';
    }
}

function resetForm() {
    document.getElementById('successScreen').classList.remove('show');
    ['inputNoOrder','inputDeskripsi','inputPihak','inputJumlah','inputCatatan'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('inputPlatform').value = '';
    document.getElementById('inputMetode').value   = 'transfer';
    document.getElementById('inputStatus').value   = 'lunas';
    document.getElementById('inputTanggal').value  = new Date().toISOString().split('T')[0];
    updateRingkasan();
}

function showAlert(msg, type) {
    const el = document.createElement('div');
    el.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    el.style.zIndex = 9999;
    el.style.minWidth = '260px';
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}
</script>
</body>
</html>