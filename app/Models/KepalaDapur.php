<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- ini
use Illuminate\Notifications\Notifiable;

class KepalaDapur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "kepala_dapur";
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'alamat',
        'no_hp',
        'foto',
        'kecamatan',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password; // ambil kolom password_distributor
    }
}

