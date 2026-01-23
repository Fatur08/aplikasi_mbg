<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataRelawanController extends Controller
{
    // BAGIAN MAKER
    public function index_maker_data_staff_relawan(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff relawan
        $query = Relawan::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_relawan', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_relawan', 'like', '%' . $cari_nama . '%');
        }

        // Pagination Hei 
        $relawan = $query->paginate(100);

        $divisiList = DB::table('relawan')
            ->select('divisi_relawan')
            ->whereNotNull('divisi_relawan')
            ->where('divisi_relawan', '!=', '')
            ->distinct()
            ->get();

        return view('maker.data_staff.relawan.index_relawan', compact('relawan', 'divisiList'));
    }





    public function store_maker_data_staff_relawan(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_relawan       = $request->nama_relawan;
        $no_hp_relawan      = $request->no_hp_relawan;
        $foto_relawan       = $request->foto_relawan;
        $ktp_relawan        = $request->ktp_relawan;
        $divisi_relawan     = $request->divisi_relawan;
        $old_divisi_relawan = $request->old_divisi_relawan;

        $final_divisi_relawan = !empty($divisi_relawan)
            ? $divisi_relawan
            : $old_divisi_relawan;
    

        if($request->hasFile('foto_relawan')){
            $foto_relawan = $nama_relawan.".".$request
                ->file('foto_relawan')
                ->getClientOriginalExtension();
        } else {
            $foto_relawan = null;
        }


        if($request->hasFile('ktp_relawan')){
            $ktp_relawan = $nama_relawan.".".$request
                ->file('ktp_relawan')
                ->getClientOriginalExtension();
        } else {
            $ktp_relawan = null;
        }

        $data = [
            'nama_relawan'             => $nama_relawan,
            'nomor_dapur_relawan'      => $nomor_dapur,
            'divisi_relawan'           => $final_divisi_relawan,
            'no_hp_relawan'            => $no_hp_relawan,
            'foto_relawan'             => $foto_relawan,
            'ktp_relawan'              => $ktp_relawan,
            'status_validasi_relawan'  => 0
        ];

        $simpan = DB::table('relawan')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_relawan')) {
                $foto_relawan = $nama_relawan.".".$request
                    ->file('foto_relawan')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/relawan/foto/';
                $request->file('foto_relawan')->storeAs($storagePath, $foto_relawan);
                $publicPath = public_path('storage/uploads/data_staff/relawan/foto/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_relawan);
                $destinationFile = public_path('storage/uploads/data_staff/relawan/foto/' . $foto_relawan);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('ktp_relawan')) {
                $ktp_relawan = $nama_relawan.".".$request
                    ->file('ktp_relawan')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/relawan/ktp/';
                $request->file('ktp_relawan')->storeAs($storagePath, $ktp_relawan);
                $publicPath = public_path('storage/uploads/data_staff/relawan/ktp/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $ktp_relawan);
                $destinationFile = public_path('storage/uploads/data_staff/relawan/ktp/' . $ktp_relawan);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }






    public function ktp_maker_data_staff_relawan(Request $request)
    {        
        $id         = $request->id;
        $relawan    = DB::table('relawan')->get();
        $data       = DB::table('relawan')->where('id_relawan', $id)->first();
        return view('maker.data_staff.relawan.ktp_relawan',compact('relawan','data'));
    }
}