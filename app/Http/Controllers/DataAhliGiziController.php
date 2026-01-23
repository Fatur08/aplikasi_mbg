<?php

namespace App\Http\Controllers;

use App\Models\AhliGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataAhliGiziController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_staff_ahli_gizi(Request $request)
    {
        $pilih_dapur = $request->pilih_dapur;
        $cari_nama   = $request->cari_nama;

        // Query data staff ahli_gizi
        $query = AhliGizi::query();

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_ahli_gizi', 'like', '%' . $cari_nama . '%');
        }


        // Filter pencarian dapur (jika ada)
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_ahli_gizi', $pilih_dapur);
        }

        // Pagination Hei 
        $ahli_gizi = $query->paginate(100);


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

        return view('owner.data_staff.ahli_gizi.index_ahli_gizi', compact('ahli_gizi', 'dapurList', 'namaDapur'));
    }



    public function validasi_owner_data_staff_ahli_gizi(Request $request)
    {
        $id = $request->id;
        $ahli_gizi = DB::table('ahli_gizi')->get();
        $data = DB::table('ahli_gizi')->where('id_ahli_gizi', $id)->first();
        return view('owner.data_staff.ahli_gizi.validasi_ahli_gizi',compact('ahli_gizi','data'));
    }



    public function update_owner_validasi_ahli_gizi($id, Request $request)
    {
        try {
            $status_validasi_ahli_gizi = $request->status_validasi_ahli_gizi;

            // Update hanya kolom yang perlu
            $update = DB::table('ahli_gizi')
                ->where('id_ahli_gizi', $id)
                ->update([
                    'status_validasi_ahli_gizi' => $status_validasi_ahli_gizi
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


    public function batalkan_owner_validasi_ahli_gizi($id, Request $request)
    {
        $update = DB::table('ahli_gizi')
            ->where('id_ahli_gizi',$id)
            ->update([
                'status_validasi_ahli_gizi' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }
    
















    // BAGIAN MAKER
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