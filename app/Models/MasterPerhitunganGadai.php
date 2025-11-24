<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPerhitunganGadai extends Model
{
    use HasFactory;

    protected $table = 'master_perhitungan_gadai';

    protected $fillable = [
        'type',
        'range_awal',
        'range_akhir',
        'tarif_bunga_harian',
        'skema_bunga',
        'tarif_bunga_per_periode',
        'periode_hari',
        'tenor_hari',
        'jatuh_tempo_awal',
        'biaya_admin',
    ];
}
