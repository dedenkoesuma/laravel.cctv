<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KalkulatorModalController extends Controller
{
    public function index()
    {
        return view('admin.modal.kalkulator_modal');
    }

   public function getPaketList()
{
    $pakets = DB::table('modal_pakets')
        ->select('id', 'nama', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'pakets'  => $pakets
    ]);
}

public function getTotalModalByPaket($id)
{
    $paket = DB::table('modal_pakets')->where('id', $id)->first();
    if (!$paket) {
        return response()->json(['success' => false, 'message' => 'Paket tidak ditemukan'], 404);
    }

    $total = DB::table('modal_paket_items')
        ->where('modal_paket_id', $id)
        ->selectRaw('SUM(qty * harga_beli * (1 - diskon / 100)) as total')
        ->value('total') ?? 0;

    return response()->json([
        'success'     => true,
        'id'          => $paket->id,
        'nama_paket'  => $paket->nama,
        'total_modal' => (float) $total
    ]);
}
}