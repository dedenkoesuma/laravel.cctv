@extends('layouts.simple')

@section('title', 'Gudang Mini - Admin')

@section('content')
<style>
    .gudang-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%); color: white; padding: 32px 24px; border-radius: 14px; margin-bottom: 28px; }
    .summary-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
    .summary-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center; border-left: 4px solid #667eea; }
    .summary-card.danger { border-left-color: #ef4444; }
    .summary-card.warning { border-left-color: #f59e0b; }
    .summary-card.success { border-left-color: #10b981; }
    .summary-value { font-size: 2rem; font-weight: 800; color: #1f2937; }
    .summary-label { font-size: 0.8rem; color: #6b7280; margin-top: 4px; font-weight: 500; }
    .toolbar { background: white; border-radius: 12px; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
    .toolbar input, .toolbar select { border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 14px; font-size: 0.875rem; outline: none; }
    .table-card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); overflow: hidden; }
    .stok-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
    .stok-badge.habis { background: #fee2e2; color: #dc2626; }
    .stok-badge.menipis { background: #fef3c7; color: #d97706; }
    .stok-badge.aman { background: #d1fae5; color: #059669; }
    .history-group { border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 12px; overflow: hidden; }
    .history-group-header { background: #f8fafc; padding: 12px 16px; cursor: pointer; display: flex; justify-content: space-between; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid #e5e7eb;}
    .history-item { padding: 10px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; }
    .type-badge { padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .type-badge.masuk { background: #d1fae5; color: #065f46; }
    .type-badge.keluar { background: #fee2e2; color: #991b1b; }
    .ket-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 700; }
    .ket-badge.terjual { background: #dbeafe; color: #1d4ed8; }
    .loading-overlay { text-align: center; padding: 40px; color: #9ca3af; }
</style>

<div class="container py-4">
    <div class="gudang-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fs-3 fw-bold mb-1">📦 Gudang Mini</h1>
                <p class="mb-0 opacity-75">Kelola stok barang masuk dan kontrol inventaris</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalBarangKeluar">
                    <i class="bi bi-box-arrow-up me-2"></i>Barang Keluar
                </button>
                <button class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#modalBarangMasuk">
                    <i class="bi bi-plus-circle me-2"></i>Barang Masuk
                </button>
            </div>
        </div>
    </div>

    <div class="summary-cards">
        <div class="summary-card"><div class="summary-value" id="sumTotalProduk">-</div><div class="summary-label">Total Jenis Produk</div></div>
        <div class="summary-card success"><div class="summary-value" id="sumTotalStok">-</div><div class="summary-label">Total Stok Tersedia</div></div>
        <div class="summary-card warning"><div class="summary-value" id="sumMenipis">-</div><div class="summary-label">Stok Menipis (≤5)</div></div>
        <div class="summary-card danger"><div class="summary-value" id="sumHabis">-</div><div class="summary-label">Stok Habis</div></div>
    </div>

    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="🔍 Cari nama produk / brand / SKU..." style="flex:1;" oninput="debounceLoad()">
        <select id="filterCategory" onchange="loadProducts()"><option value="">Semua Kategori</option></select>
        <select id="filterStok" onchange="loadProducts()">
            <option value="">Semua Stok</option>
            <option value="tersedia">Stok Aman</option>
            <option value="menipis">Menipis (≤5)</option>
            <option value="habis">Habis</option>
        </select>
        <button class="btn btn-outline-secondary btn-sm" onclick="loadProducts()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
    </div>

    <div class="table-card">
        <div id="loadingProducts" class="loading-overlay"><div class="spinner-border text-primary" role="status"></div></div>
        <div id="tableContainer" style="display:none;">
            <table class="table mb-0">
                <thead>
                    <tr><th>Nama Produk</th><th>Brand / SKU</th><th>Kategori</th><th class="text-center">Masuk</th><th class="text-center">Keluar</th><th class="text-center">Sisa Stok</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL BARANG MASUK --}}
<div class="modal fade" id="modalBarangMasuk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Input Barang Masuk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="formBarangMasuk">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nama Produk*</label><input type="text" class="form-control" name="nama_produk" list="produkSuggestions" required><datalist id="produkSuggestions"></datalist></div>
                        <div class="col-md-3"><label class="form-label">Brand</label><input type="text" class="form-control" name="brand"></div>
                        <div class="col-md-3"><label class="form-label">Kategori</label><input type="text" class="form-control" name="category"></div>
                        <div class="col-md-4"><label class="form-label">SKU</label><input type="text" class="form-control" name="sku"></div>
                        <div class="col-md-4"><label class="form-label">Harga Beli</label><input type="number" class="form-control" name="harga_beli"></div>
                        <div class="col-md-4"><label class="form-label">Tanggal*</label><input type="date" class="form-control" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-md-4"><label class="form-label">Jumlah*</label><input type="number" class="form-control" name="jumlah" value="1" required></div>
                        <div class="col-12"><label class="form-label">SN (Opsional)</label><textarea class="form-control" name="serial_numbers" rows="2"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-primary" onclick="simpanBarangMasuk()">Simpan</button></div>
        </div>
    </div>
</div>

{{-- MODAL BARANG KELUAR --}}
<div class="modal fade" id="modalBarangKeluar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white"><h5 class="modal-title">Input Barang Keluar</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="formBarangKeluar">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Pilih Produk*</label><select class="form-select" id="keluarProdukSelect" name="product_id" required onchange="onProdukKeluarChange()"></select></div>
                        <div class="col-md-6"><label class="form-label">Keterangan*</label><select class="form-select" name="keterangan" required><option value="terjual">Terjual</option><option value="rusak">Rusak</option><option value="retur">Retur</option></select></div>
                        <div class="col-md-6"><label class="form-label">Tanggal Keluar*</label><input type="date" class="form-control" name="tanggal_keluar" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Jumlah Keluar*</label><input type="number" class="form-control" id="keluarJumlah" name="jumlah" value="1" required></div>
                        <div class="col-md-6"><label class="form-label">Harga Jual</label><input type="number" class="form-control" name="harga_jual"></div>
                        <div class="col-12"><label class="form-label">Nama Penerima</label><input type="text" class="form-control" name="penerima" placeholder="Contoh: PT Jaya"></div>
                        <div class="col-12"><label class="form-label">Catatan</label><input type="text" class="form-control" name="catatan"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-danger" onclick="simpanBarangKeluar()">Simpan Keluar</button></div>
        </div>
    </div>
</div>

{{-- MODAL HISTORY --}}
<div class="modal fade" id="modalHistory" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="historyTitle">History</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body" id="historyBody"></div>
        </div>
    </div>
</div>
<script>
    let debounceTimer;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    document.addEventListener('DOMContentLoaded', () => { 
        loadProducts(); 
        loadCategories(); 
        loadProdukSuggestions(); 
    });

    function debounceLoad() { clearTimeout(debounceTimer); debounceTimer = setTimeout(loadProducts, 400); }

    // Fungsi yang sebelumnya hilang
    function loadCategories() {
        fetch('/api/admin/gudang/categories')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('filterCategory');
                select.innerHTML = '<option value="">Semua Kategori</option>';
                if(data.success && data.categories) {
                    data.categories.forEach(cat => {
                        if(cat) select.innerHTML += `<option value="${cat}">${cat}</option>`;
                    });
                }
            }).catch(e => console.error('Gagal memuat kategori:', e));
    }

    function loadProducts() {
        document.getElementById('loadingProducts').style.display = 'block';
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('filterCategory').value;
        const stok = document.getElementById('filterStok').value;

        fetch(`/api/admin/gudang/products?search=${search}&category=${category}&stok=${stok}`)
        .then(r => r.json()).then(data => {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            data.products.forEach(p => {
                let badge = p.sisa_stok <= 0 ? 'habis' : (p.sisa_stok <= 5 ? 'menipis' : 'aman');
                tbody.innerHTML += `
                    <tr>
                        <td><strong>${p.nama_produk}</strong></td>
                        <td>${p.brand || '-'} <br><small>${p.sku || ''}</small></td>
                        <td>${p.category || '-'}</td>
                        <td class="text-center text-success">${p.total_masuk}</td>
                        <td class="text-center text-danger">${p.total_keluar}</td>
                        <td class="text-center"><span class="stok-badge ${badge}">${p.sisa_stok}</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="showHistory(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')">Detail</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="hapusProduk(${p.id}, '${p.nama_produk.replace(/'/g, "\\'")}')">Hapus</button>
                        </td>
                    </tr>`;
            });
            document.getElementById('sumTotalProduk').innerText = data.summary.total_produk;
            document.getElementById('sumTotalStok').innerText = data.summary.total_stok;
            document.getElementById('sumMenipis').innerText = data.summary.produk_menipis;
            document.getElementById('sumHabis').innerText = data.summary.produk_habis;
        }).finally(() => { document.getElementById('loadingProducts').style.display = 'none'; document.getElementById('tableContainer').style.display = 'block'; });
    }

    function hapusProduk(id, nama) {
        if (!confirm(`Hapus produk "${nama}"? Semua riwayat akan hilang.`)) return;
        fetch(`/api/admin/gudang/products/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(r => r.json()).then(res => { if(res.success) { loadProducts(); loadProdukSuggestions(); } else alert(res.message); });
    }

    function showHistory(productId, productName) {
        document.getElementById('historyTitle').textContent = `History: ${productName}`;
        new bootstrap.Modal(document.getElementById('modalHistory')).show();
        fetch(`/api/admin/gudang/history/${productId}`).then(r => r.json()).then(data => {
            let html = '<hr><h6 class="fw-bold">📅 Riwayat Lengkap</h6>';
            data.history.forEach(group => {
                html += `<div class="history-group"><div class="history-group-header"><strong>${group.tanggal}</strong></div>`;
                group.items.forEach(item => {
                    const isMasuk = item.tipe === 'masuk';
                    html += `
                        <div class="history-item">
                            <div>
                                <span class="type-badge ${isMasuk ? 'masuk' : 'keluar'}">${item.tipe}</span>
                                <strong>${isMasuk ? '+' : '-'}${item.jumlah} unit</strong>
                                ${item.penerima ? `<span class="text-muted small"> → ${item.penerima}</span>` : ''}
                                ${item.harga_jual > 0 ? `<span class="text-muted small"> (Rp ${parseInt(item.harga_jual).toLocaleString()})</span>` : ''}
                                ${item.catatan ? `<br><small class="text-muted">Ket: ${item.catatan}</small>` : ''}
                            </div>
                            <button class="btn btn-sm text-danger" onclick="${isMasuk ? `hapusItem(${item.id}, ${productId})` : `hapusKeluar(${item.id}, ${productId})`}"><i class="bi bi-trash"></i></button>
                        </div>`;
                });
                html += '</div>';
            });
            document.getElementById('historyBody').innerHTML = html;
        });
    }

    function simpanBarangMasuk() {
        const data = Object.fromEntries(new FormData(document.getElementById('formBarangMasuk')).entries());
        fetch('/api/admin/gudang/barang-masuk', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        }).then(r => r.json()).then(res => { 
            if(res.success) { 
                bootstrap.Modal.getInstance(document.getElementById('modalBarangMasuk')).hide(); 
                document.getElementById('formBarangMasuk').reset();
                loadProducts(); 
                loadProdukSuggestions(); 
            } else {
                alert(res.message);
            }
        });
    }

    function simpanBarangKeluar() {
        const data = Object.fromEntries(new FormData(document.getElementById('formBarangKeluar')).entries());
        fetch('/api/admin/gudang/barang-keluar', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        }).then(r => r.json()).then(res => { 
            if(res.success) { 
                bootstrap.Modal.getInstance(document.getElementById('modalBarangKeluar')).hide(); 
                document.getElementById('formBarangKeluar').reset();
                loadProducts(); 
                loadProdukSuggestions(); 
            } else { 
                alert(res.message); 
            } 
        });
    }

    function loadProdukSuggestions() {
        fetch('/api/admin/gudang/products').then(r => r.json()).then(data => {
            const dl = document.getElementById('produkSuggestions');
            const sel = document.getElementById('keluarProdukSelect');
            dl.innerHTML = ''; 
            sel.innerHTML = '<option value="">-- Pilih Produk --</option>'; // Memberikan label default
            
            data.products.forEach(p => {
                dl.innerHTML += `<option value="${p.nama_produk}">`;
                // Tambahkan data stok ke option dropdown
                sel.innerHTML += `<option value="${p.id}" data-sisa="${p.sisa_stok}">${p.nama_produk} (Stok: ${p.sisa_stok})</option>`;
            });
        });
    }

    // Fungsi yang dibutuhkan oleh HTML <select onchange="...">
    function onProdukKeluarChange() {
        const sel = document.getElementById('keluarProdukSelect');
        const sisa = sel.options[sel.selectedIndex]?.dataset?.sisa || 0;
        const inputJumlah = document.getElementById('keluarJumlah');
        
        // Mencegah input jumlah keluar melebihi stok yang ada
        if(inputJumlah) {
            inputJumlah.max = sisa;
        }
    }

    // Fungsi hapus riwayat item spesifik
    function hapusItem(itemId, productId) {
        if (!confirm('Hapus record barang masuk ini?')) return;
        fetch(`/api/admin/gudang/barang-masuk/${itemId}`, {
            method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.json()).then(res => {
            if (res.success) {
                showHistory(productId, document.getElementById('historyTitle').textContent.replace('History: ', ''));
                loadProducts();
                loadProdukSuggestions();
            }
        });
    }

    function hapusKeluar(itemId, productId) {
        if (!confirm('Hapus record barang keluar ini? Stok akan dikembalikan.')) return;
        fetch(`/api/admin/gudang/barang-keluar/${itemId}`, {
            method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.json()).then(res => {
            if (res.success) {
                showHistory(productId, document.getElementById('historyTitle').textContent.replace('History: ', ''));
                loadProducts(); 
                loadProdukSuggestions();
            }
        });
    }
</script>
@endsection