<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = "supplier";

    protected $fillable = [
        'nama_supplier',
        'nomor_dapur_supplier',
        'nota_supplier',
        'bukti_supplier',
        'status_supplier'
    ];
}
