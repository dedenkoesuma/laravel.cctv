<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f8; margin:0; padding:20px; }
        .wrap { max-width:580px; margin:0 auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
        .top-bar { background:#1a56db; padding:20px 28px; }
        .top-bar h1 { color:#fff; font-size:20px; margin:0; }
        .top-bar p  { color:#bfdbfe; font-size:12px; margin:4px 0 0; }
        .body { padding:24px 28px; }
        .body p { color:#374151; font-size:13px; line-height:1.6; margin:0 0 10px; }
        .detail-box { background:#f8fafc; border:1px solid #e5e7eb; border-radius:6px; padding:14px 18px; margin:14px 0; }
        .detail-box table { width:100%; font-size:12px; }
        .detail-box td { padding:4px 0; vertical-align:top; }
        .detail-box td:first-child { color:#6b7280; width:130px; }
        .detail-box td:last-child { font-weight:600; color:#111; }
        .rek-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:12px 18px; margin:14px 0; }
        .rek-box h4 { font-size:11px; font-weight:700; text-transform:uppercase; color:#1a56db; margin:0 0 8px; }
        .rek-box .rek-val { font-size:15px; font-weight:700; color:#1e3a5f; }
        .total-badge { background:#d1fae5; border:1px solid #10b981; border-radius:6px; padding:10px 18px; text-align:center; margin:14px 0; }
        .total-badge .label { font-size:11px; color:#065f46; }
        .total-badge .amount { font-size:22px; font-weight:700; color:#065f46; }
        .footer { background:#f9fafb; border-top:1px solid #e5e7eb; padding:14px 28px; text-align:center; font-size:11px; color:#9ca3af; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="top-bar">
        <h1>Invoice {{ $invoice->invoice_number }}</h1>
        <p>TechStore — {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</p>
    </div>
    <div class="body">
        <p>Yth. <strong>{{ $invoice->pihak_terkait }}</strong>,</p>
        <p>
            Berikut kami lampirkan Invoice atas transaksi
            <strong>{{ $invoice->so_number }}</strong>.
            Mohon segera melakukan pembayaran sesuai detail di bawah.
        </p>

        <div class="detail-box">
            <table>
                <tr><td>No. Invoice</td><td>{{ $invoice->invoice_number }}</td></tr>
                <tr><td>No. SO</td><td>{{ $invoice->so_number }}</td></tr>
                <tr><td>Tanggal Invoice</td><td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td></tr>
                <tr><td>Tipe Pembayaran</td>
                    <td>
                        @if($invoice->tipe_bayar === 'tempo')
                            Tempo {{ $invoice->tempo_hari }} hari
                            (Jatuh tempo: <strong>{{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}</strong>)
                        @else
                            Cash
                        @endif
                    </td>
                </tr>
                <tr><td>Metode Bayar</td><td>{{ ucfirst($invoice->metode_bayar) }}</td></tr>
            </table>
        </div>

        <div class="total-badge">
            <div class="label">Total Tagihan</div>
            <div class="amount">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</div>
        </div>

        <div class="rek-box">
            <h4>Rekening Tujuan Pembayaran</h4>
            <div class="rek-val">{{ $invoice->nama_bank }}</div>
            <div class="rek-val">{{ $invoice->no_rekening }}</div>
            <div style="font-size:12px;color:#374151;margin-top:3px">a.n. {{ $invoice->nama_rekening }}</div>
        </div>

        @if($invoice->catatan)
        <p><strong>Catatan:</strong> {{ $invoice->catatan }}</p>
        @endif

        <p>Invoice PDF terlampir dalam email ini. Harap konfirmasi setelah melakukan pembayaran.</p>
        <p>Terima kasih atas kepercayaan Anda kepada <strong>TechStore</strong>. 🙏</p>
    </div>
    <div class="footer">
        © {{ now()->year }} TechStore. Email ini dikirim otomatis, harap tidak membalas.
    </div>
</div>
</body>
</html>