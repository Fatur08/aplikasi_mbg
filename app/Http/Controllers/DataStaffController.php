<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use App\Models\KepalaDapur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataStaffController extends Controller
{
    public function index_maker_data_staff(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $nomor_dapur_maker = $maker->nomor_dapur_maker;
        $cari_nama = $request->cari_nama;

        // 🔹 Subquery dapur unik berdasarkan nomor_dapur
        $subDapur = DB::table('dapur')
            ->select('nomor_dapur', DB::raw('MIN(id_dapur) as id_dapur'))
            ->groupBy('nomor_dapur');

        $querykepaladapur = DB::table('kepala_dapur')
            ->joinSub($subDapur, 'dapur_unik', function ($join) {
                $join->on('kepala_dapur.nomor_dapur_kepala_dapur', '=', 'dapur_unik.nomor_dapur');
            })
            ->join('dapur', 'dapur.id_dapur', '=', 'dapur_unik.id_dapur')
            ->select(
                'kepala_dapur.id',
                'kepala_dapur.nama_lengkap',
                'kepala_dapur.nomor_dapur_kepala_dapur',
                'kepala_dapur.foto',
                'kepala_dapur.email',
                'kepala_dapur.alamat',
                'kepala_dapur.no_hp',
                'kepala_dapur.password',
                'dapur.nama_dapur',
                'dapur.dapur_kecamatan'
            )
            ->where('kepala_dapur.nomor_dapur_kepala_dapur', $nomor_dapur_maker);

        if (!empty($cari_nama)) {
            $querykepaladapur->where('kepala_dapur.nama_lengkap', 'like', '%' . $cari_nama . '%');
        }

        $kepala_dapur = $querykepaladapur->orderBy('kepala_dapur.nama_lengkap', 'asc')->get();


        // 🔹 Query utama distributor dengan join dapur unik
        $querydistributor = DB::table('distributor')
            ->joinSub($subDapur, 'dapur_unik', function ($join) {
                $join->on('distributor.nomor_dapur_distributor', '=', 'dapur_unik.nomor_dapur');
            })
            ->join('dapur', 'dapur.id_dapur', '=', 'dapur_unik.id_dapur')
            ->select(
                'distributor.id_distributor',
                'distributor.nama_distributor',
                'distributor.email_distributor',
                'distributor.alamat_distributor',
                'distributor.no_hp_distributor',
                'distributor.foto_distributor',
                'distributor.nomor_dapur_distributor',
                'distributor.password_distributor',
                'dapur.nama_dapur',
                'dapur.dapur_kecamatan'
            )
            ->where('distributor.nomor_dapur_distributor', $nomor_dapur_maker);

        // 🔍 Filter pencarian nama distributor (jika diinput)
        if (!empty($cari_nama)) {
            $querydistributor->where('distributor.nama_distributor', 'like', '%' . $cari_nama . '%');
        }

        // 🔹 Ambil data
        $distributor = $querydistributor->orderBy('distributor.nama_distributor', 'asc')->get();

        
        


        // BAGIAN DATA PEKERJA
        $queryRelawan = Relawan::query();
        $queryRelawan->select('*');
        if(!empty($cari_nama)){
            $queryRelawan->where('nama_aslap','like','%'.$cari_nama.'%');
        }
        $aslap = $queryRelawan->get();
        $aslap = $queryRelawan->paginate(50);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        $peranList = DB::table('aslap')
            ->select('peran_aslap')
            ->whereNotNull('peran_aslap')
            ->where('peran_aslap', '!=', '')
            ->distinct()
            ->get();

        return view('maker.data_staff.index_data_staff', compact('kepala_dapur', 'distributor', 'aslap', 'dapurList', 'peranList'));
    }
}
