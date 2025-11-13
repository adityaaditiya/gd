<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CicilEmasTransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'barang_id',
        'kode_barcode',
        'nama_barang',
        'kode_intern',
        'kode_group',
        'berat',
        'harga',
    ];

    protected $casts = [
        'berat' => 'float',
        'harga' => 'float',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CicilEmasTransaction::class, 'transaction_id');
    }
}
