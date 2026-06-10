<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModalPaket;
use App\Models\ModalPaketItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ModalPaketController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $pakets = ModalPaket::with('items')->get();

        $totalModal  = $pakets->sum('total_modal');
        $jumlahPaket = $pakets->count();
        $rataRata    = $jumlahPaket > 0 ? (int) ($totalModal / $jumlahPaket) : 0;

        if ($request->ajax()) {
            return response()->json([
                'totalModal'  => $totalModal,
                'jumlahPaket' => $jumlahPaket,
                'rataRata'    => $rataRata,
                'pakets'      => $pakets->map(fn($p) => [
                    'id'          => $p->id,
                    'nama'        => $p->nama,
                    'total_modal' => $p->total_modal,
                    'item_count'  => $p->items->count(),
                ]),
            ]);
        }

        return view('admin.modal-paket.index', compact(
            'pakets',
            'totalModal',
            'jumlahPaket',
            'rataRata'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'items'             => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.harga_beli'=> 'required|integer|min:0',
            'items.*.diskon'    => 'nullable|numeric|min:0|max:100',
        ]);

        $paket = ModalPaket::create(['nama' => $request->nama]);

        foreach ($request->items as $index => $item) {
            ModalPaketItem::create([
                'modal_paket_id' => $paket->id,
                'nama_item'      => $item['nama_item'],
                'qty'            => (int) $item['qty'],
                'harga_beli'     => (int) $item['harga_beli'],
                'diskon'         => (float) ($item['diskon'] ?? 0),
                'urutan'         => $index,
            ]);
        }

        $paket->load('items');

        return response()->json([
            'success' => true,
            'paket'   => [
                'id'          => $paket->id,
                'nama'        => $paket->nama,
                'total_modal' => $paket->total_modal,
                'item_count'  => $paket->items->count(),
            ],
        ]);
    }

    public function show(ModalPaket $modalPaket): JsonResponse
    {
        $modalPaket->load('items');

        return response()->json([
            'id'    => $modalPaket->id,
            'nama'  => $modalPaket->nama,
            'items' => $modalPaket->items->map(fn($item) => [
                'id'          => $item->id,
                'nama_item'   => $item->nama_item,
                'qty'         => $item->qty,
                'harga_beli'  => $item->harga_beli,
                'diskon'      => $item->diskon,
                'urutan'      => $item->urutan,
            ]),
        ]);
    }

    public function update(Request $request, ModalPaket $modalPaket): JsonResponse
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'items'             => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.harga_beli'=> 'required|integer|min:0',
            'items.*.diskon'    => 'nullable|numeric|min:0|max:100',
        ]);

        $modalPaket->update(['nama' => $request->nama]);

        // Hapus semua item lama, insert ulang
        $modalPaket->items()->delete();

        foreach ($request->items as $index => $item) {
            ModalPaketItem::create([
                'modal_paket_id' => $modalPaket->id,
                'nama_item'      => $item['nama_item'],
                'qty'            => (int) $item['qty'],
                'harga_beli'     => (int) $item['harga_beli'],
                'diskon'         => (float) ($item['diskon'] ?? 0),
                'urutan'         => $index,
            ]);
        }

        $modalPaket->load('items');

        return response()->json([
            'success'     => true,
            'total_modal' => $modalPaket->total_modal,
        ]);
    }

    public function destroy(ModalPaket $modalPaket): JsonResponse
    {
        $modalPaket->delete(); // items terhapus otomatis via cascade

        return response()->json(['success' => true]);
    }
}