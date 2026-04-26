<?php
// =====================================================
// FILE: database/migrations/2026_04_25_000001_create_link_penjualan_table.php
// Jalankan: php artisan migrate
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('token', 32)->unique();           // Token unik link
            $table->string('label')->nullable();             // Nama/label untuk link ini
            $table->string('nama_admin')->nullable();        // Untuk siapa link ini
            $table->timestamp('expired_at');                 // Kapan link kadaluarsa
            $table->boolean('is_active')->default(true);     // Bisa di-nonaktifkan manual
            $table->unsignedInteger('max_penggunaan')->default(0); // 0 = unlimited
            $table->unsignedInteger('jumlah_penggunaan')->default(0); // Counter pemakaian
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_penjualan');
    }
};