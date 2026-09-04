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
        $noOrder    = PesananOnline::generateNoOrder();

        return view('pesanan-online.create', compact('platforms', 'statuses', 'tipeKertas', 'noOrder'));
    }

    // ── Store ────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan'          => 'required|string|max:100',
            'platform'           => ['required', Rule::in(PesananOnline::PLATFORMS)],
            'items'              => 'required|array|min:1',
            'items.*.jumlah'     => 'nullable|integer|min:1',
            'total'              => 'required|integer|min:0',
            'jasa_potong'        => 'nullable|boolean',
            'status'             => ['nullable', Rule::in(PesananOnline::STATUSES)],
            'catatan'            => 'nullable|string|max:500',
        ]);

        [$tipeKertas, $totalLembar] = $this->buildTipeKertas($request->input('items', []));

        if (empty($tipeKertas)) {
            return back()
                ->withErrors(['tipe_kertas' => 'Pilih minimal satu tipe kertas beserta jumlahnya.'])
                ->withInput();
        }

        PesananOnline::create([
            'no_order'      => PesananOnline::generateNoOrder(),
            'pelanggan'     => $validated['pelanggan'],
            'platform'      => $validated['platform'],
            'tipe_kertas'   => $tipeKertas,
            'jumlah_lembar' => $totalLembar,
            'total'         => $validated['total'],
            'jasa_potong'   => $request->boolean('jasa_potong'),
            'status'        => $validated['status'] ?? 'Proses',
            'catatan'       => $validated['catatan'] ?? null,
        ]);

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
            'pelanggan'          => 'required|string|max:100',
            'platform'           => ['required', Rule::in(PesananOnline::PLATFORMS)],
            'items'              => 'required|array|min:1',
            'items.*.jumlah'     => 'nullable|integer|min:1',
            'total'              => 'required|integer|min:0',
            'jasa_potong'        => 'nullable|boolean',
            'status'             => ['required', Rule::in(PesananOnline::STATUSES)],
            'catatan'            => 'nullable|string|max:500',
        ]);

        [$tipeKertas, $totalLembar] = $this->buildTipeKertas($request->input('items', []));

        if (empty($tipeKertas)) {
            return back()
                ->withErrors(['tipe_kertas' => 'Pilih minimal satu tipe kertas beserta jumlahnya.'])
                ->withInput();
        }

        $pesanan->update([
            'pelanggan'     => $validated['pelanggan'],
            'platform'      => $validated['platform'],
            'tipe_kertas'   => $tipeKertas,
            'jumlah_lembar' => $totalLembar,
            'total'         => $validated['total'],
            'jasa_potong'   => $request->boolean('jasa_potong'),
            'status'        => $validated['status'],
            'catatan'       => $validated['catatan'] ?? null,
        ]);

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
                'Jasa Potong', 'Status', 'Catatan', 'Tanggal',
            ]);

            foreach ($pesanan as $p) {
                fputcsv($file, [
                    $p->no_order,
                    $p->pelanggan,
                    $p->platform,
                    $p->tipe_kertas_text,
                    $p->jumlah_lembar,
                    $p->total,
                    $p->jasa_potong ? 'Ya' : 'Tidak',
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
    private function buildTipeKertas(array $items): array
    {
        $tipeKertas  = [];
        $totalLembar = 0;

        foreach ($items as $tipe => $data) {
            $dicentang = !empty($data['pilih']);
            $jumlah    = (int) ($data['jumlah'] ?? 0);

            if ($dicentang && $jumlah > 0) {
                $tipeKertas[]  = ['tipe' => $tipe, 'jumlah' => $jumlah];
                $totalLembar  += $jumlah;
            }
        }

        return [$tipeKertas, $totalLembar];
    }

    private function listTipeKertas(): array
    {
        return [
            'ART PAPER 120 1 SISI', 
            'ART PAPER 150 1 SISI',
            'ART PAPER 120 2 SISI',
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