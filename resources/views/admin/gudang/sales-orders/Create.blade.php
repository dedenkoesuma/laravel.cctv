{{-- resources/views/admin/gudang/sales-orders/create.blade.php --}}
@extends('layouts.simple')

@section('title', 'Buat Surat Order')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Buat Surat Order</h4>
        <a href="/admin/gudang" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Gudang
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.sales-orders.store') }}" method="POST" id="soForm">
        @csrf
        <div class="row">
            {{-- Info Customer --}}
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-person me-2"></i>Informasi Customer
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. SO</label>
                            <input type="text" class="form-control bg-light" value="{{ $soNumber }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name') }}"
                                placeholder="Nama lengkap customer" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="customer_phone" class="form-control"
                                value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="customer_email" class="form-control"
                                value="{{ old('customer_email') }}" placeholder="email@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="customer_address" class="form-control" rows="2"
                                placeholder="Alamat pengiriman">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info SO --}}
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-info-circle me-2"></i>Detail Order
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal SO <span class="text-danger">*</span></label>
                            <input type="date" name="so_date"
                                class="form-control @error('so_date') is-invalid @enderror"
                                value="{{ old('so_date', date('Y-m-d')) }}" required>
                            @error('so_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            <textarea name="notes" class="form-control" rows="5"
                                placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Item Produk --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-boxes me-2"></i>Item Produk</span>
                <button type="button" class="btn btn-light btn-sm" id="addItemBtn">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="itemTable">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">Produk</th>
                                <th width="12%" class="text-center">Stok Tersedia</th>
                                <th width="10%" class="text-center">Qty</th>
                                <th width="18%">Harga Satuan (Rp)</th>
                                <th width="15%" class="text-end">Subtotal</th>
                                <th width="8%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">
                            <tr class="item-row" data-index="0">
                                <td>
                                    <select name="items[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-stok="{{ $product->sisa_stok }}"
                                                data-harga="{{ $product->harga_jual ?? 0 }}"
                                                data-use-sn="{{ $product->use_serial_number ? '1' : '0' }}"
                                                {{ isset($productId) && $productId == $product->id ? 'selected' : '' }}>
                                                {{ $product->nama_produk }}
                                                {{ $product->sku ? '('.$product->sku.')' : '' }}
                                                - Stok: {{ $product->sisa_stok }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="sn-info d-none mt-1 d-block" style="color:#d97706;">
                                        <i class="bi bi-upc-scan me-1"></i>Produk ini menggunakan Serial Number
                                    </small>
                                </td>
                                <td class="text-center align-middle stok-info fw-bold text-success">-</td>
                                <td>
                                    <input type="number" name="items[0][qty]"
                                        class="form-control qty-input text-center"
                                        min="1" value="1" required>
                                </td>
                                <td>
                                    <input type="number" name="items[0][harga_satuan]"
                                        class="form-control harga-input"
                                        min="0" value="0" step="1000" required>
                                </td>
                                <td class="text-end align-middle fw-bold subtotal-cell">Rp 0</td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold py-3">TOTAL</td>
                                <td class="text-end fw-bold text-primary py-3 fs-5" id="grandTotal">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="/admin/gudang" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-primary fw-bold">
                <i class="bi bi-save me-1"></i>Simpan Sales Order
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;

// ===== FORMAT RUPIAH =====
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID');
}

// ===== UPDATE SUBTOTAL PER BARIS =====
function updateSubtotal(row) {
    const qty    = parseInt(row.querySelector('.qty-input').value) || 0;
    const harga  = parseInt(row.querySelector('.harga-input').value) || 0;
    const sub    = qty * harga;
    row.querySelector('.subtotal-cell').textContent = formatRupiah(sub);
    updateGrandTotal();
}

// ===== UPDATE GRAND TOTAL =====
function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal-cell').forEach(cell => {
        total += parseInt(cell.textContent.replace(/[^0-9]/g, '')) || 0;
    });
    document.getElementById('grandTotal').textContent = formatRupiah(total);
}

// ===== BIND EVENTS KE BARIS =====
function bindRowEvents(row) {
    const select    = row.querySelector('.product-select');
    const qtyInput  = row.querySelector('.qty-input');
    const hrgInput  = row.querySelector('.harga-input');
    const removeBtn = row.querySelector('.remove-row');
    const stokInfo  = row.querySelector('.stok-info');
    const snInfo    = row.querySelector('.sn-info');

    select.addEventListener('change', function () {
        const opt   = this.options[this.selectedIndex];
        const stok  = opt.dataset.stok  || '0';
        const harga = opt.dataset.harga || '0';
        const useSn = opt.dataset.useSn === '1';

        stokInfo.textContent    = stok + ' unit';
        stokInfo.style.color    = parseInt(stok) > 0 ? '#059669' : '#dc2626';
        hrgInput.value          = harga;
        qtyInput.max            = stok;

        if (useSn) { snInfo.classList.remove('d-none'); }
        else       { snInfo.classList.add('d-none'); }

        updateSubtotal(row);
    });

    qtyInput.addEventListener('input', () => updateSubtotal(row));
    hrgInput.addEventListener('input', () => updateSubtotal(row));

    removeBtn.addEventListener('click', function () {
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            updateGrandTotal();
        } else {
            alert('Minimal 1 item produk.');
        }
    });
}

// ===== TAMBAH BARIS BARU =====
document.getElementById('addItemBtn').addEventListener('click', function () {
    const firstRow = document.querySelector('.item-row');
    const newRow   = firstRow.cloneNode(true);
    newRow.dataset.index = itemIndex;

    // Reset semua nilai
    const newSelect = newRow.querySelector('.product-select');
    newSelect.name  = `items[${itemIndex}][product_id]`;
    newSelect.value = '';

    const newQty   = newRow.querySelector('.qty-input');
    newQty.name    = `items[${itemIndex}][qty]`;
    newQty.value   = 1;

    const newHarga  = newRow.querySelector('.harga-input');
    newHarga.name   = `items[${itemIndex}][harga_satuan]`;
    newHarga.value  = 0;

    newRow.querySelector('.subtotal-cell').textContent = 'Rp 0';
    newRow.querySelector('.stok-info').textContent     = '-';
    newRow.querySelector('.stok-info').style.color     = '';
    newRow.querySelector('.sn-info').classList.add('d-none');

    document.getElementById('itemBody').appendChild(newRow);
    bindRowEvents(newRow);
    itemIndex++;
});

// ===== VALIDASI FORM =====
document.getElementById('soForm').addEventListener('submit', function (e) {
    let valid = true;

    document.querySelectorAll('.product-select').forEach(sel => {
        if (!sel.value) {
            valid = false;
            sel.classList.add('is-invalid');
        } else {
            sel.classList.remove('is-invalid');
        }
    });

    // Cek duplikat produk
    const ids = [...document.querySelectorAll('.product-select')]
        .map(s => s.value).filter(v => v);
    if (new Set(ids).size !== ids.length) {
        e.preventDefault();
        alert('Terdapat produk yang sama! Gunakan qty lebih besar untuk produk yang sama.');
        return;
    }

    if (!valid) {
        e.preventDefault();
        alert('Pilih produk untuk semua item!');
    }
});

// ===== INIT: Bind baris pertama =====
document.querySelectorAll('.item-row').forEach(row => bindRowEvents(row));

// ===== AUTO SELECT produk jika dari tombol di tabel gudang =====
(function () {
    const urlParams  = new URLSearchParams(window.location.search);
    const productId  = urlParams.get('product_id');
    if (!productId) return;

    const firstSelect = document.querySelector('.product-select');
    if (!firstSelect) return;

    firstSelect.value = productId;
    firstSelect.dispatchEvent(new Event('change'));
})();
</script>

@endsection