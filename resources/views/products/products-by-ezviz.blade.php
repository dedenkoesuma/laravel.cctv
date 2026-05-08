@extends('layouts.simple')

@section('title', 'Produk ' . ($brandData['name'] ?? $brand) . ' - TechStore')

@section('content')
<div class="container py-5">
    <!-- Brand Header with Logo & Description -->
    <div class="row mb-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/') }}#brands" class="text-decoration-none">Brands</a></li>
                    <li class="breadcrumb-item active">{{ $brandData['name'] ?? $brand }}</li>
                </ol>
            </nav>
            
            @if(isset($brandData))
            <div class="brand-header p-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, {{ $brandData['color'] }}15 0%, {{ $brandData['color'] }}05 100%); border-left: 5px solid {{ $brandData['color'] }};">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3" style="color: {{ $brandData['color'] }}">
                            {{ $brandData['name'] }}
                        </h1>
                        <p class="lead text-muted mb-0">
                            {{ $brandData['description'] }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge fs-6 px-4 py-2" style="background-color: {{ $brandData['color'] }}; color: white;">
                            <i class="bi bi-shield-check"></i> Authorized Dealer
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Brand Filter Pills -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4" style="font-weight: 700; color: #333;">
                        Filter by Brand
                    </h3>
                    <div class="brand-filter-buttons d-flex justify-content-center align-items-center gap-3 flex-wrap">
                        <a href="{{ url('/products/all') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'all' ? 'active' : '' }}">
                            Semua Brand
                        </a>
                        <a href="{{ url('/products/hikvision') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'hikvision' ? 'active' : '' }}">
                            HIKVISION
                        </a>
                        <a href="{{ url('/products/dahua') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'dahua' ? 'active' : '' }}">
                            Dahua
                        </a>
                        <a href="{{ url('/products/hilook') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'hilook' ? 'active' : '' }}">
                            HiLook
                        </a>
                        <a href="{{ url('/products/ezviz') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'ezviz' ? 'active' : '' }}">
                            EZVIZ
                        </a>
                        <a href="{{ url('/products/unv') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'unv' ? 'active' : '' }}">
                            UNV
                        </a>
                        <a href="{{ url('/products/ruijie') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'ruijie' ? 'active' : '' }}">
                            RUIJIE
                        </a>
                        <a href="{{ url('/products/hiview') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'hiview' ? 'active' : '' }}">
                            HIVIEW
                        </a>
                        <a href="{{ url('/products/foreage') }}" class="brand-filter-btn {{ strtolower($brandSlug ?? $brand) === 'foreage' ? 'active' : '' }}">
                            Foreage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section with Tabs -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-hdd-rack-fill" style="color: {{ $brandData['color'] ?? '#667eea' }}"></i> 
                        PAKET CCTV {{ strtoupper($brandData['name'] ?? $brand) }}
                    </h3>

                    <!-- Product Tabs -->
                    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="database-tab" data-bs-toggle="tab" data-bs-target="#database" type="button">
                                Database Products
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                Basic
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cooper-tab" data-bs-toggle="tab" data-bs-target="#cooper" type="button">
                                Audio
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pro-tab" data-bs-toggle="tab" data-bs-target="#pro" type="button">
                                Full Color
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="enterprise-tab" data-bs-toggle="tab" data-bs-target="#enterprise" type="button">
                                IP Camera
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="productTabContent">
                        
                        <!-- DATABASE PRODUCTS TAB - DYNAMIC WITH SAME CARD STYLE -->
                        <div class="tab-pane fade show active" id="database" role="tabpanel">
                            @if(isset($products) && $products->count() > 0)
                                <div class="alert alert-success mb-4">
                                    <i class="bi bi-info-circle"></i> 
                                    Menampilkan <strong>{{ $products->count() }} produk</strong> {{ $brandData['name'] ?? $brand }} dari database
                                </div>
                                
                                <div class="row g-3">
                                    @foreach($products as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card h-100 product-card border">
                                            <div class="card-header bg-light text-center py-3">
                                                @if(isset($product->kategori))
                                                <span class="badge bg-secondary mb-2">
                                                    {{ $product->kategori }}
                                                </span>
                                                @endif
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                     alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                     class="float-end" 
                                                     style="width: 30px;">
                                                <div class="product-image-container my-3">
                                                    @if(isset($product->gambar) && $product->gambar)
                                                        <img src="{{ asset('storage/'.$product->gambar) }}" 
                                                             class="img-fluid" 
                                                             alt="{{ $product->nama_produk }}"
                                                             onerror="this.src='https://via.placeholder.com/200x150?text=No+Image'">
                                                    @else
                                                        <img src="https://via.placeholder.com/200x150?text=Product" 
                                                             class="img-fluid" 
                                                             alt="Product Image">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @if(isset($product->sku))
                                                <div class="text-center mb-3">
                                                    <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#667eea' }}; color: white;">
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                                @endif
                                                
                                                <h6 class="fw-bold mb-3">{{ $product->nama_produk ?? 'Product Name' }}</h6>
                                                
                                                <!-- Specs List (jika ada deskripsi) -->
                                                @if(isset($product->deskripsi) && $product->deskripsi)
                                                <ul class="list-unstyled small specs-list">
                                                    @php
                                                        // Split deskripsi by newline atau koma untuk specs
                                                        $specs = explode("\n", $product->deskripsi);
                                                        if(count($specs) === 1) {
                                                            $specs = explode(",", $product->deskripsi);
                                                        }
                                                    @endphp
                                                    @foreach(array_slice($specs, 0, 6) as $spec)
                                                        @if(trim($spec))
                                                        <li><i class="bi bi-check-circle-fill text-success"></i> {{ trim($spec) }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                @else
                                                <ul class="list-unstyled small specs-list">
                                                    @if(isset($product->kategori))
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Category: {{ $product->kategori }}</li>
                                                    @endif
                                                    @if(isset($product->harga_jual))
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Ready Stock</li>
                                                    @endif
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Garansi Resmi</li>
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Original Product</li>
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Fast Shipping</li>
                                                    <li><i class="bi bi-check-circle-fill text-success"></i> Support 24/7</li>
                                                </ul>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white border-top text-center">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewDetail('{{ $product->id ?? '' }}')">
                                                    <i class="bi bi-eye"></i> Lihat Spesifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- No Products from Database -->
                                <div class="alert alert-info text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                                    <h5 class="mt-3">Belum Ada Produk dari Database</h5>
                                    <p class="mb-3">
                                        Produk {{ $brandData['name'] ?? $brand }} belum ditambahkan ke database.<br>
                                        Silakan lihat tab lain untuk katalog produk atau hubungi kami untuk informasi.
                                    </p>
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Tambah Produk di Admin
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- BASIC TAB - Dynamic from static_products table -->
                        <div class="tab-pane fade" id="basic" role="tabpanel">
                            <div class="row g-3">
                                @if(isset($staticProducts['basic']) && $staticProducts['basic']->count() > 0)
                                    @foreach($staticProducts['basic'] as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card h-100 product-card border">
                                            <div class="card-header bg-light text-center py-3">
                                                <span class="badge bg-secondary mb-2">{{ $product->kategori ?? 'Product' }}</span>
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                     alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                     class="float-end" 
                                                     style="width: 30px;">
                                                <div class="product-image-container my-3">
                                                    @if($product->gambar)
                                                        <img src="{{ asset('storage/'.$product->gambar) }}" 
                                                             class="img-fluid" 
                                                             alt="{{ $product->nama_produk }}"
                                                             onerror="this.src='https://via.placeholder.com/200x150?text=DVR'">
                                                    @else
                                                        <img src="https://via.placeholder.com/200x150?text=DVR" class="img-fluid" alt="DVR">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold mb-3">{{ $product->nama_produk }}</h6>
                                                <ul class="list-unstyled small specs-list">
                                                    @if($product->deskripsi)
                                                        @foreach(array_slice(explode("\n", $product->deskripsi), 0, 8) as $spec)
                                                            @if(trim($spec))
                                                            <li><i class="bi bi-check-circle-fill text-success"></i> {{ trim($spec) }}</li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white border-top text-center">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewStaticDetail({{ $product->id }})">
                                                    <i class="bi bi-eye"></i> Lihat Spesifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info text-center">
                                            <i class="bi bi-info-circle"></i> Produk Basic belum tersedia
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- AUDIO TAB (Cooper) - Dynamic from database -->
                        <div class="tab-pane fade" id="cooper" role="tabpanel">
                            <div class="row g-3">
                                @if(isset($staticProducts['audio']) && $staticProducts['audio']->count() > 0)
                                    @foreach($staticProducts['audio'] as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card h-100 product-card border">
                                            <div class="card-header bg-light text-center py-3">
                                                <span class="badge bg-secondary mb-2">{{ $product->kategori ?? 'Audio' }}</span>
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                     alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                     class="float-end" 
                                                     style="width: 30px;">
                                                <div class="product-image-container my-3">
                                                    @if($product->gambar)
                                                        <img src="{{ asset('storage/'.$product->gambar) }}" 
                                                             class="img-fluid" 
                                                             alt="{{ $product->nama_produk }}"
                                                             onerror="this.src='https://via.placeholder.com/200x150?text=Audio+DVR'">
                                                    @else
                                                        <img src="https://via.placeholder.com/200x150?text=Audio+DVR" class="img-fluid" alt="Audio DVR">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold mb-3">{{ $product->nama_produk }}</h6>
                                                <ul class="list-unstyled small specs-list">
                                                    @if($product->deskripsi)
                                                        @foreach(array_slice(explode("\n", $product->deskripsi), 0, 8) as $spec)
                                                            @if(trim($spec))
                                                            <li><i class="bi bi-check-circle-fill text-success"></i> {{ trim($spec) }}</li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white border-top text-center">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewStaticDetail({{ $product->id }})">
                                                    <i class="bi bi-eye"></i> Lihat Spesifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- FULL COLOR TAB (Pro) - Dynamic from database -->
                        <div class="tab-pane fade" id="pro" role="tabpanel">
                            <div class="row g-3">
                                @if(isset($staticProducts['fullcolor']) && $staticProducts['fullcolor']->count() > 0)
                                    @foreach($staticProducts['fullcolor'] as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card h-100 product-card border">
                                            <div class="card-header bg-light text-center py-3">
                                                <span class="badge bg-warning text-dark mb-2">{{ $product->kategori ?? 'Full Color' }}</span>
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                     alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                     class="float-end" 
                                                     style="width: 30px;">
                                                <div class="product-image-container my-3">
                                                    @if($product->gambar)
                                                        <img src="{{ asset('storage/'.$product->gambar) }}" 
                                                             class="img-fluid" 
                                                             alt="{{ $product->nama_produk }}"
                                                             onerror="this.src='https://via.placeholder.com/200x150?text=Full+Color'">
                                                    @else
                                                        <img src="https://via.placeholder.com/200x150?text=Full+Color" class="img-fluid" alt="Full Color">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold mb-3">{{ $product->nama_produk }}</h6>
                                                <ul class="list-unstyled small specs-list">
                                                    @if($product->deskripsi)
                                                        @foreach(array_slice(explode("\n", $product->deskripsi), 0, 8) as $spec)
                                                            @if(trim($spec))
                                                            <li><i class="bi bi-check-circle-fill text-success"></i> {{ trim($spec) }}</li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white border-top text-center">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewStaticDetail({{ $product->id }})">
                                                    <i class="bi bi-eye"></i> Lihat Spesifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- IP CAMERA TAB (Enterprise) - Dynamic from database -->
                        <div class="tab-pane fade" id="enterprise" role="tabpanel">
                            <div class="row g-3">
                                @if(isset($staticProducts['ipcamera']) && $staticProducts['ipcamera']->count() > 0)
                                    @foreach($staticProducts['ipcamera'] as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card h-100 product-card border">
                                            <div class="card-header bg-light text-center py-3">
                                                <span class="badge bg-primary mb-2">{{ $product->kategori ?? 'IP Camera' }}</span>
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                     alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                     class="float-end" 
                                                     style="width: 30px;">
                                                <div class="product-image-container my-3">
                                                    @if($product->gambar)
                                                        <img src="{{ asset('storage/'.$product->gambar) }}" 
                                                             class="img-fluid" 
                                                             alt="{{ $product->nama_produk }}"
                                                             onerror="this.src='https://via.placeholder.com/200x150?text=IP+Camera'">
                                                    @else
                                                        <img src="https://via.placeholder.com/200x150?text=IP+Camera" class="img-fluid" alt="IP Camera">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                        {{ $product->sku }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold mb-3">{{ $product->nama_produk }}</h6>
                                                <ul class="list-unstyled small specs-list">
                                                    @if($product->deskripsi)
                                                        @foreach(array_slice(explode("\n", $product->deskripsi), 0, 8) as $spec)
                                                            @if(trim($spec))
                                                            <li><i class="bi bi-check-circle-fill text-success"></i> {{ trim($spec) }}</li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white border-top text-center">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewStaticDetail({{ $product->id }})">
                                                    <i class="bi bi-eye"></i> Lihat Spesifikasi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cooper" role="tabpanel">
                            <div class="row g-3">
                                @for($i = 1; $i <= 16; $i++)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card h-100 product-card border">
                                        <div class="card-header bg-light text-center py-3">
                                            <span class="badge bg-secondary mb-2">Audio {{ ['2CH', '4CH', '8CH', '16CH'][($i-1) % 4] }}</span>
                                            <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                 alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                 class="float-end" 
                                                 style="width: 30px;">
                                            <div class="product-image-container my-3">
                                                <img src="https://via.placeholder.com/200x150?text=Audio+DVR" class="img-fluid" alt="DVR">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                    DHI-{{ ['2', '4', '8', '16'][($i-1) % 4] }}A-AUDIO
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-3">Audio Recording</h6>
                                            <ul class="list-unstyled small specs-list">
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Built-in Microphone</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Two-way Audio</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Audio Detection</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> H.265+ Video</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Up to 8MP</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Smart Motion Detection</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Mobile App</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Night Vision</li>
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white border-top text-center">
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                <i class="bi bi-eye"></i> Lihat Spesifikasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <!-- FULL COLOR TAB (Pro) -->
                        <div class="tab-pane fade" id="pro" role="tabpanel">
                            <div class="row g-3">
                                @for($i = 1; $i <= 16; $i++)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card h-100 product-card border">
                                        <div class="card-header bg-light text-center py-3">
                                            <span class="badge bg-warning text-dark mb-2">Full Color</span>
                                            <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                 alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                 class="float-end" 
                                                 style="width: 30px;">
                                            <div class="product-image-container my-3">
                                                <img src="https://via.placeholder.com/200x150?text=Full+Color" class="img-fluid" alt="Full Color">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                    FC-{{ ['2MP', '4MP', '5MP', '8MP'][($i-1) % 4] }}
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-3">Full Color Night Vision</h6>
                                            <ul class="list-unstyled small specs-list">
                                                <li><i class="bi bi-check-circle-fill text-success"></i> 24/7 Color Images</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Warm LED Light</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> F1.0 Super Aperture</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> AI Human Detection</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Smart Tracking</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> IP67 Waterproof</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Wide Dynamic Range</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Remote Access</li>
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white border-top text-center">
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                <i class="bi bi-eye"></i> Lihat Spesifikasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <!-- IP CAMERA TAB (Enterprise) -->
                        <div class="tab-pane fade" id="enterprise" role="tabpanel">
                            <div class="row g-3">
                                @for($i = 1; $i <= 16; $i++)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card h-100 product-card border">
                                        <div class="card-header bg-light text-center py-3">
                                            <span class="badge bg-primary mb-2">IP Camera</span>
                                            <img src="https://via.placeholder.com/30x30?text={{ substr($brandData['name'] ?? 'D', 0, 1) }}" 
                                                 alt="{{ $brandData['name'] ?? 'Brand' }}" 
                                                 class="float-end" 
                                                 style="width: 30px;">
                                            <div class="product-image-container my-3">
                                                <img src="https://via.placeholder.com/200x150?text=IP+Cam" class="img-fluid" alt="IP Camera">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <span class="badge px-3 py-2" style="background-color: {{ $brandData['color'] ?? '#003D7A' }}; color: white;">
                                                    IPC-{{ ['2MP', '4MP', '5MP', '8MP'][($i-1) % 4] }}-{{ $i }}
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-3">Enterprise IP Camera</h6>
                                            <ul class="list-unstyled small specs-list">
                                                <li><i class="bi bi-check-circle-fill text-success"></i> {{ ['2MP', '4MP', '5MP', '8MP'][($i-1) % 4] }} Resolution</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> AI Deep Learning</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Face Recognition</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> License Plate Recognition</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> PoE Powered</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> MicroSD Card Slot</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> ONVIF Compatible</li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i> Smart Codec</li>
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white border-top text-center">
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                <i class="bi bi-eye"></i> Lihat Spesifikasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-star-fill text-warning"></i> Kenapa Pilih Kami?
            </h3>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-award fs-1 text-primary mb-2"></i>
                <h6 class="fw-bold">Produk Original</h6>
                <p class="small text-muted mb-0">100% produk asli dengan garansi resmi</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-tools fs-1 text-success mb-2"></i>
                <h6 class="fw-bold">Instalasi Profesional</h6>
                <p class="small text-muted mb-0">Tim teknisi berpengalaman</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-headset fs-1 text-info mb-2"></i>
                <h6 class="fw-bold">Support 24/7</h6>
                <p class="small text-muted mb-0">Layanan pelanggan siap membantu</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="feature-box text-center p-3">
                <i class="bi bi-truck fs-1 text-warning mb-2"></i>
                <h6 class="fw-bold">Pengiriman Cepat</h6>
                <p class="small text-muted mb-0">Pengiriman ke seluruh Indonesia</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row">
        <div class="col-12">
            <div class="cta-box p-5 rounded-3 text-center" style="background: linear-gradient(135deg, {{ $brandData['color'] ?? '#667eea' }} 0%, {{ $brandData['colorDark'] ?? '#764ba2' }} 100%); color: white;">
                <h3 class="fw-bold mb-3">Tertarik dengan Produk {{ $brandData['name'] ?? $brand }}?</h3>
                <p class="lead mb-4">Konsultasikan kebutuhan keamanan Anda dengan tim ahli kami</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ url('/contact') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-envelope"></i> Hubungi Kami
                    </a>
                    <a href="https://wa.me/62881025756671" target="_blank" class="btn btn-success btn-lg">
                        <i class="bi bi-whatsapp"></i> Chat WhatsApp
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-house"></i> Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Brand Filter Pills */
.brand-filter-btn {
    padding: 12px 32px;
    border-radius: 50px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    min-width: 120px;
    text-align: center;
}

.brand-filter-btn:hover {
    background: #667eea;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.brand-filter-btn.active {
    background: {{ $brandData['color'] ?? '#667eea' }};
    border-color: {{ $brandData['color'] ?? '#667eea' }};
    color: white !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.product-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 8px;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.15) !important;
}

.product-card .card-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}

.product-image-container {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
}

.product-image-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.specs-list {
    font-size: 0.85rem;
}

.specs-list li {
    padding: 4px 0;
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
}

.specs-list i {
    margin-right: 8px;
    font-size: 0.75em;
    margin-top: 4px;
    flex-shrink: 0;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
}

.feature-box {
    transition: all 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-5px);
}

.cta-box {
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: {{ $brandData['color'] ?? '#0d6efd' }};
}

.nav-tabs .nav-link.active {
    color: {{ $brandData['color'] ?? '#0d6efd' }};
    background-color: transparent;
    border-color: transparent transparent {{ $brandData['color'] ?? '#0d6efd' }};
    font-weight: 600;
}

.nav-tabs {
    border-bottom: 2px solid #dee2e6;
}

/* Responsive Brand Filter */
@media (max-width: 768px) {
    .brand-filter-buttons {
        flex-direction: column !important;
    }
    
    .brand-filter-btn {
        width: 100%;
    }
    
    .product-image-container {
        height: 100px;
    }
}

@media (max-width: 991px) {
    .product-card .card-body h6 {
        font-size: 0.95rem;
    }
    
    .specs-list {
        font-size: 0.8rem;
    }
}
</style>

<script>
function viewDetail(productId) {
    // Redirect to product detail page
    if(productId) {
        // window.location.href = '/products/detail/' + productId;
        alert('Detail produk #' + productId + ' - Coming soon!');
    }
}

function contactWhatsApp(productName, sku) {
    const message = `Halo, saya tertarik dengan produk:\n*${productName}*\nSKU: ${sku}\n\nMohon info lebih lanjut.`;
    const whatsappUrl = `https://wa.me/62881025756671?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
</script>
@endsection