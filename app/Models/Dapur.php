<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dapur extends Model
{
    use HasFactory;

    protected $table = "dapur";

    protected $fillable = [
        'id_dapur',
        'nomor_dapur',
        'nama_dapur',
        'dapur_kecamatan'
    ];
}
