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
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        .header-section {
            background: #090D16;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 1.25rem 0;
            margin-bottom: 2rem;
            color: white;
        }
        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            overflow: hidden;
            background: #ffffff;
        }
        .card-header {
            background: #0f172a;
            color: white;
            font-weight: 800;
            padding: 1.5rem 2rem;
            font-size: 1.25rem;
            letter-spacing: -0.01em;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .form-label {
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .required:after {
            content: " *";
            color: #dc2626;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }
        .form-control, .input-group-text, .form-select {
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            border: 1.5px solid #cbd5e1;
            font-size: 0.95rem;
        }
        .input-group-text {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
            border: 1.5px solid #cbd5e1;
            border-right: none;
        }
        .btn-save {
            background: #dc2626;
            border: none;
            padding: 0.85rem 2.5rem;
            font-weight: 700;
            color: white;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        }
        .btn-save:hover {
            background: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.35);
            color: white;
        }
        .btn-cancel {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            padding: 0.85rem 2rem;
            font-weight: 700;
            color: #475569;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .btn-cancel:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .image-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            padding: 2.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
            display: block;
        }
        .image-upload-area:hover {
            border-color: #dc2626;
            background: #fef2f2;
        }
        .image-preview {
            max-width: 100%;
            max-height: 260px;
            object-fit: cover;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .delete-image-btn {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            padding: 0.45rem 1.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .delete-image-btn:hover {
            background: #dc2626;
            color: white;
        }
        textarea.form-control {
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge" style="background: rgba(220,38,38,0.2); color:#fca5a5; border:1px solid rgba(220,38,38,0.3); font-size:11px; font-weight:700;">
                        CATALOG EDITOR
                    </span>
                    <h3 class="mb-0 text-white fw-bold fs-5">
                        <i class="bi bi-pencil-square me-2 text-danger"></i>Edit Produk: {{ $product->product_name ?? $product->nama_produk ?? 'Static Product' }}
                    </h3>
                </div>
                <div>
                    <a href="{{ route('admin.static-products.index') }}" class="btn btn-outline-light btn-sm fw-bold px-3 py-1.5" style="border-radius:8px; border-color:rgba(255,255,255,0.2);">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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
                                <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #dc2626;"></i>
                                <p class="mb-0 mt-3" style="color: #dc2626; font-weight: 600;">Klik untuk upload gambar baru</p>
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