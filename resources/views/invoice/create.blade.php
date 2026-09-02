<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Invoice - Toko Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('invoice.index') }}" class="hover:text-indigo-600">Invoice</a>
                <span>/</span>
                <span class="text-gray-800">Buat invoice baru</span>
            </div>
            <h1 class="text-xl font-medium text-gray-800">Buat Invoice</h1>
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

            <form method="POST" action="{{ route('invoice.store') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">No. Invoice</label>
                        <input type="text"
                               value="{{ $noInvoice }}"
                               readonly
                               class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Tanggal invoice <span class="text-red-500">*</span></label>
                        <input type="date"
                               name="tgl_tagihan"
                               value="{{ old('tgl_tagihan', now()->format('Y-m-d')) }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('tgl_tagihan') border-red-400 @enderror">
                        @error('tgl_tagihan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
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

                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-1">Deskripsi pesanan</label>
                    <input type="text"
                           name="deskripsi"
                           value="{{ old('deskripsi') }}"
                           placeholder="Contoh: Cetak brosur 500 lembar A4"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('deskripsi') border-red-400 @enderror">
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Jatuh tempo</label>
                        <input type="date"
                               name="jatuh_tempo"
                               value="{{ old('jatuh_tempo') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('jatuh_tempo') border-red-400 @enderror">
                        @error('jatuh_tempo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status') == $s ? 'selected' : '' }}>
                                    {{ \App\Models\TagihanCetak::STATUS_LABELS[$s] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-1">Total (Rp) <span class="text-red-500">*</span></label>
                    <input type="number"
                           name="total"
                           value="{{ old('total') }}"
                           min="0"
                           placeholder="150000"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('total') border-red-400 @enderror">
                    @error('total')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-500 mb-1">Catatan</label>
                    <textarea name="catatan"
                              rows="3"
                              placeholder="Catatan tambahan..."
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('invoice.index') }}"
                       class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        Simpan invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>