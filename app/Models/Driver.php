<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "driver";
    protected $primaryKey = 'id_driver';

    protected $fillable = [
        'nama_driver',
        'email_driver',
        'alamat_driver',
        'no_hp_driver',
        'foto_driver',
        'kecamatan_driver',
        'password_driver'
    ];

    protected $hidden = [
        'password_driver',
    ];

    public function getAuthPassword()
    {
        return $this->password_driver; // ambil kolom password_driver
    }
}