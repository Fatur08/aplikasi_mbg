<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Maker extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "maker";
    protected $primaryKey = 'id_maker';

    protected $fillable = [
        'id_owner',
        'nama_maker',
        'email_maker',
        'alamat_maker',
        'no_hp_maker',
        'foto_maker',
        'kecamatan_maker',
        'password_maker',
        'status_validasi_maker'
    ];

    protected $hidden = [
        'password_maker',
    ];

    public function getAuthPassword()
    {
        return $this->password_maker; // ambil kolom password_distributor
    }
}
