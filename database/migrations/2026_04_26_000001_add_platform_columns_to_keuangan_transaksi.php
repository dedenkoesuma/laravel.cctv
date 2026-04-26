<?php
// =====================================================
// FILE: database/migrations/2026_04_26_000001_add_platform_columns_to_keuangan_transaksi.php
// Jalankan: php artisan migrate
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            // Tambah kolom platform (nama marketplace)
            if (!Schema::hasColumn('keuangan_transaksi', 'platform')) {
                $table->string('platform', 50)->nullable()->after('catatan');
            }

            // Tambah kolom no_order (nomor order dari marketplace)
            if (!Schema::hasColumn('keuangan_transaksi', 'no_order')) {
                $table->string('no_order', 100)->nullable()->after('platform');
            }
        });
    }

    public function down(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            $table->dropColumn(['platform', 'no_order']);
        });
    }
};