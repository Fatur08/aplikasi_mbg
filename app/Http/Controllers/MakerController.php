<?php

namespace App\Http\Controllers;

use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MakerController extends Controller
{
    public function index_owner_maker(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $kecamatan_cari    = $request->kecamatan_cari;
        $pilih_dapur       = $request->pilih_dapur;

        $query = Maker::query();
        $query->select('*');
        if(!empty($nama_lengkap_cari)){
            $query->where('nama_maker','like','%'.$nama_lengkap_cari.'%');
        }
        if(!empty($kecamatan_cari)){
            $query->where('kecamatan_maker','like','%'.$kecamatan_cari.'%');
        }
        if($pilih_dapur !== null && $pilih_dapur !== ''){
            $query->where('nomor_dapur_maker', $pilih_dapur);
        }
        $maker = $query->get();
        $maker = $query->paginate(100);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        return view('owner.data_induk.maker.index_maker', compact('maker', 'dapurList'));
    }

    public function store_owner_maker(Request $request)
    {
        $nama_maker = $request->nama_maker;
        $email_maker = $request->email_maker;
        $alamat_maker = $request->alamat_maker;
        $no_hp_maker = $request->no_hp_maker;
        $kecamatan_maker = $request->kecamatan_maker;
        $password_maker = 12345;

        if($request->hasFile('foto_maker')){
            $foto_maker = $nama_maker.".".$request
                ->file('foto_maker')
                ->getClientOriginalExtension();
        } else {
            $foto_maker = null;
        }

        $data = [
            'nama_maker' => $nama_maker,
            'email_maker' => $email_maker,
            'alamat_maker' => $alamat_maker,
            'no_hp_maker' => $no_hp_maker,
            'foto_maker' => $foto_maker,
            'kecamatan_maker' => $kecamatan_maker,
            'password_maker' => $password_maker
        ];

        $simpan = DB::table('maker')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_maker')) {
                $foto_maker = $nama_maker.".".$request
                    ->file('foto_maker')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/maker/';
                $request->file('foto_maker')->storeAs($storagePath, $foto_maker);
                $publicPath = public_path('storage/uploads/data_induk/maker/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_maker);
                $destinationFile = public_path('storage/uploads/data_induk/maker/' . $foto_maker);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_maker(Request $request)
    {
        $id = $request->id;
        $maker = DB::table('maker')->get();
        $data = DB::table('maker')->where('id_maker', $id)->first();
        return view('owner.data_induk.maker.edit_maker',compact('maker','data'));
    }

    public function update_owner_maker($id_maker, Request $request)
    {
        $id_maker = $request->id_maker;
        $nama_maker = $request->nama_maker;
        $email_maker = $request->email_maker;
        $alamat_maker = $request->alamat_maker;
        $no_hp_maker = $request->no_hp_maker;
        $kecamatan_maker = $request->kecamatan_maker;
        $old_foto_maker = $request->old_foto_maker;
        $password_maker = $request->password_maker;

        if($request->hasFile('foto_maker')){
            $foto_maker = $id_maker.".".$request
                ->file('foto_maker')
                ->getClientOriginalExtension();
        } else {
            $foto_maker = $old_foto_maker;
        }

        try {
            $data = [
                'nama_maker' => $nama_maker,
                'email_maker' => $email_maker,
                'alamat_maker' => $alamat_maker,
                'no_hp_maker' => $no_hp_maker,
                'foto_maker'=>$foto_maker,
                'kecamatan_maker' => $kecamatan_maker,
                'password_maker' => $password_maker
            ];
            $update = DB::table('maker')->where('id_maker', $id_maker)->update($data);
            if ($update){
                if ($request->hasFile('foto_maker')) {
                    $foto_maker = $id_maker.".".$request
                        ->file('foto_maker')
                        ->getClientOriginalExtension();
                    $folderpath = "public/uploads/data_induk/maker/";
                    $folderpathold = $folderpath . $old_foto_maker;
                    if (Storage::exists($folderpathold)) {
                        Storage::delete($folderpathold);
                    }
                    $request->file('foto_maker')->storeAs($folderpath, $foto_maker);
                    $publicPath = public_path('storage/uploads/data_induk/maker/');
                    if (!is_dir($publicPath)) {
                        mkdir($publicPath, 0777, true);
                    }
                    $sourceFile = storage_path('app/' . $folderpath . $foto_maker);
                    $destinationFile = public_path('storage/uploads/data_induk/maker/' . $foto_maker);
                    copy($sourceFile, $destinationFile);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_owner_maker($id_maker)
    {
        $delete = DB::table('maker')->where('id_maker', $id_maker)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
