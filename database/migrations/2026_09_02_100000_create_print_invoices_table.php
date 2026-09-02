<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_cetak', function (Blueprint $table) {
            $table->id();
            $table->string('no_tagihan')->unique();
            $table->string('pelanggan');
            $table->text('deskripsi')->nullable();
            $table->date('tgl_tagihan');
            $table->date('jatuh_tempo')->nullable();
            $table->unsignedBigInteger('total')->default(0);
            $table->enum('status', ['unpaid', 'lunas', 'batal'])->default('unpaid');
            $table->unsignedBigInteger('pesanan_online_id')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pesanan_online_id')
                  ->references('id')->on('pesanan_onlines')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_cetak');
    }
};