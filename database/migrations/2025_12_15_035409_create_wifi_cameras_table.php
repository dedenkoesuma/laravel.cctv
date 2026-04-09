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
        if (!Schema::hasTable('wifi_cameras')) {
            Schema::create('wifi_cameras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand');
            $table->string('subtitle');
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('specifications')->nullable();
            $table->json('package_includes')->nullable();
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('brand');
            $table->index('status');
            $table->index('is_featured');
            $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_cameras');
    }
};