<?php

namespace App\Http\Controllers;

use App\Models\Akuntan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataAkuntanController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_staff_akuntan(Request $request)
    {
        $pilih_dapur = $request->pilih_dapur;
        $cari_nama   = $request->cari_nama;

        // Query data staff akuntan
        $query = Akuntan::query();

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_akuntan', 'like', '%' . $cari_nama . '%');
        }


        // Filter pencarian dapur (jika ada)
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_akuntan', $pilih_dapur);
        }

        // Pagination Hei 
        $akuntan = $query->paginate(100);


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

        return view('maker.data_staff.akuntan.index_akuntan', compact('akuntan', 'dapurList', 'namaDapur'));
    }





    public function validasi_owner_data_staff_akuntan(Request $request)
    {
        $id = $request->id;
        $akuntan = DB::table('akuntan')->get();
        $data = DB::table('akuntan')->where('id_akuntan', $id)->first();
        return view('owner.data_staff.akuntan.validasi_akuntan',compact('akuntan','data'));
    }



    public function update_owner_validasi_akuntan($id, Request $request)
    {
        try {
            $status_validasi_akuntan = $request->status_validasi_akuntan;

            // Update hanya kolom yang perlu
            $update = DB::table('akuntan')
                ->where('id_akuntan', $id)
                ->update([
                    'status_validasi_akuntan' => $status_validasi_akuntan
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


    public function batalkan_owner_validasi_akuntan($id, Request $request)
    {
        $update = DB::table('akuntan')
            ->where('id_akuntan',$id)
            ->update([
                'status_validasi_akuntan' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }
    
    














    // BAGIAN MAKER
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