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
        // Create ruijie_page_settings table
        Schema::create('ruijie_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Ruijie Networks');
            $table->text('subtitle')->nullable();
            $table->integer('products_count')->default(500);
            $table->integer('clients_count')->default(10000);
            $table->integer('satisfaction_rate')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create ruijie_categories table
        Schema::create('ruijie_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category_type')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create ruijie_products table
        Schema::create('ruijie_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('ruijie_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->json('features')->nullable();
            $table->json('specifications')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruijie_products');
        Schema::dropIfExists('ruijie_categories');
        Schema::dropIfExists('ruijie_page_settings');
    }
};