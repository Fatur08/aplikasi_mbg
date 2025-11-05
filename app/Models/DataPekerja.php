<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DataPekerja extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "data_pekerja";
    protected $primaryKey = 'id_data_pekerja';

    protected $fillable = [
        'nama_data_pekerja',
        'peran_data_pekerja',
        'no_hp_data_pekerja',
        'foto_data_pekerja',
        'ktp_data_pekerja',
        'status_validasi_data_pekerja'
    ];

    //protected $hidden = [
    //    'password_data_pekerja',
    //];

    //public function getAuthPassword()
    //{
    //    return $this->password_data_pekerja;
    //}
}
