<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'invoice_number',
        'product_name',
        'customer_name',
        'customer_phone',
        'quantity',
        'modal_per_unit',
        'selling_price_per_unit',
        'shipping_cost',
        'additional_cost',
        'total_modal',
        'total_selling',
        'total_cost',
        'profit',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'integer',
        'modal_per_unit' => 'decimal:2',
        'selling_price_per_unit' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'additional_cost' => 'decimal:2',
        'total_modal' => 'decimal:2',
        'total_selling' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    /**
     * Boot method to auto-calculate totals
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaction) {
            $transaction->calculateTotals();
        });
    }

    /**
     * Calculate all totals automatically
     */
    public function calculateTotals()
    {
        // Total modal = quantity * modal per unit
        $this->total_modal = $this->quantity * $this->modal_per_unit;
        
        // Total penjualan = quantity * harga jual per unit
        $this->total_selling = $this->quantity * $this->selling_price_per_unit;
        
        // Total biaya = total modal + ongkir + biaya tambahan
        $this->total_cost = $this->total_modal + $this->shipping_cost + $this->additional_cost;
        
        // Profit = total penjualan - total biaya
        $this->profit = $this->total_selling - $this->total_cost;
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $lastInvoice = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $number = 1;
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $number = $lastNumber + 1;
        }
        
        return $prefix . '-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope untuk transaksi bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', date('m'))
                    ->whereYear('transaction_date', date('Y'));
    }

    /**
     * Scope untuk transaksi tahun ini
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('transaction_date', date('Y'));
    }

    /**
     * Get payment status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return [
            'pending' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger',
        ][$this->payment_status] ?? 'secondary';
    }

    /**
     * Get payment status label
     */
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan',
        ][$this->payment_status] ?? 'Unknown';
    }
}