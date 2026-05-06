<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruijie Products Management - TechStore</title>
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">

    <header class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="bg-white p-2 rounded-lg">
                            <i class="fas fa-home text-indigo-600 text-xl"></i>
                        </div>
                        <span class="text-white font-bold text-xl">TechStore</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900">Ruijie Products Management</h1>
                    <p class="text-gray-600 mt-1">Manage your Ruijie network products</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                    
                    @can('create_ruijie')
                    <a href="{{ route('admin.ruijie.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Product
                    </a>
                    @endcan
                    
                    <a href="{{ route('products.ruijie') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-eye mr-2"></i>
                        View Public Page
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search products..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           onkeyup="filterProducts()">
                </div>
                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="filterProducts()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="filterProducts()">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="sortProducts()">
                    <option value="order">Sort by Order</option>
                    <option value="name">Sort by Name</option>
                    <option value="price">Sort by Price</option>
                    <option value="newest">Newest First</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Total Products</p>
                        <p class="text-3xl font-bold text-blue-900 mt-1">{{ collect($products ?? [])->count() }}</p>
                    </div>
                    <div class="bg-blue-500 p-3 rounded-lg">
                        <i class="fas fa-box text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Active</p>
                        <p class="text-3xl font-bold text-green-900 mt-1">{{ collect($products ?? [])->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="bg-green-500 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Featured</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-1">{{ collect($products ?? [])->where('is_featured', true)->count() }}</p>
                    </div>
                    <div class="bg-yellow-500 p-3 rounded-lg">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Categories</p>
                        <p class="text-3xl font-bold text-purple-900 mt-1">{{ collect($categories ?? [])->count() }}</p>
                    </div>
                    <div class="bg-purple-500 p-3 rounded-lg">
                        <i class="fas fa-folder text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden card-hover"
                 data-name="{{ strtolower($product->name) }}"
                 data-category="{{ $product->category ? $product->category->slug : '' }}"
                 data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                 data-price="{{ $product->price ?? 0 }}"
                 data-order="{{ $product->order }}"
                 data-date="{{ $product->created_at }}">
                
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                            <i class="fas fa-network-wired text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                        @if($product->is_featured)
                            <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-lg">Featured</span>
                        @endif
                        @if($product->is_active)
                            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-lg">Active</span>
                        @else
                            <span class="bg-gray-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-lg">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="p-5">
                    @if($product->category)
                        <div class="flex items-center text-xs text-gray-500 mb-2">
                            <i class="fas fa-folder mr-1"></i>
                            <span>{{ $product->category->name }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Model:</span>
                            <span class="font-medium text-gray-700">{{ $product->model ?? 'N/A' }}</span>
                        </div>
                        @if($product->price)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Price:</span>
                                <span class="text-blue-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    @if($product->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                    @endif

                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        @can('edit_ruijie')
                        <a href="{{ route('admin.ruijie.products.edit', $product->id) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        @endcan
                        
                        @can('delete_ruijie')
                        <button onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')" class="flex-1 bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No products found</p>
                
                @can('create_ruijie')
                <a href="{{ route('admin.ruijie.products.create') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Add Your First Product
                </a>
                @endcan
            </div>
            @endforelse
        </div>

        <div id="noResults" class="hidden text-center py-12">
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No products match your filters</p>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Product?</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="deleteProductName" class="font-semibold"></span>"? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                    <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteProductId = null;

        function filterProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.dataset.name;
                const category = card.dataset.category;
                const status = card.dataset.status;

                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                const matchesStatus = !statusFilter || status === statusFilter;

                if (matchesSearch && matchesCategory && matchesStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        }

        function sortProducts() {
            const sortBy = document.getElementById('sortBy').value;
            const grid = document.getElementById('productsGrid');
            const cards = Array.from(document.querySelectorAll('.product-card'));

            cards.sort((a, b) => {
                switch(sortBy) {
                    case 'name': return a.dataset.name.localeCompare(b.dataset.name);
                    case 'price': return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'newest': return new Date(b.dataset.date) - new Date(a.dataset.date);
                    case 'order': default: return parseInt(a.dataset.order) - parseInt(b.dataset.order);
                }
            });

            cards.forEach(card => grid.appendChild(card));
        }

        function deleteProduct(id, name) {
            deleteProductId = id;
            document.getElementById('deleteProductName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteProductId = null;
        }

        function confirmDelete() {
            if (!deleteProductId) return;

            fetch(`/admin/ruijie/products/${deleteProductId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting product: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting product. Please try again.');
            });
        }
    </script>
</body>
</html>