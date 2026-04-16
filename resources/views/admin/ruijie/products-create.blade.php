<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Ruijie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.ruijie.products') }}" class="flex items-center space-x-3">
                        <div class="bg-white p-2 rounded-lg">
                            <i class="fas fa-arrow-left text-indigo-600"></i>
                        </div>
                        <span class="text-white font-bold text-xl">Back to Products</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-gray-600 mt-1">Create a new Ruijie network product</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3"></i>
                    <div>
                        <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.ruijie.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md">
            @csrf
            
            <input type="hidden" name="redirect_to" value="admin">

            <div class="p-6 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Ruijie RG-S2910-24GT4XS-E">
                </div>

                <div>
                    <label for="model" class="block text-sm font-semibold text-gray-700 mb-2">
                        Model Number
                    </label>
                    <input type="text" id="model" name="model" value="{{ old('model') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., RG-S2910-24GT4XS-E">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., rg-s2910-24gt4xs-e">
                    <p class="text-xs text-gray-500 mt-1">URL-friendly version (lowercase, no spaces, auto-generated)</p>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Price (Rp)
                        </label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="1000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., 2000000">
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                            Stok Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 10) }}" min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan jumlah stok">
                        <p class="text-xs text-gray-500 mt-1">Jumlah produk yang tersedia saat ini</p>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter product description...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="features" class="block text-sm font-semibold text-gray-700 mb-2">
                        Features (Satu per baris)
                    </label>
                    <textarea id="features" name="features" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="24x Gigabit Ethernet ports&#10;4x 10GE SFP+ uplink ports&#10;Advanced Layer 3 routing">{{ old('features') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Setiap baris yang dipisah Enter akan otomatis menjadi list (bullet point).</p>
                </div>

                <div>
                    <label for="specifications" class="block text-sm font-semibold text-gray-700 mb-2">
                        Specifications (Satu per baris)
                    </label>
                    <textarea id="specifications" name="specifications" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Ports: 24x GE + 4x 10GE&#10;Switching Capacity: 128 Gbps&#10;Power: 100-240V AC">{{ old('specifications') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Format: Key: Value (Satu per baris dengan Enter)</p>
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Image
                    </label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Recommended: 800x600px, Max 5MB</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                        <div>
                            <label for="is_active" class="font-semibold text-gray-700">Active Status</label>
                            <p class="text-xs text-gray-500">Show on public page</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                        <div>
                            <label for="is_featured" class="font-semibold text-gray-700">Featured</label>
                            <p class="text-xs text-gray-500">Show on homepage</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center rounded-b-lg">
                <a href="{{ route('admin.ruijie.products') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Create Product
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        });
    </script>
</body>
</html>