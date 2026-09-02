<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UangKas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'uang_kas';

    protected $fillable = [
        'jenis',
        'tanggal',
        'kategori',
        'keterangan',
        'jumlah',
        'catatan',
        'otomatis',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'jumlah'   => 'integer',
        'otomatis' => 'boolean',
    ];

    const KATEGORI_MASUK = [
        'Penjualan Cetak',
        'Modal Tambahan',
        'Piutang Tertagih',
        'Lain-lain',
    ];

    const KATEGORI_KELUAR = [
        'Belanja Bahan Baku',
        'Operasional',
        'Gaji Karyawan',
        'Sewa Tempat',
        'Lain-lain',
    ];

    // ── Scopes ──────────────────────────────────────────

    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('keterangan', 'like', "%{$keyword}%")
              ->orWhere('kategori', 'like', "%{$keyword}%");
        });
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                      ->whereYear('tanggal', now()->year);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // ── Accessors ────────────────────────────────────────

    public function getJumlahRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getTanggalFormattedAttribute(): string
    {
        return $this->tanggal->format('d/m/Y');
    }
}