<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('jumlah')->default(1);
            $table->enum('keterangan', ['terjual', 'rusak', 'retur'])->default('terjual');
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->string('penerima')->nullable();
            $table->date('tanggal_keluar');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('gudang_products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};