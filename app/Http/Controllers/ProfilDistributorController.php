<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProfilDistributorController extends Controller
{
    public function index_profil_distributor(Request $request)
    {
        // Ambil data distributor yang sedang login
        $distributor = Auth::guard('distributor')->user();

        // Pastikan user login ditemukan
        if (!$distributor) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Ambil data lengkap distributor dari tabel distributor
        $data_distributor = DB::table('distributor')
            ->where('id_distributor', $distributor->id_distributor)
            ->first();

        // Kirim data ke view
        return view('distributor.profil_distributor.index_profil_distributor', compact('data_distributor'));
    }

    public function update_profil_distributor(Request $request)
    {
        $distributor = Auth::guard('distributor')->user();
        $id_distributor = $distributor->id_distributor;
        $old_foto_distributor = $distributor->foto_distributor;
        $nama_distributor = $request->nama_distributor;
        $email_distributor = $request->email_distributor;
        $alamat_distributor = $request->alamat_distributor;
        $no_hp_distributor = $request->no_hp_distributor;
        $kecamatan_distributor = $request->kecamatan_distributor;
        $password_distributor = $request->password_distributor;

        if($request->hasFile('foto_distributor')){
            $foto_distributor = $nama_distributor.".".$request
                ->file('foto_distributor')
                ->getClientOriginalExtension();
        } else {
            $foto_distributor = $old_foto_distributor;
        }

        try {
            $data = [
                'nama_distributor'      => $nama_distributor,
                'email_distributor'     => $email_distributor,
                'alamat_distributor'    => $alamat_distributor,
                'no_hp_distributor'     => $no_hp_distributor,
                'foto_distributor'      => $foto_distributor,
                'kecamatan_distributor' => $kecamatan_distributor,
                'password_distributor'  => $password_distributor
            ];
            $update = DB::table('distributor')->where('id_distributor', $id_distributor)->update($data);
            if ($update){
                if ($request->hasFile('foto_distributor')) {
                    $foto_distributor = $nama_distributor.".".$request
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
}
