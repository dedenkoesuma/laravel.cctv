<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_onlines', function (Blueprint $table) {
            $table->id();

            $table->string('no_order', 20)->unique()->comment('Nomor order unik, contoh: ORD-0001');

            $table->string('pelanggan', 100)->comment('Nama pelanggan');

            $table->enum('platform', ['WA', 'Tokopedia', 'Shopee', 'Instagram'])
                  ->comment('Platform asal pesanan');

            $table->string('tipe_kertas', 50)->comment('Contoh: A4 80gr BW, Foto glossy');

            $table->unsignedInteger('jumlah_lembar')->default(1)->comment('Jumlah lembar yang dicetak');

            $table->unsignedBigInteger('total')->default(0)->comment('Total harga dalam rupiah');

            $table->enum('status', ['Proses', 'Selesai', 'Dibatalkan'])
                  ->default('Proses')
                  ->comment('Status pengerjaan pesanan');

            $table->text('catatan')->nullable()->comment('Catatan tambahan dari pelanggan');

            $table->timestamps();
            $table->softDeletes()->comment('Soft delete — data tidak hilang permanen');

            // ── Index untuk performa query ──
            $table->index('status');
            $table->index('platform');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_onlines');
    }
};