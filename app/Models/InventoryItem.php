<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number', // Deprecated - will be removed
        'product_name',
        'brand',
        'category',
        'description',
        'purchase_price',
        'selling_price',
        'supplier',
        'warehouse_location',
        'stock',
        'status',
        'entry_date',
        'exit_date',
        'exit_reason',
        'customer_name',
        'customer_phone',
        'notes',
        'scanned_by',
        'is_grouped',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'entry_date' => 'datetime',
        'exit_date' => 'datetime',
        'stock' => 'integer',
        'is_grouped' => 'boolean',
    ];

    /**
     * ========================================
     * RELATIONSHIPS
     * ========================================
     */

    /**
     * Inventory item has many serial numbers
     */
    public function serialNumbers()
    {
        return $this->hasMany(InventorySerialNumber::class, 'inventory_item_id');
    }

    /**
     * Get only in-stock serial numbers
     */
    public function inStockSerials()
    {
        return $this->serialNumbers()->where('status', 'in_stock');
    }

    /**
     * Get sold serial numbers
     */
    public function soldSerials()
    {
        return $this->serialNumbers()->where('status', 'sold');
    }

    /**
     * Get damaged serial numbers
     */
    public function damagedSerials()
    {
        return $this->serialNumbers()->where('status', 'damaged');
    }

    /**
     * ========================================
     * SCOPES
     * ========================================
     */

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '>', 0)->where('stock', '<=', $threshold);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '=', 0);
    }

    public function scopeGrouped($query)
    {
        return $query->where('is_grouped', true);
    }

    /**
     * ========================================
     * STOCK MANAGEMENT METHODS
     * ========================================
     */

    /**
     * Increment stock (when new items come in)
     */
    public function incrementStock($quantity = 1)
    {
        $this->increment('stock', $quantity);
        $this->update(['status' => 'in_stock']);
        return $this;
    }

    /**
     * Decrement stock (when items go out)
     */
    public function decrementStock($quantity = 1)
    {
        $newStock = max(0, $this->stock - $quantity);
        $this->update([
            'stock' => $newStock,
            'status' => $newStock > 0 ? 'in_stock' : 'out_of_stock'
        ]);
        return $this;
    }

    /**
     * Set stock to specific number
     */
    public function setStock($quantity)
    {
        $this->update([
            'stock' => max(0, $quantity),
            'status' => $quantity > 0 ? 'in_stock' : 'out_of_stock'
        ]);
        return $this;
    }

    /**
     * Recalculate stock from serial numbers
     * Useful for data integrity check
     */
    public function recalculateStock()
    {
        $inStockCount = $this->serialNumbers()->where('status', 'in_stock')->count();
        $this->setStock($inStockCount);
        return $this;
    }

    /**
     * ========================================
     * HELPER METHODS
     * ========================================
     */

    /**
     * Get total number of serial numbers (all statuses)
     */
    public function getTotalSerialsAttribute()
    {
        return $this->serialNumbers()->count();
    }

    /**
     * Get available stock (in_stock status)
     */
    public function getAvailableStockAttribute()
    {
        return $this->inStockSerials()->count();
    }

    /**
     * Get sold count
     */
    public function getSoldCountAttribute()
    {
        return $this->soldSerials()->count();
    }

    /**
     * Get damaged count
     */
    public function getDamagedCountAttribute()
    {
        return $this->damagedSerials()->count();
    }

    /**
     * Check if product is low stock
     */
    public function isLowStock($threshold = 10)
    {
        return $this->stock > 0 && $this->stock <= $threshold;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->stock == 0;
    }

    /**
     * Get stock status label
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'Out of Stock';
        } elseif ($this->isLowStock()) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    /**
     * Get stock status color (for UI)
     */
    public function getStockStatusColorAttribute()
    {
        if ($this->stock == 0) {
            return 'danger';
        } elseif ($this->isLowStock()) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    /**
     * ========================================
     * STATIC METHODS
     * ========================================
     */

    /**
     * Find or create product (grouped)
     * Used when scanning new items
     */
    public static function findOrCreateProduct($productData)
    {
        return static::firstOrCreate(
            [
                'product_name' => $productData['product_name'],
                'brand' => $productData['brand'],
                'supplier' => $productData['supplier'] ?? null,
            ],
            [
                'category' => $productData['category'] ?? null,
                'description' => $productData['description'] ?? null,
                'purchase_price' => $productData['purchase_price'],
                'selling_price' => $productData['selling_price'] ?? 0,
                'warehouse_location' => $productData['warehouse_location'] ?? null,
                'stock' => 0, // Will be incremented when serial numbers added
                'status' => 'in_stock',
                'entry_date' => now(),
                'is_grouped' => true,
            ]
        );
    }

    /**
     * Add serial number to this product
     */
    public function addSerialNumber($serialNumber, $additionalData = [])
    {
        // Check if serial already exists
        $existing = InventorySerialNumber::where('serial_number', $serialNumber)->first();
        if ($existing) {
            throw new \Exception("Serial number {$serialNumber} already exists");
        }

        // Create serial number
        $serial = $this->serialNumbers()->create(array_merge([
            'serial_number' => $serialNumber,
            'status' => 'in_stock',
            'entry_date' => now(),
            'scanned_by' => session('admin_name', 'System'),
        ], $additionalData));

        // Increment stock
        $this->incrementStock();

        return $serial;
    }
}