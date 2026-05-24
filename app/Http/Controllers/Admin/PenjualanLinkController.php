<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/PenjualanLinkController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanLinkController extends Controller
{
    // ===================================================
    // 1. GENERATE LINK BARU (dipanggil dari halaman admin)
    // POST /api/admin/keuangan/generate-link
    // ===================================================
    public function generateLink(Request $request)
    {
        $request->validate([
            'label'          => 'nullable|string|max:100',
            'nama_admin'     => 'nullable|string|max:100',
            'expired_days'   => 'required|integer|min:1|max:90',
            'max_penggunaan' => 'nullable|integer|min:0',
        ]);

        // Generate token unik
        $token = strtoupper('PJL-' . bin2hex(random_bytes(5)));

        // Pastikan token tidak duplikat
        while (DB::table('link_penjualan')->where('token', $token)->exists()) {
            $token = strtoupper('PJL-' . bin2hex(random_bytes(5)));
        }

        $expiredAt = now()->addDays($request->expired_days);

        DB::table('link_penjualan')->insert([
            'token'             => $token,
            'label'             => $request->label ?? 'Link Penjualan',
            'nama_admin'        => $request->nama_admin ?? 'Admin',
            'expired_at'        => $expiredAt,
            'is_active'         => true,
            'max_penggunaan'    => $request->max_penggunaan ?? 0,
            'jumlah_penggunaan' => 0,
            'created_by'        => session('admin_id', 1),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $url = url('/penjualan-online/' . $token);

        return response()->json([
            'success' => true,
            'token'   => $token,
            'url'     => $url,
            'expired' => $expiredAt->format('d M Y'),
            'message' => 'Link berhasil dibuat!',
        ]);
    }

    // ===================================================
    // 2. TAMPILKAN FORM PUBLIK DENGAN TOKEN (legacy)
    // GET /penjualan-online/{token}
    // ===================================================
    public function showForm($token)
    {
        $link = DB::table('link_penjualan')
            ->where('token', $token)
            ->first();

        // Validasi: token ada
        if (!$link) {
            return view('penjualan.link-invalid', ['alasan' => 'Link tidak ditemukan.']);
        }

        // Validasi: masih aktif
        if (!$link->is_active) {
            return view('penjualan.link-invalid', ['alasan' => 'Link ini sudah dinonaktifkan oleh admin.']);
        }

        // Validasi: belum expired
        if (now()->gt($link->expired_at)) {
            return view('penjualan.link-invalid', ['alasan' => 'Link ini sudah kadaluarsa sejak ' . \Carbon\Carbon::parse($link->expired_at)->format('d M Y') . '.']);
        }

        // Validasi: belum melebihi max penggunaan (jika diset)
        if ($link->max_penggunaan > 0 && $link->jumlah_penggunaan >= $link->max_penggunaan) {
            return view('penjualan.link-invalid', ['alasan' => 'Link ini sudah mencapai batas maksimum penggunaan.']);
        }

        return view('penjualan.form-publik', [
            'token'      => $token,
            'label'      => $link->label,
            'nama_admin' => $link->nama_admin,
            'expired_at' => \Carbon\Carbon::parse($link->expired_at)->format('d M Y'),
        ]);
    }

    // ===================================================
    // 2b. ✅ FORM TETAP UNTUK STAFF (tanpa token dinamis)
    // GET /penjualan-online/staff
    // ===================================================
    public function showFormStaff()
    {
        return view('penjualan.form-publik', [
            'token'      => 'STAFF',
            'label'      => 'Form Staff',
            'nama_admin' => 'Admin',
            'expired_at' => 'Tidak terbatas',
        ]);
    }

    // ===================================================
    // 3. ✅ SIMPAN DATA PENJUALAN (tanpa validasi token)
    // POST /penjualan-online/simpan
    // ===================================================
    public function simpan(Request $request)
    {
        // Validasi input saja, tidak perlu cek token
        $request->validate([
            'platform'      => 'required|string|max:50',
            'jumlah'        => 'required|numeric|min:1',
            'tanggal'       => 'required|date',
            'deskripsi'     => 'required|string|max:255',
            'metode_bayar'  => 'required|in:cash,transfer,qris,kartu_kredit',
            'no_order'      => 'nullable|string|max:100',
            'pihak_terkait' => 'nullable|string|max:100',
            'status'        => 'nullable|in:lunas,pending,batal',
            'catatan'       => 'nullable|string|max:500',
        ]);

        // Generate kode transaksi
        $tahun = date('Y');
        $count = DB::table('keuangan_transaksi')->whereYear('created_at', $tahun)->count();
        $kode  = 'TRX-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        // Simpan ke keuangan_transaksi
        DB::table('keuangan_transaksi')->insert([
            'kode_transaksi' => $kode,
            'tipe'           => 'pemasukan',
            'kategori'       => 'Penjualan Online',
            'sub_kategori'   => $request->platform,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'referensi'      => $request->no_order,
            'metode_bayar'   => $request->metode_bayar,
            'status'         => $request->status ?? 'lunas',
            'pihak_terkait'  => $request->pihak_terkait,
            'catatan'        => $request->catatan,
            'platform'       => $request->platform,
            'no_order'       => $request->no_order,
            'created_by'     => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil disimpan!',
            'kode'    => $kode,
        ]);
    }

    // ===================================================
    // 4. LIST SEMUA LINK (untuk panel admin)
    // GET /api/admin/keuangan/links
    // ===================================================
    public function getLinks()
    {
        $links = DB::table('link_penjualan')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($link) {
                $link->url           = url('/penjualan-online/' . $link->token);
                $link->is_expired    = now()->gt($link->expired_at);
                $link->expired_label = \Carbon\Carbon::parse($link->expired_at)->format('d M Y');
                return $link;
            });

        return response()->json(['success' => true, 'data' => $links]);
    }

    // ===================================================
    // 5. NONAKTIFKAN / AKTIFKAN LINK
    // POST /api/admin/keuangan/links/{id}/toggle
    // ===================================================
    public function toggleLink($id)
    {
        $link = DB::table('link_penjualan')->where('id', $id)->first();

        if (!$link) {
            return response()->json(['success' => false, 'message' => 'Link tidak ditemukan'], 404);
        }

        DB::table('link_penjualan')->where('id', $id)->update([
            'is_active'  => !$link->is_active,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $link->is_active ? 'Link dinonaktifkan.' : 'Link diaktifkan kembali.',
        ]);
    }

    // ===================================================
    // 6. HAPUS LINK
    // DELETE /api/admin/keuangan/links/{id}
    // ===================================================
    public function deleteLink($id)
    {
        DB::table('link_penjualan')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Link dihapus.']);
    }
}