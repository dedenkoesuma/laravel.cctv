<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Online — Toko Print</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                        display: ['Space Grotesk', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #090D16;
            color: #F8FAFC;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
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
        
        {{-- Top Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-white/10">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold tracking-wider uppercase text-slate-500 mb-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/50"></span>
                    <span>Toko Print · Transaksi Penjualan</span>
                </div>
                <h1 class="text-2xl font-bold font-display text-white tracking-tight">Pesanan Online</h1>
                <p class="text-sm text-slate-400 mt-0.5">Kelola pesanan dari WhatsApp, Tokopedia, Shopee, dan Instagram</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pesanan-online.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-700 hover:from-indigo-500 hover:to-violet-600 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-indigo-900/30 hover:shadow-indigo-700/40 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Pesanan</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Executive KPI Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            {{-- Total Pesanan --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-indigo-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                    <span class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-white tracking-tight">
                    {{ $summary['total'] }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="text-indigo-400 font-medium">Bulan berjalan</span>
                </div>
            </div>

            {{-- Sedang Proses --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Proses</span>
                    <span class="p-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-amber-400 tracking-tight">
                    {{ $summary['proses'] }}
                </div>
                <div class="text-[11px] text-amber-400/80 mt-2">
                    Perlu segera dikerjakan
                </div>
            </div>

            {{-- Selesai Hari Ini --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai Hari Ini</span>
                    <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-emerald-400 tracking-tight">
                    {{ $summary['selesai'] }}
                </div>
                <div class="text-[11px] text-emerald-400/80 mt-2">
                    Siap diambil / dikirim
                </div>
            </div>

            {{-- Dibatalkan --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-rose-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-600"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dibatalkan</span>
                    <span class="p-2 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-rose-400 tracking-tight">
                    {{ $summary['batal'] }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2">
                    Total batal bulan ini
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="glass-card rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('pesanan-online.index') }}" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama pelanggan atau no. order..."
                           class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                </div>

                <div class="min-w-[170px]">
                    <select name="platform" class="w-full bg-[#1A2234] border border-white/10 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">Semua Platform</option>
                        @foreach(['WA','Tokopedia','Shopee','Instagram'] as $p)
                            <option value="{{ $p }}" {{ request('platform') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[170px]">
                    <select name="status" class="w-full bg-[#1A2234] border border-white/10 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <option value="">Semua Status</option>
                        @foreach(['Proses','Selesai','Dibatalkan'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->anyFilled(['search','platform','status']))
                        <a href="{{ route('pesanan-online.index') }}"
                           class="inline-flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 font-medium px-4 py-2.5 rounded-xl text-sm transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#161F32]/80 text-slate-400 font-semibold text-xs uppercase tracking-wider border-b border-white/10">
                        <tr>
                            <th class="px-5 py-3.5">No. Order</th>
                            <th class="px-5 py-3.5">Pelanggan</th>
                            <th class="px-5 py-3.5">Platform</th>
                            <th class="px-5 py-3.5">Tipe Kertas</th>
                            <th class="px-5 py-3.5 text-center">Lembar</th>
                            <th class="px-5 py-3.5 text-right">Total</th>
                            <th class="px-5 py-3.5 text-center">Potong</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($pesanan as $p)
                            <tr class="hover:bg-white/[0.025] transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap font-mono font-semibold text-indigo-400 text-xs">
                                    {{ $p->no_order }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-200 font-medium">
                                    {{ $p->pelanggan }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $platStyle = [
                                            'WA'        => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                            'Tokopedia' => 'bg-teal-500/15 text-teal-400 border-teal-500/30',
                                            'Shopee'    => 'bg-orange-500/15 text-orange-400 border-orange-500/30',
                                            'Instagram' => 'bg-pink-500/15 text-pink-400 border-pink-500/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $platStyle[$p->platform] ?? 'bg-slate-500/15 text-slate-400 border-slate-500/30' }}">
                                        {{ $p->platform }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-300 text-xs">
                                    {{ $p->tipe_kertas_text }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center font-mono text-slate-200 text-xs">
                                    {{ $p->jumlah_lembar }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-semibold text-emerald-400">
                                    {{ $p->total_rupiah }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @if($p->jasa_potong)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                                            ✂ Ya
                                        </span>
                                    @else
                                        <span class="text-slate-600 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusStyle = [
                                            'Proses'     => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                            'Selesai'    => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                            'Dibatalkan' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyle[$p->status] ?? 'bg-slate-500/15 text-slate-400 border-slate-500/30' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-400 font-mono text-xs">
                                    {{ $p->tanggal }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Detail --}}
                                        <a href="{{ route('pesanan-online.show', $p->id) }}"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition"
                                           title="Lihat Rincian">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Detail</span>
                                        </a>

                                        {{-- Toggle Status --}}
                                        <form method="POST" action="{{ route('pesanan-online.status', $p->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 border border-indigo-500/20 transition"
                                                    title="Ubah Status">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                <span>Status</span>
                                            </button>
                                        </form>

                                        {{-- Edit --}}
                                        <a href="{{ route('pesanan-online.edit', $p->id) }}"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition"
                                           title="Edit Pesanan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('pesanan-online.destroy', $p->id) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan {{ $p->no_order }}?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition"
                                                    title="Hapus Pesanan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-500 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-300 font-medium text-sm">Tidak ada pesanan online ditemukan</p>
                                        <p class="text-slate-500 text-xs mt-1">Silakan gunakan tombol "Buat Pesanan" di atas untuk menambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination footer --}}
            <div class="px-5 py-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400 bg-[#161F32]/50">
                <div>
                    Menampilkan <span class="font-semibold text-slate-200">{{ $pesanan->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-200">{{ $pesanan->lastItem() ?? 0 }}</span>
                    dari total <span class="font-semibold text-slate-200">{{ $pesanan->total() }}</span> pesanan
                </div>
                <div>
                    {{ $pesanan->links() }}
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>