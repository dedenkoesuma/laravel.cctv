<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('invoice_number')->unique();
            $table->string('product_name');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->integer('quantity');
            $table->decimal('modal_per_unit', 15, 2); // Harga beli per unit
            $table->decimal('selling_price_per_unit', 15, 2); // Harga jual per unit
            $table->decimal('shipping_cost', 15, 2)->default(0); // Ongkos kirim
            $table->decimal('additional_cost', 15, 2)->default(0); // Biaya tambahan
            $table->decimal('total_modal', 15, 2); // Total modal (quantity * modal_per_unit)
            $table->decimal('total_selling', 15, 2); // Total penjualan (quantity * selling_price)
            $table->decimal('total_cost', 15, 2); // Total biaya (total_modal + shipping + additional)
            $table->decimal('profit', 15, 2); // Keuntungan bersih
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_date');
            $table->index('invoice_number');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};