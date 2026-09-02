<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananOnline extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan_onlines';

    protected $fillable = [
        'no_order',
        'pelanggan',
        'platform',
        'tipe_kertas',
        'jumlah_lembar',
        'total',
        'status',
        'catatan',
    ];

    protected $casts = [
        'jumlah_lembar' => 'integer',
        'total'         => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    const PLATFORMS = ['WA', 'Tokopedia', 'Shopee', 'Instagram'];

    const STATUSES = ['Proses', 'Selesai', 'Dibatalkan'];

    const STATUS_NEXT = [
        'Proses'     => 'Selesai',
        'Selesai'    => 'Dibatalkan',
        'Dibatalkan' => 'Proses',
    ];

    // ── Scopes ──────────────────────────────────────────

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('pelanggan', 'like', "%{$keyword}%")
              ->orWhere('no_order',  'like', "%{$keyword}%");
        });
    }

    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    // ── Accessors ────────────────────────────────────────

    public function getTotalRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getTanggalAttribute(): string
    {
        return $this->created_at->format('d/m/Y');
    }

    // ── Helpers ──────────────────────────────────────────

    public static function generateNoOrder(): string
    {
        $last = static::withTrashed()->count() + 1;
        return 'ORD-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function nextStatus(): string
    {
        return self::STATUS_NEXT[$this->status] ?? 'Proses';
    }

    public function isProses(): bool    { return $this->status === 'Proses'; }
    public function isSelesai(): bool   { return $this->status === 'Selesai'; }
    public function isDibatalkan(): bool{ return $this->status === 'Dibatalkan'; }
}