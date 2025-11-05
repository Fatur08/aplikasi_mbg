<?php

namespace App\Http\Controllers;

use App\Models\DataPekerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class DataPekerjaController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_pekerja(Request $request)
    {
        $nama_lengkap_cari = $request->nama_lengkap_cari;
        $pilih_dapur = $request->pilih_dapur;
        $status_validasi_data_pekerja = $request->status_validasi_data_pekerja;
        $query = DataPekerja::query()
            ->leftJoin('dapur', 'data_pekerja.nomor_dapur_data_pekerja', '=', 'dapur.nomor_dapur')
            ->select('data_pekerja.*', 'dapur.nama_dapur')
            ->distinct();
        
        if (!empty($nama_lengkap_cari)) {
            $query->where('nama_data_pekerja', 'like', '%' . $nama_lengkap_cari . '%');
        }
        
        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_data_pekerja', $pilih_dapur);
        }
        
        if (isset($status_validasi_data_pekerja)) {
            $query->where('status_validasi_data_pekerja', $status_validasi_data_pekerja);
        }
        $query->orderBy('status_validasi_data_pekerja', 'asc');
        $data_pekerja = $query->paginate(50);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        $peranList = DB::table('data_pekerja')
            ->select('peran_data_pekerja')
            ->whereNotNull('peran_data_pekerja')
            ->where('peran_data_pekerja', '!=', '')
            ->distinct()
            ->get();


        // Ambil jumlah total pekerja
        $total_data_pekerja = DataPekerja::count();

        // Ambil jumlah pekerja berdasarkan status_validasi_data_pekerja
        $total_menunggu    = DataPekerja::where('status_validasi_data_pekerja', 0)->count();
        $total_tervalidasi = DataPekerja::where('status_validasi_data_pekerja', 1)->count();
        $total_ditolak     = DataPekerja::where('status_validasi_data_pekerja', 2)->count();
        return view('owner.data_induk.data_pekerja.index_data_pekerja', compact('data_pekerja', 'dapurList', 'total_data_pekerja', 'total_menunggu', 'total_tervalidasi', 'total_ditolak', 'peranList'));
    }

    public function store_owner_data_pekerja(Request $request)
    {
        $nama_data_pekerja = $request->nama_data_pekerja;
        $peran_data_pekerja = $request->peran_data_pekerja;
        $no_hp_data_pekerja = $request->no_hp_data_pekerja;
        $nomor_dapur_data_pekerja = $request->nomor_dapur_data_pekerja;

        if($request->hasFile('foto_data_pekerja')){
            $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .
                $request->file('foto_data_pekerja')->getClientOriginalExtension();
        } else {
            $foto_data_pekerja = null;
        }



        if($request->hasFile('ktp_data_pekerja')){
            $ktp_data_pekerja = "KTP_". $nama_data_pekerja.".".$request
                ->file('ktp_data_pekerja')
                ->getClientOriginalExtension();
        } else {
            $ktp_data_pekerja = null;
        }




        $data = [
            'nomor_dapur_data_pekerja' => $nomor_dapur_data_pekerja,
            'nama_data_pekerja' => $nama_data_pekerja,
            'peran_data_pekerja' => $peran_data_pekerja,
            'no_hp_data_pekerja' => $no_hp_data_pekerja,
            'foto_data_pekerja' => $foto_data_pekerja,
            'ktp_data_pekerja' => $ktp_data_pekerja,
            'status_validasi_data_pekerja' => 0
        ];

        $simpan = DB::table('data_pekerja')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_data_pekerja')) {
                $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .$request->file('foto_data_pekerja')->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/data_pekerja/foto/';
                $request->file('foto_data_pekerja')->storeAs($storagePath, $foto_data_pekerja);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/foto/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_data_pekerja);
                $destinationFile = public_path('storage/uploads/data_induk/data_pekerja/foto/' . $foto_data_pekerja);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('ktp_data_pekerja')) {
                $ktp_data_pekerja = "KTP_".$nama_data_pekerja.".".$request
                    ->file('ktp_data_pekerja')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/data_pekerja/ktp/';
                $request->file('ktp_data_pekerja')->storeAs($storagePath, $ktp_data_pekerja);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/ktp/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $ktp_data_pekerja);
                $destinationFile = public_path('storage/uploads/data_induk/data_pekerja/ktp/' . $ktp_data_pekerja);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }


    public function ktp_owner_data_pekerja(Request $request)
    {        
        $id = $request->id;
        $data_pekerja = DB::table('data_pekerja')->get();
        $data = DB::table('data_pekerja')->where('id_data_pekerja', $id)->first();
        return view('owner.data_induk.data_pekerja.ktp_data_pekerja',compact('data_pekerja','data'));
    }


    public function edit_owner_data_pekerja(Request $request)
    {
        $id = $request->id;
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        $peranList = DB::table('data_pekerja')
            ->select('peran_data_pekerja')
            ->whereNotNull('peran_data_pekerja')
            ->where('peran_data_pekerja', '!=', '')
            ->distinct()
            ->get();
        $data_pekerja = DB::table('data_pekerja')->get();
        $data = DB::table('data_pekerja')->where('id_data_pekerja', $id)->first();
        return view('owner.data_induk.data_pekerja.edit_data_pekerja',compact('data_pekerja','data','dapurList','peranList'));
    }

    public function update_owner_data_pekerja($id_data_pekerja, Request $request)
    {
        try {
            $nama_data_pekerja = $request->nama_data_pekerja;
            $nomor_dapur_data_pekerja = $request->nomor_dapur_data_pekerja;
            $no_hp_data_pekerja = $request->no_hp_data_pekerja;
            $old_foto_data_pekerja = $request->old_foto_data_pekerja;
            $old_ktp_data_pekerja = $request->old_ktp_data_pekerja;

            $peran_data_pekerja = $request->peran_data_pekerja;
            $old_peran_data_pekerja = $request->old_peran_data_pekerja;

            // Ambil data lama dari database
            $oldData = DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->first();

            // Tentukan peran yang akan dipakai
            $final_peran = !empty($peran_data_pekerja)
                ? $peran_data_pekerja
                : (!empty($old_peran_data_pekerja) ? $old_peran_data_pekerja : $oldData->peran_data_pekerja);

            // Siapkan data baru
            $updateData = [
                'nomor_dapur_data_pekerja' => $nomor_dapur_data_pekerja,
                'nama_data_pekerja' => $nama_data_pekerja,
                'peran_data_pekerja' => $final_peran,
                'no_hp_data_pekerja' => $no_hp_data_pekerja,
                'status_validasi_data_pekerja' => 0,
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
            DB::table('data_pekerja')
                ->where('id_data_pekerja', $id_data_pekerja)
                ->update($updateData);

            // === HANDLE FOTO PEKERJA ===
            if ($request->hasFile('foto_data_pekerja')) {
                $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .
                    $request->file('foto_data_pekerja')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/data_pekerja/foto/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/foto/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_foto_data_pekerja, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('foto_data_pekerja')->storeAs($folderpath, $foto_data_pekerja);
                copy(storage_path('app/' . $folderpath . $foto_data_pekerja), $publicPath . $foto_data_pekerja);

                DB::table('data_pekerja')
                    ->where('id_data_pekerja', $id_data_pekerja)
                    ->update(['foto_data_pekerja' => $foto_data_pekerja]);

                $hasChange = true;
            }

            // === HANDLE KTP PEKERJA ===
            if ($request->hasFile('ktp_data_pekerja')) {
                $ktp_data_pekerja = "KTP_" . $nama_data_pekerja . "." .
                    $request->file('ktp_data_pekerja')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/data_pekerja/ktp/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/ktp/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_ktp_data_pekerja, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('ktp_data_pekerja')->storeAs($folderpath, $ktp_data_pekerja);
                copy(storage_path('app/' . $folderpath . $ktp_data_pekerja), $publicPath . $ktp_data_pekerja);

                DB::table('data_pekerja')
                    ->where('id_data_pekerja', $id_data_pekerja)
                    ->update(['ktp_data_pekerja' => $ktp_data_pekerja]);

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

    public function delete_owner_data_pekerja($id_data_pekerja)
    {
        // Ambil data dulu sebelum dihapus
        $data_pekerja = DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->first();
    
        if (!$data_pekerja) {
            return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
        }
    
        // === Hapus file FOTO jika ada ===
        if (!empty($data_pekerja->foto_data_pekerja)) {
            $pathFoto = "uploads/data_induk/data_pekerja/foto/" . $data_pekerja->foto_data_pekerja;
            if (Storage::disk('public')->exists($pathFoto)) {
                Storage::disk('public')->delete($pathFoto);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicFoto = public_path('storage/uploads/data_induk/data_pekerja/foto/' . $data_pekerja->foto_data_pekerja);
            if (file_exists($publicFoto)) {
                unlink($publicFoto);
            }
        }
    
        // === Hapus file KTP jika ada ===
        if (!empty($data_pekerja->ktp_data_pekerja)) {
            $pathKtp = "uploads/data_induk/data_pekerja/ktp/" . $data_pekerja->ktp_data_pekerja;
            if (Storage::disk('public')->exists($pathKtp)) {
                Storage::disk('public')->delete($pathKtp);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicKtp = public_path('storage/uploads/data_induk/data_pekerja/ktp/' . $data_pekerja->ktp_data_pekerja);
            if (file_exists($publicKtp)) {
                unlink($publicKtp);
            }
        }
    
        // === Hapus data di database ===
        DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->delete();
    
        return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
    }

    public function status_validasi_owner_data_pekerja(Request $request)
    {
        $id = $request->id;
        $data_pekerja = DB::table('data_pekerja')->get();
        $data = DB::table('data_pekerja')->where('id_data_pekerja', $id)->first();
        return view('owner.data_induk.data_pekerja.status_validasi_data_pekerja',compact('data_pekerja','data'));
    }



    public function update_status_validasi_owner_data_pekerja($id, Request $request)
    {
        try {
            $status_validasi_data_pekerja = $request->status_validasi_data_pekerja;

            // Update hanya kolom yang perlu
            $update = DB::table('data_pekerja')
                ->where('id_data_pekerja', $id)
                ->update([
                    'status_validasi_data_pekerja' => $status_validasi_data_pekerja
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


    public function batalkan_status_validasi_owner_data_pekerja($id, Request $request)
    {
        $update = DB::table('data_pekerja')
            ->where('id_data_pekerja',$id)
            ->update([
                'status_validasi_data_pekerja' => 0
            ]);

        if($update){
            return Redirect::back()->with(['success'=>'Status Berhasil Dibatalkan']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Diproses']);
        }
    }

























    // BAGIAN ADMIN
    public function store_admin_data_pekerja(Request $request)
    {
        $nama_data_pekerja = $request->nama_data_pekerja;
        $peran_data_pekerja = $request->peran_data_pekerja;
        $no_hp_data_pekerja = $request->no_hp_data_pekerja;
        $nomor_dapur_data_pekerja = $request->nomor_dapur_data_pekerja;

        if($request->hasFile('foto_data_pekerja')){
            $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .
                $request->file('foto_data_pekerja')->getClientOriginalExtension();
        } else {
            $foto_data_pekerja = null;
        }



        if($request->hasFile('ktp_data_pekerja')){
            $ktp_data_pekerja = "KTP_". $nama_data_pekerja.".".$request
                ->file('ktp_data_pekerja')
                ->getClientOriginalExtension();
        } else {
            $ktp_data_pekerja = null;
        }




        $data = [
            'nomor_dapur_data_pekerja' => $nomor_dapur_data_pekerja,
            'nama_data_pekerja' => $nama_data_pekerja,
            'peran_data_pekerja' => $peran_data_pekerja,
            'no_hp_data_pekerja' => $no_hp_data_pekerja,
            'foto_data_pekerja' => $foto_data_pekerja,
            'ktp_data_pekerja' => $ktp_data_pekerja,
            'status_validasi_data_pekerja' => 0
        ];

        $simpan = DB::table('data_pekerja')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_data_pekerja')) {
                $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .$request->file('foto_data_pekerja')->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/data_pekerja/foto/';
                $request->file('foto_data_pekerja')->storeAs($storagePath, $foto_data_pekerja);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/foto/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_data_pekerja);
                $destinationFile = public_path('storage/uploads/data_induk/data_pekerja/foto/' . $foto_data_pekerja);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('ktp_data_pekerja')) {
                $ktp_data_pekerja = "KTP_".$nama_data_pekerja.".".$request
                    ->file('ktp_data_pekerja')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_induk/data_pekerja/ktp/';
                $request->file('ktp_data_pekerja')->storeAs($storagePath, $ktp_data_pekerja);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/ktp/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $ktp_data_pekerja);
                $destinationFile = public_path('storage/uploads/data_induk/data_pekerja/ktp/' . $ktp_data_pekerja);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }



    public function ktp_admin_data_pekerja(Request $request)
    {        
        $id = $request->id;
        $data_pekerja = DB::table('data_pekerja')->get();
        $data = DB::table('data_pekerja')->where('id_data_pekerja', $id)->first();
        return view('admin.data_induk.data_pekerja.ktp_data_pekerja',compact('data_pekerja','data'));
    }


    public function edit_admin_data_pekerja(Request $request)
    {
        $id = $request->id;
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        $peranList = DB::table('data_pekerja')
            ->select('peran_data_pekerja')
            ->whereNotNull('peran_data_pekerja')
            ->where('peran_data_pekerja', '!=', '')
            ->distinct()
            ->get();
        $data_pekerja = DB::table('data_pekerja')->get();
        $data = DB::table('data_pekerja')->where('id_data_pekerja', $id)->first();
        return view('admin.data_induk.data_pekerja.edit_data_pekerja',compact('data_pekerja','data','dapurList','peranList'));
    }

    public function update_admin_data_pekerja($id_data_pekerja, Request $request)
    {
        try {
            $nama_data_pekerja = $request->nama_data_pekerja;
            $nomor_dapur_data_pekerja = $request->nomor_dapur_data_pekerja;
            $no_hp_data_pekerja = $request->no_hp_data_pekerja;
            $old_foto_data_pekerja = $request->old_foto_data_pekerja;
            $old_ktp_data_pekerja = $request->old_ktp_data_pekerja;

            $peran_data_pekerja = $request->peran_data_pekerja;
            $old_peran_data_pekerja = $request->old_peran_data_pekerja;

            // Ambil data lama dari database
            $oldData = DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->first();

            // Tentukan peran yang akan dipakai
            $final_peran = !empty($peran_data_pekerja)
                ? $peran_data_pekerja
                : (!empty($old_peran_data_pekerja) ? $old_peran_data_pekerja : $oldData->peran_data_pekerja);

            // Siapkan data baru
            $updateData = [
                'nomor_dapur_data_pekerja' => $nomor_dapur_data_pekerja,
                'nama_data_pekerja' => $nama_data_pekerja,
                'peran_data_pekerja' => $final_peran,
                'no_hp_data_pekerja' => $no_hp_data_pekerja,
                'status_validasi_data_pekerja' => 0,
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
            DB::table('data_pekerja')
                ->where('id_data_pekerja', $id_data_pekerja)
                ->update($updateData);

            // === HANDLE FOTO PEKERJA ===
            if ($request->hasFile('foto_data_pekerja')) {
                $foto_data_pekerja = "Foto_" . $nama_data_pekerja . "." .
                    $request->file('foto_data_pekerja')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/data_pekerja/foto/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/foto/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_foto_data_pekerja, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('foto_data_pekerja')->storeAs($folderpath, $foto_data_pekerja);
                copy(storage_path('app/' . $folderpath . $foto_data_pekerja), $publicPath . $foto_data_pekerja);

                DB::table('data_pekerja')
                    ->where('id_data_pekerja', $id_data_pekerja)
                    ->update(['foto_data_pekerja' => $foto_data_pekerja]);

                $hasChange = true;
            }

            // === HANDLE KTP PEKERJA ===
            if ($request->hasFile('ktp_data_pekerja')) {
                $ktp_data_pekerja = "KTP_" . $nama_data_pekerja . "." .
                    $request->file('ktp_data_pekerja')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_induk/data_pekerja/ktp/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_induk/data_pekerja/ktp/');

                if (!is_dir($publicPath)) mkdir($publicPath, 0777, true);

                // Hapus file lama
                $baseFileName = pathinfo($old_ktp_data_pekerja, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf'];
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                    if (file_exists($oldStorageFile)) unlink($oldStorageFile);
                    if (file_exists($oldPublicFile)) unlink($oldPublicFile);
                }

                // Simpan file baru
                $request->file('ktp_data_pekerja')->storeAs($folderpath, $ktp_data_pekerja);
                copy(storage_path('app/' . $folderpath . $ktp_data_pekerja), $publicPath . $ktp_data_pekerja);

                DB::table('data_pekerja')
                    ->where('id_data_pekerja', $id_data_pekerja)
                    ->update(['ktp_data_pekerja' => $ktp_data_pekerja]);

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

    public function delete_admin_data_pekerja($id_data_pekerja)
    {
        // Ambil data dulu sebelum dihapus
        $data_pekerja = DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->first();
    
        if (!$data_pekerja) {
            return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
        }
    
        // === Hapus file FOTO jika ada ===
        if (!empty($data_pekerja->foto_data_pekerja)) {
            $pathFoto = "uploads/data_induk/data_pekerja/foto/" . $data_pekerja->foto_data_pekerja;
            if (Storage::disk('public')->exists($pathFoto)) {
                Storage::disk('public')->delete($pathFoto);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicFoto = public_path('storage/uploads/data_induk/data_pekerja/foto/' . $data_pekerja->foto_data_pekerja);
            if (file_exists($publicFoto)) {
                unlink($publicFoto);
            }
        }
    
        // === Hapus file KTP jika ada ===
        if (!empty($data_pekerja->ktp_data_pekerja)) {
            $pathKtp = "uploads/data_induk/data_pekerja/ktp/" . $data_pekerja->ktp_data_pekerja;
            if (Storage::disk('public')->exists($pathKtp)) {
                Storage::disk('public')->delete($pathKtp);
            }
        
            // Hapus juga file di public_path jika disalin ke sana
            $publicKtp = public_path('storage/uploads/data_induk/data_pekerja/ktp/' . $data_pekerja->ktp_data_pekerja);
            if (file_exists($publicKtp)) {
                unlink($publicKtp);
            }
        }
    
        // === Hapus data di database ===
        DB::table('data_pekerja')->where('id_data_pekerja', $id_data_pekerja)->delete();
    
        return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
    }
}
