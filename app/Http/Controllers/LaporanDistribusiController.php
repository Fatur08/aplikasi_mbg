<?php

namespace App\Http\Controllers;

use App\Models\LaporanDistribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LaporanDistribusiController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_laporan_distribusi(Request $request)
    {
        $pilih_dapur    = $request->pilih_dapur;
        $kecamatan      = $request->cari_dapur_kecamatan_distributor;
        $bulan          = $request->cari_bulan;
    
        // 🔹 Mulai query dasar
        $query = LaporanDistribusi::query();

        // 🔹 Filter berdasarkan kecamatan
        if (!empty($kecamatan)) {
            $query->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%');
        }

        // 🔹 Filter berdasarkan dapur
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_distribusi', $pilih_dapur);
        }

        // ✅ 🔹 Filter berdasarkan bulan saja (TANPA tahun)
        if (!empty($bulan)) {
            $query->whereMonth('tanggal_distribusi', $bulan);
        }

        // 🔹 Eksekusi query
        $distribusi = $query->orderBy('tanggal_distribusi', 'desc')->paginate(300);
        $dataKosong = $distribusi->isEmpty();// 🔹 Cek apakah hasilnya kosong
        $dataKosong = $distribusi->isEmpty();

        // 🔹 Ambil nama distributor (jika ada kecamatan atau dapur dipilih)
        $nama_distributor = '';
        if (!$dataKosong && (!empty($kecamatan) || !empty($pilih_dapur))) {
            $nama_distributor = LaporanDistribusi::query()
                ->when($kecamatan, fn($q) => $q->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%'))
                ->when($pilih_dapur, fn($q) => $q->where('nomor_dapur_distribusi', $pilih_dapur))
                ->value('nama_distributor');
        }

        // 🔹 Deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($kecamatan) || !empty($pilih_dapur);

        
        // 🔹 Ambil nama dapur berdasarkan nomor dapur
            $nama_dapur = '';
            if (!empty($pilih_dapur)) {
                $nama_dapur = DB::table('dapur')
                    ->where('nomor_dapur', $pilih_dapur)
                    ->value('nama_dapur');
            }
        
        
        $data_kecamatan = [];
        if ($pilih_dapur) {
            $data_kecamatan = DB::table('dapur')
                ->where('nomor_dapur', $pilih_dapur)
                ->pluck('dapur_kecamatan')
                ->unique()
                ->values();
        }


        // 🔹 Ambil daftar dapur untuk dropdown filter
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->orderBy('nama_dapur')
            ->get();
        
    
        return view('owner.laporan.distribusi.index_laporan_distribusi', compact(
            'distribusi',
            'nama_distributor',
            'kecamatan',
            'dataKosong',
            'sudahCari',
            'dapurList',
            'data_kecamatan'
        ));
    }

    public function edit_owner_laporan_distribusi(Request $request)
    {
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('owner.laporan.distribusi.edit_laporan_distribusi',compact('distribusi','data'));
    }

    public function update_owner_laporan_distribusi($id, Request $request)
    {
        try {
            $status_distribusi = $request->status_distribusi;

            // Update hanya kolom yang perlu
            $update = DB::table('distribusi')
                ->where('id_distribusi', $id)
                ->update([
                    'status_distribusi' => $status_distribusi
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

    public function delete_owner_laporan_distribusi($id)
    {
        $delete = DB::table('distribusi')->where('id_distribusi', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }

    public function bukti_owner_pengiriman(Request $request)
    {        
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('owner.laporan.distribusi.bukti_pengiriman_laporan_distribusi',compact('distribusi','data'));
    }

    public function kendala_owner_distribusi(Request $request)
    {        
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('owner.laporan.distribusi.kendala_distribusi',compact('distribusi','data'));
    }

    public function batalkan_owner_distribusi($id, Request $request)
    {
        $update = DB::table('distribusi')
            ->where('id_distribusi',$id)
            ->update([
                'status_distribusi' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }











































    // BAGIAN ADMIN
    public function index_admin_laporan_distribusi(Request $request)
    {
        $admin               = DB::table('admin')->where('id_admin', auth()->id())->first();
        $nomor_dapur         = $admin->nomor_dapur_admin ?? null;
        $kecamatan           = $request->cari_kecamatan;
        $bulan               = $request->cari_bulan;
    
        // 🔹 Mulai query dasar
        $query = LaporanDistribusi::query();

        // 🔹 Filter berdasarkan kecamatan
        if (!empty($kecamatan)) {
            $query->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%');
        }

        // 🔹 Filter berdasarkan dapur
        if (!empty($nomor_dapur)) {
            $query->where('nomor_dapur_distribusi', $nomor_dapur);
        }

        // ✅ 🔹 Filter berdasarkan bulan saja (TANPA tahun)
        if (!empty($bulan)) {
            $query->whereMonth('tanggal_distribusi', $bulan);
        }

        // 🔹 Eksekusi query
        $distribusi = $query->orderBy('tanggal_distribusi', 'desc')->paginate(300);
        $dataKosong = $distribusi->isEmpty();// 🔹 Cek apakah hasilnya kosong
        $dataKosong = $distribusi->isEmpty();

        // 🔹 Ambil nama distributor (jika ada kecamatan atau dapur dipilih)
        $nama_distributor = '';
        if (!$dataKosong && (!empty($kecamatan) || !empty($nomor_dapur))) {
            $nama_distributor = LaporanDistribusi::query()
                ->when($kecamatan, fn($q) => $q->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%'))
                ->when($nomor_dapur, fn($q) => $q->where('nomor_dapur_distribusi', $nomor_dapur))
                ->value('nama_distributor');
        }

        // 🔹 Deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($kecamatan) || !empty($nomor_dapur);

        
        // 🔹 Ambil nama dapur berdasarkan nomor dapur
            $nama_dapur = '';
            if (!empty($nomor_dapur)) {
                $nama_dapur = DB::table('dapur')
                    ->where('nomor_dapur', $nomor_dapur)
                    ->value('nama_dapur');
            }

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        
        
        $data_kecamatan = [];
        if ($nomor_dapur) {
            $data_kecamatan = DB::table('dapur')
                ->where('nomor_dapur', $nomor_dapur)
                ->pluck('dapur_kecamatan')
                ->unique()
                ->values();
        }
        
    
        return view('admin.laporan.distribusi.index_laporan_distribusi', compact(
            'distribusi',
            'nama_distributor',
            'kecamatan',
            'dataKosong',
            'sudahCari',
            'dapurList',
            'data_kecamatan'
        ));
    }

    public function edit_admin_laporan_distribusi(Request $request)
    {
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('admin.laporan.distribusi.edit_laporan_distribusi',compact('distribusi','data'));
    }

    public function update_admin_laporan_distribusi($id, Request $request)
    {
        try {
            $status_distribusi = $request->status_distribusi;

            // Update hanya kolom yang perlu
            $update = DB::table('distribusi')
                ->where('id_distribusi', $id)
                ->update([
                    'status_distribusi' => $status_distribusi
                ]);

            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Disetujui']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diproses']);
        }
    }

    public function delete_admin_laporan_distribusi($id)
    {
        $delete = DB::table('distribusi')->where('id_distribusi', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }

    public function bukti_admin_pengiriman(Request $request)
    {        
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('admin.laporan.distribusi.bukti_pengiriman_laporan_distribusi',compact('distribusi','data'));
    }

    public function kendala_admin_distribusi(Request $request)
    {        
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('admin.laporan.distribusi.kendala_distribusi',compact('distribusi','data'));
    }

    public function batalkan_admin_distribusi($id, Request $request)
    {
        $update = DB::table('distribusi')
            ->where('id_distribusi',$id)
            ->update([
                'status_distribusi' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }
}
