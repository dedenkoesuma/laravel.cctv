<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('total_masuk')->default(0);   // total semua barang masuk
            $table->integer('total_keluar')->default(0);  // total terjual/keluar
            $table->integer('sisa_stok')->default(0);     // total_masuk - total_keluar
            $table->string('satuan')->default('unit');    // pcs, unit, box, dll
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};