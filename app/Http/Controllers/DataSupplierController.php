<?php

namespace App\Http\Controllers;

use App\Models\BarangSupplier;
use App\Models\Supplier;
use App\Models\InformasiSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DataSupplierController extends Controller
{
    // BAGIAAN OWNER
    // BAGIAN SUPPLIER
    public function index_owner_supplier(Request $request)
    {        
        $nama_supplier_cari = $request->nama_supplier_cari;
        $query = Supplier::query();
        $query->select('*');
        if(!empty($nama_supplier_cari)){
            $query->where('nama_supplier','like','%'.$nama_supplier_cari.'%');
        }
        $supplier = $query->get();
        $supplier = $query->paginate(10);
        $nama_supplier = DB::table('supplier')->select('id_supplier', 'nama_supplier')->get();
        return view('owner.data_supplier.supplier.index_supplier' ,compact('supplier'));
    }

    public function store_owner_supplier(Request $request)
    {
        $nama_supplier = $request->nama_supplier;
        $alamat_supplier = $request->alamat_supplier;
        $no_hp = $request->no_hp;

        $data = [
            'nama_supplier' => $nama_supplier,
            'alamat_supplier' => $alamat_supplier,
            'no_hp_supplier' => $no_hp
        ];

        $simpan = DB::table('supplier')->insert($data);
        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_supplier(Request $request)
    {
        $id = $request->id;
        $supplier = DB::table('supplier')->get();
        $data = DB::table('supplier')->where('id_supplier', $id)->first();
        return view('owner.data_supplier.supplier.edit_supplier',compact('supplier','data'));
    }

    public function update_owner_supplier($id, Request $request)
    {
        $nama_supplier = $request->nama_supplier;
        $alamat_supplier = $request->alamat_supplier;
        $no_hp_supplier = $request->no_hp_supplier;

        try {
            // Update data supplier
            $data = [
                'nama_supplier' => $nama_supplier,
                'alamat_supplier' => $alamat_supplier,
                'no_hp_supplier' => $no_hp_supplier
            ];
            $update = DB::table('supplier')->where('id_supplier', $id)->update($data);

            if ($update) {
                // Ambil data informasi_supplier terkait supplier ini
                $informasi = DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->first();

                if ($informasi) {
                    // --- Update Nama ---
                    $nama_informasi_supplier = $nama_supplier;

                    // --- Handle Nota ---
                    $nota = $informasi->nota_informasi_supplier;
                    if ($nota) {
                        $extension = pathinfo($nota, PATHINFO_EXTENSION);
                        $newNota = "Nota_" . $nama_supplier . "." . $extension;

                        $folderNota = "public/uploads/data_supplier/informasi_supplier/nota/";
                        $oldFile = $folderNota . $nota;
                        $newFile = $folderNota . $newNota;

                        if ($oldFile !== $newFile && Storage::exists($oldFile)) {
                            Storage::move($oldFile, $newFile);
                        }

                        $nota = $newNota;
                    }

                    // --- Handle Bukti Terima ---
                    $bukti_terima = $informasi->bukti_terima_informasi_supplier;
                    if ($bukti_terima) {
                        $extension = pathinfo($bukti_terima, PATHINFO_EXTENSION);
                        $newBukti = "Bukti Terima_" . $nama_supplier . "." . $extension;

                        $folderBukti = "public/uploads/data_supplier/informasi_supplier/bukti_terima/";
                        $oldFile = $folderBukti . $bukti_terima;
                        $newFile = $folderBukti . $newBukti;

                        if ($oldFile !== $newFile && Storage::exists($oldFile)) {
                            Storage::move($oldFile, $newFile);
                        }

                        $bukti_terima = $newBukti;
                    }

                    // Update informasi_supplier
                    DB::table('informasi_supplier')
                        ->where('id_informasi_supplier', $id)
                        ->update([
                            'nama_informasi_supplier' => $nama_informasi_supplier,
                            'nota_informasi_supplier' => $nota,
                            'bukti_terima_informasi_supplier' => $bukti_terima,
                        ]);
                }

                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function validasi_owner_supplier(Request $request)
    {
        $id = $request->id;
        $supplier = DB::table('supplier')->get();
        $data = DB::table('supplier')->where('id_supplier', $id)->first();
        return view('owner.data_supplier.supplier.validasi_supplier',compact('supplier','data'));
    }

    public function update_validasi_owner_supplier($id, Request $request)
    {
        $id = $request->id;
        $status_supplier = $request->status_supplier;

        try {
            // Update data supplier
            $data = [
                'status_supplier' => $status_supplier
            ];
            $update = DB::table('supplier')->where('id_supplier', $id)->update($data);

            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Divalidasi']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Divalidasi']);
        }
    }

    public function batalkan_validasi_owner_supplier($id, Request $request)
    {
        $id = $request->id;

        try {
            // Update data supplier
            $data = [
                'status_supplier' => 0
            ];
            $update = DB::table('supplier')->where('id_supplier', $id)->update($data);

            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Divalidasi']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Divalidasi']);
        }
    }

    public function delete_owner_supplier($id)
    {
        $delete = DB::table('supplier')->where('id_supplier', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }



    // BAGIAN OWNER
    // BAGIAN INFORMASI SUPPLIER
    public function index_owner_informasi_supplier(Request $request)
    {        
        $nama_supplier_cari = $request->nama_supplier_cari;
        $pilih_dapur = $request->pilih_dapur;

        $query = InformasiSupplier::query()
            ->with(['BarangSupplier' => function($q) {
                $q->select('id_informasi_supplier', 'harga_barang_supplier', 'jumlah_barang_supplier');
            }])
            ->leftJoin('dapur', 'informasi_supplier.nomor_dapur_informasi_supplier', '=', 'dapur.nomor_dapur')
            ->select('informasi_supplier.*', 'dapur.nama_dapur')
            ->distinct();

        if (!empty($nama_supplier_cari)) {
            $query->where('nama_informasi_supplier', 'like', '%' . $nama_supplier_cari . '%');
        }

        if (!empty($pilih_dapur)) {
            $query->where('nomor_dapur_informasi_supplier', $pilih_dapur);
        }

        // Ambil data dengan pagination
        $informasi_supplier = $query->paginate(100);

        // Hitung total harga otomatis dari tabel barang supplier
        foreach ($informasi_supplier as $info) {
            $info->total_otomatis = $info->BarangSupplier->sum(function($barang) {
                return $barang->harga_barang_supplier;
            });
        }

        $nama_supplier = DB::table('supplier')->select('id_supplier', 'nama_supplier')->get();


        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('owner.data_supplier.informasi_supplier.index_informasi_supplier', compact('nama_supplier', 'informasi_supplier', 'dapurList'));
    }

    public function store_owner_informasi_supplier(Request $request)
    {
        $id_supplier = $request->id_supplier;
        $nama_informasi_supplier = DB::table('supplier')->where('id_supplier', $id_supplier)->value('nama_supplier');

        if($request->hasFile('nota_informasi_supplier')){
            $nota_informasi_supplier = "Nota_".$nama_informasi_supplier.".".$request
                ->file('nota_informasi_supplier')
                ->getClientOriginalExtension();
        } else {
            $nota_informasi_supplier = null;
        }

        if($request->hasFile('bukti_terima_informasi_supplier')){
            $bukti_terima_informasi_supplier = "Bukti Terima_".$nama_informasi_supplier.".".$request
                ->file('bukti_terima_informasi_supplier')
                ->getClientOriginalExtension();
        } else {
            $bukti_terima_informasi_supplier = null;
        }

        $data = [
            'id_informasi_supplier' => $id_supplier,
            'nama_informasi_supplier' => $nama_informasi_supplier,
            'nota_informasi_supplier'=>$nota_informasi_supplier,
            'bukti_terima_informasi_supplier' => $bukti_terima_informasi_supplier
        ];

        $simpan = DB::table('informasi_supplier')->insert($data);
        if ($simpan){
            if ($request->hasFile('nota_informasi_supplier')) {
                $nota_informasi_supplier = "Nota_".$nama_informasi_supplier.".".$request
                    ->file('nota_informasi_supplier')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_supplier/informasi_supplier/nota/';
                $request->file('nota_informasi_supplier')->storeAs($storagePath, $nota_informasi_supplier);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/nota/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $nota_informasi_supplier);
                $destinationFile = public_path('storage/uploads/data_supplier/informasi_supplier/nota/' . $nota_informasi_supplier);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('bukti_terima_informasi_supplier')) {
                $bukti_terima_informasi_supplier = "Bukti Terima_".$nama_informasi_supplier.".".$request
                    ->file('bukti_terima_informasi_supplier')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_supplier/informasi_supplier/bukti_terima/';
                $request->file('bukti_terima_informasi_supplier')->storeAs($storagePath, $bukti_terima_informasi_supplier);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $bukti_terima_informasi_supplier);
                $destinationFile = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $bukti_terima_informasi_supplier);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_informasi_supplier(Request $request)
    {
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('owner.data_supplier.informasi_supplier.edit_informasi_supplier',compact('informasi_supplier','data'));
    }

    public function update_owner_informasi_supplier($id, Request $request)
    {
        $informasi_supplier = DB::table('informasi_supplier')
            ->where('id_informasi_supplier', $id)
            ->select('nama_informasi_supplier')
            ->first();

        $nama_informasi_supplier = $informasi_supplier ? $informasi_supplier->nama_informasi_supplier : null;
        $old_nota = $request->old_nota_informasi_supplier;
        $old_bukti_terima = $request->old_bukti_terima_informasi_supplier;

        try {
            // Handle Nota
            if ($request->hasFile('nota_informasi_supplier')) {
                $nota_informasi_supplier = "Nota_" . $nama_informasi_supplier . "." . 
                    $request->file('nota_informasi_supplier')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_supplier/informasi_supplier/nota/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/nota/');

                // Pastikan folder public ada
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
            
                // 🔥 Hapus semua file lama yang punya nama dasar sama (terlepas dari ekstensi)
                $baseFileName = pathinfo($old_nota, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'pdf', 'webp']; // tambahkan jika perlu
            
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                
                    if (file_exists($oldStorageFile)) {
                        unlink($oldStorageFile);
                    }
                    if (file_exists($oldPublicFile)) {
                        unlink($oldPublicFile);
                    }
                }
            
                // Simpan file baru ke storage
                $request->file('nota_informasi_supplier')->storeAs($folderpath, $nota_informasi_supplier);
            
                // Salin ke public
                $sourceFile = storage_path('app/' . $folderpath . $nota_informasi_supplier);
                $destinationFile = $publicPath . $nota_informasi_supplier;
                copy($sourceFile, $destinationFile);

                // ✅ Update nama file baru di database
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->update(['nota_informasi_supplier' => $nota_informasi_supplier]);
            
                $hasChange = true;
            }
        
            // Handle Bukti Terima
            if ($request->hasFile('bukti_terima_informasi_supplier')) {
                $bukti_terima_informasi_supplier = "Bukti Terima_" . $nama_informasi_supplier . "." . 
                    $request->file('bukti_terima_informasi_supplier')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_supplier/informasi_supplier/bukti_terima/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/');

                // Pastikan folder public ada
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
            
                // 🔥 Hapus semua file lama yang punya nama dasar sama (terlepas dari ekstensi)
                $baseFileName = pathinfo($old_bukti_terima, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'pdf', 'webp']; // tambahkan jika perlu
            
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                
                    if (file_exists($oldStorageFile)) {
                        unlink($oldStorageFile);
                    }
                    if (file_exists($oldPublicFile)) {
                        unlink($oldPublicFile);
                    }
                }
            
                // Simpan file baru ke storage
                $request->file('bukti_terima_informasi_supplier')->storeAs($folderpath, $bukti_terima_informasi_supplier);
            
                // Salin ke public
                $sourceFile = storage_path('app/' . $folderpath . $bukti_terima_informasi_supplier);
                $destinationFile = $publicPath . $bukti_terima_informasi_supplier;
                copy($sourceFile, $destinationFile);

                // ✅ Update nama file baru di database
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->update(['bukti_terima_informasi_supplier' => $bukti_terima_informasi_supplier]);
            
                $hasChange = true;
            }

            if ($hasChange){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_owner_informasi_supplier($id)
    {
        $informasi = DB::table('informasi_supplier')
            ->where('id_informasi_supplier', $id)
            ->first();

        if ($informasi) {
            // Hapus file nota jika ada
            if (!empty($informasi->nota_informasi_supplier)) {
                $pathNota = "uploads/data_supplier/informasi_supplier/nota/" . $informasi->nota_informasi_supplier;
                if (Storage::disk('public')->exists($pathNota)) {
                    Storage::disk('public')->delete($pathNota);
                }
            }

            // Hapus file bukti terima jika ada
            if (!empty($informasi->bukti_terima_informasi_supplier)) {
                $pathBukti = "uploads/data_supplier/informasi_supplier/bukti_terima/" . $informasi->bukti_terima_informasi_supplier;
                if (Storage::disk('public')->exists($pathBukti)) {
                    Storage::disk('public')->delete($pathBukti);
                }
            }

            // Hapus data di database
            DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->delete();

            return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
        }

        return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
    }

    public function nota_owner_informasi_supplier(Request $request)
    {        
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('owner.data_supplier.informasi_supplier.nota_informasi_supplier',compact('informasi_supplier','data'));
    }

    public function bukti_terima_owner_informasi_supplier(Request $request)
    {        
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('owner.data_supplier.informasi_supplier.bukti_terima_informasi_supplier',compact('informasi_supplier','data'));
    }


    public function validasi_owner_informasi_supplier(Request $request)
    {
        $id = $request->id;
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('owner.data_supplier.informasi_supplier.validasi_informasi_supplier',compact('data'));
    }

    public function update_validasi_owner_informasi_supplier($id, Request $request)
    {
        $id = $request->id;
        $status_informasi_supplier = $request->status_informasi_supplier;

        try {
            DB::beginTransaction();

            // 1️⃣ Update status informasi supplier
            $updateSupplier = DB::table('informasi_supplier')
                ->where('id_informasi_supplier', $id)
                ->update([
                    'status_informasi_supplier' => $status_informasi_supplier
                ]);

            // 2️⃣ Jika berhasil, update juga status data koperasi yang berkaitan
            if ($updateSupplier) {
                DB::table('data_koperasi')
                    ->where('id_informasi_supplier', $id)
                    ->update([
                        'status_data_koperasi' => $status_informasi_supplier
                    ]);
            }

            DB::commit();

            return Redirect::back()->with(['success' => 'Data Berhasil Divalidasi']);
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Divalidasi: ' . $e->getMessage()]);
        }
    }

    public function batalkan_validasi_owner_informasi_supplier(Request $request)
    {
        try {
            $id = $request->id;
        
            // Update status di tabel informasi_supplier
            DB::table('informasi_supplier')
                ->where('id_informasi_supplier', $id)
                ->update([
                    'status_informasi_supplier' => 0
                ]);
            
            // Update status di tabel data_koperasi
            DB::table('data_koperasi')
                ->where('id_informasi_supplier', $id)
                ->update([
                    'status_data_koperasi' => 0
                ]);
            
            return Redirect::back()->with(['success' => 'Data Berhasil Divalidasi']);
        
        } catch (\Exception $e) {
            // dd($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan validasi.'
            ]);
        }
    }


    public function tambah_owner_barang_supplier(Request $request)
    {
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('owner.data_supplier.informasi_supplier.tambah_barang_supplier',compact('informasi_supplier','data'));
    }

    public function store_owner_barang_supplier(Request $request)
    {
        $nomor_dapur = 1;
        $id_informasi_supplier = $request->id;
        $tanggal_hari_ini = date('Y-m-d');

        // Daftar input barang dari form
        $inputBarang = [
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_1,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_1,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_1,
                'harga_barang_supplier'   => $request->harga_barang_supplier_1,
            ],
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_2,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_2,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_2,
                'harga_barang_supplier'   => $request->harga_barang_supplier_2,
            ],
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_3,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_3,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_3,
                'harga_barang_supplier'   => $request->harga_barang_supplier_3,
            ],
        ];

        $dataInsert = [];

        // Loop setiap barang dan hanya ambil yang diisi
        foreach ($inputBarang as $barang) {
            if (!empty($barang['nama_barang_supplier'])) {
                $dataInsert[] = [
                    'id_informasi_supplier'       => $id_informasi_supplier,
                    'nomor_dapur_barang_supplier' => $nomor_dapur,
                    'nama_barang_supplier'        => $barang['nama_barang_supplier'],
                    'jumlah_barang_supplier'      => $barang['jumlah_barang_supplier'] ?? 0,
                    'satuan_barang_supplier'      => $barang['satuan_barang_supplier'] ?? '-',
                    'harga_barang_supplier'       => $barang['harga_barang_supplier'] ?? 0,
                ];
            }
        }

        // Validasi minimal satu barang
        if (empty($dataInsert)) {
            return Redirect::back()->with(['warning' => 'Minimal isi satu barang terlebih dahulu']);
        }

        DB::beginTransaction();

        try {
            // Simpan barang supplier baru
            DB::table('barang_supplier')->insert($dataInsert);

            // Ubah status informasi supplier menjadi 0 (belum divalidasi)
            DB::table('informasi_supplier')
                ->where('id_informasi_supplier', $id_informasi_supplier)
                ->update(['status_informasi_supplier' => 0]);

            // Hitung total harga barang yang dimasukkan hari ini untuk nomor dapur & informasi supplier terkait
            $totalHariIni = BarangSupplier::where('nomor_dapur_barang_supplier', $nomor_dapur)
                ->sum(DB::raw('harga_barang_supplier'));

            // Cek apakah sudah ada data koperasi hari ini
            $dataKoperasi = DB::table('data_koperasi')
                ->where('nomor_dapur_data_koperasi', $nomor_dapur)
                ->where('tanggal_data_koperasi', $tanggal_hari_ini)
                ->first();

            if ($dataKoperasi) {
                // Jika sudah ada → update total harga (harga_data_koperasi)
                DB::table('data_koperasi')
                    ->where('id_data_koperasi', $dataKoperasi->id_data_koperasi)
                    ->update([
                        'harga_data_koperasi' => $totalHariIni,
                        'status_data_koperasi' => 0
                    ]);
            } else {
                // Jika belum ada → buat data koperasi baru
                DB::table('data_koperasi')->insert([
                    'id_informasi_supplier' => $id_informasi_supplier,
                    'nomor_dapur_data_koperasi' => $nomor_dapur,
                    'tanggal_data_koperasi' => $tanggal_hari_ini,
                    'jenis_data_koperasi' => 'Pengeluaran',
                    'kategori_data_koperasi' => 'Pembelian bahan dari supplier',
                    'harga_data_koperasi' => $totalHariIni,
                    'status_data_koperasi' => 0,
                ]);
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data barang supplier berhasil disimpan dan dicatat ke data koperasi']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function lihat_owner_barang_supplier(Request $request)
    {
        $nomor_dapur = 1;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_informasi_supplier = $request->id;

        // Ambil data bahan dari tabel bahan_menu
        $barang_supplier = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
            ->select(
                'barang_supplier.*',
                'barang_supplier.nama_barang_supplier',
                'barang_supplier.jumlah_barang_supplier',
                'barang_supplier.satuan_barang_supplier',
                'barang_supplier.harga_barang_supplier'
            )
            ->get();

        return view('owner.data_supplier.informasi_supplier.lihat_barang_supplier', compact('barang_supplier'));
    }





























    // BAGIAAN ADMIN
    // BAGIAN SUPPLIER
    public function index_admin_supplier(Request $request)
    {        
        $nama_supplier_cari = $request->nama_supplier_cari;
        $query = Supplier::query();
        $query->select('*');
        if(!empty($nama_supplier_cari)){
            $query->where('nama_supplier','like','%'.$nama_supplier_cari.'%');
        }
        $supplier = $query->get();
        $supplier = $query->paginate(10);
        $nama_supplier = DB::table('supplier')->select('id_supplier', 'nama_supplier')->get();
        return view('admin.data_supplier.supplier.index_supplier' ,compact('supplier'));
    }

    public function store_admin_supplier(Request $request)
    {
        $nama_supplier = $request->nama_supplier;
        $alamat_supplier = $request->alamat_supplier;
        $no_hp = $request->no_hp;

        $data = [
            'nama_supplier' => $nama_supplier,
            'alamat_supplier' => $alamat_supplier,
            'no_hp_supplier' => $no_hp,
            'status_supplier' => 0
        ];

        $simpan = DB::table('supplier')->insert($data);
        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_admin_supplier(Request $request)
    {
        $id = $request->id;
        $supplier = DB::table('supplier')->get();
        $data = DB::table('supplier')->where('id_supplier', $id)->first();
        return view('admin.data_supplier.supplier.edit_supplier',compact('supplier','data'));
    }

    public function update_admin_supplier($id, Request $request)
    {
        $nama_supplier = $request->nama_supplier;
        $alamat_supplier = $request->alamat_supplier;
        $no_hp_supplier = $request->no_hp_supplier;

        try {
            // Update data supplier
            $data = [
                'nama_supplier' => $nama_supplier,
                'alamat_supplier' => $alamat_supplier,
                'no_hp_supplier' => $no_hp_supplier
            ];
            $update = DB::table('supplier')->where('id_supplier', $id)->update($data);

            if ($update) {
                // Ambil data informasi_supplier terkait supplier ini
                $informasi = DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->first();

                if ($informasi) {
                    // --- Update Nama ---
                    $nama_informasi_supplier = $nama_supplier;

                    // --- Handle Nota ---
                    $nota_informasi_supplier = $informasi->nota_informasi_supplier;
                    if ($nota_informasi_supplier) {
                        $extension = pathinfo($nota_informasi_supplier, PATHINFO_EXTENSION);
                        $newNota = "Nota_" . $nama_supplier . "." . $extension;

                        $folderNota = "public/uploads/data_supplier/informasi_supplier/nota/";
                        $oldFile = $folderNota . $nota_informasi_supplier;
                        $newFile = $folderNota . $newNota;

                        // Rename file di storage
                        if ($oldFile !== $newFile && Storage::exists($oldFile)) {
                            Storage::move($oldFile, $newFile);
                        }
                    
                        // Rename juga file di folder public
                        $oldPublicFile = public_path('storage/uploads/data_supplier/informasi_supplier/nota/' . $nota_informasi_supplier);
                        $newPublicFile = public_path('storage/uploads/data_supplier/informasi_supplier/nota/' . $newNota);
                        if (file_exists($oldPublicFile)) {
                            rename($oldPublicFile, $newPublicFile);
                        }
                    
                        $nota_informasi_supplier = $newNota;
                    }

                    // --- Handle Bukti Terima ---
                    $bukti_terima_informasi_supplier = $informasi->bukti_terima_informasi_supplier;
                    if ($bukti_terima_informasi_supplier) {
                        $extension = pathinfo($bukti_terima_informasi_supplier, PATHINFO_EXTENSION);
                        $newBuktiTerima = "Bukti Terima_" . $nama_supplier . "." . $extension;

                        $folderBuktiTerima = "public/uploads/data_supplier/informasi_supplier/bukti_terima/";
                        $oldFile = $folderBuktiTerima . $bukti_terima_informasi_supplier;
                        $newFile = $folderBuktiTerima . $newBuktiTerima;

                        // Rename file di storage
                        if ($oldFile !== $newFile && Storage::exists($oldFile)) {
                            Storage::move($oldFile, $newFile);
                        }
                    
                        // Rename juga file di folder public
                        $oldPublicFile = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $bukti_terima_informasi_supplier);
                        $newPublicFile = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $newBuktiTerima);
                        if (file_exists($oldPublicFile)) {
                            rename($oldPublicFile, $newPublicFile);
                        }
                    
                        $bukti_terima_informasi_supplier = $newBuktiTerima;
                    }

                    // Update informasi_supplier
                    DB::table('informasi_supplier')
                        ->where('id_informasi_supplier', $id)
                        ->update([
                            'nama_informasi_supplier' => $nama_informasi_supplier,
                            'nota_informasi_supplier' => $nota_informasi_supplier,
                            'bukti_terima_informasi_supplier' => $bukti_terima_informasi_supplier,
                        ]);
                }

                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_admin_supplier($id)
    {
        $delete = DB::table('supplier')->where('id_supplier', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }














    // BAGIAN ADMIN
    // BAGIAN INFORMASI SUPPLIER
    public function index_admin_informasi_supplier(Request $request)
    {
        $nama_supplier_cari = $request->nama_supplier_cari;

        $query = InformasiSupplier::with(['BarangSupplier' => function($q) {
            $q->select('id_informasi_supplier', 'harga_barang_supplier', 'jumlah_barang_supplier');
        }]);

        if (!empty($nama_supplier_cari)) {
            $query->where('nama_informasi_supplier', 'like', '%' . $nama_supplier_cari . '%');
        }

        // Ambil data dengan pagination
        $informasi_supplier = $query->paginate(10);

        // Hitung total harga otomatis dari tabel barang supplier
        foreach ($informasi_supplier as $info) {
            $info->total_otomatis = $info->BarangSupplier->sum(function($barang) {
                return $barang->harga_barang_supplier;
            });
        }

        $nama_supplier = DB::table('supplier')->select('id_supplier', 'nama_supplier')->get();

        return view('admin.data_supplier.informasi_supplier.index_informasi_supplier', compact('nama_supplier', 'informasi_supplier'));
    }

    public function store_admin_informasi_supplier(Request $request)
    {
        $id_supplier = $request->id_supplier;
        $nama_informasi_supplier = DB::table('supplier')->where('id_supplier', $id_supplier)->value('nama_supplier');

        if($request->hasFile('nota_informasi_supplier')){
            $nota_informasi_supplier = "Nota_".$nama_informasi_supplier.".".$request
                ->file('nota_informasi_supplier')
                ->getClientOriginalExtension();
        } else {
            $nota_informasi_supplier = null;
        }

        if($request->hasFile('bukti_terima_informasi_supplier')){
            $bukti_terima_informasi_supplier = "Bukti Terima_".$nama_informasi_supplier.".".$request
                ->file('bukti_terima_informasi_supplier')
                ->getClientOriginalExtension();
        } else {
            $bukti_terima_informasi_supplier = null;
        }

        $data = [
            'id_informasi_supplier' => $id_supplier,
            'nama_informasi_supplier' => $nama_informasi_supplier,
            'nota_informasi_supplier'=>$nota_informasi_supplier,
            'bukti_terima_informasi_supplier' => $bukti_terima_informasi_supplier
        ];

        $simpan = DB::table('informasi_supplier')->insert($data);
        if ($simpan){
            if ($request->hasFile('nota_informasi_supplier')) {
                $nota_informasi_supplier = "Nota_".$nama_informasi_supplier.".".$request
                    ->file('nota_informasi_supplier')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_supplier/informasi_supplier/nota/';
                $request->file('nota_informasi_supplier')->storeAs($storagePath, $nota_informasi_supplier);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/nota/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $nota_informasi_supplier);
                $destinationFile = public_path('storage/uploads/data_supplier/informasi_supplier/nota/' . $nota_informasi_supplier);
                copy($sourceFile, $destinationFile);
            }
            if ($request->hasFile('bukti_terima_informasi_supplier')) {
                $bukti_terima_informasi_supplier = "Bukti Terima_".$nama_informasi_supplier.".".$request
                    ->file('bukti_terima_informasi_supplier')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_supplier/informasi_supplier/bukti_terima/';
                $request->file('bukti_terima_informasi_supplier')->storeAs($storagePath, $bukti_terima_informasi_supplier);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $bukti_terima_informasi_supplier);
                $destinationFile = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $bukti_terima_informasi_supplier);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_admin_informasi_supplier(Request $request)
    {
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('admin.data_supplier.informasi_supplier.edit_informasi_supplier',compact('informasi_supplier','data'));
    }

    public function update_admin_informasi_supplier($id, Request $request)
    {
        $informasi_supplier = DB::table('informasi_supplier')
            ->where('id_informasi_supplier', $id)
            ->select('nama_informasi_supplier')
            ->first();

        $nama_informasi_supplier = $informasi_supplier ? $informasi_supplier->nama_informasi_supplier : null;
        $old_nota = $request->old_nota_informasi_supplier;
        $old_bukti_terima = $request->old_bukti_terima_informasi_supplier;

        try {
            // Handle Nota
            if ($request->hasFile('nota_informasi_supplier')) {
                $nota_informasi_supplier = "Nota_" . $nama_informasi_supplier . "." . 
                    $request->file('nota_informasi_supplier')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_supplier/informasi_supplier/nota/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/nota/');

                // Pastikan folder public ada
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
            
                // 🔥 Hapus semua file lama yang punya nama dasar sama (terlepas dari ekstensi)
                $baseFileName = pathinfo($old_nota, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'pdf', 'webp']; // tambahkan jika perlu
            
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                
                    if (file_exists($oldStorageFile)) {
                        unlink($oldStorageFile);
                    }
                    if (file_exists($oldPublicFile)) {
                        unlink($oldPublicFile);
                    }
                }
            
                // Simpan file baru ke storage
                $request->file('nota_informasi_supplier')->storeAs($folderpath, $nota_informasi_supplier);
            
                // Salin ke public
                $sourceFile = storage_path('app/' . $folderpath . $nota_informasi_supplier);
                $destinationFile = $publicPath . $nota_informasi_supplier;
                copy($sourceFile, $destinationFile);

                // ✅ Update nama file baru di database
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->update(['nota_informasi_supplier' => $nota_informasi_supplier]);
            
                $hasChange = true;
            }
        
            // Handle Bukti Terima
            if ($request->hasFile('bukti_terima_informasi_supplier')) {
                $bukti_terima_informasi_supplier = "Bukti Terima_" . $nama_informasi_supplier . "." . 
                    $request->file('bukti_terima_informasi_supplier')->getClientOriginalExtension();

                $folderpath = "public/uploads/data_supplier/informasi_supplier/bukti_terima/";
                $storageFolderPath = storage_path('app/' . $folderpath);
                $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/');

                // Pastikan folder public ada
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
            
                // 🔥 Hapus semua file lama yang punya nama dasar sama (terlepas dari ekstensi)
                $baseFileName = pathinfo($old_bukti_terima, PATHINFO_FILENAME);
                $extensions = ['png', 'jpg', 'jpeg', 'pdf', 'webp']; // tambahkan jika perlu
            
                foreach ($extensions as $ext) {
                    $oldStorageFile = $storageFolderPath . $baseFileName . '.' . $ext;
                    $oldPublicFile = $publicPath . $baseFileName . '.' . $ext;
                
                    if (file_exists($oldStorageFile)) {
                        unlink($oldStorageFile);
                    }
                    if (file_exists($oldPublicFile)) {
                        unlink($oldPublicFile);
                    }
                }
            
                // Simpan file baru ke storage
                $request->file('bukti_terima_informasi_supplier')->storeAs($folderpath, $bukti_terima_informasi_supplier);
            
                // Salin ke public
                $sourceFile = storage_path('app/' . $folderpath . $bukti_terima_informasi_supplier);
                $destinationFile = $publicPath . $bukti_terima_informasi_supplier;
                copy($sourceFile, $destinationFile);

                // ✅ Update nama file baru di database
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id)
                    ->update(['bukti_terima_informasi_supplier' => $bukti_terima_informasi_supplier]);
            
                $hasChange = true;
            }

            if ($hasChange){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_admin_informasi_supplier($id)
    {
        $informasi = DB::table('informasi_supplier')
            ->where('id_informasi_supplier', $id)
            ->first();

        if ($informasi) {
            // Hapus file nota jika ada
            if (!empty($informasi->nota_informasi_supplier)) {
                $pathNota = "uploads/data_supplier/informasi_supplier/nota/" . $informasi->nota_informasi_supplier;
                if (Storage::disk('public')->exists($pathNota)) {
                    Storage::disk('public')->delete($pathNota);
                }
            }

            // Hapus file bukti terima jika ada
            if (!empty($informasi->bukti_terima_informasi_supplier)) {
                $pathBukti = "uploads/data_supplier/informasi_supplier/bukti_terima/" . $informasi->bukti_terima_informasi_supplier;
                if (Storage::disk('public')->exists($pathBukti)) {
                    Storage::disk('public')->delete($pathBukti);
                }
            }

            // Hapus data di database
            DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->delete();

            return Redirect::back()->with(['success' => 'Data dan file berhasil dihapus']);
        }

        return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
    }

    public function tambah_admin_barang_supplier(Request $request)
    {
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('admin.data_supplier.informasi_supplier.tambah_barang_supplier',compact('informasi_supplier','data'));
    }

    public function store_admin_barang_supplier(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur_admin = $admin->nomor_dapur_admin;
        $id_informasi_supplier = $request->id;
        $tanggal_hari_ini = date('Y-m-d');

        // Daftar input barang dari form
        $inputBarang = [
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_1,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_1,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_1,
                'harga_barang_supplier'   => $request->harga_barang_supplier_1,
            ],
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_2,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_2,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_2,
                'harga_barang_supplier'   => $request->harga_barang_supplier_2,
            ],
            [
                'nama_barang_supplier'    => $request->nama_barang_supplier_3,
                'jumlah_barang_supplier'  => $request->jumlah_barang_supplier_3,
                'satuan_barang_supplier'  => $request->satuan_barang_supplier_3,
                'harga_barang_supplier'   => $request->harga_barang_supplier_3,
            ],
        ];

        $dataInsert = [];

        // Loop setiap barang dan hanya ambil yang diisi
        foreach ($inputBarang as $barang) {
            if (!empty($barang['nama_barang_supplier'])) {
                $dataInsert[] = [
                    'id_informasi_supplier'       => $id_informasi_supplier,
                    'nomor_dapur_barang_supplier' => $nomor_dapur_admin,
                    'nama_barang_supplier'        => $barang['nama_barang_supplier'],
                    'jumlah_barang_supplier'      => $barang['jumlah_barang_supplier'] ?? 0,
                    'satuan_barang_supplier'      => $barang['satuan_barang_supplier'] ?? '-',
                    'harga_barang_supplier'       => $barang['harga_barang_supplier'] ?? 0,
                ];
            }
        }

        // Validasi minimal satu barang
        if (empty($dataInsert)) {
            return Redirect::back()->with(['warning' => 'Minimal isi satu barang terlebih dahulu']);
        }

        DB::beginTransaction();

        try {
            // Simpan barang supplier baru
            DB::table('barang_supplier')->insert($dataInsert);

            // Ubah status informasi supplier menjadi 0 (belum divalidasi)
            DB::table('informasi_supplier')
                ->where('id_informasi_supplier', $id_informasi_supplier)
                ->update(['status_informasi_supplier' => 0]);

            // Cek apakah sudah ada data koperasi untuk supplier ini (bukan berdasarkan tanggal)
            $dataKoperasi = DB::table('data_koperasi')
                ->where('nomor_dapur_data_koperasi', $nomor_dapur_admin)
                ->where('id_informasi_supplier', $id_informasi_supplier)
                ->first();
                    
            if (!$dataKoperasi) {
                // Jika belum ada → buat data koperasi baru
                DB::table('data_koperasi')->insert([
                    'id_informasi_supplier' => $id_informasi_supplier,
                    'nomor_dapur_data_koperasi' => $nomor_dapur_admin,
                    'tanggal_data_koperasi' => $tanggal_hari_ini,
                    'jenis_data_koperasi' => 'modal_keluar',
                    'kategori_data_koperasi' => 'Pembelian bahan dari supplier',
                    'status_data_koperasi' => 0,
                ]);
            }


            
            
            // BAGIAN LAPORAN KEUANGAN
            // Cek apakah sudah ada data laporan keuangan untuk supplier ini (bukan berdasarkan tanggal)
            //$laporanKeuangan = DB::table('keuangan')
            //    ->where('nomor_dapur_keuangan', $nomor_dapur_admin)
            //    ->where('id_informasi_supplier', $id_informasi_supplier)
            //    ->first();
            //        
            //if (!$laporanKeuangan) {
            //    // Jika belum ada → buat data koperasi baru
            //    DB::table('keuangan')->insert([
            //        'id_informasi_supplier' => $id_informasi_supplier,
            //        'nomor_dapur_keuangan' => $nomor_dapur_admin,
            //        'tanggal_laporan_keuangan' => $tanggal_hari_ini,
            //        'jenis_transaksi' => 'Pengeluaran',
            //    ]);
            //}

            DB::commit();
            return Redirect::back()->with(['success' => 'Data barang supplier berhasil disimpan dan dicatat ke data koperasi']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function lihat_admin_barang_supplier(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur_admin = $admin->nomor_dapur_admin;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_informasi_supplier = $request->id;

        // Ambil data bahan dari tabel bahan_menu
        $barang_supplier = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur_admin)
            ->select(
                'barang_supplier.*',
                'barang_supplier.nama_barang_supplier',
                'barang_supplier.jumlah_barang_supplier',
                'barang_supplier.satuan_barang_supplier',
                'barang_supplier.harga_barang_supplier'
            )
            ->get();

        return view('admin.data_supplier.informasi_supplier.lihat_barang_supplier', compact('barang_supplier'));
    }

    public function nota_admin_informasi_supplier(Request $request)
    {        
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('admin.data_supplier.informasi_supplier.nota_informasi_supplier',compact('informasi_supplier','data'));
    }

    public function bukti_terima_admin_informasi_supplier(Request $request)
    {        
        $id = $request->id;
        $informasi_supplier = DB::table('informasi_supplier')->get();
        $data = DB::table('informasi_supplier')->where('id_informasi_supplier', $id)->first();
        return view('admin.data_supplier.informasi_supplier.bukti_terima_informasi_supplier',compact('informasi_supplier','data'));
    }
}
