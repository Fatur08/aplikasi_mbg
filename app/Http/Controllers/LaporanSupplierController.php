<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;


use Illuminate\Support\Facades\Redirect;
use App\Models\DataKoperasi;
use Carbon\Carbon;

class LaporanSupplierController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_laporan_supplier(Request $request)
    {
        $nomor_dapur = $request->pilih_dapur;
    
        // Data supplier
        $supplier = DB::table('informasi_supplier')
            ->where('nomor_dapur_informasi_supplier', $nomor_dapur)
            ->get();
    
        // Query dasar barang supplier
        $barangSupplierQuery = DB::table('barang_supplier')
            ->where('nomor_dapur_barang_supplier', $nomor_dapur)
            ->whereNotNull('tanggal_barang_supplier')
            ->where('tanggal_barang_supplier', '!=', '');
    
        // 🔍 Filter dari_tanggal
        if ($request->filled('dari_tanggal')) {
            $barangSupplierQuery->whereDate(
                'tanggal_barang_supplier',
                '>=',
                $request->dari_tanggal
            );
        }
    
        // 🔍 Filter sampai_tanggal
        if ($request->filled('sampai_tanggal')) {
            $barangSupplierQuery->whereDate(
                'tanggal_barang_supplier',
                '<=',
                $request->sampai_tanggal
            );
        }
    
        // Eksekusi query
        $barangSupplier = $barangSupplierQuery
            ->orderBy('tanggal_barang_supplier', 'desc')
            ->get();





        /* ================= LIST DAPUR ================= */
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
    
        return view(
            'owner.laporan.supplier.index_laporan_supplier',
            compact('supplier', 'barangSupplier', 'dapurList')
        );
    }






    public function bukti_owner_barang_supplier(Request $request)
    {
        $id                 = $request->id;
        $barang_supplier      = DB::table('barang_supplier')->get();
        $data               = DB::table('barang_supplier')->where('id_barang_supplier', $id)->first();
        return view('owner.laporan.supplier.bukti_barang_supplier',compact('barang_supplier','data'));
    }




    public function validasi_owner_barang_supplier(Request $request)
    {
        $id = $request->id;
        $data = DB::table('barang_supplier')->where('id_barang_supplier', $id)->first();
        return view('owner.laporan.supplier.validasi_laporan_supplier',compact('data'));
    }


    public function update_owner_validasi_barang_supplier(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // ===============================
            // 1️⃣ Ambil data barang supplier
            // ===============================
            $barangSupplier = DB::table('barang_supplier')
                ->where('id_barang_supplier', $id)
                ->first();

            if (!$barangSupplier) {
                return redirect()->back()->with(
                    'error',
                    'Data barang supplier tidak ditemukan'
                );
            }

            // ===============================
            // 2️⃣ Update status barang supplier
            // ===============================
            DB::table('barang_supplier')
                ->where('id_barang_supplier', $id)
                ->update([
                    'status_barang_supplier' => $request->status_barang_supplier
                ]);

            // ===============================
            // 3️⃣ Data penting keuangan
            // ===============================
            $tanggalLaporan = $barangSupplier->tanggal_barang_supplier;
            $nomorDapur     = $barangSupplier->nomor_dapur_barang_supplier;
            $id_informasi_supplier     = $barangSupplier->id_informasi_supplier;

            // ===============================
            // 4️⃣ Cek data keuangan
            // ===============================
            $keuangan = DB::table('keuangan')
                ->where('tanggal_laporan_keuangan', $tanggalLaporan)
                ->where('nomor_dapur_keuangan', $nomorDapur)
                ->first();

            if (!$keuangan) {
                // ➕ BELUM ADA → INSERT BARU
                DB::table('keuangan')->insert([
                    'id_informasi_supplier'   => $id_informasi_supplier,
                    'nomor_dapur_keuangan'    => $nomorDapur,
                    'tanggal_laporan_keuangan'=> $tanggalLaporan,
                ]);
            } else {
                // 🔁 SUDAH ADA → UPDATE ID SUPPLIER SAJA
                DB::table('keuangan')
                    ->where('id_laporan_keuangan', $keuangan->id_laporan_keuangan)
                    ->update([
                        'id_informasi_supplier' => $id_informasi_supplier
                    ]);
            }

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Status barang supplier berhasil divalidasi'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Proses gagal: ' . $e->getMessage()
            );
        }
    }




    public function batalkan_owner_validasi_laporan_supplier(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // ===============================
            // 1️⃣ Ambil data barang supplier
            // ===============================
            $barangSupplier = DB::table('barang_supplier')
                ->where('id_barang_supplier', $id)
                ->first();

            if (!$barangSupplier) {
                return redirect()->back()->with(
                    'error',
                    'Barang supplier tidak ditemukan'
                );
            }

            // ===============================
            // 2️⃣ Batalkan status barang supplier
            // ===============================
            DB::table('barang_supplier')
                ->where('id_barang_supplier', $id)
                ->update([
                    'status_barang_supplier' => 0
                ]);

            // ===============================
            // 3️⃣ Hapus data di keuangan
            // ===============================
            DB::table('keuangan')
                ->where('id_informasi_supplier', $barangSupplier->id_informasi_supplier)
                ->where('nomor_dapur_keuangan', $barangSupplier->nomor_dapur_barang_supplier)
                ->where('tanggal_laporan_keuangan', $barangSupplier->tanggal_barang_supplier)
                ->delete();

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Validasi barang supplier berhasil dibatalkan'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }
    














    // BAGIAN MAKER
    public function index_maker_laporan_supplier(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();
    
        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        // Data supplier
        $supplier = DB::table('informasi_supplier')
            ->where('nomor_dapur_informasi_supplier', $nomor_dapur)
            ->get();
    
        // Query dasar barang supplier
        $barangSupplierQuery = DB::table('barang_supplier')
            ->where('nomor_dapur_barang_supplier', $nomor_dapur)
            ->whereNotNull('tanggal_barang_supplier')
            ->where('tanggal_barang_supplier', '!=', '');
    
        // 🔍 Filter dari_tanggal
        if ($request->filled('dari_tanggal')) {
            $barangSupplierQuery->whereDate(
                'tanggal_barang_supplier',
                '>=',
                $request->dari_tanggal
            );
        }
    
        // 🔍 Filter sampai_tanggal
        if ($request->filled('sampai_tanggal')) {
            $barangSupplierQuery->whereDate(
                'tanggal_barang_supplier',
                '<=',
                $request->sampai_tanggal
            );
        }
    
        // Eksekusi query
        $barangSupplier = $barangSupplierQuery
            ->orderBy('tanggal_barang_supplier', 'desc')
            ->get();
    
        return view(
            'maker.laporan.supplier.index_laporan_supplier',
            compact('supplier', 'barangSupplier')
        );
    }



    public function getJumlahBarangSupplier($id_supplier)
    {
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;

        $jumlah = DB::table('barang_supplier')
            ->where('id_informasi_supplier', $id_supplier)
            ->where('nomor_dapur_barang_supplier', $nomor_dapur)
            ->whereNotNull('nama_barang_supplier')
            ->distinct('nama_barang_supplier')
            ->count('nama_barang_supplier');

        return response()->json([
            'jumlah' => $jumlah
        ]);
    }





    public function getBarangSupplier($id_supplier)
    {
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $barang = DB::table('barang_supplier')
            ->where('nomor_dapur_barang_supplier', $nomor_dapur)
            ->where('id_informasi_supplier', $id_supplier)
            ->select('nama_barang_supplier')
            ->groupBy('nama_barang_supplier')
            ->get();
    
        return response()->json($barang);
    }




    public function store_maker_laporan_supplier(Request $request)
    {
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;

        /* ===============================
           UPLOAD BUKTI (SATU KALI SAJA)
        ================================*/
        $bukti_barang_supplier = null;

        // upload bukti
        $bukti_barang_supplier = null;


        if ($request->hasFile('bukti_barang_supplier')) {
            $bukti_barang_supplier = "Bukti_".$request->tanggal_laporan_supplier.".".$request
                ->file('bukti_barang_supplier')
                ->getClientOriginalExtension();
            $storagePath = 'public/uploads/data_supplier/informasi_supplier/bukti_terima/';
            $request->file('bukti_barang_supplier')->storeAs($storagePath, $bukti_barang_supplier);
            $publicPath = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
            $sourceFile = storage_path('app/' . $storagePath . $bukti_barang_supplier);
            $destinationFile = public_path('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $bukti_barang_supplier);
            copy($sourceFile, $destinationFile);
        }

        /* ===============================
           LOOP BARANG
        ================================*/
        foreach ($request->barang as $item) {

            $cekBarang = DB::table('barang_supplier')
                ->where('id_informasi_supplier', $request->id_informasi_supplier)
                ->where('nomor_dapur_barang_supplier', $nomor_dapur)
                ->where('nama_barang_supplier', $item['nama_barang_supplier'])
                ->first();

            // ❌ BELUM ADA → INSERT BARU
            if (!$cekBarang) {

                DB::table('barang_supplier')->insert([
                    'nomor_dapur_barang_supplier' => $nomor_dapur,
                    'id_informasi_supplier'       => $request->id_informasi_supplier,
                    'tanggal_barang_supplier'     => $request->tanggal_laporan_supplier,
                    'nama_barang_supplier'        => $item['nama_barang_supplier'],
                    'satuan_barang_supplier'      => $item['satuan_barang_supplier'],
                    'jumlah_barang_supplier'      => $item['jumlah_barang_supplier'],
                    'harga_barang_supplier'       => $item['harga_barang_supplier'],
                    'bukti_barang_supplier'       => $bukti_barang_supplier,
                ]);

            } 
            // ⚠️ ADA TAPI TANGGAL KOSONG → UPDATE
            else if (empty($cekBarang->tanggal_barang_supplier)) {

                DB::table('barang_supplier')
                    ->where('id_barang_supplier', $cekBarang->id_barang_supplier)
                    ->update([
                        'tanggal_barang_supplier' => $request->tanggal_laporan_supplier,
                        'satuan_barang_supplier'  => $item['satuan_barang_supplier'],
                        'jumlah_barang_supplier'  => $item['jumlah_barang_supplier'],
                        'harga_barang_supplier'   => $item['harga_barang_supplier'],
                        'bukti_barang_supplier'   => $bukti_barang_supplier,
                    ]);

            } 
            // ✅ SEMUA SUDAH ADA → INSERT BARU
            else {

                DB::table('barang_supplier')->insert([
                    'nomor_dapur_barang_supplier' => $nomor_dapur,
                    'id_informasi_supplier'       => $request->id_informasi_supplier,
                    'tanggal_barang_supplier'     => $request->tanggal_laporan_supplier,
                    'nama_barang_supplier'        => $item['nama_barang_supplier'],
                    'satuan_barang_supplier'      => $item['satuan_barang_supplier'],
                    'jumlah_barang_supplier'      => $item['jumlah_barang_supplier'],
                    'harga_barang_supplier'       => $item['harga_barang_supplier'],
                    'bukti_barang_supplier'       => $bukti_barang_supplier,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Laporan supplier berhasil disimpan');
    }






    public function bukti_maker_barang_supplier(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $nomor_dapur_maker = $maker->nomor_dapur_maker;

        $id                 = $request->id;
        $barang_supplier      = DB::table('barang_supplier')->get();
        $data               = DB::table('barang_supplier')->where('id_barang_supplier', $id)->first();
        return view('maker.laporan.supplier.bukti_barang_supplier',compact('barang_supplier','data'));
    }
}
