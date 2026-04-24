<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class InventorySerial extends Model
{
    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
        'barang_masuk_id',
        'sales_order_item_id',
        'barang_keluar_id',
        'notes',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(GudangProduct::class, 'product_id');
    }
    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }
    public function salesOrderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class);
    }
    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class);
    }
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'Tersedia',
            'reserved'  => 'Dipesan',
            'sold'      => 'Terjual',
            default     => ucfirst($this->status),
        };
    }
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'success',
            'reserved'  => 'warning',
            'sold'      => 'danger',
            default     => 'secondary',
        };
    }
}