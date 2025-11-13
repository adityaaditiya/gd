<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CicilEmasInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'cicil_emas_transaction_id',
        'sequence',
        'due_date',
        'amount',
        'penalty_rate',
        'penalty_amount',
        'paid_amount',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'float',
        'penalty_rate' => 'float',
        'penalty_amount' => 'float',
        'paid_amount' => 'float',
        'paid_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CicilEmasTransaction::class, 'cicil_emas_transaction_id');
    }
}
