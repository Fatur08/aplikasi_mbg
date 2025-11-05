<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKoperasi extends Model
{
    use HasFactory;

    protected $table = "data_koperasi";
    protected $primaryKey = 'id_data_koperasi';

    protected $fillable = [
        'id_data_koperasi',
        'id_informasi_supplier',
        'nomor_dapur_data_koperasi',
        'tanggal_data_koperasi',
        'jenis_data_koperasi',
        'kategori_data_koperasi',
        'harga_data_koperasi',
        'status_data_koperasi'
    ];
}
