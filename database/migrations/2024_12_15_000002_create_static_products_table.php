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
            $table->string('brand'); 
            $table->string('tab_category')->nullable();
            $table->string('sku')->nullable();
            $table->string('nama_produk');
            $table->string('kategori')->nullable(); 
            $table->integer('position')->default(0); 
            
            // Disesuaikan agar cocok dengan data Seeder
            $table->decimal('harga_modal', 12, 2)->nullable(); 
            $table->decimal('harga_jual', 12, 2)->nullable();
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable(); 
            $table->string('gambar')->nullable(); 
            $table->boolean('is_active')->default(true); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('static_products');
    }
};