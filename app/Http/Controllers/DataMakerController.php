<?php

namespace App\Http\Controllers;

use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMakerController extends Controller
{
    public function index_maker_data_staff_maker(Request $request)
    {
        $maker = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $maker->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        $query = Maker::query();
        $query->select('*');
        if(!empty($cari_nama)){
            $query->where('nama_maker','like','%'.$cari_nama.'%');
        }
        $maker = $query->get();
        $maker = $query->paginate(100);
        return view('maker.data_staff.maker.index_maker', compact('maker'));
    }
}
