<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    // GET /notifications
    public function index(): JsonResponse
    {
        try {
            $notifs = Notification::belumDibaca()
                ->latest()
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'tipe'       => $n->tipe,
                    'judul'      => $n->judul,
                    'pesan'      => $n->pesan,
                    'invoice_id' => $n->invoice_id,
                    'no_invoice' => '-', // Kita matikan relasi invoice yang bikin server crash
                    'warna'      => $n->warnaLabel(),
                    'icon'       => $n->icon(),
                    'waktu'      => $n->created_at->diffForHumans(),
                ]);

            return response()->json([
                'count'  => $notifs->count(),
                'data'   => $notifs,
            ]);
        } catch (\Exception $e) {
            // Kalau backend error, kirim pesan errornya sebagai JSON, BUKAN HTML!
            return response()->json([
                'count' => 0, 
                'data'  => [], 
                'error_backend' => $e->getMessage()
            ]);
        }
    }

    // PATCH /notifications/{id}/read
    public function markRead($id): JsonResponse
    {
        // Ubah pencarian pakai ID manual biar nggak kena error Route Binding
        Notification::where('id', $id)->update(['dibaca' => true]);
        return response()->json(['ok' => true]);
    }

    // PATCH /notifications/read-all
    public function markAllRead(): JsonResponse
    {
        Notification::belumDibaca()->update(['dibaca' => true]);
        return response()->json(['ok' => true]);
    }
}