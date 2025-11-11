<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class JadwalLelang extends Model
{
    use HasFactory;

    protected $table = 'jadwal_lelang';

    protected $fillable = [
        'barang_id',
        'transaksi_id',
        'tanggal_rencana',
        'lokasi',
        'petugas',
        'harga_limit',
        'estimasi_biaya',
        'status',
        'catatan',
        'hasil_status',
        'harga_laku',
        'biaya_lelang',
        'distribusi_perusahaan',
        'distribusi_nasabah',
        'piutang_sisa',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
        'tanggal_selesai' => 'datetime',
        'harga_limit' => 'decimal:2',
        'estimasi_biaya' => 'decimal:2',
        'harga_laku' => 'decimal:2',
        'biaya_lelang' => 'decimal:2',
        'distribusi_perusahaan' => 'decimal:2',
        'distribusi_nasabah' => 'decimal:2',
        'piutang_sisa' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangJaminan::class, 'barang_id', 'barang_id');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiGadai::class, 'transaksi_id', 'transaksi_id');
    }

    public function mutasiKas(): HasMany
    {
        return $this->hasMany(MutasiKas::class, 'jadwal_lelang_id');
    }

    public function markAsCompleted(Carbon $completedAt, array $distribution): void
    {
        $this->forceFill(array_merge($distribution, [
            'status' => 'Selesai',
            'tanggal_selesai' => $completedAt,
        ]))->save();
    }
}
