<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. TAMBAH kolom stock ke tabel yang SUDAH ADA
        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'stock')) {
                $table->integer('stock')->default(0)->after('status');
            }
        });

        // 2. BUAT tabel BARU untuk stock transactions
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id');
            $table->enum('type', ['masuk', 'keluar']);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('inventory_item_id')
                  ->references('id')
                  ->on('inventory_items')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transactions');
        
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};