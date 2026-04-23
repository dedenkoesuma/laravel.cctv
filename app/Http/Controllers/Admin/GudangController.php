<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GudangController extends Controller
{
    public function index()
    {
        return view('admin.gudang');
    }

    public function getProducts(Request $request)
    {
        try {
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

            if ($request->stok === 'habis') {
                $query->where('sisa_stok', '<=', 0);
            } elseif ($request->stok === 'menipis') {
                $query->where('sisa_stok', '>', 0)->where('sisa_stok', '<=', 5);
            } elseif ($request->stok === 'tersedia') {
                $query->where('sisa_stok', '>', 5);
            }

            $products = $query->orderBy('nama_produk')->get();

            $products = $products->map(function($p) {
                $useSn = DB::table('barang_masuk')
                            ->where('product_id', $p->id)
                            ->whereNotNull('serial_number')
                            ->where('serial_number', '!=', '')
                            ->exists();
                $p->use_serial_number = $useSn;
                return $p;
            });

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
        } catch (\Exception $e) {
            Log::error("Gudang Error (getProducts): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat data produk'], 500);
        }
    }

    public function getHistory($productId)
    {
        $product = DB::table('gudang_products')->where('id', $productId)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $masuk = DB::table('barang_masuk')
            ->where('product_id', $productId)
            ->get()
            ->map(function($item) {
                $item->tipe    = 'masuk';
                $item->tanggal = $item->tanggal_masuk;
                return $item;
            });

        $keluar = DB::table('barang_keluar')
            ->where('product_id', $productId)
            ->get()
            ->map(function($item) {
                $item->tipe    = 'keluar';
                $item->tanggal = $item->tanggal_keluar;
                return $item;
            });

        $allHistory = $masuk->concat($keluar)->sortByDesc('tanggal')->values();

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

    public function storeBarangMasuk(Request $request)
    {
        $request->validate([
            'nama_produk'    => 'required|string|max:255',
            'jumlah'         => 'required|integer|min:1',
            'tanggal_masuk'  => 'required|date',
            'harga_beli'     => 'nullable|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            $product = DB::table('gudang_products')->where('nama_produk', $request->nama_produk)->first();
            
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
                    'updated_at' => now(),
                ]);
            }

            $serialNumbers = [];
            if ($request->serial_numbers) {
                $serialNumbers = is_array($request->serial_numbers) 
                    ? array_filter(array_map('trim', $request->serial_numbers))
                    : array_filter(array_map('trim', explode("\n", $request->serial_numbers)));
            }

            if (!empty($serialNumbers)) {
                foreach ($serialNumbers as $sn) {
                    if (DB::table('barang_masuk')->where('product_id', $productId)->where('serial_number', $sn)->exists()) {
                        return response()->json(['success' => false, 'message' => "SN $sn sudah ada!"], 422);
                    }
                    DB::table('barang_masuk')->insert([
                        'product_id'    => $productId,
                        'serial_number' => $sn,
                        'jumlah'        => 1,
                        'harga_beli'    => $request->harga_beli ?? 0,
                        'tanggal_masuk' => $request->tanggal_masuk,
                        'status'        => 'tersedia',
                        'created_at'    => now(),
                    ]);
                }
            } else {
                DB::table('barang_masuk')->insert([
                    'product_id'    => $productId,
                    'jumlah'        => $request->jumlah,
                    'harga_beli'    => $request->harga_beli ?? 0,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'status'        => 'tersedia',
                    'created_at'    => now(),
                ]);
            }

            $this->recalculateStock($productId);
            return response()->json(['success' => true, 'message' => 'Barang masuk berhasil dicatat']);
        });
    }

    public function storeBarangKeluar(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:gudang_products,id',
            'jumlah'         => 'required|integer|min:1',
            'keterangan'     => 'required|in:terjual,rusak,retur',
            'tanggal_keluar' => 'required|date',
            'serial_ids'     => 'nullable|array',
            'penerima'       => 'nullable|string',
            'harga_jual'     => 'nullable|numeric',
            'catatan'        => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $product = DB::table('gudang_products')->where('id', $request->product_id)->lockForUpdate()->first();

            if ($request->jumlah > $product->sisa_stok) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi'], 422);
            }

            if ($request->serial_ids) {
                foreach ($request->serial_ids as $snId) {
                    $snItem = DB::table('barang_masuk')->where('id', $snId)->first();
                    DB::table('barang_keluar')->insert([
                        'product_id'     => $request->product_id,
                        'jumlah'         => 1,
                        'serial_number'  => $snItem->serial_number,
                        'keterangan'     => $request->keterangan,
                        'tanggal_keluar' => $request->tanggal_keluar,
                        'penerima'       => $request->penerima,
                        'harga_jual'     => $request->harga_jual ?? 0,
                        'catatan'        => $request->catatan,
                        'created_at'     => now(),
                    ]);
                    DB::table('barang_masuk')->where('id', $snId)->update(['status' => 'terjual']);
                }
            } else {
                DB::table('barang_keluar')->insert([
                    'product_id'     => $request->product_id,
                    'jumlah'         => $request->jumlah,
                    'keterangan'     => $request->keterangan,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'penerima'       => $request->penerima,
                    'harga_jual'     => $request->harga_jual ?? 0,
                    'catatan'        => $request->catatan,
                    'created_at'     => now(),
                ]);
            }

            $this->recalculateStock($request->product_id);
            return response()->json(['success' => true, 'message' => 'Barang keluar berhasil dicatat']);
        });
    }

    private function recalculateStock($productId)
    {
        $totalMasuk = DB::table('barang_masuk')->where('product_id', $productId)->sum('jumlah');
        $totalKeluar = DB::table('barang_keluar')->where('product_id', $productId)->sum('jumlah');

        DB::table('gudang_products')->where('id', $productId)->update([
            'total_masuk'  => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'sisa_stok'    => $totalMasuk - $totalKeluar,
            'updated_at'   => now(),
        ]);
    }

    public function getCategories()
    {
        $categories = DB::table('gudang_products')->whereNotNull('category')->distinct()->pluck('category');
        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function destroyProduct($id)
    {
        $product = DB::table('gudang_products')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        DB::table('gudang_products')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Produk dan semua riwayatnya berhasil dihapus']);
    }

    public function destroyBarangMasuk($id) {
        $item = DB::table('barang_masuk')->where('id', $id)->first();
        if ($item) {
            DB::table('barang_masuk')->where('id', $id)->delete();
            $this->recalculateStock($item->product_id);
        }
        return response()->json(['success' => true]);
    }

    public function destroyBarangKeluar($id) {
        $item = DB::table('barang_keluar')->where('id', $id)->first();
        if ($item) {
            if ($item->serial_number) {
                DB::table('barang_masuk')
                    ->where('product_id', $item->product_id)
                    ->where('serial_number', $item->serial_number)
                    ->update(['status' => 'tersedia']);
            }
            DB::table('barang_keluar')->where('id', $id)->delete();
            $this->recalculateStock($item->product_id);
        }
        return response()->json(['success' => true]);
    }

    public function productUseSerialNumber(Request $request)
    {
        $product = DB::table('gudang_products')->where('nama_produk', $request->nama_produk)->first();
        if (!$product) return response()->json(['use_serial_number' => false]);
        $hasSN = DB::table('barang_masuk')->where('product_id', $product->id)->whereNotNull('serial_number')->exists(); 
        return response()->json(['use_serial_number' => $hasSN]);
    }

    public function getAvailableSerials(Request $request)
    {
        $serials = DB::table('barang_masuk')
            ->where('product_id', $request->product_id)
            ->where('status', 'tersedia')
            ->whereNotNull('serial_number')
            ->select('id', 'serial_number')
            ->get();
        return response()->json($serials);
    }
}