<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Relawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "relawan";
    protected $primaryKey = 'id_relawan';

    protected $fillable = [
        'nama_relawan',
        'divisi_relawan',
        'no_hp_relawan',
        'foto_relawan',
        'ktp_relawan',
        'status_validasi_relawan'
    ];

    //protected $hidden = [
    //    'password_data_pekerja',
    //];

    //public function getAuthPassword()
    //{
    //    return $this->password_data_pekerja;
    //}
}
