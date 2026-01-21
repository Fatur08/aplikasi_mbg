<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataAkuntanController extends Controller
{
    public function index_admin_data_staff_akuntan(Request $request)
    {
        return view('admin.data_staff.akuntan.index_akuntan');
    }
}
