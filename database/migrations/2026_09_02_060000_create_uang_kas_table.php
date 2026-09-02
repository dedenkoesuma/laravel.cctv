<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uang_kas', function (Blueprint $table) {
            $table->id();

            $table->enum('jenis', ['masuk', 'keluar'])
                  ->comment('Jenis transaksi kas: masuk atau keluar');

            $table->date('tanggal')->comment('Tanggal transaksi');

            $table->string('kategori', 100)->comment('Contoh: Penjualan Cetak, Operasional, Gaji, dll');

            $table->string('keterangan', 191)->comment('Deskripsi singkat transaksi');

            $table->unsignedBigInteger('jumlah')->default(0)->comment('Nominal dalam rupiah');

            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['jenis', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uang_kas');
    }
};