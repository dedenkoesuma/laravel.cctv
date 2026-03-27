<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembukuan - Admin TechStore</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .bookkeeping-container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn-add-transaction {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-add-transaction:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        
        .stat-card.modal { border-left-color: #dc3545; }
        .stat-card.selling { border-left-color: #28a745; }
        .stat-card.shipping { border-left-color: #ffc107; }
        .stat-card.profit { border-left-color: #667eea; }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }
        
        .stat-card.modal .stat-icon { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .stat-card.selling .stat-icon { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .stat-card.shipping .stat-icon { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .stat-card.profit .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }
        
        .stat-subtext {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* Filter Section */
        .filter-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: end;
        }
        
        .form-group { margin: 0; }
        
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-filter {
            padding: 10px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filter:hover {
            background: #5568d3;
        }
        
        /* Transactions Table */
        .transactions-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px 24px;
            border-bottom: 2px solid #f1f3f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .transactions-table thead {
            background: #f8f9fa;
        }
        
        .transactions-table th {
            padding: 16px;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .transactions-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 0.9rem;
            color: #495057;
        }
        
        .transactions-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        
        .text-success { color: #28a745 !important; font-weight: 600; }
        .text-danger { color: #dc3545 !important; font-weight: 600; }
        
        .btn-sm {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 16px;
        }
        
        .empty-state h3 {
            color: #6c757d;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            color: #adb5bd;
        }
        
        /* Back Button */
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .bookkeeping-container { padding: 16px; }
            .page-header { flex-direction: column; align-items: stretch; }
            .btn-add-transaction { width: 100%; justify-content: center; }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bookkeeping-container">
        <!-- Back Button -->
        <a href="/admin/dashboard" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-calculator"></i>
                <span>Pembukuan & Laporan</span>
            </h1>
            <a href="/admin/bookkeeping/create" class="btn-add-transaction">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Transaksi</span>
            </a>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card modal">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-label">Total Modal</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_modal'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-subtext">{{ $statistics['total_transactions'] ?? 0 }} transaksi</div>
            </div>
            
            <div class="stat-card selling">
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label">Total Penjualan</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_selling'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-subtext">Periode saat ini</div>
            </div>
            
            <div class="stat-card shipping">
                <div class="stat-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-label">Total Ongkir</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_shipping'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-subtext">Biaya pengiriman</div>
            </div>
            
            <div class="stat-card profit">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label">Total Keuntungan</div>
                <div class="stat-value">Rp {{ number_format($statistics['total_profit'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-subtext">
                    @php
                        $margin = ($statistics['total_selling'] ?? 0) > 0 
                            ? (($statistics['total_profit'] ?? 0) / $statistics['total_selling']) * 100 
                            : 0;
                    @endphp
                    Margin {{ number_format($margin, 1) }}%
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" action="{{ url('/admin/bookkeeping') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? date('Y-m-01') }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? date('Y-m-t') }}">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Transactions Table -->
        <div class="transactions-card">
            <div class="card-header">
                <h3 class="card-title">Daftar Transaksi</h3>
                <div class="action-buttons">
                    <button class="btn-sm btn-primary" onclick="exportData()">
                        <i class="bi bi-download"></i> Export
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                @if(isset($transactions) && $transactions->count() > 0)
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Produk</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Modal</th>
                            <th>Penjualan</th>
                            <th>Ongkir</th>
                            <th>Profit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td><strong>{{ $transaction->invoice_number }}</strong></td>
                            <td>{{ $transaction->product_name }}</td>
                            <td>
                                {{ $transaction->customer_name }}
                                @if($transaction->customer_phone)
                                    <br><small style="color: #6c757d;">{{ $transaction->customer_phone }}</small>
                                @endif
                            </td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>Rp {{ number_format($transaction->total_modal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->total_selling, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                            <td class="{{ $transaction->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($transaction->profit, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $transaction->status_badge ?? 'warning' }}">
                                    {{ $transaction->status_label ?? $transaction->payment_status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-sm btn-primary" onclick="editTransaction({{ $transaction->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-sm btn-danger" onclick="deleteTransaction({{ $transaction->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div style="padding: 20px;">
                    {{ $transactions->links() }}
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Belum Ada Transaksi</h3>
                    <p>Mulai tambahkan transaksi pertama Anda</p>
                    <a href="/admin/bookkeeping/create" class="btn-add-transaction" style="display: inline-flex; margin-top: 20px;">
                        <i class="bi bi-plus-circle"></i>
                        <span>Tambah Transaksi</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
    function editTransaction(id) {
        alert('Edit transaksi ID: ' + id);
        // window.location.href = `/admin/bookkeeping/${id}/edit`;
    }
    
    function deleteTransaction(id) {
        if (confirm('Yakin ingin menghapus transaksi ini?')) {
            fetch(`/admin/bookkeeping/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Transaksi berhasil dihapus!');
                    location.reload();
                }
            });
        }
    }
    
    function exportData() {
        const startDate = '{{ $startDate ?? date("Y-m-01") }}';
        const endDate = '{{ $endDate ?? date("Y-m-t") }}';
        window.location.href = `/admin/bookkeeping/export?start_date=${startDate}&end_date=${endDate}`;
    }
    </script>
</body>
</html>