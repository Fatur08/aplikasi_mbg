<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataAhliGiziController extends Controller
{
    public function index_maker_data_staff_ahli_gizi(Request $request)
    {
        return view('maker.data_staff.ahli_gizi.index_ahli_gizi');
    }
}
