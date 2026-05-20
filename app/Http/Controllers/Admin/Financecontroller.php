<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        return view('admin.finance.index');
    }

   public function getSummary(Request $request)
{
    $bulan = $request->bulan ?? date('m');
    $tahun = $request->tahun ?? date('Y');

    // Penjualan lunas bulan ini (TRX- saja, bukan PIU-)
    $penjualanBulan = DB::table('keuangan_transaksi')
        ->where('tipe', 'pemasukan')
        ->where('status', 'lunas')
        ->where('kode_transaksi', 'not like', 'PIU-%')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');

    // Piutang belum lunas (PIU- + pending)
    $piutangPending = DB::table('keuangan_transaksi')
        ->where('tipe', 'pemasukan')
        ->where('status', 'pending')
        ->where('kode_transaksi', 'like', 'PIU-%')
        ->sum('jumlah');

    // Piutang lunas bulan ini (PIU- + lunas)
    $piutangLunas = DB::table('keuangan_transaksi')
        ->where('tipe', 'pemasukan')
        ->where('status', 'lunas')
        ->where('kode_transaksi', 'like', 'PIU-%')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');

    // Pengeluaran lunas bulan ini
    $pengeluaranBulan = DB::table('keuangan_transaksi')
        ->where('tipe', 'pengeluaran')
        ->where('status', 'lunas')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');

    // Total semua transaksi lunas bulan ini (finance + SO invoice + PO)
    $totalLunasBulan = DB::table('keuangan_transaksi')
        ->where('status', 'lunas')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');

    return response()->json([
        'piutang_pending'   => $piutangPending,
        'piutang_lunas'     => $piutangLunas,
        'penjualan_bulan'   => $penjualanBulan,
        'pengeluaran_bulan' => $pengeluaranBulan,
        'total_lunas_bulan' => $totalLunasBulan,
    ]);
}

    public function getTransaksi(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereIn('tipe', ['pemasukan', 'pengeluaran']);

        if ($request->tipe) {
            if ($request->tipe === 'pengeluaran') {
                $query->where('tipe', 'pengeluaran');
            } elseif ($request->tipe === 'piutang') {
                $query->where('tipe', 'pemasukan')
                      ->where('kode_transaksi', 'like', 'PIU-%');
            } elseif ($request->tipe === 'penjualan') {
                $query->where('tipe', 'pemasukan')
                      ->where('kode_transaksi', 'not like', 'PIU-%');
            }
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->bulan)  $query->whereMonth('tanggal', $request->bulan);
        if ($request->tahun)  $query->whereYear('tanggal', $request->tahun);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%');
            });
        }

        $transaksi = $query->orderByDesc('tanggal')->orderByDesc('id')->get();

        $transaksi->transform(function ($t) {
            if ($t->tipe === 'pemasukan') {
                // Tentukan tipe dari prefix kode, BUKAN dari status
                // PIU- = piutang (tempo), TRX- = penjualan
                $t->tipe = str_starts_with($t->kode_transaksi, 'PIU-') ? 'piutang' : 'penjualan';
            }
            return $t;
        });

        return response()->json(['success' => true, 'data' => $transaksi]);
    }

    public function getInvoices(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereNotNull('invoice_number');

        if ($request->status) $query->where('status', $request->status);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('no_order',      'like', '%' . $request->search . '%')
                  ->orWhere('pihak_terkait', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_transaksi','like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderByDesc('id')->get();

        $data->transform(function ($inv) {
            $inv->so_number = $inv->no_order;
            return $inv;
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getInvoiceDetail($id)
    {
        $invoice = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice tidak ditemukan'], 404);
        }

        $invoice->so_number = $invoice->no_order;

        $so    = null;
        $items = collect();

        if ($invoice->no_order) {
            $so = DB::table('sales_orders')
                ->where('so_number', $invoice->no_order)
                ->first();

            if ($so) {
                $items = DB::table('sales_order_items')
                    ->leftJoin('gudang_products', 'sales_order_items.product_id', '=', 'gudang_products.id')
                    ->where('sales_order_items.sales_order_id', $so->id)
                    ->select('sales_order_items.*', 'gudang_products.nama_produk', 'gudang_products.sku')
                    ->get()
                    ->map(function ($item) {
                        if (!$item->nama_produk && $item->notes) {
                            $item->nama_produk = $item->notes;
                        }
                        $item->serials = $item->product_id
                            ? DB::table('barang_masuk')
                                ->where('product_id', $item->product_id)
                                ->where('status', 'terjual')
                                ->whereNotNull('serial_number')
                                ->where('serial_number', '!=', '')
                                ->select('id', 'serial_number')
                                ->get()
                            : collect();
                        return $item;
                    });
            }
        }

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'so'      => $so,
            'items'   => $items,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'          => 'required|in:piutang,pengeluaran,penjualan',
            'kategori'      => 'required|string',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal'       => 'required|date',
            'deskripsi'     => 'required|string',
            'pihak_terkait' => 'required|string',
            'metode_bayar'  => 'nullable|in:cash,transfer,qris,kartu_kredit',
        ]);

        $kode   = $this->generateKode($request->tipe);
        $tipeDb = in_array($request->tipe, ['piutang', 'penjualan']) ? 'pemasukan' : 'pengeluaran';

        // Piutang selalu pending, penjualan & pengeluaran ikut input (default lunas)
        $defaultStatus = $request->tipe === 'piutang' ? 'pending' : 'lunas';

        $allowedMetode = ['cash', 'transfer', 'qris', 'kartu_kredit'];
        $metode = in_array($request->metode_bayar, $allowedMetode)
            ? $request->metode_bayar
            : 'transfer';

        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => $tipeDb,
            'kategori'       => $request->kategori,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'jatuh_tempo'    => $request->jatuh_tempo ?: null,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->referensi,
            'metode_bayar'   => $metode,
            'status'         => $request->status ?? $defaultStatus,
            'pihak_terkait'  => $request->pihak_terkait,
            'catatan'        => $request->catatan
                . ($request->metode_bayar && !in_array($request->metode_bayar, $allowedMetode)
                    ? ' | Tipe bayar: ' . $request->metode_bayar
                    : ''),
            'created_by'     => $this->getCreatedBy(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        if ($request->tipe === 'piutang' && ($request->status ?? $defaultStatus) === 'pending' && !empty($request->jatuh_tempo)) {
            $this->buatNotifikasiPiutang($request->pihak_terkait, $request->jumlah, $request->jatuh_tempo);
        }

        $msgType = match($request->tipe) {
            'piutang'    => 'Piutang',
            'penjualan'  => 'Penjualan',
            default      => 'Pengeluaran'
        };

        return response()->json([
            'success' => true,
            'message' => "{$msgType} {$kode} berhasil disimpan!",
            'kode'    => $kode,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:lunas,pending,batal']);

        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        $pesan = match($request->status) {
            'lunas' => 'Transaksi ditandai LUNAS ✅ — otomatis masuk laporan pemasukan/penjualan.',
            'batal' => 'Transaksi dibatalkan.',
            default => 'Status diperbarui.',
        };

        return response()->json(['success' => true, 'message' => $pesan]);
    }

    public function update(Request $request, $id)
    {
        $tipe = $request->tipe;
        $tipeDb = in_array($tipe, ['piutang', 'penjualan']) ? 'pemasukan' : 'pengeluaran';

        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'tipe'          => $tipeDb,
            'kategori'      => $request->kategori,
            'jumlah'        => $request->jumlah,
            'tanggal'       => $request->tanggal,
            'jatuh_tempo'   => $request->jatuh_tempo ?: null,
            'deskripsi'     => $request->deskripsi,
            'referensi'     => $request->referensi,
            'metode_bayar'  => $request->metode_bayar,
            'status'        => $request->status,
            'pihak_terkait' => $request->pihak_terkait,
            'catatan'       => $request->catatan,
            'updated_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate!']);
    }

    public function destroy($id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi dihapus!']);
    }

    public function show($id)
    {
        $trx = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$trx) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== PRIVATE HELPERS =====

    private function generateKode(string $tipe): string
    {
        $tahun  = date('Y');
        $prefix = match($tipe) {
            'piutang'   => 'PIU',
            'penjualan' => 'TRX',
            default     => 'EXP'
        };

        $prefixFull = $prefix . '-' . $tahun . '-';

        $lastTrx = DB::table('keuangan_transaksi')
            ->where('kode_transaksi', 'like', $prefixFull . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $newNumber = $lastTrx ? ((int) substr($lastTrx->kode_transaksi, -4)) + 1 : 1;

        return $prefixFull . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function getCreatedBy(): int
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (\Auth::guard($guard)->check()) {
                return (int) \Auth::guard($guard)->user()->id;
            }
        }
        return 0;
    }

    private function buatNotifikasiPiutang(string $pihak, float $jumlah, string $jatuhTempo): void
    {
        $jatuhTempoCarbon = \Carbon\Carbon::parse($jatuhTempo);
        $selisih = (int) now()->startOfDay()->diffInDays($jatuhTempoCarbon->startOfDay(), false);
        $total   = 'Rp ' . number_format($jumlah, 0, ',', '.');
        $tglFmt  = $jatuhTempoCarbon->format('d/m/Y');

        $tipeNotif = 'h3';
        $judul     = "Piutang baru dicatat — {$pihak}";
        $pesan     = "Piutang atas nama {$pihak} senilai {$total} dengan jatuh tempo {$tglFmt}.";

        if ($selisih < 0) {
            $tipeNotif = 'overdue';
            $judul     = "Piutang overdue — {$pihak}";
            $pesan     = "Piutang atas nama {$pihak} senilai {$total} telah melewati jatuh tempo ({$tglFmt}).";
        } elseif ($selisih === 1) {
            $tipeNotif = 'h1';
            $judul     = "Jatuh tempo besok! — {$pihak}";
            $pesan     = "Piutang atas nama {$pihak} senilai {$total} jatuh tempo besok ({$tglFmt}). Segera follow up.";
        } elseif ($selisih <= 3) {
            $tipeNotif = 'h3';
            $judul     = "Jatuh tempo {$selisih} hari lagi — {$pihak}";
            $pesan     = "Piutang atas nama {$pihak} senilai {$total} akan jatuh tempo pada {$tglFmt}.";
        }

        \App\Models\Notification::create([
            'tipe'       => $tipeNotif,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'invoice_id' => null,
            'dibaca'     => false,
        ]);
    }
}