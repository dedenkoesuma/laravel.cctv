<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gudang_products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('total_masuk')->default(0);
            $table->integer('total_keluar')->default(0);
            $table->integer('sisa_stok')->default(0);
            $table->timestamps();
        });

        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('serial_number')->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->date('tanggal_masuk');
            $table->enum('status', ['tersedia', 'terjual', 'rusak'])->default('tersedia');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('gudang_products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
        Schema::dropIfExists('gudang_products');
    }
};