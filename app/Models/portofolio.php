<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portofolio extends Model
{
    use HasFactory;

    protected $table = 'portofolio';

    protected $fillable = [
        'nama_project',
        'klien',
        'lokasi',
        'tanggal_project',
        'deskripsi',
        'gambar_utama',
        'kategori_project',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'tanggal_project' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function galeri()
    {
        return $this->hasMany(GaleriPortofolio::class, 'portofolio_id');
    }
}
