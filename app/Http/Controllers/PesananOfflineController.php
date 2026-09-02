<?php

namespace App\Http\Controllers;

use App\Models\PesananOffline;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesananOfflineController extends Controller
{
    public function index(Request $request)
    {
        $query = PesananOffline::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $pesanan = $query->paginate(10)->withQueryString();

        $summary = [
            'total'   => PesananOffline::count(),
            'proses'  => PesananOffline::byStatus('Proses')->count(),
            'selesai' => PesananOffline::byStatus('Selesai')->hariIni()->count(),
            'batal'   => PesananOffline::byStatus('Dibatalkan')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('pesanan-offline.index', compact('pesanan', 'summary'));
    }

    public function create()
    {
        $statuses   = PesananOffline::STATUSES;
        $tipeKertas = $this->listTipeKertas();
        $noOrder    = PesananOffline::generateNoOrder();

        return view('pesanan-offline.create', compact('statuses', 'tipeKertas', 'noOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan'     => 'required|string|max:100',
            'tipe_kertas'   => 'required|string|max:50',
            'jumlah_lembar' => 'required|integer|min:1',
            'total'         => 'required|integer|min:0',
            'status'        => ['nullable', Rule::in(PesananOffline::STATUSES)],
            'catatan'       => 'nullable|string|max:500',
        ]);

        $validated['no_order'] = PesananOffline::generateNoOrder();
        $validated['status']   = $validated['status'] ?? 'Proses';

        PesananOffline::create($validated);

        return redirect()
            ->route('pesanan-offline.index')
            ->with('success', 'Pesanan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pesanan = PesananOffline::findOrFail($id);

        return view('pesanan-offline.show', compact('pesanan'));
    }

    public function edit($id)
    {
        $pesanan    = PesananOffline::findOrFail($id);
        $statuses   = PesananOffline::STATUSES;
        $tipeKertas = $this->listTipeKertas();

        return view('pesanan-offline.edit', compact('pesanan', 'statuses', 'tipeKertas'));
    }

    public function update(Request $request, $id)
    {
        $pesanan = PesananOffline::findOrFail($id);

        $validated = $request->validate([
            'pelanggan'     => 'required|string|max:100',
            'tipe_kertas'   => 'required|string|max:50',
            'jumlah_lembar' => 'required|integer|min:1',
            'total'         => 'required|integer|min:0',
            'status'        => ['required', Rule::in(PesananOffline::STATUSES)],
            'catatan'       => 'nullable|string|max:500',
        ]);

        $pesanan->update($validated);

        return redirect()
            ->route('pesanan-offline.index')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function updateStatus($id)
    {
        $pesanan = PesananOffline::findOrFail($id);
        $pesanan->update(['status' => $pesanan->nextStatus()]);

        return back()->with('success', "Status diubah ke {$pesanan->status}.");
    }

    public function destroy($id)
    {
        $pesanan = PesananOffline::findOrFail($id);
        $pesanan->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    public function restore($id)
    {
        $pesanan = PesananOffline::onlyTrashed()->findOrFail($id);
        $pesanan->restore();

        return back()->with('success', 'Pesanan berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $pesanan = PesananOffline::onlyTrashed()->findOrFail($id);
        $pesanan->forceDelete();

        return back()->with('success', 'Pesanan dihapus permanen.');
    }

    public function exportCsv(Request $request)
    {
        $query = PesananOffline::latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $pesanan  = $query->get();
        $filename = 'pesanan-offline-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pesanan) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No. Order', 'Pelanggan', 'Tipe Kertas',
                'Jumlah Lembar', 'Total (Rp)', 'Status', 'Catatan', 'Tanggal',
            ]);

            foreach ($pesanan as $p) {
                fputcsv($file, [
                    $p->no_order,
                    $p->pelanggan,
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

    private function listTipeKertas(): array
    {
        return [
            'ART PAPER 120 1 SISI',
            'ART PAPER 150 1 SISI',
            'ART PAPER 120 2 SISI',
            'ART PAPER 150 2 SISI',
            'HVS 100 2 SISI',
            'ART CARTON 190 1 SISI',
            'ART CARTON 210 1 SISI',
            'ART CARTON 230 1 SISI',
            'ART CARTON 260 1 SISI',
            'ART CARTON 190 2 SISI',
            'ART CARTON 210 2 SISI',
            'ART CARTON 230 2 SISI',
            'ART CARTON 260 2 SISI',
            'ART CARTON 190 1 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 210 1 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 230 1 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 260 1 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 190 2 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 210 2 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 230 2 SISI LAMINASI DOFF/GLOSY',
            'ART CARTON 260 2 SISI LAMINASI DOFF/GLOSY',
            'HVS 100 1 SISI',
            'HVS 100 2 SISI',
            'HVS 80 1 SISI',
            'HVS 80 2 SISI',
        ];
    }
}