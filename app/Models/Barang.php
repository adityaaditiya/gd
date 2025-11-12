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
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}
