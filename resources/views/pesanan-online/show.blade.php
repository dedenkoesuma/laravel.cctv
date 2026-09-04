<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Toko Print</title>
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
                <span class="text-gray-800">{{ $pesanan->no_order }}</span>
            </div>
            <h1 class="text-xl font-medium text-gray-800">Detail Pesanan</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm max-w-2xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-2xl bg-white rounded-xl border p-6">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs text-gray-500">No. Order</p>
                    <p class="text-lg font-medium text-indigo-600">{{ $pesanan->no_order }}</p>
                </div>
                @php
                    $statusColor = [
                        'Proses'     => 'bg-yellow-100 text-yellow-700',
                        'Selesai'    => 'bg-green-100 text-green-700',
                        'Dibatalkan' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs {{ $statusColor[$pesanan->status] ?? '' }}">
                    {{ $pesanan->status }}
                </span>
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <dt class="text-gray-500">Pelanggan</dt>
                    <dd class="font-medium text-gray-800">{{ $pesanan->pelanggan }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Platform</dt>
                    <dd class="font-medium text-gray-800">{{ $pesanan->platform }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Tipe kertas</dt>
                   <dd class="font-medium text-gray-800">{{ $pesanan->tipe_kertas_text }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Jumlah lembar</dt>
                    <dd class="font-medium text-gray-800">{{ $pesanan->jumlah_lembar }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Jasa potong</dt>
                    <dd class="font-medium text-gray-800">{{ $pesanan->jasa_potong ? 'Ya' : 'Tidak' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Tanggal</dt>
                    <dd class="font-medium text-gray-800">{{ $pesanan->tanggal }}</dd>
                </div>
                @if($pesanan->catatan)
                    <div class="col-span-2">
                        <dt class="text-gray-500">Catatan</dt>
                        <dd class="font-medium text-gray-800">{{ $pesanan->catatan }}</dd>
                    </div>
                @endif
            </dl>

            <div class="flex gap-3 justify-end pt-4 border-t">
                <form method="POST" action="{{ route('pesanan-online.status', $pesanan->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="border border-indigo-200 text-indigo-600 px-4 py-2 rounded-lg text-sm hover:bg-indigo-50">
                        Ubah status → {{ $pesanan->nextStatus() }}
                    </button>
                </form>

                <a href="{{ route('pesanan-online.edit', $pesanan->id) }}"
                   class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Edit
                </a>

                <a href="{{ route('pesanan-online.index') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>