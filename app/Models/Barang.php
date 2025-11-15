<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barcode',
        'nama_barang',
        'kode_intern',
        'kode_group',
        'berat',
        'harga',
        'kadar',
        'sku',
    ];

    protected $casts = [
        'berat' => 'decimal:3',
        'harga' => 'decimal:2',
        'kadar' => 'decimal:2',
    ];
}
