<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            // Kolom Invoice
            $table->string('invoice_number', 50)->nullable()->after('no_order');
            $table->date('invoice_date')->nullable()->after('invoice_number');

            // Tempo / Jatuh Tempo
            $table->enum('tipe_bayar', ['cash', 'tempo'])->default('cash')->after('invoice_date');
            $table->integer('tempo_hari')->nullable()->after('tipe_bayar')->comment('Jumlah hari tempo, misal 30');
            $table->date('jatuh_tempo')->nullable()->after('tempo_hari')->comment('Tanggal jatuh tempo pembayaran');

            // Nomor Rekening Tujuan
            $table->string('nama_bank', 100)->nullable()->after('jatuh_tempo');
            $table->string('no_rekening', 50)->nullable()->after('nama_bank');
            $table->string('nama_rekening', 100)->nullable()->after('no_rekening');

            // Referensi SO
            $table->string('so_number', 50)->nullable()->after('nama_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('keuangan_transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number', 'invoice_date',
                'tipe_bayar', 'tempo_hari', 'jatuh_tempo',
                'nama_bank', 'no_rekening', 'nama_rekening',
                'so_number',
            ]);
        });
    }
};