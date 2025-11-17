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
        'tenor_hari',
        'jatuh_tempo_awal',
        'biaya_admin',
    ];
}
