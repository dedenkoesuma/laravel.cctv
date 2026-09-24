@extends('layouts.simple')

@section('title', 'Kelola Testimoni & Instalasi')

@section('content')
<style>
    .admin-wrapper {
        padding-top: 100px;
        padding-bottom: 60px;
        min-height: 85vh;
        background-color: #0b0f19;
    }
    .testi-header-banner {
        background: linear-gradient(135deg, #090D16 0%, #0F172A 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
        color: #fff;
    }
    .testi-card {
        background: #111827;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }
    .testi-table {
        color: #e2e8f0;
    }
    .testi-table thead th {
        background: #0d1322;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .testi-table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }
    .testi-table tbody tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }
    .btn-dash-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
        transition: all 0.2s ease;
    }
    .btn-dash-back:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateY(-1px);
    }
</style>

<div class="admin-wrapper">
    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3) !important;" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="testi-header-banner d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase;">Portofolio Publik</span>
                    <span class="text-white-50 small">•</span>
                    <span class="text-white-50 small">Landing Page Media</span>
                </div>
                <h2 class="fw-bolder mb-1 text-white fs-3">Galeri & Testimoni Instalasi</h2>
                <p class="text-white-50 mb-0 small">Kelola dokumentasi instalasi riil yang ditampilkan pada halaman beranda website.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn-dash-back">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
                <button class="btn btn-danger fw-bold px-4 py-2 shadow-sm rounded-pill d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-lg"></i> Tambah Dokumentasi
                </button>
            </div>
        </div>

        <div class="card testi-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table testi-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Foto Instalasi</th>
                                <th>Keterangan Proyek</th>
                                <th>Lokasi Pekerjaan</th>
                                <th class="text-center pe-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $index => $item)
                            <tr>
                                <td class="ps-4 text-white-50 fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Preview" class="rounded-3 shadow-sm" style="width: 84px; height: 56px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.1);">
                                </td>
                                <td>
                                    <div class="fw-bold text-white fs-6">{{ $item->title }}</div>
                                    <div class="text-white-50 small">ID #{{ $item->id }}</div>
                                </td>
                                <td>
                                    @if($item->location)
                                        <span class="badge" style="background: rgba(255, 255, 255, 0.06); color: #cbd5e1; border: 1px solid rgba(255, 255, 255, 0.1); padding: 6px 12px; font-weight: 500;">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $item->location }}
                                        </span>
                                    @else
                                        <span class="text-muted small"><i class="bi bi-dash"></i> Tidak tercantum</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <form action="{{ route('admin.testimonials.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill" title="Hapus">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-white-50">
                                    <i class="bi bi-images fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada data dokumentasi instalasi.</p>
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
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom-0 pt-4 px-4 text-white" style="background: linear-gradient(135deg, #090D16 0%, #0F172A 100%);">
                <div>
                    <h5 class="modal-title fw-bold" id="addModalLabel">Tambah Hasil Instalasi Baru</h5>
                    <p class="text-white-50 small mb-0">Upload foto dokumentasi pengerjaan untuk portofolio publik</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-4" style="background: #ffffff;">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Judul / Keterangan Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" placeholder="Contoh: Instalasi Dahua 8 Channel Gedung Perkantoran" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Lokasi Pekerjaan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="text" name="location" class="form-control form-control-lg fs-6" placeholder="Contoh: Kawasan Industri Cikarang, Bekasi">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Upload Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" required onchange="previewImage(event)">
                        <div class="form-text mt-2 text-muted"><i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, WEBP. Maksimal 2MB. Disarankan rasio landscape.</div>
                    </div>

                    <div class="text-center mt-3">
                        <img id="imgPreview" src="#" alt="Preview" class="img-fluid rounded shadow-sm d-none" style="max-height: 200px; width: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4 bg-light">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4 rounded-pill shadow-sm"><i class="bi bi-save me-1"></i> Simpan Data</button>
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