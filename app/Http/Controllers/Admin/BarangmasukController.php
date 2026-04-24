<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\GudangProduct;
use App\Models\InventorySerial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Ini adalah tambahan/override untuk BarangMasukController yang sudah ada.
 * Tambahkan method store() ini ke controller yang sudah ada,
 * atau replace seluruh store() method yang lama.
 */
class BarangMasukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:gudang_products,id',
            'qty'            => 'required|integer|min:1',
            'harga_beli'     => 'required|numeric|min:0',
            'supplier'       => 'nullable|string|max:255',
            'tanggal_masuk'  => 'required|date',
            'catatan'        => 'nullable|string',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*' => 'nullable|string|max:100',
        ]);

        $product = GudangProduct::findOrFail($request->product_id);

        // Validasi SN jika produk menggunakan SN
        if ($product->use_serial_number) {
            $request->validate([
                'serial_numbers' => "required|array|size:{$request->qty}",
                'serial_numbers.*' => 'required|string|max:100',
            ]);

            // Cek duplikat SN dalam request
            $snInput = array_filter($request->serial_numbers);
            if (count(array_unique($snInput)) !== count($snInput)) {
                return back()->withInput()->with('error', 'Terdapat duplikat Serial Number dalam input.');
            }

            // Cek SN sudah ada di database
            foreach ($snInput as $sn) {
                $exists = InventorySerial::where('product_id', $request->product_id)
                    ->where('serial_number', $sn)
                    ->exists();
                if ($exists) {
                    return back()->withInput()->with('error', "Serial Number '{$sn}' sudah terdaftar di sistem.");
                }
            }
        }

        DB::beginTransaction();
        try {
            // Simpan barang masuk
            $barangMasuk = BarangMasuk::create([
                'product_id'    => $request->product_id,
                'qty'           => $request->qty,
                'harga_beli'    => $request->harga_beli,
                'supplier'      => $request->supplier,
                'tanggal_masuk' => $request->tanggal_masuk,
                'catatan'       => $request->catatan,
                'status'        => 'tersedia',
            ]);

            // Tambah stok produk
            $product->increment('stok', $request->qty);

            // Simpan SN jika diperlukan
            if ($product->use_serial_number && $request->serial_numbers) {
                foreach (array_filter($request->serial_numbers) as $sn) {
                    InventorySerial::create([
                        'product_id'     => $request->product_id,
                        'serial_number'  => trim($sn),
                        'status'         => 'available',
                        'barang_masuk_id' => $barangMasuk->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.gudang.index')
                ->with('success', "Barang masuk berhasil dicatat. Stok bertambah {$request->qty} unit.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * API: Cek apakah produk menggunakan SN (AJAX)
     */
    public function checkUseSn(Request $request)
    {
        $product = GudangProduct::find($request->product_id);
        return response()->json([
            'use_serial_number' => $product ? $product->use_serial_number : false,
        ]);
    }
}