<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanOperasionalController extends Controller
{
    public function index_owner_operasional(Request $request)
    {
        return view('owner.laporan.operasional.index_laporan_operasional');
    }
}
