<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan Online — Toko Print</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #090D16;
            color: #F8FAFC;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            overflow-x: hidden;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-[#090D16] text-slate-100 min-h-screen">

<div class="flex min-h-screen">
    {{-- Sidebar Navigation --}}
    @include('components.sidebar')

    {{-- Main Content Area --}}
    <main class="flex-1 min-w-0 p-6 lg:p-8 bg-[#0B0F19] overflow-y-auto">
        
        {{-- Breadcrumb & Title --}}
        <div class="mb-6 pb-6 border-b border-white/10">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">
                <a href="{{ route('pesanan-online.index') }}" class="hover:text-indigo-400 transition">Pesanan Online</a>
                <span>/</span>
                <span class="text-slate-300">{{ $pesanan->no_order }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display text-white tracking-tight">Detail Pesanan Online</h1>
            <p class="text-sm text-slate-400 mt-1">Rincian spesifikasi cetak, pelanggan, dan status pekerjaan</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3 max-w-2xl">
                <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Detail Card --}}
        <div class="max-w-2xl glass-card rounded-2xl p-6 lg:p-8">
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-white/10">
                <div>
                    <span class="text-xs uppercase font-semibold text-slate-500 tracking-wider">No. Order</span>
                    <h2 class="text-xl font-bold font-mono text-indigo-400 mt-0.5">{{ $pesanan->no_order }}</h2>
                </div>
                @php
                    $statusStyle = [
                        'Proses'     => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                        'Selesai'    => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                        'Dibatalkan' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusStyle[$pesanan->status] ?? 'bg-slate-500/15 text-slate-400 border-slate-500/30' }}">
                    {{ $pesanan->status }}
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm mb-6">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Pelanggan</dt>
                    <dd class="text-slate-100 font-medium">{{ $pesanan->pelanggan }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Platform</dt>
                    <dd class="text-slate-100 font-medium">{{ $pesanan->platform }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Tipe Kertas</dt>
                    <dd class="text-slate-100 font-medium">{{ $pesanan->tipe_kertas_text }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Jumlah Lembar</dt>
                    <dd class="text-slate-100 font-mono font-medium">{{ $pesanan->jumlah_lembar }} lembar</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Jasa Potong</dt>
                    <dd class="text-slate-100 font-medium">{{ $pesanan->jasa_potong ? '✂ Ya, pakai jasa potong' : 'Tidak' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Tanggal Pesanan</dt>
                    <dd class="text-slate-100 font-mono">{{ $pesanan->tanggal }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Total Pembayaran</dt>
                    <dd class="text-emerald-400 font-mono font-bold text-lg">{{ $pesanan->total_rupiah }}</dd>
                </div>
            </dl>

            @if($pesanan->catatan)
                <div class="mb-6 p-4 rounded-xl bg-[#1A2234] border border-white/10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Catatan Pesanan:</p>
                    <p class="text-slate-300 text-sm leading-relaxed">{{ $pesanan->catatan }}</p>
                </div>
            @endif

            <div class="flex items-center justify-between pt-6 border-t border-white/10">
                <a href="{{ route('pesanan-online.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke daftar</span>
                </a>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('pesanan-online.status', $pesanan->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-indigo-500/15 hover:bg-indigo-500/25 text-indigo-300 border border-indigo-500/30 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Ubah Status</span>
                        </button>
                    </form>
                    <a href="{{ route('pesanan-online.edit', $pesanan->id) }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-medium bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>