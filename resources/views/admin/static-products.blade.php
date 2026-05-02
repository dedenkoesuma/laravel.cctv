<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static Products Management</title>
     <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        .img-thumbnail {
            border: 2px solid #dee2e6;
        }
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        code {
            color: #d63384;
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
        }
        .brand-links {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .brand-link-btn {
            transition: all 0.3s ease;
        }
        .brand-link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-light">
@php
    $adminRoleName = session('admin_role');
    $currentRole = \Spatie\Permission\Models\Role::where('name', $adminRoleName)->first();
    
    // Fungsi ngecek izin murni HANYA dari centangan di database
    $canAccess = function($permissionName) use ($currentRole) {
        return $currentRole ? $currentRole->hasPermissionTo($permissionName) : false;
    };
@endphp
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-box-seam"></i> Static Products</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Static Products</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.static-products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Brand Quick Links -->
    <div class="brand-links">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-white mb-0"><i class="bi bi-link-45deg"></i> Quick Access - Public Product Pages</h5>
            <span class="badge bg-white text-dark">5 Brands Available</span>
        </div>
        <div class="row g-2">
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products/hikvision" target="_blank" class="btn btn-light w-100 brand-link-btn">
                    <i class="bi bi-box-seam"></i> Hikvision
                </a>
            </div>
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products/dahua" target="_blank" class="btn btn-light w-100 brand-link-btn">
                    <i class="bi bi-box-seam"></i> Dahua
                </a>
            </div>
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products/hilook" target="_blank" class="btn btn-light w-100 brand-link-btn">
                    <i class="bi bi-box-seam"></i> HiLook
                </a>
            </div>
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products/unv" target="_blank" class="btn btn-light w-100 brand-link-btn">
                    <i class="bi bi-box-seam"></i> UNV
                </a>
            </div>
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products/hiview" target="_blank" class="btn btn-light w-100 brand-link-btn">
                    <i class="bi bi-box-seam"></i> HiView
                </a>
            </div>
            <div class="col-md-2">
                <a href="http://127.0.0.1:8000/products" target="_blank" class="btn btn-warning w-100 brand-link-btn">
                    <i class="bi bi-grid"></i> All Products
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Products</h6>
                            <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Active</h6>
                            <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Inactive</h6>
                            <h2 class="mb-0">{{ $statistics['inactive'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Out of Stock</h6>
                            <h2 class="mb-0">{{ $statistics['out_of_stock'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter & Search</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.static-products.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Brand</label>
                        <select name="brand" class="form-select">
                            <option value="">Semua Brand</option>
                            <option value="hikvision" {{ request('brand') == 'hikvision' ? 'selected' : '' }}>Hikvision</option>
                            <option value="dahua" {{ request('brand') == 'dahua' ? 'selected' : '' }}>Dahua</option>
                            <option value="hilook" {{ request('brand') == 'hilook' ? 'selected' : '' }}>HiLook</option>
                            <option value="unv" {{ request('brand') == 'unv' ? 'selected' : '' }}>UNV</option>
                            <option value="hiview" {{ request('brand') == 'hiview' ? 'selected' : '' }}>HiView</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tab Category</label>
                        <select name="tab_category" class="form-select">
                            <option value="">Semua Tab</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('tab_category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari SKU atau nama produk..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Produk</h5>
            <span class="badge bg-secondary">{{ $products->total() }} products</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Image</th>
                            <th width="15%">SKU</th>
                            <th width="25%">Product Name</th>
                            <th width="10%">Brand</th>
                            <th width="10%">Tab</th>
                            <th width="8%">Stock</th>
                            <th width="10%">Status</th>
                            <th width="12%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $item)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            <td>
                                @if(isset($item->image) && $item->image)
                                    <img src="{{ Storage::url($item->image) }}" 
                                         alt="{{ $item->product_name ?? 'Product' }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                @elseif(isset($item->gambar) && $item->gambar)
                                    <img src="{{ Storage::url($item->gambar) }}" 
                                         alt="{{ $item->nama_produk ?? $item->product_name ?? 'Product' }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center p-2 rounded" style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><code>{{ $item->sku }}</code></td>
                            <td>
                                <strong>{{ $item->product_name ?? $item->nama_produk ?? 'N/A' }}</strong>
                                @if(isset($item->category) || isset($item->kategori))
                                    <br><small class="text-muted">{{ $item->category ?? $item->kategori }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $brandColor = match(strtolower($item->brand)) {
                                        'hikvision' => 'danger',
                                        'dahua' => 'primary',
                                        'hilook' => 'success',
                                        'unv' => 'warning',
                                        'hiview' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $brandColor }}">{{ ucfirst($item->brand) }}</span>
                            </td>
                            <td><span class="badge bg-secondary">{{ ucfirst($item->tab_category) }}</span></td>
                            <td>
                                @php
                                    $stock = $item->stock ?? $item->stok ?? 0;
                                @endphp
                                @if($stock > 10)
                                    <span class="badge bg-success">{{ $stock }}</span>
                                @elseif($stock > 0)
                                    <span class="badge bg-warning">{{ $stock }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $stock }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status = $item->status ?? (isset($item->is_active) && $item->is_active ? 'active' : 'inactive');
                                @endphp
                                @if($status == 'active')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                                @elseif($status == 'out_of_stock')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Out of Stock</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-dash-circle"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.static-products.edit', $item->id) }}" 
                                       class="btn btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="http://127.0.0.1:8000/products/{{ strtolower($item->brand) }}" 
                                       target="_blank"
                                       class="btn btn-info" 
                                       title="View on Public Page">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->product_name ?? $item->nama_produk ?? 'Product') }}')"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">Tidak ada data produk</p>
                                <a href="{{ route('admin.static-products.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                <p class="fw-bold" id="deleteProductName"></p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/static-products/${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

</body>
</html>