<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InformasiStokLimitController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_stok_limit(Request $request)
    {
        $stokLimit = DB::table('stok')
            ->select(
                'nama_stok',
                'satuan_stok',
                'sisa_stok',
                'tanggal_kadaluarsa_stok',
                'status_stok'
            )
            ->whereIn('status_stok', [0, 1, 3, 4, 5])
            ->orderBy('status_stok')
            ->get();

        return view('owner.informasi.stok_limit.index_stok_limit', compact('stokLimit'));
    }


    // BAGIAN ADMIN
    public function index_admin_stok_limit(Request $request)
    {
        $stokLimit = DB::table('stok')
            ->select(
                'nama_stok',
                'satuan_stok',
                'sisa_stok',
                'tanggal_kadaluarsa_stok',
                'status_stok'
            )
            ->whereIn('status_stok', [0, 1, 3, 4, 5])
            ->orderBy('status_stok')
            ->get();

        return view('admin.informasi.stok_limit.index_stok_limit', compact('stokLimit'));
    }
}
