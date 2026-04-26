{{-- =====================================================
     FILE: resources/views/penjualan/link-invalid.blade.php
     ===================================================== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Tidak Valid - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0fdf4; display: flex; min-height: 100vh; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.10); max-width: 400px; width: 100%; }
    </style>
</head>
<body>
    <div class="card p-5 text-center">
        <div style="font-size: 3.5rem;">⛔</div>
        <h2 class="fw-bold mt-3 text-danger">Link Tidak Valid</h2>
        <p class="text-muted mt-2">{{ $alasan ?? 'Link ini tidak dapat digunakan.' }}</p>
        <hr>
        <p class="small text-muted">Hubungi admin TechStore untuk mendapatkan link baru.</p>
    </div>
</body>
</html>