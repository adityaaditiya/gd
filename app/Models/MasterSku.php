<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSku extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}
