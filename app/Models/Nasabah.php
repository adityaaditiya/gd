<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'telepon',
        'kota',
        'kelurahan',
        'kecamatan',
        'alamat',
        'npwp',
        'id_lain',
        'nasabah_lama',
        'kode_member',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nasabah_lama' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    /**
     * Generate a unique kode member value.
     */
    public static function generateKodeMember(): string
    {
        $areaCode = str_pad(config('app.branch_code', '01'), 2, '0', STR_PAD_LEFT);
        $year = now()->format('y');
        $prefix = $areaCode . $year;

        $lastCode = static::where('kode_member', 'like', $prefix . '%')
            ->orderByDesc('kode_member')
            ->value('kode_member');

        $lastSequence = $lastCode
            ? (int) substr($lastCode, -6)
            : 0;

        $nextSequence = $lastSequence + 1;

        return $prefix . str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }

    public function transaksiGadai()
    {
        return $this->hasMany(TransaksiGadai::class);
    }
}
