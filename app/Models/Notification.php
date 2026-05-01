<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'tipe',
        'judul',
        'pesan',
        'invoice_id',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Scope: hanya yang belum dibaca
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    // Label warna per tipe (untuk badge di blade)
    public function warnaLabel(): string
    {
        return match($this->tipe) {
            'h3'      => 'warning',
            'h1'      => 'orange',
            'overdue' => 'danger',
        };
    }

    // Icon per tipe
    public function icon(): string
    {
        return match($this->tipe) {
            'h3'      => '🕐',
            'h1'      => '⚠️',
            'overdue' => '🚨',
        };
    }
}