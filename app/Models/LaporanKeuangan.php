<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = "keuangan";

    protected $fillable = [
        'id_laporan_keuangan',
        'tanggal_laporan_keuangan',
        'jenis_transaksi',
        'kategori_laporan_keuangan',
        'keterangan_laporan_keuangan',
        'jumlah_dana',
        'sisa_dana'
    ];
}
