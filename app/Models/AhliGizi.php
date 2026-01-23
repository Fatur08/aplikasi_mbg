<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AhliGizi extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "ahli_gizi";
    protected $primaryKey = 'id_ahli_gizi';

    protected $fillable = [
        'nama_ahli_gizi',
        'nomor_dapur_ahli_gizi',
        'email_ahli_gizi',
        'alamat_ahli_gizi',
        'no_hp_ahli_gizi',
        'foto_ahli_gizi',
        'status_validasi_ahli_gizi'
    ];
}
