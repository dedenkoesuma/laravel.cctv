<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuijiePageSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'products_count',
        'clients_count',
        'satisfaction_rate',
        'is_active'
    ];

    protected $casts = [
        'products_count' => 'integer',
        'clients_count' => 'integer',
        'satisfaction_rate' => 'integer',
        'is_active' => 'boolean'
    ];
}