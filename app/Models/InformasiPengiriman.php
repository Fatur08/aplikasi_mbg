<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiPengiriman extends Model
{
    use HasFactory;

    protected $table = "distribusi";

    protected $fillable = [
        'id_distribusi',
        'nama_distributor',
        'dapur_kecamatan_distributor',
        'tanggal_distribusi',
        'tujuan_distribusi',
        'jumlah_paket',
        'menu_makanan',
        'bukti_pengiriman',
        'status_distribusi'
    ];
}
