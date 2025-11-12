<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerpanjanganGadai extends Model
{
    use HasFactory;

    protected $table = 'perpanjangan_gadai';

    protected $primaryKey = 'perpanjangan_id';

    protected $fillable = [
        'transaksi_gadai_id',
        'pegawai_id',
        'tanggal_perpanjangan',
        'tanggal_mulai_baru',
        'tanggal_jatuh_tempo_baru',
        'tenor_sebelumnya',
        'tenor_baru',
        'bunga_dibayar',
        'biaya_admin',
        'biaya_titip',
        'total_bayar',
        'pokok_pinjaman',
        'tanggal_mulai_sebelumnya',
        'tanggal_jatuh_tempo_sebelumnya',
        'catatan',
        'dibatalkan_pada',
        'dibatalkan_oleh',
        'alasan_pembatalan',
    ];

    protected $casts = [
        'tanggal_perpanjangan' => 'datetime',
        'tanggal_mulai_baru' => 'date',
        'tanggal_jatuh_tempo_baru' => 'date',
        'tanggal_mulai_sebelumnya' => 'date',
        'tanggal_jatuh_tempo_sebelumnya' => 'date',
        'tenor_sebelumnya' => 'integer',
        'tenor_baru' => 'integer',
        'bunga_dibayar' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'biaya_titip' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'pokok_pinjaman' => 'decimal:2',
        'dibatalkan_pada' => 'datetime',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiGadai::class, 'transaksi_gadai_id', 'transaksi_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function pembatal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }
}
