{{-- resources/views/admin/purchase-orders/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #111827; background: white; }
/* HEADER */
.pdf-header { background: #1e3a5f; color: white; padding: 20px 28px; display: flex; justify-content: space-between; align-items: flex-start; }
.company-name { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
.company-sub  { font-size: 10px; opacity: .8; line-height: 1.6; }
.po-title     { text-align: right; }
.po-title h1  { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
.po-title .po-num { font-size: 13px; opacity: .8; margin-top: 3px; }
/* BODY */
.pdf-body { padding: 20px 28px; }
/* INFO BOX */
.info-grid { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb; }
.info-cell { display: table-cell; width: 50%; padding: 14px 16px; vertical-align: top; }
.info-cell:first-child { border-right: 1px solid #e5e7eb; }
.info-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: 3px; }
.info-val   { font-size: 12px; font-weight: bold; color: #111827; margin-bottom: 2px; }
.info-sub   { font-size: 10px; color: #6b7280; line-height: 1.5; }
.detail-grid { display: table; width: 100%; margin-top: 10px; }
.detail-row  { display: table-row; }
.detail-cell { display: table-cell; padding: 3px 0; font-size: 10px; }
.detail-cell:first-child { color: #6b7280; width: 45%; }
.detail-cell:last-child  { font-weight: bold; }
/* PPN BADGE */
.ppn-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
.ppn-badge.yes { background: #dbeafe; color: #1d4ed8; }
.ppn-badge.no  { background: #f3f4f6; color: #6b7280; }
/* ITEM TABLE */
.item-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
.item-table thead th { background: #1e3a5f; color: white; font-size: 9px; text-transform: uppercase; letter-spacing: .05em; padding: 9px 10px; text-align: left; font-weight: bold; }
.item-table tbody td { padding: 9px 10px; border-bottom: 1px solid #f3f4f6; font-size: 10.5px; vertical-align: top; }
.item-table tbody tr:nth-child(even) td { background: #f9fafb; }
.item-table .product-name { font-weight: bold; font-size: 11px; }
.item-table .product-desc { font-size: 9.5px; color: #6b7280; margin-top: 2px; }
/* TOTAL SECTION */
.total-section { display: table; width: 100%; margin-bottom: 20px; }
.total-left  { display: table-cell; vertical-align: top; padding-right: 20px; width: 55%; }
.total-right { display: table-cell; vertical-align: top; width: 45%; }
.notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 12px; font-size: 10px; color: #92400e; line-height: 1.6; }
.total-table { width: 100%; border-collapse: collapse; }
.total-table td { padding: 5px 8px; font-size: 11px; }
.total-table .lbl { color: #6b7280; }
.total-table .val { text-align: right; font-weight: bold; }
.total-table .ppn-row { color: #1d4ed8; background: #eff6ff; }
.total-table tfoot td { border-top: 2px solid #1e3a5f; padding-top: 10px; font-size: 13px; font-weight: bold; color: #1e3a5f; }
.total-table tfoot .val { font-size: 15px; }
/* SIGNATURE */
.sign-section { display: table; width: 100%; margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 16px; }
.sign-cell { display: table-cell; width: 33.33%; text-align: center; padding: 0 12px; }
.sign-area { height: 55px; border-bottom: 1px solid #374151; margin-bottom: 6px; }
.sign-name  { font-size: 11px; font-weight: bold; color: #111827; }
.sign-title { font-size: 9.5px; color: #6b7280; }
/* FOOTER */
.pdf-footer { background: #f8fafc; border-top: 1px solid #e5e7eb; padding: 10px 28px; text-align: center; font-size: 9px; color: #9ca3af; margin-top: 10px; }
/* STATUS BADGE */
.status-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
.status-badge.draft     { background: #f3f4f6; color: #374151; }
.status-badge.sent      { background: #dbeafe; color: #1d4ed8; }
.status-badge.confirmed { background: #d1fae5; color: #065f46; }
.status-badge.completed { background: #dcfce7; color: #14532d; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="pdf-header">
    <div>
        <div class="company-name">🏢 TechStore Indonesia</div>
        <div class="company-sub">
            Jl. Kalibaru Timur, RT.3/RW.2, Bungur, Kec. Senen, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10460<br>
            📞 021-7891234 &nbsp;|&nbsp; 📧 admin@techstore.id<br>
            NPWP: 01.234.567.8-123.000
        </div>
    </div>
    <div class="po-title">
        <h1>PURCHASE ORDER</h1>
        <div class="po-num">{{ $po->po_number }}</div>
        <div style="font-size:10px;opacity:.7;margin-top:3px">
            {{ \Carbon\Carbon::parse($po->po_date)->format('d F Y') }}
        </div>
    </div>
</div>

<div class="pdf-body">
    {{-- INFO SUPPLIER & DETAIL PO --}}
    <div class="info-grid">
        <div class="info-cell">
            <div class="info-label">Kepada / Supplier</div>
            <div class="info-val" style="font-size:13px">{{ $po->supplier_name }}</div>
            @if($po->supplier_pic)
                <div class="info-sub">Attn: {{ $po->supplier_pic }}</div>
            @endif
            @if($po->supplier_address)
                <div class="info-sub">📍 {{ $po->supplier_address }}</div>
            @endif
            @if($po->supplier_phone)
                <div class="info-sub">📞 {{ $po->supplier_phone }}</div>
            @endif
            @if($po->supplier_email)
                <div class="info-sub">📧 {{ $po->supplier_email }}</div>
            @endif
        </div>
        <div class="info-cell">
            <div class="info-label">Detail Order</div>
            <div class="detail-grid">
                <div class="detail-row">
                    <div class="detail-cell">No. PO</div>
                    <div class="detail-cell" style="font-family:monospace">{{ $po->po_number }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-cell">Tanggal PO</div>
                    <div class="detail-cell">{{ \Carbon\Carbon::parse($po->po_date)->format('d M Y') }}</div>
                </div>
                @if($po->required_date)
                <div class="detail-row">
                    <div class="detail-cell">Dibutuhkan Sebelum</div>
                    <div class="detail-cell">{{ \Carbon\Carbon::parse($po->required_date)->format('d M Y') }}</div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-cell">Metode Pembayaran</div>
                    <div class="detail-cell">
                        @php $pmMap=['transfer'=>'Transfer Bank','cash'=>'Cash','tempo_30'=>'Tempo 30 Hari','tempo_60'=>'Tempo 60 Hari']; @endphp
                        {{ $pmMap[$po->payment_method] ?? $po->payment_method }}
                    </div>
                </div>
                @if($po->delivery_to)
                <div class="detail-row">
                    <div class="detail-cell">Kirim ke</div>
                    <div class="detail-cell">{{ $po->delivery_to }}</div>
                </div>
                @endif
                <div class="detail-row">
                    <div class="detail-cell">PPN</div>
                    <div class="detail-cell">
                        @if($po->use_ppn)
                            <span class="ppn-badge yes">PPN {{ $po->ppn_percent }}%</span>
                        @else
                            <span class="ppn-badge no">Non PPN</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-cell">Status</div>
                    <div class="detail-cell">
                        <span class="status-badge {{ $po->status }}">
                            @php $sMap=['draft'=>'Draft','sent'=>'Terkirim','confirmed'=>'Dikonfirmasi','completed'=>'Selesai']; @endphp
                            {{ $sMap[$po->status] ?? $po->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEM TABLE --}}
    <table class="item-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="35%">Nama Produk / Deskripsi</th>
                <th width="10%" style="text-align:center">Qty</th>
                <th width="10%" style="text-align:center">Satuan</th>
                <th width="15%" style="text-align:right">Harga Satuan</th>
                @if($items->sum('discount_item') > 0)
                <th width="10%" style="text-align:center">Diskon</th>
                @endif
                <th width="15%" style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
            <tr>
                <td style="text-align:center;color:#6b7280">{{ $i+1 }}</td>
                <td>
                    <div class="product-name">{{ $item->product_name }}</div>
                    @if($item->product_description)
                        <div class="product-desc">{{ $item->product_description }}</div>
                    @endif
                </td>
                <td style="text-align:center;font-weight:bold">{{ $item->qty + 0 }}</td>
                <td style="text-align:center">{{ $item->unit }}</td>
                <td style="text-align:right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                @if($items->sum('discount_item') > 0)
                <td style="text-align:center;color:#dc2626">
                    {{ $item->discount_item > 0 ? ($item->discount_item + 0) . '%' : '-' }}
                </td>
                @endif
                <td style="text-align:right;font-weight:bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL SECTION --}}
    <div class="total-section">
        {{-- KIRI: Catatan --}}
        <div class="total-left">
            @if($po->notes)
            <div class="notes-box">
                <strong>📝 Catatan:</strong><br>
                {{ $po->notes }}
            </div>
            @endif
            <div style="margin-top:12px;font-size:9.5px;color:#6b7280;line-height:1.6">
                Dokumen ini adalah Purchase Order resmi dari TechStore Indonesia.<br>
                Mohon konfirmasi penerimaan PO ini dalam waktu 1x24 jam.<br>
                Barang yang cacat/rusak saat diterima akan dikembalikan kepada supplier.
            </div>
        </div>

        {{-- KANAN: Total --}}
        <div class="total-right">
            <table class="total-table">
                <tbody>
                    <tr>
                        <td class="lbl">Subtotal</td>
                        <td class="val">Rp {{ number_format($po->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($po->discount > 0)
                    <tr>
                        <td class="lbl">Diskon Keseluruhan</td>
                        <td class="val" style="color:#dc2626">{{ $po->discount + 0 }}%</td>
                    </tr>
                    @endif
                    @if($po->use_ppn)
                    <tr class="ppn-row">
                        <td class="lbl" style="color:#1d4ed8">PPN {{ $po->ppn_percent }}%</td>
                        <td class="val" style="color:#1d4ed8">Rp {{ number_format($po->ppn_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($po->shipping_cost > 0)
                    <tr>
                        <td class="lbl">Ongkos Kirim</td>
                        <td class="val">Rp {{ number_format($po->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td class="lbl">TOTAL</td>
                        <td class="val">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="sign-section">
        <div class="sign-cell">
            <div style="font-size:10px;color:#6b7280;margin-bottom:4px">Dibuat oleh,</div>
            <div class="sign-area"></div>
            <div class="sign-name">Admin Purchasing</div>
            <div class="sign-title">TechStore Indonesia</div>
        </div>
        <div class="sign-cell">
            <div style="font-size:10px;color:#6b7280;margin-bottom:4px">Disetujui oleh,</div>
            <div class="sign-area"></div>
            <div class="sign-name">Manager / Direktur</div>
            <div class="sign-title">TechStore Indonesia</div>
        </div>
        <div class="sign-cell">
            <div style="font-size:10px;color:#6b7280;margin-bottom:4px">Diterima oleh Supplier,</div>
            <div class="sign-area"></div>
            <div class="sign-name">{{ $po->supplier_name }}</div>
            <div class="sign-title">{{ $po->supplier_pic ?? 'Authorized Person' }}</div>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<div class="pdf-footer">
    Dokumen ini digenerate otomatis oleh sistem TechStore Indonesia &nbsp;|&nbsp; 
    Dicetak: {{ now()->format('d M Y, H:i') }} WIB &nbsp;|&nbsp; 
    {{ $po->po_number }}
</div>

</body>
</html>