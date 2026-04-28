<?php
// =====================================================
// FILE: database/migrations/2026_04_28_000001_create_quotations_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quo_number')->unique();              // QUO-2026-0001
            $table->string('token')->unique();                   // token unik untuk link customer
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('project_name')->nullable();          // nama proyek / keperluan
            $table->date('quo_date');
            $table->date('valid_until');                         // masa berlaku penawaran
            $table->enum('status', [
                'draft',
                'sent',       // sudah dikirim ke customer
                'approved',   // customer setuju
                'rejected',   // customer tolak
                'revised',    // customer minta revisi
                'expired',    // melewati masa berlaku
                'converted',  // sudah jadi SO
            ])->default('draft');
            $table->boolean('ppn_enabled')->default(false);
            $table->decimal('ppn_rate', 5, 2)->default(11);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('discount_global', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);      // sebelum PPN & diskon global
            $table->decimal('total_amount', 15, 2)->default(0);  // grand total
            $table->text('notes')->nullable();                   // catatan untuk customer
            $table->text('terms')->nullable();                   // syarat & ketentuan
            $table->text('customer_notes')->nullable();          // catatan dari customer saat respond
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->nullOnDelete();
            $table->foreignId('created_by')->constrained('admins')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->integer('qty')->default(1);
            $table->string('satuan')->default('unit');           // unit, pcs, meter, set, dll
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);       // diskon per item dalam %
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->integer('urutan')->default(0);               // urutan tampil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};