<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Perbesar kolom jadi TEXT supaya muat beberapa tipe kertas (JSON array)
        Schema::table('pesanan_onlines', function (Blueprint $table) {
            $table->text('tipe_kertas')->comment('JSON array, contoh: ["A4 80gr BW","Foto glossy"]')->change();
        });

        // 2) Konversi data lama (string biasa) jadi JSON array 1 item,
        //    supaya tidak hilang & tetap kebaca oleh cast 'array' di model.
        DB::table('pesanan_onlines')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $value = $row->tipe_kertas;

                // Lewati kalau sudah berupa JSON array yang valid
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    continue;
                }

                DB::table('pesanan_onlines')
                    ->where('id', $row->id)
                    ->update(['tipe_kertas' => json_encode([$value])]);
            }
        });
    }

    public function down(): void
    {
        // Balikin jadi string tunggal (ambil elemen pertama dari array)
        DB::table('pesanan_onlines')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $decoded = json_decode($row->tipe_kertas, true);
                $first   = is_array($decoded) ? ($decoded[0] ?? '') : $row->tipe_kertas;

                DB::table('pesanan_onlines')
                    ->where('id', $row->id)
                    ->update(['tipe_kertas' => $first]);
            }
        });

        Schema::table('pesanan_onlines', function (Blueprint $table) {
            $table->string('tipe_kertas', 50)->comment('Contoh: A4 80gr BW, Foto glossy')->change();
        });
    }
};