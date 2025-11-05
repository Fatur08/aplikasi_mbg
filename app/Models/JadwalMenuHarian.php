<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMenuHarian extends Model
{
    use HasFactory;

    protected $table = "jadwal_menu_harian";

    protected $fillable = [
        'id_jadwal_menu_harian',
        'id_menu_harian',
        'tanggal_jadwal_menu_harian',
        'jumlah_porsi_menu_harian',
        'nomor_dapur',
        'status_jadwal_menu_harian'
    ];
}
