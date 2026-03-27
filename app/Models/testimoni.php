<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';

    protected $fillable = [
        'nama_klien',
        'perusahaan',
        'foto',
        'konten_testimoni',
        'rating',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}