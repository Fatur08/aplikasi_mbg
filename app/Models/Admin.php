<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "admin";
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama_admin',
        'email_admin',
        'alamat_admin',
        'no_hp_admin',
        'foto_admin',
        'kecamatan_admin',
        'password_admin'
    ];

    protected $hidden = [
        'password_admin',
    ];

    public function getAuthPassword()
    {
        return $this->password_admin; // ambil kolom password_distributor
    }
}
