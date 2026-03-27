<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriProduk extends Model
{
    protected $table = 'galeri_produk';

    protected $fillable = [
        'produk_id',
        'gambar',
        'urutan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
