<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataAkuntanController extends Controller
{
    public function index_maker_data_staff_akuntan(Request $request)
    {
        return view('maker.data_staff.akuntan.index_akuntan');
    }
}
