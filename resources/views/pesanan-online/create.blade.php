<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan Online - Toko Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('pesanan-online.index') }}" class="hover:text-indigo-600">Pesanan online</a>
                <span>/</span>
                <span class="text-gray-800">Buat pesanan baru</span>
            </div>
            <h1 class="text-xl font-medium text-gray-800">Buat Pesanan Online</h1>
        </div>

        <div class="max-w-2xl bg-white rounded-xl border p-6">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pesanan-online.store') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">No. Order</label>
                        <input type="text"
                               value="{{ $noOrder }}"
                               readonly
                               class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400">
                        <input type="hidden" name="no_order" value="{{ $noOrder }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Tanggal</label>
                        <input type="text"
                               value="{{ now()->format('d/m/Y') }}"
                               readonly
                               class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nama pelanggan <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="pelanggan"
                               value="{{ old('pelanggan') }}"
                               placeholder="Nama lengkap pelanggan"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('pelanggan') border-red-400 @enderror">
                        @error('pelanggan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Platform <span class="text-red-500">*</span></label>
                        <select name="platform"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('platform') border-red-400 @enderror">
                            @foreach($platforms as $p)
                                <option value="{{ $p }}" {{ old('platform') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        @error('platform')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-2">Tipe kertas & jumlah lembar <span class="text-red-500">*</span></label>
                    <div class="border rounded-lg overflow-hidden @error('tipe_kertas') border-red-400 @enderror">
                                                <div class="grid grid-cols-2 divide-x">
                            <div class="divide-y">
                                @foreach(collect($tipeKertas)->take(ceil(count($tipeKertas) / 2)) as $t)
                                    <div class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50">
                                        <input type="checkbox"
                                               id="tk-{{ $loop->parent->index ?? $loop->index }}-{{ $loop->index }}"
                                               name="items[{{ $t }}][pilih]"
                                               value="1"
                                               onchange="this.closest('div').querySelector('input[type=number]').disabled = !this.checked"
                                               {{ collect(old('items', []))->has($t) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300 shrink-0">
                                        <span class="flex-1 text-xs text-gray-700 leading-tight">{{ $t }}</span>
                                        <input type="number"
                                               name="items[{{ $t }}][jumlah]"
                                               min="1"
                                               value="{{ old('items.' . $t . '.jumlah', 1) }}"
                                               {{ collect(old('items', []))->has($t) ? '' : 'disabled' }}
                                               class="w-16 border rounded-lg px-2 py-1 text-xs text-center focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-300 shrink-0">
                                    </div>
                                @endforeach
                            </div>
                                                        <div class="divide-y">
                                @foreach(collect($tipeKertas)->skip(ceil(count($tipeKertas) / 2)) as $t)
                                    <div class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50">
                                        <input type="checkbox"
                                               name="items[{{ $t }}][pilih]"
                                               value="1"
                                               onchange="this.closest('div').querySelector('input[type=number]').disabled = !this.checked"
                                               {{ collect(old('items', []))->has($t) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300 shrink-0">
                                        <span class="flex-1 text-xs text-gray-700 leading-tight">{{ $t }}</span>
                                        <input type="number"
                                               name="items[{{ $t }}][jumlah]"
                                               min="1"
                                               value="{{ old('items.' . $t . '.jumlah', 1) }}"
                                               {{ collect(old('items', []))->has($t) ? '' : 'disabled' }}
                                               class="w-16 border rounded-lg px-2 py-1 text-xs text-center focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-300 shrink-0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Centang tipe kertas yang dipakai, lalu isi jumlah lembarnya masing-masing. Bisa pilih lebih dari satu.
                    </p>
                    @error('tipe_kertas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Total (Rp) <span class="text-red-500">*</span></label>
                        <input type="number"
                               name="total"
                               value="{{ old('total') }}"
                               min="0"
                               placeholder="25000"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('total') border-red-400 @enderror">
                        @error('total')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700 border rounded-lg px-3 py-2 w-fit cursor-pointer">
                        <input type="checkbox"
                               name="jasa_potong"
                               value="1"
                               {{ old('jasa_potong') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                        Pakai jasa potong
                    </label>
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-500 mb-1">Catatan</label>
                    <textarea name="catatan"
                              rows="3"
                              placeholder="Catatan tambahan dari pelanggan..."
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('pesanan-online.index') }}"
                       class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        Simpan pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>