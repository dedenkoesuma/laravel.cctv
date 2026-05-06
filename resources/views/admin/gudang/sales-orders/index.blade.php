@extends('layouts.simple')

@section('title', 'Daftar Sales Order')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i>Daftar Sales Order</h4>
        <div class="d-flex gap-2">
            {{-- Tombol muncul jika punya izin create ATAU manage --}}
            @canany(['create_sales_orders', 'manage_sales_orders'])
            <a href="{{ route('admin.sales-orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Buat SO Baru
            </a>
            @endcanany
            
            <a href="/admin/dashboard" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Alert --}}
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

    {{-- Stats Cards --}}
    @php
        $allSo = \Illuminate\Support\Facades\DB::table('sales_orders')
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-secondary">{{ $allSo['draft'] ?? 0 }}</div>
                <div class="small text-muted">Draft</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-success">{{ $allSo['approved'] ?? 0 }}</div>
                <div class="small text-muted">Disetujui</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-primary">{{ $allSo['delivered'] ?? 0 }}</div>
                <div class="small text-muted">Terkirim</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-3 fw-bold text-danger">{{ $allSo['cancelled'] ?? 0 }}</div>
                <div class="small text-muted">Dibatalkan</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.sales-orders.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari No. SO atau nama customer..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft"      {{ request('status') === 'draft'      ? 'selected' : '' }}>Draft</option>
                        <option value="approved"   {{ request('status') === 'approved'   ? 'selected' : '' }}>Disetujui</option>
                        <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Terkirim</option>
                        <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.sales-orders.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>No. SO</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Item</th>
                            <th>PPN</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th width="260">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesOrders as $so)
                        <tr>
                            <td>
                                <a href="{{ route('admin.sales-orders.show', $so->id) }}" class="fw-bold text-decoration-none">
                                    {{ $so->so_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $so->customer_name }}</div>
                                @if($so->customer_phone)
                                    <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $so->customer_phone }}</small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($so->so_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $so->items ? $so->items->count() : 0 }} produk</span>
                            </td>
                            <td>
                                @if(isset($so->ppn_aktif) && $so->ppn_aktif)
                                    <span class="badge bg-danger">Ya ({{ floatval($so->ppn_rate) }}%)</span>
                                @else
                                    <span class="badge bg-light text-dark border">Tidak</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($so->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $so->status_color }}">
                                    {{ $so->status_label }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($so->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    {{-- Tombol Detail / View (selalu muncul jika bisa buka halaman ini) --}}
                                    <a href="{{ route('admin.sales-orders.show', $so->id) }}"
                                        class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>

                                    {{-- Tombol Approve (butuh izin edit ATAU manage) --}}
                                    @if($so->status === 'draft')
                                        @canany(['edit_sales_orders', 'manage_sales_orders'])
                                        <a href="{{ route('admin.sales-orders.approve-form', $so->id) }}"
                                            class="btn btn-success btn-sm" title="Approve">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </a>
                                        @endcanany
                                    @endif

                                    {{-- PDF Document (Anggap aman untuk yang bisa View) --}}
                                    <a href="{{ route('admin.sales-orders.pdf', $so->id) }}"
                                        class="btn btn-dark btn-sm" title="Download PDF" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i>PDF
                                    </a>

                                    {{-- Tombol Batalkan (butuh izin delete ATAU manage) --}}
                                    @if(!in_array($so->status, ['delivered','cancelled']))
                                        @canany(['delete_sales_orders', 'manage_sales_orders'])
                                        <form action="{{ route('admin.sales-orders.cancel', $so->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin batalkan SO {{ $so->so_number }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Batalkan">
                                                <i class="fas fa-ban me-1"></i>Batalkan
                                            </button>
                                        </form>
                                        @endcanany
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada Sales Order.
                                <br>
                                {{-- Tombol muncul jika punya izin create ATAU manage --}}
                                @canany(['create_sales_orders', 'manage_sales_orders'])
                                <a href="{{ route('admin.sales-orders.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus me-1"></i>Buat SO Pertama
                                </a>
                                @endcanany
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($salesOrders->hasPages())
        <div class="card-footer">
            {{ $salesOrders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection