<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransaksiGadai extends Model
{
    use HasFactory;

    protected $table = 'transaksi_gadai';

    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'no_sbg',
        'nasabah_id',
        'pegawai_kasir_id',
        'tanggal_gadai',
        'jatuh_tempo_awal',
        'tenor_hari',
        'tarif_bunga_harian',
        'total_bunga',
        'bunga_terutang_riil',
        'uang_pinjaman',
        'biaya_admin',
        'premi',
        'status_transaksi',
        'tanggal_batal',
        'alasan_batal',
        'pegawai_pembatal_id',
        'tanggal_pelunasan',
        'pokok_dibayar',
        'bunga_dibayar',
        'biaya_lain_dibayar',
        'total_pelunasan',
        'metode_pembayaran',
        'catatan_pelunasan',
        'pegawai_pelunasan_id',
    ];

    protected $casts = [
        'tanggal_gadai' => 'date',
        'jatuh_tempo_awal' => 'date',
        'tanggal_batal' => 'datetime',
        'tanggal_pelunasan' => 'datetime',
        'tenor_hari' => 'integer',
        'tarif_bunga_harian' => 'decimal:4',
        'total_bunga' => 'decimal:2',
        'bunga_terutang_riil' => 'decimal:2',
        'uang_pinjaman' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'premi' => 'decimal:2',
        'pokok_dibayar' => 'decimal:2',
        'bunga_dibayar' => 'decimal:2',
        'biaya_lain_dibayar' => 'decimal:2',
        'total_pelunasan' => 'decimal:2',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'pegawai_kasir_id');
    }

    public function pembatal()
    {
        return $this->belongsTo(User::class, 'pegawai_pembatal_id');
    }

    public function petugasPelunasan()
    {
        return $this->belongsTo(User::class, 'pegawai_pelunasan_id');
    }

    public function barangJaminan()
    {
        return $this->hasMany(BarangJaminan::class, 'transaksi_id', 'transaksi_id');
    }

    public function getActualDaysAttribute(): ?int
    {
        return $this->calculateActualDays();
    }

    public function getAccruedInterestAttribute(): ?float
    {
        if ($this->bunga_terutang_riil !== null) {
            return (float) $this->bunga_terutang_riil;
        }

        $computed = $this->computeBungaTerutangRiil();

        return $computed !== null ? round($computed, 2) : null;
    }

    public function getTotalPotonganAttribute(): float
    {
        $admin = (float) ($this->biaya_admin ?? 0);
        $premi = (float) ($this->premi ?? 0);

        $potongan = $admin + $premi;

        return round(max(0, $potongan), 2);
    }

    public function getUangCairAttribute(): ?float
    {
        if (array_key_exists('uang_cair', $this->attributes)) {
            return round((float) $this->attributes['uang_cair'], 2);
        }

        if ($this->uang_pinjaman === null) {
            return null;
        }

        return round(max(0, (float) $this->uang_pinjaman - $this->total_potongan), 2);
    }

    public function calculateActualDays(?Carbon $referenceDate = null): ?int
    {
        if (!$this->tanggal_gadai) {
            return null;
        }

        $start = $this->tanggal_gadai instanceof Carbon
            ? $this->tanggal_gadai->copy()->startOfDay()
            : Carbon::parse($this->tanggal_gadai)->startOfDay();

        $endSource = $referenceDate ?? ($this->tanggal_pelunasan ?? Carbon::today());
        $end = $endSource instanceof Carbon
            ? $endSource->copy()->startOfDay()
            : Carbon::parse($endSource)->startOfDay();

        if ($end->lessThan($start)) {
            return 1;
        }

        return max(1, $start->diffInDays($end) + 1);
    }

    public function computeBungaTerutangRiil(?Carbon $referenceDate = null): ?float
    {
        if ($this->status_transaksi === 'Batal') {
            return 0.0;
        }

        if ($this->uang_pinjaman === null) {
            return null;
        }

        $days = $this->calculateActualDays($referenceDate);

        if ($days === null) {
            return null;
        }

        $principal = (float) $this->uang_pinjaman;
        $dailyRate = (float) ($this->tarif_bunga_harian ?? 0.0015);

        if ($dailyRate <= 0) {
            $dailyRate = 0.0015;
        }

        return round($principal * $dailyRate * $days, 2);
    }

    public function refreshBungaTerutangRiil(?Carbon $referenceDate = null): void
    {
        $computed = $this->computeBungaTerutangRiil($referenceDate);

        if ($computed === null) {
            return;
        }

        $formatted = number_format($computed, 2, '.', '');

        if ($this->bunga_terutang_riil !== $formatted) {
            $this->forceFill([
                'bunga_terutang_riil' => $formatted,
            ])->saveQuietly();
        }

        $this->bunga_terutang_riil = $formatted;
    }
}
