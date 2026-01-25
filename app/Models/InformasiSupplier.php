<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiSupplier extends Model
{
    use HasFactory;

    protected $table = "informasi_supplier";

    protected $fillable = [
        'id_informasi_supplier',
        'nomor_dapur_informasi_supplier',
        'nama_informasi_supplier',
        'alamat_informasi_supplier',
        'no_hp_informasi_supplier',
        'status_informasi_supplier'
    ];

    // 🔹 Tambahkan relasi ke Supplier
    public function BarangSupplier()
    {
        return $this->hasMany(BarangSupplier::class, 'id_informasi_supplier', 'id_informasi_supplier');
    }
}
