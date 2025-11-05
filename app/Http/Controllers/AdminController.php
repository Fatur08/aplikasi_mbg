<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index_owner_admin(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $kecamatan_cari = $request->kecamatan_cari;
        $query = Admin::query();
        $query->select('*');
        if(!empty($nama_lengkap_cari)){
            $query->where('nama_admin','like','%'.$nama_lengkap_cari.'%');
        }
        if(!empty($kecamatan_cari)){
            $query->where('kecamatan_admin','like','%'.$kecamatan_cari.'%');
        }
        $admin = $query->get();
        $admin = $query->paginate(10);
        return view('owner.data_induk.admin.index_admin', compact('admin'));
    }

    public function store_owner_admin(Request $request)
    {
        $nama_admin = $request->nama_admin;
        $email_admin = $request->email_admin;
        $alamat_admin = $request->alamat_admin;
        $no_hp_admin = $request->no_hp_admin;
        $kecamatan_admin = $request->kecamatan_admin;
        $password_admin = 12345;

        if($request->hasFile('foto_admin')){
            $foto_admin = $nama_admin.".".$request
                ->file('foto_admin')
                ->getClientOriginalExtension();
        } else {
            $foto_admin = null;
        }

        $data = [
            'nama_admin' => $nama_admin,
            'email_admin' => $email_admin,
            'alamat_admin' => $alamat_admin,
            'no_hp_admin' => $no_hp_admin,
            'foto_admin' => $foto_admin,
            'kecamatan_admin' => $kecamatan_admin,
            'password_admin' => $password_admin
        ];

        $simpan = DB::table('admin')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_admin')) {
                $foto_admin = $nama_admin.".".$request
                    ->file('foto_admin')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/admin/';
                $request->file('foto_admin')->storeAs($storagePath, $foto_admin);
                $publicPath = public_path('storage/uploads/data_induk/admin/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_admin);
                $destinationFile = public_path('storage/uploads/data_induk/admin/' . $foto_admin);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_admin(Request $request)
    {
        $id = $request->id;
        $admin = DB::table('admin')->get();
        $data = DB::table('admin')->where('id_admin', $id)->first();
        return view('owner.data_induk.admin.edit_admin',compact('admin','data'));
    }

    public function update_owner_admin($id_admin, Request $request)
    {
        $id_admin = $request->id_admin;
        $nama_admin = $request->nama_admin;
        $email_admin = $request->email_admin;
        $alamat_admin = $request->alamat_admin;
        $no_hp_admin = $request->no_hp_admin;
        $kecamatan_admin = $request->kecamatan_admin;
        $old_foto_admin = $request->old_foto_admin;
        $password_admin = $request->password_admin;

        if($request->hasFile('foto_admin')){
            $foto_admin = $id_admin.".".$request
                ->file('foto_admin')
                ->getClientOriginalExtension();
        } else {
            $foto_admin = $old_foto_admin;
        }

        try {
            $data = [
                'nama_admin' => $nama_admin,
                'email_admin' => $email_admin,
                'alamat_admin' => $alamat_admin,
                'no_hp_admin' => $no_hp_admin,
                'foto_admin'=>$foto_admin,
                'kecamatan_admin' => $kecamatan_admin,
                'password_admin' => $password_admin
            ];
            $update = DB::table('admin')->where('id_admin', $id_admin)->update($data);
            if ($update){
                if ($request->hasFile('foto_admin')) {
                    $foto_admin = $id_admin.".".$request
                        ->file('foto_admin')
                        ->getClientOriginalExtension();
                    $folderpath = "public/uploads/data_induk/admin/";
                    $folderpathold = $folderpath . $old_foto_admin;
                    if (Storage::exists($folderpathold)) {
                        Storage::delete($folderpathold);
                    }
                    $request->file('foto_admin')->storeAs($folderpath, $foto_admin);
                    $publicPath = public_path('storage/uploads/data_induk/admin/');
                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }
                    $sourceFile = storage_path('app/' . $folderpath . $foto_admin);
                    $destinationFile = public_path('storage/uploads/data_induk/admin/' . $foto_admin);
                    copy($sourceFile, $destinationFile);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_owner_admin($id_admin)
    {
        $delete = DB::table('admin')->where('id_admin', $id_admin)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
