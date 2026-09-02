<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan Offline - Toko Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6 max-w-2xl">
        <div class="mb-6">
            <h1 class="text-xl font-medium text-gray-800">Buat Pesanan Offline</h1>
            <p class="text-sm text-gray-500">No. Order: <span class="font-medium text-indigo-600">{{ $noOrder }}</span></p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pesanan-offline.store') }}" class="bg-white rounded-xl border p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-700 mb-1">Nama Pelanggan</label>
                <input type="text" name="pelanggan" value="{{ old('pelanggan') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Tipe Kertas</label>
                <select name="tipe_kertas" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih tipe kertas</option>
                    @foreach($tipeKertas as $t)
                        <option value="{{ $t }}" {{ old('tipe_kertas') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Jumlah Lembar</label>
                    <input type="number" name="jumlah_lembar" value="{{ old('jumlah_lembar') }}" min="1"
                           class="w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Total (Rp)</label>
                    <input type="number" name="total" value="{{ old('total') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ old('status', 'Proses') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="catatan" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('catatan') }}</textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Simpan Pesanan
                </button>
                <a href="{{ route('pesanan-offline.index') }}" class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>