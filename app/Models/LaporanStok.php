<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanStok extends Model
{
    use HasFactory;

    protected $table = "stok";

    protected $fillable = [
        'id_stok',
        'nomor_dapur_stok',
        'nama_kepala_dapur',
        'nama_stok',
        'tanggal_masuk_stok',
        'jumlah_stok_masuk',
        'tanggal_keluar_stok',
        'jumlah_stok_keluar',
        'sisa_stok',
        'satuan_stok',
        'sumber_masuk_stok',
        'tanggal_kadaluarsa_stok',
        'status_stok',
        'keterangan_stok'
    ];
}
