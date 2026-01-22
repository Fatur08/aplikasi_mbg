<?php

namespace App\Http\Controllers;

use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataMakerController extends Controller
{
    public function index_maker_data_staff_maker(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();
    
        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;
    
        // Query data staff maker
        $query = Maker::query();
    
        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_maker', $nomor_dapur);
        }
    
        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_maker', 'like', '%' . $cari_nama . '%');
        }
    
        // Pagination
        $maker = $query->paginate(100);
    
        return view('maker.data_staff.maker.index_maker', compact('maker'));
    }
}
