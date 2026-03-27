<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // TAMBAHKAN CEK INI - Skip jika tabel sudah ada
        if (Schema::hasTable('inventory_items')) {
            return;
        }
        
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('product_name');
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->enum('status', ['in_stock', 'sold', 'damaged', 'returned'])->default('in_stock');
            $table->integer('stock')->default(1);  // ✅ TAMBAHKAN INI
            $table->timestamp('entry_date')->nullable();
            $table->timestamp('exit_date')->nullable();
            $table->string('exit_reason')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->string('scanned_by')->nullable();
            $table->string('warehouse_location')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa
            $table->index('serial_number');
            $table->index('status');
            $table->index('brand');
            $table->index('category');
            $table->index('entry_date');
            $table->index('exit_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
};