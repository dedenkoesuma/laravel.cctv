<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            // Untuk transaksi penjualan toko online
            $table->string('platform')->nullable()->after('catatan');        // Tokopedia, Shopee, dll
            $table->string('no_order')->nullable()->after('platform');       // Nomor order marketplace
        });
    }

    public function down(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            $table->dropColumn(['platform', 'no_order']);
        });
    }
};