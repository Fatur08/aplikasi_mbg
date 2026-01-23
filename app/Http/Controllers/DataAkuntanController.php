<?php

namespace App\Http\Controllers;

use App\Models\Akuntan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataAkuntanController extends Controller
{
    public function index_maker_data_staff_akuntan(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff akuntan
        $query = Akuntan::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_akuntan', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_akuntan', 'like', '%' . $cari_nama . '%');
        }

        // Pagination Hei 
        $akuntan = $query->paginate(100);

        return view('maker.data_staff.akuntan.index_akuntan', compact('akuntan'));
    }





    public function store_maker_data_staff_akuntan(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_akuntan   = $request->nama_akuntan;
        $email_akuntan  = $request->email_akuntan;
        $alamat_akuntan = $request->alamat_akuntan;
        $no_hp_akuntan  = $request->no_hp_akuntan;
        $foto_akuntan   = $request->foto_akuntan;
        

        if($request->hasFile('foto_akuntan')){
            $foto_akuntan = $nama_akuntan.".".$request
                ->file('foto_akuntan')
                ->getClientOriginalExtension();
        } else {
            $foto_akuntan = null;
        }

        $data = [
            'nama_akuntan'             => $nama_akuntan,
            'nomor_dapur_akuntan'      => $nomor_dapur,
            'email_akuntan'            => $email_akuntan,
            'alamat_akuntan'           => $alamat_akuntan,
            'no_hp_akuntan'            => $no_hp_akuntan,
            'foto_akuntan'             =>$foto_akuntan,
            'status_validasi_akuntan'  => 0
        ];

        $simpan = DB::table('akuntan')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_akuntan')) {
                $foto_akuntan = $nama_akuntan.".".$request
                    ->file('foto_akuntan')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/akuntan/';
                $request->file('foto_akuntan')->storeAs($storagePath, $foto_akuntan);
                $publicPath = public_path('storage/uploads/data_staff/akuntan/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_akuntan);
                $destinationFile = public_path('storage/uploads/data_staff/akuntan/' . $foto_akuntan);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
}