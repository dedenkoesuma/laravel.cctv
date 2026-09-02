<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananOnline extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan_onlines';

    // ── Auto-sync ke Uang Masuk ───────────────────────────
    // Setiap pesanan berstatus "Selesai" otomatis punya 1 entry
    // kembar di uang_kas (jenis=masuk). Diedit/dibatalkan/dihapus
    // di sini akan ikut menyesuaikan entry tersebut.

    protected static function booted(): void
    {
        static::created(function (PesananOnline $pesanan) {
            if ($pesanan->status === 'Selesai') {
                static::syncUangMasuk($pesanan);
            }
        });

        static::updated(function (PesananOnline $pesanan) {
            $statusChanged = $pesanan->wasChanged('status');

            $becameSelesai   = $statusChanged && $pesanan->status === 'Selesai';
            $noLongerSelesai = $statusChanged
                && $pesanan->getOriginal('status') === 'Selesai'
                && $pesanan->status !== 'Selesai';
            $stillSelesaiButDataChanged = ! $statusChanged
                && $pesanan->status === 'Selesai'
                && ($pesanan->wasChanged('total') || $pesanan->wasChanged('pelanggan') || $pesanan->wasChanged('no_order'));

            if ($becameSelesai || $stillSelesaiButDataChanged) {
                static::syncUangMasuk($pesanan);
            } elseif ($noLongerSelesai) {
                static::removeUangMasuk($pesanan);
            }
        });

        static::deleting(function (PesananOnline $pesanan) {
            if (! $pesanan->uang_kas_id) {
                return;
            }

            if ($pesanan->isForceDeleting()) {
                UangKas::withTrashed()->find($pesanan->uang_kas_id)?->forceDelete();
            } else {
                UangKas::find($pesanan->uang_kas_id)?->delete(); // soft delete
            }
        });

        static::restored(function (PesananOnline $pesanan) {
            if ($pesanan->uang_kas_id) {
                UangKas::onlyTrashed()->find($pesanan->uang_kas_id)?->restore();
            }
        });
    }

    protected static function syncUangMasuk(PesananOnline $pesanan): void
    {
        $data = [
            'jenis'      => 'masuk',
            'tanggal'    => optional($pesanan->created_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            'kategori'   => 'Penjualan Cetak',
            'keterangan' => "Pesanan {$pesanan->no_order} - {$pesanan->pelanggan}",
            'jumlah'     => $pesanan->total,
            'otomatis'   => true,
        ];

        if ($pesanan->uang_kas_id) {
            // Update via query builder (bukan Eloquent save) supaya tidak memicu event lain.
            UangKas::where('id', $pesanan->uang_kas_id)->update($data);
            return;
        }

        $kas = UangKas::create($data);

        static::withTrashed()->where('id', $pesanan->id)->update(['uang_kas_id' => $kas->id]);
        $pesanan->uang_kas_id = $kas->id;
    }

    protected static function removeUangMasuk(PesananOnline $pesanan): void
    {
        if (! $pesanan->uang_kas_id) {
            return;
        }

        UangKas::find($pesanan->uang_kas_id)?->delete(); // soft delete, riwayat tetap ada

        static::withTrashed()->where('id', $pesanan->id)->update(['uang_kas_id' => null]);
        $pesanan->uang_kas_id = null;
    }

    public function uangKas()
    {
        return $this->belongsTo(UangKas::class);
    }

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