<?php

namespace App\Http\Controllers;

use App\Models\Aslap;
use App\Models\DataAslap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class DataAslapController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_aslap(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $pilih_dapur = $request->pilih_dapur;
        $status_validasi_aslap = $request->status_validasi_aslap;
        $query = DataAslap::query()
            ->leftJoin('dapur', 'aslap.nomor_dapur_aslap', '=', 'dapur.nomor_dapur')
            ->select('aslap.*', 'dapur.nama_dapur')
            ->distinct();
        
        if (!empty($nama_lengkap_cari)) {
            $query->where('nama_aslap', 'like', '%' . $nama_lengkap_cari . '%');
        }
        
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_aslap', $pilih_dapur);
        }
        
        if (isset($status_validasi_aslap)) {
            $query->where('status_validasi_aslap', $status_validasi_aslap);
        }
        $query->orderBy('status_validasi_aslap', 'asc');
        $aslap = $query->paginate(50);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        $peranList = DB::table('aslap')
            ->select('peran_aslap')
            ->whereNotNull('peran_aslap')
            ->where('peran_aslap', '!=', '')
            ->distinct()
            ->get();


        // Ambil jumlah total pekerja
        $total_aslap = DataAslap::count();

        // Ambil jumlah pekerja berdasarkan status_validasi_aslap
        $total_menunggu    = DataAslap::where('status_validasi_aslap', 0)->count();
        $total_tervalidasi = DataAslap::where('status_validasi_aslap', 1)->count();
        $total_ditolak     = DataAslap::where('status_validasi_aslap', 2)->count();
        return view('owner.data_induk.aslap.index_aslap', compact('aslap', 'dapurList', 'total_aslap', 'total_menunggu', 'total_tervalidasi', 'total_ditolak', 'peranList'));
    }

    public function store_owner_aslap(Request $request)
    {
        $nama_aslap = $request->nama_aslap;
        $peran_aslap = $request->peran_aslap;
        $no_hp_aslap = $request->no_hp_aslap;
        $nomor_dapur_aslap = $request->nomor_dapur_aslap;

        if($request->hasFile('foto_aslap')){
            $foto_aslap = "Foto_" . $nama_aslap . "." .
                $request->file('foto_aslap')->getClientOriginalExtension();
        } else {
            $foto_aslap = null;
        }



        if($request->hasFile('ktp_aslap')){
            $ktp_aslap = "KTP_". $nama_aslap.".".$request
                ->file('ktp_aslap')
                ->getClientOriginalExtension();
        } else {
            $ktp_aslap = null;
        }




        $data = [
            'nomor_dapur_aslap' => $nomor_dapur_aslap,
            'nama_aslap' => $nama_aslap,
            'peran_aslap' => $peran_aslap,
            'no_hp_aslap' => $no_hp_aslap,
            'foto_aslap' => $foto_aslap,
            'ktp_aslap' => $ktp_aslap,
            'status_validasi_aslap' => 0
        ];

        $simpan = DB::table('aslap')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_aslap')) {
                $foto_aslap = "Foto_" . $nama_aslap . "." .$request->file('foto_aslap')->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/aslap/foto/';
                $request->file('foto_aslap')->storeAs($storagePath, $foto_aslap);
                $publicPath = public_path('storage/uploads/data_induk/aslap/foto/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_aslap);
                $destinationFile = public_path('storage/uploads/data_induk/aslap/foto/' . $foto_aslap);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('ktp_aslap')) {
                $ktp_aslap = "KTP_".$nama_aslap.".".$request
                    ->file('ktp_aslap')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/aslap/ktp/';
                $request->file('ktp_aslap')->storeAs($storagePath, $ktp_aslap);
                $publicPath = public_path('storage/uploads/data_induk/aslap/ktp/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $ktp_aslap);
                $destinationFile = public_path('storage/uploads/data_induk/aslap/ktp/' . $ktp_aslap);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }


    public function ktp_owner_aslap(Request $request)
    {        
        $id = $request->id;
        $aslap = DB::table('aslap')->get();
        $data = DB::table('aslap')->where('id_aslap', $id)->first();
        return view('owner.data_induk.aslap.ktp_aslap',compact('aslap','data'));
    }


    public function edit_owner_aslap(Request $request)
    {
        $id = $request->id;
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        $peranList = DB::table('aslap')
            ->select('peran_aslap')
            ->whereNotNull('peran_aslap')
            ->where('peran_aslap', '!=', '')
            ->distinct()
            ->get();
        $aslap = DB::table('aslap')->get();
        $data = DB::table('aslap')->where('id_aslap', $id)->first();
        return view('owner.data_induk.aslap.edit_aslap',compact('aslap','data','dapurList','peranList'));
    }

    public function update_owner_aslap($id_aslap, Request $request)
    {
        try {
            $nama_aslap = $request->nama_aslap;
            $nomor_dapur_aslap = $request->nomor_dapur_aslap;
            $no_hp_aslap = $request->no_hp_aslap;
            $old_foto_aslap = $request->old_foto_aslap;
            $old_ktp_aslap = $request->old_ktp_aslap;

            $peran_aslap = $request->peran_aslap;
            $old_peran_aslap = $request->old_peran_aslap;

            // Ambil data lama dari database
            $oldData = DB::table('aslap')->where('id_aslap', $id_aslap)->first();

            // Tentukan peran yang akan dipakai
            $final_peran = !empty($peran_aslap)
                ? $peran_aslap
                : (!empty($old_peran_aslap) ? $old_peran_aslap : $oldData->peran_aslap);

            // Siapkan data baru
            $updateData = [
                'nomor_dapur_aslap' => $nomor_dapur_aslap,
                'nama_aslap' => $nama_aslap,
                'peran_aslap' => $final_peran,
                'no_hp_aslap' => $no_hp_aslap,
                'status_validasi_aslap' => 0,
            ];

            // Bandingkan data lama dan baru untuk mendeteksi perubahan
            $hasChange = false;
            foreach ($updateData as $key => $value) {
                if ($oldData->$key != $value) {
                    $hasChange = true;
                    break;
                }
            }

            // Jalankan update
            DB::table('aslap')
                ->where('id_aslap', $id_aslap)
                ->update($updateData);

            // === HANDLE FOTO PEKERJA ===
            if ($request->hasFile('foto_aslap')) {
                $foto_aslap = "Foto_" . $nama_aslap . "." .
                    $request->file('foto_aslap')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/aslap/foto/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/aslap/foto/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_foto_aslap, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('foto_aslap')->storeAs($folderpath, $foto_aslap);
                copy(storage_path('app/' . $folderpath . $foto_aslap), $publicPath . $foto_aslap);

                DB::table('aslap')
                    ->where('id_aslap', $id_aslap)
                    ->update(['foto_aslap' => $foto_aslap]);

                $hasChange = true;
            }

            // === HANDLE KTP PEKERJA ===
            if ($request->hasFile('ktp_aslap')) {
                $ktp_aslap = "KTP_" . $nama_aslap . "." .
                    $request->file('ktp_aslap')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/aslap/ktp/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/aslap/ktp/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_ktp_aslap, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('ktp_aslap')->storeAs($folderpath, $ktp_aslap);
                copy(storage_path('app/' . $folderpath . $ktp_aslap), $publicPath . $ktp_aslap);

                DB::table('aslap')
                    ->where('id_aslap', $id_aslap)
                    ->update(['ktp_aslap' => $ktp_aslap]);

                $hasChange = true;
            }

            // === RESPON ===
            if ($hasChange) {
                return Redirect::back()->with(['success' => 'Data Pekerja Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }

        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Terjadi kesalahan saat update data']);
        }
    }

    public function delete_owner_aslap($id_aslap)
    {
        // Ambil data dulu sebelum dihapus
        $aslap = DB::table('aslap')->where('id_aslap', $id_aslap)->first();
    
        if (!$aslap) {
            return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
        }
    
        // === Hapus file FOTO jika ada ===
        if (!empty($aslap->foto_aslap)) {
            $pathFoto = "uploads/data_induk/aslap/foto/" . $aslap->foto_aslap;
            if (Storage::disk('public')->exists($pathFoto)) {
                Storage::disk('public')->delete($pathFoto);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicFoto = public_path('storage/uploads/data_induk/aslap/foto/' . $aslap->foto_aslap);
            if (file_exists($publicFoto)) {
                unlink($publicFoto);
            }
        }
    
        // === Hapus file KTP jika ada ===
        if (!empty($aslap->ktp_aslap)) {
            $pathKtp = "uploads/data_induk/aslap/ktp/" . $aslap->ktp_aslap;
            if (Storage::disk('public')->exists($pathKtp)) {
                Storage::disk('public')->delete($pathKtp);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicKtp = public_path('storage/uploads/data_induk/aslap/ktp/' . $aslap->ktp_aslap);
            if (file_exists($publicKtp)) {
                unlink($publicKtp);
            }
        }
    
        // === Hapus data di database ===
        DB::table('aslap')->where('id_aslap', $id_aslap)->delete();
    
        return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
    }

    public function status_validasi_owner_aslap(Request $request)
    {
        $id = $request->id;
        $aslap = DB::table('aslap')->get();
        $data = DB::table('aslap')->where('id_aslap', $id)->first();
        return view('owner.data_induk.aslap.status_validasi_aslap',compact('aslap','data'));
    }



    public function update_status_validasi_owner_aslap($id, Request $request)
    {
        try {
            $status_validasi_aslap = $request->status_validasi_aslap;

            // Update hanya kolom yang perlu
            $update = DB::table('aslap')
                ->where('id_aslap', $id)
                ->update([
                    'status_validasi_aslap' => $status_validasi_aslap
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


    public function batalkan_status_validasi_owner_aslap($id, Request $request)
    {
        $update = DB::table('aslap')
            ->where('id_aslap',$id)
            ->update([
                'status_validasi_aslap' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }

























    // BAGIAN MAKER
    public function index_maker_data_staff_aslap(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff aslap
        $query = Aslap::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_aslap', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_aslap', 'like', '%' . $cari_nama . '%');
        }

        // Pagination Hei 
        $aslap = $query->paginate(100);

        return view('maker.data_staff.aslap.index_aslap', compact('aslap'));
    }





    public function store_maker_data_staff_aslap(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_aslap   = $request->nama_aslap;
        $email_aslap  = $request->email_aslap;
        $alamat_aslap = $request->alamat_aslap;
        $no_hp_aslap  = $request->no_hp_aslap;
        $foto_aslap   = $request->foto_aslap;
        

        if($request->hasFile('foto_aslap')){
            $foto_aslap = $nama_aslap.".".$request
                ->file('foto_aslap')
                ->getClientOriginalExtension();
        } else {
            $foto_aslap = null;
        }

        $data = [
            'nama_aslap'             => $nama_aslap,
            'nomor_dapur_aslap'      => $nomor_dapur,
            'email_aslap'            => $email_aslap,
            'alamat_aslap'           => $alamat_aslap,
            'no_hp_aslap'            => $no_hp_aslap,
            'foto_aslap'             =>$foto_aslap,
            'status_validasi_aslap'  => 0
        ];

        $simpan = DB::table('aslap')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_aslap')) {
                $foto_aslap = $nama_aslap.".".$request
                    ->file('foto_aslap')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/aslap/';
                $request->file('foto_aslap')->storeAs($storagePath, $foto_aslap);
                $publicPath = public_path('storage/uploads/data_staff/aslap/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_aslap);
                $destinationFile = public_path('storage/uploads/data_staff/aslap/' . $foto_aslap);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }










    public function ktp_maker_aslap(Request $request)
    {        
        $id = $request->id;
        $aslap = DB::table('aslap')->get();
        $data = DB::table('aslap')->where('id_aslap', $id)->first();
        return view('maker.data_induk.aslap.ktp_aslap',compact('aslap','data'));
    }


    public function edit_maker_aslap(Request $request)
    {
        $id = $request->id;
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        $peranList = DB::table('aslap')
            ->select('peran_aslap')
            ->whereNotNull('peran_aslap')
            ->where('peran_aslap', '!=', '')
            ->distinct()
            ->get();
        $aslap = DB::table('aslap')->get();
        $data = DB::table('aslap')->where('id_aslap', $id)->first();
        return view('maker.data_induk.aslap.edit_aslap',compact('aslap','data','dapurList','peranList'));
    }

    public function update_maker_aslap($id_aslap, Request $request)
    {
        try {
            $nama_aslap = $request->nama_aslap;
            $nomor_dapur_aslap = $request->nomor_dapur_aslap;
            $no_hp_aslap = $request->no_hp_aslap;
            $old_foto_aslap = $request->old_foto_aslap;
            $old_ktp_aslap = $request->old_ktp_aslap;

            $peran_aslap = $request->peran_aslap;
            $old_peran_aslap = $request->old_peran_aslap;

            // Ambil data lama dari database
            $oldData = DB::table('aslap')->where('id_aslap', $id_aslap)->first();

            // Tentukan peran yang akan dipakai
            $final_peran = !empty($peran_aslap)
                ? $peran_aslap
                : (!empty($old_peran_aslap) ? $old_peran_aslap : $oldData->peran_aslap);

            // Siapkan data baru
            $updateData = [
                'nomor_dapur_aslap' => $nomor_dapur_aslap,
                'nama_aslap' => $nama_aslap,
                'peran_aslap' => $final_peran,
                'no_hp_aslap' => $no_hp_aslap,
                'status_validasi_aslap' => 0,
            ];

            // Bandingkan data lama dan baru untuk mendeteksi perubahan
            $hasChange = false;
            foreach ($updateData as $key => $value) {
                if ($oldData->$key != $value) {
                    $hasChange = true;
                    break;
                }
            }

            // Jalankan update
            DB::table('aslap')
                ->where('id_aslap', $id_aslap)
                ->update($updateData);

            // === HANDLE FOTO PEKERJA ===
            if ($request->hasFile('foto_aslap')) {
                $foto_aslap = "Foto_" . $nama_aslap . "." .
                    $request->file('foto_aslap')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/aslap/foto/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/aslap/foto/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_foto_aslap, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('foto_aslap')->storeAs($folderpath, $foto_aslap);
                copy(storage_path('app/' . $folderpath . $foto_aslap), $publicPath . $foto_aslap);

                DB::table('aslap')
                    ->where('id_aslap', $id_aslap)
                    ->update(['foto_aslap' => $foto_aslap]);

                $hasChange = true;
            }

            // === HANDLE KTP PEKERJA ===
            if ($request->hasFile('ktp_aslap')) {
                $ktp_aslap = "KTP_" . $nama_aslap . "." .
                    $request->file('ktp_aslap')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/aslap/ktp/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/aslap/ktp/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_ktp_aslap, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('ktp_aslap')->storeAs($folderpath, $ktp_aslap);
                copy(storage_path('app/' . $folderpath . $ktp_aslap), $publicPath . $ktp_aslap);

                DB::table('aslap')
                    ->where('id_aslap', $id_aslap)
                    ->update(['ktp_aslap' => $ktp_aslap]);

                $hasChange = true;
            }

            // === RESPON ===
            if ($hasChange) {
                return Redirect::back()->with(['success' => 'Data Pekerja Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }

        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Terjadi kesalahan saat update data']);
        }
    }

    public function delete_maker_aslap($id_aslap)
    {
        // Ambil data dulu sebelum dihapus
        $aslap = DB::table('aslap')->where('id_aslap', $id_aslap)->first();
    
        if (!$aslap) {
            return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
        }
    
        // === Hapus file FOTO jika ada ===
        if (!empty($aslap->foto_aslap)) {
            $pathFoto = "uploads/data_induk/aslap/foto/" . $aslap->foto_aslap;
            if (Storage::disk('public')->exists($pathFoto)) {
                Storage::disk('public')->delete($pathFoto);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicFoto = public_path('storage/uploads/data_induk/aslap/foto/' . $aslap->foto_aslap);
            if (file_exists($publicFoto)) {
                unlink($publicFoto);
            }
        }
    
        // === Hapus file KTP jika ada ===
        if (!empty($aslap->ktp_aslap)) {
            $pathKtp = "uploads/data_induk/aslap/ktp/" . $aslap->ktp_aslap;
            if (Storage::disk('public')->exists($pathKtp)) {
                Storage::disk('public')->delete($pathKtp);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicKtp = public_path('storage/uploads/data_induk/aslap/ktp/' . $aslap->ktp_aslap);
            if (file_exists($publicKtp)) {
                unlink($publicKtp);
            }
        }
    
        // === Hapus data di database ===
        DB::table('aslap')->where('id_aslap', $id_aslap)->delete();
    
        return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
    }
}
