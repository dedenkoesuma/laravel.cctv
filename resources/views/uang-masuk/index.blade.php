<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uang Masuk — Toko Print</title>
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
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                    <span>Toko Print · Kas Keuangan</span>
                </div>
                <h1 class="text-2xl font-bold font-display text-white tracking-tight">Uang Masuk</h1>
                <p class="text-sm text-slate-400 mt-0.5">Monitoring dan pencatatan kas masuk operasional toko</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('uang-masuk.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-emerald-900/30 hover:shadow-emerald-700/40 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat Pemasukan</span>
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
        @if(session('error'))
            <div class="bg-amber-500/10 border border-amber-500/30 text-amber-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Executive KPI Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">
            {{-- Total Bulan Ini --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Bulan Ini</span>
                    <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-mono text-emerald-400 tracking-tight">
                    Rp {{ number_format($summary['bulan_ini'], 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="text-emerald-400 font-medium">Pemasukan kas</span>
                    <span>periode {{ now()->translatedFormat('F Y') }}</span>
                </div>
            </div>

            {{-- Total Hari Ini --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-teal-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-cyan-500"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Hari Ini</span>
                    <span class="p-2 rounded-lg bg-teal-500/10 text-teal-400 border border-teal-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-mono text-teal-300 tracking-tight">
                    Rp {{ number_format($summary['hari_ini'], 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2">
                    Kas masuk per {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>

            {{-- Jumlah Transaksi --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-slate-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-400 to-slate-600"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Frekuensi Transaksi</span>
                    <span class="p-2 rounded-lg bg-white/5 text-slate-300 border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-white tracking-tight">
                    {{ $summary['jumlah_transaksi'] }} <span class="text-sm font-normal text-slate-400">transaksi</span>
                </div>
                <div class="text-[11px] text-slate-500 mt-2">
                    Tercatat aktif di bulan berjalan
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="glass-card rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('uang-masuk.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari keterangan atau kategori..."
                           class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <div class="min-w-[190px]">
                    <select name="kategori" class="w-full bg-[#1A2234] border border-white/10 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->anyFilled(['search','kategori']))
                        <a href="{{ route('uang-masuk.index') }}"
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
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Keterangan</th>
                            <th class="px-5 py-3.5 text-right">Jumlah</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($transaksi as $t)
                            <tr class="hover:bg-white/[0.025] transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-slate-300 font-mono text-xs">
                                    {{ $t->tanggal_formatted }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            {{ $t->kategori }}
                                        </span>
                                        @if($t->otomatis)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                                                Auto
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-200 font-medium">
                                    {{ $t->keterangan }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-semibold text-emerald-400">
                                    + {{ $t->jumlah_rupiah }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @if($t->otomatis)
                                        <span class="inline-flex items-center gap-1 text-slate-500 text-xs px-2 py-1 rounded bg-white/[0.02] border border-white/5 cursor-not-allowed" title="Dibuat otomatis dari Pesanan Online">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            <span>Terkunci</span>
                                        </span>
                                    @else
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('uang-masuk.edit', $t->id) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                            <form method="POST" action="{{ route('uang-masuk.destroy', $t->id) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-500 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-300 font-medium text-sm">Belum ada transaksi uang masuk</p>
                                        <p class="text-slate-500 text-xs mt-1">Silakan gunakan tombol "Catat Pemasukan" di atas untuk menambahkan data.</p>
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
                    Menampilkan <span class="font-semibold text-slate-200">{{ $transaksi->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-200">{{ $transaksi->lastItem() ?? 0 }}</span>
                    dari total <span class="font-semibold text-slate-200">{{ $transaksi->total() }}</span> transaksi
                </div>
                <div>
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>