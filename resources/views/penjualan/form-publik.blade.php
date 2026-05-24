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
    <title>Input Penjualan Online — TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --green:      #16a34a;
            --green-dark: #14532d;
            --green-mid:  #15803d;
            --green-pale: #f0fdf4;
            --green-soft: #dcfce7;
            --green-line: #bbf7d0;
            --text:       #111827;
            --text-mid:   #374151;
            --text-muted: #6b7280;
            --border:     #e5e7eb;
            --white:      #ffffff;
        }

        * { box-sizing: border-box; }
        body {
            background: #f3f4f6;
            font-family: 'Sora', sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--green-dark);
            padding: 0 20px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-brand {
            font-size: 15px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-brand .dot {
            width: 7px; height: 7px;
            background: #4ade80;
            border-radius: 50%;
        }
        .topbar-right {
            font-size: 11px;
            color: #86efac;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
            padding: 26px 20px 52px;
            position: relative;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 28px;
            background: #f3f4f6;
            border-radius: 28px 28px 0 0;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: #bbf7d0;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .hero h1 {
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.4px;
            margin-bottom: 5px;
        }
        .hero p {
            font-size: 12.5px;
            color: #86efac;
            line-height: 1.55;
            margin: 0;
        }

        /* ── WRAPPER ── */
        .form-wrapper {
            max-width: 660px;
            margin: 0 auto;
            padding: 0 14px 40px;
            width: 100%;
        }

        /* ── CARD ── */
        .fcard {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 12px;
            animation: slideUp 0.35s ease both;
        }
        .fcard:nth-child(1) { animation-delay: 0.04s; }
        .fcard:nth-child(2) { animation-delay: 0.08s; }
        .fcard:nth-child(3) { animation-delay: 0.12s; }
        .fcard:nth-child(4) { animation-delay: 0.16s; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fcard-head {
            padding: 13px 18px;
            background: var(--green-pale);
            border-bottom: 1px solid #d1fae5;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .fcard-head-icon {
            width: 30px; height: 30px;
            background: var(--green-soft);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .fcard-head-title {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--green-dark);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .fcard-body { padding: 18px; }

        /* ── FIELDS ── */
        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 5px;
        }
        .form-control,
        .form-select {
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 9px 13px;
            color: var(--text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.13);
            outline: none;
        }
        .form-control::placeholder { color: #9ca3af; font-size: 12px; }

        /* amount field */
        .amount-wrap { position: relative; }
        .amount-prefix {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            pointer-events: none;
            font-family: 'JetBrains Mono', monospace;
        }
        .amount-wrap .form-control {
            padding-left: 36px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--green-dark);
        }

        /* ── RINGKASAN BOX ── */
        .ringkasan-box {
            background: var(--green-pale);
            border: 1.5px solid var(--green-line);
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 4px;
        }
        .ringkasan-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 12.5px;
            border-bottom: 1px dashed #d1fae5;
        }
        .ringkasan-row:last-child { border-bottom: none; }
        .ringkasan-row .rlabel { color: var(--text-muted); font-weight: 500; }
        .ringkasan-row .rval   { font-weight: 600; color: var(--text-mid); }
        .ringkasan-row.total {
            padding-top: 10px;
            margin-top: 4px;
            border-top: 2px solid #86efac !important;
        }
        .ringkasan-row.total .rlabel {
            font-size: 13px;
            font-weight: 800;
            color: var(--green-dark);
        }
        .ringkasan-row.total .rval {
            font-size: 17px;
            font-weight: 800;
            color: var(--green-dark);
            font-family: 'JetBrains Mono', monospace;
        }

        /* ── SUBMIT ── */
        .btn-kirim {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--green-mid), var(--green));
            border: none;
            border-radius: 10px;
            color: white;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(22,163,74,0.35);
            transition: all 0.2s;
            letter-spacing: 0.2px;
        }
        .btn-kirim:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(22,163,74,0.45);
        }
        .btn-kirim:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        .trust-note {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* ── SUCCESS OVERLAY ── */
        #successScreen {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        #successScreen.show { display: flex; }
        .success-card {
            background: white;
            border-radius: 16px;
            padding: 30px 26px;
            text-align: center;
            max-width: 340px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: popIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85); }
            to   { opacity: 1; transform: scale(1); }
        }
        .success-icon-wrap {
            width: 60px; height: 60px;
            background: var(--green-soft);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            margin: 0 auto 14px;
        }
        .success-card h3 {
            font-size: 17px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 7px;
        }
        .success-card p {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 5px;
        }
        .success-kode {
            display: inline-block;
            background: var(--green-pale);
            border: 1.5px solid var(--green-line);
            color: var(--green-dark);
            padding: 7px 20px;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            font-weight: 700;
            margin: 10px 0 18px;
        }
        .btn-lagi {
            width: 100%;
            padding: 11px;
            background: var(--green-soft);
            color: var(--green-dark);
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            transition: background 0.15s;
        }
        .btn-lagi:hover { background: var(--green-line); }

        /* ── TOAST ── */
        .toast-notif {
            position: fixed;
            bottom: 20px; right: 20px;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            z-index: 9998;
            animation: toastIn 0.3s ease;
            max-width: 300px;
        }
        .toast-notif.danger  { background: #ef4444; }
        .toast-notif.success { background: var(--green); }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 16px;
            font-size: 11px;
            color: #9ca3af;
        }

        @media (max-width: 480px) {
            .hero { padding: 20px 16px 48px; }
            .hero h1 { font-size: 18px; }
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-brand">
        <div class="dot"></div>
        TechStore
    </div>
    <div class="topbar-right">
        <i class="bi bi-shield-lock-fill"></i> Akses Staff
    </div>
</div>

{{-- HERO --}}
<div class="hero">
    <div style="max-width:640px;margin:0 auto;">
        <div class="hero-badge">🔐 {{ $label ?? 'Form Staff' }}</div>
        <h1>Input Penjualan Online</h1>
        <p>Isi data transaksi dengan lengkap. Data akan langsung masuk ke sistem keuangan TechStore sebagai pemasukan.</p>
    </div>
</div>

{{-- FORM --}}
<div class="form-wrapper">

    {{-- CARD 1: INFORMASI TRANSAKSI --}}
    <div class="fcard">
        <div class="fcard-head">
            <div class="fcard-head-icon">📅</div>
            <div class="fcard-head-title">Informasi Transaksi</div>
        </div>
        <div class="fcard-body">
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="inputTanggal">
                </div>
                <div class="col-6">
                    <label class="form-label">Platform <span class="text-danger">*</span></label>
                    <select class="form-select" id="inputPlatform" onchange="updateRingkasan()">
                        <option value="">-- Pilih Platform --</option>
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
        </div>
    </div>

    {{-- CARD 2: DETAIL PENJUALAN --}}
    <div class="fcard">
        <div class="fcard-head">
            <div class="fcard-head-icon">📦</div>
            <div class="fcard-head-title">Detail Penjualan</div>
        </div>
        <div class="fcard-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Keterangan / Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="inputDeskripsi"
                           placeholder="Contoh: Penjualan Tokopedia — Router WiFi AC1200 x2">
                </div>
                <div class="col-6">
                    <label class="form-label">Nama Pembeli</label>
                    <input type="text" class="form-control" id="inputPihak" placeholder="Nama customer">
                </div>
                <div class="col-6">
                    <label class="form-label">Metode Bayar <span class="text-danger">*</span></label>
                    <select class="form-select" id="inputMetode" onchange="updateRingkasan()">
                        <option value="transfer">🏦 Transfer Bank</option>
                        <option value="cash">💵 Cash / COD</option>
                        <option value="qris">📱 QRIS</option>
                        <option value="kartu_kredit">💳 Kartu Kredit</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD 3: JUMLAH & STATUS --}}
    <div class="fcard">
        <div class="fcard-head">
            <div class="fcard-head-icon">💰</div>
            <div class="fcard-head-title">Jumlah & Status</div>
        </div>
        <div class="fcard-body">
            <div class="row g-3 mb-3">
                <div class="col-7">
                    <label class="form-label">Total Penjualan (Rp) <span class="text-danger">*</span></label>
                    <div class="amount-wrap">
                        <span class="amount-prefix">Rp</span>
                        <input type="number" class="form-control" id="inputJumlah"
                               placeholder="0" min="1" oninput="updateRingkasan()">
                    </div>
                </div>
                <div class="col-5">
                    <label class="form-label">Status Pembayaran</label>
                    <select class="form-select" id="inputStatus" onchange="updateRingkasan()">
                        <option value="lunas">✅ Lunas</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="batal">❌ Batal</option>
                    </select>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="ringkasan-box">
                <div class="ringkasan-row">
                    <span class="rlabel">Platform</span>
                    <span class="rval" id="rsm-platform">—</span>
                </div>
                <div class="ringkasan-row">
                    <span class="rlabel">Metode Bayar</span>
                    <span class="rval" id="rsm-metode">Transfer Bank</span>
                </div>
                <div class="ringkasan-row">
                    <span class="rlabel">Status</span>
                    <span class="rval" id="rsm-status">✅ Lunas</span>
                </div>
                <div class="ringkasan-row total">
                    <span class="rlabel">TOTAL</span>
                    <span class="rval" id="rsm-jumlah">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD 4: CATATAN --}}
    <div class="fcard">
        <div class="fcard-head">
            <div class="fcard-head-icon">📝</div>
            <div class="fcard-head-title">Catatan</div>
        </div>
        <div class="fcard-body">
            <textarea class="form-control" id="inputCatatan" rows="3"
                      placeholder="Catatan tambahan (opsional)..."></textarea>
        </div>
    </div>

    {{-- SUBMIT --}}
    <button class="btn-kirim" onclick="kirimData()" id="btnKirim">
        <i class="bi bi-send-fill"></i>
        <span id="btnText">Kirim Data Penjualan</span>
    </button>
    <div class="trust-note">
        <i class="bi bi-shield-check"></i>
        Data langsung masuk ke sistem keuangan TechStore
    </div>

</div>{{-- end form-wrapper --}}

{{-- SUCCESS OVERLAY --}}
<div id="successScreen">
    <div class="success-card">
        <div class="success-icon-wrap">✅</div>
        <h3>Berhasil Dikirim!</h3>
        <p>Transaksi penjualan berhasil dicatat ke sistem keuangan TechStore.</p>
        <div class="success-kode" id="kodeHasil">TRX-2026-0001</div>
        <p style="font-size:11px;color:#9ca3af;margin-bottom:16px;">Simpan kode ini sebagai referensi transaksi.</p>
        <button class="btn-lagi" onclick="resetForm()">
            <i class="bi bi-plus-circle me-1"></i> Input Penjualan Lagi
        </button>
    </div>
</div>

<footer>© 2026 TechStore — PT. MJA Teknologi</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Default tanggal hari ini
document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];

const METODE_LABEL = { transfer: '🏦 Transfer Bank', cash: '💵 Cash / COD', qris: '📱 QRIS', kartu_kredit: '💳 Kartu Kredit' };
const STATUS_LABEL = { lunas: '✅ Lunas', pending: '⏳ Pending', batal: '❌ Batal' };
const PLATFORM_ICON = {
    Tokopedia: '🟠', Shopee: '🔴', 'TikTok Shop': '🟢',
    Lazada: '🟣', Bukalapak: '🔵', Website: '🌐',
    Instagram: '📸', WhatsApp: '📱', Lainnya: '📦',
};

function updateRingkasan() {
    const platform = document.getElementById('inputPlatform').value;
    const metode   = document.getElementById('inputMetode').value;
    const status   = document.getElementById('inputStatus').value;
    const jumlah   = parseFloat(document.getElementById('inputJumlah').value) || 0;

    document.getElementById('rsm-platform').textContent = platform
        ? (PLATFORM_ICON[platform] || '') + ' ' + platform : '—';
    document.getElementById('rsm-metode').textContent  = METODE_LABEL[metode] || '—';
    document.getElementById('rsm-status').textContent  = STATUS_LABEL[status] || '—';
    document.getElementById('rsm-jumlah').textContent  = 'Rp ' + Math.round(jumlah).toLocaleString('id-ID');
}

async function kirimData() {
    const platform  = document.getElementById('inputPlatform').value;
    const jumlah    = document.getElementById('inputJumlah').value;
    const tanggal   = document.getElementById('inputTanggal').value;
    const deskripsi = document.getElementById('inputDeskripsi').value.trim();
    const metode    = document.getElementById('inputMetode').value;

    if (!platform)                          return showToast('Platform wajib dipilih!', 'danger');
    if (!deskripsi)                         return showToast('Keterangan / nama produk wajib diisi!', 'danger');
    if (!jumlah || parseFloat(jumlah) < 1) return showToast('Jumlah penjualan wajib diisi!', 'danger');
    if (!tanggal)                           return showToast('Tanggal wajib diisi!', 'danger');

    const btn = document.getElementById('btnKirim');
    document.getElementById('btnText').textContent = 'Menyimpan...';
    btn.disabled = true;

    try {
        const res = await fetch('/penjualan-online/simpan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
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
            showToast(data.message || 'Gagal menyimpan data.', 'danger');
        }
    } catch (e) {
        showToast('Koneksi bermasalah. Coba lagi.', 'danger');
    } finally {
        document.getElementById('btnText').textContent = 'Kirim Data Penjualan';
        btn.disabled = false;
    }
}

function resetForm() {
    ['inputNoOrder','inputDeskripsi','inputPihak','inputJumlah','inputCatatan']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('inputPlatform').value = '';
    document.getElementById('inputMetode').value   = 'transfer';
    document.getElementById('inputStatus').value   = 'lunas';
    document.getElementById('inputTanggal').value  = new Date().toISOString().split('T')[0];
    document.getElementById('successScreen').classList.remove('show');
    updateRingkasan();
}

function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = 'toast-notif ' + type;
    el.innerHTML = (type === 'danger' ? '❌ ' : '✅ ') + msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

updateRingkasan();
</script>
</body>
</html>