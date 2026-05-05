<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrder extends Model
{
    protected $fillable = [
        'so_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_email',
        'so_date',
        'status',
        'notes',
        'total_amount',
        'created_by',
    ];

    protected $casts = [
        'so_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function serials()
    {
        return $this->hasManyThrough(InventorySerial::class, SalesOrderItem::class);
    }

    // Generate nomor SO otomatis: SO-2026-0001
    public static function generateSoNumber(): string
    {
        $year = now()->year;
        $prefix = "SO-{$year}-";
        $last = self::where('so_number', 'like', "{$prefix}%")
            ->orderBy('so_number', 'desc')
            ->first();

        $nextNum = 1;
        if ($last) {
            $lastNum = (int) substr($last->so_number, strlen($prefix));
            $nextNum = $lastNum + 1;
        }

        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'approved'  => 'Disetujui',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'secondary',
            'approved'  => 'success',
            'delivered' => 'primary',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}