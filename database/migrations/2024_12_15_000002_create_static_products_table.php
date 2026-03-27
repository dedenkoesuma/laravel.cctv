<?php
// ==================================================
// 1. DATABASE MIGRATION
// ==================================================
// File: database/migrations/xxxx_create_static_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('static_products', function (Blueprint $table) {
            $table->id();
            $table->string('brand'); // hikvision, dahua, hilook, unv, hiview
            $table->string('tab'); // basic, cooper, pro, enterprise
            $table->string('sku')->unique();
            $table->string('nama_produk');
            $table->string('channel'); // 4, 8, 16, 32
            $table->string('kategori'); // Small Business, Cooper Series, etc
            $table->string('image')->nullable();
            $table->json('specs'); // Store specifications as JSON
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->integer('stok')->default(0);
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->integer('order')->default(0); // For ordering products
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('static_products');
    }
};