<?php

namespace App\Http\Controllers;

use App\Models\SPPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataSPPIController extends Controller
{
    public function index_sppi_data_staff_sppi(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $sppiLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff sppi
        $query = SPPI::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_sppi', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_sppi', 'like', '%' . $cari_nama . '%');
        }

        // Pagination
        $sppi = $query->paginate(100);

        return view('sppi.data_staff.sppi.index_sppi', compact('sppi'));
    }





    public function store_maker_data_staff_sppi(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_sppi   = $request->nama_sppi;
        $email_sppi  = $request->email_sppi;
        $alamat_sppi = $request->alamat_sppi;
        $no_hp_sppi  = $request->no_hp_sppi;
        $foto_sppi   = $request->foto_sppi;
        

        if($request->hasFile('foto_sppi')){
            $foto_sppi = $nama_sppi.".".$request
                ->file('foto_sppi')
                ->getClientOriginalExtension();
        } else {
            $foto_sppi = null;
        }

        $data = [
            'nama_sppi'             => $nama_sppi,
            'nomor_dapur_sppi'      => $nomor_dapur,
            'email_sppi'            => $email_sppi,
            'alamat_sppi'           => $alamat_sppi,
            'no_hp_sppi'            => $no_hp_sppi,
            'foto_sppi'             =>$foto_sppi,
            'status_validasi_sppi'  => 0
        ];

        $simpan = DB::table('sppi')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_sppi')) {
                $foto_sppi = $nama_sppi.".".$request
                    ->file('foto_sppi')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/sppi/';
                $request->file('foto_sppi')->storeAs($storagePath, $foto_sppi);
                $publicPath = public_path('storage/uploads/data_staff/sppi/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_sppi);
                $destinationFile = public_path('storage/uploads/data_staff/sppi/' . $foto_sppi);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
}