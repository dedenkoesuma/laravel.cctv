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
        Schema::create('ruijie_products', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('category'); // Switch, Router, Access Point, etc.
            $table->text('description');
            
            // Pricing
            $table->decimal('price', 15, 2);
            $table->decimal('original_price', 15, 2)->nullable();
            
            // Inventory
            $table->integer('stock')->default(0);
            
            // Media
            $table->string('main_image')->nullable();
            $table->string('icon')->nullable(); // Bootstrap icon class
            
            // Product Details
            $table->json('features')->nullable(); // Array of features
            $table->text('specifications')->nullable(); // Detailed specs
            
            // Status & Visibility
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            
            // Ordering
            $table->integer('sort_order')->default(0);
            
            // Timestamps & Soft Delete
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index('category');
            $table->index('status');
            $table->index('is_featured');
            $table->index('sort_order');
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruijie_products');
    }
};