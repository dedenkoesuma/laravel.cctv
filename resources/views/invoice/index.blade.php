<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Toko Print</title>
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
                <h1 class="text-xl font-medium text-gray-800">Invoice</h1>
                <p class="text-sm text-gray-500">Kelola invoice dan status pembayaran pelanggan printing</p>
            </div>
            <a href="{{ route('invoice.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                + Buat Invoice
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
        <p class="text-xs text-gray-500">Total invoice</p>
        <p class="text-2xl font-medium">{{ $summary['total'] }}</p>
        <p class="text-xs text-gray-400">{{ $summary['lunas'] }} lunas</p>
    </div>

    <div class="bg-white rounded-xl border p-4">
        <p class="text-xs text-gray-500">Belum dibayar</p>
        <p class="text-2xl font-medium text-yellow-600">{{ $summary['unpaid'] }}</p>
        <p class="text-xs text-gray-400">menunggu pembayaran</p>
    </div>

    <div class="bg-white rounded-xl border p-4">
        <p class="text-xs text-gray-500">Lunas</p>
        <p class="text-2xl font-medium text-green-600">{{ $summary['lunas'] }}</p>
        <p class="text-xs text-gray-400">sudah dibayar</p>
    </div>

    <div class="bg-white rounded-xl border p-4">
        <p class="text-xs text-gray-500">Lewat jatuh tempo</p>
        <p class="text-2xl font-medium text-red-600">{{ $summary['overdue'] }}</p>
        <p class="text-xs text-gray-400">perlu ditindaklanjuti</p>
    </div>
</div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('invoice.index') }}" class="flex gap-3 mb-4 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari no. invoice atau nama pelanggan..."
                   class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-48">
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua status</option>
                @foreach(\App\Models\TagihanCetak::STATUSES as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ \App\Models\TagihanCetak::STATUS_LABELS[$s] }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Cari
            </button>
            @if(request()->anyFilled(['search','status']))
                <a href="{{ route('invoice.index') }}"
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
                        <th class="text-left px-4 py-3">No. Invoice</th>
                        <th class="text-left px-4 py-3">Pelanggan</th>
                        <th class="text-left px-4 py-3">Tgl. Invoice</th>
                        <th class="text-left px-4 py-3">Jatuh Tempo</th>
                        <th class="text-left px-4 py-3">Total</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-indigo-600">{{ $inv->no_tagihan }}</td>
                            <td class="px-4 py-3">{{ $inv->pelanggan }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ optional($inv->tgl_tagihan)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $inv->jatuh_tempo ? $inv->jatuh_tempo->format('d/m/Y') : '-' }}
                                @if($inv->is_overdue)
                                    <span class="ml-1 text-xs text-red-500">(lewat)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $inv->total_rupiah }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColor = [
                                        'unpaid' => 'bg-yellow-100 text-yellow-700',
                                        'lunas'  => 'bg-green-100 text-green-700',
                                        'batal'  => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $statusColor[$inv->status] ?? '' }}">
                                    {{ \App\Models\TagihanCetak::STATUS_LABELS[$inv->status] ?? $inv->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @if($inv->status === 'unpaid')
                                        <form method="POST" action="{{ route('invoice.lunas', $inv->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-700 text-xs border border-green-200 px-2 py-1 rounded">
                                                Tandai lunas
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('invoice.edit', $inv->id) }}"
                                       class="text-gray-500 hover:text-gray-700 text-xs border px-2 py-1 rounded">
                                        Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('invoice.destroy', $inv->id) }}"
                                          onsubmit="return confirm('Hapus invoice {{ $inv->no_tagihan }}?')">
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
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                Belum ada invoice.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t flex items-center justify-between text-sm text-gray-500">
                <span>
                    Menampilkan {{ $invoices->firstItem() ?? 0 }}–{{ $invoices->lastItem() ?? 0 }}
                    dari {{ $invoices->total() }} invoice
                </span>
                {{ $invoices->links() }}
            </div>
        </div>

    </div>
</div>

</body>
</html>