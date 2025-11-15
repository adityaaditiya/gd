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
        'transaksi_gadai_id',
        'cicil_emas_transaction_id',
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

    public function transaksiGadai(): BelongsTo
    {
        return $this->belongsTo(TransaksiGadai::class, 'transaksi_gadai_id', 'transaksi_id');
    }

    public function cicilEmasTransaction(): BelongsTo
    {
        return $this->belongsTo(CicilEmasTransaction::class, 'cicil_emas_transaction_id');
    }
}
