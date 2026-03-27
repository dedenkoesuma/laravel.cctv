<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // PENTING: Tentukan tabel yang benar!
    protected $table = 'static_products';

    protected $fillable = [
        'sku',
        'nama_produk',        // Sesuai database Anda
        'brand',
        'tab_category',
        'kategori',
        'deskripsi',          // Sesuai database Anda
        'gambar',             // Sesuai database Anda
        'harga_jual',
        'harga_modal',
        'stok',               // Sesuai database Anda
        'position',
        'is_active',
        // Kolom spesifikasi (optional)
        'channel',
        'compression',
        'max_channel',
        'max_resolution',
        'video_format',
        'sata_hdd',
        'intelligent_search',
        'output_resolution',
        'p2p_mobile',
        'ai_smd',
        'face_detection',
        'perimeter_protection',
        'ai_database'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'intelligent_search' => 'boolean',
        'p2p_mobile' => 'boolean',
        'ai_smd' => 'boolean',
        'face_detection' => 'boolean',
        'perimeter_protection' => 'boolean',
        'ai_database' => 'boolean',
        'harga_jual' => 'integer',
        'harga_modal' => 'integer',
        'stok' => 'integer',
    ];

    // Scope untuk filter berdasarkan brand
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    // Scope untuk filter berdasarkan tab category
    public function scopeByTabCategory($query, $category)
    {
        return $query->where('tab_category', $category);
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Accessor untuk format brand name
    public function getBrandDataAttribute()
    {
        $brands = [
            'hikvision' => [
                'name' => 'Hikvision',
                'color' => '#E63946',
                'description' => 'Leading provider of innovative video surveillance products and solutions. Trusted by millions worldwide for security and protection.'
            ],
            'dahua' => [
                'name' => 'Dahua Technology',
                'color' => '#1B4B8C',
                'description' => 'World-leading video-centric smart IoT solution and service provider. Delivering cutting-edge technology for safer communities.'
            ],
            'hilook' => [
                'name' => 'HiLook',
                'color' => '#00A8E1',
                'description' => 'Cost-effective security solutions with professional features. Perfect for small to medium businesses.'
            ],
            'ezviz' => [
                'name' => 'EZVIZ',
                'color' => '#00B050',
                'description' => 'Smart home security with easy setup. Reliable protection for your home.'
            ],
            'unv' => [
                'name' => 'Uniview',
                'color' => '#FF6B35',
                'description' => 'Advanced surveillance technology with smart features. Affordable yet powerful security solutions.'
            ],
        ];

        $brandKey = strtolower($this->brand);
        return $brands[$brandKey] ?? [
            'name' => ucfirst($this->brand),
            'color' => '#6C757D',
            'description' => 'Quality security products and solutions.'
        ];
    }

    // Accessor untuk compatibility dengan nama kolom berbeda
    public function getProductNameAttribute()
    {
        return $this->nama_produk;
    }

    public function getImageAttribute($value)
    {
        return $this->attributes['gambar'] ?? null;
    }

    public function getDescriptionAttribute()
    {
        return $this->deskripsi;
    }

    public function getStockAttribute()
    {
        return $this->stok;
    }

    public function getStatusAttribute()
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    // Mutator untuk set values
    public function setProductNameAttribute($value)
    {
        $this->attributes['nama_produk'] = $value;
    }

    public function setImageAttribute($value)
    {
        $this->attributes['gambar'] = $value;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['deskripsi'] = $value;
    }

    public function setStockAttribute($value)
    {
        $this->attributes['stok'] = $value;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['is_active'] = ($value === 'active' || $value === 1 || $value === true) ? 1 : 0;
    }

    // Helper untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    public function getFormattedCostAttribute()
    {
        return 'Rp ' . number_format($this->harga_modal, 0, ',', '.');
    }
}