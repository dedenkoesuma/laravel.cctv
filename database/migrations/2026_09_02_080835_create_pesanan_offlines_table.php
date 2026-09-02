<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_offlines', function (Blueprint $table) {
            $table->id();
            $table->string('no_order')->unique();
            $table->string('pelanggan');
            $table->string('tipe_kertas');
            $table->integer('jumlah_lembar');
            $table->integer('total');
            $table->string('status')->default('Proses'); // Proses, Selesai, Dibatalkan
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('uang_kas_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_offlines');
    }
};