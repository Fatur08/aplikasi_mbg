<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Akuntan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "akuntan";
    protected $primaryKey = 'id_akuntan';

    protected $fillable = [
        'nama_akuntan',
        'nomor_dapur_akuntan',
        'email_akuntan',
        'alamat_akuntan',
        'no_hp_akuntan',
        'foto_akuntan',
        'status_validasi_akuntan'
    ];
}