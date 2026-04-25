<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanOperasionalController extends Controller
{
    // OWNER
    public function index_owner_operasional(Request $request)
    {
        return view('owner.laporan.operasional.index_laporan_operasional');
    }







    // MAKER
    public function index_maker_informasi_operasional(Request $request)
    {
        return view('maker.operasional.informasi_operasional.index_informasi_operasional');
    }
}
