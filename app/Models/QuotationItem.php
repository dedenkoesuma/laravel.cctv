<?php
// =====================================================
// FILE: app/Models/QuotationItem.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id', 'nama_item', 'deskripsi',
        'qty', 'satuan', 'harga_satuan', 'discount', 'subtotal', 'urutan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'discount'     => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Hitung subtotal item: (harga * qty) * (1 - diskon%)
     */
    public function hitungSubtotal(): void
    {
        $this->subtotal = $this->harga_satuan * $this->qty * (1 - $this->discount / 100);
    }
}