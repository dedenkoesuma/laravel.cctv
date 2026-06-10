<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modal_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('modal_paket_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modal_paket_id')->constrained()->onDelete('cascade');
            $table->string('nama_item');
            $table->integer('qty')->default(1);
            $table->bigInteger('harga_beli')->default(0);
            $table->decimal('diskon', 5, 2)->default(0);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modal_paket_items');
        Schema::dropIfExists('modal_pakets');
    }
};