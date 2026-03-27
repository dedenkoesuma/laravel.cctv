<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - {{ $product->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
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

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
            <p class="text-gray-600 mt-1">Update product information</p>
        </div>

        <!-- Error Messages -->
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

        <!-- Edit Form -->
        <form action="{{ route('admin.ruijie.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Ruijie RG-S2910-24GT4XS-E">
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-semibold text-gray-700 mb-2">
                        Model Number
                    </label>
                    <input type="text" id="model" name="model" value="{{ old('model', $product->model) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., RG-S2910-24GT4XS-E">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., rg-s2910-24gt4xs-e">
                    <p class="text-xs text-gray-500 mt-1">URL-friendly version (lowercase, no spaces)</p>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Price (Rp)
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 2000000">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Features (JSON) -->
                <div>
                    <label for="features" class="block text-sm font-semibold text-gray-700 mb-2">
                        Features (One per line)
                    </label>
                    <textarea id="features" name="features" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter features, one per line...">{{ old('features', is_array($product->features) ? implode("\n", $product->features) : $product->features) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Each line will be a bullet point</p>
                </div>

                <!-- Specifications (JSON) -->
                <div>
                    <label for="specifications" class="block text-sm font-semibold text-gray-700 mb-2">
                        Specifications (Format: Key: Value)
                    </label>
                    <textarea id="specifications" name="specifications" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Ports: 24x GE + 4x 10GE&#10;Power: 100-240V AC&#10;Dimensions: 440 x 220 x 44mm">{{ old('specifications', is_array($product->specifications) ? implode("\n", array_map(fn($k, $v) => "$k: $v", array_keys($product->specifications), $product->specifications)) : $product->specifications) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Format: Key: Value (one per line)</p>
                </div>

                <!-- Current Image -->
                @if($product->image_url)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Image</label>
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-48 mx-auto rounded">
                    </div>
                </div>
                @endif

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Image {{ $product->image_url ? '(Upload new to replace)' : '' }}
                    </label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Recommended: 800x600px, max 2MB</p>
                </div>

                <!-- Image URL (Alternative) -->
                <div>
                    <label for="image_url" class="block text-sm font-semibold text-gray-700 mb-2">
                        Or Enter Image URL
                    </label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="https://example.com/image.jpg">
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" id="order" name="order" value="{{ old('order', $product->order ?? 0) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                </div>

                <!-- Status Toggles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Is Active -->
                    <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                        <div>
                            <label for="is_active" class="font-semibold text-gray-700">Active Status</label>
                            <p class="text-xs text-gray-500">Show on public page</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Is Featured -->
                    <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                        <div>
                            <label for="is_featured" class="font-semibold text-gray-700">Featured</label>
                            <p class="text-xs text-gray-500">Show on homepage</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center rounded-b-lg">
                <a href="{{ route('admin.ruijie.products') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Update Product
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