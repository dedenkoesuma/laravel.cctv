<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uang Keluar - Toko Print</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('components.sidebar')

    <div class="flex-1 p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Uang Keluar</h1>
                <p class="text-sm text-gray-500">Catat semua pengeluaran kas toko</p>
            </div>
            <a href="{{ route('uang-keluar.create') }}"
               class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                - Tambah Uang Keluar
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Total bulan ini</p>
                <p class="text-2xl font-medium text-red-600">Rp {{ number_format($summary['bulan_ini'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Total hari ini</p>
                <p class="text-2xl font-medium text-red-600">Rp {{ number_format($summary['hari_ini'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Jumlah transaksi</p>
                <p class="text-2xl font-medium text-gray-800">{{ $summary['jumlah_transaksi'] }}</p>
                <p class="text-xs text-gray-400">bulan ini</p>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('uang-keluar.index') }}" class="flex gap-3 mb-4 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari keterangan atau kategori..."
                   class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48">
            <select name="kategori" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua kategori</option>
                @foreach($kategoriList as $k)
                    <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Cari
            </button>
            @if(request()->anyFilled(['search','kategori']))
                <a href="{{ route('uang-keluar.index') }}" class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Kategori</th>
                        <th class="text-left px-4 py-3">Keterangan</th>
                        <th class="text-left px-4 py-3">Jumlah</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaksi as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ $t->tanggal_formatted }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">{{ $t->kategori }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $t->keterangan }}</td>
                            <td class="px-4 py-3 font-medium text-red-600">− {{ $t->jumlah_rupiah }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('uang-keluar.edit', $t->id) }}"
                                       class="text-gray-500 hover:text-gray-700 text-xs border px-2 py-1 rounded">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('uang-keluar.destroy', $t->id) }}"
                                          onsubmit="return confirm('Hapus transaksi ini?')">
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
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Belum ada transaksi uang keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-3 border-t flex items-center justify-between text-sm text-gray-500">
                <span>
                    Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }}
                    dari {{ $transaksi->total() }} transaksi
                </span>
                {{ $transaksi->links() }}
            </div>
        </div>

    </div>
</div>

</body>
</html>