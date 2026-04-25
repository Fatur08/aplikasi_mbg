<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanOperasionalController extends Controller
{
    // OWNER
    public function index_owner_operasional(Request $request)
    {
        return view('owner.laporan.operasional.index_laporan_operasional');
    }







    // MAKER
    public function index_maker_informasi_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        $data = DB::table('informasi_operasional')
            ->where('id_owner', $maker->id_owner)
            ->where('nomor_dapur_informasi_operasional', $maker->nomor_dapur_maker)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('maker.operasional.informasi_operasional.index_informasi_operasional', compact('data'));
    }


    public function store_maker_informasi_operasional(Request $request)
    {
        // Ambil data maker login
        $maker = Auth::guard('maker')->user();

        // Simpan ke tabel informasi_operasional
        DB::table('informasi_operasional')->insert([
            'id_owner' => $maker->id_owner,
            'nomor_dapur_informasi_operasional' => $maker->nomor_dapur_maker,

            'jenis_informasi_operasional' => $request->jenis_informasi_operasional,
            'jumlah_jenis_informasi_operasional' => $request->jumlah_jenis_informasi_operasional,
            'harga_satuan_informasi_operasional' => $request->harga_satuan_informasi_operasional
        ]);

        return redirect()->back()->with('success', 'Data operasional berhasil disimpan');
    }
}
