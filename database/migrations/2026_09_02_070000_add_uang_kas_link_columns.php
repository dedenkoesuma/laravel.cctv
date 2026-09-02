<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_onlines', function (Blueprint $table) {
            $table->foreignId('uang_kas_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('uang_kas')
                  ->nullOnDelete()
                  ->comment('Link ke entry Uang Masuk yang dibuat otomatis saat pesanan Selesai');
        });

        Schema::table('uang_kas', function (Blueprint $table) {
            $table->boolean('otomatis')
                  ->default(false)
                  ->after('catatan')
                  ->comment('True kalau entry ini dibuat otomatis dari pesanan/invoice, bukan input manual');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_onlines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('uang_kas_id');
        });

        Schema::table('uang_kas', function (Blueprint $table) {
            $table->dropColumn('otomatis');
        });
    }
};