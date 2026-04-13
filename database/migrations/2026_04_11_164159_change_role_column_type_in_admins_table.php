<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom role dari ENUM ke VARCHAR
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 50)->change();
        });
    }

    public function down()
    {
        // Biarkan kosong atau kembalikan ke ENUM jika perlu
        Schema::table('admins', function (Blueprint $table) {
            // $table->enum('role', ['admin', 'superadmin'])->change();
        });
    }
};