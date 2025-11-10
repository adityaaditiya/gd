<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangJaminan extends Model
{
    use HasFactory;

    protected $table = 'barang_jaminan';

    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'transaksi_id',
        'pegawai_penaksir_id',
        'jenis_barang',
        'merek',
        'usia_barang_thn',
        'hps',
        'nilai_taksiran',
        'kondisi_fisik',
        'kelengkapan',
        'foto_1',
        'foto_2',
        'foto_3',
        'foto_4',
        'foto_5',
        'foto_6',
    ];

    protected $casts = [
        'hps' => 'decimal:2',
        'nilai_taksiran' => 'decimal:2',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiGadai::class, 'transaksi_id', 'transaksi_id');
    }

    public function penaksir()
    {
        return $this->belongsTo(User::class, 'pegawai_penaksir_id');
    }
}
