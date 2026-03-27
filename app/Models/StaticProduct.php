<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticProduct extends Model
{
    protected $fillable = [
        'brand',
        'tab',
        'sku',
        'nama_produk',
        'channel',
        'kategori',
        'image',
        'specs',
        'harga_jual',
        'stok',
        'status',
        'order'
    ];

    protected $casts = [
        'specs' => 'array',
        'harga_jual' => 'decimal:2'
    ];

    // Scope for filtering by brand
    public function scopeBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    // Scope for filtering by tab
    public function scopeTab($query, $tab)
    {
        return $query->where('tab', $tab);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}