<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataRelawanController extends Controller
{
    public function index_admin_data_staff_relawan(Request $request)
    {
        return view('admin.data_staff.relawan.index_relawan');
    }
}
