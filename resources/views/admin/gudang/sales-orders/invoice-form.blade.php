@extends('layouts.simple')

@section('title', 'Buat Invoice - ' . $salesOrder->so_number)

@section('content')
<div class="container-fluid" style="max-width:800px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-file-invoice-dollar me-2 text-success"></i>
                Buat Invoice
            </h4>
            <small class="text-muted">SO: <strong>{{ $salesOrder->so_number }}</strong> — {{ $salesOrder->customer_name }}</small>
        </div>
        <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.sales-orders.store-invoice', $salesOrder->id) }}" method="POST">
        @csrf

        {{-- Ringkasan SO --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle me-2"></i>Ringkasan SO
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor Invoice</small>
                        <strong class="font-monospace text-success fs-5">{{ $invoiceNumber }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor SO</small>
                        <strong class="font-monospace">{{ $salesOrder->so_number }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Customer</small>
                        <strong>{{ $salesOrder->customer_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Total Tagihan</small>
                        <strong class="text-success" id="label_total">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

      {{-- Nomor Rekening --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-info text-white">
                <i class="fas fa-university me-2"></i>Informasi Rekening Tujuan Pembayaran
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Bank</label>
                        {{-- Ganti value "BCA" dengan nama bank kamu --}}
                        <input type="text" name="nama_bank" class="form-control bg-light"
                            value="BCA" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nomor Rekening</label>
                        {{-- Ganti value "1234567890" dengan nomor rekening kamu --}}
                        <input type="text" name="no_rekening" class="form-control font-monospace bg-light"
                            value="5015211951" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Atas Nama</label>
                        {{-- Ganti value "PT TechStore" dengan nama pemilik rekening --}}
                        <input type="text" name="nama_rekening" class="form-control bg-light"
                            value="Nabil Ihsan Kurniasandy" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-money-bill-wave me-2"></i>Pengaturan Pembayaran
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Tipe Bayar --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipe Pembayaran <span class="text-danger">*</span></label>
                        <select name="tipe_bayar" id="tipe_bayar" class="form-select" required>
                            <option value="cash" {{ old('tipe_bayar', 'cash') == 'cash' ? 'selected' : '' }}>Cash (Langsung)</option>
                            <option value="tempo" {{ old('tipe_bayar') == 'tempo' ? 'selected' : '' }}>Tempo (Jatuh Tempo)</option>
                        </select>
                    </div>

                    {{-- Tempo Hari (muncul jika tempo) --}}
                    <div class="col-md-4" id="tempo_hari_field" style="display:none">
                        <label class="form-label fw-bold">Tempo (hari) <span class="text-danger">*</span></label>
                        <input type="number" name="tempo_hari" id="tempo_hari" class="form-control"
                            placeholder="Contoh: 30" min="1"
                            value="{{ old('tempo_hari') }}">
                        <small class="text-muted" id="jatuh_tempo_preview"></small>
                    </div>

                    {{-- Metode Bayar --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Metode Bayar <span class="text-danger">*</span></label>
                        <select name="metode_bayar" class="form-select" required>
                            <option value="transfer" {{ old('metode_bayar','transfer') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cash"     {{ old('metode_bayar') == 'cash'         ? 'selected' : '' }}>Cash</option>
                            <option value="qris"     {{ old('metode_bayar') == 'qris'         ? 'selected' : '' }}>QRIS</option>
                            <option value="kartu_kredit" {{ old('metode_bayar') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                        </select>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mt-3">
                    <label class="form-label fw-bold">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2"
                        placeholder="Catatan tambahan untuk invoice...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- DP (Down Payment) --}}
        <div class="card mb-3 shadow-sm border-warning">
            <div class="card-header bg-orange text-white" style="background-color:#e67e22;">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-hand-holding-usd me-2"></i>Down Payment (DP)</span>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="ada_dp" name="ada_dp" value="1"
                            {{ old('ada_dp') ? 'checked' : '' }}>
                        <label class="form-check-label text-white fw-bold" for="ada_dp">Aktifkan DP</label>
                    </div>
                </div>
            </div>
            <div class="card-body" id="dp_section" style="display:none">
                <div class="row g-3 align-items-end">
                    {{-- Tipe DP --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipe DP <span class="text-danger">*</span></label>
                        <select id="tipe_dp" name="tipe_dp" class="form-select">
                            <option value="nominal" {{ old('tipe_dp','nominal') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            <option value="persen"  {{ old('tipe_dp') == 'persen'  ? 'selected' : '' }}>Persentase (%)</option>
                        </select>
                    </div>

                    {{-- Nilai DP --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold" id="label_nilai_dp">Jumlah DP (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="prefix_dp">Rp</span>
                            <input type="number" id="nilai_dp" name="nilai_dp" class="form-control"
                                placeholder="0" min="0" step="1000"
                                value="{{ old('nilai_dp') }}">
                        </div>
                        <small class="text-muted" id="dp_equivalent"></small>
                    </div>

                    {{-- Sisa Tagihan --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">Sisa Tagihan</label>
                        <div class="form-control bg-light fw-bold text-danger" id="sisa_tagihan_display">
                            Rp 0
                        </div>
                        <input type="hidden" name="dp_nominal" id="dp_nominal_hidden">
                        <input type="hidden" name="sisa_tagihan" id="sisa_tagihan_hidden">
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info mt-3 mb-0 py-2 small" id="dp_info_box">
                    <i class="fas fa-info-circle me-1"></i>
                    Customer membayar DP sebesar <strong id="info_dp_amount">Rp 0</strong>,
                    sisa <strong id="info_sisa_amount">Rp 0</strong> dibayar kemudian.
                </div>
            </div>
        </div>

        {{-- Ringkasan Item --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-boxes me-2"></i>Item Produk
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesOrder->items as $item)
                        <tr>
                            <td>{{ $item->nama_produk ?? $item->notes }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        @if($salesOrder->ppn_aktif && $salesOrder->ppn_nominal > 0)
                        <tr>
                            <td colspan="3" class="text-end text-muted">PPN ({{ floatval($salesOrder->ppn_rate) }}%)</td>
                            <td class="text-danger">+Rp {{ number_format($salesOrder->ppn_nominal, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="fw-bold text-success">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        {{-- Baris DP & Sisa (muncul jika DP aktif) --}}
                        <tr id="row_dp_summary" style="display:none">
                            <td colspan="3" class="text-end text-muted">DP Dibayar</td>
                            <td class="text-primary fw-bold" id="cell_dp_summary">Rp 0</td>
                        </tr>
                        <tr id="row_sisa_summary" style="display:none">
                            <td colspan="3" class="text-end fw-bold text-danger">SISA TAGIHAN</td>
                            <td class="fw-bold text-danger" id="cell_sisa_summary">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('admin.sales-orders.show', $salesOrder->id) }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-file-invoice-dollar me-1"></i>Buat Invoice
            </button>
        </div>
    </form>
</div>

{{-- Script inline agar elemen DOM sudah ada saat dieksekusi --}}
<script>
(function () {
    // ── TOTAL dari server ───────────────────────────────────────────
    const TOTAL = {{ $salesOrder->total_amount }};

    // Guard: pastikan semua elemen ada
    const chkDP        = document.getElementById('ada_dp');
    const tipeBayar    = document.getElementById('tipe_bayar');
    if (!chkDP || !tipeBayar) { console.error('Invoice JS: elemen tidak ditemukan'); return; }
    const tempoField    = document.getElementById('tempo_hari_field');
    const tempoInput    = document.getElementById('tempo_hari');
    const preview       = document.getElementById('jatuh_tempo_preview');

    function toggleTempo() {
        const isTempo = tipeBayar.value === 'tempo';
        tempoField.style.display = isTempo ? 'block' : 'none';
        tempoInput.required      = isTempo;
        if (!isTempo) {
            tempoInput.value   = '';
            preview.textContent = '';
        } else {
            updatePreview();
        }
    }

    function updatePreview() {
        const hari = parseInt(tempoInput.value);
        if (!isNaN(hari) && hari > 0) {
            const d = new Date();
            d.setDate(d.getDate() + hari);
            preview.textContent = 'Jatuh tempo: ' + d.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            });
        } else {
            preview.textContent = '';
        }
    }

    tipeBayar.addEventListener('change', toggleTempo);
    tempoInput.addEventListener('input', updatePreview);
    toggleTempo(); // ← jalankan saat halaman load (fix bug old value)

    // ── Elemen DP ───────────────────────────────────────────────────
    const dpSection     = document.getElementById('dp_section');
    const tipeDPSel     = document.getElementById('tipe_dp');
    const nilaiDPInput  = document.getElementById('nilai_dp');
    const prefixDP      = document.getElementById('prefix_dp');
    const labelNilaiDP  = document.getElementById('label_nilai_dp');
    const dpEquiv       = document.getElementById('dp_equivalent');
    const sisaDisplay   = document.getElementById('sisa_tagihan_display');
    const dpHidden      = document.getElementById('dp_nominal_hidden');
    const sisaHidden    = document.getElementById('sisa_tagihan_hidden');
    const infoDPAmt     = document.getElementById('info_dp_amount');
    const infoSisaAmt   = document.getElementById('info_sisa_amount');
    const rowDPSum      = document.getElementById('row_dp_summary');
    const cellDPSum     = document.getElementById('cell_dp_summary');
    const rowSisaSum    = document.getElementById('row_sisa_summary');
    const cellSisaSum   = document.getElementById('cell_sisa_summary');

    function rupiah(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function hitungDP() {
        const tipe  = tipeDPSel.value;
        const nilai = parseFloat(nilaiDPInput.value) || 0;
        let dpNominal = 0;

        if (tipe === 'persen') {
            dpNominal = Math.round((nilai / 100) * TOTAL);
            dpEquiv.textContent = nilai > 0 ? '= ' + rupiah(dpNominal) : '';
        } else {
            dpNominal = Math.round(nilai);
            dpEquiv.textContent = nilai > 0
                ? '= ' + (nilai / TOTAL * 100).toFixed(1) + '% dari total'
                : '';
        }

        // Validasi: DP tidak boleh melebihi total
        if (dpNominal > TOTAL) {
            dpNominal = TOTAL;
            nilaiDPInput.value = tipe === 'persen' ? 100 : TOTAL;
            dpEquiv.textContent = 'Maksimal total tagihan';
        }

        const sisa = TOTAL - dpNominal;

        sisaDisplay.textContent     = rupiah(sisa);
        dpHidden.value              = dpNominal;
        sisaHidden.value            = sisa;
        infoDPAmt.textContent       = rupiah(dpNominal);
        infoSisaAmt.textContent     = rupiah(sisa);
        cellDPSum.textContent       = rupiah(dpNominal);
        cellSisaSum.textContent     = rupiah(sisa);
    }

    function toggleDPSection() {
        const aktif = chkDP.checked;
        dpSection.style.display    = aktif ? 'block' : 'none';
        rowDPSum.style.display     = aktif ? '' : 'none';
        rowSisaSum.style.display   = aktif ? '' : 'none';

        // Wajib isi jika DP aktif
        nilaiDPInput.required = aktif;

        if (aktif) {
            hitungDP();
        } else {
            dpHidden.value   = '';
            sisaHidden.value = '';
        }
    }

    function toggleTipeDP() {
        const isPersen = tipeDPSel.value === 'persen';
        prefixDP.textContent        = isPersen ? '%' : 'Rp';
        labelNilaiDP.innerHTML      = isPersen
            ? 'Persentase DP (%) <span class="text-danger">*</span>'
            : 'Jumlah DP (Rp) <span class="text-danger">*</span>';
        nilaiDPInput.step           = isPersen ? '1' : '1000';
        nilaiDPInput.max            = isPersen ? '100' : TOTAL;
        nilaiDPInput.placeholder    = isPersen ? 'Contoh: 30' : 'Contoh: 5000000';
        nilaiDPInput.value          = '';
        dpEquiv.textContent         = '';
        hitungDP();
    }

    chkDP.addEventListener('change', toggleDPSection);
    tipeDPSel.addEventListener('change', toggleTipeDP);
    nilaiDPInput.addEventListener('input', hitungDP);

    toggleDPSection(); // init
})();
</script>
@endsection