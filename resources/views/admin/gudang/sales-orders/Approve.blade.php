{{-- resources/views/admin/gudang/sales-orders/approve.blade.php --}}
@extends('layouts.simple')

@section('title', 'Approve SO - Input Serial Number')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-check-circle me-2 text-success"></i>
            Approve SO: <span class="text-primary">{{ $salesOrder->so_number }}</span>
        </h4>
        {{-- ✅ FIX: Pass $salesOrder->id bukan $salesOrder (stdClass) --}}
        <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="alert alert-warning">
        <i class="fas fa-info-circle me-2"></i>
        Untuk produk yang menggunakan <strong>Serial Number</strong>, Anda wajib memilih SN sesuai jumlah qty.
        Stok akan otomatis berkurang setelah approve.
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ FIX: Pass $salesOrder->id ke route approve --}}
    <form action="{{ route('admin.sales-orders.approve', $salesOrder->id) }}" method="POST" id="approveForm">
        @csrf

        {{-- Info Customer --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user me-2"></i>Informasi Order
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>No. SO:</strong> {{ $salesOrder->so_number }}</div>
                    <div class="col-md-3"><strong>Customer:</strong> {{ $salesOrder->customer_name }}</div>
                    <div class="col-md-3">
                        <strong>Tanggal:</strong>
                        {{-- ✅ FIX: so_date adalah string, parse dulu --}}
                        {{ \Carbon\Carbon::parse($salesOrder->so_date)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Total:</strong> Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Item per produk --}}
        @foreach($salesOrder->items as $item)
        @php
           
            $hasSn = isset($availableSerials[$item->id]);
        @endphp
        <div class="card mb-3 {{ $hasSn ? 'border-warning' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center
                {{ $hasSn ? 'bg-warning' : 'bg-light' }}">
                <span class="fw-bold">
                    <i class="fas fa-box me-2"></i>
                    {{-- ✅ FIX: Akses langsung dari stdClass hasil join, bukan ->product->nama_produk --}}
                    {{ $item->nama_produk }}
                    <span class="badge bg-secondary ms-2">{{ $item->sku ?? '-' }}</span>
                    @if($hasSn)
                        <span class="badge bg-danger ms-2"><i class="fas fa-qrcode me-1"></i>Wajib SN</span>
                    @endif
                </span>
                <span>Qty: <strong>{{ $item->qty }} unit</strong></span>
            </div>
            <div class="card-body">
                @if($hasSn)
                    {{-- Mode SN: pilih dari list --}}
                    <div class="mb-2">
                        <div class="d-flex gap-2 mb-2">
                            <span class="text-muted small align-self-center">
                                SN terpilih: <strong id="snCount_{{ $item->id }}">0</strong>/{{ $item->qty }}
                            </span>
                        </div>

                        <div id="selectMode_{{ $item->id }}">
                            <div class="row g-2">
                                @foreach($availableSerials[$item->id] as $serial)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <div class="form-check border rounded p-2 sn-option"
                                        onclick="toggleSnSelect(this, {{ $item->id }}, {{ $item->qty }})">
                                        <input class="form-check-input sn-checkbox"
                                            type="checkbox"
                                            name="serials[{{ $item->id }}][]"
                                            value="{{ $serial->id }}"
                                            id="sn_{{ $serial->id }}">
                                        <label class="form-check-label w-100" for="sn_{{ $serial->id }}" style="cursor:pointer">
                                            <i class="fas fa-barcode me-1 text-muted"></i>
                                            <span class="font-monospace small">{{ $serial->serial_number }}</span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach

                                @if(collect($availableSerials[$item->id])->isEmpty())
                                    <div class="col-12">
                                        <div class="alert alert-danger py-2 mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Tidak ada SN available untuk produk ini!
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Tanpa SN --}}
                    <div class="alert alert-success py-2 mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Produk ini tidak menggunakan Serial Number.
                        Stok akan langsung berkurang <strong>{{ $item->qty }} unit</strong>.
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-success" id="approveBtn">
                <i class="fas fa-check me-1"></i>Approve & Kurangi Stok
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleSnSelect(el, itemId, maxQty) {
    const checkbox = el.querySelector('.sn-checkbox');
    const checked  = document.querySelectorAll(`#selectMode_${itemId} .sn-checkbox:checked`).length;

    if (!checkbox.checked && checked >= maxQty) {
        alert(`Maksimal pilih ${maxQty} SN untuk produk ini.`);
        return;
    }

    checkbox.checked = !checkbox.checked;
    el.classList.toggle('border-success', checkbox.checked);
    el.classList.toggle('bg-light', checkbox.checked);

    document.getElementById(`snCount_${itemId}`).textContent =
        document.querySelectorAll(`#selectMode_${itemId} .sn-checkbox:checked`).length;
}

// Validasi sebelum submit
document.getElementById('approveForm').addEventListener('submit', function(e) {
    @foreach($salesOrder->items as $item)
    @php $hasSn = isset($availableSerials[$item->id]); @endphp
    @if($hasSn)
    const checked_{{ $item->id }} = document.querySelectorAll(
        '#selectMode_{{ $item->id }} .sn-checkbox:checked'
    ).length;
    if (checked_{{ $item->id }} !== {{ $item->qty }}) {
        e.preventDefault();
        alert('Pilih tepat {{ $item->qty }} Serial Number untuk produk {{ $item->nama_produk }}.');
        return;
    }
    @endif
    @endforeach
});
</script>
@endpush
@endsection