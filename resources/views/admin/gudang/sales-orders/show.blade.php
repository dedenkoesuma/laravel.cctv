@extends('layouts.simple')
@section('title', 'Detail SO - ' . $salesOrder->so_number)
@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-file-invoice me-2 text-primary"></i>
                {{ $salesOrder->so_number }}
                <span class="badge bg-{{ $salesOrder->status_color }} ms-2 fs-6">
                    {{ $salesOrder->status_label }}
                </span>
            </h4>
            <small class="text-muted">
                Dibuat {{ \Carbon\Carbon::parse($salesOrder->created_at)->format('d/m/Y H:i') }}
                oleh {{ $salesOrder->creator_name ?? '-' }}
            </small>
        </div>
        <a href="{{ route('admin.sales-orders.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- Action Buttons --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2">
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <span class="text-muted small me-2">Aksi:</span>
                @if($salesOrder->status === 'draft')
                    <a href="{{ route('admin.sales-orders.approve-form', $salesOrder->id) }}"
                        class="btn btn-success btn-sm">
                        <i class="fas fa-check me-1"></i>Approve & Input SN
                    </a>
                @endif
                @if($salesOrder->status === 'approved')
                    @php
                        $invoice = DB::table('keuangan_transaksi')
                            ->where('no_order', $salesOrder->so_number) 
                            ->first();
                    @endphp
                    @if(!$invoice)
                        {{-- Belum ada invoice --}}
                        <a href="{{ route('admin.sales-orders.create-invoice', $salesOrder->id) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-invoice-dollar me-1"></i>Buat Invoice
                        </a>
                    @elseif($invoice->status === 'pending')
                        {{-- Invoice pending — pelunasan hanya bisa dari Finance --}}
                        <span class="badge bg-warning text-dark me-1">
                            <i class="fas fa-clock me-1"></i>Invoice {{ $invoice->invoice_number }}
                            @if($invoice->tipe_bayar === 'tempo')
                                — Tempo s/d {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') }}
                            @endif
                        </span>
                        <a href="/admin/finance" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-cash-register me-1"></i>Tandai Lunas di Finance
                        </a>
                    @else
                        {{-- Invoice sudah lunas --}}
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Invoice {{ $invoice->invoice_number }} — LUNAS
                        </span>
                    @endif
                    {{-- Tombol Tandai Terkirim tetap ada --}}
                    <form action="{{ route('admin.sales-orders.deliver', $salesOrder->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm"
                            onclick="return confirm('Tandai SO ini sebagai Terkirim?')">
                            <i class="fas fa-truck me-1"></i>Tandai Terkirim
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.sales-orders.pdf', $salesOrder->id) }}"
                    class="btn btn-dark btn-sm" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i>Download PDF
                </a>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal">
                    <i class="fas fa-envelope me-1"></i>Kirim Email
                </button>
                @if(!in_array($salesOrder->status, ['delivered','cancelled']))
                    <form action="{{ route('admin.sales-orders.cancel', $salesOrder->id) }}" method="POST" class="d-inline ms-auto">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin batalkan SO ini? Stok akan dikembalikan.')">
                            <i class="fas fa-ban me-1"></i>Batalkan SO
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="row g-3">
        {{-- Info Customer --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user me-2"></i>Informasi Customer
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted" width="140">Nama Customer</td>
                            <td><strong>{{ $salesOrder->customer_name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. HP</td>
                            <td>{{ $salesOrder->customer_phone ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $salesOrder->customer_email ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>{{ $salesOrder->customer_address ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- Info SO --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle me-2"></i>Informasi Order
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted" width="140">No. SO</td>
                            <td><strong class="font-monospace">{{ $salesOrder->so_number }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal SO</td>
                            <td>{{ \Carbon\Carbon::parse($salesOrder->so_date)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td><span class="badge bg-{{ $salesOrder->status_color }}">{{ $salesOrder->status_label }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Tagihan</td>
                            <td><strong class="text-success fs-6">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        @if($salesOrder->notes)
                        <tr>
                            <td class="text-muted">Catatan</td>
                            <td>{{ $salesOrder->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Tabel Item + SN --}}
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fas fa-boxes me-2"></i>Item Produk & Serial Number
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Serial Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal_items = 0; @endphp
                        @foreach($salesOrder->items as $index => $item)
                        @php $subtotal_items += $item->subtotal; @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->nama_produk }}</div>
                                @if($item->notes)
                                    <div class="small text-muted">{{ $item->notes }}</div>
                                @endif
                            </td>
                            <td class="font-monospace small">{{ $item->sku ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>
                                @if($item->serials && $item->serials->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->serials as $serial)
                                            <span class="badge bg-primary font-monospace">
                                                <i class="fas fa-barcode me-1"></i>{{ $serial->serial_number }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($salesOrder->status === 'draft')
                                    <span class="text-warning small">
                                        <i class="fas fa-clock me-1"></i>Belum diinput (saat approve)
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end text-muted">Subtotal Produk</td>
                            <td class="fw-bold text-muted">
                                Rp {{ number_format($subtotal_items, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                        {{-- Tampilkan PPN hanya jika ada / dicentang --}}
                        @if(isset($salesOrder->ppn_rate) && floatval($salesOrder->ppn_rate) > 0)
                        <tr>
                            <td colspan="5" class="text-end text-muted">
                                PPN ({{ floatval($salesOrder->ppn_rate) }}%)
                            </td>
                            <td class="fw-bold text-danger">
                                + Rp {{ number_format($salesOrder->ppn_nominal ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="5" class="text-end fw-bold">TOTAL KESELURUHAN</td>
                            <td class="fw-bold text-success fs-6">
                                Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    {{-- Info Invoice (jika sudah dibuat) --}}
    @php
        $invoiceInfo = DB::table('keuangan_transaksi')
            ->where('no_order', $salesOrder->so_number)
            ->first();
    @endphp
    @if($invoiceInfo)
    <div class="card mt-3 shadow-sm">
        <div class="card-header {{ $invoiceInfo->status === 'lunas' ? 'bg-success' : 'bg-warning text-dark' }}">
            <i class="fas fa-file-invoice-dollar me-2"></i>
            Invoice: {{ $invoiceInfo->invoice_number }}
            <span class="badge {{ $invoiceInfo->status === 'lunas' ? 'bg-light text-success' : 'bg-dark' }} ms-2">
                {{ strtoupper($invoiceInfo->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-muted d-block">Tipe Pembayaran</small>
                    <strong>
                        @if($invoiceInfo->tipe_bayar === 'tempo')
                            <i class="fas fa-calendar-alt text-warning me-1"></i>
                            Tempo {{ $invoiceInfo->tempo_hari }} hari
                        @else
                            <i class="fas fa-money-bill text-success me-1"></i>Cash
                        @endif
                    </strong>
                </div>
                @if($invoiceInfo->jatuh_tempo)
                <div class="col-md-4">
                    <small class="text-muted d-block">Jatuh Tempo</small>
                    <strong class="{{ \Carbon\Carbon::parse($invoiceInfo->jatuh_tempo)->isPast() && $invoiceInfo->status !== 'lunas' ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($invoiceInfo->jatuh_tempo)->format('d F Y') }}
                        @if(\Carbon\Carbon::parse($invoiceInfo->jatuh_tempo)->isPast() && $invoiceInfo->status !== 'lunas')
                            <span class="badge bg-danger ms-1">LEWAT JATUH TEMPO</span>
                        @endif
                    </strong>
                </div>
                @endif
                <div class="col-md-4">
                    <small class="text-muted d-block">Rekening Tujuan</small>
                    <strong>{{ $invoiceInfo->nama_bank }}</strong><br>
                    <span class="font-monospace">{{ $invoiceInfo->no_rekening }}</span><br>
                    <small class="text-muted">a.n. {{ $invoiceInfo->nama_rekening }}</small>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Metode Bayar</small>
                    <strong>{{ ucfirst($invoiceInfo->metode_bayar) }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Total Tagihan</small>
                    <strong class="text-success fs-6">Rp {{ number_format($invoiceInfo->jumlah, 0, ',', '.') }}</strong>
                </div>
            </div>
            {{-- ===== TOMBOL AKSI INVOICE ===== --}}
            <div class="d-flex gap-2 flex-wrap mt-3 pt-3 border-top">
                {{-- Download PDF --}}
                <a href="{{ route('admin.sales-orders.invoice-download', $salesOrder->id) }}"
                    class="btn btn-dark btn-sm" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i>Download Invoice PDF
                </a>
                {{-- Share WhatsApp --}}
                @php
                    $invoicePdfUrl = route('admin.sales-orders.invoice-preview', $salesOrder->id);
                    $waText = urlencode(
                        "Halo {$salesOrder->customer_name},\n\n" .
                        "Berikut Invoice *{$invoiceInfo->invoice_number}* dari TechStore.\n\n" .
                        "Total Tagihan: *Rp " . number_format($invoiceInfo->jumlah, 0, ',', '.') . "*\n" .
                        ($invoiceInfo->tipe_bayar === 'tempo'
                            ? "Jatuh Tempo: *" . \Carbon\Carbon::parse($invoiceInfo->jatuh_tempo)->format('d/m/Y') . "*\n"
                            : ""
                        ) .
                        "Rekening: *{$invoiceInfo->nama_bank} {$invoiceInfo->no_rekening}* a.n. {$invoiceInfo->nama_rekening}\n\n" .
                        "Terima kasih atas kepercayaan Anda 🙏"
                    );
                    $waPhone = $salesOrder->customer_phone
                        ? '62' . ltrim(preg_replace('/[^0-9]/', '', $salesOrder->customer_phone), '0')
                        : '';
                    $waUrl = $waPhone
                        ? "https://wa.me/{$waPhone}?text={$waText}"
                        : "https://wa.me/?text={$waText}";
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm">
                    <i class="fab fa-whatsapp me-1"></i>Share WhatsApp
                </a>
                {{-- Kirim Email --}}
                <button type="button" class="btn btn-info btn-sm"
                    data-bs-toggle="modal" data-bs-target="#emailInvoiceModal">
                    <i class="fas fa-envelope me-1"></i>Kirim Email Invoice
                </button>
            </div>
        </div>
    </div>
    {{-- ===== MODAL EMAIL INVOICE ===== --}}
    <div class="modal fade" id="emailInvoiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-envelope me-2"></i>Kirim Invoice via Email
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sales-orders.invoice-send-email', $salesOrder->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small">
                            Invoice <strong>{{ $invoiceInfo->invoice_number }}</strong>
                            akan dikirim sebagai attachment PDF.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Kirim ke Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control"
                                value="{{ $salesOrder->customer_email }}"
                                placeholder="email@customer.com" required>
                            <small class="text-muted">Masukkan email tujuan pengiriman Invoice.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
{{-- Modal Kirim Email SO --}}
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope me-2"></i>Kirim SO via Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.sales-orders.send-email', $salesOrder->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">SO <strong>{{ $salesOrder->so_number }}</strong> akan dikirim sebagai attachment PDF.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kirim ke Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                            value="{{ $salesOrder->customer_email }}"
                            placeholder="email@customer.com" required>
                        <small class="text-muted">Masukkan email tujuan pengiriman SO.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection