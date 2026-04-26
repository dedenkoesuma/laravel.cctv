<?php
// =====================================================
// FILE: database/migrations/2024_01_01_create_keuangan_tables.php
// Jalankan: php artisan migrate
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== TABEL UTAMA TRANSAKSI KEUANGAN =====
        Schema::create('keuangan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // TRX-2026-0001
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->string('kategori'); // penjualan, pembelian_stok, operasional, dll
            $table->string('sub_kategori')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->string('referensi')->nullable(); // SO number, invoice, dll
            $table->enum('metode_bayar', ['cash', 'transfer', 'qris', 'kartu_kredit'])->default('cash');
            $table->enum('status', ['lunas', 'pending', 'batal'])->default('lunas');
            $table->string('pihak_terkait')->nullable(); // nama customer / supplier
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // ===== TABEL KATEGORI KEUANGAN =====
        Schema::create('keuangan_kategori', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->string('nama');
            $table->string('icon')->default('bi-circle');
            $table->string('warna')->default('#6b7280');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ===== TABEL AKUN / KAS =====
        Schema::create('keuangan_akun', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun'); // Kas Utama, Bank BCA, Dana, dll
            $table->enum('tipe', ['kas', 'bank', 'dompet_digital']);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('saldo_sekarang', 15, 2)->default(0);
            $table->string('nomor_rekening')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_transaksi');
        Schema::dropIfExists('keuangan_kategori');
        Schema::dropIfExists('keuangan_akun');
    }
};