<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySerialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'serial_number',
        'status',
        'customer_name',
        'customer_phone',
        'selling_price',
        'sold_date',
        'exit_date',
        'exit_reason',
        'notes',
        'scanned_by',
        'entry_date',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'sold_date' => 'datetime',
        'exit_date' => 'datetime',
        'entry_date' => 'datetime',
    ];

    /**
     * Relationship: Serial number belongs to inventory item
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /**
     * Scope: Get only in-stock serial numbers
     */
    public function scopeInStock($query)
    {
        return $query->where('status', 'in_stock');
    }

    /**
     * Scope: Get sold serial numbers
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope: Get damaged serial numbers
     */
    public function scopeDamaged($query)
    {
        return $query->where('status', 'damaged');
    }

    /**
     * Mark this serial number as sold
     */
    public function markAsSold($customerName, $customerPhone, $sellingPrice, $notes = null)
    {
        $this->update([
            'status' => 'sold',
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'selling_price' => $sellingPrice,
            'sold_date' => now(),
            'notes' => $notes,
        ]);

        // Decrement stock in parent item
        $this->inventoryItem->decrementStock();
    }

    /**
     * Mark this serial number as damaged
     */
    public function markAsDamaged($notes = null)
    {
        $this->update([
            'status' => 'damaged',
            'exit_date' => now(),
            'exit_reason' => 'damaged',
            'notes' => $notes,
        ]);

        // Decrement stock in parent item
        $this->inventoryItem->decrementStock();
    }

    /**
     * Mark this serial number as returned
     */
    public function markAsReturned($notes = null)
    {
        $this->update([
            'status' => 'returned',
            'exit_date' => now(),
            'exit_reason' => 'returned',
            'notes' => $notes,
        ]);

        // Decrement stock in parent item
        $this->inventoryItem->decrementStock();
    }

    /**
     * Return to stock (restock)
     */
    public function returnToStock()
    {
        $oldStatus = $this->status;
        
        $this->update([
            'status' => 'in_stock',
            'customer_name' => null,
            'customer_phone' => null,
            'selling_price' => null,
            'sold_date' => null,
            'exit_date' => null,
            'exit_reason' => null,
        ]);

        // Increment stock if it was previously out
        if (in_array($oldStatus, ['sold', 'damaged', 'returned'])) {
            $this->inventoryItem->incrementStock();
        }
    }
}