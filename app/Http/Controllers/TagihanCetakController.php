<?php

namespace App\Http\Controllers;

use App\Models\TagihanCetak;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagihanCetakController extends Controller
{
    public function index(Request $request)
    {
        $query = TagihanCetak::latest('tgl_tagihan');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $invoices = $query->paginate(10)->withQueryString();

        $summary = [
            'total'   => TagihanCetak::count(),
            'unpaid'  => TagihanCetak::byStatus('unpaid')->count(),
            'lunas'   => TagihanCetak::byStatus('lunas')->count(),
            'overdue' => TagihanCetak::overdue()->count(),
        ];

        return view('invoice.index', compact('invoices', 'summary'));
    }

    public function create()
    {
        $statuses  = TagihanCetak::STATUSES;
        $noInvoice = TagihanCetak::generateNoTagihan();

        return view('invoice.create', compact('statuses', 'noInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan'   => 'required|string|max:150',
            'deskripsi'   => 'nullable|string|max:500',
            'tgl_tagihan' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tgl_tagihan',
            'total'       => 'required|integer|min:0',
            'status'      => ['nullable', Rule::in(TagihanCetak::STATUSES)],
            'catatan'     => 'nullable|string|max:500',
        ]);

        $validated['no_tagihan'] = TagihanCetak::generateNoTagihan();
        $validated['status']     = $validated['status'] ?? 'unpaid';

        TagihanCetak::create($validated);

        return redirect()
            ->route('invoice.index')
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function edit($id)
    {
        $invoice  = TagihanCetak::findOrFail($id);
        $statuses = TagihanCetak::STATUSES;

        return view('invoice.edit', compact('invoice', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $invoice = TagihanCetak::findOrFail($id);

        $validated = $request->validate([
            'pelanggan'   => 'required|string|max:150',
            'deskripsi'   => 'nullable|string|max:500',
            'tgl_tagihan' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tgl_tagihan',
            'total'       => 'required|integer|min:0',
            'status'      => ['required', Rule::in(TagihanCetak::STATUSES)],
            'catatan'     => 'nullable|string|max:500',
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoice.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    public function markLunas($id)
    {
        $invoice = TagihanCetak::findOrFail($id);
        $invoice->update(['status' => 'lunas']);

        return back()->with('success', "Invoice {$invoice->no_tagihan} ditandai lunas.");
    }

    public function destroy($id)
    {
        $invoice = TagihanCetak::findOrFail($id);
        $invoice->delete();

        return back()->with('success', 'Invoice berhasil dihapus.');
    }
}