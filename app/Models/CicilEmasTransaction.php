<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasInstallment> $installments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasTransactionItem> $items
 */
class CicilEmasTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_cicilan',
        'nasabah_id',
        'package_id',
        'pabrikan',
        'berat_gram',
        'kadar',
        'harga_emas',
        'dp_percentage',
        'estimasi_uang_muka',
        'pokok_pembiayaan',
        'margin_percentage',
        'margin_amount',
        'administrasi',
        'total_pembiayaan',
        'tenor_bulan',
        'besaran_angsuran',
        'option_id',
        'option_label',
        'dibatalkan_pada',
        'alasan_pembatalan',
        'dibatalkan_oleh',
    ];

    protected $casts = [
        'berat_gram' => 'float',
        'harga_emas' => 'float',
        'dp_percentage' => 'float',
        'estimasi_uang_muka' => 'float',
        'pokok_pembiayaan' => 'float',
        'margin_percentage' => 'float',
        'margin_amount' => 'float',
        'administrasi' => 'float',
        'total_pembiayaan' => 'float',
        'tenor_bulan' => 'integer',
        'besaran_angsuran' => 'float',
        'dibatalkan_pada' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(CicilEmasInstallment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CicilEmasTransactionItem::class, 'transaction_id');
    }

    public function pembatal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }
}
