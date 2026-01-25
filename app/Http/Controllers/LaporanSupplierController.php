<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class LaporanSupplierController extends Controller
{
    // BAGIAN MAKER
    public function index_maker_laporan_supplier()
    {
        // Ambil data maker yang login
        $makerLogin      = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur     = $makerLogin->nomor_dapur_maker ?? null;
    
        $supplier = DB::table('informasi_supplier')
            ->where('nomor_dapur_informasi_supplier', $nomor_dapur)
            ->get();
        return view('maker.laporan.supplier.index_laporan_supplier', compact('supplier'));
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

        if ($request->hasFile('bukti_barang_supplier')) {
            $bukti_barang_supplier = 'Bukti_' .
                date('Ymd_His') . '.' .
                $request->file('bukti_barang_supplier')->getClientOriginalExtension();

            $request->file('bukti_barang_supplier')
                ->storeAs(
                    'public/uploads/data_koperasi/bukti_terima',
                    $bukti_barang_supplier
                );
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
}
