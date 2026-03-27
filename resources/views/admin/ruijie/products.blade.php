@extends('layouts.simple')

@section('title', 'Ruijie Products Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Ruijie Products Management</h2>
            <p class="text-muted mb-0">Manage your Ruijie network products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
            <a href="{{ route('admin.ruijie.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
            <a href="{{ route('products.ruijie') }}" class="btn btn-success" target="_blank">
                <i class="fas fa-eye me-2"></i>View Public Page
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="featuredFilter">
                        <option value="">All Products</option>
                        <option value="1">Featured Only</option>
                        <option value="0">Non-Featured</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-box text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Products</h6>
                            <h3 class="mb-0">{{ $products->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="mb-0">{{ $products->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-star text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Featured</h6>
                            <h3 class="mb-0">{{ $products->where('is_featured', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-layer-group text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Categories</h6>
                            <h3 class="mb-0">{{ $categories->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4" id="productsGrid">
        @forelse($products as $product)
        <div class="col-md-6 col-lg-4 col-xl-3 product-item" 
             data-category="{{ $product->category_id }}"
             data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
             data-featured="{{ $product->is_featured ? '1' : '0' }}"
             data-name="{{ strtolower($product->name) }}">
            <div class="card h-100 border-0 shadow-sm">
                <!-- Image -->
                <div class="position-relative">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @if($product->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                        @endif
                        @if($product->is_active)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body">
                    @if($product->category)
                    <div class="small text-muted mb-2">
                        <i class="{{ $product->category->icon ?? 'fas fa-cube' }} me-1"></i>
                        {{ $product->category->name }}
                    </div>
                    @endif
                    
                    <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($product->description, 80) }}
                    </p>
                    
                    @if($product->price)
                    <div class="mb-3">
                        <span class="h5 text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="card-footer bg-transparent border-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.ruijie.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <button onclick="deleteProduct({{ $product->id }})" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>No products yet</h4>
                <p class="text-muted">Start by adding your first Ruijie product</p>
                <a href="{{ route('admin.ruijie.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Product
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>

<script>
// Search filter
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('categoryFilter').addEventListener('change', filterProducts);
document.getElementById('statusFilter').addEventListener('change', filterProducts);
document.getElementById('featuredFilter').addEventListener('change', filterProducts);

function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryId = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    const featured = document.getElementById('featuredFilter').value;
    
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const name = product.dataset.name;
        const prodCategory = product.dataset.category;
        const prodStatus = product.dataset.status;
        const prodFeatured = product.dataset.featured;
        
        let show = true;
        
        if (searchTerm && !name.includes(searchTerm)) show = false;
        if (categoryId && prodCategory !== categoryId) show = false;
        if (status && prodStatus !== status) show = false;
        if (featured && prodFeatured !== featured) show = false;
        
        product.style.display = show ? 'block' : 'none';
    });
}

// Delete product
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/admin/ruijie/products/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete product');
            }
        });
    }
}
</script>
@endsection