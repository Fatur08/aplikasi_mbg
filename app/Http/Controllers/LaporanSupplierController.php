<?php

namespace App\Http\Controllers;

class LaporanSupplierController extends Controller
{
    // BAGIAN MAKER
    public function index_maker_laporan_supplier()
    {
        return view('maker.laporan.supplier.index_laporan_supplier');
    }
}
