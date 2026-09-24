<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beranda - Toko Print</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@include('components.sidebar-styles')
<style>
  body{background:var(--bg);color:var(--text);font-family:'Inter',sans-serif;font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased;display:flex;min-height:100vh;overflow-x:hidden;}
  .mono{font-family:'JetBrains Mono',monospace;}

  .ambient{position:fixed;inset:0;pointer-events:none;z-index:0;
    background:radial-gradient(680px 420px at 14% -6%,rgba(245,165,36,0.10),transparent 60%),
               radial-gradient(520px 360px at 100% 8%,rgba(94,177,245,0.06),transparent 60%);
    opacity:0;animation:ambientIn 2.4s var(--ease) forwards;}
  @keyframes ambientIn{to{opacity:1;}}

  .main{flex:1;min-width:0;position:relative;z-index:1;}
  .page{max-width:1320px;margin:0 auto;padding:28px 32px 60px;}

  .topbar{display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;padding-bottom:22px;border-bottom:1px solid var(--line);margin-bottom:22px;position:relative;}
  .id-line{display:flex;align-items:center;gap:10px;color:var(--text-faint);font-size:12px;margin-bottom:10px;}
  .id-dot{width:6px;height:6px;border-radius:50%;position:relative;}
  .topbar h1{font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:600;letter-spacing:-0.01em;}
  .topbar .sub{color:var(--text-dim);font-size:13px;margin-top:4px;}

  .btn{border:none;padding:10px 16px;border-radius:var(--radius-s);font-size:12.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;transition:transform .18s var(--ease),filter .18s var(--ease);text-decoration:none;}
  .btn:hover{transform:translateY(-1px);filter:brightness(1.07);}
  .btn-green{background:var(--green);color:#062315;}
  .btn-red{background:var(--red);color:#2C0A0A;}
  .btn-amber{background:var(--amber);color:#1A1204;}

  .stat-strip{display:grid;gap:1px;background:var(--line);border:1px solid var(--line);border-radius:var(--radius-m);overflow:hidden;margin-bottom:20px;}
  .stat-strip.cols-5{grid-template-columns:repeat(5,1fr);}
  .stat-cell{background:var(--panel);padding:18px 22px;display:flex;flex-direction:column;gap:6px;}
  .stat-cell .label{font-size:11.5px;color:var(--text-dim);}
  .stat-cell .value{font-family:'JetBrains Mono',monospace;font-size:24px;font-weight:600;font-variant-numeric:tabular-nums;}
  .v-green{color:var(--green);}.v-red{color:var(--red);}.v-amber{color:var(--amber);}.v-text{color:var(--text);}

  .split{display:grid;grid-template-columns:1.3fr 1fr;gap:20px;}
  .section-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:12px;}
  .section-head h2{font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;}
  .section-head a{font-size:12px;color:var(--amber);text-decoration:none;}
  .quick-actions{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;}

  .feed-panel{background:var(--panel);border:1px solid var(--line);border-radius:var(--radius-m);overflow:hidden;}
  .feed-row{display:grid;grid-template-columns:60px 1fr auto;gap:14px;align-items:center;padding:12px 18px;border-bottom:1px solid var(--line-soft);}
  .feed-row:last-child{border-bottom:none;}
  .feed-time{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-faint);}
  .feed-desc .cat{font-size:12.5px;color:var(--text);}
  .feed-desc .ref{font-size:11px;color:var(--text-faint);font-family:'JetBrains Mono',monospace;}
  .feed-amt{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;text-align:right;}
  .amt-in{color:var(--green);}.amt-in::before{content:'+ ';}
  .amt-out{color:var(--red);}.amt-out::before{content:'\2212 ';}

  .summary-panel{background:var(--panel);border:1px solid var(--line);border-radius:var(--radius-m);padding:18px 20px;}
  .summary-row{display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--line-soft);}
  .summary-row:last-child{border-bottom:none;}
  .summary-row .k{font-size:12.5px;color:var(--text-dim);display:flex;align-items:center;gap:8px;}
  .summary-dot{width:7px;height:7px;border-radius:50%;}
  .summary-row .v{font-family:'JetBrains Mono',monospace;font-size:13.5px;font-weight:600;font-variant-numeric:tabular-nums;}

  @media(max-width:1000px){
    body{flex-direction:column;}
    .page{padding:20px 16px 50px;}
    .stat-strip.cols-5{grid-template-columns:1fr 1fr;}
    .split{grid-template-columns:1fr;}
  }
</style>
</head>
<body>
<div class="ambient"></div>

@include('components.sidebar')

<div class="main">
  <div class="page">
    <div class="topbar">
      <div>
        <div class="id-line">
          <span class="id-dot" style="background:var(--amber);box-shadow:0 0 0 3px var(--amber-dim);color:var(--amber);"></span>
          TOKO PRINT · RINGKASAN
        </div>
        <h1>Dashboard Print</h1>
        <div class="sub">Ringkasan operasional toko print hari ini, {{ now()->translatedFormat('d F Y') }}</div>
      </div>
      <div>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background:rgba(59,130,246,0.15); color:#60A5FA; border:1px solid rgba(59,130,246,0.3); font-size:12px;">
          <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span>Dashboard Admin CCTV</span>
        </a>
      </div>
    </div>

    <div class="quick-actions">
      <a href="{{ route('pesanan-online.create') }}" class="btn btn-amber">+ Buat Pesanan</a>
      <a href="{{ route('uang-masuk.create') }}" class="btn btn-green">+ Tambah Uang Masuk</a>
      <a href="{{ route('uang-keluar.create') }}" class="btn btn-red">+ Tambah Uang Keluar</a>
    </div>

    <div class="stat-strip cols-5">
      <div class="stat-cell">
        <div class="label">Omset bulan ini</div>
        <div class="value v-text">Rp {{ number_format($omsetBulanIni, 0, ',', '.') }}</div>
      </div>
      <div class="stat-cell">
        <div class="label">Laba bersih (bulan ini)</div>
        <div class="value v-green">Rp {{ number_format($labaBersih, 0, ',', '.') }}</div>
      </div>
      <div class="stat-cell">
        <div class="label">Uang masuk hari ini</div>
        <div class="value v-green">Rp {{ number_format($masukHariIni, 0, ',', '.') }}</div>
      </div>
      <div class="stat-cell">
        <div class="label">Uang keluar hari ini</div>
        <div class="value v-red">Rp {{ number_format($keluarHariIni, 0, ',', '.') }}</div>
      </div>
      <div class="stat-cell">
        <div class="label">Pesanan proses</div>
        <div class="value v-amber">{{ $pesananProses }}</div>
      </div>
    </div>

    <div class="split">
      <div class="section">
        <div class="section-head">
          <h2>Aktivitas kas terbaru</h2>
          <a href="{{ route('uang-masuk.index') }}">Lihat semua →</a>
        </div>
        <div class="feed-panel">
          @forelse($aktivitasKas as $kas)
            <div class="feed-row">
              <span class="feed-time">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d/m') }}</span>
              <div class="feed-desc">
                <div class="cat">{{ $kas->keterangan }}</div>
                <div class="ref">{{ $kas->kategori }}</div>
              </div>
              <span class="feed-amt {{ $kas->tipe === 'masuk' ? 'amt-in' : 'amt-out' }}">
                Rp {{ number_format($kas->jumlah, 0, ',', '.') }}
              </span>
            </div>
          @empty
            <div style="padding:2rem;text-align:center;color:var(--text-faint);font-size:12.5px">Belum ada aktivitas kas</div>
          @endforelse
        </div>
      </div>

      <div class="section">
        <div class="section-head">
          <h2>Ringkasan pesanan</h2>
          <a href="{{ route('pesanan-online.index') }}">Lihat semua →</a>
        </div>
        <div class="summary-panel">
          <div class="summary-row">
            <span class="k"><span class="summary-dot" style="background:var(--text-dim)"></span>Total pesanan</span>
            <span class="v">{{ $totalPesanan }}</span>
          </div>
          <div class="summary-row">
            <span class="k"><span class="summary-dot" style="background:var(--amber)"></span>Sedang proses</span>
            <span class="v" style="color:var(--amber)">{{ $pesananProses }}</span>
          </div>
          <div class="summary-row">
            <span class="k"><span class="summary-dot" style="background:var(--green)"></span>Selesai</span>
            <span class="v" style="color:var(--green)">{{ $pesananSelesai }}</span>
          </div>
          <div class="summary-row" style="border-top:1px solid var(--line);margin-top:4px;padding-top:14px;">
            <span class="k">Total lembar tercetak</span>
            <span class="v">{{ number_format($totalLembar, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>