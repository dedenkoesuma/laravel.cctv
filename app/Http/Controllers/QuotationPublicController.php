<?php
// =====================================================
// FILE: app/Http/Controllers/QuotationPublicController.php
// Controller ini untuk halaman yang dibuka CUSTOMER
// (tidak butuh login admin)
// =====================================================
namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationPublicController extends Controller
{
    // ===== TAMPILKAN PENAWARAN KE CUSTOMER =====
    public function show($token)
    {
        $quo = Quotation::with('items')
            ->where('token', $token)
            ->firstOrFail();

        // Auto expire jika sudah lewat tanggal berlaku
        if ($quo->is_expired && $quo->status === 'sent') {
            $quo->update(['status' => 'expired']);
            $quo->status = 'expired';
        }

        return view('quotation.show', compact('quo'));
    }

    // ===== CUSTOMER RESPOND (Setuju / Tolak / Minta Revisi) =====
    public function respond(Request $request, $token)
    {
        $request->validate([
            'action' => 'required|in:approved,rejected,revised',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $quo = Quotation::where('token', $token)->firstOrFail();

        if (!in_array($quo->status, ['sent', 'draft'])) {
            return response()->json([
                'success' => false,
                'message' => 'Penawaran ini sudah tidak dapat diubah statusnya.',
            ]);
        }

        if ($quo->is_expired) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, penawaran ini sudah kadaluarsa.',
            ]);
        }

        $quo->update([
            'status'         => $request->action,
            'customer_notes' => $request->notes,
            'responded_at'   => now(),
        ]);

        $labelMap = [
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'revised'  => 'diminta revisi',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Penawaran berhasil ' . ($labelMap[$request->action]) . '. Terima kasih!',
            'status'  => $request->action,
        ]);
    }
}