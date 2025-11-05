<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSupplier extends Model
{
    use HasFactory;

    protected $table = "barang_supplier";

    protected $fillable = [
        'id_barang_supplier',
        'id_informasi_supplier',
        'nomor_dapur_barang_supplier',
        'nama_barang_supplier',
        'jumlah_barang_supplier',
        'satuan_barang_supplier',
        'harga_barang_supplier'
    ];

    public function InformasiSupplier()
    {
        return $this->belongsTo(InformasiSupplier::class, 'id_informasi_supplier', 'id_informasi_supplier');
    }
}
