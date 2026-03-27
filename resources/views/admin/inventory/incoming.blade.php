<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barang Masuk - Enhanced Scan Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4F46E5;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .scan-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }

        .mode-selector {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .mode-card {
            background: white;
            border: 3px solid #e5e7eb;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .mode-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        }

        .mode-card.active {
            border-color: var(--primary);
            background: linear-gradient(135deg, #667eea15, #764ba215);
        }

        .mode-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--warning);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .mode-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        /* Bulk Scan Area */
        .bulk-scan-area {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            display: none;
        }

        .bulk-scan-area.active {
            display: block;
            animation: slideDown 0.3s;
        }

        .scan-textarea {
            width: 100%;
            min-height: 300px;
            padding: 20px;
            font-size: 16px;
            border: 3px solid #10B981;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            resize: vertical;
        }

        .scan-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        /* Product Info Form */
        .product-info-form {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .product-info-form .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .product-info-form .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
        }

        .product-info-form .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Quick Scan Input */
        .scan-input-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            display: none;
        }

        .scan-input-container.active {
            display: block;
        }

        .scan-input {
            width: 100%;
            padding: 20px;
            font-size: 20px;
            border: 3px solid #10B981;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s;
        }

        .scan-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .stat-value {
            font-size: 48px;
            font-weight: 700;
            margin: 10px 0;
        }

        .stat-label {
            color: #6B7280;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .queue-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-height: 500px;
            overflow-y: auto;
        }

        .queue-item {
            background: #f8f9fa;
            border-left: 5px solid #6B7280;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .queue-item.processing {
            border-left-color: var(--warning);
            background: #FEF3C7;
            animation: pulse 2s infinite;
        }

        .queue-item.success {
            border-left-color: var(--success);
            background: #D1FAE5;
        }

        .queue-item.error {
            border-left-color: var(--danger);
            background: #FEE2E2;
        }

        .queue-item.duplicate {
            border-left-color: var(--info);
            background: #DBEAFE;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }

        .badge-pending { background: #E5E7EB; color: #6B7280; }
        .badge-processing { background: var(--warning); color: white; }
        .badge-success { background: var(--success); color: white; }
        .badge-error { background: var(--danger); color: white; }
        .badge-duplicate { background: var(--info); color: white; }

        .ready-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 25px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            animation: glow 2s infinite;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
            50% { box-shadow: 0 0 30px rgba(16, 185, 129, 0.8); }
        }

        .btn-process {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 18px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-process:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
        }

        .btn-process:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-clear {
            background: var(--danger);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-clear:hover {
            background: #DC2626;
            transform: translateY(-2px);
        }

        .processing-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .processing-overlay.active {
            display: flex;
        }

        .processing-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }

        .spinner {
            width: 80px;
            height: 80px;
            border: 8px solid #f3f3f3;
            border-top: 8px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .progress-bar-custom {
            height: 30px;
            border-radius: 15px;
            background: #e5e7eb;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success), var(--primary));
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .help-text {
            background: #FEF3C7;
            border-left: 4px solid var(--warning);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .help-text i {
            color: var(--warning);
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="scan-container">
        <!-- Header -->
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-box-arrow-in-down"></i> Barang Masuk - Enhanced Scan
                    </h2>
                    <p class="text-muted mb-0">
                        <span class="badge bg-success">✨ Bulk Mode Available</span>
                        Scan ratusan barang sekaligus dengan mudah
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/admin/inventory" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                    <button class="btn btn-success" onclick="exportData()">
                        <i class="bi bi-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Mode Selector -->
        <div class="mode-selector">
            <div class="mode-card" id="quickMode" onclick="selectMode('quick')">
                <div class="mode-icon">⚡</div>
                <h5>Quick Mode</h5>
                <p class="text-muted mb-0">Scan satu-satu<br>Auto-save (1-2s)</p>
            </div>
            
            <div class="mode-card active" id="bulkMode" onclick="selectMode('bulk')">
                <span class="mode-badge">NEW</span>
                <div class="mode-icon">📦</div>
                <h5>Bulk Scan Mode</h5>
                <p class="text-muted mb-0">Scan ratusan SN<br>Sekaligus (30-300+)</p>
            </div>
            
            <div class="mode-card" id="fullMode" onclick="selectMode('full')">
                <div class="mode-icon">✏️</div>
                <h5>Full Mode</h5>
                <p class="text-muted mb-0">Edit lengkap<br>Detail per item</p>
            </div>
        </div>

        <!-- Quick Scan Input (Mode 1) -->
        <div class="scan-input-container" id="quickScanArea">
            <div class="ready-indicator mb-4">
                <i class="bi bi-wifi"></i>
                <span>Ready to Scan - Quick Mode</span>
            </div>
            
            <input 
                type="text" 
                class="scan-input" 
                id="scanInputQuick" 
                placeholder="SCAN BARCODE"
                autocomplete="off"
            >
            <p class="text-center text-muted mt-3 mb-0">
                <i class="bi bi-info-circle"></i> Scan barcode atau ketik manual lalu Enter
            </p>
        </div>

        <!-- Bulk Scan Area (Mode 2) - NEW -->
        <div class="bulk-scan-area active" id="bulkScanArea">
            <div class="help-text">
                <i class="bi bi-lightbulb-fill"></i>
                <strong>Cara Pakai Bulk Mode:</strong> Scan semua SN secara berurutan (otomatis pindah baris), atau paste dari Excel. Isi info produk sekali untuk semua SN.
            </div>

            <!-- Product Info Form (Filled Once for All SNs) -->
            <div class="product-info-form">
                <h5 class="mb-3"><i class="bi bi-box-seam"></i> Informasi Produk (Untuk Semua SN)</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bulkProductName" placeholder="Contoh: IP Camera 4MP" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control" id="bulkBrand" placeholder="Contoh: Hikvision">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" id="bulkCategory" placeholder="Contoh: IP Camera">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="bulkPurchasePrice" placeholder="0" step="0.01" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="bulkSellingPrice" placeholder="0" step="0.01">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="bulkSupplier" placeholder="Nama Supplier">
                    </div>
                </div>
            </div>

            <!-- Textarea for Multiple SNs -->
            <label class="form-label fw-bold">
                <i class="bi bi-upc-scan"></i> Serial Numbers (Satu SN per baris)
            </label>
            <textarea 
                class="scan-textarea" 
                id="bulkSerialInput" 
                placeholder="Scan atau paste serial numbers di sini...&#10;SN001&#10;SN002&#10;SN003&#10;...&#10;&#10;Atau scan dengan barcode scanner (otomatis pindah baris per scan)"
                autofocus
            ></textarea>

            <div class="d-flex gap-3 mt-3">
                <button class="btn-process" onclick="processBulkScan()">
                    <i class="bi bi-lightning-charge-fill"></i> Process All (<span id="bulkCount">0</span> SN)
                </button>
                <button class="btn btn-outline-secondary" onclick="clearBulkInput()">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-label">Scanned</div>
                <div class="stat-value text-primary" id="scannedCount">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Saved</div>
                <div class="stat-value text-success" id="savedCount">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Duplicates</div>
                <div class="stat-value text-info" id="duplicateCount">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Errors</div>
                <div class="stat-value text-danger" id="errorCount">0</div>
            </div>
        </div>

        <!-- Scan Queue -->
        <div class="queue-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul"></i> Scan Queue
                </h5>
                <button class="btn-clear" onclick="clearQueue()">
                    <i class="bi bi-trash"></i> Clear
                </button>
            </div>

            <div id="queueList">
                <div class="empty-state text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 64px; margin-bottom: 20px;"></i>
                    <p>Belum ada item yang di-scan</p>
                    <small>Mulai scan barcode untuk menambahkan item</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Overlay -->
    <div class="processing-overlay" id="processingOverlay">
        <div class="processing-content">
            <div class="spinner"></div>
            <h4 id="processingTitle">Processing...</h4>
            <div class="progress-bar-custom">
                <div class="progress-fill" id="progressFill" style="width: 0%">0%</div>
            </div>
            <p class="text-muted mb-0" id="processingText">Menyimpan data ke database</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentMode = 'bulk';
        let scanQueue = [];
        let scannedCount = 0;
        let savedCount = 0;
        let duplicateCount = 0;
        let errorCount = 0;
        let isProcessing = false;

        // ===================================
        // MODE SELECTION
        // ===================================
        function selectMode(mode) {
            currentMode = mode;
            
            // Update UI
            document.getElementById('quickMode').classList.toggle('active', mode === 'quick');
            document.getElementById('bulkMode').classList.toggle('active', mode === 'bulk');
            document.getElementById('fullMode').classList.toggle('active', mode === 'full');

            // Show/hide appropriate areas
            document.getElementById('quickScanArea').classList.toggle('active', mode === 'quick');
            document.getElementById('bulkScanArea').classList.toggle('active', mode === 'bulk');

            if (mode === 'quick') {
                document.getElementById('scanInputQuick').focus();
            } else if (mode === 'bulk') {
                document.getElementById('bulkSerialInput').focus();
            }
        }

        // ===================================
        // BULK MODE - COUNT SNs
        // ===================================
        document.getElementById('bulkSerialInput').addEventListener('input', function() {
            const text = this.value.trim();
            const lines = text.split('\n').filter(line => line.trim() !== '');
            document.getElementById('bulkCount').textContent = lines.length;
        });

        // ===================================
        // BULK MODE - PROCESS ALL SNs
        // ===================================
        async function processBulkScan() {
            const productName = document.getElementById('bulkProductName').value.trim();
            const purchasePrice = document.getElementById('bulkPurchasePrice').value;
            const serialText = document.getElementById('bulkSerialInput').value.trim();

            // Validation
            if (!productName) {
                alert('Nama Produk harus diisi!');
                document.getElementById('bulkProductName').focus();
                return;
            }

            if (!purchasePrice || parseFloat(purchasePrice) <= 0) {
                alert('Harga Beli harus diisi dan lebih dari 0!');
                document.getElementById('bulkPurchasePrice').focus();
                return;
            }

            if (!serialText) {
                alert('Tidak ada Serial Number yang di-input!');
                document.getElementById('bulkSerialInput').focus();
                return;
            }

            // Parse serial numbers
            const serialNumbers = serialText.split('\n')
                .map(sn => sn.trim().toUpperCase())
                .filter(sn => sn !== '');

            if (serialNumbers.length === 0) {
                alert('Tidak ada Serial Number yang valid!');
                return;
            }

            // Remove duplicates in input
            const uniqueSerials = [...new Set(serialNumbers)];
            const duplicatesInInput = serialNumbers.length - uniqueSerials.length;

            if (duplicatesInInput > 0) {
                if (!confirm(`Ada ${duplicatesInInput} SN duplikat dalam input. Lanjutkan dengan ${uniqueSerials.length} SN unik?`)) {
                    return;
                }
            }

            // Prepare product data
            const productData = {
                product_name: productName,
                brand: document.getElementById('bulkBrand').value.trim() || null,
                category: document.getElementById('bulkCategory').value.trim() || null,
                purchase_price: parseFloat(purchasePrice),
                selling_price: parseFloat(document.getElementById('bulkSellingPrice').value) || 0,
                supplier: document.getElementById('bulkSupplier').value.trim() || null,
            };

            // Show processing overlay
            showProcessing(`Processing ${uniqueSerials.length} items...`, 0);

            try {
                // Process in batches of 10
                const batchSize = 10;
                let processed = 0;

                for (let i = 0; i < uniqueSerials.length; i += batchSize) {
                    const batch = uniqueSerials.slice(i, i + batchSize);
                    
                    // Prepare batch data
                    const items = batch.map(sn => ({
                        serial_number: sn,
                        ...productData
                    }));

                    // Send batch request
                    const response = await fetch('/api/admin/inventory/incoming-batch-optimized', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ items: items })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Add to queue
                        data.saved_items.forEach(item => {
                            addToQueueDisplay(item.serial_number, 'success', `Stock +1 (Total: ${item.stock || 1})`);
                            savedCount++;
                        });

                        data.errors.forEach(error => {
                            if (error.error && error.error.includes('sudah terdaftar')) {
                                addToQueueDisplay(error.serial_number, 'duplicate', 'SN sudah ada');
                                duplicateCount++;
                            } else {
                                addToQueueDisplay(error.serial_number, 'error', error.error);
                                errorCount++;
                            }
                        });
                    } else {
                        batch.forEach(sn => {
                            addToQueueDisplay(sn, 'error', data.error || 'Unknown error');
                            errorCount++;
                        });
                    }

                    processed += batch.length;
                    scannedCount = processed;
                    updateStats();
                    updateProgress(processed, uniqueSerials.length);

                    // Small delay between batches
                    await new Promise(resolve => setTimeout(resolve, 300));
                }

                hideProcessing();
                playSound('complete');

                // Show summary
                const summary = `
                    ✅ Berhasil: ${savedCount} item
                    ${duplicateCount > 0 ? `ℹ️ Duplikat: ${duplicateCount} item` : ''}
                    ${errorCount > 0 ? `❌ Error: ${errorCount} item` : ''}
                `;
                alert('Bulk scan selesai!\n\n' + summary);

                // Clear input
                clearBulkInput();

            } catch (error) {
                hideProcessing();
                console.error('Bulk scan error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        // ===================================
        // QUEUE DISPLAY
        // ===================================
        function addToQueueDisplay(serial, status, message = '') {
            const item = {
                serial: serial,
                status: status,
                timestamp: new Date(),
                message: message
            };

            scanQueue.unshift(item); // Add to beginning
            
            // Keep only last 100 items
            if (scanQueue.length > 100) {
                scanQueue = scanQueue.slice(0, 100);
            }

            renderQueue();
        }

        function renderQueue() {
            const queueList = document.getElementById('queueList');
            
            if (scanQueue.length === 0) {
                queueList.innerHTML = `
                    <div class="empty-state text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 64px; margin-bottom: 20px;"></i>
                        <p>Belum ada item yang di-scan</p>
                        <small>Mulai scan barcode untuk menambahkan item</small>
                    </div>
                `;
                return;
            }

            queueList.innerHTML = scanQueue.map(item => {
                let statusBadge = '';
                let statusClass = '';
                
                switch(item.status) {
                    case 'pending':
                        statusBadge = '<span class="status-badge badge-pending">Pending</span>';
                        break;
                    case 'processing':
                        statusBadge = '<span class="status-badge badge-processing">Processing...</span>';
                        statusClass = 'processing';
                        break;
                    case 'success':
                        statusBadge = '<span class="status-badge badge-success">✓ Saved</span>';
                        statusClass = 'success';
                        break;
                    case 'duplicate':
                        statusBadge = '<span class="status-badge badge-duplicate">ℹ️ Duplicate</span>';
                        statusClass = 'duplicate';
                        break;
                    case 'error':
                        statusBadge = '<span class="status-badge badge-error">✗ Error</span>';
                        statusClass = 'error';
                        break;
                }

                return `
                    <div class="queue-item ${statusClass}">
                        <div>
                            <strong>${item.serial}</strong>
                            <br>
                            <small class="text-muted">
                                ${item.timestamp.toLocaleTimeString('id-ID')}
                                ${item.message ? ' • ' + item.message : ''}
                            </small>
                        </div>
                        ${statusBadge}
                    </div>
                `;
            }).join('');

            // Auto-scroll to top
            queueList.scrollTop = 0;
        }

        // ===================================
        // STATISTICS
        // ===================================
        function updateStats() {
            document.getElementById('scannedCount').textContent = scannedCount;
            document.getElementById('savedCount').textContent = savedCount;
            document.getElementById('duplicateCount').textContent = duplicateCount;
            document.getElementById('errorCount').textContent = errorCount;
        }

        // ===================================
        // PROCESSING OVERLAY
        // ===================================
        function showProcessing(text, progress = 0) {
            const overlay = document.getElementById('processingOverlay');
            document.getElementById('processingText').textContent = text;
            updateProgress(0, 100);
            overlay.classList.add('active');
        }

        function hideProcessing() {
            const overlay = document.getElementById('processingOverlay');
            overlay.classList.remove('active');
        }

        function updateProgress(current, total) {
            const percent = Math.round((current / total) * 100);
            const fill = document.getElementById('progressFill');
            fill.style.width = percent + '%';
            fill.textContent = percent + '%';
            document.getElementById('processingText').textContent = `${current} of ${total} items processed`;
        }

        // ===================================
        // UTILITIES
        // ===================================
        function clearBulkInput() {
            document.getElementById('bulkSerialInput').value = '';
            document.getElementById('bulkCount').textContent = '0';
            // Removed auto-focus to allow user to freely edit other fields
        }

        function clearQueue() {
            if (confirm('Hapus semua item dari queue?')) {
                scanQueue = [];
                scannedCount = 0;
                savedCount = 0;
                duplicateCount = 0;
                errorCount = 0;
                updateStats();
                renderQueue();
            }
        }

        function playSound(type) {
            const audio = new Audio();
            switch(type) {
                case 'scan':
                    audio.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTcIGWi77eefTRAMUKfj8LZjHAY4kdfyzHksBSR3x/DdkEAKFF606OuoVRQKRp/g8r5sIQUrgc7y2Yk3CBlou+3nn00QDFCn4/C2YxwGOJHX8sx5LAUkd8fw3ZBAAhRet'; 
                    break;
                case 'error':
                    audio.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQAcAAEAHAAABAAgAZGF0YQoGAACAgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmqq6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/w==';
                    break;
                case 'complete':
                    audio.src = 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQAcAAEAHAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTcIGWi77eefTRAMUKfj8LZjHAY4kdfyzHksBSR3x/DdkEAKFF606OuoVRQKRp/g8r5sIQUrgc7y2Yk3CBlou+3nn00QDFCn4/C2YxwGOJHX8sx5LAUkd8fw3ZBACBR==';
                    break;
            }
            audio.play().catch(() => {});
        }

        function exportData() {
            const csv = scanQueue.map(item => 
                `${item.serial},${item.status},${item.message || ''},${item.timestamp.toISOString()}`
            ).join('\n');
            
            const blob = new Blob(['Serial Number,Status,Message,Timestamp\n' + csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `scan-queue-${Date.now()}.csv`;
            a.click();
        }

        // Auto-focus only on mode change (removed aggressive interval)
        // User can manually click on fields without interruption

        // Initialize
        selectMode('bulk');
    </script>
</body>
</html>