<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class DataDriverController extends Controller
{
    //Bagian Owner
    //public function index_owner_distributor(Request $request)
    //{
    //    $nama_lengkap_cari = $request->nama_lengkap_cari;
    //    $kecamatan_cari = $request->kecamatan_cari;
    //    $query = Distributor::query();
    //    $query->select('*');
    //    if(!empty($nama_lengkap_cari)){
    //        $query->where('nama_distributor','like','%'.$nama_lengkap_cari.'%');
    //    }
    //    if(!empty($kecamatan_cari)){
    //        $query->where('kecamatan_distributor','like','%'.$kecamatan_cari.'%');
    //    }
    //    $distributor = $query->get();
    //    $distributor = $query->paginate(10);
    //    return view('owner.data_induk.distributor.index_distributor', compact('distributor'));
    //}
//
    //public function store_owner_distributor(Request $request)
    //{
    //    $nama_distributor = $request->nama_distributor;
    //    $email_distributor = $request->email_distributor;
    //    $alamat_distributor = $request->alamat_distributor;
    //    $no_hp_distributor = $request->no_hp_distributor;
    //    $kecamatan_distributor = $request->kecamatan_distributor;
    //    $password_distributor = 12345;
//
    //    if($request->hasFile('foto_distributor')){
    //        $foto_distributor = $nama_distributor.".".$request
    //            ->file('foto_distributor')
    //            ->getClientOriginalExtension();
    //    } else {
    //        $foto_distributor = null;
    //    }
//
    //    $data = [
    //        'nama_distributor' => $nama_distributor,
    //        'email_distributor' => $email_distributor,
    //        'alamat_distributor' => $alamat_distributor,
    //        'no_hp_distributor' => $no_hp_distributor,
    //        'foto_distributor' => $foto_distributor,
    //        'kecamatan_distributor' => $kecamatan_distributor,
    //        'password_distributor' => $password_distributor
    //    ];
//
    //    $simpan = DB::table('distributor')->insert($data);
    //    if ($simpan){
    //        if ($request->hasFile('foto_distributor')) {
    //            $foto_distributor = $nama_distributor.".".$request
    //                ->file('foto_distributor')
    //                ->getClientOriginalExtension();
    //            $storagePath = 'public/uploads/data_induk/distributor/';
    //            $request->file('foto_distributor')->storeAs($storagePath, $foto_distributor);
    //            $publicPath = public_path('storage/uploads/data_induk/distributor/');
    //            if (!is_dir($publicPath)) {
    //                mkdir($publicPath, 0777, true);
    //            }
    //            $sourceFile = storage_path('app/' . $storagePath . $foto_distributor);
    //            $destinationFile = public_path('storage/uploads/data_induk/distributor/' . $foto_distributor);
    //            copy($sourceFile, $destinationFile);
    //        }
    //        return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
    //    } else {
    //        return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
    //    }
    //}
//
    //public function edit_owner_distributor(Request $request)
    //{
    //    $id = $request->id;
    //    $distributor = DB::table('distributor')->get();
    //    $data = DB::table('distributor')->where('id_distributor', $id)->first();
    //    return view('owner.data_induk.distributor.edit_distributor',compact('distributor','data'));
    //}
//
    //public function update_owner_distributor($id_distributor, Request $request)
    //{
    //    $id_distributor = $request->id_distributor;
    //    $nama_distributor = $request->nama_distributor;
    //    $email_distributor = $request->email_distributor;
    //    $alamat_distributor = $request->alamat_distributor;
    //    $no_hp_distributor = $request->no_hp_distributor;
    //    $kecamatan_distributor = $request->kecamatan_distributor;
    //    $old_foto_distributor = $request->old_foto_distributor;
    //    $password_distributor = $request->password_distributor;
//
    //    if($request->hasFile('foto_distributor')){
    //        $foto_distributor = $id_distributor.".".$request
    //            ->file('foto_distributor')
    //            ->getClientOriginalExtension();
    //    } else {
    //        $foto_distributor = $old_foto_distributor;
    //    }
//
    //    try {
    //        $data = [
    //            'nama_distributor' => $nama_distributor,
    //            'email_distributor' => $email_distributor,
    //            'alamat_distributor' => $alamat_distributor,
    //            'no_hp_distributor' => $no_hp_distributor,
    //            'foto_distributor'=>$foto_distributor,
    //            'kecamatan_distributor' => $kecamatan_distributor,
    //            'password_distributor' => $password_distributor
    //        ];
    //        $update = DB::table('distributor')->where('id_distributor', $id_distributor)->update($data);
    //        if ($update){
    //            if ($request->hasFile('foto_distributor')) {
    //                $foto_distributor = $id_distributor.".".$request
    //                    ->file('foto_distributor')
    //                    ->getClientOriginalExtension();
    //                $folderpath = "public/uploads/data_induk/distributor/";
    //                $folderpathold = $folderpath . $old_foto_distributor;
    //                if (Storage::exists($folderpathold)) {
    //                    Storage::delete($folderpathold);
    //                }
    //                $request->file('foto_distributor')->storeAs($folderpath, $foto_distributor);
    //                $publicPath = public_path('storage/uploads/data_induk/distributor/');
    //                if (!is_dir($publicPath)) {
    //                    mkdir($publicPath, 0777, true);
    //                }
    //                $sourceFile = storage_path('app/' . $folderpath . $foto_distributor);
    //                $destinationFile = public_path('storage/uploads/data_induk/distributor/' . $foto_distributor);
    //                copy($sourceFile, $destinationFile);
    //            }
    //            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
    //        }
    //    } catch (\Exception $e) {
    //        //dd($e);
    //        return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
    //    }
    //}
//
    //public function delete_owner_distributor($id_distributor)
    //{
    //    $delete = DB::table('distributor')->where('id_distributor', $id_distributor)->delete();
    //    if($delete){
    //        return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
    //    } else {
    //        return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
    //    }
    //}


































    // BAGIAN MAKER
    public function index_maker_data_staff_driver(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff driver
        $query = Driver::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_driver', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_driver', 'like', '%' . $cari_nama . '%');
        }

        // Pagination Hei 
        $driver = $query->paginate(100);

        return view('maker.data_staff.driver.index_driver', compact('driver'));
    }





    public function store_maker_data_staff_driver(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_driver   = $request->nama_driver;
        $email_driver  = $request->email_driver;
        $alamat_driver = $request->alamat_driver;
        $no_hp_driver  = $request->no_hp_driver;
        $foto_driver   = $request->foto_driver;
        $dapur = DB::table('dapur')
            ->where('nomor_dapur', $nomor_dapur)
            ->first();

        $kecamatan_driver = $dapur->dapur_kecamatan ?? null;
        $password_driver = 12345;
        

        if($request->hasFile('foto_driver')){
            $foto_driver = $nama_driver.".".$request
                ->file('foto_driver')
                ->getClientOriginalExtension();
        } else {
            $foto_driver = null;
        }

        $data = [
            'nama_driver'             => $nama_driver,
            'nomor_dapur_driver'      => $nomor_dapur,
            'email_driver'            => $email_driver,
            'alamat_driver'           => $alamat_driver,
            'no_hp_driver'            => $no_hp_driver,
            'foto_driver'             =>$foto_driver,
            'kecamatan_driver'        => $kecamatan_driver,
            'password_driver'         => $password_driver,
            'status_validasi_driver'  => 0
        ];

        $simpan = DB::table('driver')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_driver')) {
                $foto_driver = $nama_driver.".".$request
                    ->file('foto_driver')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/driver/';
                $request->file('foto_driver')->storeAs($storagePath, $foto_driver);
                $publicPath = public_path('storage/uploads/data_staff/driver/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_driver);
                $destinationFile = public_path('storage/uploads/data_staff/driver/' . $foto_driver);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }







    public function edit_maker_distributor(Request $request)
    {
        $id = $request->id;
        $distributor = DB::table('distributor')->get();
        $data = DB::table('distributor')->where('id_distributor', $id)->first();
        return view('maker.data_induk.distributor.edit_distributor',compact('distributor','data'));
    }

    public function update_maker_distributor($id_distributor, Request $request)
    {
        $id_distributor = $request->id_distributor;
        $nama_distributor = $request->nama_distributor;
        $email_distributor = $request->email_distributor;
        $alamat_distributor = $request->alamat_distributor;
        $no_hp_distributor = $request->no_hp_distributor;
        $kecamatan_distributor = $request->kecamatan_distributor;
        $old_foto_distributor = $request->old_foto_distributor;
        $password_distributor = $request->password_distributor;

        if($request->hasFile('foto_distributor')){
            $foto_distributor = $id_distributor.".".$request
                ->file('foto_distributor')
                ->getClientOriginalExtension();
        } else {
            $foto_distributor = $old_foto_distributor;
        }

        try {
            $data = [
                'nama_distributor' => $nama_distributor,
                'email_distributor' => $email_distributor,
                'alamat_distributor' => $alamat_distributor,
                'no_hp_distributor' => $no_hp_distributor,
                'foto_distributor'=>$foto_distributor,
                'kecamatan_distributor' => $kecamatan_distributor,
                'password_distributor' => $password_distributor
            ];
            $update = DB::table('distributor')->where('id_distributor', $id_distributor)->update($data);
            if ($update){
                if ($request->hasFile('foto_distributor')) {
                    $foto_distributor = $id_distributor.".".$request
                        ->file('foto_distributor')
                        ->getClientOriginalExtension();
                    $folderpath = "public/uploads/data_induk/distributor/";
                    $folderpathold = $folderpath . $old_foto_distributor;
                    if (Storage::exists($folderpathold)) {
                        Storage::delete($folderpathold);
                    }
                    $request->file('foto_distributor')->storeAs($folderpath, $foto_distributor);
                    $publicPath = public_path('storage/uploads/data_induk/distributor/');
                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }
                    $sourceFile = storage_path('app/' . $folderpath . $foto_distributor);
                    $destinationFile = public_path('storage/uploads/data_induk/distributor/' . $foto_distributor);
                    copy($sourceFile, $destinationFile);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_maker_distributor($id_distributor)
    {
        $delete = DB::table('distributor')->where('id_distributor', $id_distributor)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}