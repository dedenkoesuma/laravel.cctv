<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barang Keluar - Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #EF4444;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --info-color: #3B82F6;
        }

        body {
            background: #F3F4F6;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .scanner-container {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            margin-bottom: 24px;
        }

        #serialNumberInput {
            height: 64px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            letter-spacing: 2px;
            border: 3px solid var(--primary-color);
            border-radius: 12px;
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0%, 100% { border-color: var(--primary-color); }
            50% { border-color: #F87171; }
        }

        .item-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            display: none;
        }

        .item-info-card.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .action-buttons-container {
            display: none;
        }

        .action-buttons-container.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .action-card.sold {
            border-color: #3B82F6;
        }

        .action-card.sold:hover {
            background: #EFF6FF;
        }

        .action-card.damaged {
            border-color: #EF4444;
        }

        .action-card.damaged:hover {
            background: #FEF2F2;
        }

        .action-card.returned {
            border-color: #F59E0B;
        }

        .action-card.returned:hover {
            background: #FEF3C7;
        }

        .action-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 12px;
        }

        .form-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            overflow-y: auto;
        }

        .form-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease;
        }

        .modal-content-custom {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            margin: 20px;
        }

        .success-animation {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10000;
            background: white;
            padding: 48px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            text-align: center;
        }

        .success-animation.show {
            display: block;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .scanner-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .recent-items {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }

        .item-card {
            border-left: 4px solid var(--primary-color);
            padding: 16px;
            margin-bottom: 12px;
            background: #F9FAFB;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">📤 Barang Keluar</h2>
                <p class="text-muted mb-0">Scan Serial Number untuk proses barang keluar</p>
            </div>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Scanner Section -->
                <div class="scanner-container">
                    <div class="text-center mb-4">
                        <span class="scanner-status bg-danger text-white">
                            <i class="bi bi-upc-scan"></i> Scan untuk Keluar
                        </span>
                    </div>

                    <div class="position-relative mb-3">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="serialNumberInput" 
                            placeholder="Scan atau ketik SN"
                            autocomplete="off"
                            autofocus
                        >
                        <i class="bi bi-upc-scan position-absolute end-0 top-50 translate-middle-y me-3" style="font-size: 32px; color: var(--primary-color);"></i>
                    </div>

                    <div class="text-center text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Scan atau ketik Serial Number barang yang akan keluar
                        </small>
                    </div>
                </div>

                <!-- Item Info Card -->
                <div class="item-info-card" id="itemInfoCard">
                    <h5 class="mb-3">
                        <i class="bi bi-box-seam"></i> Informasi Barang
                    </h5>
                    <div class="row g-3" id="itemInfo"></div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons-container" id="actionButtons">
                    <h5 class="mb-3">Pilih Aksi:</h5>

                    <div class="action-card sold" onclick="showActionForm('sold')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon" style="background: #DBEAFE; color: #3B82F6;">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Barang Terjual</h5>
                                <p class="text-muted mb-0">Barang keluar karena sudah dijual ke customer</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <div class="action-card damaged" onclick="showActionForm('damaged')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon" style="background: #FEE2E2; color: #EF4444;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Barang Rusak</h5>
                                <p class="text-muted mb-0">Tandai barang sebagai rusak atau cacat</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>

                    <div class="action-card returned" onclick="showActionForm('returned')">
                        <div class="d-flex align-items-center gap-3">
                            <div class="action-icon" style="background: #FEF3C7; color: #F59E0B;">
                                <i class="bi bi-arrow-return-left"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Barang Return</h5>
                                <p class="text-muted mb-0">Barang dikembalikan ke supplier</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Outgoing Sidebar -->
            <div class="col-lg-4">
                <div class="recent-items">
                    <h5 class="mb-3">
                        <i class="bi bi-clock-history"></i> Barang Keluar Hari Ini
                        <span class="badge bg-danger" id="todayCount">0</span>
                    </h5>
                    <div id="recentItemsList">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-0">Belum ada barang keluar hari ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sold Form Modal -->
    <div class="form-modal" id="soldModal">
        <div class="modal-content-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-cart-check text-primary"></i> Barang Terjual
                </h4>
                <button class="btn-close" onclick="closeModal('soldModal')"></button>
            </div>

            <form id="soldForm">
                <input type="hidden" id="soldSerialNumber">
                
                <div class="mb-3">
                    <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="customerName" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="customerPhone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="sellingPrice" required min="0" step="1000">
                    </div>
                    <div class="form-text">Harga beli: <span id="purchasePriceInfo">-</span></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" id="soldNotes" rows="2"></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Proses Penjualan
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="closeModal('soldModal')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Damaged Form Modal -->
    <div class="form-modal" id="damagedModal">
        <div class="modal-content-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Barang Rusak
                </h4>
                <button class="btn-close" onclick="closeModal('damagedModal')"></button>
            </div>

            <form id="damagedForm">
                <input type="hidden" id="damagedSerialNumber">
                
                <div class="mb-3">
                    <label class="form-label">Alasan Kerusakan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="damagedNotes" rows="3" required placeholder="Jelaskan kondisi dan penyebab kerusakan"></textarea>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i>
                    Barang akan ditandai sebagai rusak dan tidak bisa dijual
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="bi bi-check-circle"></i> Tandai Sebagai Rusak
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="closeModal('damagedModal')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Returned Form Modal -->
    <div class="form-modal" id="returnedModal">
        <div class="modal-content-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-arrow-return-left text-warning"></i> Return Barang
                </h4>
                <button class="btn-close" onclick="closeModal('returnedModal')"></button>
            </div>

            <form id="returnedForm">
                <input type="hidden" id="returnedSerialNumber">
                
                <div class="mb-3">
                    <label class="form-label">Alasan Return <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="returnedNotes" rows="3" required placeholder="Jelaskan alasan return ke supplier"></textarea>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Barang akan dikembalikan ke supplier
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="bi bi-check-circle"></i> Proses Return
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="closeModal('returnedModal')">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Animation -->
    <div class="success-animation" id="successAnimation">
        <div class="success-checkmark" id="successIcon"></div>
        <h4 class="mb-2" id="successTitle">Berhasil!</h4>
        <p class="text-muted" id="successMessage">Transaksi berhasil diproses</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        let currentItem = null;
        let currentAction = null;

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Handle serial number input
        let scanTimeout;
        document.getElementById('serialNumberInput').addEventListener('input', function(e) {
            const serialNumber = e.target.value.toUpperCase();
            e.target.value = serialNumber;
            
            clearTimeout(scanTimeout);
            
            scanTimeout = setTimeout(() => {
                if (serialNumber) {
                    checkItem(serialNumber);
                }
            }, 500);
        });

        // Check item
        async function checkItem(serialNumber) {
            try {
                const response = await fetch('/api/admin/inventory/check-serial', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ serial_number: serialNumber })
                });

                const data = await response.json();

                if (data.exists && data.item.status === 'in_stock') {
                    currentItem = data.item;
                    showItemInfo(data.item);
                } else if (data.exists && data.item.status !== 'in_stock') {
                    alert(`Barang tidak tersedia!\nStatus: ${data.item.status}`);
                    resetForm();
                } else {
                    alert('Serial Number tidak ditemukan!');
                    resetForm();
                }
            } catch (error) {
                console.error('Error checking item:', error);
                alert('Error checking item');
            }
        }

        // Show item info
        function showItemInfo(item) {
            const infoHtml = `
                <div class="col-md-6">
                    <small class="opacity-75">Serial Number</small>
                    <h6>${item.serial_number}</h6>
                </div>
                <div class="col-md-6">
                    <small class="opacity-75">Product Name</small>
                    <h6>${item.product_name}</h6>
                </div>
                <div class="col-md-4">
                    <small class="opacity-75">Brand</small>
                    <h6>${item.brand || '-'}</h6>
                </div>
                <div class="col-md-4">
                    <small class="opacity-75">Purchase Price</small>
                    <h6>${formatCurrency(item.purchase_price)}</h6>
                </div>
                <div class="col-md-4">
                    <small class="opacity-75">Location</small>
                    <h6>${item.warehouse_location || '-'}</h6>
                </div>
            `;

            document.getElementById('itemInfo').innerHTML = infoHtml;
            document.getElementById('itemInfoCard').classList.add('show');
            document.getElementById('actionButtons').classList.add('show');
        }

        // Show action form
        function showActionForm(action) {
            currentAction = action;
            const modalId = action + 'Modal';
            
            if (action === 'sold') {
                document.getElementById('soldSerialNumber').value = currentItem.serial_number;
                document.getElementById('purchasePriceInfo').textContent = formatCurrency(currentItem.purchase_price);
                // Pre-fill with purchase price + margin
                const suggestedPrice = Math.round(currentItem.purchase_price * 1.2);
                document.getElementById('sellingPrice').value = suggestedPrice;
            } else if (action === 'damaged') {
                document.getElementById('damagedSerialNumber').value = currentItem.serial_number;
            } else if (action === 'returned') {
                document.getElementById('returnedSerialNumber').value = currentItem.serial_number;
            }
            
            document.getElementById(modalId).classList.add('show');
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Handle sold form
        document.getElementById('soldForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            await processOutgoing('sold', {
                customer_name: document.getElementById('customerName').value,
                customer_phone: document.getElementById('customerPhone').value,
                selling_price: document.getElementById('sellingPrice').value,
                notes: document.getElementById('soldNotes').value
            });
        });

        // Handle damaged form
        document.getElementById('damagedForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            await processOutgoing('damaged', {
                notes: document.getElementById('damagedNotes').value
            });
        });

        // Handle returned form
        document.getElementById('returnedForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            await processOutgoing('returned', {
                notes: document.getElementById('returnedNotes').value
            });
        });

        // Process outgoing
        async function processOutgoing(action, additionalData) {
            try {
                const requestData = {
                    serial_number: currentItem.serial_number,
                    action: action,
                    ...additionalData
                };

                const response = await fetch('/api/admin/inventory/outgoing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();

                if (data.success) {
                    // Close modal
                    closeModal(action + 'Modal');
                    
                    // Show success animation
                    const colors = {
                        'sold': '#3B82F6',
                        'damaged': '#EF4444',
                        'returned': '#F59E0B'
                    };
                    
                    const messages = {
                        'sold': 'Barang berhasil dijual!',
                        'damaged': 'Barang ditandai sebagai rusak',
                        'returned': 'Barang berhasil di-return'
                    };
                    
                    document.getElementById('successIcon').style.background = colors[action];
                    document.getElementById('successTitle').textContent = 'Berhasil!';
                    document.getElementById('successMessage').textContent = messages[action];
                    document.getElementById('successAnimation').classList.add('show');
                    
                    setTimeout(() => {
                        document.getElementById('successAnimation').classList.remove('show');
                        resetForm();
                        loadRecentItems();
                        document.getElementById('serialNumberInput').focus();
                    }, 2000);
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error processing outgoing:', error);
                alert('Error processing item');
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('serialNumberInput').value = '';
            document.getElementById('itemInfoCard').classList.remove('show');
            document.getElementById('actionButtons').classList.remove('show');
            
            // Reset forms
            document.getElementById('soldForm').reset();
            document.getElementById('damagedForm').reset();
            document.getElementById('returnedForm').reset();
            
            currentItem = null;
            currentAction = null;
        }

        // Load recent items
        async function loadRecentItems() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch(`/api/admin/inventory/items?date_from=${today}&status=sold,damaged,returned&sort_by=exit_date&sort_order=desc&per_page=10`);
                const data = await response.json();

                if (data.success && data.items.data.length > 0) {
                    document.getElementById('todayCount').textContent = data.items.total;
                    
                    const badgeColors = {
                        'sold': 'primary',
                        'damaged': 'danger',
                        'returned': 'warning'
                    };
                    
                    const listHtml = data.items.data.map(item => `
                        <div class="item-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>${item.serial_number}</strong>
                                <span class="badge bg-${badgeColors[item.status]}">${item.exit_reason}</span>
                            </div>
                            <div class="text-muted small">${item.product_name}</div>
                            <div class="text-muted small">
                                <i class="bi bi-clock"></i> 
                                ${new Date(item.exit_date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                            </div>
                        </div>
                    `).join('');

                    document.getElementById('recentItemsList').innerHTML = listHtml;
                } else {
                    document.getElementById('todayCount').textContent = '0';
                }
            } catch (error) {
                console.error('Error loading recent items:', error);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadRecentItems();
            setInterval(loadRecentItems, 30000);
        });
    </script>
</body>
</html>