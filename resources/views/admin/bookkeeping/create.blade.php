<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Transaksi - Admin TechStore</title>
    
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
            padding: 20px;
        }
        
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
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
            border: none;
            cursor: pointer;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px 32px;
        }
        
        .form-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
        }
        
        .form-body {
            padding: 32px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .form-grid.full {
            grid-template-columns: 1fr;
        }
        
        .form-group {
            margin-bottom: 0;
            position: relative;
        }
        
        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-label .required {
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            color: #495057;
            position: relative;
            z-index: 1;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        
        .form-control:hover {
            border-color: #c5c9d4;
        }
        
        .form-control[readonly] {
            background: #f8f9fa;
            cursor: not-allowed;
            color: #6c757d;
        }
        
        .form-control[disabled] {
            background: #e9ecef;
            cursor: not-allowed;
            color: #6c757d;
        }
        
        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 12px;
            padding-right: 40px;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
        }
        
        input[type="number"].form-control {
            -moz-appearance: textfield;
        }
        
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .calculation-box {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .calculation-box h4 {
            margin: 0 0 16px 0;
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .calculation-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 0.95rem;
        }
        
        .calculation-row.total {
            border-top: 2px solid #dee2e6;
            margin-top: 12px;
            padding-top: 16px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .calculation-label {
            color: #6c757d;
        }
        
        .calculation-value {
            font-weight: 600;
            color: #333;
        }
        
        .calculation-value.profit {
            font-size: 1.5rem;
            color: #28a745;
        }
        
        .calculation-value.loss {
            color: #dc3545;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            padding: 24px 32px;
            background: #f8f9fa;
            border-top: 2px solid #e9ecef;
        }
        
        .btn {
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            background: white;
            color: #6c757d;
            border: 2px solid #e9ecef;
            text-decoration: none;
        }
        
        .btn-secondary:hover {
            background: #f8f9fa;
        }
        
        .help-text {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 4px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-body {
                padding: 24px 20px;
            }
            
            .form-actions {
                flex-direction: column;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Back Button -->
        <a href="/admin/bookkeeping" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali</span>
        </a>
        
        <!-- Alert Messages -->
        <div id="successAlert" class="alert success"></div>
        <div id="errorAlert" class="alert error"></div>
        
        <div class="form-card">
            <div class="form-header">
                <h2>
                    <i class="bi bi-receipt"></i>
                    <span>Tambah Transaksi Baru</span>
                </h2>
                <p>Masukkan detail transaksi penjualan</p>
            </div>

            <form id="transactionForm">
                <div class="form-body">
                    <!-- Basic Info -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Tanggal Transaksi <span class="required">*</span>
                            </label>
                            <input type="date" 
                                   name="transaction_date" 
                                   id="transaction_date"
                                   class="form-control" 
                                   value="{{ date('Y-m-d') }}" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Nomor Invoice <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="invoice_number" 
                                   id="invoice_number"
                                   class="form-control" 
                                   value="{{ $invoiceNumber ?? 'INV-' . date('Ymd') . '-001' }}" 
                                   required 
                                   readonly>
                            <small class="help-text">Otomatis generate</small>
                        </div>
                    </div>

                    <!-- Product & Customer -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Nama Produk <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="product_name" 
                                   id="product_name"
                                   class="form-control" 
                                   placeholder="Contoh: Router Ruijie RG-EG105G" 
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Nama Customer <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   name="customer_name" 
                                   id="customer_name"
                                   class="form-control" 
                                   placeholder="Nama pelanggan" 
                                   required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                No. Telepon Customer
                            </label>
                            <input type="tel" 
                                   name="customer_phone" 
                                   id="customer_phone"
                                   class="form-control" 
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Jumlah (Qty) <span class="required">*</span>
                            </label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   class="form-control" 
                                   value="1" 
                                   min="1" 
                                   step="1"
                                   required>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Harga Modal per Unit <span class="required">*</span>
                            </label>
                            <input type="number" 
                                   name="modal_per_unit" 
                                   id="modal_per_unit"
                                   class="form-control" 
                                   placeholder="0" 
                                   min="0" 
                                   step="1"
                                   required>
                            <small class="help-text">Harga beli dari supplier</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Harga Jual per Unit <span class="required">*</span>
                            </label>
                            <input type="number" 
                                   name="selling_price_per_unit" 
                                   id="selling_price_per_unit"
                                   class="form-control" 
                                   placeholder="0" 
                                   min="0" 
                                   step="1"
                                   required>
                            <small class="help-text">Harga jual ke customer</small>
                        </div>
                    </div>

                    <!-- Additional Costs -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Ongkos Kirim
                            </label>
                            <input type="number" 
                                   name="shipping_cost" 
                                   id="shipping_cost"
                                   class="form-control" 
                                   placeholder="0" 
                                   min="0" 
                                   step="1"
                                   value="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Biaya Tambahan
                            </label>
                            <input type="number" 
                                   name="additional_cost" 
                                   id="additional_cost"
                                   class="form-control" 
                                   placeholder="0" 
                                   min="0" 
                                   step="1"
                                   value="0">
                            <small class="help-text">Packing, admin, dll</small>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Status Pembayaran <span class="required">*</span>
                            </label>
                            <select name="payment_status" 
                                    id="payment_status"
                                    class="form-control" 
                                    required>
                                <option value="paid" selected>Lunas</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-grid full">
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" 
                                      id="notes"
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>

                    <!-- Calculation Preview -->
                    <div class="calculation-box">
                        <h4>
                            <i class="bi bi-calculator"></i>
                            <span>Perhitungan</span>
                        </h4>
                        
                        <div class="calculation-row">
                            <span class="calculation-label">Total Modal:</span>
                            <span class="calculation-value" id="calc-modal">Rp 0</span>
                        </div>
                        
                        <div class="calculation-row">
                            <span class="calculation-label">Total Penjualan:</span>
                            <span class="calculation-value" id="calc-selling">Rp 0</span>
                        </div>
                        
                        <div class="calculation-row">
                            <span class="calculation-label">Ongkos Kirim:</span>
                            <span class="calculation-value" id="calc-shipping">Rp 0</span>
                        </div>
                        
                        <div class="calculation-row">
                            <span class="calculation-label">Biaya Tambahan:</span>
                            <span class="calculation-value" id="calc-additional">Rp 0</span>
                        </div>
                        
                        <div class="calculation-row total">
                            <span class="calculation-label">Keuntungan Bersih:</span>
                            <span class="calculation-value profit" id="calc-profit">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i>
                        <span>Simpan Transaksi</span>
                    </button>
                    <a href="/admin/bookkeeping" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    // Format number to Indonesian format
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Calculate total profit
    function calculateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const modalPerUnit = parseFloat(document.getElementById('modal_per_unit').value) || 0;
        const sellingPerUnit = parseFloat(document.getElementById('selling_price_per_unit').value) || 0;
        const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
        const additionalCost = parseFloat(document.getElementById('additional_cost').value) || 0;
        
        const totalModal = quantity * modalPerUnit;
        const totalSelling = quantity * sellingPerUnit;
        const totalCost = totalModal + shippingCost + additionalCost;
        const profit = totalSelling - totalCost;
        
        document.getElementById('calc-modal').textContent = 'Rp ' + formatNumber(totalModal);
        document.getElementById('calc-selling').textContent = 'Rp ' + formatNumber(totalSelling);
        document.getElementById('calc-shipping').textContent = 'Rp ' + formatNumber(shippingCost);
        document.getElementById('calc-additional').textContent = 'Rp ' + formatNumber(additionalCost);
        
        const profitElement = document.getElementById('calc-profit');
        profitElement.textContent = 'Rp ' + formatNumber(profit);
        profitElement.className = 'calculation-value ' + (profit >= 0 ? 'profit' : 'loss');
    }
    
    // Show alert message
    function showAlert(type, message) {
        const alertId = type === 'success' ? 'successAlert' : 'errorAlert';
        const alert = document.getElementById(alertId);
        alert.textContent = message;
        alert.style.display = 'block';
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
    
    // Attach event listeners to all input fields
    document.addEventListener('DOMContentLoaded', function() {
        // Fields that affect calculation
        const calculationFields = [
            'quantity',
            'modal_per_unit',
            'selling_price_per_unit',
            'shipping_cost',
            'additional_cost'
        ];
        
        calculationFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', calculateTotal);
                field.addEventListener('change', calculateTotal);
            }
        });
        
        // Initial calculation
        calculateTotal();
    });
    
    // Handle form submission
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Show loading
        const submitBtn = document.getElementById('submitBtn');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Menyimpan...</span>';
        submitBtn.disabled = true;
        
        fetch('/admin/bookkeeping', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showAlert('success', 'Transaksi berhasil disimpan!');
                setTimeout(() => {
                    window.location.href = '/admin/bookkeeping';
                }, 1500);
            } else {
                showAlert('error', 'Gagal menyimpan transaksi: ' + (result.message || 'Unknown error'));
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menyimpan transaksi');
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        });
    });
    </script>
</body>
</html>