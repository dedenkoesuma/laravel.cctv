<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembukuan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16pt;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .report-period {
            font-size: 10pt;
            color: #666;
        }
        
        /* Summary Section */
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 8px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-label {
            font-weight: bold;
            width: 40%;
        }
        
        .summary-value {
            text-align: right;
            font-weight: 600;
        }
        
        .summary-total {
            background: #667eea;
            color: white;
            font-size: 11pt;
            font-weight: bold;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #5568d3;
        }
        
        td {
            padding: 6px;
            border: 1px solid #dee2e6;
            font-size: 8pt;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #6c757d;
        }
        
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-line {
            display: inline-block;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 50px;
            min-width: 200px;
        }
        
        /* Page break */
        .page-break {
            page-break-after: always;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">TECHSTORE</div>
            <div class="report-title">Laporan Pembukuan Transaksi</div>
            <div class="report-period">
                Periode: {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="summary">
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Transaksi:</div>
                <div class="summary-cell summary-value">{{ $statistics['total_transactions'] }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Modal:</div>
                <div class="summary-cell summary-value">Rp {{ number_format($statistics['total_modal'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Penjualan:</div>
                <div class="summary-cell summary-value">Rp {{ number_format($statistics['total_selling'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Ongkir:</div>
                <div class="summary-cell summary-value">Rp {{ number_format($statistics['total_shipping'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Biaya Tambahan:</div>
                <div class="summary-cell summary-value">Rp {{ number_format($statistics['total_additional_cost'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label">Total Biaya:</div>
                <div class="summary-cell summary-value">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-cell summary-label summary-total">TOTAL KEUNTUNGAN:</div>
                <div class="summary-cell summary-value summary-total">Rp {{ number_format($statistics['total_profit'], 0, ',', '.') }}</div>
            </div>
        </div>
        
        <!-- Transactions Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 8%;">Tanggal</th>
                    <th style="width: 12%;">Invoice</th>
                    <th style="width: 18%;">Produk</th>
                    <th style="width: 12%;">Customer</th>
                    <th style="width: 5%;" class="text-center">Qty</th>
                    <th style="width: 10%;" class="text-right">Total Modal</th>
                    <th style="width: 10%;" class="text-right">Total Jual</th>
                    <th style="width: 10%;" class="text-right">Profit</th>
                    <th style="width: 7%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->product_name }}</td>
                    <td>
                        {{ $transaction->customer_name }}
                        @if($transaction->customer_phone)
                            <br><small style="color: #6c757d;">{{ $transaction->customer_phone }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $transaction->quantity }}</td>
                    <td class="text-right">{{ number_format($transaction->total_modal, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($transaction->total_selling, 0, ',', '.') }}</td>
                    <td class="text-right {{ $transaction->profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($transaction->profit, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $transaction->status_badge ?? 'warning' }}">
                            {{ $transaction->status_label ?? $transaction->payment_status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Signature -->
        <div class="signature">
            <div>Dicetak pada: {{ date('d F Y H:i:s') }}</div>
            <div class="signature-line">
                Admin
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Laporan Pembukuan - TECHSTORE</div>
            <div>Dokumen ini dicetak secara otomatis dari sistem</div>
        </div>
    </div>
</body>
</html>