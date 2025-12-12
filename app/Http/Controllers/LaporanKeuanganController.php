<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_laporan_keuangan(Request $request)
    {
        $pilih_bulan   = $request->pilih_bulan;
        $pilih_dapur   = $request->pilih_dapur;
        $tahunSekarang = Carbon::now()->year;
    
        // 🔹 Query utama laporan keuangan
        $query = DB::table('keuangan')
            ->leftJoin('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')
            ->select(
                'keuangan.*',
                'data_koperasi.*',
                'barang_supplier.harga_barang_supplier',
                'barang_modal_keluar.harga_barang_modal_keluar'
            );
    
        // Filter bulan
        if (!empty($pilih_bulan)) {
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        } else {
            // Default: bulan berjalan
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', Carbon::now()->month)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        }

        if (!empty($pilih_dapur)) {
            $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
        }
    
        // 🔹 Ambil daftar laporan (pagination tetap sama)
        $laporan_keuangan = $query->orderBy('tanggal_laporan_keuangan', 'desc')->paginate(300);

        $grouped = $laporan_keuangan->getCollection()
            ->groupBy('tanggal_laporan_keuangan');
    
        /**
         * 🔹 Perhitungan total pemasukan & pengeluaran
         */
        $total_pemasukan = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('data_koperasi.harga_data_koperasi');
        
        
        $total_pengeluaran_supplier = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('barang_supplier.harga_barang_supplier');



        $total_pengeluaran_modal_keluar = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('barang_modal_keluar.harga_barang_modal_keluar');
    


        $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
        
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        /**
         * 🔹 Data grafik batang berdasarkan tanggal laporan keuangan
         * Dikelompokkan berdasarkan tanggal, dengan sum dari data_koperasi
         */
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')

            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->select(
                'keuangan.tanggal_laporan_keuangan',
            
                // ✅ TOTAL PEMASUKAN (TETAP)
                DB::raw('SUM(
                    CASE 
                        WHEN data_koperasi.jenis_data_koperasi = "modal_masuk" 
                        THEN data_koperasi.harga_data_koperasi 
                        ELSE 0 
                    END
                ) AS total_pemasukan'),
            
                // ✅ TOTAL PENGELUARAN (SUPPLIER + MODAL KELUAR)
                DB::raw('
                    SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                    SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    AS total_pengeluaran
                '),
            
                // ✅ MARGIN
                DB::raw('
                    SUM(
                        CASE 
                            WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                            THEN data_koperasi.harga_data_koperasi
                            ELSE 0
                        END
                    ) -
                    (
                        SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                        SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    )
                    AS margin
                ')
            )
            
            ->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
            ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang)
            ->when($pilih_dapur, function ($query) use ($pilih_dapur) {
                $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });



        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        


        dd([
          'request_all' => $request->all(),
          'pilih_bulan' => $pilih_bulan,
          'pilih_dapur' => $pilih_dapur,
          'query_data' => $data,
        ]);
        
        return view('owner.laporan.keuangan.index_laporan_keuangan', compact(
            'laporan_keuangan',
            'grouped',
            'total_pemasukan',
            'total_pengeluaran',
            'sisa_dana',
            'data',
            'pilih_bulan',
            'pilih_dapur',
            'dapurList'
        ));
    }

    public function store_owner_laporan_keuangan(Request $request)
    {
        $tanggal_laporan_keuangan = $request->tanggal_laporan_keuangan;
        $jenis_laporan_keuangan = $request->jenis_laporan_keuangan;
        $kategori_laporan_keuangan = $request->kategori_laporan_keuangan;
        $keterangan_laporan_keuangan = $request->keterangan_laporan_keuangan;
        $jumlah_dana = (int)$request->jumlah_dana;

        $data = [
            'tanggal_laporan_keuangan' => $tanggal_laporan_keuangan,
            'jenis_transaksi'   => $jenis_laporan_keuangan,
            'kategori_laporan_keuangan' => $kategori_laporan_keuangan,
            'keterangan_laporan_keuangan' => $keterangan_laporan_keuangan,
            'jumlah_dana'               => $jumlah_dana
        ];

        $simpan = DB::table('keuangan')->insert($data);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_owner_laporan_keuangan(Request $request)
    {
        $id = $request->id;
        $keuangan = DB::table('keuangan')->get();
        $data = DB::table('keuangan')->where('id_laporan_keuangan', $id)->first();
        return view('owner.laporan.keuangan.edit_laporan_keuangan',compact('keuangan','data'));
    }

    public function update_owner_laporan_keuangan($id, Request $request)
    {
        try {
            $tanggal_laporan_keuangan = $request->edit_tanggal_laporan_keuangan;
            $jenis_laporan_keuangan = $request->edit_jenis_laporan_keuangan;
            $kategori_laporan_keuangan = $request->edit_kategori_laporan_keuangan;
            $keterangan_laporan_keuangan = $request->edit_keterangan_laporan_keuangan;
            $jumlah_dana = (int)$request->edit_jumlah_dana;

            // Update hanya kolom yang perlu
            $update = DB::table('keuangan')
                ->where('id_laporan_keuangan', $id)
                ->update([
                    'tanggal_laporan_keuangan' => $tanggal_laporan_keuangan,
                    'jenis_transaksi'   => $jenis_laporan_keuangan,
                    'kategori_laporan_keuangan' => $kategori_laporan_keuangan,
                    'keterangan_laporan_keuangan' => $keterangan_laporan_keuangan,
                    'jumlah_dana'               => $jumlah_dana
                ]);

            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diproses']);
        }
    }

    public function cetak_owner_laporan_keuangan(Request $request)
    {
        $pilih_bulan   = $request->bulan;
        $pilih_dapur   = $request->dapur;
        $tahunSekarang = Carbon::now()->year;

        $query = DB::table('keuangan')
            ->leftJoin('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')
            ->select(
                'keuangan.*',
                'data_koperasi.*',
                'barang_supplier.harga_barang_supplier',
                'barang_modal_keluar.harga_barang_modal_keluar'
            );
    
        // Filter bulan
        if (!empty($pilih_bulan)) {
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        } else {
            // Default: bulan berjalan
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', Carbon::now()->month)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        }

        if (!empty($pilih_dapur)) {
            $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
        }
    
        // 🔹 Ambil daftar laporan (pagination tetap sama)
        $laporan_keuangan = $query->orderBy('tanggal_laporan_keuangan', 'desc')->paginate(300);

        $grouped = $laporan_keuangan->getCollection()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_laporan_keuangan)->format('Y-m-d');
        });
        
        
        $total_pemasukan = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('data_koperasi.harga_data_koperasi');
        
        
        $total_pengeluaran_supplier = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('barang_supplier.harga_barang_supplier');



        $total_pengeluaran_modal_keluar = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->sum('barang_modal_keluar.harga_barang_modal_keluar');
    


        $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
    
        $sisa_dana = $total_pemasukan - $total_pengeluaran;


        /**
         * 🔹 Data grafik batang berdasarkan tanggal laporan keuangan
         * Dikelompokkan berdasarkan tanggal, dengan sum dari data_koperasi
         */
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')

            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->select(
                'keuangan.tanggal_laporan_keuangan',
            
                // ✅ TOTAL PEMASUKAN (TETAP)
                DB::raw('SUM(
                    CASE 
                        WHEN data_koperasi.jenis_data_koperasi = "modal_masuk" 
                        THEN data_koperasi.harga_data_koperasi 
                        ELSE 0 
                    END
                ) AS total_pemasukan'),
            
                // ✅ TOTAL PENGELUARAN (SUPPLIER + MODAL KELUAR)
                DB::raw('
                    SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                    SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    AS total_pengeluaran
                '),
            
                // ✅ MARGIN
                DB::raw('
                    SUM(
                        CASE 
                            WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                            THEN data_koperasi.harga_data_koperasi
                            ELSE 0
                        END
                    ) -
                    (
                        SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                        SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    )
                    AS margin
                ')
            )
            
            ->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
            ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang)
            ->when($pilih_dapur, function ($query) use ($pilih_dapur) {
                $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
        
            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });

        return view('owner.laporan.keuangan.cetak_laporan_keuangan', compact('grouped', 'sisa_dana', 'data', 'pilih_bulan'));
    }

    public function delete_owner_laporan_keuangan($id)
    {
        $delete = DB::table('keuangan')->where('id_laporan_keuangan', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }


























    // BAGIAN ADMIN
    public function index_admin_laporan_keuangan(Request $request)
    {
        $admin         = Auth::guard('admin')->user();
        $dapur         = $admin->nomor_dapur_admin;
        $pilih_bulan   = $request->pilih_bulan;
        $tahunSekarang = Carbon::now()->year;
    
        // 🔹 Query utama laporan keuangan
        $query = DB::table('keuangan')
            ->leftJoin('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')
            ->select(
                'keuangan.*',
                'data_koperasi.*',
                'barang_supplier.harga_barang_supplier',
                'barang_modal_keluar.harga_barang_modal_keluar'
            );
    
        // Filter bulan
        if (!empty($pilih_bulan)) {
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        } else {
            // Default: bulan berjalan
            $query->whereMonth('keuangan.tanggal_laporan_keuangan', Carbon::now()->month)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        }

        if (!empty($dapur)) {
            $query->where('keuangan.nomor_dapur_keuangan', $dapur);
        }
    
        // 🔹 Ambil daftar laporan (pagination tetap sama)
        $laporan_keuangan = $query->orderBy('tanggal_laporan_keuangan', 'desc')->paginate(300);

        $grouped = $laporan_keuangan->getCollection()
            ->groupBy('tanggal_laporan_keuangan');
    
        /**
         * 🔹 Perhitungan total pemasukan & pengeluaran
         */
        $total_pemasukan = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($dapur, function ($q) use ($dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $dapur);
            })
        
            ->sum('data_koperasi.harga_data_koperasi');
        
        
        $total_pengeluaran_supplier = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($dapur, function ($q) use ($dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $dapur);
            })
        
            ->sum('barang_supplier.harga_barang_supplier');



        $total_pengeluaran_modal_keluar = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->when($pilih_bulan, function ($q) use ($pilih_bulan, $tahunSekarang) {
                $q->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
                  ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
        
            ->when($dapur, function ($q) use ($dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $dapur);
            })
        
            ->sum('barang_modal_keluar.harga_barang_modal_keluar');
    


        $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
        
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        /**
         * 🔹 Data grafik batang berdasarkan tanggal laporan keuangan
         * Dikelompokkan berdasarkan tanggal, dengan sum dari data_koperasi
         */
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')

            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->select(
                'keuangan.tanggal_laporan_keuangan',
            
                // ✅ TOTAL PEMASUKAN (TETAP)
                DB::raw('SUM(
                    CASE 
                        WHEN data_koperasi.jenis_data_koperasi = "modal_masuk" 
                        THEN data_koperasi.harga_data_koperasi 
                        ELSE 0 
                    END
                ) AS total_pemasukan'),
            
                // ✅ TOTAL PENGELUARAN (SUPPLIER + MODAL KELUAR)
                DB::raw('
                    SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                    SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    AS total_pengeluaran
                '),
            
                // ✅ MARGIN
                DB::raw('
                    SUM(
                        CASE 
                            WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                            THEN data_koperasi.harga_data_koperasi
                            ELSE 0
                        END
                    ) -
                    (
                        SUM(COALESCE(barang_supplier.harga_barang_supplier, 0)) +
                        SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar, 0))
                    )
                    AS margin
                ')
            )
            
            ->whereMonth('keuangan.tanggal_laporan_keuangan', $pilih_bulan)
            ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang)
            ->when($dapur, function ($query) use ($dapur) {
                $query->where('keuangan.nomor_dapur_keuangan', $dapur);
            })
        
            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });
        


        dd([
          'request_all' => $request->all(),
          'pilih_bulan' => $pilih_bulan,
          'dapur' => $dapur,
          'query_data' => $data,
        ]);
        return view('admin.laporan.keuangan.index_laporan_keuangan', compact(
            'laporan_keuangan',
            'grouped',
            'total_pemasukan',
            'total_pengeluaran',
            'sisa_dana',
            'data',
            'pilih_bulan',
            'dapur'
        ));
    }

    public function store_admin_laporan_keuangan(Request $request)
    {
        $tanggal_laporan_keuangan = $request->tanggal_laporan_keuangan;
        $jenis_laporan_keuangan = $request->jenis_laporan_keuangan;
        $kategori_laporan_keuangan = $request->kategori_laporan_keuangan;
        $keterangan_laporan_keuangan = $request->keterangan_laporan_keuangan;
        $jumlah_dana = (int)$request->jumlah_dana;

        $data = [
            'tanggal_laporan_keuangan' => $tanggal_laporan_keuangan,
            'jenis_transaksi'   => $jenis_laporan_keuangan,
            'kategori_laporan_keuangan' => $kategori_laporan_keuangan,
            'keterangan_laporan_keuangan' => $keterangan_laporan_keuangan,
            'jumlah_dana'               => $jumlah_dana
        ];

        $simpan = DB::table('keuangan')->insert($data);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_admin_laporan_keuangan(Request $request)
    {
        $id = $request->id;
        $keuangan = DB::table('keuangan')->get();
        $data = DB::table('keuangan')->where('id_laporan_keuangan', $id)->first();
        return view('admin.laporan.keuangan.edit_laporan_keuangan',compact('keuangan','data'));
    }

    public function update_admin_laporan_keuangan($id, Request $request)
    {
        try {
            $tanggal_laporan_keuangan = $request->edit_tanggal_laporan_keuangan;
            $jenis_laporan_keuangan = $request->edit_jenis_laporan_keuangan;
            $kategori_laporan_keuangan = $request->edit_kategori_laporan_keuangan;
            $keterangan_laporan_keuangan = $request->edit_keterangan_laporan_keuangan;
            $jumlah_dana = (int)$request->edit_jumlah_dana;

            // Update hanya kolom yang perlu
            $update = DB::table('keuangan')
                ->where('id_laporan_keuangan', $id)
                ->update([
                    'tanggal_laporan_keuangan' => $tanggal_laporan_keuangan,
                    'jenis_transaksi'   => $jenis_laporan_keuangan,
                    'kategori_laporan_keuangan' => $kategori_laporan_keuangan,
                    'keterangan_laporan_keuangan' => $keterangan_laporan_keuangan,
                    'jumlah_dana'               => $jumlah_dana
                ]);

            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            } else {
                return Redirect::back()->with(['warning' => 'Tidak ada perubahan data']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diproses']);
        }
    }

    public function cetak_admin_laporan_keuangan(Request $request)
    {
        $dari_tanggal     = $request->dari_tanggal ? date('Y-m-d', strtotime($request->dari_tanggal)) : null;
        $sampai_tanggal   = $request->sampai_tanggal ? date('Y-m-d', strtotime($request->sampai_tanggal)) : null;
        $jenis_transaksi  = $request->jenis_transaksi; 

        $query = DB::table('keuangan');

        if (!empty($jenis_transaksi)) {
            $query->where('jenis_transaksi', $jenis_transaksi);
        }

        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_laporan_keuangan', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_laporan_keuangan', '<=', $sampai_tanggal);
        }

        $data = $query->get();
        $total_pemasukan = DB::table('keuangan')
            ->where('jenis_transaksi', 'Pemasukan')
            ->sum('jumlah_dana');
    
        $total_pengeluaran = DB::table('keuangan')
            ->where('jenis_transaksi', 'Pengeluaran')
            ->sum('jumlah_dana');
    
        $sisa_dana = $total_pemasukan - $total_pengeluaran;

        return view('admin.laporan.keuangan.cetak_laporan_keuangan', compact('data','sisa_dana'));
    }

    public function delete_admin_laporan_keuangan($id)
    {
        $delete = DB::table('keuangan')->where('id_laporan_keuangan', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
