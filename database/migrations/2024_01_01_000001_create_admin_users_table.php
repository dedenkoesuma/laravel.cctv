<?php
// database/migrations/2024_01_01_000001_create_admin_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('role', ['super_admin', 'admin', 'editor'])->default('admin');
            $table->string('foto_profil')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
};

// database/migrations/2024_01_01_000002_create_kategori_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori');
    }
};

// database/migrations/2024_01_01_000003_create_produk_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->onDelete('set null');
            $table->string('nama_produk', 200);
            $table->string('slug', 200)->unique();
            $table->string('kode_produk', 50)->unique()->nullable();
            $table->string('merek', 100)->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->text('deskripsi_lengkap')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->decimal('harga_promo', 15, 2)->nullable();
            $table->integer('stok')->default(0);
            $table->string('gambar_utama')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
};

// database/migrations/2024_01_01_000004_create_spesifikasi_produk_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('spesifikasi_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('nama_spek', 100);
            $table->text('nilai_spek');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spesifikasi_produk');
    }
};

// database/migrations/2024_01_01_000005_create_galeri_produk_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('galeri_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('gambar');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galeri_produk');
    }
};

// database/migrations/2024_01_01_000006_create_slider_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200)->nullable();
            $table->text('subjudul')->nullable();
            $table->string('gambar');
            $table->string('link_url')->nullable();
            $table->string('link_text', 100)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('slider');
    }
};

// database/migrations/2024_01_01_000007_create_tentang_kami_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('tentang_kami', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200)->nullable();
            $table->text('konten')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tentang_kami');
    }
};

// database/migrations/2024_01_01_000008_create_layanan_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 200);
            $table->string('icon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layanan');
    }
};

// database/migrations/2024_01_01_000009_create_portofolio_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('portofolio', function (Blueprint $table) {
            $table->id();
            $table->string('nama_project', 200);
            $table->string('klien', 100)->nullable();
            $table->string('lokasi', 200)->nullable();
            $table->date('tanggal_project')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_utama')->nullable();
            $table->string('kategori_project', 100)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portofolio');
    }
};

// database/migrations/2024_01_01_000010_create_galeri_portofolio_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('galeri_portofolio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portofolio_id')->constrained('portofolio')->onDelete('cascade');
            $table->string('gambar');
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galeri_portofolio');
    }
};

// database/migrations/2024_01_01_000011_create_testimoni_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_klien', 100);
            $table->string('perusahaan', 100)->nullable();
            $table->string('foto')->nullable();
            $table->text('konten_testimoni');
            $table->integer('rating')->default(5);
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimoni');
    }
};

// database/migrations/2024_01_01_000012_create_artikel_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('slug', 200)->unique();
            $table->text('konten');
            $table->text('excerpt')->nullable();
            $table->string('gambar_featured')->nullable();
            $table->string('kategori_artikel', 100)->nullable();
            $table->string('tags')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('admin_users')->onDelete('set null');
            $table->integer('views')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikel');
    }
};

// database/migrations/2024_01_01_000013_create_kontak_masuk_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('kontak_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('telepon', 20)->nullable();
            $table->string('subjek', 200)->nullable();
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'diproses', 'selesai'])->default('baru');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kontak_masuk');
    }
};

// database/migrations/2024_01_01_000014_create_settings_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};

// database/migrations/2024_01_01_000015_create_faq_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->text('jawaban');
            $table->string('kategori', 100)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq');
    }
};