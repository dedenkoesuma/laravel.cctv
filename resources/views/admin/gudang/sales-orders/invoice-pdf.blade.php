<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #e11d48;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-left  { display: table-cell; width: 60%; vertical-align: middle; }
        .header-right { display: table-cell; width: 40%; vertical-align: middle; text-align: right; }

        .logo { height: 60px; object-fit: contain; }
        .company-name { font-size: 22px; font-weight: 800; color: #111; margin-top: 5px;}
        .company-sub  { font-size: 10px; color: #555; margin-top: 2px; }

        .invoice-title { font-size: 26px; font-weight: 700; color: #e11d48; letter-spacing:1px; }
        .invoice-number { font-size: 13px; font-weight: 700; color: #111; margin-top: 3px; }
        .invoice-date   { font-size: 10px; color: #666; }

        /* ===== STATUS BADGE ===== */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .status-pending { background: #fef3c7; color: #b45309; border: 1px solid #f59e0b; }
        .status-lunas   { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }

        /* ===== INFO BOXES ===== */
        .info-row { display: table; width: 100%; margin-bottom: 16px; }
        .info-box { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
        .info-box:last-child { padding-right: 0; padding-left: 16px; }

        .info-box h4 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #e11d48;
            border-bottom: 1px solid #fecdd3;
            padding-bottom: 4px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .info-box table { width: 100%; }
        .info-box td { padding: 2px 0; vertical-align: top; }
        .info-box td:first-child { color: #666; width: 110px; font-size: 11px; }
        .info-box td:last-child  { font-weight: 600; font-size: 11px; }

        /* ===== REKENING BOX ===== */
        .rekening-box {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .rekening-box h4 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #e11d48;
            margin-bottom: 6px;
        }
        .rekening-box .rek-row { display: table; width: 100%; }
        .rekening-box .rek-cell { display: table-cell; width: 33%; }
        .rekening-box .rek-label { font-size: 9px; color: #666; }
        .rekening-box .rek-value { font-size: 12px; font-weight: 700; color: #881337; }

        /* ===== TEMPO BOX ===== */
        .tempo-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 16px;
            font-size: 11px;
        }
        .tempo-box strong { color: #b45309; }

        /* ===== TABEL ITEM ===== */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .items-table thead tr { background: #e11d48; color: #fff; }
        .items-table thead th { padding: 7px 10px; text-align: left; font-size: 11px; }
        .items-table thead th.text-right { text-align: right; }
        .items-table thead th.text-center { text-align: center; }

        .items-table tbody tr:nth-child(even) { background: #f8fafc; }
        .items-table tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; vertical-align: top; }
        .items-table tbody td.text-right  { text-align: right; }
        .items-table tbody td.text-center { text-align: center; }

        .items-table tfoot td { padding: 6px 10px; font-size: 11px; }
        .items-table tfoot tr.subtotal td { color: #555; }
        .items-table tfoot tr.ppn td { color: #dc2626; }
        .items-table tfoot tr.total td {
            font-size: 13px;
            font-weight: 700;
            color: #065f46;
            background: #d1fae5;
            border-top: 2px solid #10b981;
        }
        .items-table tfoot tr.dp td {
            font-size: 11px;
            font-weight: 600;
            color: #b45309;
            background: #fffbeb;
            border-top: 1px solid #fcd34d;
        }
        .items-table tfoot tr.sisa td {
            font-size: 13px;
            font-weight: 700;
            color: #991b1b;
            background: #fff1f2;
            border-top: 2px solid #fecdd3;
            border-bottom: 3px double #e11d48;
        }

        /* ===== FOOTER ===== */
        .page-wrap { padding: 28px 32px; }
    </style>
</head>
<body>
<div class="page-wrap">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            @php
                $imagePath = storage_path('app/public/gambar/logo-mja.png');
                $src = '';
                if(file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $src = 'data:image/png;base64,' . $imageData;
                }
            @endphp
            @if($src)
                <img src="{{ $src }}" class="logo" alt="Logo MJA">
            @endif
            <div class="company-name">TechStore</div>
            <div class="company-sub">Solusi Teknologi & Networking Terpercaya</div>
        </div>
        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div class="invoice-date">Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</div>
            <div>
                @if($invoice->status === 'lunas')
                    <span class="status-badge status-lunas">LUNAS</span>
                @else
                    <span class="status-badge status-pending">BELUM LUNAS</span>
                @endif
            </div>
        </div>
    </div>

    {{-- INFO CUSTOMER & ORDER --}}
    <div class="info-row">
        <div class="info-box">
            <h4>Tagihan Kepada</h4>
            <table>
                <tr><td>Nama</td><td>{{ $invoice->pihak_terkait }}</td></tr>
                @if($salesOrder->customer_phone)
                <tr><td>No. HP</td><td>{{ $salesOrder->customer_phone }}</td></tr>
                @endif
                @if($salesOrder->customer_email)
                <tr><td>Email</td><td>{{ $salesOrder->customer_email }}</td></tr>
                @endif
                @if($salesOrder->customer_address)
                <tr><td>Alamat</td><td>{{ $salesOrder->customer_address }}</td></tr>
                @endif
            </table>
        </div>
        <div class="info-box">
            <h4>Detail Invoice</h4>
            <table>
                <tr><td>No. Invoice</td><td>{{ $invoice->invoice_number }}</td></tr>
                <tr><td>No. SO</td><td>{{ $invoice->so_number }}</td></tr>
                <tr><td>Tgl. Invoice</td><td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td></tr>
                <tr><td>Metode Bayar</td><td>{{ ucfirst($invoice->metode_bayar) }}</td></tr>
                <tr><td>Tipe Bayar</td>
                    <td>
                        @if($invoice->tipe_bayar === 'tempo')
                            Tempo {{ $invoice->tempo_hari }} hari
                        @else
                            Cash
                        @endif
                    </td>
                </tr>
                @if($invoice->jatuh_tempo)
                <tr><td>Jatuh Tempo</td><td><strong>{{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}</strong></td></tr>
                @endif
                {{-- ✅ DP & Sisa Tagihan di Info Detail --}}
                @if(!empty($invoice->dp_nominal) && $invoice->dp_nominal > 0)
                <tr><td>DP / Uang Muka</td><td><strong style="color:#b45309">Rp {{ number_format($invoice->dp_nominal, 0, ',', '.') }}</strong></td></tr>
                <tr><td>Sisa Tagihan</td><td><strong style="color:#dc2626">Rp {{ number_format($invoice->sisa_tagihan, 0, ',', '.') }}</strong></td></tr>
                @endif
            </table>
        </div>
    </div>

    {{-- TEMPO WARNING --}}
    @if($invoice->tipe_bayar === 'tempo' && $invoice->status !== 'lunas')
    <div class="tempo-box">
        <strong>Pembayaran Tempo:</strong>
        Harap melakukan pembayaran sebelum
        <strong>{{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d F Y') }}</strong>
        ({{ $invoice->tempo_hari }} hari dari tanggal invoice).
        @if(!empty($invoice->dp_nominal) && $invoice->dp_nominal > 0)
            Sisa tagihan yang harus dibayar:
            <strong>Rp {{ number_format($invoice->sisa_tagihan, 0, ',', '.') }}</strong>.
        @endif
    </div>
    @endif

    {{-- TABEL ITEM --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Produk</th>
                <th>SKU</th>
                <th class="text-center" style="width:50px">Qty</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal_items = 0; @endphp
            @foreach($salesOrder->items as $i => $item)
            @php $subtotal_items += $item->subtotal; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->nama_produk ?? $item->notes }}</strong>
                    @if($item->sku)<div style="font-size:10px;color:#888">SKU: {{ $item->sku }}</div>@endif
                </td>
                <td style="color:#888;font-size:10px">{{ $item->sku ?? '-' }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="subtotal">
                <td colspan="5" style="text-align:right">Subtotal</td>
                <td style="text-align:right">Rp {{ number_format($subtotal_items, 0, ',', '.') }}</td>
            </tr>
            @if($salesOrder->ppn_aktif && $salesOrder->ppn_nominal > 0)
            <tr class="ppn">
                <td colspan="5" style="text-align:right">
                    PPN ({{ floatval($salesOrder->ppn_rate) }}%)
                </td>
                <td style="text-align:right">+ Rp {{ number_format($salesOrder->ppn_nominal, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total">
                <td colspan="5" style="text-align:right">TOTAL TAGIHAN</td>
                <td style="text-align:right">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
            </tr>
            {{-- ✅ Baris DP & Sisa Tagihan di tfoot --}}
            @if(!empty($invoice->dp_nominal) && $invoice->dp_nominal > 0)
            <tr class="dp">
                <td colspan="5" style="text-align:right">DP / Uang Muka (sudah dibayar)</td>
                <td style="text-align:right">- Rp {{ number_format($invoice->dp_nominal, 0, ',', '.') }}</td>
            </tr>
            <tr class="sisa">
                <td colspan="5" style="text-align:right">SISA TAGIHAN</td>
                <td style="text-align:right">Rp {{ number_format($invoice->sisa_tagihan, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    {{-- REKENING TUJUAN --}}
    <div class="rekening-box">
        <h4>Rekening Tujuan Pembayaran</h4>
        <div class="rek-row">
            <div class="rek-cell">
                <div class="rek-label">Bank</div>
                <div class="rek-value">{{ $invoice->nama_bank }}</div>
            </div>
            <div class="rek-cell">
                <div class="rek-label">Nomor Rekening</div>
                <div class="rek-value">{{ $invoice->no_rekening }}</div>
            </div>
            <div class="rek-cell">
                <div class="rek-label">Atas Nama</div>
                <div class="rek-value">{{ $invoice->nama_rekening }}</div>
            </div>
        </div>
    </div>

    @if($invoice->catatan)
    <div style="font-size:11px;color:#555;margin-bottom:14px;">
        <strong>Catatan:</strong> {{ $invoice->catatan }}
    </div>
    @endif

    {{-- FOOTER DENGAN TTD & STEMPEL (Versi Table DOMPDF Safe) --}}
    <table style="width: 100%; border-top: 1px solid #e5e7eb; margin-top: 20px; padding-top: 10px; border-collapse: collapse;">
        <tr>
            <td style="width: 60%; vertical-align: bottom; font-size: 10px; color: #666;">
                <div>Dokumen ini diterbitkan secara resmi oleh <strong>TechStore</strong>.</div>
                <div style="margin-top:3px">Terima kasih atas kepercayaan Anda.</div>
            </td>
            <td style="width: 40%; text-align: center; vertical-align: bottom;">
                <!-- Jarak kosong dilebarkan jadi 90px agar ada ruang muat untuk gambar -->
                <div style="font-size:10px;color:#666; margin-bottom: 90px;">Hormat kami,</div>
                
                @php
                    // Setup Base64 untuk Stempel
                    $stempelPath = storage_path('app/public/gambar/stempel.png');
                    $stempelBase64 = '';
                    if(file_exists($stempelPath)){
                        $stempelData = base64_encode(file_get_contents($stempelPath));
                        $stempelBase64 = 'data:image/png;base64,' . $stempelData;
                    }

                    // Setup Base64 untuk Tanda Tangan
                    $ttdPath = storage_path('app/public/gambar/ttd.png');
                    $ttdBase64 = '';
                    if(file_exists($ttdPath)){
                        $ttdData = base64_encode(file_get_contents($ttdPath));
                        $ttdBase64 = 'data:image/png;base64,' . $ttdData;
                    }
                @endphp

                <!-- Container area TTD dan Garis -->
                <div style="width: 160px; margin: 0 auto; text-align: center;">

                    <!-- Stempel ditarik lebih jauh ke atas jadi -85px -->
                    <div style="height: 0;">
                        @if($stempelBase64)
                            <img src="{{ $stempelBase64 }}" style="width: 90px; opacity: 0.7; margin-top: -85px; margin-left: -20px;">
                        @endif
                    </div>

                    <!-- TTD ditarik lebih jauh ke atas jadi -115px -->
                    <div style="height: 0;">
                        @if($ttdBase64)
                            <img src="{{ $ttdBase64 }}" style="width: 120px; margin-top: -115px; margin-left: 20px;">
                        @endif
                    </div>

                    <!-- Garis TechStore akan tetap aman di posisinya -->
                    <div style="border-top: 1px solid #333; padding-top: 4px; font-size: 10px; color: #444; font-weight: bold; width: 100%;">
                        TechStore
                    </div>

                </div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>