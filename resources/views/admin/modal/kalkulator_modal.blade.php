@extends('layouts.finance')

@section('title', 'Kalkulator Modal')

@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center justify-content-center rounded-3"
         style="width:42px;height:42px;background:#534AB7;">
      <i class="fas fa-calculator text-white"></i>
    </div>
    <div>
      <h5 class="mb-0 fw-semibold">Kalkulator Modal</h5>
      <small class="text-muted">Hitung total biaya pembelian produk</small>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-3">
      <div class="card border-0 bg-light rounded-3 p-3">
        <div class="text-muted" style="font-size:11px;letter-spacing:.4px;">TOTAL PRODUK</div>
        <div class="fw-semibold fs-4" id="st-produk">0</div>
      </div>
    </div>
    <div class="col-3">
      <div class="card border-0 bg-light rounded-3 p-3">
        <div class="text-muted" style="font-size:11px;letter-spacing:.4px;">TOTAL UNIT</div>
        <div class="fw-semibold fs-4" id="st-unit">0</div>
      </div>
    </div>
    <div class="col-3">
      <div class="card border-0 bg-light rounded-3 p-3">
        <div class="text-muted" style="font-size:11px;letter-spacing:.4px;">TOTAL DISKON</div>
        <div class="fw-semibold fs-4" id="st-diskon" style="color:#e74c3c;">Rp 0</div>
      </div>
    </div>
    <div class="col-3">
      <div class="card border-0 bg-light rounded-3 p-3">
        <div class="text-muted" style="font-size:11px;letter-spacing:.4px;">TOTAL MODAL</div>
        <div class="fw-semibold fs-4" id="st-modal" style="color:#534AB7;">Rp 0</div>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="card border rounded-3 mb-3 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="table-layout:fixed;">
        <colgroup>
          <col style="width:40px">
          <col>
          <col style="width:80px">
          <col style="width:140px">
          <col style="width:100px">
          <col style="width:110px">
          <col style="width:120px">
          <col style="width:40px">
        </colgroup>
        <thead class="table-light">
          <tr>
            <th class="text-center text-muted" style="font-size:11px;letter-spacing:.4px;">#</th>
            <th class="text-muted" style="font-size:11px;letter-spacing:.4px;">NAMA PRODUK</th>
            <th class="text-end text-muted" style="font-size:11px;letter-spacing:.4px;">QTY</th>
            <th class="text-end text-muted" style="font-size:11px;letter-spacing:.4px;">HARGA BELI</th>
            <th class="text-end text-muted" style="font-size:11px;letter-spacing:.4px;">DISKON %</th>
            <th class="text-end text-muted" style="font-size:11px;letter-spacing:.4px;">HEMAT</th>
            <th class="text-end text-muted" style="font-size:11px;letter-spacing:.4px;">TOTAL</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="km-tbody"></tbody>
      </table>
    </div>
  </div>

  {{-- Actions --}}
  <div class="d-flex gap-2 mb-4">
    <button type="button" class="btn text-white fw-semibold px-4" style="background:#534AB7;" id="btn-tambah">
      <i class="fas fa-plus me-1"></i> Tambah Produk
    </button>
    <button type="button" class="btn btn-outline-secondary" id="btn-reset">Reset</button>
  </div>

  {{-- Footer Summary --}}
  <div class="card border-0 bg-light rounded-3 p-4">
    <div id="km-ringkasan" class="mb-3"></div>
    <div class="d-flex justify-content-between align-items-center py-2 border-top">
      <span class="text-muted" style="font-size:13px;">Subtotal Sebelum Diskon</span>
      <span class="fw-semibold" id="km-subtotal">Rp 0</span>
    </div>
    <div class="d-flex justify-content-between align-items-center py-2 border-top">
      <span class="text-muted" style="font-size:13px;">Total Hemat (Diskon)</span>
      <span class="fw-semibold" id="km-total-hemat" style="color:#e74c3c;">- Rp 0</span>
    </div>
    <div class="d-flex justify-content-between align-items-center py-2 border-top">
      <span class="fw-bold">Total Modal Keseluruhan</span>
      <span class="fw-bold fs-4" id="km-grand" style="color:#534AB7;">Rp 0</span>
    </div>
  </div>

</div>

<script>
(function() {
    var rows = [], ctr = 0;

    function fmt(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function tambah() {
        ctr++;
        var id = ctr;
        rows.push({ id: id, nama: '', qty: '', harga: '', diskon: '' });
        render();
        setTimeout(function() {
            var el = document.getElementById('km-n' + id);
            if (el) el.focus();
        }, 50);
    }

    function hapus(id) {
        rows = rows.filter(function(r) { return r.id !== id; });
        render();
    }

    function upd(id, field, val) {
        var r = rows.find(function(r) { return r.id === id; });
        if (r) { r[field] = val; updateTotals(); }
    }

    function updateTotals() {
        var grand = 0, units = 0, totalDiskon = 0, subtotalBefore = 0;

        rows.forEach(function(r) {
            var q = parseFloat(r.qty) || 0;
            var h = parseFloat(r.harga) || 0;
            var d = parseFloat(r.diskon) || 0;
            var sub = q * h;
            var hemat = sub * (d / 100);
            var total = sub - hemat;
            subtotalBefore += sub;
            totalDiskon += hemat;
            grand += total;
            units += q;

            var te = document.getElementById('km-t' + r.id);
            if (te) {
                te.textContent = (q && h) ? fmt(total) : '—';
                te.className = 'text-end fw-semibold' + ((!q || !h) ? ' text-muted' : '');
            }
            var he = document.getElementById('km-sv' + r.id);
            if (he) {
                he.textContent = (q && h && d) ? '- ' + fmt(hemat) : '—';
                he.style.color = (q && h && d) ? '#e74c3c' : '#aaa';
            }
        });

        document.getElementById('km-grand').textContent = fmt(grand);
        document.getElementById('km-subtotal').textContent = fmt(subtotalBefore);
        document.getElementById('km-total-hemat').textContent = '- ' + fmt(totalDiskon);
        document.getElementById('st-produk').textContent = rows.length;
        document.getElementById('st-unit').textContent = Math.round(units).toLocaleString('id-ID');
        document.getElementById('st-diskon').textContent = fmt(totalDiskon);
        document.getElementById('st-modal').textContent = fmt(grand);

        var aktif = rows.filter(function(r) {
            return r.nama && parseFloat(r.qty) && parseFloat(r.harga);
        });
        var rb = document.getElementById('km-ringkasan');
        if (aktif.length === 0) {
            rb.innerHTML = '<p class="text-muted mb-0" style="font-size:13px;">Belum ada produk yang diisi lengkap.</p>';
        } else {
            rb.innerHTML = aktif.map(function(r) {
                var q = parseFloat(r.qty) || 0;
                var h = parseFloat(r.harga) || 0;
                var d = parseFloat(r.diskon) || 0;
                var t = (q * h) * (1 - d / 100);
                var pct = grand > 0 ? Math.round(t / grand * 100) : 0;
                return '<div class="d-flex justify-content-between align-items-center mb-2">'
                    + '<span class="text-muted" style="font-size:13px;">' + r.nama + '</span>'
                    + '<div class="d-flex align-items-center gap-3">'
                    + '<span class="text-muted" style="font-size:11px;">' + pct + '%</span>'
                    + '<span class="fw-semibold" style="font-size:13px;">' + fmt(t) + '</span>'
                    + '</div></div>';
            }).join('');
        }
    }

    function render() {
        var tb = document.getElementById('km-tbody');
        if (!tb) return;
        if (rows.length === 0) {
            tb.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4" style="font-size:13px;">Belum ada produk. Klik "Tambah Produk" untuk mulai.</td></tr>';
            updateTotals();
            return;
        }
        tb.innerHTML = rows.map(function(r, i) {
            var q = parseFloat(r.qty) || 0;
            var h = parseFloat(r.harga) || 0;
            var d = parseFloat(r.diskon) || 0;
            var sub = q * h;
            var hemat = sub * (d / 100);
            var total = sub - hemat;
            return '<tr>'
                + '<td class="text-center text-muted" style="font-size:12px;">' + (i + 1) + '</td>'
                + '<td><input id="km-n' + r.id + '" type="text" class="form-control form-control-sm border-0 bg-transparent" value="' + r.nama + '" placeholder="Nama produk..." oninput="kmUpd(' + r.id + ',\'nama\',this.value)"></td>'
                + '<td><input id="km-q' + r.id + '" type="number" class="form-control form-control-sm border-0 bg-transparent text-end" value="' + r.qty + '" placeholder="0" min="0" oninput="kmUpd(' + r.id + ',\'qty\',this.value)"></td>'
                + '<td><input id="km-p' + r.id + '" type="number" class="form-control form-control-sm border-0 bg-transparent text-end" value="' + r.harga + '" placeholder="0" min="0" oninput="kmUpd(' + r.id + ',\'harga\',this.value)"></td>'
                + '<td><input id="km-d' + r.id + '" type="number" class="form-control form-control-sm border-0 bg-transparent text-end" value="' + r.diskon + '" placeholder="0" min="0" max="100" oninput="kmUpd(' + r.id + ',\'diskon\',this.value)"></td>'
                + '<td id="km-sv' + r.id + '" class="text-end" style="font-size:13px;color:' + ((q && h && d) ? '#e74c3c' : '#aaa') + ';">' + ((q && h && d) ? ('- ' + fmt(hemat)) : '—') + '</td>'
                + '<td id="km-t' + r.id + '" class="text-end fw-semibold' + ((!q || !h) ? ' text-muted' : '') + '" style="font-size:13px;">' + ((q && h) ? fmt(total) : '—') + '</td>'
                + '<td class="text-center"><button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="kmHapus(' + r.id + ')"><i class="fas fa-times"></i></button></td>'
                + '</tr>';
        }).join('');
        updateTotals();
    }

    function resetSemua() { rows = []; ctr = 0; render(); }

    window.kmUpd   = upd;
    window.kmHapus = hapus;

    document.getElementById('btn-tambah').addEventListener('click', tambah);
    document.getElementById('btn-reset').addEventListener('click', resetSemua);

    tambah(); tambah(); tambah();
})();
</script>
@endsection