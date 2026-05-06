<?php

// =====================================================
// FILE: app/Http/Controllers/Admin/QuotationController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // ✅ TAMBAHAN WAJIB

class QuotationController extends Controller
{
    // ===== HELPER: Ambil admin_id Cerdas (Anti Error) =====
    private function adminId(): int
    {
        // 1. Coba ambil dari session (sistem bawaanmu yang lama)
        $adminId = session('admin_id');
        if ($adminId && is_numeric($adminId)) {
            return (int) $adminId;
        }

        // 2. Jika tidak ada di session, ambil dari Auth Laravel
        if (Auth::check()) {
            $user = Auth::user();
            $id = $user->id;
            
            // Jika ID-nya sudah angka, langsung pakai
            if (is_numeric($id)) return (int) $id;
            
            // Jika ID-nya ternyata Email, cari ID aslinya di tabel admins
            $admin = DB::table('admins')->where('email', $id)->orWhere('username', $id)->first();
            if ($admin) return (int) $admin->id;
        }

        // Fallback darurat agar database tidak crash
        return 1; 
    }

    // ===== LIST =====
    public function index()
    {
        $quotations = Quotation::with('creator')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($q) {
                if ($q->is_expired && $q->status === 'sent') {
                    $q->update(['status' => 'expired']);
                    $q->status = 'expired';
                }
                return $q;
            });

        return view('admin.quotation.index', compact('quotations'));
    }

    // ===== FORM BUAT =====
    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('admin.quotation.create', compact('products'));
    }

    // ===== SIMPAN =====
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'        => 'required|string|max:255',
            'customer_phone'       => 'nullable|string|max:20',
            'customer_email'       => 'nullable|email',
            'quo_date'             => 'required|date',
            'valid_until'          => 'required|date|after:quo_date',
            'items'                => 'required|array|min:1',
            'items.*.nama_item'    => 'required|string',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        $quoId = null;

        DB::transaction(function () use ($request, &$quoId) {
            $quo = Quotation::create([
                'quo_number'       => Quotation::generateNumber(),
                'token'            => Quotation::generateToken(),
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_email'   => $request->customer_email,
                'customer_address' => $request->customer_address,
                'project_name'     => $request->project_name,
                'quo_date'         => $request->quo_date,
                'valid_until'      => $request->valid_until,
                'status'           => 'draft',
                'ppn_enabled'      => $request->boolean('ppn_enabled'),
                'ppn_rate'         => $request->ppn_rate ?? 11,
                'discount_global'  => $request->discount_global ?? 0,
                'notes'            => $request->notes,
                'terms'            => $request->terms ?? $this->defaultTerms(),
                'created_by'       => $this->adminId(), // ✅ SUDAH MENGGUNAKAN HELPER CERDAS
            ]);

            foreach ($request->items as $i => $itemData) {
                $item = new QuotationItem([
                    'quotation_id' => $quo->id,
                    'nama_item'    => $itemData['nama_item'],
                    'deskripsi'    => $itemData['deskripsi'] ?? null,
                    'qty'          => $itemData['qty'],
                    'satuan'       => $itemData['satuan'] ?? 'unit',
                    'harga_satuan' => $itemData['harga_satuan'],
                    'discount'     => $itemData['discount'] ?? 0,
                    'urutan'       => $i,
                ]);
                $item->hitungSubtotal();
                $item->save();
            }

            $quo->load('items');
            $quo->recalculate();
            $quo->save();

            $quoId = $quo->id;
        });

        return response()->json([
            'success' => true,
            'message' => 'Penawaran berhasil dibuat!',
            'id'      => $quoId,
        ]);
    }

    // ===== DETAIL =====
    public function show($id)
    {
        $quo = Quotation::with(['items', 'creator', 'salesOrder'])->findOrFail($id);
        return view('admin.quotation.show', compact('quo'));
    }

    // ===== EDIT =====
    public function edit($id)
    {
        $quo      = Quotation::with('items')->findOrFail($id);
        $products = Product::orderBy('nama_produk')->get();
        return view('admin.quotation.create', compact('quo', 'products'));
    }

    // ===== UPDATE =====
    public function update(Request $request, $id)
    {
        $quo = Quotation::findOrFail($id);

        DB::transaction(function () use ($request, $quo) {
            $quo->update([
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_email'   => $request->customer_email,
                'customer_address' => $request->customer_address,
                'project_name'     => $request->project_name,
                'quo_date'         => $request->quo_date,
                'valid_until'      => $request->valid_until,
                'ppn_enabled'      => $request->boolean('ppn_enabled'),
                'ppn_rate'         => $request->ppn_rate ?? 11,
                'discount_global'  => $request->discount_global ?? 0,
                'notes'            => $request->notes,
                'terms'            => $request->terms,
                // ✅ Reset status setelah admin edit/revisi
                'status'           => 'draft',
                'responded_at'     => null,
                'customer_notes'   => null,
            ]);

            $quo->items()->delete();

            foreach ($request->items as $i => $itemData) {
                $item = new QuotationItem([
                    'quotation_id' => $quo->id,
                    'nama_item'    => $itemData['nama_item'],
                    'deskripsi'    => $itemData['deskripsi'] ?? null,
                    'qty'          => $itemData['qty'],
                    'satuan'       => $itemData['satuan'] ?? 'unit',
                    'harga_satuan' => $itemData['harga_satuan'],
                    'discount'     => $itemData['discount'] ?? 0,
                    'urutan'       => $i,
                ]);
                $item->hitungSubtotal();
                $item->save();
            }

            $quo->load('items');
            $quo->recalculate();
            $quo->save();
        });

        return response()->json(['success' => true, 'message' => 'Penawaran berhasil diupdate!']);
    }

    // ===== KIRIM (ubah status -> sent) =====
    public function send($id)
    {
        $quo = Quotation::findOrFail($id);
        $quo->update(['status' => 'sent', 'sent_at' => now()]);

        return response()->json([
            'success'      => true,
            'message'      => 'Status diubah ke Terkirim!',
            'whatsapp_url' => $quo->whatsapp_url,
            'public_url'   => $quo->public_url,
        ]);
    }

    // ===== KONVERSI KE SALES ORDER =====
    public function convertToSO($id)
    {
        $quo = Quotation::with('items')->findOrFail($id);

        if ($quo->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Penawaran harus Disetujui terlebih dahulu.']);
        }
        if ($quo->sales_order_id) {
            return response()->json(['success' => false, 'message' => 'Sudah pernah dikonversi ke SO.']);
        }

        $soNumber = null;

        DB::transaction(function () use ($quo, &$soNumber) {
            $so = SalesOrder::create([
                'so_number'        => SalesOrder::generateSoNumber(),
                'customer_name'    => $quo->customer_name,
                'customer_phone'   => $quo->customer_phone,
                'customer_email'   => $quo->customer_email,
                'customer_address' => $quo->customer_address,
                'so_date'          => now()->toDateString(),
                'status'           => 'draft',
                'notes'            => "Dari Penawaran {$quo->quo_number}.",
                'total_amount'     => $quo->total_amount,
                'created_by'       => $this->adminId(), // ✅ SUDAH MENGGUNAKAN HELPER CERDAS
            ]);

            foreach ($quo->items as $item) {
                // ✅ Cari product_id berdasarkan nama_item di gudang_products
                $product = DB::table('gudang_products')
                    ->where('nama_produk', $item->nama_item)
                    ->first();

                SalesOrderItem::create([
                    'sales_order_id' => $so->id,
                    'product_id'     => $product ? $product->id : null, // ✅ null jika tidak ditemukan, bukan 0
                    'qty'            => $item->qty,
                    'harga_satuan'   => $item->harga_satuan,
                    'subtotal'       => $item->subtotal,
                    'notes'          => $item->nama_item, // ✅ simpan nama item di notes sebagai referensi
                ]);
            }

            $quo->update(['status' => 'converted', 'sales_order_id' => $so->id]);
            $soNumber = $so->so_number;
        });

        return response()->json([
            'success' => true,
            'message' => "Berhasil dikonversi ke Sales Order {$soNumber}!",
        ]);
    }

    // ===== HAPUS =====
    public function destroy($id)
    {
        Quotation::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Penawaran dihapus!']);
    }

    // ===== PDF =====
    public function pdf($id)
    {
        $quo = Quotation::with('items')->findOrFail($id);
        return view('admin.quotation.pdf', compact('quo'));
    }

    private function defaultTerms(): string
    {
        return "1. Penawaran berlaku sesuai tanggal yang tertera.\n" .
               "2. Harga belum termasuk ongkos kirim kecuali disebutkan.\n" .
               "3. Pembayaran sesuai kesepakatan.\n" .
               "4. Garansi produk sesuai kebijakan pabrik.\n" .
               "5. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan.";
    }
}