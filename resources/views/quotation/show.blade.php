{{--
    resources/views/quotation/show.blade.php
    Halaman ini dibuka customer lewat link unik
    URL: /penawaran/{token}
--}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Penawaran {{ $quo->quo_number }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --navy:   #0f2342;
    --blue:   #1d5dbf;
    --blue-l: #3b82f6;
    --green:  #059669;
    --red:    #dc2626;
    --amber:  #d97706;
    --slate:  #64748b;
    --border: #e2e8f0;
    --bg:     #f0f4f8;
    --white:  #ffffff;
    --radius: 16px;
    --shadow: 0 4px 24px rgba(0,0,0,.08);
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: #1e293b;
    min-height: 100vh;
    padding: 24px 16px 80px;
}

.wrap { max-width: 760px; margin: 0 auto; }

/* ===== STATUS BANNER ===== */
.status-banner {
    padding: 13px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 700;
    font-size: .84rem;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideDown .4s ease;
}
@keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
.banner-sent     { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
.banner-approved { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.banner-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.banner-revised  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.banner-expired  { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
.banner-converted{ background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

/* ===== CARD ===== */
.card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 16px;
}

/* ===== HEADER ===== */
.quo-header {
    background: linear-gradient(135deg, var(--navy) 0%, #1a4a9e 60%, var(--blue) 100%);
    padding: 32px;
    color: white;
    position: relative;
    overflow: hidden;
}
.quo-header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,.04);
    border-radius: 50%;
}
.quo-header::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 40%;
    width: 160px; height: 160px;
    background: rgba(255,255,255,.03);
    border-radius: 50%;
}
.company-name { font-size: .95rem; font-weight: 800; opacity: .9; margin-bottom: 3px; }
.company-sub  { font-size: .75rem; opacity: .5; letter-spacing: .04em; }
.quo-title    { font-size: 1.5rem; font-weight: 800; margin: 22px 0 6px; letter-spacing: -.02em; }
.quo-number   { font-family: 'JetBrains Mono', monospace; font-size: .82rem; opacity: .7; letter-spacing: .05em; }

.meta-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-top: 1px solid rgba(255,255,255,.12);
    margin-top: 22px;
}
.meta-item { padding: 14px 0; }
.meta-item:not(:last-child) { border-right: 1px solid rgba(255,255,255,.12); padding-right: 20px; }
.meta-item:not(:first-child) { padding-left: 20px; }
.meta-label { font-size: .65rem; opacity: .5; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
.meta-value { font-size: .88rem; font-weight: 700; }
.meta-value.expired { color: #fca5a5; }

/* ===== SECTION ===== */
.info-section,
.items-section,
.summary-section,
.notes-section { padding: 24px 32px; }
.section-title {
    font-size: .68rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 16px;
}

/* ===== CUSTOMER INFO ===== */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.info-item label { font-size: .68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; display: block; margin-bottom: 5px; }
.info-item span  { font-size: .88rem; font-weight: 600; color: #1e293b; }

/* ===== ITEMS TABLE ===== */
table.items-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.items-table thead th {
    background: #f8fafc;
    padding: 10px 12px;
    text-align: left;
    font-size: .67rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 2px solid var(--border);
}
.items-table tbody td { padding: 13px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
.items-table tbody tr:last-child td { border-bottom: none; }
.items-table tbody tr:hover td { background: #fafbfc; }
.item-name { font-weight: 700; color: #1e293b; }
.item-desc { font-size: .74rem; color: #94a3b8; margin-top: 4px; line-height: 1.5; }
.text-right  { text-align: right !important; }
.text-center { text-align: center !important; }

/* ===== SUMMARY ===== */
.summary-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px 24px;
    border: 1px solid var(--border);
}
.sum-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; font-size: .85rem; }
.sum-row.divider { border-top: 1.5px solid var(--border); margin-top: 8px; padding-top: 14px; }
.sum-row.total { font-size: 1.1rem; font-weight: 900; color: var(--navy); margin-top: 4px; }
.sum-label { color: #64748b; }
.sum-value { font-weight: 700; }
.ppn-badge { background: #dbeafe; color: #1e40af; font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; }

/* ===== NOTES / TERMS ===== */
.notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 16px 18px; font-size: .82rem; color: #92400e; line-height: 1.75; }
.terms-box { background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 16px 18px; font-size: .78rem; color: #64748b; line-height: 1.85; white-space: pre-line; }

/* ===== CTA SECTION ===== */
.cta-section {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 28px 32px;
    margin-bottom: 16px;
}
.cta-title { font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 6px; }
.cta-sub   { font-size: .82rem; color: #94a3b8; margin-bottom: 22px; }
.cta-grid  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
.cta-btn {
    padding: 16px 12px;
    border-radius: 12px;
    border: none;
    font-size: .82rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all .2s cubic-bezier(.34,1.56,.64,1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.cta-btn:hover:not(:disabled) { transform: translateY(-3px) scale(1.02); }
.cta-btn:active:not(:disabled) { transform: scale(.97); }
.cta-btn:disabled { opacity: .45; cursor: not-allowed; }
.cta-btn .ico { font-size: 1.5rem; }
.btn-approve { background: linear-gradient(135deg, #065f46, #10b981); color: white; box-shadow: 0 6px 18px rgba(16,185,129,.3); }
.btn-reject  { background: #fff5f5; color: #dc2626; border: 2px solid #fca5a5; }
.btn-revise  { background: #fefce8; color: #92400e; border: 2px solid #fde68a; }

.notes-input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 12px 14px;
    font-size: .82rem;
    font-family: inherit;
    outline: none;
    margin-top: 16px;
    resize: vertical;
    min-height: 84px;
    transition: border-color .2s, box-shadow .2s;
    color: #1e293b;
}
.notes-input:focus { border-color: var(--blue-l); box-shadow: 0 0 0 3px rgba(59,130,246,.1); }

/* ===== RESPONDED CARD ===== */
.responded-card { text-align: center; padding: 48px 32px; }
.responded-icon  { font-size: 4rem; margin-bottom: 18px; animation: pop .5s cubic-bezier(.34,1.56,.64,1); }
@keyframes pop { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.responded-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 10px; }
.responded-sub   { font-size: .85rem; color: #64748b; line-height: 1.75; }
.responded-note  { margin-top: 14px; padding: 12px 16px; border-radius: 10px; font-size: .78rem; }

/* ===== FOOTER ===== */
.quo-footer { text-align: center; font-size: .72rem; color: #b0bec5; margin-top: 28px; line-height: 1.8; }

/* ===== MODAL ===== */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15,35,66,.55);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    z-index: 1000;
    padding: 20px;
    animation: fadeIn .2s ease;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.modal-box {
    background: var(--white);
    border-radius: 20px;
    padding: 32px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 24px 60px rgba(0,0,0,.2);
    animation: modalPop .3s cubic-bezier(.34,1.56,.64,1);
    text-align: center;
}
@keyframes modalPop { from { transform: scale(.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-icon  { font-size: 3rem; margin-bottom: 14px; }
.modal-title { font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
.modal-desc  { font-size: .83rem; color: #64748b; line-height: 1.65; margin-bottom: 24px; }
.modal-btns  { display: flex; gap: 10px; }
.modal-btn {
    flex: 1; padding: 12px; border-radius: 10px; border: none;
    font-size: .85rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: all .15s;
}
.modal-btn:hover { opacity: .85; }
.modal-cancel { background: #f1f5f9; color: #64748b; }
.modal-confirm-approve { background: linear-gradient(135deg, #065f46, #10b981); color: white; }
.modal-confirm-reject  { background: linear-gradient(135deg, #991b1b, #dc2626); color: white; }
.modal-confirm-revised { background: linear-gradient(135deg, #92400e, #f59e0b); color: white; }

/* ===== TOAST ===== */
.toast-container { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 2000; display: flex; flex-direction: column; gap: 8px; align-items: center; pointer-events: none; }
.toast {
    padding: 12px 20px;
    border-radius: 100px;
    font-size: .83rem;
    font-weight: 700;
    color: white;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    animation: toastIn .35s cubic-bezier(.34,1.56,.64,1);
    white-space: nowrap;
}
@keyframes toastIn { from { opacity: 0; transform: translateY(20px) scale(.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
.toast-success { background: linear-gradient(135deg, #065f46, #10b981); }
.toast-error   { background: linear-gradient(135deg, #991b1b, #dc2626); }
.toast-info    { background: linear-gradient(135deg, #1e40af, #3b82f6); }

/* ===== SPINNER ===== */
.spinner {
    display: inline-block; width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.4);
    border-top-color: white;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    vertical-align: middle; margin-right: 6px;
}
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 560px) {
    .quo-header, .info-section, .items-section, .summary-section, .notes-section { padding-left: 20px; padding-right: 20px; }
    .cta-section { padding: 22px 20px; }
    .meta-grid  { grid-template-columns: 1fr; }
    .meta-item  { border-right: none !important; padding-left: 0 !important; border-bottom: 1px solid rgba(255,255,255,.1); }
    .info-grid  { grid-template-columns: 1fr; }
    .cta-grid   { grid-template-columns: 1fr; }
    .items-table thead th:nth-child(2),
    .items-table tbody td:nth-child(2) { display: none; }
    .modal-btns { flex-direction: column; }
}
</style>
</head>
<body>
<div class="wrap">

    {{-- STATUS BANNER --}}
    @if($quo->status === 'approved')
    <div class="status-banner banner-approved">✅ Penawaran ini sudah Anda setujui. Terima kasih!</div>
    @elseif($quo->status === 'rejected')
    <div class="status-banner banner-rejected">❌ Penawaran ini telah ditolak.</div>
    @elseif($quo->status === 'revised')
    <div class="status-banner banner-revised">🔄 Anda telah meminta revisi. Tim kami akan segera menghubungi Anda.</div>
    @elseif($quo->status === 'expired')
    <div class="status-banner banner-expired">⏰ Penawaran ini sudah kadaluarsa. Silakan hubungi kami untuk penawaran terbaru.</div>
    @elseif($quo->status === 'converted')
    <div class="status-banner banner-converted">📦 Penawaran ini sudah diproses menjadi Sales Order. Terima kasih!</div>
    @endif

    {{-- KARTU PENAWARAN --}}
    <div class="card">
        {{-- HEADER --}}
        <div class="quo-header">
            <div class="company-name">🏢 {{ config('app.name', 'Perusahaan Anda') }}</div>
            <div class="company-sub">Sistem Penawaran Digital</div>
            <div class="quo-title">Surat Penawaran Harga</div>
            <div class="quo-number">{{ $quo->quo_number }}</div>
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">Tanggal Penawaran</div>
                    <div class="meta-value">{{ $quo->quo_date->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Berlaku Hingga</div>
                    <div class="meta-value {{ $quo->is_expired ? 'expired' : '' }}">
                        {{ $quo->valid_until->format('d M Y') }}
                        @if($quo->is_expired) (Expired) @endif
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Total Penawaran</div>
                    <div class="meta-value">Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- INFO CUSTOMER --}}
        <div class="info-section">
            <div class="section-title">Ditujukan Kepada</div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nama</label>
                    <span>{{ $quo->customer_name }}</span>
                </div>
                @if($quo->project_name)
                <div class="info-item">
                    <label>Keperluan / Proyek</label>
                    <span>{{ $quo->project_name }}</span>
                </div>
                @endif
                @if($quo->customer_phone)
                <div class="info-item">
                    <label>No. HP</label>
                    <span>{{ $quo->customer_phone }}</span>
                </div>
                @endif
                @if($quo->customer_address)
                <div class="info-item">
                    <label>Alamat</label>
                    <span>{{ $quo->customer_address }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- DAFTAR ITEM --}}
        <div class="items-section">
            <div class="section-title">Rincian Item Penawaran</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Item / Produk</th>
                        <th class="text-center" style="width:8%">Qty</th>
                        <th class="text-center" style="width:8%">Sat</th>
                        <th class="text-right" style="width:15%">Harga Satuan</th>
                        @if($quo->items->where('discount', '>', 0)->count())
                        <th class="text-center" style="width:8%">Diskon</th>
                        @endif
                        <th class="text-right" style="width:15%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quo->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $item->nama_item }}</div>
                            @if($item->deskripsi)
                            <div class="item-desc">{{ $item->deskripsi }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-center">{{ $item->satuan }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        @if($quo->items->where('discount', '>', 0)->count())
                        <td class="text-center">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
                        @endif
                        <td class="text-right" style="font-weight:700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SUMMARY --}}
        <div class="summary-section">
            <div class="summary-box">
                <div class="sum-row">
                    <span class="sum-label">Subtotal</span>
                    <span class="sum-value">Rp {{ number_format($quo->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($quo->discount_global > 0)
                <div class="sum-row">
                    <span class="sum-label">Diskon</span>
                    <span class="sum-value" style="color:#ef4444;">- Rp {{ number_format($quo->discount_global, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($quo->ppn_enabled)
                <div class="sum-row">
                    <span class="sum-label">PPN <span class="ppn-badge">{{ $quo->ppn_rate }}%</span></span>
                    <span class="sum-value" style="color:#d97706;">+ Rp {{ number_format($quo->ppn_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="sum-row divider total">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- CATATAN --}}
        @if($quo->notes)
        <div class="notes-section" style="padding-top:0;">
            <div class="section-title">Catatan</div>
            <div class="notes-box">{{ $quo->notes }}</div>
        </div>
        @endif

        {{-- SYARAT & KETENTUAN --}}
        @if($quo->terms)
        <div class="notes-section" style="padding-top:0;">
            <div class="section-title">Syarat & Ketentuan</div>
            <div class="terms-box">{{ $quo->terms }}</div>
        </div>
        @endif
    </div>

    {{-- ===== CTA ===== --}}
    @if(in_array($quo->status, ['sent', 'draft']))
    <div class="cta-section" id="ctaSection">
        <div class="cta-title">Konfirmasi Penawaran Ini</div>
        <div class="cta-sub">Silakan pilih tindakan di bawah. Keputusan Anda akan langsung diterima tim kami.</div>
        <div class="cta-grid">
            <button class="cta-btn btn-approve" onclick="openModal('approved')">
                <span class="ico">✅</span>
                <span>Setuju &<br>Terima Penawaran</span>
            </button>
            <button class="cta-btn btn-revise" onclick="openModal('revised')">
                <span class="ico">🔄</span>
                <span>Minta<br>Revisi Harga</span>
            </button>
            <button class="cta-btn btn-reject" onclick="openModal('rejected')">
                <span class="ico">❌</span>
                <span>Tolak<br>Penawaran</span>
            </button>
        </div>
        <textarea class="notes-input" id="customerNotes" placeholder="Tulis catatan atau alasan di sini (opsional)..."></textarea>
    </div>

    @elseif($quo->status === 'approved')
    <div class="card responded-card">
        <div class="responded-icon">🎉</div>
        <div class="responded-title" style="color:#065f46;">Penawaran Diterima!</div>
        <div class="responded-sub">
            Terima kasih sudah menyetujui penawaran ini.<br>
            Tim kami akan segera menghubungi Anda untuk langkah selanjutnya.
            @if($quo->customer_notes)
            <div class="responded-note" style="background:#f0fdf4;color:#065f46;">
                Catatan Anda: <em>{{ $quo->customer_notes }}</em>
            </div>
            @endif
        </div>
    </div>

    @elseif($quo->status === 'rejected')
    <div class="card responded-card">
        <div class="responded-icon">📩</div>
        <div class="responded-title" style="color:#dc2626;">Penawaran Ditolak</div>
        <div class="responded-sub">
            Terima kasih atas konfirmasi Anda.<br>
            Jika ada yang bisa kami bantu, jangan ragu untuk menghubungi kami.
        </div>
    </div>

    @elseif($quo->status === 'revised')
    <div class="card responded-card">
        <div class="responded-icon">✉️</div>
        <div class="responded-title" style="color:#92400e;">Permintaan Revisi Terkirim</div>
        <div class="responded-sub">
            Tim kami akan meninjau permintaan revisi Anda dan menghubungi Anda segera.
            @if($quo->customer_notes)
            <div class="responded-note" style="background:#fefce8;color:#92400e;">
                Catatan Anda: <em>{{ $quo->customer_notes }}</em>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="quo-footer">
        Dokumen ini dibuat secara digital oleh sistem penawaran kami.<br>
        {{ config('app.name') }} &mdash; {{ now()->year }}
    </div>
</div>

{{-- ===== MODAL KONFIRMASI ===== --}}
<div class="modal-overlay" id="modalOverlay" style="display:none;" onclick="closeModalOutside(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-icon"  id="modalIcon"></div>
        <div class="modal-title" id="modalTitle"></div>
        <div class="modal-desc"  id="modalDesc"></div>
        <div class="modal-btns">
            <button class="modal-btn modal-cancel" onclick="closeModal()">Batal</button>
            <button class="modal-btn" id="modalConfirmBtn" onclick="confirmRespond()">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

{{-- ===== TOAST CONTAINER ===== --}}
<div class="toast-container" id="toastContainer"></div>

<script>
const TOKEN   = '{{ $quo->token }}';
const CSRF    = document.querySelector('meta[name="csrf-token"]')?.content || '';
let pendingAction = null;

// ===== MODAL =====
const modalConfig = {
    approved: {
        icon: '✅',
        title: 'Setujui Penawaran?',
        desc: 'Dengan menyetujui, tim kami akan segera memproses pesanan Anda.',
        btnClass: 'modal-confirm-approve',
        btnText: 'Ya, Saya Setuju',
    },
    rejected: {
        icon: '❌',
        title: 'Tolak Penawaran?',
        desc: 'Anda yakin ingin menolak penawaran ini? Tindakan ini tidak dapat dibatalkan.',
        btnClass: 'modal-confirm-reject',
        btnText: 'Ya, Tolak',
    },
    revised: {
        icon: '🔄',
        title: 'Minta Revisi Harga?',
        desc: 'Tim kami akan menghubungi Anda untuk mendiskusikan revisi yang diperlukan.',
        btnClass: 'modal-confirm-revised',
        btnText: 'Ya, Minta Revisi',
    },
};

function openModal(action) {
    pendingAction = action;
    const cfg = modalConfig[action];
    document.getElementById('modalIcon').textContent    = cfg.icon;
    document.getElementById('modalTitle').textContent   = cfg.title;
    document.getElementById('modalDesc').textContent    = cfg.desc;
    const btn = document.getElementById('modalConfirmBtn');
    btn.className = 'modal-btn ' + cfg.btnClass;
    btn.textContent = cfg.btnText;
    document.getElementById('modalOverlay').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    pendingAction = null;
}

function closeModalOutside(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

// ===== TOAST =====
function showToast(msg, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = msg;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity .3s, transform .3s';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

// ===== RESPOND =====
async function confirmRespond() {
    if (!pendingAction) return;
    const action = pendingAction;
    const notes  = document.getElementById('customerNotes')?.value || '';

    closeModal();

    // Disable tombol & tampilkan loading
    const btns = document.querySelectorAll('.cta-btn');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    btns.forEach(b => b.disabled = true);

    // Loading state pada CTA section
    const ctaSection = document.getElementById('ctaSection');
    const originalGrid = ctaSection.querySelector('.cta-grid').innerHTML;
    ctaSection.querySelector('.cta-grid').innerHTML = `
        <div style="grid-column:1/-1;text-align:center;padding:16px;color:#64748b;font-size:.85rem;">
            <span class="spinner"></span> Mengirim konfirmasi...
        </div>`;

    try {
        const res  = await fetch(`/penawaran/${TOKEN}/respond`, {
            method : 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'Accept'       : 'application/json',
                'X-CSRF-TOKEN' : CSRF,
            },
            body: JSON.stringify({ action, notes }),
        });

        const data = await res.json();

        if (data.success) {
            showToast(data.message, 'success');

            const emojiMap = { approved: '🎉', rejected: '📩', revised: '✉️' };
            const colorMap = { approved: '#065f46', rejected: '#dc2626', revised: '#92400e' };
            const titleMap = {
                approved: 'Penawaran Diterima!',
                rejected: 'Penawaran Ditolak',
                revised : 'Permintaan Revisi Terkirim',
            };
            const subMap = {
                approved: 'Terima kasih sudah menyetujui penawaran ini.<br>Tim kami akan segera menghubungi Anda.',
                rejected: 'Terima kasih atas konfirmasi Anda.<br>Jika ada yang bisa kami bantu, jangan ragu menghubungi kami.',
                revised : 'Tim kami akan meninjau permintaan Anda dan segera menghubungi Anda.',
            };

            ctaSection.style.transition = 'opacity .3s';
            ctaSection.style.opacity = '0';
            setTimeout(() => {
                ctaSection.innerHTML = `
                    <div style="text-align:center;padding:24px 0;">
                        <div style="font-size:3.5rem;margin-bottom:16px;animation:pop .5s cubic-bezier(.34,1.56,.64,1);">${emojiMap[action]}</div>
                        <div style="font-size:1.1rem;font-weight:800;color:${colorMap[action]};margin-bottom:10px;">${titleMap[action]}</div>
                        <p style="color:#64748b;font-size:.84rem;line-height:1.7;">${subMap[action]}</p>
                        ${notes ? `<div style="margin-top:14px;padding:10px 14px;background:#f8fafc;border-radius:8px;font-size:.78rem;color:#64748b;">Catatan Anda: <em>${notes}</em></div>` : ''}
                    </div>`;
                ctaSection.style.opacity = '1';
            }, 300);

        } else {
            showToast('❌ ' + data.message, 'error');
            ctaSection.querySelector('.cta-grid').innerHTML = originalGrid;
            btns.forEach(b => b.disabled = false);
        }

    } catch (e) {
        showToast('Gagal terhubung ke server. Silakan coba lagi.', 'error');
        ctaSection.querySelector('.cta-grid').innerHTML = originalGrid;
        btns.forEach(b => b.disabled = false);
    }
}

// Tutup modal dengan Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
</body>
</html>