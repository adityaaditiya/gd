<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiKas extends Model
{
    use HasFactory;

    protected $table = 'kas_mutasi';

    protected $fillable = [
        'jadwal_lelang_id',
        'tanggal',
        'referensi',
        'tipe',
        'jumlah',
        'sumber',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function jadwalLelang(): BelongsTo
    {
        return $this->belongsTo(JadwalLelang::class, 'jadwal_lelang_id');
    }
}
