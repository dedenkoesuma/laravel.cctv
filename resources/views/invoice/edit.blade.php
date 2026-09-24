<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice — Toko Print</title>
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
                <a href="{{ route('invoice.index') }}" class="hover:text-violet-400 transition">Invoice</a>
                <span>/</span>
                <span class="text-slate-300">Edit {{ $invoice->no_tagihan }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display text-white tracking-tight">Perbarui Invoice Cetak</h1>
            <p class="text-sm text-slate-400 mt-1">Ubah data rincian penagihan pelanggan cetak</p>
        </div>

        {{-- Form Box --}}
        <div class="max-w-2xl glass-card rounded-2xl p-6 lg:p-8">
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

            <form method="POST" action="{{ route('invoice.update', $invoice->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">No. Invoice</label>
                        <input type="text" value="{{ $invoice->no_tagihan }}" readonly
                               class="w-full bg-[#1A2234]/60 border border-white/5 text-violet-400 font-mono text-sm rounded-xl px-4 py-2.5 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                            Tanggal Invoice <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tgl_tagihan"
                               value="{{ old('tgl_tagihan', optional($invoice->tgl_tagihan)->format('Y-m-d')) }}"
                               class="w-full bg-[#1A2234] border border-white/10 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition @error('tgl_tagihan') border-rose-500 @enderror">
                        @error('tgl_tagihan') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Nama Pelanggan / Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="pelanggan" value="{{ old('pelanggan', $invoice->pelanggan) }}"
                           class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition @error('pelanggan') border-rose-500 @enderror">
                    @error('pelanggan') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Deskripsi Pesanan Cetak
                    </label>
                    <input type="text" name="deskripsi" value="{{ old('deskripsi', $invoice->deskripsi) }}"
                           class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition @error('deskripsi') border-rose-500 @enderror">
                    @error('deskripsi') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Jatuh Tempo (Opsional)</label>
                        <input type="date" name="jatuh_tempo"
                               value="{{ old('jatuh_tempo', optional($invoice->jatuh_tempo)->format('Y-m-d')) }}"
                               class="w-full bg-[#1A2234] border border-white/10 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition @error('jatuh_tempo') border-rose-500 @enderror">
                        @error('jatuh_tempo') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Status Pembayaran</label>
                        <select name="status"
                                class="w-full bg-[#1A2234] border border-white/10 text-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status', $invoice->status) == $s ? 'selected' : '' }}>
                                    {{ \App\Models\TagihanCetak::STATUS_LABELS[$s] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Total Nilai Tagihan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-xs">
                            Rp
                        </span>
                        <input type="number" name="total" value="{{ old('total', $invoice->total) }}" min="0"
                               class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl pl-11 pr-4 py-2.5 text-sm font-mono focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition @error('total') border-rose-500 @enderror">
                    </div>
                    @error('total') <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Catatan Invoice (Opsional)
                    </label>
                    <textarea name="catatan" rows="3"
                              class="w-full bg-[#1A2234] border border-white/10 text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500 transition">{{ old('catatan', $invoice->catatan) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                    <a href="{{ route('invoice.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-700 hover:from-violet-500 hover:to-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition shadow-lg shadow-violet-900/30 hover:-translate-y-0.5">
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