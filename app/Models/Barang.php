<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barcode',
        'nama_barang',
        'kode_intern',
        'kode_baki',
        'kode_jenis',
        'berat',
        'harga',
        'kadar',
        'kode_group',
    ];

    protected $casts = [
        'berat' => 'decimal:3',
        'harga' => 'decimal:2',
        'kadar' => 'decimal:2',
    ];

    public function cicilEmasItems(): HasMany
    {
        return $this->hasMany(CicilEmasTransactionItem::class, 'barang_id');
    }

    public function getIsLockedAttribute(): bool
    {
        $activeUsageCount = $this->getAttribute('active_cicil_emas_usage_count');

        if ($activeUsageCount !== null) {
            return (int) $activeUsageCount > 0;
        }

        return $this->cicilEmasItems()
            ->whereHas('transaction', fn ($query) => $query->whereNull('dibatalkan_pada'))
            ->exists();
    }
}
