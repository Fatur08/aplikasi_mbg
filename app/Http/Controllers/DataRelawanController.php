<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataRelawanController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_staff_relawan(Request $request)
    {
        $pilih_dapur = $request->pilih_dapur;
        $cari_nama = $request->cari_nama;

        // Query data staff relawan
        $query = Relawan::query();

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_relawan', 'like', '%' . $cari_nama . '%');
        }


        // Filter pencarian dapur (jika ada)
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_relawan', $pilih_dapur);
        }

        // Pagination Hei 
        $relawan = $query->paginate(100);


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

        return view('owner.data_staff.relawan.index_relawan', compact('relawan', 'dapurList', 'namaDapur'));
    }



    public function ktp_owner_data_staff_relawan(Request $request)
    {
        $id = $request->id;
        $relawan = DB::table('relawan')->get();
        $data = DB::table('relawan')->where('id_relawan', $id)->first();
        return view('owner.data_staff.relawan.ktp_relawan', compact('relawan', 'data'));
    }




    public function validasi_owner_data_staff_relawan(Request $request)
    {
        $id = $request->id;
        $relawan = DB::table('relawan')->get();
        $data = DB::table('relawan')->where('id_relawan', $id)->first();
        return view('owner.data_staff.relawan.validasi_relawan', compact('relawan', 'data'));
    }



    public function update_owner_validasi_relawan($id, Request $request)
    {
        try {
            $status_validasi_relawan = $request->status_validasi_relawan;

            // Update hanya kolom yang perlu
            $update = DB::table('relawan')
                ->where('id_relawan', $id)
                ->update([
                    'status_validasi_relawan' => $status_validasi_relawan
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


    public function batalkan_owner_validasi_relawan($id, Request $request)
    {
        $update = DB::table('relawan')
            ->where('id_relawan', $id)
            ->update([
                'status_validasi_relawan' => 0
            ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses']);
        }
    }

















    // BAGIAN MAKER
    public function index_maker_data_staff_relawan(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama = $request->cari_nama;

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
            ->where('nomor_dapur_relawan', $nomor_dapur)
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

        $nama_relawan = $request->nama_relawan;
        $no_hp_relawan = $request->no_hp_relawan;
        $foto_relawan = $request->foto_relawan;
        $ktp_relawan = $request->ktp_relawan;
        $divisi_relawan = $request->divisi_relawan;
        $old_divisi_relawan = $request->old_divisi_relawan;

        $final_divisi_relawan = !empty($divisi_relawan)
            ? $divisi_relawan
            : $old_divisi_relawan;


        if ($request->hasFile('foto_relawan')) {
            $foto_relawan = $nama_relawan . "." . $request
                ->file('foto_relawan')
                ->getClientOriginalExtension();
        } else {
            $foto_relawan = null;
        }


        if ($request->hasFile('ktp_relawan')) {
            $ktp_relawan = $nama_relawan . "." . $request
                ->file('ktp_relawan')
                ->getClientOriginalExtension();
        } else {
            $ktp_relawan = null;
        }

        $data = [
            'nama_relawan' => $nama_relawan,
            'nomor_dapur_relawan' => $nomor_dapur,
            'divisi_relawan' => $final_divisi_relawan,
            'no_hp_relawan' => $no_hp_relawan,
            'foto_relawan' => $foto_relawan,
            'ktp_relawan' => $ktp_relawan,
            'status_validasi_relawan' => 0
        ];

        $simpan = DB::table('relawan')->insert($data);
        if ($simpan) {
            if ($request->hasFile('foto_relawan')) {
                $foto_relawan = $nama_relawan . "." . $request
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
                $ktp_relawan = $nama_relawan . "." . $request
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
        $id = $request->id;
        $relawan = DB::table('relawan')->get();
        $data = DB::table('relawan')->where('id_relawan', $id)->first();
        return view('maker.data_staff.relawan.ktp_relawan', compact('relawan', 'data'));
    }
}