<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataStaffController extends Controller
{
    public function index_maker_data_staff(Request $request)
    {
        $maker              = Auth::guard('maker')->user();
        $nomor_dapur_maker  = $maker->nomor_dapur_maker;
        return view('maker.data_staff.index_data_staff');
    }
}
