<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory Management - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4F46E5;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #3B82F6;
        }

        body {
            background: #F3F4F6;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stats-value {
            font-size: 32px;
            font-weight: 700;
            margin: 12px 0 4px;
        }

        .stats-label {
            color: #6B7280;
            font-size: 14px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .stock-badge {
            font-size: 18px;
            padding: 8px 16px;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">📦 Inventory Management</h2>
                <p class="text-muted mb-0">Monitor and manage your inventory items</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.inventory.incoming') }}" class="btn btn-success">
                    <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
                </a>
                <a href="{{ route('admin.inventory.outgoing') }}" class="btn btn-danger">
                    <i class="bi bi-box-arrow-up"></i> Barang Keluar
                </a>
                <a href="{{ route('admin.inventory.reports') }}" class="btn btn-info">
                    <i class="bi bi-file-earmark-text"></i> Reports
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Items -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-icon" style="background: #EEF2FF; color: var(--primary-color);">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark">Total</span>
                    </div>
                    <div class="stats-value" id="totalItems">{{ $totalItems ?? 0 }}</div>
                    <div class="stats-label">Total Items</div>
                </div>
            </div>

            <!-- ✅ Total Stock (NEW) -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-icon" style="background: #DBEAFE; color: var(--info-color);">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                        <span class="badge bg-info">Stock</span>
                    </div>
                    <div class="stats-value text-info" id="totalStock">{{ $totalStock ?? 0 }}</div>
                    <div class="stats-label">Total Stock Units</div>
                </div>
            </div>

            <!-- In Stock -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-icon" style="background: #ECFDF5; color: var(--success-color);">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <span class="badge bg-success">Available</span>
                    </div>
                    <div class="stats-value text-success" id="inStock">{{ $inStock ?? 0 }}</div>
                    <div class="stats-label">Products Available</div>
                </div>
            </div>

            <!-- Sold -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stats-icon" style="background: #EFF6FF; color: var(--info-color);">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                        <span class="badge bg-primary">Sold</span>
                    </div>
                    <div class="stats-value text-primary" id="sold">-</div>
                    <div class="stats-label">Items Sold</div>
                </div>
            </div>
        </div>

        <!-- Stock Status Cards (NEW) -->
        <div class="row g-3 mb-4">
            <!-- Low Stock -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #FEF3C7; color: var(--warning-color);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Low Stock Alert</div>
                            <div class="stats-value text-warning" style="font-size: 24px;" id="lowStock">{{ $lowStock ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #FEE2E2; color: var(--danger-color);">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Out of Stock</div>
                            <div class="stats-value text-danger" style="font-size: 24px;" id="outOfStock">{{ $outOfStock ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Damaged -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #FEF2F2; color: var(--danger-color);">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Damaged Items</div>
                            <div class="stats-value text-danger" style="font-size: 24px;" id="damaged">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #FEF3C7; color: var(--warning-color);">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Total Stock Value</div>
                            <div class="stats-value" style="font-size: 24px;" id="totalValue">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #DBEAFE; color: var(--info-color);">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Total Revenue</div>
                            <div class="stats-value" style="font-size: 24px;" id="totalRevenue">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stats-icon" style="background: #D1FAE5; color: var(--success-color);">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stats-label">Total Profit</div>
                            <div class="stats-value" style="font-size: 24px;" id="totalProfit">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📋 Inventory Items</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    <button class="btn btn-outline-secondary" onclick="loadItems()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Serial Number</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th class="text-center">Stock</th>
                            <th>Status</th>
                            <th>Entry Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($items as $item)
                        <tr>
                            <td><strong class="text-primary">{{ $item->serial_number }}</strong></td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->brand ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge stock-badge bg-{{ $item->stock_status_color ?? 'secondary' }}">
                                    {{ $item->stock ?? 0 }}
                                </span>
                            </td>
                            <td>
                                @if($item->status == 'in_stock')
                                    <span class="badge bg-success">In Stock</span>
                                @elseif($item->status == 'sold')
                                    <span class="badge bg-primary">Sold</span>
                                @elseif($item->status == 'damaged')
                                    <span class="badge bg-danger">Damaged</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->entry_date ? $item->entry_date->format('d M Y, H:i') : '-' }}</td>
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <button class="btn btn-sm btn-info" onclick="viewItem({{ $item->id }})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editStock({{ $item->id }}, {{ $item->stock ?? 0 }}, '{{ $item->product_name }}')" title="Manage Stock">
                                        <i class="fas fa-boxes"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $item->id }})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-2 mb-0">No inventory items found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="d-flex justify-content-center mt-3"></div>
        </div>
    </div>

    <!-- ✅ Modal Edit Stock (NEW) -->
    <div class="modal fade" id="modalEditStock" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-boxes"></i> Kelola Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditStock">
                    @csrf
                    <input type="hidden" id="itemId">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Produk:</strong> <span id="productName"></span><br>
                            <strong>Stock Saat Ini:</strong> <span id="currentStock" class="fs-4">0</span> unit
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="transactionType" required>
                                <option value="masuk">Barang Masuk (+)</option>
                                <option value="keluar">Barang Keluar (-)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg" name="quantity" id="quantity" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Format date
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Get status badge HTML
        function getStatusBadge(status) {
            const badges = {
                'in_stock': '<span class="badge bg-success">In Stock</span>',
                'sold': '<span class="badge bg-primary">Sold</span>',
                'damaged': '<span class="badge bg-danger">Damaged</span>',
                'returned': '<span class="badge bg-warning">Returned</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('/api/admin/inventory/statistics');
                const data = await response.json();
                
                if (data.success) {
                    const stats = data.statistics;
                    
                    document.getElementById('totalItems').textContent = stats.total_items;
                    document.getElementById('totalStock').textContent = stats.total_stock || 0;
                    document.getElementById('inStock').textContent = stats.in_stock;
                    document.getElementById('sold').textContent = stats.sold;
                    document.getElementById('damaged').textContent = stats.damaged;
                    document.getElementById('lowStock').textContent = stats.low_stock || 0;
                    document.getElementById('outOfStock').textContent = stats.out_of_stock || 0;
                    
                    document.getElementById('totalValue').textContent = formatCurrency(stats.total_value);
                    document.getElementById('totalRevenue').textContent = formatCurrency(stats.total_revenue);
                    document.getElementById('totalProfit').textContent = formatCurrency(stats.total_profit);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // ✅ Edit Stock Function (NEW)
        function editStock(id, currentStock, productName) {
            $('#itemId').val(id);
            $('#currentStock').text(currentStock);
            $('#productName').text(productName);
            $('#quantity').val('');
            $('#notes').val('');
            $('#transactionType').val('masuk');
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditStock'));
            modal.show();
        }

        // ✅ Form Submit Handler (NEW)
        $('#formEditStock').submit(function(e) {
            e.preventDefault();
            
            const id = $('#itemId').val();
            const btn = $(this).find('button[type="submit"]');
            const btnText = btn.html();
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            
            $.ajax({
                url: `/api/admin/inventory/items/${id}/update-stock`,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan');
                    btn.prop('disabled', false).html(btnText);
                }
            });
        });

        // View item detail
        async function viewItem(id) {
            alert('View item detail: ' + id);
            // Implement modal untuk detail
        }

        // Delete item
        async function deleteItem(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) return;
            
            try {
                const response = await fetch(`/api/admin/inventory/items/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Item berhasil dihapus');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                alert('Error deleting item');
            }
        }

        // Search on Enter
        document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                location.reload();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            
            // Refresh stats every 30 seconds
            setInterval(() => {
                loadStatistics();
            }, 30000);
        });
    </script>
</body>
</html>