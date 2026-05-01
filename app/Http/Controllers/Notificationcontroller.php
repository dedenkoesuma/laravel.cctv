<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    // GET /notifications — ambil semua belum dibaca (untuk polling frontend)
    public function index(): JsonResponse
    {
        $notifs = Notification::belumDibaca()
            ->with('invoice')
            ->latest()
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'tipe'       => $n->tipe,
                'judul'      => $n->judul,
                'pesan'      => $n->pesan,
                'invoice_id' => $n->invoice_id,
                'no_invoice' => $n->invoice->no_invoice ?? '-',
                'warna'      => $n->warnaLabel(),
                'icon'       => $n->icon(),
                'waktu'      => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'count'  => $notifs->count(),
            'data'   => $notifs,
        ]);
    }

    // PATCH /notifications/{id}/read — tandai satu dibaca
    public function markRead(Notification $notification): JsonResponse
    {
        $notification->update(['dibaca' => true]);
        return response()->json(['ok' => true]);
    }

    // PATCH /notifications/read-all — tandai semua dibaca
    public function markAllRead(): JsonResponse
    {
        Notification::belumDibaca()->update(['dibaca' => true]);
        return response()->json(['ok' => true]);
    }
}