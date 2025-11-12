<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasInstallment> $installments
 */
class CicilEmasTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'package_id',
        'pabrikan',
        'berat_gram',
        'kadar',
        'harga_emas',
        'dp_percentage',
        'estimasi_uang_muka',
        'tenor_bulan',
        'besaran_angsuran',
        'option_id',
        'option_label',
    ];

    protected $casts = [
        'berat_gram' => 'float',
        'harga_emas' => 'float',
        'dp_percentage' => 'float',
        'estimasi_uang_muka' => 'float',
        'tenor_bulan' => 'integer',
        'besaran_angsuran' => 'float',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(CicilEmasInstallment::class);
    }
}
