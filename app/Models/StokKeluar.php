<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $table = "stok_keluar";

    protected $fillable = [
        'id_stok_keluar',
        'id_bahan',
        'nomor_dapur_stok_keluar',
        'tanggal_keluar',
        'jumlah_keluar',
        'tujuan_stok_keluar',
        'keterangan_stok_keluar'
    ];
}
