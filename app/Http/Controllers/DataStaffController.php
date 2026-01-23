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


        // 🔹 Query utama driver dengan join dapur unik
        $querydriver = DB::table('driver')
            ->joinSub($subDapur, 'dapur_unik', function ($join) {
                $join->on('driver.nomor_dapur_driver', '=', 'dapur_unik.nomor_dapur');
            })
            ->join('dapur', 'dapur.id_dapur', '=', 'dapur_unik.id_dapur')
            ->select(
                'driver.id_driver',
                'driver.nama_driver',
                'driver.email_driver',
                'driver.alamat_driver',
                'driver.no_hp_driver',
                'driver.foto_driver',
                'driver.nomor_dapur_driver',
                'driver.password_driver',
                'dapur.nama_dapur',
                'dapur.dapur_kecamatan'
            )
            ->where('driver.nomor_dapur_driver', $nomor_dapur_maker);

        // 🔍 Filter pencarian nama driver (jika diinput)
        if (!empty($cari_nama)) {
            $querydriver->where('driver.nama_driver', 'like', '%' . $cari_nama . '%');
        }

        // 🔹 Ambil data
        $driver = $querydriver->orderBy('driver.nama_driver', 'asc')->get();

        
        


        // BAGIAN DATA PEKERJA
        $queryRelawan = Relawan::query();
        $queryRelawan->select('*');
        if(!empty($cari_nama)){
            $queryRelawan->where('nama_relawan','like','%'.$cari_nama.'%');
        }
        $relawan = $queryRelawan->get();
        $relawan = $queryRelawan->paginate(50);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        $peranList = DB::table('relawan')
            ->select('peran_relawan')
            ->whereNotNull('peran_relawan')
            ->where('peran_relawan', '!=', '')
            ->distinct()
            ->get();

        return view('maker.data_staff.index_data_staff', compact('kepala_dapur', 'driver', 'relawan', 'dapurList', 'peranList'));
    }
}
