@extends('layouts.simple')

@section('title', 'Kelola Testimoni & Instalasi')

@section('content')
<style>
    /* CSS Tambahan untuk memperbaiki tata letak (mencegah tertutup navbar) */
    .admin-wrapper {
        padding-top: 120px; /* Jarak agar tidak tertabrak navbar atas */
        padding-bottom: 60px;
        min-height: 80vh;   /* Memastikan footer tetap di bawah */
        background-color: #f4f7f6; /* Warna background abu-abu lembut khas dashboard */
    }
    
    .custom-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
    }
</style>

<div class="admin-wrapper">
    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bolder mb-1 text-dark">📸 Kelola Hasil Instalasi</h2>
                <p class="text-muted mb-0">Upload gambar testimoni yang akan tampil di halaman depan website.</p>
            </div>
            <button class="btn btn-primary fw-bold px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>

        <div class="card custom-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary">No</th>
                                <th class="py-3 text-secondary">Gambar</th>
                                <th class="py-3 text-secondary">Judul / Keterangan</th>
                                <th class="py-3 text-secondary">Lokasi</th>
                                <th class="text-center pe-4 py-3 text-secondary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $index => $item)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Preview" class="rounded shadow-sm" style="width: 90px; height: 60px; object-fit: cover; border: 1px solid #eee;">
                                </td>
                                <td class="fw-bold text-dark">{{ $item->title }}</td>
                                <td>
                                    @if($item->location)
                                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $item->location }}</span>
                                    @else
                                        <span class="text-muted small"><i class="bi bi-dash"></i> Tidak ada</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <form action="{{ route('admin.testimonials.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-images fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada data gambar instalasi/testimoni.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH DATA --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="addModalLabel">Tambah Hasil Instalasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul / Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" placeholder="Contoh: Instalasi Dahua 8 Channel" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="text" name="location" class="form-control form-control-lg fs-6" placeholder="Contoh: Kawasan Industri Cikarang">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" required onchange="previewImage(event)">
                        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, WEBP. Maksimal 2MB. Disarankan mode landscape.</div>
                    </div>

                    <div class="text-center mt-3">
                        <img id="imgPreview" src="#" alt="Preview" class="img-fluid rounded shadow d-none" style="max-height: 200px; width: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi untuk memunculkan preview gambar saat dipilih
function previewImage(event) {
    const reader = new FileReader();
    const imageField = document.getElementById("imgPreview");
    
    reader.onload = function(){
        if(reader.readyState === 2){
            imageField.src = reader.result;
            imageField.classList.remove('d-none');
        }
    }
    
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>
@endsection