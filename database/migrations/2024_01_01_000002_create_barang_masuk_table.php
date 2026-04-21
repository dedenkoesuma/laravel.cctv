<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->date('tanggal_masuk');
            $table->enum('status', ['tersedia', 'terjual', 'rusak'])->default('tersedia');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};