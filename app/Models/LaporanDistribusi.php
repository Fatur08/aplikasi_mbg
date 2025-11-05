<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanDistribusi extends Model
{
    use HasFactory;

    protected $table = "distribusi";

    protected $fillable = [
        'id_distribusi',
        'nomor_dapur_distribusi',
        'nama_distributor',
        'kecamatan_sekolah',
        'tanggal_distribusi',
        'tujuan_distribusi',
        'jumlah_paket',
        'menu_makanan',
        'bukti_pengiriman',
        'status_distribusi'
    ];
}
