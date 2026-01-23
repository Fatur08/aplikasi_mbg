<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SPPI extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "sppi";
    protected $primaryKey = 'id_sppi';

    protected $fillable = [
        'nama_sppi',
        'nomor_dapur_sppi',
        'email_sppi',
        'alamat_sppi',
        'no_hp_sppi',
        'foto_sppi',
        'status_validasi_sppi'
    ];
}
