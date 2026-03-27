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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('product_name');
            $table->string('brand'); // Hikvision, Dahua, HiLook, UNV, HiView
            $table->string('tab_category'); // Basic, Cooper, Pro, Enterprise
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('channel')->nullable(); // 4, 8, 16, 32
            $table->string('compression')->default('H.265+');
            $table->integer('max_channel')->nullable();
            $table->string('max_resolution')->nullable(); // 5MP, 8MP, etc
            $table->string('video_format')->nullable(); // HDCVI/AHD/TVI/CVBS
            $table->integer('sata_hdd')->default(1);
            $table->boolean('intelligent_search')->default(true);
            $table->string('output_resolution')->default('4K/1080P');
            $table->boolean('p2p_mobile')->default(true);
            $table->boolean('ai_smd')->default(false);
            $table->boolean('face_detection')->default(false);
            $table->boolean('perimeter_protection')->default(false);
            $table->boolean('ai_database')->default(false);
            $table->integer('stock')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};