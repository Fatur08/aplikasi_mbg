<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataSPPIController extends Controller
{
    public function index_admin_data_staff_sppi(Request $request)
    {
        return view('admin.data_staff.sppi.index_sppi');
    }
}
