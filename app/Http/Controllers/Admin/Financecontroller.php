<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/FinanceController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    // ===== HALAMAN STAFF FINANCE =====
    public function index()
    {
        return view('admin.finance.index');
    }

    // ===== API: SUMMARY KHUSUS FINANCE =====
    public function getSummary(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $piutangBulan = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $piutangPending = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->where('status', 'pending')
            ->sum('jumlah');

        $piutangLunas = DB::table('keuangan_transaksi')
            ->where('tipe', 'pemasukan')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $pengeluaranBulan = DB::table('keuangan_transaksi')
            ->where('tipe', 'pengeluaran')
            ->where('status', 'lunas')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalTransaksi = DB::table('keuangan_transaksi')
            ->whereIn('tipe', ['pemasukan', 'pengeluaran'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        return response()->json([
            'piutang_bulan'     => $piutangBulan,
            'piutang_pending'   => $piutangPending,
            'piutang_lunas'     => $piutangLunas,
            'pengeluaran_bulan' => $pengeluaranBulan,
            'total_transaksi'   => $totalTransaksi,
        ]);
    }

    // ===== API: LIST TRANSAKSI FINANCE =====
    public function getTransaksi(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereIn('tipe', ['pemasukan', 'pengeluaran']);

        if ($request->tipe) {
            $tipeDb = $request->tipe === 'piutang' ? 'pemasukan' : 'pengeluaran';
            $query->where('tipe', $tipeDb);
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

        // Konversi tipe pemasukan → piutang untuk frontend
        $transaksi->transform(function ($t) {
            if ($t->tipe === 'pemasukan') $t->tipe = 'piutang';
            return $t;
        });

        return response()->json(['success' => true, 'data' => $transaksi]);
    }

    // ===== API: LIST INVOICE =====
    public function getInvoices(Request $request)
    {
        $query = DB::table('keuangan_transaksi')
            ->whereNotNull('invoice_number');

        if ($request->status) $query->where('status', $request->status);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('no_order',     'like', '%' . $request->search . '%')
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

    // ===== API: DETAIL INVOICE + ITEM SO + SERIAL NUMBER =====
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

    // ===== SIMPAN TRANSAKSI =====
    // ===== SIMPAN TRANSAKSI =====
    public function store(Request $request)
    {
        $request->validate([
            'tipe'          => 'required|in:piutang,pengeluaran',
            'kategori'      => 'required|string',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal'       => 'required|date',
            'deskripsi'     => 'required|string',
            'pihak_terkait' => 'required|string',
            'metode_bayar'  => 'nullable|in:cash,transfer,qris,kartu_kredit',
        ]);

        $kode   = $this->generateKode($request->tipe);
        $tipeDb = $request->tipe === 'piutang' ? 'pemasukan' : 'pengeluaran';
        $defaultStatus = $request->tipe === 'piutang' ? 'pending' : 'lunas';
        $allowedMetode = ['cash', 'transfer', 'qris', 'kartu_kredit'];
        $metode = in_array($request->metode_bayar, $allowedMetode) 
            ? $request->metode_bayar 
            : 'transfer';
        // 1. Simpan data Piutang / Pengeluaran ke database
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
            'created_by'     => session('admin_id', 1),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // 2. ===== BIKIN NOTIFIKASI PIUTANG MANUAL (BIAR MASUK JADI 0) =====
        if ($request->tipe === 'piutang' && ($request->status ?? $defaultStatus) === 'pending' && !empty($request->jatuh_tempo)) {
            $jatuhTempo = \Carbon\Carbon::parse($request->jatuh_tempo);
            $selisih = (int) now()->startOfDay()->diffInDays($jatuhTempo->startOfDay(), false);
            $total = 'Rp ' . number_format($request->jumlah, 0, ',', '.');
            $tglFmt = $jatuhTempo->format('d/m/Y');
            $pihak = $request->pihak_terkait;

            $tipeNotif = 'h3'; // Default
            $judul = "Piutang baru dicatat — {$pihak}";
            $pesan = "Piutang atas nama {$pihak} senilai {$total} dengan jatuh tempo {$tglFmt}.";

            if ($selisih < 0) {
                $tipeNotif = 'overdue';
                $judul = "Piutang overdue — {$pihak}";
                $pesan = "Piutang atas nama {$pihak} senilai {$total} telah melewati jatuh tempo ({$tglFmt}).";
            } elseif ($selisih === 1) {
                $tipeNotif = 'h1';
                $judul = "Jatuh tempo besok! — {$pihak}";
                $pesan = "Piutang atas nama {$pihak} senilai {$total} jatuh tempo besok ({$tglFmt}). Segera follow up.";
            } elseif ($selisih <= 3) {
                $tipeNotif = 'h3';
                $judul = "Jatuh tempo {$selisih} hari lagi — {$pihak}";
                $pesan = "Piutang atas nama {$pihak} senilai {$total} akan jatuh tempo pada {$tglFmt}.";
            }

            // SIMPAN NOTIFIKASI
            \App\Models\Notification::create([
                'tipe'       => $tipeNotif,
                'judul'      => $judul,
                'pesan'      => $pesan,
                'invoice_id' => null,
                'dibaca'     => false // INI KUNCI UTAMANYA: Paksa jadi 0 !
            ]);
        }
        // =================================================================

        return response()->json([
            'success' => true,
            'message' => ($request->tipe === 'piutang' ? 'Piutang' : 'Pengeluaran') . ' ' . $kode . ' berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===== UPDATE STATUS =====
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:lunas,pending,batal']);

        DB::table('keuangan_transaksi')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        $pesan = match($request->status) {
            'lunas' => 'Piutang ditandai LUNAS ✅ — otomatis masuk laporan pemasukan.',
            'batal' => 'Piutang dibatalkan.',
            default => 'Status diperbarui.',
        };

        return response()->json(['success' => true, 'message' => $pesan]);
    }

    // ===== UPDATE TRANSAKSI =====
    public function update(Request $request, $id)
    {
        // Konversi tipe untuk database
        $tipe = $request->tipe;
        if ($tipe === 'piutang') {
            $tipeDb = 'pemasukan';
        } elseif ($tipe === 'pengeluaran') {
            $tipeDb = 'pengeluaran';
        } else {
            // Sudah dalam format DB (pemasukan/pengeluaran)
            $tipeDb = $tipe;
        }

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

    // ===== HAPUS =====
    public function destroy($id)
    {
        DB::table('keuangan_transaksi')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi dihapus!']);
    }

    // ===== DETAIL =====
    public function show($id)
    {
        $trx = DB::table('keuangan_transaksi')->where('id', $id)->first();
        if (!$trx) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);

        return response()->json(['success' => true, 'data' => $trx]);
    }

    // ===== GENERATE KODE =====
    private function generateKode(string $tipe): string
    {
        $tahun  = date('Y');
        $prefix = $tipe === 'piutang' ? 'PIU' : 'EXP';
        $tipeDb = $tipe === 'piutang' ? 'pemasukan' : 'pengeluaran';

        $count = DB::table('keuangan_transaksi')
            ->where('tipe', $tipeDb)
            ->whereYear('created_at', $tahun)
            ->count();

        return $prefix . '-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}