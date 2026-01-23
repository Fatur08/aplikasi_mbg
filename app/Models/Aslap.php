<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Aslap extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "aslap";
    protected $primaryKey = 'id_aslap';

    protected $fillable = [
        'nama_aslap',
        'nomor_dapur_aslap',
        'email_aslap',
        'alamat_aslap',
        'no_hp_aslap',
        'foto_aslap',
        'status_validasi_aslap'
    ];
}