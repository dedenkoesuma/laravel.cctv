<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriPortofolio extends Model
{
    protected $table = 'galeri_portofolio';

    protected $fillable = [
        'portofolio_id',
        'gambar',
        'keterangan',
        'urutan'
    ];

    public function portofolio()
    {
        return $this->belongsTo(Portofolio::class, 'portofolio_id');
    }
}