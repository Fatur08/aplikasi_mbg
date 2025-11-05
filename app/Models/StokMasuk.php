<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = "stok_masuk";
    protected $primaryKey = 'id_stok_masuk';

    protected $fillable = [
        'id_stok_masuk',
        'id_bahan',
        'nomor_dapur_stok_masuk',
        'tanggal_masuk',
        'jumlah_masuk',
        'sumber_stok_masuk',
        'keterangan_stok_masuk'
    ];
}
