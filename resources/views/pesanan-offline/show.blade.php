<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan Offline - Toko Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6 max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Detail Pesanan</h1>
                <p class="text-sm text-gray-500">{{ $pesanan->no_order }}</p>
            </div>
            <a href="{{ route('pesanan-offline.index') }}" class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                ← Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">No. Order</p>
                    <p class="font-medium text-indigo-600">{{ $pesanan->no_order }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    @php
                        $statusColor = [
                            'Proses'     => 'bg-yellow-100 text-yellow-700',
                            'Selesai'    => 'bg-green-100 text-green-700',
                            'Dibatalkan' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $statusColor[$pesanan->status] ?? '' }}">
                        {{ $pesanan->status }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500">Pelanggan</p>
                    <p class="font-medium">{{ $pesanan->pelanggan }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-medium">{{ $pesanan->tanggal }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tipe Kertas</p>
                    <p class="font-medium">{{ $pesanan->tipe_kertas }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Jumlah Lembar</p>
                    <p class="font-medium">{{ $pesanan->jumlah_lembar }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total</p>
                    <p class="font-medium">{{ $pesanan->total_rupiah }}</p>
                </div>
            </div>

            @if($pesanan->catatan)
                <div>
                    <p class="text-gray-500 text-sm">Catatan</p>
                    <p class="text-sm">{{ $pesanan->catatan }}</p>
                </div>
            @endif

            <div class="flex gap-2 pt-2">
                <a href="{{ route('pesanan-offline.edit', $pesanan->id) }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('pesanan-offline.status', $pesanan->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="border border-indigo-200 text-indigo-600 px-4 py-2 rounded-lg text-sm hover:bg-indigo-50">
                        Ubah Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>