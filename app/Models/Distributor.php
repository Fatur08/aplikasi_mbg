<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Distributor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "distributor";
    protected $primaryKey = 'id_distributor';

    protected $fillable = [
        'nama_distributor',
        'email_distributor',
        'alamat_distributor',
        'no_hp_distributor',
        'foto_distributor',
        'kecamatan_distributor',
        'password_distributor'
    ];

    protected $hidden = [
        'password_distributor',
    ];

    public function getAuthPassword()
    {
        return $this->password_distributor; // ambil kolom password_distributor
    }
}