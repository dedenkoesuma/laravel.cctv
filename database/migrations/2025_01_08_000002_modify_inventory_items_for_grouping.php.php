<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // 1. Pastikan kolom stock ada
            if (!Schema::hasColumn('inventory_items', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('status');
            }
            
            // 2. Add flag untuk tracking
            if (!Schema::hasColumn('inventory_items', 'is_grouped')) {
                $table->boolean('is_grouped')->default(false)->after('stock');
            }
        });
        
        // 3. Ubah serial_number menjadi nullable
        DB::statement('ALTER TABLE inventory_items MODIFY serial_number VARCHAR(255) NULL');
        
        // 4. Drop unique constraint dari serial_number jika ada
        try {
            DB::statement('ALTER TABLE inventory_items DROP INDEX serial_number');
        } catch (\Exception $e) {
            // Skip jika tidak ada
        }
        
        // TIDAK PAKAI UNIQUE CONSTRAINT - hanya index biasa
        try {
            DB::statement('ALTER TABLE inventory_items ADD INDEX idx_product_group (product_name, brand, supplier)');
        } catch (\Exception $e) {
            // Skip jika sudah ada
        }
        
        try {
            DB::statement('ALTER TABLE inventory_items ADD INDEX idx_product_brand (product_name, brand)');
        } catch (\Exception $e) {
            // Skip jika sudah ada
        }
        
        try {
            DB::statement('ALTER TABLE inventory_items ADD INDEX idx_status (status)');
        } catch (\Exception $e) {
            // Skip jika sudah ada
        }
    }

    public function down(): void
    {
        // Drop indexes
        try {
            DB::statement('ALTER TABLE inventory_items DROP INDEX idx_product_group');
        } catch (\Exception $e) {}
        
        try {
            DB::statement('ALTER TABLE inventory_items DROP INDEX idx_product_brand');
        } catch (\Exception $e) {}
        
        try {
            DB::statement('ALTER TABLE inventory_items DROP INDEX idx_status');
        } catch (\Exception $e) {}
        
        Schema::table('inventory_items', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_items', 'is_grouped')) {
                $table->dropColumn('is_grouped');
            }
        });
        
        // Restore serial_number
        DB::statement('ALTER TABLE inventory_items MODIFY serial_number VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE inventory_items ADD UNIQUE (serial_number)');
    }
};