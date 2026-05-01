<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel invoices jika belum ada
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('no_invoice')->unique();
                $table->date('tgl_invoice');
                $table->decimal('total', 15, 2)->default(0);
                $table->enum('status', ['unpaid', 'paid', 'lunas', 'overdue'])->default('unpaid');
                $table->timestamps();
            });
        }

        // Tambah kolom ke tabel invoices jika belum ada
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('tgl_invoice');
            }
            if (!Schema::hasColumn('invoices', 'status')) {
                $table->enum('status', ['unpaid', 'paid', 'lunas', 'overdue'])->default('unpaid')->after('total');
            }
            if (!Schema::hasColumn('invoices', 'jatuh_tempo')) {
                $table->date('jatuh_tempo')->nullable()->after('status');
            }
            if (!Schema::hasColumn('invoices', 'notif_h3_sent')) {
                $table->boolean('notif_h3_sent')->default(false)->after('jatuh_tempo');
            }
            if (!Schema::hasColumn('invoices', 'notif_h1_sent')) {
                $table->boolean('notif_h1_sent')->default(false)->after('notif_h3_sent');
            }
            if (!Schema::hasColumn('invoices', 'notif_overdue_sent')) {
                $table->boolean('notif_overdue_sent')->default(false)->after('notif_h1_sent');
            }
        });

        // Buat tabel notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->enum('tipe', ['h3', 'h1', 'overdue']);
                $table->string('judul');
                $table->text('pesan');
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->boolean('dibaca')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                $collect = collect(['jatuh_tempo', 'notif_h3_sent', 'notif_h1_sent', 'notif_overdue_sent', 'status', 'total']);
                $existing = $collect->filter(fn($col) => Schema::hasColumn('invoices', $col))->values()->all();

                if (!empty($existing)) {
                    $table->dropColumn($existing);
                }
            });
        }
    }
};