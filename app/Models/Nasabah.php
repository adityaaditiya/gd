<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        do {
            $code = 'MBR-' . Str::upper(Str::random(6));
        } while (static::where('kode_member', $code)->exists());

        return $code;
    }

    public function transaksiGadai()
    {
        return $this->hasMany(TransaksiGadai::class);
    }
}
