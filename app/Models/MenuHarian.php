<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuHarian extends Model
{
    use HasFactory;

    protected $table = "menu_harian";

    protected $fillable = [
        'id_menu_harian',
        'nama_menu_harian'
    ];
}
