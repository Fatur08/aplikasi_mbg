<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperasionalController extends Controller
{
    // OWNER
    public function index_owner_operasional(Request $request)
    {
        return view('owner.laporan.operasional.index_laporan_operasional');
    }







    // MAKER
    // Informasi Operasional
    public function index_maker_informasi_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        // CEK APAKAH AKUN SUKADANA ILIR ATAU BUKAN 
        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        $query = DB::table('informasi_operasional')
            ->where('id_owner', $maker->id_owner)
            ->where('nomor_dapur_informasi_operasional', $maker->nomor_dapur_maker);

        // 🔍 Filter pencarian
        if ($request->filled('cari_jenis_informasi_operasional')) {
            $query->where('jenis_informasi_operasional', 'like', '%' . $request->cari_jenis_informasi_operasional . '%');
        }

        $data = $query->orderBy('jenis_informasi_operasional', 'asc')->get();

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





    // Laporan Operasional
    public function index_maker_laporan_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        // 🔒 Validasi akses
        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        // ✅ Ambil data jenis operasional (distinct biar tidak duplikat)
        $jenisOperasional = DB::table('informasi_operasional')
            ->select('id_informasi_operasional', 'jenis_operasional')
            ->where('id_owner', $maker->id_owner)
            ->where('nomor_dapur_informasi_operasional', $maker->nomor_dapur_maker)
            ->distinct()
            ->get();

        return view(
            'maker.operasional.laporan_operasional.index_laporan_operasional',
            compact('jenisOperasional')
        );
    }
}
