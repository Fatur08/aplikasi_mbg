<?php

namespace App\Http\Controllers;

use App\Models\AhliGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataAhliGiziController extends Controller
{
    public function index_maker_data_staff_ahli_gizi(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff ahli_gizi
        $query = AhliGizi::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_ahli_gizi', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_ahli_gizi', 'like', '%' . $cari_nama . '%');
        }

        // Pagination Hei 
        $ahli_gizi = $query->paginate(100);

        return view('maker.data_staff.ahli_gizi.index_ahli_gizi', compact('ahli_gizi'));
    }





    public function store_maker_data_staff_ahli_gizi(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_ahli_gizi   = $request->nama_ahli_gizi;
        $email_ahli_gizi  = $request->email_ahli_gizi;
        $alamat_ahli_gizi = $request->alamat_ahli_gizi;
        $no_hp_ahli_gizi  = $request->no_hp_ahli_gizi;
        $foto_ahli_gizi   = $request->foto_ahli_gizi;
        

        if($request->hasFile('foto_ahli_gizi')){
            $foto_ahli_gizi = $nama_ahli_gizi.".".$request
                ->file('foto_ahli_gizi')
                ->getClientOriginalExtension();
        } else {
            $foto_ahli_gizi = null;
        }

        $data = [
            'nama_ahli_gizi'             => $nama_ahli_gizi,
            'nomor_dapur_ahli_gizi'      => $nomor_dapur,
            'email_ahli_gizi'            => $email_ahli_gizi,
            'alamat_ahli_gizi'           => $alamat_ahli_gizi,
            'no_hp_ahli_gizi'            => $no_hp_ahli_gizi,
            'foto_ahli_gizi'             =>$foto_ahli_gizi,
            'status_validasi_ahli_gizi'  => 0
        ];

        $simpan = DB::table('ahli_gizi')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_ahli_gizi')) {
                $foto_ahli_gizi = $nama_ahli_gizi.".".$request
                    ->file('foto_ahli_gizi')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/ahli_gizi/';
                $request->file('foto_ahli_gizi')->storeAs($storagePath, $foto_ahli_gizi);
                $publicPath = public_path('storage/uploads/data_staff/ahli_gizi/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_ahli_gizi);
                $destinationFile = public_path('storage/uploads/data_staff/ahli_gizi/' . $foto_ahli_gizi);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
}