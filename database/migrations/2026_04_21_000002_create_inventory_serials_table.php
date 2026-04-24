<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom use_serial_number ke gudang_products
        Schema::table('gudang_products', function (Blueprint $table) {
            $table->boolean('use_serial_number')->default(false)->after('sku');
        });
        // Tabel serial number inventory
        Schema::create('inventory_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('gudang_products')->onDelete('cascade');
            $table->string('serial_number');
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->foreignId('barang_masuk_id')->nullable()->constrained('barang_masuk')->onDelete('set null');
            $table->foreignId('sales_order_item_id')->nullable()->constrained('sales_order_items')->onDelete('set null');
            $table->foreignId('barang_keluar_id')->nullable()->constrained('barang_keluar')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'serial_number']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inventory_serials');
        Schema::table('gudang_products', function (Blueprint $table) {
            $table->dropColumn('use_serial_number');
        });
    }
};