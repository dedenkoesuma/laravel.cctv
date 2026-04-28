{{-- resources/views/admin/quotation/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Penawaran {{ $quo->quo_number }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',Arial,sans-serif;font-size:12px;color:#1e293b;background:white;padding:0;}
.page{width:210mm;min-height:297mm;padding:18mm 16mm;margin:0 auto;background:white;}

/* KOP SURAT */
.kop{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:3px solid #1e3a5f;padding-bottom:14px;margin-bottom:16px;}
.kop-logo{font-size:20px;font-weight:900;color:#1e3a5f;line-height:1.1;}
.kop-sub{font-size:10px;color:#64748b;margin-top:2px;}
.kop-info{text-align:right;font-size:10px;color:#64748b;line-height:1.7;}

/* JUDUL */
.doc-title{text-align:center;margin-bottom:16px;}
.doc-title h2{font-size:14px;font-weight:800;color:#1e3a5f;text-transform:uppercase;letter-spacing:.1em;}
.doc-title .num{font-size:11px;color:#64748b;margin-top:3px;font-family:monospace;}

/* INFO GRID */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;}
.info-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:12px;}
.info-box h4{font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;}
.info-row{display:flex;gap:4px;margin-bottom:4px;font-size:11px;}
.info-lbl{color:#64748b;width:100px;flex-shrink:0;}
.info-val{color:#1e293b;font-weight:600;}

/* TABLE */
table{width:100%;border-collapse:collapse;margin-bottom:14px;font-size:11px;}
thead th{background:#1e3a5f;color:white;padding:8px 10px;text-align:left;font-size:10px;font-weight:700;}
thead th.r{text-align:right;}
thead th.c{text-align:center;}
tbody td{padding:7px 10px;border-bottom:1px solid #f1f5f9;vertical-align:top;}
tbody td.r{text-align:right;}
tbody td.c{text-align:center;}
tbody tr:nth-child(even) td{background:#fafbfc;}
tfoot td{padding:7px 10px;font-weight:700;}

/* SUMMARY */
.summary-wrap{display:flex;justify-content:flex-end;margin-bottom:16px;}
.summary-table{width:260px;}
.summary-table td{padding:4px 8px;font-size:11px;}
.summary-table td:last-child{text-align:right;font-weight:600;}
.summary-table tr.total td{border-top:2px solid #1e3a5f;font-size:13px;font-weight:800;color:#1e3a5f;padding-top:8px;}

/* NOTES & TERMS */
.notes-section{margin-bottom:14px;}
.notes-section h4{font-size:10px;font-weight:800;color:#1e3a5f;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;}
.notes-box{background:#fffbeb;border:1px solid #fde68a;border-radius:5px;padding:10px 12px;font-size:11px;color:#92400e;line-height:1.7;}
.terms-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:5px;padding:10px 12px;font-size:10.5px;color:#64748b;line-height:1.8;white-space:pre-line;}

/* FOOTER */
.sig-section{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:24px;}
.sig-box{text-align:center;}
.sig-box .sig-title{font-size:10px;color:#64748b;margin-bottom:60px;}
.sig-box .sig-name{font-size:11px;font-weight:700;border-top:1px solid #1e293b;padding-top:4px;}
.sig-box .sig-label{font-size:10px;color:#64748b;}
.page-footer{text-align:center;font-size:9px;color:#94a3b8;border-top:1px solid #e2e8f0;padding-top:10px;margin-top:20px;}

/* STATUS STAMP */
.stamp{position:relative;}
.stamp-label{
    display:inline-block;
    border:3px solid;
    border-radius:4px;
    padding:4px 14px;
    font-size:14px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.1em;
    transform:rotate(-15deg);
    position:absolute;
    right:20px;
    top:-20px;
    opacity:.25;
}
.stamp-approved{border-color:#065f46;color:#065f46;}
.stamp-rejected{border-color:#dc2626;color:#dc2626;}

/* PRINT */
@media print {
    body{padding:0;}
    .page{padding:12mm 14mm;}
    .no-print{display:none!important;}
}
</style>
</head>
<body>

{{-- TOMBOL PRINT --}}
<div class="no-print" style="text-align:center;padding:12px;background:#1e3a5f;gap:10px;display:flex;justify-content:center;align-items:center;">
    <button onclick="window.print()" style="background:#10b981;color:white;border:none;padding:8px 20px;border-radius:7px;font-weight:700;cursor:pointer;font-size:.85rem;">
        🖨️ Print / Download PDF
    </button>
    <a href="{{ route('admin.quotation.show', $quo->id) }}"
       style="background:white;color:#1e3a5f;border:none;padding:8px 16px;border-radius:7px;font-weight:700;cursor:pointer;font-size:.85rem;text-decoration:none;">
        ← Kembali
    </a>
</div>

<div class="page">
    {{-- KOP SURAT --}}
    <div class="kop">
        <div>
            <div class="kop-logo">{{ config('app.name', 'NAMA PERUSAHAAN') }}</div>
            <div class="kop-sub">Jl. Contoh No. 1, Kota Anda | Telp: 0800-0000 | info@perusahaan.com</div>
        </div>
        <div class="kop-info">
            <div>{{ config('app.name', 'NAMA PERUSAHAAN') }}</div>
            <div>NPWP: 00.000.000.0-000.000</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="doc-title stamp">
        <h2>Surat Penawaran Harga</h2>
        <div class="num">{{ $quo->quo_number }}</div>
        @if($quo->status === 'approved')
            <span class="stamp-label stamp-approved">Disetujui</span>
        @elseif($quo->status === 'rejected')
            <span class="stamp-label stamp-rejected">Ditolak</span>
        @endif
    </div>

    {{-- INFO --}}
    <div class="info-grid">
        <div class="info-box">
            <h4>Ditujukan Kepada</h4>
            <div class="info-row"><span class="info-lbl">Nama</span><span class="info-val">{{ $quo->customer_name }}</span></div>
            @if($quo->customer_phone)<div class="info-row"><span class="info-lbl">No. HP</span><span class="info-val">{{ $quo->customer_phone }}</span></div>@endif
            @if($quo->customer_email)<div class="info-row"><span class="info-lbl">Email</span><span class="info-val">{{ $quo->customer_email }}</span></div>@endif
            @if($quo->project_name)<div class="info-row"><span class="info-lbl">Keperluan</span><span class="info-val">{{ $quo->project_name }}</span></div>@endif
            @if($quo->customer_address)<div class="info-row"><span class="info-lbl">Alamat</span><span class="info-val">{{ $quo->customer_address }}</span></div>@endif
        </div>
        <div class="info-box">
            <h4>Detail Penawaran</h4>
            <div class="info-row"><span class="info-lbl">No. Penawaran</span><span class="info-val" style="font-family:monospace;">{{ $quo->quo_number }}</span></div>
            <div class="info-row"><span class="info-lbl">Tanggal</span><span class="info-val">{{ $quo->quo_date->format('d M Y') }}</span></div>
            <div class="info-row"><span class="info-lbl">Berlaku Hingga</span><span class="info-val">{{ $quo->valid_until->format('d M Y') }}</span></div>
            <div class="info-row"><span class="info-lbl">Total</span><span class="info-val" style="color:#1e3a5f;">Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</span></div>
        </div>
    </div>

    {{-- ITEMS --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:32%">Item / Produk</th>
                <th style="width:18%">Keterangan</th>
                <th class="c" style="width:6%">Qty</th>
                <th class="c" style="width:6%">Sat</th>
                <th class="r" style="width:15%">Harga Satuan</th>
                @if($quo->items->where('discount','>',0)->count())<th class="c" style="width:6%">Disk%</th>@endif
                <th class="r" style="width:12%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quo->items as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td><strong>{{ $item->nama_item }}</strong></td>
                <td style="color:#64748b;font-size:10.5px;">{{ $item->deskripsi ?: '-' }}</td>
                <td class="c">{{ $item->qty }}</td>
                <td class="c">{{ $item->satuan }}</td>
                <td class="r">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                @if($quo->items->where('discount','>',0)->count())<td class="c">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>@endif
                <td class="r"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SUMMARY --}}
    <div class="summary-wrap">
        <table class="summary-table">
            <tr><td style="color:#64748b;">Subtotal</td><td>Rp {{ number_format($quo->subtotal, 0, ',', '.') }}</td></tr>
            @if($quo->discount_global > 0)
            <tr><td style="color:#64748b;">Diskon</td><td style="color:#dc2626;">- Rp {{ number_format($quo->discount_global, 0, ',', '.') }}</td></tr>
            @endif
            @if($quo->ppn_enabled)
            <tr><td style="color:#64748b;">PPN {{ $quo->ppn_rate }}%</td><td style="color:#d97706;">+ Rp {{ number_format($quo->ppn_amount, 0, ',', '.') }}</td></tr>
            @endif
            <tr class="total"><td>TOTAL</td><td>Rp {{ number_format($quo->total_amount, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    {{-- CATATAN --}}
    @if($quo->notes)
    <div class="notes-section">
        <h4>Catatan</h4>
        <div class="notes-box">{{ $quo->notes }}</div>
    </div>
    @endif

    @if($quo->terms)
    <div class="notes-section">
        <h4>Syarat & Ketentuan</h4>
        <div class="terms-box">{{ $quo->terms }}</div>
    </div>
    @endif

    {{-- TTD --}}
    <div class="sig-section">
        <div class="sig-box">
            <div class="sig-title">Hormat Kami,</div>
            <div class="sig-name">{{ config('app.name') }}</div>
            <div class="sig-label">Penawar</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Disetujui oleh,</div>
            <div class="sig-name">{{ $quo->customer_name }}</div>
            <div class="sig-label">Customer</div>
        </div>
    </div>

    <div class="page-footer">
        Dokumen ini dicetak dari Sistem Penawaran Digital — {{ config('app.name') }} — {{ now()->format('d M Y') }}
    </div>
</div>

</body>
</html>