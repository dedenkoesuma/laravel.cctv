<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan Online — Toko Print</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
                <span class="text-slate-300">Edit {{ $pesanan->no_order }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display text-white tracking-tight">Perbarui Pesanan Online</h1>
            <p class="text-sm text-slate-400 mt-1">Ubah rincian data order dan spesifikasi cetak</p>
        </div>

        {{-- Form Box --}}
        <div class="max-w-3xl glass-card rounded-2xl p-6 lg:p-8">
            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl mb-6 text-sm">
                    <div class="font-semibold mb-1">Terdapat kesalahan pengisian data:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pesanan-online.update', $pesanan->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">No. Order</label>
                        <input type="text" value="{{ $pesanan->no_order }}" readonly
                               class="w-full bg-[#1A2234]/60 border border-white/5 text-indigo-400 font-mono text-sm rounded-xl px-4 py-2.5 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Tanggal Pesanan</label>
                        <input type="text" value="{{ $pesanan->tanggal }}" readonly
                               class="w-full bg-[#1A2234]/60 border border-white/5 text-slate-400 font-mono text-sm rounded-xl px-4 py-2.5 cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Nama Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="pelanggan" value="{{ old('pelanggan', $pesanan->pelanggan) }}"
                               class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition @error('pelanggan') border-rose-500 @enderror">
                        @error('pelanggan') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Platform Sumber <span class="text-rose-500">*</span>
                        </label>
                        <select name="platform"
                                class="w-full bg-[#1A2234] border border-white/10 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition @error('platform') border-rose-500 @enderror">
                            @foreach($platforms as $p)
                                <option value="{{ $p }}" {{ old('platform', $pesanan->platform) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('platform') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Tipe Kertas & Jumlah Lembar <span class="text-rose-500">*</span>
                    </label>
                    <div class="bg-[#1A2234]/70 border border-white/10 rounded-xl p-4 @error('tipe_kertas') border-rose-500 @enderror">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-2">
                                @foreach(collect($tipeKertas)->take(ceil(count($tipeKertas) / 2)) as $t)
                                    @php $checked = isset($currentItems[$t]); @endphp
                                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.05] border border-white/5 transition">
                                        <input type="checkbox"
                                               name="items[{{ $t }}][pilih]"
                                               value="1"
                                               onchange="this.closest('div').querySelector('input[type=number]').disabled = !this.checked"
                                               {{ $checked ? 'checked' : '' }}
                                               class="rounded border-slate-600 bg-[#090D16] text-indigo-600 focus:ring-indigo-500 shrink-0">
                                        <span class="flex-1 text-xs text-slate-300 leading-tight">{{ $t }}</span>
                                        <input type="number"
                                               name="items[{{ $t }}][jumlah]"
                                               min="1"
                                               value="{{ $currentItems[$t] ?? 1 }}"
                                               {{ $checked ? '' : 'disabled' }}
                                               class="w-16 bg-[#090D16] border border-white/10 rounded-lg px-2 py-1 text-xs text-center text-white focus:outline-none focus:border-indigo-500 disabled:opacity-40 shrink-0">
                                    </div>
                                @endforeach
                            </div>
                            <div class="space-y-2">
                                @foreach(collect($tipeKertas)->skip(ceil(count($tipeKertas) / 2)) as $t)
                                    @php $checked = isset($currentItems[$t]); @endphp
                                    <div class="flex items-center gap-2 p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.05] border border-white/5 transition">
                                        <input type="checkbox"
                                               name="items[{{ $t }}][pilih]"
                                               value="1"
                                               onchange="this.closest('div').querySelector('input[type=number]').disabled = !this.checked"
                                               {{ $checked ? 'checked' : '' }}
                                               class="rounded border-slate-600 bg-[#090D16] text-indigo-600 focus:ring-indigo-500 shrink-0">
                                        <span class="flex-1 text-xs text-slate-300 leading-tight">{{ $t }}</span>
                                        <input type="number"
                                               name="items[{{ $t }}][jumlah]"
                                               min="1"
                                               value="{{ $currentItems[$t] ?? 1 }}"
                                               {{ $checked ? '' : 'disabled' }}
                                               class="w-16 bg-[#090D16] border border-white/10 rounded-lg px-2 py-1 text-xs text-center text-white focus:outline-none focus:border-indigo-500 disabled:opacity-40 shrink-0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('tipe_kertas') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Total Biaya (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-xs">
                                Rp
                            </span>
                            <input type="number" name="total" value="{{ old('total', $pesanan->total) }}" min="0"
                                   class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl pl-11 pr-4 py-2.5 text-sm font-mono focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition @error('total') border-rose-500 @enderror">
                        </div>
                        @error('total') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Status Pesanan
                        </label>
                        <select name="status"
                                class="w-full bg-[#1A2234] border border-white/10 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status', $pesanan->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2.5 text-sm text-slate-300 bg-[#1A2234] border border-white/10 rounded-xl px-4 py-3 w-fit cursor-pointer hover:border-indigo-500/40 transition">
                        <input type="checkbox" name="jasa_potong" value="1" {{ old('jasa_potong', $pesanan->jasa_potong) ? 'checked' : '' }}
                               class="rounded border-slate-600 bg-[#090D16] text-indigo-600 focus:ring-indigo-500">
                        <span>Gunakan Jasa Potong Kertas (Finishing)</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Catatan Pesanan
                    </label>
                    <textarea name="catatan" rows="3"
                              class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">{{ old('catatan', $pesanan->catatan) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <a href="{{ route('pesanan-online.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-700 hover:from-indigo-500 hover:to-violet-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-lg shadow-indigo-900/30 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>