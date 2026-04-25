<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class InformasiOperasional extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "informasi_operasional";
    protected $primaryKey = 'id_informasi_operasional';

    protected $fillable = [
        'id_owner',
        'nomor_dapur_informasi_operasional',
        'jenis_informasi_operasional',
        'jumlah_jenis_informasi_operasional',
        'harga_satuan_informasi_operasional'
    ];
}
