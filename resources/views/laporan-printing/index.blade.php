<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Printing - Toko Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4 no-print">
            <div>
                <h1 class="text-xl font-medium text-gray-800">Laporan Printing</h1>
                <p class="text-sm text-gray-500">Rekap pesanan online &amp; offline, uang masuk, uang keluar &amp; laba rugi</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()"
                        class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                    🖨 Print
                </button>
                <a href="{{ route('laporan-printing.pdf', request()->query()) }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    ⬇ Export PDF
                </a>
            </div>
        </div>

        {{-- Filter Periode --}}
        <form method="GET" action="{{ route('laporan-printing.index') }}"
              class="bg-white rounded-xl border p-4 mb-6 no-print flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Periode</label>
                <select name="periode" id="periode-select"
                        class="border rounded-lg px-3 py-2 text-sm">
                    <option value="hari"   {{ $periode == 'hari'   ? 'selected' : '' }}>Hari ini</option>
                    <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Minggu ini</option>
                    <option value="bulan"  {{ $periode == 'bulan'  ? 'selected' : '' }}>Bulan ini</option>
                    <option value="tahun"  {{ $periode == 'tahun'  ? 'selected' : '' }}>Tahun ini</option>
                    <option value="semua"  {{ $periode == 'semua'  ? 'selected' : '' }}>Semua data</option>
                    <option value="custom" {{ $periode == 'custom' ? 'selected' : '' }}>Custom tanggal</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dari</label>
                <input type="date" name="dari"
                       onchange="document.getElementById('periode-select').value='custom'"
                       value="{{ $dari?->toDateString() }}"
                       class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sampai</label>
                <input type="date" name="sampai"
                       onchange="document.getElementById('periode-select').value='custom'"
                       value="{{ $sampai?->toDateString() }}"
                       class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Terapkan
            </button>
            <span class="text-xs text-gray-400 ml-auto self-center">{{ $periodeLabel }}</span>
        </form>

        {{-- Print Header --}}
        <div class="text-center mb-6 hidden print:block">
            <h2 class="text-lg font-bold">LAPORAN PRINTING</h2>
            <p class="text-sm">{{ $periodeLabel }} — Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Total pesanan</p>
                <p class="text-2xl font-medium">{{ $ringkasanPesanan['total_pesanan'] }}</p>
                <p class="text-xs text-gray-400">{{ $ringkasanPesanan['total_selesai'] }} selesai</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Omzet (selesai)</p>
                {{-- Ini harus dijumlah (+), kalau tidak omzetnya tetap 25.000 --}}
                <p class="text-2xl font-medium">Rp {{ number_format($ringkasanPesanan['total_omzet'] + $ringkasanOffline['total_omzet'], 0, ',', '.') }}</p>
                <p class="text-xs text-green-500">dari pesanan selesai</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Uang masuk</p>
                <p class="text-2xl font-medium text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400">total tercatat</p>
            </div>
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-500">Uang keluar</p>
                <p class="text-2xl font-medium text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400">total tercatat</p>
            </div>
        </div>

        {{-- Laba Rugi --}}
        <div class="bg-white rounded-xl border p-4 mb-6">
            <p class="text-xs text-gray-500">Laba / Rugi</p>
            <p class="text-2xl font-medium {{ $labaRugi >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($labaRugi, 0, ',', '.') }}
            </p>
        </div>

        {{-- Pesanan per Platform --}}
        <h3 class="text-sm font-medium text-gray-700 mb-2">Pesanan per Platform (Selesai)</h3>
        <div class="bg-white rounded-xl border overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">Platform</th>
                        <th class="text-left px-4 py-3">Jumlah</th>
                        <th class="text-left px-4 py-3">Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ringkasanPesanan['per_platform'] as $platform => $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $platform }}</td>
                            <td class="px-4 py-3">{{ $row['jumlah'] }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($row['omzet'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Detail Pesanan Online --}}
        <h3 class="text-sm font-medium text-gray-700 mb-2">Detail Pesanan Online</h3>
        <div class="bg-white rounded-xl border overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">No. Order</th>
                        <th class="text-left px-4 py-3">Pelanggan</th>
                        <th class="text-left px-4 py-3">Platform</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanan as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-indigo-600">{{ $p->no_order }}</td>
                            <td class="px-4 py-3">{{ $p->pelanggan }}</td>
                            <td class="px-4 py-3">{{ $p->platform }}</td>
                            <td class="px-4 py-3">{{ $p->total_rupiah }}</td>
                            <td class="px-4 py-3">{{ $p->status }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->tanggal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Detail Pesanan Offline --}}
        <h3 class="text-sm font-medium text-gray-700 mb-2">Detail Pesanan Offline</h3>
        <div class="bg-white rounded-xl border overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">No. Order</th>
                        <th class="text-left px-4 py-3">Pelanggan</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesananOffline as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-indigo-600">{{ $p->no_order }}</td>
                            <td class="px-4 py-3">{{ $p->pelanggan }}</td>
                            <td class="px-4 py-3">{{ $p->total_rupiah }}</td>
                            <td class="px-4 py-3">{{ $p->status }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->tanggal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Uang Masuk --}}
        <h3 class="text-sm font-medium text-gray-700 mb-2">Uang Masuk</h3>
        <div class="bg-white rounded-xl border overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Kategori</th>
                        <th class="text-left px-4 py-3">Keterangan</th>
                        <th class="text-left px-4 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($uangMasuk as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $u->kategori }}</td>
                            <td class="px-4 py-3">{{ $u->keterangan }}</td>
                            <td class="px-4 py-3 text-green-600">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Uang Keluar --}}
        <h3 class="text-sm font-medium text-gray-700 mb-2">Uang Keluar</h3>
        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 font-normal">
                    <tr>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">Kategori</th>
                        <th class="text-left px-4 py-3">Keterangan</th>
                        <th class="text-left px-4 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($uangKeluar as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $u->kategori }}</td>
                            <td class="px-4 py-3">{{ $u->keterangan }}</td>
                            <td class="px-4 py-3 text-red-600">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
    }
</style>

</body>
</html>