<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'no_invoice',
        'tgl_invoice',
        'jatuh_tempo',
        'status',
        'total',
        'customer_name',
        'notif_h3_sent',
        'notif_h1_sent',
        'notif_overdue_sent',
    ];

    protected $casts = [
        'jatuh_tempo'        => 'date',
        'notif_h3_sent'      => 'boolean',
        'notif_h1_sent'      => 'boolean',
        'notif_overdue_sent' => 'boolean',
    ];

    // Relasi ke customer (jika ada model Customer)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}