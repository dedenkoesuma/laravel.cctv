<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - {{ $product->product_name ?? 'Static Product' }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
        }
        .header-section {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        .card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            padding: 2rem;
            font-size: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.7rem;
        }
        .required:after {
            content: " *";
            color: #e74c3c;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.3);
            border-width: 2px;
        }
        .form-control, .input-group-text {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border-width: 2px;
        }
        .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 1rem 2.5rem;
            font-weight: 600;
            color: white;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-save:hover {
            background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-cancel {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            border: none;
            padding: 1rem 2.5rem;
            font-weight: 600;
            color: white;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background: linear-gradient(135deg, #7f8c8d 0%, #6c7a7b 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(127, 140, 141, 0.4);
        }
        .image-upload-area {
            border: 3px dashed #667eea;
            border-radius: 1rem;
            padding: 3rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        .image-upload-area:hover {
            border-color: #764ba2;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            transform: scale(1.02);
        }
        .image-preview {
            max-width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .delete-image-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .delete-image-btn:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: scale(1.05);
        }
        textarea.form-control {
            border-radius: 1rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                        <i class="bi bi-pencil-square"></i> Edit Produk
                    </h3>
                </div>
                <div>
                    <a href="{{ route('admin.static-products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Error Messages -->
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="bi bi-exclamation-triangle"></i> Terdapat kesalahan:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('admin.static-products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Form Edit Produk
                </div>
                <div class="card-body p-5">
                    
                    <!-- Image Upload -->
                    <div class="mb-5">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Gambar Produk
                        </label>
                        
                        @if((isset($product->image) && $product->image) || (isset($product->gambar) && $product->gambar))
                            <!-- Current Image -->
                            <div class="text-center mb-3" id="currentImageContainer">
                                <img src="{{ Storage::url($product->image ?? $product->gambar) }}" 
                                     alt="{{ $product->product_name ?? $product->nama_produk ?? 'Product' }}" 
                                     class="image-preview mb-3"
                                     id="currentImage">
                                <div>
                                    <button type="button" class="delete-image-btn" onclick="document.getElementById('removeImageCheck').checked = !document.getElementById('removeImageCheck').checked; toggleImageOpacity();">
                                        <i class="bi bi-trash"></i> Hapus Gambar
                                    </button>
                                    <input type="checkbox" name="remove_image" id="removeImageCheck" value="1" class="d-none">
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Image -->
                        <label for="imageInput" class="image-upload-area">
                            <div id="uploadPlaceholder">
                                <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #667eea;"></i>
                                <p class="mb-0 mt-3" style="color: #667eea; font-weight: 600;">Klik untuk upload gambar baru</p>
                                <small class="text-muted">PNG, JPG, JPEG (Max: 2MB)</small>
                            </div>
                            <div id="newImagePreview" class="d-none">
                                <img id="previewImg" class="image-preview" alt="Preview">
                            </div>
                        </label>
                        <input type="file" 
                               class="d-none @error('image') is-invalid @enderror" 
                               id="imageInput" 
                               name="image" 
                               accept="image/png,image/jpeg,image/jpg">
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="product_name" class="form-label required">
                            <i class="bi bi-tag"></i> Nama Produk
                        </label>
                        <input type="text" 
                               class="form-control @error('product_name') is-invalid @enderror" 
                               id="product_name" 
                               name="product_name" 
                               value="{{ old('product_name', $product->product_name ?? $product->nama_produk) }}" 
                               placeholder="Masukkan nama produk..."
                               required>
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="form-label">
                            <i class="bi bi-currency-dollar"></i> Harga
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price ?? $product->harga ?? $product->harga_jual ?? '') }}" 
                                   min="0"
                                   max="999999999"
                                   step="1000"
                                   placeholder="0">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <label for="description" class="form-label">
                            <i class="bi bi-card-text"></i> Deskripsi
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="6" 
                                  placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description ?? $product->deskripsi ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('admin.static-products.index') }}" class="btn-cancel">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Image Upload Preview
const imageInput = document.getElementById('imageInput');
const newImagePreview = document.getElementById('newImagePreview');
const previewImg = document.getElementById('previewImg');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const removeImageCheck = document.getElementById('removeImageCheck');

if (imageInput) {
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Harap upload file gambar (PNG, JPG, JPEG)');
                this.value = '';
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file harus kurang dari 2MB');
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadPlaceholder.classList.add('d-none');
                newImagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
            
            // Uncheck remove image if new image selected
            if (removeImageCheck) {
                removeImageCheck.checked = false;
                const currentImage = document.getElementById('currentImage');
                if (currentImage) {
                    currentImage.style.opacity = '1';
                }
            }
        } else {
            uploadPlaceholder.classList.remove('d-none');
            newImagePreview.classList.add('d-none');
        }
    });
}

// Toggle image opacity when remove checkbox changes
function toggleImageOpacity() {
    const currentImage = document.getElementById('currentImage');
    if (currentImage) {
        if (removeImageCheck.checked) {
            currentImage.style.opacity = '0.3';
            // Clear file input
            if (imageInput) {
                imageInput.value = '';
                uploadPlaceholder.classList.remove('d-none');
                newImagePreview.classList.add('d-none');
            }
        } else {
            currentImage.style.opacity = '1';
        }
    }
}

// Auto-format price input
const priceInput = document.getElementById('price');
priceInput.addEventListener('blur', function() {
    if (this.value) {
        this.value = Math.round(this.value / 1000) * 1000;
    }
});

// Confirmation before leaving page with unsaved changes
let formChanged = false;
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input, textarea');

inputs.forEach(input => {
    input.addEventListener('change', () => {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

form.addEventListener('submit', () => {
    formChanged = false;
});
</script>

</body>
</html>