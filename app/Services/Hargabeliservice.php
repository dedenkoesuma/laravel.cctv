<?php

namespace App\Services;

use App\Models\GudangProduct;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HargaBeliService
{
    /**
     * Dipanggil saat PO di-approve / status jadi 'received'
     * Otomatis update harga_beli di gudang_products berdasarkan nama produk
     */
    public function updateDariPO(PurchaseOrder $po): void
    {
        $items = DB::table('purchase_order_items')
            ->where('purchase_order_id', $po->id)
            ->get();

        foreach ($items as $item) {
            // Cari produk di gudang berdasarkan nama (case-insensitive)
            $produk = GudangProduct::whereRaw('LOWER(nama_produk) = ?', [strtolower($item->product_name)])
                ->first();

            if ($produk) {
                $hargaBeli = $item->unit_price ?? 0;
                $margin    = $produk->harga_jual > 0
                    ? (($produk->harga_jual - $hargaBeli) / $produk->harga_jual) * 100
                    : 0;

                $produk->update([
                    'harga_beli'            => $hargaBeli,
                    'margin_persen'         => round($margin, 2),
                    'last_po_id'            => $po->id,
                    'harga_beli_updated_at' => now(),
                ]);

                Log::info("HargaBeli updated: {$produk->nama_produk} → Rp {$hargaBeli} (PO #{$po->po_number})");
            }
        }
    }

    /**
     * Update manual harga beli dari form edit produk
     */
    public function updateManual(GudangProduct $produk, float $hargaBeli): void
    {
        $margin = $produk->harga_jual > 0
            ? (($produk->harga_jual - $hargaBeli) / $produk->harga_jual) * 100
            : 0;

        $produk->update([
            'harga_beli'            => $hargaBeli,
            'margin_persen'         => round($margin, 2),
            'harga_beli_updated_at' => now(),
        ]);
    }

    /**
     * Hitung total HPP dari semua SO yang lunas bulan tertentu
     * Digunakan di dashboard keuangan bos
     */
   public function hitungHPPBulan(int $bulan, int $tahun): array
{
    $items = DB::table('keuangan_transaksi as kt')
        ->join('sales_orders as so',       'so.so_number',       '=', 'kt.no_order')
        ->join('sales_order_items as soi', 'soi.sales_order_id', '=', 'so.id')
        ->join('gudang_products as gp',    'gp.id',              '=', 'soi.product_id')
        ->select(
            'gp.nama_produk',
            'gp.harga_beli',
            'gp.harga_jual',
            'soi.qty',
            'soi.harga_satuan',
            DB::raw('gp.harga_beli * soi.qty                                    as total_modal'),
            DB::raw('soi.harga_satuan * soi.qty                                 as total_jual'),
            DB::raw('(soi.harga_satuan - gp.harga_beli) * soi.qty               as profit'),
            DB::raw('CASE
                WHEN soi.harga_satuan > 0
                THEN ROUND((soi.harga_satuan - gp.harga_beli) / soi.harga_satuan * 100, 2)
                ELSE 0
            END as margin_persen')
        )
        ->where('kt.tipe',   'pemasukan')
        ->where('kt.status', 'lunas')
        ->whereNotNull('kt.no_order')
        ->whereMonth('kt.tanggal', $bulan)
        ->whereYear('kt.tanggal',  $tahun)
        ->get();

    $totalJual   = $items->sum('total_jual');
    $totalProfit = $items->sum('profit');

    return [
        'items'        => $items,
        'total_modal'  => $items->sum('total_modal'),
        'total_jual'   => $totalJual,
        'total_profit' => $totalProfit,
        'avg_margin'   => $totalJual > 0 ? round(($totalProfit / $totalJual) * 100, 2) : 0,
    ];
}
}