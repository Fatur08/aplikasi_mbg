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
        // Ambil data maker yang login
        $makerLogin      = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur     = $makerLogin->nomor_dapur_maker ?? null;

        $jumlah = DB::table('barang_supplier')
            ->where('id_informasi_supplier', $id_supplier)
            ->where('nomor_dapur_barang_supplier', $nomor_dapur)
            ->select('nama_barang_supplier')
            ->groupBy('nama_barang_supplier')
            ->count();

        return response()->json([
            'jumlah' => $jumlah
        ]);
    }





    public function getBarangSupplier($id_supplier)
    {
        $maker = auth()->user();
    
        $barang = DB::table('barang_supplier')
            ->where('nomor_dapur_barang_supplier', $maker->nomor_dapur_maker)
            ->where('id_informasi_supplier', $id_supplier)
            ->select('nama_barang_supplier')
            ->groupBy('nama_barang_supplier')
            ->get();
    
        return response()->json($barang);
    }




    public function store_maker_laporan_supplier(Request $request)
    {
        $maker = auth()->user();

        // upload bukti
        $path = $request->file('bukti_barang_supplier')
            ->store('bukti_supplier', 'public');

        foreach ($request->barang as $item) {

            // 1️⃣ Cek data barang berdasarkan supplier + dapur + nama barang
            $cekBarang = DB::table('barang_supplier')
                ->where('id_informasi_supplier', $request->id_informasi_supplier)
                ->where('nomor_dapur_barang_supplier', $maker->nomor_dapur_maker)
                ->where('nama_barang_supplier', $item['nama_barang_supplier'])
                ->first();

            // 2️⃣ Jika BELUM ADA → INSERT BARU
            if (!$cekBarang) {

                DB::table('barang_supplier')->insert([
                    'nomor_dapur_barang_supplier' => $maker->nomor_dapur_maker,
                    'id_informasi_supplier'       => $request->id_informasi_supplier,
                    'tanggal_laporan_supplier'   => $request->tanggal_laporan_supplier,
                    'nama_barang_supplier'       => $item['nama_barang_supplier'],
                    'satuan_barang_supplier'     => $item['satuan_barang_supplier'],
                    'jumlah_barang_supplier'     => $item['jumlah_barang_supplier'],
                    'harga_barang_supplier'      => $item['harga_barang_supplier'],
                    'bukti_barang_supplier'      => $path,
                    'created_at'                 => now(),
                ]);

            } else {

                // 3️⃣ Jika ADA tapi tanggal masih KOSONG → UPDATE
                if (empty($cekBarang->tanggal_laporan_supplier)) {

                    DB::table('barang_supplier')
                        ->where('id_barang_supplier', $cekBarang->id_barang_supplier)
                        ->update([
                            'tanggal_laporan_supplier' => $request->tanggal_laporan_supplier,
                            'satuan_barang_supplier'   => $item['satuan_barang_supplier'],
                            'jumlah_barang_supplier'   => $item['jumlah_barang_supplier'],
                            'harga_barang_supplier'    => $item['harga_barang_supplier'],
                            'bukti_barang_supplier'    => $path,
                            'updated_at'               => now(),
                        ]);

                } else {

                    // 4️⃣ Jika SEMUA SUDAH ADA → INSERT BARIS BARU
                    DB::table('barang_supplier')->insert([
                        'nomor_dapur_barang_supplier' => $maker->nomor_dapur_maker,
                        'id_informasi_supplier'       => $request->id_informasi_supplier,
                        'tanggal_laporan_supplier'   => $request->tanggal_laporan_supplier,
                        'nama_barang_supplier'       => $item['nama_barang_supplier'],
                        'satuan_barang_supplier'     => $item['satuan_barang_supplier'],
                        'jumlah_barang_supplier'     => $item['jumlah_barang_supplier'],
                        'harga_barang_supplier'      => $item['harga_barang_supplier'],
                        'bukti_barang_supplier'      => $path,
                        'created_at'                 => now(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Laporan supplier berhasil disimpan');
    }
}
