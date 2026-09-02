<?php

namespace App\Http\Controllers;

use App\Models\PesananOnline;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesananOnlineController extends Controller
{
    // ── Index ────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = PesananOnline::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('platform')) {
            $query->byPlatform($request->platform);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $pesanan  = $query->paginate(10)->withQueryString();

        $summary = [
            'total'    => PesananOnline::count(),
            'proses'   => PesananOnline::byStatus('Proses')->count(),
            'selesai'  => PesananOnline::byStatus('Selesai')->hariIni()->count(),
            'batal'    => PesananOnline::byStatus('Dibatalkan')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('pesanan-online.index', compact('pesanan', 'summary'));
    }

    // ── Create ───────────────────────────────────────────
    public function create()
{
    $platforms  = PesananOnline::PLATFORMS;
    $statuses   = PesananOnline::STATUSES;
    $tipeKertas = $this->listTipeKertas();
    $noOrder    = PesananOnline::generateNoOrder(); // tambah ini

    return view('pesanan-online.create', compact('platforms', 'statuses', 'tipeKertas', 'noOrder'));
}

    // ── Store ────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan'      => 'required|string|max:100',
            'platform'       => ['required', Rule::in(PesananOnline::PLATFORMS)],
            'tipe_kertas'    => 'required|string|max:50',
            'jumlah_lembar'  => 'required|integer|min:1',
            'total'          => 'required|integer|min:0',
            'status'         => ['nullable', Rule::in(PesananOnline::STATUSES)],
            'catatan'        => 'nullable|string|max:500',
        ]);

        $validated['no_order'] = PesananOnline::generateNoOrder();
        $validated['status']   = $validated['status'] ?? 'Proses';

        PesananOnline::create($validated);

        return redirect()
            ->route('pesanan-online.index')
            ->with('success', 'Pesanan berhasil ditambahkan.');
    }

    // ── Show ─────────────────────────────────────────────
    public function show($id)
    {
        $pesanan = PesananOnline::findOrFail($id);

        return view('pesanan-online.show', compact('pesanan'));
    }

    // ── Edit ─────────────────────────────────────────────
    public function edit($id)
    {
        $pesanan    = PesananOnline::findOrFail($id);
        $platforms  = PesananOnline::PLATFORMS;
        $statuses   = PesananOnline::STATUSES;
        $tipeKertas = $this->listTipeKertas();

        return view('pesanan-online.edit', compact('pesanan', 'platforms', 'statuses', 'tipeKertas'));
    }

    // ── Update ───────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $pesanan = PesananOnline::findOrFail($id);

        $validated = $request->validate([
            'pelanggan'      => 'required|string|max:100',
            'platform'       => ['required', Rule::in(PesananOnline::PLATFORMS)],
            'tipe_kertas'    => 'required|string|max:50',
            'jumlah_lembar'  => 'required|integer|min:1',
            'total'          => 'required|integer|min:0',
            'status'         => ['required', Rule::in(PesananOnline::STATUSES)],
            'catatan'        => 'nullable|string|max:500',
        ]);

        $pesanan->update($validated);

        return redirect()
            ->route('pesanan-online.index')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    // ── Update Status (toggle) ───────────────────────────
    public function updateStatus($id)
    {
        $pesanan = PesananOnline::findOrFail($id);
        $pesanan->update(['status' => $pesanan->nextStatus()]);

        return back()->with('success', "Status diubah ke {$pesanan->status}.");
    }

    // ── Destroy ──────────────────────────────────────────
    public function destroy($id)
    {
        $pesanan = PesananOnline::findOrFail($id);
        $pesanan->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    // ── Restore (dari soft delete) ───────────────────────
    public function restore($id)
    {
        $pesanan = PesananOnline::onlyTrashed()->findOrFail($id);
        $pesanan->restore();

        return back()->with('success', 'Pesanan berhasil dipulihkan.');
    }

    // ── Force Delete (hapus permanen) ────────────────────
    public function forceDelete($id)
    {
        $pesanan = PesananOnline::onlyTrashed()->findOrFail($id);
        $pesanan->forceDelete();

        return back()->with('success', 'Pesanan dihapus permanen.');
    }

    // ── Export CSV ───────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $query = PesananOnline::latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('platform')) {
            $query->byPlatform($request->platform);
        }

        $pesanan = $query->get();

        $filename = 'pesanan-online-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pesanan) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No. Order', 'Pelanggan', 'Platform',
                'Tipe Kertas', 'Jumlah Lembar', 'Total (Rp)',
                'Status', 'Catatan', 'Tanggal',
            ]);

            foreach ($pesanan as $p) {
                fputcsv($file, [
                    $p->no_order,
                    $p->pelanggan,
                    $p->platform,
                    $p->tipe_kertas,
                    $p->jumlah_lembar,
                    $p->total,
                    $p->status,
                    $p->catatan,
                    $p->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Private Helper ───────────────────────────────────
    private function listTipeKertas(): array
    {
        return [
            'A4 70gr BW',
            'A4 80gr BW',
            'A4 warna',
            'F4 70gr BW',
            'F4 80gr BW',
            'A3 80gr',
            'Foto glossy',
            'HVS warna',
        ];
    }
}