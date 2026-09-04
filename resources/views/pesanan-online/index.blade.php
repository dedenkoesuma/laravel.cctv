<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Online - Toko Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Pesanan Online</h1>
                <p class="text-sm text-gray-500">Kelola pesanan dari WA, Tokopedia, Shopee, dan Instagram</p>
            </div>
            <a href="{{ route('pesanan-online.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                + Buat Pesanan
            </a>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Total pesanan</p>
                <p class="text-2xl font-medium">{{ $summary['total'] }}</p>
                <p class="text-xs text-green-500">bulan ini</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Sedang proses</p>
                <p class="text-2xl font-medium">{{ $summary['proses'] }}</p>
                <p class="text-xs text-yellow-500">perlu dikerjakan</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Selesai hari ini</p>
                <p class="text-2xl font-medium">{{ $summary['selesai'] }}</p>
                <p class="text-xs text-green-500">sudah diambil</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Dibatalkan</p>
                <p class="text-2xl font-medium">{{ $summary['batal'] }}</p>
                <p class="text-xs text-red-500">bulan ini</p>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('pesanan-online.index') }}"
              class="flex gap-3 mb-4 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau no. order..."
                   class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48">
            <select name="platform" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua platform</option>
                @foreach(['WA','Tokopedia','Shopee','Instagram'] as $p)
                    <option value="{{ $p }}" {{ request('platform') == $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua status</option>
                @foreach(['Proses','Selesai','Dibatalkan'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Cari
            </button>
            @if(request()->anyFilled(['search','platform','status']))
                <a href="{{ route('pesanan-online.index') }}"
                   class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">No. Order</th>
                        <th class="text-left px-4 py-3">Pelanggan</th>
                        <th class="text-left px-4 py-3">Platform</th>
                        <th class="text-left px-4 py-3">Tipe kertas</th>
                        <th class="text-left px-4 py-3">Lembar</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Potong</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanan as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-indigo-600">{{ $p->no_order }}</td>
                            <td class="px-4 py-3">{{ $p->pelanggan }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $platColor = [
                                        'WA'        => 'bg-green-100 text-green-700',
                                        'Tokopedia' => 'bg-yellow-100 text-yellow-700',
                                        'Shopee'    => 'bg-red-100 text-red-700',
                                        'Instagram' => 'bg-pink-100 text-pink-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $platColor[$p->platform] ?? '' }}">
                                    {{ $p->platform }}
                                </span>
                            </td>
                                                        <td class="px-4 py-3">
                                {{ $p->tipe_kertas_text }}
                            </td>
                            <td class="px-4 py-3">{{ $p->jumlah_lembar }}</td>
                            <td class="px-4 py-3">{{ $p->total_rupiah }}</td>
                            <td class="px-4 py-3">
                                @if($p->jasa_potong)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        ✂ Ya
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColor = [
                                        'Proses'     => 'bg-yellow-100 text-yellow-700',
                                        'Selesai'    => 'bg-green-100 text-green-700',
                                        'Dibatalkan' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $statusColor[$p->status] ?? '' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->tanggal }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    {{-- Detail --}}
                                    <a href="{{ route('pesanan-online.show', $p->id) }}"
                                       class="text-gray-500 hover:text-gray-700 text-xs border px-2 py-1 rounded">
                                        Detail
                                    </a>
                                    {{-- Toggle status --}}
                                    <form method="POST"
                                          action="{{ route('pesanan-online.status', $p->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-indigo-500 hover:text-indigo-700 text-xs border border-indigo-200 px-2 py-1 rounded">
                                            Status
                                        </button>
                                    </form>
                                    {{-- Edit --}}
                                    <a href="{{ route('pesanan-online.edit', $p->id) }}"
                                       class="text-gray-500 hover:text-gray-700 text-xs border px-2 py-1 rounded">
                                        Edit
                                    </a>
                                    {{-- Hapus --}}
                                    <form method="POST"
                                          action="{{ route('pesanan-online.destroy', $p->id) }}"
                                          onsubmit="return confirm('Hapus pesanan {{ $p->no_order }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 text-xs border border-red-200 px-2 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                                                        <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                                Tidak ada pesanan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t flex items-center justify-between text-sm text-gray-500">
                <span>
                    Menampilkan {{ $pesanan->firstItem() ?? 0 }}–{{ $pesanan->lastItem() ?? 0 }}
                    dari {{ $pesanan->total() }} pesanan
                </span>
                {{ $pesanan->links() }}
            </div>
        </div>

    </div>
</div>

</body>
</html>