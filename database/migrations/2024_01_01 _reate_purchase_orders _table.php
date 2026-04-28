<?php
// =====================================================
// FILE: database/migrations/2024_01_01_create_purchase_orders_table.php
// Jalankan: php artisan migrate
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== TABEL PURCHASE ORDER =====
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();         // PO-2026-0001
            $table->string('supplier_name');
            $table->string('supplier_phone')->nullable();
            $table->string('supplier_email')->nullable();
            $table->text('supplier_address')->nullable();
            $table->string('supplier_pic')->nullable();    // nama contact person

            $table->date('po_date');
            $table->date('required_date')->nullable();     // tanggal dibutuhkan

            $table->enum('payment_method', [
                'cash', 'transfer', 'tempo_30', 'tempo_60'
            ])->default('transfer');

            $table->string('delivery_to')->nullable();     // kirim ke gudang mana

            // PPN
            $table->boolean('use_ppn')->default(false);
            $table->decimal('ppn_percent', 5, 2)->default(11.00); // bisa 11% atau custom
            $table->decimal('ppn_amount', 15, 2)->default(0);

            // Harga
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->enum('status', [
                'draft', 'sent', 'confirmed', 'partial', 'completed', 'cancelled'
            ])->default('draft');

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // ===== TABEL ITEM PO =====
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_description')->nullable();
            $table->string('unit')->default('pcs');        // pcs, unit, box, roll
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('qty_received', 10, 2)->default(0); // sudah diterima
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount_item', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });

        // ===== TABEL HISTORY / LOG PO =====
        Schema::create('purchase_order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('action');         // created, edited, sent, confirmed, dll
            $table->text('description')->nullable();
            $table->string('actor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_logs');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};