<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_onlines', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan_onlines', 'jasa_potong')) {
                $table->boolean('jasa_potong')->default(false)->after('total')
                      ->comment('Pesanan pakai jasa potong atau tidak');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_onlines', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan_onlines', 'jasa_potong')) {
                $table->dropColumn('jasa_potong');
            }
        });
    }
};