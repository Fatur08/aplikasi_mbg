<?php

namespace App\Http\Controllers;

use App\Models\KepalaDapur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KepalaDapurController extends Controller
{
    //Bagian Owner
    public function index_owner_kepala_dapur(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $kecamatan_cari = $request->kecamatan_cari;
        $query = KepalaDapur::query();
        $query->select('*');
        if(!empty($nama_lengkap_cari)){
            $query->where('nama_lengkap','like','%'.$nama_lengkap_cari.'%');
        }
        if(!empty($kecamatan_cari)){
            $query->where('kecamatan','like','%'.$kecamatan_cari.'%');
        }
        $kepala_dapur = $query->get();
        $kepala_dapur = $query->paginate(10);
        return view('owner.data_induk.kepala_dapur.index_kepala_dapur', compact('kepala_dapur'));
    }

    public function store_owner_kepala_dapur(Request $request)
    {
        $nama_lengkap = $request->nama_lengkap;
        $email = $request->email;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $kecamatan = $request->kecamatan;
        $password = 12345;

        if($request->hasFile('foto')){
            $foto = $nama_lengkap.".".$request
                ->file('foto')
                ->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'foto'=>$foto,
            'kecamatan' => $kecamatan,
            'password' => $password
        ];

        $simpan = DB::table('kepala_dapur')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto')) {
                $foto = $nama_lengkap.".".$request
                    ->file('foto')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/kepala_dapur/';
                $request->file('foto')->storeAs($storagePath, $foto);
                $publicPath = public_path('storage/uploads/data_induk/kepala_dapur/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto);
                $destinationFile = public_path('storage/uploads/data_induk/kepala_dapur/' . $foto);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_kepala_dapur(Request $request)
    {
        $id = $request->id;
        $kepala_dapur = DB::table('kepala_dapur')->get();
        $data = DB::table('kepala_dapur')->where('id', $id)->first();
        return view('owner.data_induk.kepala_dapur.edit_kepala_dapur',compact('kepala_dapur','data'));
    }

    public function update_owner_kepala_dapur($id, Request $request)
    {
        $id = $request->id;
        $nama_lengkap = $request->nama_lengkap;
        $email = $request->email;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $kecamatan = $request->kecamatan;
        $old_foto = $request->old_foto;
        $password = $request->password;

        if($request->hasFile('foto')){
            $foto = $id.".".$request
                ->file('foto')
                ->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'email' => $email,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto'=>$foto,
                'kecamatan' => $kecamatan,
                'password' => $password
            ];
            $update = DB::table('kepala_dapur')->where('id', $id)->update($data);
            if ($update){
                if ($request->hasFile('foto')) {
                    $foto = $id.".".$request
                        ->file('foto')
                        ->getClientOriginalExtension();
                    $folderpath = "public/uploads/data_induk/kepala_dapur/";
                    $folderpathold = $folderpath . $old_foto;
                    if (Storage::exists($folderpathold)) {
                        Storage::delete($folderpathold);
                    }
                    $request->file('foto')->storeAs($folderpath, $foto);
                    $publicPath = public_path('storage/uploads/data_induk/kepala_dapur/');
                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }
                    $sourceFile = storage_path('app/' . $folderpath . $foto);
                    $destinationFile = public_path('storage/uploads/data_induk/kepala_dapur/' . $foto);
                    copy($sourceFile, $destinationFile);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_owner_kepala_dapur($id)
    {
        $delete = DB::table('kepala_dapur')->where('id', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }




    //Bagian Admin
    public function index_admin_kepala_dapur(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $kecamatan_cari = $request->kecamatan_cari;
        $query = KepalaDapur::query();
        $query->select('*');
        if(!empty($nama_lengkap_cari)){
            $query->where('nama_lengkap','like','%'.$nama_lengkap_cari.'%');
        }
        if(!empty($kecamatan_cari)){
            $query->where('kecamatan','like','%'.$kecamatan_cari.'%');
        }
        $kepala_dapur = $query->get();
        $kepala_dapur = $query->paginate(10);
        return view('admin.data_induk.kepala_dapur.index_kepala_dapur', compact('kepala_dapur'));
    }

    public function store_admin_kepala_dapur(Request $request)
    {
        $nama_lengkap = $request->nama_lengkap;
        $email = $request->email;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $kecamatan = $request->kecamatan;
        $password = 12345;

        if($request->hasFile('foto')){
            $foto = $nama_lengkap.".".$request
                ->file('foto')
                ->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'foto'=>$foto,
            'kecamatan' => $kecamatan,
            'password' => $password
        ];

        $simpan = DB::table('kepala_dapur')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto')) {
                $foto = $nama_lengkap.".".$request
                    ->file('foto')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/kepala_dapur/';
                $request->file('foto')->storeAs($storagePath, $foto);
                $publicPath = public_path('storage/uploads/data_induk/kepala_dapur/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto);
                $destinationFile = public_path('storage/uploads/data_induk/kepala_dapur/' . $foto);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_admin_kepala_dapur(Request $request)
    {
        $id = $request->id;
        $kepala_dapur = DB::table('kepala_dapur')->get();
        $data = DB::table('kepala_dapur')->where('id', $id)->first();
        return view('admin.data_induk.kepala_dapur.edit_kepala_dapur',compact('kepala_dapur','data'));
    }

    public function update_admin_kepala_dapur($id, Request $request)
    {
        $id = $request->id;
        $nama_lengkap = $request->nama_lengkap;
        $email = $request->email;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $kecamatan = $request->kecamatan;
        $old_foto = $request->old_foto;
        $password = $request->password;

        if($request->hasFile('foto')){
            $foto = $id.".".$request
                ->file('foto')
                ->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'email' => $email,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'foto'=>$foto,
                'kecamatan' => $kecamatan,
                'password' => $password
            ];
            $update = DB::table('kepala_dapur')->where('id', $id)->update($data);
            if ($update){
                if ($request->hasFile('foto')) {
                    $foto = $id.".".$request
                        ->file('foto')
                        ->getClientOriginalExtension();
                    $folderpath = "public/uploads/data_induk/kepala_dapur/";
                    $folderpathold = $folderpath . $old_foto;
                    if (Storage::exists($folderpathold)) {
                        Storage::delete($folderpathold);
                    }
                    $request->file('foto')->storeAs($folderpath, $foto);
                    $publicPath = public_path('storage/uploads/data_induk/kepala_dapur/');
                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }
                    $sourceFile = storage_path('app/' . $folderpath . $foto);
                    $destinationFile = public_path('storage/uploads/data_induk/kepala_dapur/' . $foto);
                    copy($sourceFile, $destinationFile);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_admin_kepala_dapur($id)
    {
        $delete = DB::table('kepala_dapur')->where('id', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
