<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataMakerController extends Controller
{
    public function index_admin_data_staff_maker(Request $request)
    {
        return view('admin.data_staff.maker.index_maker');
    }
}
