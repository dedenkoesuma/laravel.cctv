<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create table for tracking serial numbers separately
     */
    public function up(): void
    {
        Schema::create('inventory_serial_numbers', function (Blueprint $table) {
            $table->id();
            
            // Link to master product
            $table->unsignedBigInteger('inventory_item_id');
            
            // Serial number (unique)
            $table->string('serial_number')->unique();
            
            // Status
            $table->enum('status', ['in_stock', 'sold', 'damaged', 'returned'])
                  ->default('in_stock')
                  ->index();
            
            // For sold items
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->timestamp('sold_date')->nullable();
            
            // For damaged/returned items
            $table->timestamp('exit_date')->nullable();
            $table->string('exit_reason')->nullable();
            $table->text('notes')->nullable();
            
            // Tracking
            $table->string('scanned_by')->nullable();
            $table->timestamp('entry_date')->nullable();
            
            $table->timestamps();
            
            // Foreign key with cascade delete
            $table->foreign('inventory_item_id')
                  ->references('id')
                  ->on('inventory_items')
                  ->onDelete('cascade');
            
            // Indexes for performance
            $table->index(['inventory_item_id', 'status']);
            $table->index('entry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_serial_numbers');
    }
};