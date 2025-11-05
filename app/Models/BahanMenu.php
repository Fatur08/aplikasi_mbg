<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanMenu extends Model
{
    use HasFactory;

    protected $table = "bahan_menu";

    protected $fillable = [
        'id_bahan_menu',
        'id_bahan',
        'id_menu_harian',
        'tanggal_bahan_menu',
        'nama_bahan_menu',
        'jumlah_bahan_menu',
        'satuan_bahan_menu'
    ];
}
