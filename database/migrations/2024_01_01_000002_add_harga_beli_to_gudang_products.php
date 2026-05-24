<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gudang_products', function (Blueprint $table) {
            // Harga beli default per unit (bisa diisi manual atau otomatis dari PO)
            $table->decimal('harga_beli', 15, 2)->default(0)->after('harga_jual');
            // Margin otomatis dalam persen
            $table->decimal('margin_persen', 8, 2)->default(0)->after('harga_beli');
            // Catat dari PO mana harga beli terakhir diupdate
            $table->unsignedBigInteger('last_po_id')->nullable()->after('margin_persen');
            // Tanggal harga beli terakhir diupdate
            $table->timestamp('harga_beli_updated_at')->nullable()->after('last_po_id');
        });
    }

    public function down(): void
    {
        Schema::table('gudang_products', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'margin_persen', 'last_po_id', 'harga_beli_updated_at']);
        });
    }
};