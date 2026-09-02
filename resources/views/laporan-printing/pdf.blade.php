<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Printing</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
        }
        .header p {
            font-size: 10px;
            color: #6b7280;
            margin: 0;
        }
        .summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary td {
            width: 25%;
            padding: 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .summary .label {
            font-size: 9px;
            color: #6b7280;
            display: block;
            margin-bottom: 2px;
        }
        .summary .value {
            font-size: 13px;
            font-weight: bold;
        }
        .laba { color: #16a34a; }
        .rugi { color: #dc2626; }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 18px 0 6px 0;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 3px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data th {
            background: #f3f4f6;
            text-align: left;
            padding: 5px 6px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        table.data td {
            padding: 5px 6px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; color: #9ca3af; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PRINTING</h1>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Summary --}}
    <table class="summary">
        <tr>
            <td>
                <span class="label">TOTAL PESANAN</span>
                <span class="value">{{ $ringkasanPesanan['total_pesanan'] }}</span>
            </td>
            <td>
                <span class="label">OMZET (SELESAI)</span>
                <span class="value">Rp {{ number_format($ringkasanPesanan['total_omzet'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">UANG MASUK</span>
                <span class="value text-green">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">UANG KELUAR</span>
                <span class="value text-red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td>
                <span class="label">LABA / RUGI</span>
                <span class="value {{ $labaRugi >= 0 ? 'laba' : 'rugi' }}">
                    Rp {{ number_format($labaRugi, 0, ',', '.') }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Pesanan per Platform --}}
    <div class="section-title">Pesanan per Platform (Selesai)</div>
    <table class="data">
        <thead>
            <tr>
                <th>Platform</th>
                <th>Jumlah</th>
                <th>Omzet</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ringkasanPesanan['per_platform'] as $platform => $row)
                <tr>
                    <td>{{ $platform }}</td>
                    <td>{{ $row['jumlah'] }}</td>
                    <td>Rp {{ number_format($row['omzet'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Detail Pesanan --}}
    <div class="section-title">Detail Pesanan</div>
    <table class="data">
        <thead>
            <tr>
                <th>No. Order</th>
                <th>Pelanggan</th>
                <th>Platform</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $p)
                <tr>
                    <td>{{ $p->no_order }}</td>
                    <td>{{ $p->pelanggan }}</td>
                    <td>{{ $p->platform }}</td>
                    <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>{{ $p->status }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Uang Masuk --}}
    <div class="section-title">Uang Masuk</div>
    <table class="data">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($uangMasuk as $u)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $u->kategori }}</td>
                    <td>{{ $u->keterangan }}</td>
                    <td class="text-green">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Uang Keluar --}}
    <div class="section-title">Uang Keluar</div>
    <table class="data">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($uangKeluar as $u)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($u->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $u->kategori }}</td>
                    <td>{{ $u->keterangan }}</td>
                    <td class="text-red">Rp {{ number_format($u->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>