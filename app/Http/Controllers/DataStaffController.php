<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataStaffController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_staff(Request $request)
    {
        return view('owner.data_staff.index_data_staff');
    }







    // BAGIAN MAKER
    public function index_maker_data_staff(Request $request)
    {
        $maker              = Auth::guard('maker')->user();
        $nomor_dapur_maker  = $maker->nomor_dapur_maker;
        return view('maker.data_staff.index_data_staff');
    }
}
