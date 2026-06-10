<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModalPaket extends Model
{
    protected $fillable = ['nama'];

    public function items(): HasMany
    {
        return $this->hasMany(ModalPaketItem::class)->orderBy('urutan');
    }

    public function getTotalModalAttribute(): int
    {
        return $this->items->sum(function ($item) {
            $gross = $item->qty * $item->harga_beli;
            return (int) ($gross - ($gross * $item->diskon / 100));
        });
    }

    public function getSubtotalAttribute(): int
    {
        return $this->items->sum(fn($item) => $item->qty * $item->harga_beli);
    }

    public function getTotalHematAttribute(): int
    {
        return $this->subtotal - $this->total_modal;
    }
}