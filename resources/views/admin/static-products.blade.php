<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static Products Management - PT Trac</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* CSS GLOBAL & SIDEBAR (Disamakan dengan Dashboard) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }

        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; box-shadow: 2px 0 15px rgba(0,0,0,0.1); z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 0 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.2); }
        .sidebar-header h2 { font-size: 24px; margin-bottom: 5px; font-weight: 700; }
        .sidebar-header p { font-size: 13px; opacity: 0.85; }
        .sidebar-menu { padding: 20px 0 100px; }
        .menu-section-title { padding: 20px 24px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; opacity: 0.6; letter-spacing: 1px; }
        .menu-item { padding: 14px 24px; display: flex; align-items: center; gap: 12px; color: white; text-decoration: none; transition: all 0.3s; cursor: pointer; border-left: 4px solid transparent; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.2); border-left-color: white; color: white; }
        .menu-item i { width: 24px; text-align: center; font-size: 18px; }
        .menu-item .badge { margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        
        .logout-btn { position: fixed; bottom: 20px; left: 20px; width: 240px; padding: 12px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); color: white; }

        .main-content { margin-left: 280px; padding: 30px; min-height: 100vh; }

        /* KONTEN STATIC PRODUCTS */
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .btn-group-sm .btn { padding: 0.25rem 0.5rem; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border: none; }
        .card-header { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; font-weight: 600; }
        .img-thumbnail { border: 2px solid #dee2e6; }
        .badge { font-weight: 500; padding: 0.35em 0.65em; }
        code { color: #d63384; background-color: #f8f9fa; padding: 0.2rem 0.4rem; border-radius: 0.25rem; }
        .brand-links { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 2rem; }
        .brand-link-btn { transition: all 0.3s ease; }
        .brand-link-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>🏢 PT Trac</h2>
        <p>Unified Admin Dashboard</p>
    </div>
    
    <div class="sidebar-menu">
        <a href="/dashboard" class="menu-item"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>

        <div class="menu-section-title">Operations Management</div>
        
        @canany(['view_inventory', 'manage_inventory'])
        <a href="/admin/gudang" class="menu-item"><i class="bi bi-box-seam"></i><span>Gudang</span></a>
        @endcanany

        @canany(['view_purchase_orders', 'manage_purchase_orders'])
        <a href="{{ route('admin.po.index') }}" class="menu-item"><i class="bi bi-cart-check"></i><span>Purchase Order</span></a>
        @endcanany
        
        @canany(['view_sales_orders', 'manage_sales_orders'])
        <a href="/admin/gudang/sales-orders" class="menu-item"><i class="bi bi-file-earmark-check"></i><span>Sales Order</span></a>
        @endcanany

        @canany(['view_quotation', 'manage_quotation'])
        <a href="{{ route('admin.quotation.index') }}" class="menu-item"><i class="bi bi-file-text"></i><span>Quotation</span></a>
        @endcanany

        @canany(['view_bookkeeping', 'manage_bookkeeping'])
        <a href="/admin/keuangan" class="menu-item"><i class="bi bi-wallet2"></i><span>Keuangan Boss</span></a>
        @endcanany

        @can('manage_finance')
        <a href="/admin/finance" class="menu-item"><i class="bi bi-receipt"></i><span>Finance Staff</span></a>
        @endcan

        @can('view_kalkulator')
        <a href="{{ route('admin.modal.kalkulator') }}" class="menu-item"><i class="bi bi-calculator"></i><span>Kalkulator Modal</span></a>
        @endcan

        <div class="menu-section-title">Products Management</div>
        
        @canany(['view_ruijie', 'manage_ruijie'])
        <a href="/admin/ruijie" class="menu-item"><i class="bi bi-router"></i><span>Ruijie Networks</span></a>
        @endcanany
        
        @canany(['view_wifi_cameras', 'manage_wifi_cameras'])
        <a href="/admin/wifi-cameras" class="menu-item"><i class="bi bi-camera-video"></i><span>WiFi Cameras</span></a>
        @endcanany
        
        @canany(['view_access_control', 'manage_access_control'])
        <a href="/admin/access-control" class="menu-item"><i class="bi bi-shield-lock"></i><span>Access Control</span></a>
        @endcanany
        
        @canany(['view_static_products', 'manage_static_products'])
        <a href="/admin/static-products" class="menu-item active"><i class="bi bi-box"></i><span>Static Products</span></a>
        @endcanany

        <div class="menu-section-title">System</div>
        
        @can('view_users')
        <a href="{{ route('admin.users.index') }}" class="menu-item"><i class="bi bi-people"></i><span>Users Account</span></a>
        @endcan

        @can('manage_roles')
        <a href="{{ route('admin.roles.index') }}" class="menu-item"><i class="bi bi-shield-lock"></i><span>Roles & Permissions</span><span class="badge">SECURE</span></a>
        @endcan
        
    </div>
    
    <a href="{{ route('admin.logout') }}" class="logout-btn" onclick="hapusJejakBrowser(event)">
        <i class="bi bi-box-arrow-right"></i><span>Logout</span>
    </a>
</div>

<div class="main-content">
    <div class="container-fluid p-0">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 style="font-size: 32px; font-weight: 700; color: #2d3748; margin-bottom: 5px;"><i class="bi bi-box-seam me-2"></i>Static Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/dashboard" style="text-decoration:none;">Dashboard</a></li>
                        <li class="breadcrumb-item active text-muted">Static Products</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-end">
                @can('create_static_products')
                <a href="{{ route('admin.static-products.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none; padding:10px 20px; font-weight:600; border-radius:10px;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                </a>
                @endcan
            </div>
        </div>

        <div class="brand-links">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0"><i class="bi bi-link-45deg"></i> Quick Access - Public Product Pages</h5>
                <span class="badge bg-white text-dark">5 Brands Available</span>
            </div>
            <div class="row g-2">
                <div class="col-md-2">
                    <a href="/products/hikvision" target="_blank" class="btn btn-light w-100 brand-link-btn">
                        <i class="bi bi-box-seam"></i> Hikvision
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="/products/dahua" target="_blank" class="btn btn-light w-100 brand-link-btn">
                        <i class="bi bi-box-seam"></i> Dahua
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="/products/hilook" target="_blank" class="btn btn-light w-100 brand-link-btn">
                        <i class="bi bi-box-seam"></i> HiLook
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="/products/unv" target="_blank" class="btn btn-light w-100 brand-link-btn">
                        <i class="bi bi-box-seam"></i> UNV
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="/products/hiview" target="_blank" class="btn btn-light w-100 brand-link-btn">
                        <i class="bi bi-box-seam"></i> HiView
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="/products" target="_blank" class="btn btn-warning w-100 brand-link-btn fw-bold">
                        <i class="bi bi-grid"></i> All Products
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1 fw-bold text-uppercase">Total Products</h6>
                                <h2 class="mb-0 fw-bold">{{ $statistics['total'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-box-seam fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1 fw-bold text-uppercase">Active</h6>
                                <h2 class="mb-0 fw-bold">{{ $statistics['active'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1 fw-bold text-uppercase">Inactive</h6>
                                <h2 class="mb-0 fw-bold">{{ $statistics['inactive'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white" style="border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50 mb-1 fw-bold text-uppercase">Out of Stock</h6>
                                <h2 class="mb-0 fw-bold">{{ $statistics['out_of_stock'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius:10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius:10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card mb-4" style="border-radius:12px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold" style="color:#2d3748;"><i class="bi bi-funnel text-primary me-2"></i> Filter & Search</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.static-products.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Brand</label>
                            <select name="brand" class="form-select border-0 bg-light">
                                <option value="">Semua Brand</option>
                                <option value="hikvision" {{ request('brand') == 'hikvision' ? 'selected' : '' }}>Hikvision</option>
                                <option value="dahua" {{ request('brand') == 'dahua' ? 'selected' : '' }}>Dahua</option>
                                <option value="hilook" {{ request('brand') == 'hilook' ? 'selected' : '' }}>HiLook</option>
                                <option value="unv" {{ request('brand') == 'unv' ? 'selected' : '' }}>UNV</option>
                                <option value="hiview" {{ request('brand') == 'hiview' ? 'selected' : '' }}>HiView</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Tab Category</label>
                            <select name="tab_category" class="form-select border-0 bg-light">
                                <option value="">Semua Tab</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('tab_category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-muted small text-uppercase">Status</label>
                            <select name="status" class="form-select border-0 bg-light">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Search</label>
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Cari SKU atau nama..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm" style="height:38px; border-radius:8px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="border-radius:12px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold" style="color:#2d3748;"><i class="bi bi-table text-primary me-2"></i> Daftar Produk</h5>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $products->total() }} products found</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="5%">#</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="8%">Image</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="15%">SKU</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="28%">Product Name</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="10%">Brand</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="8%">Stock</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted" width="10%">Status</th>
                                <th class="py-3 px-4 border-bottom-0 text-muted text-center" width="16%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $item)
                            <tr style="border-bottom: 1px solid #edf2f7;">
                                <td class="px-4 text-muted">{{ $products->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3">
                                    @if(isset($item->image) && $item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="Img" class="rounded shadow-sm" style="width:50px; height:50px; object-fit: cover;">
                                    @elseif(isset($item->gambar) && $item->gambar)
                                        <img src="{{ Storage::url($item->gambar) }}" alt="Img" class="rounded shadow-sm" style="width:50px; height:50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light text-center d-flex align-items-center justify-content-center rounded shadow-sm" style="width:50px; height:50px;">
                                            <i class="bi bi-image text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4"><code>{{ $item->sku }}</code></td>
                                <td class="px-4">
                                    <div class="fw-bold text-dark">{{ $item->product_name ?? $item->nama_produk ?? 'N/A' }}</div>
                                    @if(isset($item->category) || isset($item->kategori) || isset($item->tab_category))
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-tag me-1"></i> {{ $item->category ?? $item->kategori }} 
                                            <span class="mx-1">•</span> 
                                            Tab: {{ ucfirst($item->tab_category) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4">
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
                                    <span class="badge bg-{{ $brandColor }} bg-opacity-10 text-{{ $brandColor }} px-2 py-1 border border-{{ $brandColor }} rounded-pill">
                                        {{ ucfirst($item->brand) }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    @php $stock = $item->stock ?? $item->stok ?? 0; @endphp
                                    @if($stock > 10)
                                        <span class="badge bg-success rounded-pill px-3">{{ $stock }}</span>
                                    @elseif($stock > 0)
                                        <span class="badge bg-warning text-dark rounded-pill px-3">{{ $stock }}</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3">{{ $stock }}</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    @php $status = $item->status ?? (isset($item->is_active) && $item->is_active ? 'active' : 'inactive'); @endphp
                                    @if($status == 'active')
                                        <span class="text-success fw-bold small"><i class="bi bi-circle-fill me-1"></i> Active</span>
                                    @elseif($status == 'out_of_stock')
                                        <span class="text-danger fw-bold small"><i class="bi bi-x-circle-fill me-1"></i> Out of Stock</span>
                                    @else
                                        <span class="text-secondary fw-bold small"><i class="bi bi-dash-circle-fill me-1"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 text-center">
                                    <div class="btn-group shadow-sm rounded" role="group">
                                        
                                        {{-- Tombol Lihat di Halaman Publik (BEBAS VIEW) --}}
                                        <a href="/products/{{ strtolower($item->brand) }}" target="_blank" class="btn btn-sm btn-light border" title="View Public Page">
                                            <i class="bi bi-eye text-primary"></i>
                                        </a>

                                        {{-- Gembok Edit --}}
                                        @can('edit_static_products')
                                        <a href="{{ route('admin.static-products.edit', $item->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>
                                        @endcan

                                        {{-- Gembok Hapus --}}
                                        @can('delete_static_products')
                                        <button type="button" class="btn btn-sm btn-light border" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->product_name ?? $item->nama_produk ?? 'Product') }}')" title="Delete">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1 text-muted opacity-50"></i></div>
                                    <h5 class="text-muted fw-bold">Belum ada produk statis</h5>
                                    <p class="text-muted small mb-4">Tambahkan produk pertama Anda sekarang.</p>
                                    @can('create_static_products')
                                    <a href="{{ route('admin.static-products.create') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($products->hasPages())
            <div class="card-footer bg-white border-top p-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <strong>{{ $products->firstItem() }}</strong> to <strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> products
                </div>
                <div>
                   {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header bg-danger text-white border-0" style="border-radius:16px 16px 0 0; padding:20px;">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3"><i class="bi bi-trash text-danger" style="font-size:48px;"></i></div>
                <h5 class="mb-2">Hapus Produk Ini?</h5>
                <p class="text-dark fw-bold mb-1 p-2 bg-light rounded border" id="deleteProductName"></p>
                <p class="text-muted small mb-0 mt-3">Semua data terkait produk ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">Ya, Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/static-products/${id}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function hapusJejakBrowser(event) {
    event.preventDefault();
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = '/admin/logout'; 
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