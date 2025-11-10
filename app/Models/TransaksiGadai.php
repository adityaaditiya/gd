<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiGadai extends Model
{
    use HasFactory;

    protected $table = 'transaksi_gadai';

    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'no_sbg',
        'nasabah_id',
        'pegawai_kasir_id',
        'tanggal_gadai',
        'jatuh_tempo_awal',
        'tenor_hari',
        'tarif_bunga_harian',
        'total_bunga',
        'uang_pinjaman',
        'biaya_admin',
        'status_transaksi',
    ];

    protected $casts = [
        'tanggal_gadai' => 'date',
        'jatuh_tempo_awal' => 'date',
        'tenor_hari' => 'integer',
        'tarif_bunga_harian' => 'decimal:4',
        'total_bunga' => 'decimal:2',
        'uang_pinjaman' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'pegawai_kasir_id');
    }

    public function barangJaminan()
    {
        return $this->hasMany(BarangJaminan::class, 'transaksi_id', 'transaksi_id');
    }
}
