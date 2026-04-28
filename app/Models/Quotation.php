<?php
// =====================================================
// FILE: app/Models/Quotation.php
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Quotation extends Model
{
    protected $fillable = [
        'quo_number', 'token',
        'customer_name', 'customer_phone', 'customer_email', 'customer_address',
        'project_name', 'quo_date', 'valid_until', 'status',
        'ppn_enabled', 'ppn_rate', 'ppn_amount',
        'discount_global', 'subtotal', 'total_amount',
        'notes', 'terms', 'customer_notes',
        'sent_at', 'responded_at', 'sales_order_id', 'created_by',
    ];

    protected $casts = [
        'quo_date'        => 'date',
        'valid_until'     => 'date',
        'sent_at'         => 'datetime',
        'responded_at'    => 'datetime',
        'ppn_enabled'     => 'boolean',
        'ppn_rate'        => 'decimal:2',
        'ppn_amount'      => 'decimal:2',
        'discount_global' => 'decimal:2',
        'subtotal'        => 'decimal:2',
        'total_amount'    => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class)->orderBy('urutan');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public static function generateNumber(): string
    {
        $year   = now()->year;
        $prefix = "QUO-{$year}-";
        $last   = self::where('quo_number', 'like', "{$prefix}%")
            ->orderBy('quo_number', 'desc')->first();
        $next = $last ? ((int) substr($last->quo_number, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function generateToken(): string
    {
        do { $token = Str::random(32); }
        while (self::where('token', $token)->exists());
        return $token;
    }

    public function recalculate(): void
    {
        $subtotal      = $this->items->sum('subtotal');
        $afterDiscount = max(0, $subtotal - $this->discount_global);
        $ppnAmount     = $this->ppn_enabled ? $afterDiscount * ($this->ppn_rate / 100) : 0;
        $this->subtotal     = $subtotal;
        $this->ppn_amount   = $ppnAmount;
        $this->total_amount = $afterDiscount + $ppnAmount;
    }

    public function getPublicUrlAttribute(): string
    {
        return route('quotation.show', $this->token);
    }

    public function getWhatsappUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->customer_phone ?? '');
        if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
        $msg = urlencode(
            "Halo {$this->customer_name},\n\n" .
            "Berikut penawaran harga untuk *{$this->project_name}*.\n\n" .
            "📋 No: *{$this->quo_number}*\n" .
            "💰 Total: *Rp " . number_format($this->total_amount, 0, ',', '.') . "*\n" .
            "📅 Berlaku: *{$this->valid_until->format('d M Y')}*\n\n" .
            "Buka link berikut untuk melihat detail & konfirmasi:\n" .
            $this->public_url . "\n\nTerima kasih 🙏"
        );
        return "https://wa.me/{$phone}?text={$msg}";
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->valid_until->isPast() && !in_array($this->status, ['approved', 'converted']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'sent'      => 'Terkirim',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            'revised'   => 'Minta Revisi',
            'expired'   => 'Expired',
            'converted' => 'Jadi SO',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'secondary',
            'sent'      => 'info',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'revised'   => 'warning',
            'expired'   => 'dark',
            'converted' => 'primary',
            default     => 'secondary',
        };
    }
}