<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DataAslap extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "aslap";
    protected $primaryKey = 'id_aslap';

    protected $fillable = [
        'nama_aslap',
        'peran_aslap',
        'no_hp_aslap',
        'foto_aslap',
        'ktp_aslap',
        'status_validasi_aslap'
    ];

    //protected $hidden = [
    //    'password_data_pekerja',
    //];

    //public function getAuthPassword()
    //{
    //    return $this->password_data_pekerja;
    //}
}
