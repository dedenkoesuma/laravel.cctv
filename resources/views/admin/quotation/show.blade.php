{{-- resources/views/admin/quotation/show.blade.php --}}
@extends('layouts.simple')
@section('title', 'Detail Penawaran ' . $quo->quo_number)
@section('content')
<style>
.quo-header{background:linear-gradient(135deg,#1e3a5f 0%,#2d6fba 100%);color:white;padding:22px 24px;border-radius:14px;margin-bottom:20px;}
.card{background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.07);margin-bottom:16px;overflow:hidden;}
.card-hdr{padding:14px 20px;font-weight:700;font-size:.85rem;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:8px;}
.card-hdr.blue  {background:#eff6ff;color:#1e40af;}
.card-hdr.green {background:#f0fdf4;color:#065f46;}
.card-hdr.orange{background:#fff7ed;color:#9a3412;}
.card-hdr.purple{background:#f5f3ff;color:#6d28d9;}
.card-bd{padding:20px;}
.meta-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px;}
.meta-item label{font-size:.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:4px;}
.meta-item span{font-size:.9rem;font-weight:700;color:#1e293b;}
.badge-s{padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;display:inline-block;}
.s-draft    {background:#f1f5f9;color:#475569;}
.s-sent     {background:#cffafe;color:#0e7490;}
.s-approved {background:#d1fae5;color:#065f46;}
.s-rejected {background:#fee2e2;color:#991b1b;}
.s-revised  {background:#fef3c7;color:#92400e;}
.s-expired  {background:#1f2937;color:#9ca3af;}
.s-converted{background:#dbeafe;color:#1e40af;}
table.it{width:100%;border-collapse:collapse;font-size:.82rem;}
.it thead th{background:#f8fafc;padding:10px 12px;text-align:left;font-size:.7rem;font-weight:700;color:#374151;text-transform:uppercase;border-bottom:2px solid #e5e7eb;}
.it tbody td{padding:10px 12px;border-bottom:1px solid #f3f4f6;}
.sum-box{background:#f8fafc;border-radius:10px;padding:16px 20px;border:1px solid #e5e7eb;max-width:360px;margin-left:auto;}
.sr{display:flex;justify-content:space-between;padding:5px 0;font-size:.85rem;}
.sr.tot{border-top:2px solid #e5e7eb;margin-top:8px;padding-top:10px;font-weight:800;font-size:1rem;color:#1e3a5f;}
.link-box{background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.link-url{font-family:monospace;font-size:.8rem;color:#065f46;word-break:break-all;flex:1;}
.btn-copy{background:#10b981;color:white;border:none;padding:6px 14px;border-radius:7px;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;}
.action-row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;}
.btn-act{border:none;padding:9px 18px;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;text-decoration:none;}
.btn-wa  {background:#22c55e;color:white;}
.btn-edit{background:#f59e0b;color:white;}
.btn-pdf {background:#64748b;color:white;}
.btn-so  {background:#3b82f6;color:white;}
.btn-del {background:#ef4444;color:white;}
.btn-back{background:white;color:#374151;border:1px solid #d1d5db;}
.customer-note{background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:12px 14px;font-size:.82rem;color:#92400e;margin-top:10px;}
.timeline{border-left:2px solid #e5e7eb;margin-left:12px;padding-left:20px;}
.tl-item{position:relative;margin-bottom:16px;}
.tl-dot{position:absolute;left:-27px;width:12px;height:12px;border-radius:50%;background:#2d6fba;top:4px;}
.tl-time{font-size:.7rem;color:#94a3b8;}
.tl-text{font-size:.82rem;color:#374151;margin-top:2px;}

/* ===== RESPONSE BANNER ===== */
.response-banner {
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    animation: bannerIn .4s cubic-bezier(.34,1.56,.64,1);
}
@keyframes bannerIn { from { opacity:0; transform:translateY(-12px); } to { opacity:1; transform:translateY(0); } }
.response-banner.approved { background: linear-gradient(135deg,#d1fae5,#a7f3d0); border: 1.5px solid #6ee7b7; }
.response-banner.rejected { background: linear-gradient(135deg,#fee2e2,#fecaca); border: 1.5px solid #fca5a5; }
.response-banner.revised  { background: linear-gradient(135deg,#fef3c7,#fde68a); border: 1.5px solid #fcd34d; }
.rb-icon { font-size: 2.4rem; line-height: 1; flex-shrink: 0; }
.rb-content { flex: 1; }
.rb-title { font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
.rb-title.approved { color: #065f46; }
.rb-title.rejected { color: #991b1b; }
.rb-title.revised  { color: #92400e; }
.rb-meta  { font-size: .8rem; opacity: .75; margin-bottom: 6px; }
.rb-note  { font-size: .83rem; font-weight: 600; padding: 8px 12px; border-radius: 8px; display: inline-block; }
.rb-note.approved { background: rgba(6,95,70,.1); color: #065f46; }
.rb-note.rejected { background: rgba(153,27,27,.1); color: #991b1b; }
.rb-note.revised  { background: rgba(146,64,14,.1);  color: #92400e; }
.rb-action { margin-left: auto; display: flex; align-items: center; }
.rb-btn { border: none; padding: 10px 18px; border-radius: 9px; font-size: .82rem; font-weight: 700; cursor: pointer; font-family: inherit; white-space: nowrap; }
.rb-btn.so  { background: #3b82f6; color: white; }
.rb-btn.wa  { background: #22c55e; color: white; }
.rb-btn.edit{ background: #f59e0b; color: white; }
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="quo-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📋 {{ $quo->quo_number }}</h1>
                <p class="mb-0 opacity-75">{{ $quo->project_name ?? 'Penawaran untuk ' . $quo->customer_name }}</p>
            </div>
            <a href="{{ route('admin.quotation.index') }}" class="btn btn-light fw-bold">← Kembali</a>
        </div>
    </div>

    {{-- ===== RESPONSE NOTIFICATION BANNER ===== --}}
    @if($quo->status === 'approved' && $quo->responded_at)
    <div class="response-banner approved">
        <div class="rb-icon">🎉</div>
        <div class="rb-content">
            <div class="rb-title approved">Customer Menyetujui Penawaran!</div>
            <div class="rb-meta">
                Direspons pada {{ $quo->responded_at->format('d M Y') }} pukul {{ $quo->responded_at->format('H:i') }}
                &mdash; oleh <strong>{{ $quo->customer_name }}</strong>
            </div>
            @if($quo->customer_notes)
            <div class="rb-note approved">💬 "{{ $quo->customer_notes }}"</div>
            @endif
        </div>
        @if(!$quo->sales_order_id)
        <div class="rb-action">
            <button class="rb-btn so" onclick="konversiSO()">📦 Konversi ke SO</button>
        </div>
        @endif
    </div>

    @elseif($quo->status === 'rejected' && $quo->responded_at)
    <div class="response-banner rejected">
        <div class="rb-icon">❌</div>
        <div class="rb-content">
            <div class="rb-title rejected">Customer Menolak Penawaran</div>
            <div class="rb-meta">
                Direspons pada {{ $quo->responded_at->format('d M Y') }} pukul {{ $quo->responded_at->format('H:i') }}
                &mdash; oleh <strong>{{ $quo->customer_name }}</strong>
            </div>
            @if($quo->customer_notes)
            <div class="rb-note rejected">💬 "{{ $quo->customer_notes }}"</div>
            @endif
        </div>
    </div>

    @elseif($quo->status === 'revised' && $quo->responded_at)
    <div class="response-banner revised">
        <div class="rb-icon">🔄</div>
        <div class="rb-content">
            <div class="rb-title revised">Customer Meminta Revisi Harga</div>
            <div class="rb-meta">
                Direspons pada {{ $quo->responded_at->format('d M Y') }} pukul {{ $quo->responded_at->format('H:i') }}
                &mdash; oleh <strong>{{ $quo->customer_name }}</strong>
            </div>
            @if($quo->customer_notes)
            <div class="rb-note revised">💬 "{{ $quo->customer_notes }}"</div>
            @endif
        </div>
        <div class="rb-action">
            <a href="{{ route('admin.quotation.edit', $quo->id) }}" class="rb-btn edit">✏️ Edit & Revisi</a>
        </div>
    </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="action-row">
        @if(in_array($quo->status, ['draft','sent','revised']) && $quo->customer_phone)
        <button class="btn-act btn-wa" onclick="kirimWA()">
            <i class="bi bi-whatsapp"></i> Kirim via WhatsApp
        </button>
        @endif
        @if(in_array($quo->status, ['draft','revised']))
        <a href="{{ route('admin.quotation.edit', $quo->id) }}" class="btn-act btn-edit">
            <i class="bi bi-pencil"></i> Edit Penawaran
        </a>
        @endif
        <a href="{{ route('admin.quotation.pdf', $quo->id) }}" target="_blank" class="btn-act btn-pdf">
            <i class="bi bi-file-pdf"></i> Download PDF
        </a>
        @if($quo->status === 'approved' && !$quo->sales_order_id)
        <button class="btn-act btn-so" onclick="konversiSO()">
            <i class="bi bi-arrow-right-circle"></i> Konversi ke Sales Order
        </button>
        @endif
        @if($quo->status === 'draft')
        <button class="btn-act btn-del" onclick="hapus()">
            <i class="bi bi-trash"></i> Hapus
        </button>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            {{-- META --}}
            <div class="card">
                <div class="card-hdr blue">📊 Ringkasan Penawaran</div>
                <div class="card-bd">
                    <div class="meta-row">
                        <div class="meta-item">
                            <label>Status</label>
                            <span><span class="badge-s s-{{ $quo->status }}">{{ $quo->status_label }}</span></span>
                        </div>
                        <div class="meta-item">
                            <label>Tanggal</label>
                            <span>{{ $quo->quo_date->format('d M Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Berlaku Hingga</label>
                            <span style="{{ $quo->is_expired ? 'color:#ef4444' : '' }}">
                                {{ $quo->valid_until->format('d M Y') }}
                                @if($quo->is_expired) <small>(Expired)</small> @endif
                            </span>
                        </div>
                        <div class="meta-item">
                            <label>Total</label>
                            <span style="color:#1e3a5f;">Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- LINK --}}
                    <div class="card-hdr green" style="border-radius:8px;margin-bottom:10px;">🔗 Link untuk Customer</div>
                    <div class="link-box">
                        <span class="link-url" id="linkUrl">{{ $quo->public_url }}</span>
                        <button class="btn-copy" onclick="salin()">📋 Salin Link</button>
                        <a href="{{ $quo->public_url }}" target="_blank" class="btn-copy" style="background:#2d6fba;">👁 Preview</a>
                    </div>

                    {{-- SO Info --}}
                    @if($quo->salesOrder)
                    <div style="background:#dbeafe;border:1px solid #93c5fd;border-radius:8px;padding:12px 14px;margin-top:10px;font-size:.82rem;color:#1e40af;">
                        ✅ Sudah dikonversi ke Sales Order: <strong>{{ $quo->salesOrder->so_number }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            {{-- INFO CUSTOMER --}}
            <div class="card">
                <div class="card-hdr blue">👤 Informasi Customer</div>
                <div class="card-bd">
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Nama:</strong> {{ $quo->customer_name }}</div>
                        <div class="col-md-6"><strong>No. HP:</strong> {{ $quo->customer_phone ?? '-' }}</div>
                        <div class="col-md-6"><strong>Email:</strong> {{ $quo->customer_email ?? '-' }}</div>
                        <div class="col-md-6"><strong>Proyek:</strong> {{ $quo->project_name ?? '-' }}</div>
                        @if($quo->customer_address)
                        <div class="col-12"><strong>Alamat:</strong> {{ $quo->customer_address }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ITEMS --}}
            <div class="card">
                <div class="card-hdr green">🛒 Item Penawaran</div>
                <div class="card-bd" style="padding:0;">
                    <table class="it">
                        <thead>
                            <tr>
                                <th>No</th><th>Item / Produk</th><th>Keterangan</th>
                                <th class="text-center">Qty</th><th class="text-center">Sat</th>
                                <th class="text-end">Harga Satuan</th>
                                @if($quo->items->where('discount','>',0)->count())<th class="text-center">Diskon</th>@endif
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quo->items as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><strong>{{ $item->nama_item }}</strong></td>
                                <td style="color:#94a3b8;font-size:.78rem;">{{ $item->deskripsi ?: '-' }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-center">{{ $item->satuan }}</td>
                                <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                @if($quo->items->where('discount','>',0)->count())
                                <td class="text-center">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
                                @endif
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-bd">
                        <div class="sum-box">
                            <div class="sr"><span style="color:#6b7280">Subtotal</span><span>Rp {{ number_format($quo->subtotal, 0, ',', '.') }}</span></div>
                            @if($quo->discount_global > 0)
                            <div class="sr"><span style="color:#6b7280">Diskon</span><span style="color:#ef4444">- Rp {{ number_format($quo->discount_global, 0, ',', '.') }}</span></div>
                            @endif
                            @if($quo->ppn_enabled)
                            <div class="sr"><span style="color:#6b7280">PPN {{ $quo->ppn_rate }}%</span><span style="color:#d97706">+ Rp {{ number_format($quo->ppn_amount, 0, ',', '.') }}</span></div>
                            @endif
                            <div class="sr tot"><span>TOTAL</span><span>Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- TIMELINE --}}
            <div class="card">
                <div class="card-hdr purple">🕐 Timeline</div>
                <div class="card-bd">
                    <div class="timeline">
                        <div class="tl-item">
                            <div class="tl-dot"></div>
                            <div class="tl-time">{{ $quo->created_at->format('d M Y H:i') }}</div>
                            <div class="tl-text">✏️ Dibuat oleh <strong>{{ $quo->creator->name ?? 'Admin' }}</strong></div>
                        </div>
                        @if($quo->sent_at)
                        <div class="tl-item">
                            <div class="tl-dot" style="background:#06b6d4"></div>
                            <div class="tl-time">{{ $quo->sent_at->format('d M Y H:i') }}</div>
                            <div class="tl-text">📤 Dikirim ke customer</div>
                        </div>
                        @endif
                        @if($quo->responded_at)
                        <div class="tl-item">
                            @php $dotColor = ['approved'=>'#10b981','rejected'=>'#ef4444','revised'=>'#f59e0b'][$quo->status] ?? '#6b7280'; @endphp
                            <div class="tl-dot" style="background:{{ $dotColor }}"></div>
                            <div class="tl-time">{{ $quo->responded_at->format('d M Y H:i') }}</div>
                            <div class="tl-text">
                                @if($quo->status === 'approved') ✅ Customer menyetujui
                                @elseif($quo->status === 'rejected') ❌ Customer menolak
                                @elseif($quo->status === 'revised') 🔄 Customer minta revisi
                                @endif
                                @if($quo->customer_notes)
                                <div style="margin-top:4px;font-size:.74rem;color:#94a3b8;font-style:italic;">"{{ Str::limit($quo->customer_notes, 60) }}"</div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($quo->salesOrder)
                        <div class="tl-item">
                            <div class="tl-dot" style="background:#3b82f6"></div>
                            <div class="tl-time">{{ $quo->updated_at->format('d M Y H:i') }}</div>
                            <div class="tl-text">📦 Jadi SO <strong>{{ $quo->salesOrder->so_number }}</strong></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CATATAN --}}
            @if($quo->notes || $quo->terms)
            <div class="card">
                <div class="card-hdr orange">📝 Catatan & Syarat</div>
                <div class="card-bd">
                    @if($quo->notes)
                    <div style="margin-bottom:12px;">
                        <label style="font-size:.72rem;font-weight:700;color:#374151;">Catatan</label>
                        <p style="font-size:.82rem;color:#374151;margin-top:4px;">{{ $quo->notes }}</p>
                    </div>
                    @endif
                    @if($quo->terms)
                    <div>
                        <label style="font-size:.72rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;">Syarat & Ketentuan</label>
                        <p style="font-size:.78rem;color:#6b7280;white-space:pre-line;margin:0;">{{ $quo->terms }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
const QUO_ID = {{ $quo->id }};
const CSRF   = document.querySelector('meta[name="csrf-token"]')?.content || '';

function salin() {
    navigator.clipboard.writeText(document.getElementById('linkUrl').textContent.trim());
    showToast('🔗 Link berhasil disalin!', 'success');
}

async function kirimWA() {
    const res  = await fetch(`/admin/quotation/${QUO_ID}/send`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.success) {
        window.open(data.whatsapp_url, '_blank');
        showToast('✅ ' + data.message, 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

async function konversiSO() {
    if (!confirm('Konversi penawaran ini menjadi Sales Order?')) return;
    const res  = await fetch(`/admin/quotation/${QUO_ID}/convert-so`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const data = await res.json();
    showToast((data.success ? '✅ ' : '❌ ') + data.message, data.success ? 'success' : 'danger');
    if (data.success) setTimeout(() => location.reload(), 1500);
}

async function hapus() {
    if (!confirm('Hapus penawaran ini?')) return;
    const res  = await fetch(`/admin/quotation/${QUO_ID}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (data.success) window.location.href = '/admin/quotation';
}

function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    el.style.zIndex = 9999;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}
</script>
@endsection