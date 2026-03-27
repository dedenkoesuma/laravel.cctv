<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('inventory_items', 'stock')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                $table->integer('stock')->default(1)->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('inventory_items', 'stock')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }
};