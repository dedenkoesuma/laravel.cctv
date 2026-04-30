<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Order {{ $salesOrder->so_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 3px solid #1a73e8; padding-bottom: 15px; margin-bottom: 20px; }
        .company-name { font-size: 22px; font-weight: bold; color: #1a73e8; }
        .so-title { font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0; color: #333; }
        .so-number { font-size: 14px; text-align: center; color: #666; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; vertical-align: top; }
        .info-table .label { font-weight: bold; width: 130px; color: #555; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-delivered { background: #cce5ff; color: #004085; }
        .status-draft { background: #e2e3e5; color: #383d41; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #1a73e8; color: white; padding: 8px; text-align: left; font-size: 11px; }
        .items-table td { padding: 7px 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        .items-table tr:nth-child(even) td { background: #f8f9fa; }
        .sn-list { margin-top: 4px; }
        .sn-item { font-family: monospace; font-size: 10px; background: #e8f0fe; padding: 1px 5px;
                   border-radius: 3px; display: inline-block; margin: 1px 2px; }
        .total-row { font-weight: bold; font-size: 13px; }
        .subtotal-row { font-size: 12px; color: #555; }
        .subtotal-row td { border-bottom: none; padding-top: 10px; padding-bottom: 2px; }
        .ppn-row td { border-bottom: none; padding-bottom: 10px; color: #d9534f; }
        .total-row td { background: #f0f0f0 !important; padding: 10px 8px; border-top: 2px solid #ccc; }
        .footer { border-top: 1px solid #ddd; padding-top: 15px; margin-top: 20px; }
        .sign-table { width: 100%; margin-top: 30px; }
        .sign-table td { text-align: center; width: 33%; padding: 10px; }
        .sign-box { border: 1px solid #ccc; height: 70px; margin: 10px 5px; }
        .notes-box { background: #fffde7; border: 1px solid #f9ca24; padding: 10px;
                     border-radius: 5px; margin-bottom: 15px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <div class="company-name">TechStore</div>
                    <div style="font-size:11px; color:#666; margin-top:3px;">
                        Toko CCTV &amp; Sistem Keamanan Terpercaya
                    </div>
                </td>
                <td style="text-align:right">
                    <span class="status-badge status-{{ $salesOrder->status }}">
                        {{ strtoupper($salesOrder->status_label) }}
                    </span>
                    <div style="font-size:11px; color:#666; margin-top:5px;">
                        Dicetak: {{ now()->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="so-title">SURAT ORDER (SALES ORDER)</div>
    <div class="so-number">{{ $salesOrder->so_number }}</div>

    <table class="info-table">
        <tr>
            <td width="50%" style="vertical-align:top">
                <table>
                    <tr>
                        <td class="label">Tanggal SO</td>
                        <td>: {{ \Carbon\Carbon::parse($salesOrder->so_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Dibuat oleh</td>
                        <td>: {{ $salesOrder->creator_name ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="vertical-align:top">
                <table>
                    <tr>
                        <td class="label">Customer</td>
                        <td>: <strong>{{ $salesOrder->customer_name }}</strong></td>
                    </tr>
                    @if($salesOrder->customer_phone)
                    <tr>
                        <td class="label">No. HP</td>
                        <td>: {{ $salesOrder->customer_phone }}</td>
                    </tr>
                    @endif
                    @if($salesOrder->customer_email)
                    <tr>
                        <td class="label">Email</td>
                        <td>: {{ $salesOrder->customer_email }}</td>
                    </tr>
                    @endif
                    @if($salesOrder->customer_address)
                    <tr>
                        <td class="label">Alamat</td>
                        <td>: {{ $salesOrder->customer_address }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    @if($salesOrder->notes)
    <div class="notes-box">
        <strong>Catatan:</strong> {{ $salesOrder->notes }}
    </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Produk</th>
                <th width="10%">SKU</th>
                <th width="8%">Qty</th>
                <th width="18%">Harga Satuan</th>
                <th width="15%">Subtotal</th>
                <th width="14%">Serial Number</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal_items = 0; @endphp
            @foreach($salesOrder->items as $index => $item)
            @php $subtotal_items += $item->subtotal; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->sku ?? '-' }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                <td>
                    @if($item->serials && $item->serials->isNotEmpty())
                        <div class="sn-list">
                            @foreach($item->serials as $serial)
                                <span class="sn-item">{{ $serial->serial_number }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#999">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
            
            {{-- Bagian Perhitungan Akhir --}}
            <tr class="subtotal-row">
                <td colspan="5" style="text-align:right">Subtotal Produk</td>
                <td>Rp {{ number_format($subtotal_items, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            
            {{-- Tampilkan PPN hanya jika ada / dicentang --}}
            @if(isset($salesOrder->ppn_rate) && floatval($salesOrder->ppn_rate) > 0)
            <tr class="ppn-row">
                <td colspan="5" style="text-align:right">PPN ({{ floatval($salesOrder->ppn_rate) }}%)</td>
                <td>+ Rp {{ number_format($salesOrder->ppn_nominal ?? 0, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            @endif

            <tr class="total-row">
                <td colspan="5" style="text-align:right">TOTAL KESELURUHAN</td>
                <td>Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="sign-table">
        <tr>
            <td>
                <div>Hormat Kami,</div>
                <div class="sign-box"></div>
                <div>( Admin / Penjual )</div>
            </td>
            <td></td>
            <td>
                <div>Penerima,</div>
                <div class="sign-box"></div>
                <div>( {{ $salesOrder->customer_name }} )</div>
            </td>
        </tr>
    </table>

    <div class="footer" style="font-size:10px; color:#999; text-align:center; margin-top:20px;">
        Dokumen ini digenerate otomatis oleh sistem. &copy; {{ date('Y') }} TechStore.
    </div>
</body>
</html>