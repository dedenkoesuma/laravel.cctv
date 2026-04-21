<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    // ===== HALAMAN UTAMA GUDANG =====
    public function index()
    {
        return view('admin.gudang');
    }

    // ===== API: GET SEMUA PRODUK + STOK =====
    public function getProducts(Request $request)
    {
        $query = DB::table('gudang_products');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->stok_filter === 'habis') {
            $query->where('sisa_stok', '<=', 0);
        } elseif ($request->stok_filter === 'menipis') {
            $query->where('sisa_stok', '>', 0)->where('sisa_stok', '<=', 5);
        } elseif ($request->stok_filter === 'tersedia') {
            $query->where('sisa_stok', '>', 5);
        }

        $products = $query->orderBy('nama_produk')->get();

        return response()->json([
            'success'  => true,
            'products' => $products,
            'summary'  => [
                'total_produk'   => $products->count(),
                'total_stok'     => $products->sum('sisa_stok'),
                'produk_habis'   => $products->where('sisa_stok', '<=', 0)->count(),
                'produk_menipis' => $products->where('sisa_stok', '>', 0)->where('sisa_stok', '<=', 5)->count(),
            ]
        ]);
    }

    // ===== API: GET HISTORY (MASUK + KELUAR) PER PRODUK =====
    public function getHistory($productId)
    {
        $product = DB::table('gudang_products')->where('id', $productId)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        // Ambil barang masuk
        $masuk = DB::table('barang_masuk')
            ->where('product_id', $productId)
            ->get()
            ->map(function($item) {
                $item->tipe   = 'masuk';
                $item->tanggal = $item->tanggal_masuk;
                return $item;
            });

        // Ambil barang keluar
        $keluar = DB::table('barang_keluar')
            ->where('product_id', $productId)
            ->get()
            ->map(function($item) {
                $item->tipe   = 'keluar';
                $item->tanggal = $item->tanggal_keluar;
                $item->status  = $item->keterangan; // supaya tampil di badge
                return $item;
            });

        // Gabung & sort by tanggal desc
        $allHistory = $masuk->concat($keluar)
            ->sortByDesc('tanggal')
            ->values();

        // Group by tanggal
        $grouped = $allHistory->groupBy('tanggal')->map(function ($items, $tanggal) {
            return [
                'tanggal' => $tanggal,
                'items'   => $items->sortByDesc('created_at')->values(),
            ];
        })->values();

        return response()->json([
            'success'       => true,
            'product'       => $product,
            'history'       => $grouped,
            'total_history' => $allHistory->count(),
        ]);
    }

    // ===== API: TAMBAH BARANG MASUK =====
    public function storeBarangMasuk(Request $request)
    {
        $request->validate([
            'nama_produk'    => 'required|string|max:255',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_masuk'  => 'required|date',
            'harga_beli'     => 'nullable|numeric|min:0',
            'harga_jual'     => 'nullable|numeric|min:0',
            'supplier'       => 'nullable|string|max:255',
            'serial_numbers' => 'nullable|string',
            'brand'          => 'nullable|string|max:255',
            'category'       => 'nullable|string|max:255',
            'sku'            => 'nullable|string|max:100',
            'catatan'        => 'nullable|string',
        ]);

        // Cari atau buat produk
        $product = DB::table('gudang_products')
            ->where('nama_produk', $request->nama_produk)
            ->first();

        if (!$product) {
            $productId = DB::table('gudang_products')->insertGetId([
                'nama_produk' => $request->nama_produk,
                'brand'       => $request->brand,
                'category'    => $request->category,
                'sku'         => $request->sku,
                'harga_jual'  => $request->harga_jual ?? 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } else {
            $productId = $product->id;
            DB::table('gudang_products')->where('id', $productId)->update([
                'brand'      => $request->brand ?? $product->brand,
                'category'   => $request->category ?? $product->category,
                'harga_jual' => $request->harga_jual ?? $product->harga_jual,
                'updated_at' => now(),
            ]);
        }

        // Proses serial numbers
        $serialNumbers = [];
        if ($request->serial_numbers) {
            $serialNumbers = array_values(array_filter(
                array_map('trim', explode("\n", $request->serial_numbers))
            ));
        }

        $jumlah   = $request->jumlah;
        $inserted = 0;

        if (!empty($serialNumbers)) {
            foreach ($serialNumbers as $sn) {
                DB::table('barang_masuk')->insert([
                    'product_id'    => $productId,
                    'serial_number' => $sn,
                    'jumlah'        => 1,
                    'harga_beli'    => $request->harga_beli ?? 0,
                    'supplier'      => $request->supplier,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'status'        => 'tersedia',
                    'catatan'       => $request->catatan,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $inserted++;
            }
            $jumlah = $inserted;
        } else {
            DB::table('barang_masuk')->insert([
                'product_id'    => $productId,
                'serial_number' => null,
                'jumlah'        => $jumlah,
                'harga_beli'    => $request->harga_beli ?? 0,
                'supplier'      => $request->supplier,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status'        => 'tersedia',
                'catatan'       => $request->catatan,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            $inserted = $jumlah;
        }

        $this->recalculateStock($productId);

        return response()->json([
            'success' => true,
            'message' => "$inserted unit barang berhasil ditambahkan",
        ]);
    }

    // ===== API: TAMBAH BARANG KELUAR =====
    public function storeBarangKeluar(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|integer|exists:gudang_products,id',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan'     => 'required|in:terjual,rusak,retur',
            'harga_jual'     => 'nullable|numeric|min:0',
            'penerima'       => 'nullable|string|max:255',
            'catatan'        => 'nullable|string',
        ]);

        // Cek stok cukup
        $product = DB::table('gudang_products')->where('id', $request->product_id)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        if ($request->jumlah > $product->sisa_stok) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup! Sisa stok: {$product->sisa_stok} unit"
            ], 422);
        }

        DB::table('barang_keluar')->insert([
            'product_id'     => $request->product_id,
            'jumlah'         => $request->jumlah,
            'keterangan'     => $request->keterangan,
            'harga_jual'     => $request->harga_jual ?? 0,
            'penerima'       => $request->penerima,
            'tanggal_keluar' => $request->tanggal_keluar,
            'catatan'        => $request->catatan,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->recalculateStock($request->product_id);

        return response()->json([
            'success' => true,
            'message' => "{$request->jumlah} unit barang keluar ({$request->keterangan}) berhasil dicatat",
        ]);
    }

    // ===== API: UPDATE STATUS BARANG MASUK =====
    public function updateStatus(Request $request, $id)
    {
        $item = DB::table('barang_masuk')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::table('barang_masuk')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        $this->recalculateStock($item->product_id);

        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
    }

    // ===== API: HAPUS BARANG MASUK =====
    public function destroyBarangMasuk($id)
    {
        $item = DB::table('barang_masuk')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::table('barang_masuk')->where('id', $id)->delete();
        $this->recalculateStock($item->product_id);

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    // ===== API: HAPUS BARANG KELUAR =====
    public function destroyBarangKeluar($id)
    {
        $item = DB::table('barang_keluar')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::table('barang_keluar')->where('id', $id)->delete();
        $this->recalculateStock($item->product_id);

        return response()->json(['success' => true, 'message' => 'Data keluar dihapus, stok dikembalikan']);
    }

    // ===== HELPER: Recalculate stok =====
    private function recalculateStock($productId)
    {
        $totalMasuk = DB::table('barang_masuk')
            ->where('product_id', $productId)
            ->sum('jumlah');

        $totalKeluar = DB::table('barang_keluar')
            ->where('product_id', $productId)
            ->sum('jumlah');

        DB::table('gudang_products')->where('id', $productId)->update([
            'total_masuk'  => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'sisa_stok'    => $totalMasuk - $totalKeluar,
            'updated_at'   => now(),
        ]);
    }

    // ===== API: GET KATEGORI =====
    public function getCategories()
    {
        $categories = DB::table('gudang_products')
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return response()->json(['success' => true, 'categories' => $categories]);
    }
}