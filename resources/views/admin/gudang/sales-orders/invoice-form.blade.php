@extends('layouts.simple')

@section('title', 'Buat Invoice - ' . $salesOrder->so_number)

@section('content')
<div class="container-fluid" style="max-width:800px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-file-invoice-dollar me-2 text-success"></i>
                Buat Invoice
            </h4>
            <small class="text-muted">SO: <strong>{{ $salesOrder->so_number }}</strong> — {{ $salesOrder->customer_name }}</small>
        </div>
        <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.sales-orders.store-invoice', $salesOrder->id) }}" method="POST">
        @csrf

        {{-- Ringkasan SO --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle me-2"></i>Ringkasan SO
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor Invoice</small>
                        <strong class="font-monospace text-success fs-5">{{ $invoiceNumber }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor SO</small>
                        <strong class="font-monospace">{{ $salesOrder->so_number }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Customer</small>
                        <strong>{{ $salesOrder->customer_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Total Tagihan</small>
                        <strong class="text-success">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nomor Rekening --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-info text-white">
                <i class="fas fa-university me-2"></i>Informasi Rekening Tujuan Pembayaran
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bank" class="form-control"
                            placeholder="Contoh: BCA, Mandiri, BRI"
                            value="{{ old('nama_bank') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="no_rekening" class="form-control font-monospace"
                            placeholder="Contoh: 1234567890"
                            value="{{ old('no_rekening') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Atas Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama_rekening" class="form-control"
                            placeholder="Nama pemilik rekening"
                            value="{{ old('nama_rekening') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-money-bill-wave me-2"></i>Pengaturan Pembayaran
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Tipe Bayar --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipe Pembayaran <span class="text-danger">*</span></label>
                        <select name="tipe_bayar" id="tipe_bayar" class="form-select" required>
                            <option value="cash" {{ old('tipe_bayar') == 'cash' ? 'selected' : '' }}>Cash (Langsung)</option>
                            <option value="tempo" {{ old('tipe_bayar') == 'tempo' ? 'selected' : '' }}>Tempo (Jatuh Tempo)</option>
                        </select>
                    </div>

                    {{-- Tempo Hari (muncul jika tempo) --}}
                    <div class="col-md-4" id="tempo_hari_field" style="display:none">
                        <label class="form-label fw-bold">Tempo (hari) <span class="text-danger">*</span></label>
                        <input type="number" name="tempo_hari" class="form-control"
                            placeholder="Contoh: 30" min="1"
                            value="{{ old('tempo_hari') }}">
                        <small class="text-muted" id="jatuh_tempo_preview"></small>
                    </div>

                    {{-- Metode Bayar --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Metode Bayar <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-select" required>
                            <option value="transfer" {{ old('metode_bayar','transfer') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ old('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="kartu_kredit" {{ old('metode_bayar') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                        </select>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mt-3">
                    <label class="form-label fw-bold">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2"
                        placeholder="Catatan tambahan untuk invoice...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Ringkasan Item --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-boxes me-2"></i>Item Produk
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrder->items as $item)
                        <tr>
                            <td>{{ $item->nama_produk ?? $item->notes }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        @if($salesOrder->ppn_aktif && $salesOrder->ppn_nominal > 0)
                        <tr>
                            <td colspan="3" class="text-end text-muted">PPN ({{ floatval($salesOrder->ppn_rate) }}%)</td>
                            <td class="text-danger">+Rp {{ number_format($salesOrder->ppn_nominal, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="fw-bold text-success">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-file-invoice-dollar me-1"></i>Buat Invoice
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const tipeBayar = document.getElementById('tipe_bayar');
    const tempoField = document.getElementById('tempo_hari_field');
    const tempoInput = document.querySelector('input[name="tempo_hari"]');
    const preview = document.getElementById('jatuh_tempo_preview');

    function toggleTempo() {
        if (tipeBayar.value === 'tempo') {
            tempoField.style.display = 'block';
            tempoInput.required = true;
        } else {
            tempoField.style.display = 'none';
            tempoInput.required = false;
            preview.textContent = '';
        }
    }

    function updatePreview() {
        const hari = parseInt(tempoInput.value);
        if (!isNaN(hari) && hari > 0) {
            const d = new Date();
            d.setDate(d.getDate() + hari);
            preview.textContent = 'Jatuh tempo: ' + d.toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'});
        } else {
            preview.textContent = '';
        }
    }

    tipeBayar.addEventListener('change', toggleTempo);
    tempoInput.addEventListener('input', updatePreview);
    toggleTempo(); // init
</script>
@endpush
@endsection