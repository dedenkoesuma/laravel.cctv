<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintInvoice extends Model
{
    protected $table = 'print_invoices';

    protected $fillable = [
        'no_invoice',
        'pelanggan',
        'deskripsi',
        'tgl_invoice',
        'jatuh_tempo',
        'total',
        'status',
        'pesanan_online_id',
        'catatan',
    ];

    protected $casts = [
        'tgl_invoice' => 'date',
        'jatuh_tempo' => 'date',
        'total'       => 'integer',
    ];

    const STATUSES = ['unpaid', 'lunas', 'batal'];

    const STATUS_LABELS = [
        'unpaid' => 'Belum dibayar',
        'lunas'  => 'Lunas',
        'batal'  => 'Dibatalkan',
    ];

    public function pesananOnline()
    {
        return $this->belongsTo(PesananOnline::class, 'pesanan_online_id');
    }

    // ── Scopes ──────────────────────────────────────────
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('no_invoice', 'like', "%{$keyword}%")
              ->orWhere('pelanggan', 'like', "%{$keyword}%");
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->whereNotNull('jatuh_tempo')
            ->whereDate('jatuh_tempo', '<', now()->toDateString());
    }

    // ── Accessors ────────────────────────────────────────
    public function getTotalRupiahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total, 0, ',', '.');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'unpaid'
            && $this->jatuh_tempo
            && $this->jatuh_tempo->isPast();
    }

    // ── Helpers ──────────────────────────────────────────
    public static function generateNoInvoice(): string
    {
        $last = static::count() + 1;
        return 'INV-' . now()->format('Ym') . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}