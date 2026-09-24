<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Cetak — Toko Print</title>
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
                    <span class="w-2 h-2 rounded-full bg-violet-500 shadow-sm shadow-violet-500/50"></span>
                    <span>Toko Print · Tagihan & Pembayaran</span>
                </div>
                <h1 class="text-2xl font-bold font-display text-white tracking-tight">Invoice Cetak</h1>
                <p class="text-sm text-slate-400 mt-0.5">Kelola penagihan invoice dan status pelunasan pelanggan cetak</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('invoice.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-700 hover:from-violet-500 hover:to-indigo-600 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-violet-900/30 hover:shadow-violet-700/40 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Invoice</span>
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
            {{-- Total Invoice --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-violet-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-indigo-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Invoice</span>
                    <span class="p-2 rounded-lg bg-violet-500/10 text-violet-400 border border-violet-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-white tracking-tight">
                    {{ $summary['total'] }}
                </div>
                <div class="text-[11px] text-slate-500 mt-2 flex items-center gap-1.5">
                    <span class="text-emerald-400 font-medium">{{ $summary['lunas'] }} tagihan lunas</span>
                </div>
            </div>

            {{-- Belum Dibayar --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Belum Dibayar</span>
                    <span class="p-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-amber-400 tracking-tight">
                    {{ $summary['unpaid'] }}
                </div>
                <div class="text-[11px] text-amber-400/80 mt-2">
                    Menunggu pembayaran pelanggan
                </div>
            </div>

            {{-- Lunas --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Lunas</span>
                    <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-emerald-400 tracking-tight">
                    {{ $summary['lunas'] }}
                </div>
                <div class="text-[11px] text-emerald-400/80 mt-2">
                    Telah dibayar penuh
                </div>
            </div>

            {{-- Lewat Jatuh Tempo --}}
            <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-rose-500/40 transition">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-600"></div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jatuh Tempo</span>
                    <span class="p-2 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="text-2xl lg:text-3xl font-bold font-display text-rose-400 tracking-tight">
                    {{ $summary['overdue'] }}
                </div>
                <div class="text-[11px] text-rose-400/80 mt-2">
                    Perlu ditindaklanjuti segera
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="glass-card rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('invoice.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari no. invoice atau nama pelanggan..."
                           class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                </div>

                <div class="min-w-[190px]">
                    <select name="status" class="w-full bg-[#1A2234] border border-white/10 text-slate-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\TagihanCetak::STATUSES as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                {{ \App\Models\TagihanCetak::STATUS_LABELS[$s] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-500 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter</span>
                    </button>

                    @if(request()->anyFilled(['search','status']))
                        <a href="{{ route('invoice.index') }}"
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
                            <th class="px-5 py-3.5">No. Invoice</th>
                            <th class="px-5 py-3.5">Pelanggan</th>
                            <th class="px-5 py-3.5">Tgl. Invoice</th>
                            <th class="px-5 py-3.5">Jatuh Tempo</th>
                            <th class="px-5 py-3.5 text-right">Total</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($invoices as $inv)
                            <tr class="hover:bg-white/[0.025] transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap font-mono font-semibold text-violet-400 text-xs">
                                    {{ $inv->no_tagihan }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-200 font-medium">
                                    {{ $inv->pelanggan }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-400 font-mono text-xs">
                                    {{ optional($inv->tgl_tagihan)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-400 font-mono text-xs">
                                    {{ $inv->jatuh_tempo ? $inv->jatuh_tempo->format('d/m/Y') : '—' }}
                                    @if($inv->is_overdue)
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                            lewat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-semibold text-emerald-400">
                                    {{ $inv->total_rupiah }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @php
                                        $invStyle = [
                                            'unpaid' => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                            'lunas'  => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                            'batal'  => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $invStyle[$inv->status] ?? 'bg-slate-500/15 text-slate-400 border-slate-500/30' }}">
                                        {{ \App\Models\TagihanCetak::STATUS_LABELS[$inv->status] ?? $inv->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($inv->status === 'unpaid')
                                            <form method="POST" action="{{ route('invoice.lunas', $inv->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 transition"
                                                        title="Tandai Sudah Lunas">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Lunas</span>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('invoice.edit', $inv->id) }}"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition"
                                           title="Edit Tagihan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form method="POST" action="{{ route('invoice.destroy', $inv->id) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice {{ $inv->no_tagihan }}?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition"
                                                    title="Hapus Tagihan">
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
                                <td colspan="7" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-500 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-300 font-medium text-sm">Belum ada invoice tercatat</p>
                                        <p class="text-slate-500 text-xs mt-1">Gunakan tombol "Buat Invoice" untuk membuat penagihan cetak baru.</p>
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
                    Menampilkan <span class="font-semibold text-slate-200">{{ $invoices->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-200">{{ $invoices->lastItem() ?? 0 }}</span>
                    dari total <span class="font-semibold text-slate-200">{{ $invoices->total() }}</span> invoice
                </div>
                <div>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>