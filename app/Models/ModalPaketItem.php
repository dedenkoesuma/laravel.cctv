<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModalPaketItem extends Model
{
    protected $fillable = ['modal_paket_id', 'nama_item', 'qty', 'harga_beli', 'diskon', 'urutan'];

    protected $casts = [
        'diskon' => 'float',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(ModalPaket::class, 'modal_paket_id');
    }

    public function getTotalModalItemAttribute(): int
    {
        $gross = $this->qty * $this->harga_beli;
        return (int) ($gross - ($gross * $this->diskon / 100));
    }
}