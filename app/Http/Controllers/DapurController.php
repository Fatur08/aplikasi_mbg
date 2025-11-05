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
            ->leftJoin('admin', 'dapur.nomor_dapur', '=', 'admin.nomor_dapur_admin')
            ->leftJoin('kepala_dapur', 'dapur.nomor_dapur', '=', 'kepala_dapur.nomor_dapur_kepala_dapur')
            ->leftJoin('distributor', 'dapur.nomor_dapur', '=', 'distributor.nomor_dapur_distributor')
            ->select(
                'dapur.*',
                'admin.nama_admin',
                'kepala_dapur.nama_lengkap as nama_kepala_dapur',
                'distributor.nama_distributor'
            );

        if (!empty($cari_kecamatan)) {
            $query->where('dapur.dapur_kecamatan','like','%'.$cari_kecamatan.'%');
        }

        $data_dapur = $query->orderBy('dapur.nomor_dapur','asc')->paginate(8);

        $admin = DB::table('admin')->select('id_admin', 'nama_admin')->get();
        $kepala_dapur = DB::table('kepala_dapur')->select('id', 'nama_lengkap')->get();
        $distributor = DB::table('distributor')->select('id_distributor', 'nama_distributor')->get();

        return view('owner.data_induk.dapur.index_dapur', compact('admin', 'kepala_dapur', 'distributor', 'data_dapur'));
    }

    public function store_owner_dapur(Request $request)
    {
        $nama_dapur = $request->nama_dapur;
        $nomor_dapur = (int)$request->nomor_dapur;
        $id_admin = $request->id_admin;
        $id_kepala_dapur = $request->id_kepala_dapur;
        $id_distributor = $request->id_distributor;
        $dapur_kecamatan = $request->dapur_kecamatan;

        $data = [
            'nomor_dapur'   => $nomor_dapur,
            'nama_dapur' => $nama_dapur,
            'dapur_kecamatan' => $dapur_kecamatan
        ];

        $simpan = DB::table('dapur')->insert($data);

        $updateadmin = DB::table('admin')
            ->where('id_admin', $id_admin)
            ->update([
                'nomor_dapur_admin' => $nomor_dapur
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





    //Bagian Admin
    public function index_admin_dapur(Request $request)
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
            ->leftJoin('admin', 'dapur.nomor_dapur', '=', 'admin.nomor_dapur_admin')
            ->leftJoin('kepala_dapur', 'dapur.nomor_dapur', '=', 'kepala_dapur.nomor_dapur_kepala_dapur')
            ->leftJoin('distributor', 'dapur.nomor_dapur', '=', 'distributor.nomor_dapur_distributor')
            ->select(
                'dapur.*',
                'admin.nama_admin',
                'kepala_dapur.nama_lengkap as nama_kepala_dapur',
                'distributor.nama_distributor'
            );

        if (!empty($cari_kecamatan)) {
            $query->where('dapur.dapur_kecamatan','like','%'.$cari_kecamatan.'%');
        }

        $data_dapur = $query->orderBy('dapur.nomor_dapur','asc')->paginate(8);

        $admin = DB::table('admin')->select('id_admin', 'nama_admin')->get();
        $kepala_dapur = DB::table('kepala_dapur')->select('id', 'nama_lengkap')->get();
        $distributor = DB::table('distributor')->select('id_distributor', 'nama_distributor')->get();

        $select_dapur = DB::table('dapur')->select('dapur_kecamatan')->get();

        return view('admin.data_induk.dapur.index_dapur', compact('admin', 'kepala_dapur', 'distributor', 'data_dapur','availableKecamatan', 'select_dapur'));
    }

    public function store_admin_dapur(Request $request)
    {
        $nama_dapur = $request->nama_dapur;
        $nomor_dapur = (int)$request->nomor_dapur;
        $id_admin = $request->id_admin;
        $id_kepala_dapur = $request->id_kepala_dapur;
        $id_distributor = $request->id_distributor;
        $dapur_kecamatan = $request->dapur_kecamatan;

        $data = [
            'nomor_dapur'   => $nomor_dapur,
            'nama_dapur' => $nama_dapur,
            'dapur_kecamatan' => $dapur_kecamatan
        ];

        $simpan = DB::table('dapur')->insert($data);

        $updateadmin = DB::table('admin')
            ->where('id_admin', $id_admin)
            ->update([
                'nomor_dapur_admin' => $nomor_dapur
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

    public function delete_admin_dapur($id_dapur)
    {
        $delete = DB::table('dapur')->where('id_dapur', $id_dapur)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
