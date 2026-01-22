<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DapurController extends Controller
{
    //Bagian Owner
    public function index_owner_dapur(Request $request)
    {
        $cari_kecamatan = $request->cari_kecamatan;

        $query = DB::table('dapur')
            ->leftJoin('maker', 'dapur.nomor_dapur', '=', 'maker.nomor_dapur_maker')
            ->leftJoin('kepala_dapur', 'dapur.nomor_dapur', '=', 'kepala_dapur.nomor_dapur_kepala_dapur')
            ->leftJoin('distributor', 'dapur.nomor_dapur', '=', 'distributor.nomor_dapur_distributor')
            ->select(
                'dapur.*',
                'maker.nama_maker',
                'kepala_dapur.nama_lengkap as nama_kepala_dapur',
                'distributor.nama_distributor'
            );

        if (!empty($cari_kecamatan)) {
            $query->where('dapur.dapur_kecamatan','like','%'.$cari_kecamatan.'%');
        }

        $data_dapur = $query->orderBy('dapur.nomor_dapur','asc')->paginate(8);

        $maker = DB::table('maker')->select('id_maker', 'nama_maker')->get();
        $kepala_dapur = DB::table('kepala_dapur')->select('id', 'nama_lengkap')->get();
        $distributor = DB::table('distributor')->select('id_distributor', 'nama_distributor')->get();

        return view('owner.data_induk.dapur.index_dapur', compact('maker', 'kepala_dapur', 'distributor', 'data_dapur'));
    }

    public function store_owner_dapur(Request $request)
    {
        $nama_dapur = $request->nama_dapur;
        $nomor_dapur = (int)$request->nomor_dapur;
        $id_maker = $request->id_maker;
        $id_kepala_dapur = $request->id_kepala_dapur;
        $id_distributor = $request->id_distributor;
        $dapur_kecamatan = $request->dapur_kecamatan;

        $data = [
            'nomor_dapur'   => $nomor_dapur,
            'nama_dapur' => $nama_dapur,
            'dapur_kecamatan' => $dapur_kecamatan
        ];

        $simpan = DB::table('dapur')->insert($data);

        $updatemaker = DB::table('maker')
            ->where('id_maker', $id_maker)
            ->update([
                'nomor_dapur_maker' => $nomor_dapur
            ]);
        
        $updatekepaladapur = DB::table('kepala_dapur')
            ->where('id', $id_kepala_dapur)
            ->update([
                'nomor_dapur_kepala_dapur' => $nomor_dapur
            ]);

        $updatedistributor = DB::table('distributor')
            ->where('id_distributor', $id_distributor)
            ->update([
                'nomor_dapur_distributor' => $nomor_dapur
            ]);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function delete_owner_dapur($id_dapur)
    {
        $delete = DB::table('dapur')->where('id_dapur', $id_dapur)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }





    //Bagian Maker
    public function index_maker_dapur(Request $request)
    {
        $cari_kecamatan = $request->cari_kecamatan;
        $semuaKecamatan = [
            'Bandar Sribhawono',
            'Batanghari',
            'Batanghari Nuban',
            'Braja Selebah',
            'Bumi Agung',
            'Gunung Pelindung',
            'Jabung',
            'Labuhan Maringgai',
            'Labuhan Ratu',
            'Marga Sekampung',
            'Marga Tiga',
            'Mataram Baru',
            'Melinting',
            'Metro Kibang',
            'Pasir Sakti',
            'Pekalongan',
            'Purbolinggo',
            'Raman Utara',
            'Sekampung',
            'Sekampung Udik',
            'Sukadana',
            'Waway Karya',
            'Way Bungur',
            'Way Jepara'
        ];

        $usedKecamatan = DB::table('dapur')->pluck('dapur_kecamatan')->toArray();
        $availableKecamatan = array_diff($semuaKecamatan, $usedKecamatan);

        $query = DB::table('dapur')
            ->leftJoin('maker', 'dapur.nomor_dapur', '=', 'maker.nomor_dapur_maker')
            ->leftJoin('kepala_dapur', 'dapur.nomor_dapur', '=', 'kepala_dapur.nomor_dapur_kepala_dapur')
            ->leftJoin('distributor', 'dapur.nomor_dapur', '=', 'distributor.nomor_dapur_distributor')
            ->select(
                'dapur.*',
                'maker.nama_maker',
                'kepala_dapur.nama_lengkap as nama_kepala_dapur',
                'distributor.nama_distributor'
            );

        if (!empty($cari_kecamatan)) {
            $query->where('dapur.dapur_kecamatan','like','%'.$cari_kecamatan.'%');
        }

        $data_dapur = $query->orderBy('dapur.nomor_dapur','asc')->paginate(8);

        $maker = DB::table('maker')->select('id_maker', 'nama_maker')->get();
        $kepala_dapur = DB::table('kepala_dapur')->select('id', 'nama_lengkap')->get();
        $distributor = DB::table('distributor')->select('id_distributor', 'nama_distributor')->get();

        $select_dapur = DB::table('dapur')->select('dapur_kecamatan')->get();

        return view('maker.data_induk.dapur.index_dapur', compact('maker', 'kepala_dapur', 'distributor', 'data_dapur','availableKecamatan', 'select_dapur'));
    }

    public function store_maker_dapur(Request $request)
    {
        $nama_dapur = $request->nama_dapur;
        $nomor_dapur = (int)$request->nomor_dapur;
        $id_maker = $request->id_maker;
        $id_kepala_dapur = $request->id_kepala_dapur;
        $id_distributor = $request->id_distributor;
        $dapur_kecamatan = $request->dapur_kecamatan;

        $data = [
            'nomor_dapur'   => $nomor_dapur,
            'nama_dapur' => $nama_dapur,
            'dapur_kecamatan' => $dapur_kecamatan
        ];

        $simpan = DB::table('dapur')->insert($data);

        $updatemaker = DB::table('maker')
            ->where('id_maker', $id_maker)
            ->update([
                'nomor_dapur_maker' => $nomor_dapur
            ]);
        
        $updatekepaladapur = DB::table('kepala_dapur')
            ->where('id', $id_kepala_dapur)
            ->update([
                'nomor_dapur_kepala_dapur' => $nomor_dapur
            ]);

        $updatedistributor = DB::table('distributor')
            ->where('id_distributor', $id_distributor)
            ->update([
                'nomor_dapur_distributor' => $nomor_dapur
            ]);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function delete_maker_dapur($id_dapur)
    {
        $delete = DB::table('dapur')->where('id_dapur', $id_dapur)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
