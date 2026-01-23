<?php

namespace App\Http\Controllers;

use App\Models\SPPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataSPPIController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_staff_sppi(Request $request)
    {
        $pilih_dapur = $request->pilih_dapur;
        $cari_nama   = $request->cari_nama;

        // Query data staff sppi
        $query = SPPI::query();


        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_sppi', 'like', '%' . $cari_nama . '%');
        }


        // Filter pencarian dapur (jika ada)
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_sppi', $pilih_dapur);
        }

        // Pagination Hei 
        $sppi = $query->paginate(100);


        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        
        
        // ✅ Ambil nama dapur
        $namaDapur = $pilih_dapur
            ? DB::table('dapur')
                ->where('nomor_dapur', $pilih_dapur)
                ->value('nama_dapur')
            : '-';

        return view('owner.data_staff.sppi.index_sppi', compact('sppi', 'dapurList', 'namaDapur'));
    }







    public function validasi_owner_data_staff_sppi(Request $request)
    {
        $id = $request->id;
        $sppi = DB::table('sppi')->get();
        $data = DB::table('sppi')->where('id_sppi', $id)->first();
        return view('owner.data_staff.sppi.validasi_sppi',compact('sppi','data'));
    }



    public function update_owner_validasi_sppi($id, Request $request)
    {
        try {
            $status_validasi_sppi = $request->status_validasi_sppi;

            // Update hanya kolom yang perlu
            $update = DB::table('sppi')
                ->where('id_sppi', $id)
                ->update([
                    'status_validasi_sppi' => $status_validasi_sppi
                ]);

            if ($update) {
                return Redirect::back()->with(['success' => 'Status Berhasil Diubah']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diproses']);
        }
    }


    public function batalkan_owner_validasi_sppi($id, Request $request)
    {
        $update = DB::table('sppi')
            ->where('id_sppi',$id)
            ->update([
                'status_validasi_sppi' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }








    // BAGIAN MAKER
    public function index_maker_data_staff_sppi(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
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

        // Pagination Hei 
        $sppi = $query->paginate(100);

        return view('maker.data_staff.sppi.index_sppi', compact('sppi'));
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