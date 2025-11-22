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

    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_SETTLED = 'settled';
    public const STATUS_COMPLETED = 'completed';

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
        'nomor_pelunasan',
        'tanggal_pelunasan',
        'biaya_ongkos_kirim',
        'pelunasan_dipercepat',
        'penyelesaian_market_price',
        'penyelesaian_purchase_price',
        'penyelesaian_penalty_amount',
        'penyelesaian_down_payment',
        'penyelesaian_pokok_bersih',
        'penyelesaian_total_margin',
        'penyelesaian_total_harga_jual',
        'penyelesaian_surplus_defisit',
        'penyelesaian_kewajiban_pengembalian',
        'penyelesaian_keterangan',
        'penyelesaian_completed_at',
        'status',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
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
        'cancelled_at' => 'datetime',
        'tanggal_pelunasan' => 'datetime',
        'biaya_ongkos_kirim' => 'float',
        'pelunasan_dipercepat' => 'boolean',
        'penyelesaian_market_price' => 'float',
        'penyelesaian_purchase_price' => 'float',
        'penyelesaian_penalty_amount' => 'float',
        'penyelesaian_down_payment' => 'float',
        'penyelesaian_pokok_bersih' => 'float',
        'penyelesaian_total_margin' => 'float',
        'penyelesaian_total_harga_jual' => 'float',
        'penyelesaian_surplus_defisit' => 'float',
        'penyelesaian_kewajiban_pengembalian' => 'float',
        'penyelesaian_completed_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
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

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function isCancelable(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
