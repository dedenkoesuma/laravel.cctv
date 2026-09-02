<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Uang Masuk - Toko Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6">
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('uang-masuk.index') }}" class="hover:text-green-600">Uang masuk</a>
                <span>/</span>
                <span class="text-gray-800">Tambah transaksi</span>
            </div>
            <h1 class="text-xl font-medium text-gray-800">Tambah Uang Masuk</h1>
        </div>

        <div class="max-w-xl bg-white rounded-xl border p-6">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('uang-masuk.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('tanggal') border-red-400 @enderror">
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('kategori') border-red-400 @enderror">
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-500 mb-1">Keterangan <span class="text-red-500">*</span></label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                           placeholder="Contoh: Pembayaran pesanan ORD-0012"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('keterangan') border-red-400 @enderror">
                    @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-500 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" placeholder="100000"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 @error('jumlah') border-red-400 @enderror">
                    @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-500 mb-1">Catatan</label>
                    <textarea name="catatan" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('uang-masuk.index') }}" class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>