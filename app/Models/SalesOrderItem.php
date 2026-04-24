<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SalesOrderItem extends Model
{
    protected $fillable = [
        'sales_order_id',
        'product_id',
        'qty',
        'harga_satuan',
        'subtotal',
        'notes',
    ];
    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(GudangProduct::class, 'product_id');
    }
    public function serials(): HasMany
    {
        return $this->hasMany(InventorySerial::class);
    }
}