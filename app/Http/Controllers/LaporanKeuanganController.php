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
        $pilih_dapur   = $request->pilih_dapur;
        $dari_tanggal  = $request->dari_tanggal;
        $sampai_tanggal= $request->sampai_tanggal;

        // ===================== QUERY UTAMA =====================
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

        // 🔹 Filter tanggal (REPLACE bulan & tahun)
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('keuangan.tanggal_laporan_keuangan', [
                $dari_tanggal,
                $sampai_tanggal
            ]);
        }

        if (!empty($pilih_dapur)) {
            $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
        }

        $laporan_keuangan = $query
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->paginate(300);
        
        /* ================= FLAG STATUS ================= */
        $dataKosong = $laporan_keuangan->isEmpty();

        $sudahCari =
            !empty($dari_tanggal) ||
            !empty($sampai_tanggal) ||
            !empty($pilih_dapur);
        

        $grouped = $laporan_keuangan->getCollection()
            ->groupBy('tanggal_laporan_keuangan');

        // ===================== TOTAL PEMASUKAN =====================
        $total_pemasukan = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')

            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })

            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })

            ->sum('data_koperasi.harga_data_koperasi');

        // ===================== TOTAL PENGELUARAN SUPPLIER =====================
        $total_pengeluaran_supplier = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')

            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })

            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })

            ->sum('barang_supplier.harga_barang_supplier');

        // ===================== TOTAL MODAL KELUAR =====================
        $total_pengeluaran_modal_keluar = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })

            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })

            ->sum('barang_modal_keluar.harga_barang_modal_keluar');

        $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
        $sisa_dana = $total_pemasukan - $total_pengeluaran;

        // ===================== DATA GRAFIK =====================
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')

            ->select(
                'keuangan.tanggal_laporan_keuangan',

                DB::raw('SUM(
                    CASE 
                        WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                        THEN data_koperasi.harga_data_koperasi
                        ELSE 0
                    END
                ) AS total_pemasukan'),

                DB::raw('
                    SUM(COALESCE(barang_supplier.harga_barang_supplier,0)) +
                    SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar,0))
                    AS total_pengeluaran
                '),

                DB::raw('
                    SUM(
                        CASE 
                            WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                            THEN data_koperasi.harga_data_koperasi
                            ELSE 0
                        END
                    ) -
                    (
                        SUM(COALESCE(barang_supplier.harga_barang_supplier,0)) +
                        SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar,0))
                    )
                    AS margin
                ')
            )

            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })

            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })

            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });

        // ===================== DATA DAPUR =====================
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('owner.laporan.keuangan.index_laporan_keuangan', compact(
            'laporan_keuangan',
            'dataKosong',
            'sudahCari',
            'grouped',
            'total_pemasukan',
            'total_pengeluaran',
            'sisa_dana',
            'data',
            'pilih_dapur',
            'dari_tanggal',
            'sampai_tanggal',
            'dapurList'
        ));
    }



    public function cetak_owner_laporan_keuangan(Request $request)
    {
        $pilih_dapur   = $request->dapur;
        $dari_tanggal  = $request->dari_tanggal;
        $sampai_tanggal= $request->sampai_tanggal;
    
        // ===================== QUERY UTAMA =====================
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
    
        // 🔹 Filter tanggal (REPLACE BULAN & TAHUN)
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('keuangan.tanggal_laporan_keuangan', [
                $dari_tanggal,
                $sampai_tanggal
            ]);
        }
    
        if (!empty($pilih_dapur)) {
            $query->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
        }
    
        $laporan_keuangan = $query
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->paginate(300);
    
        // ===================== GROUPING =====================
        $grouped = $laporan_keuangan->getCollection()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_laporan_keuangan)->format('Y-m-d');
        });
    
        // ===================== TOTAL PEMASUKAN =====================
        $total_pemasukan = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')
    
            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })
    
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
    
            ->sum('data_koperasi.harga_data_koperasi');
    
        // ===================== TOTAL PENGELUARAN SUPPLIER =====================
        $total_pengeluaran_supplier = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
    
            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })
    
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
    
            ->sum('barang_supplier.harga_barang_supplier');
    
        // ===================== TOTAL MODAL KELUAR =====================
        $total_pengeluaran_modal_keluar = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->join('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')
    
            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })
    
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
    
            ->sum('barang_modal_keluar.harga_barang_modal_keluar');
    
        $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        // ===================== DATA GRAFIK =====================
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->leftJoin('barang_supplier', 'barang_supplier.id_informasi_supplier', '=', 'data_koperasi.id_informasi_supplier')
            ->leftJoin('barang_modal_keluar', 'barang_modal_keluar.id_data_koperasi', '=', 'data_koperasi.id_data_koperasi')
    
            ->select(
                'keuangan.tanggal_laporan_keuangan',
    
                DB::raw('SUM(
                    CASE 
                        WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                        THEN data_koperasi.harga_data_koperasi
                        ELSE 0
                    END
                ) AS total_pemasukan'),
    
                DB::raw('
                    SUM(COALESCE(barang_supplier.harga_barang_supplier,0)) +
                    SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar,0))
                    AS total_pengeluaran
                '),
    
                DB::raw('
                    SUM(
                        CASE 
                            WHEN data_koperasi.jenis_data_koperasi = "modal_masuk"
                            THEN data_koperasi.harga_data_koperasi
                            ELSE 0
                        END
                    ) -
                    (
                        SUM(COALESCE(barang_supplier.harga_barang_supplier,0)) +
                        SUM(COALESCE(barang_modal_keluar.harga_barang_modal_keluar,0))
                    )
                    AS margin
                ')
            )
    
            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })
    
            ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
                $q->where('keuangan.nomor_dapur_keuangan', $pilih_dapur);
            })
    
            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });
    
        return view('owner.laporan.keuangan.cetak_laporan_keuangan', compact(
            'grouped',
            'sisa_dana',
            'data',
            'dari_tanggal',
            'sampai_tanggal'
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



    public function delete_owner_laporan_keuangan($id)
    {
        $delete = DB::table('keuangan')->where('id_laporan_keuangan', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }


























    // BAGIAN MAKER
    public function index_maker_laporan_keuangan(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $dapur = $maker->nomor_dapur_maker;

        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;

        $data = DB::table('keuangan as k')
            ->leftJoin('barang_modal_keluar as bmk', function ($join) use ($dapur) {
                $join->on('k.id_data_koperasi', '=', 'bmk.id_data_koperasi')
                     ->where('bmk.nomor_dapur_barang_modal_keluar', '=', $dapur);
            })
            ->leftJoin('barang_supplier as bs', function ($join) use ($dapur) {
                $join->on('k.id_informasi_supplier', '=', 'bs.id_informasi_supplier')
                     ->where('bs.nomor_dapur_barang_supplier', '=', $dapur);
            })
            ->where('k.nomor_dapur_keuangan', $dapur)
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                $query->whereBetween('k.tanggal_laporan_keuangan', [
                    $dari_tanggal,
                    $sampai_tanggal
                ]);
            })
            ->select(
                'k.id_data_koperasi',
                'k.tanggal_laporan_keuangan',
                DB::raw('CASE WHEN bmk.id_data_koperasi IS NOT NULL THEN 1 ELSE 0 END AS dari_koperasi'),
                DB::raw('CASE WHEN bs.id_informasi_supplier IS NOT NULL THEN 1 ELSE 0 END AS dari_supplier')
            )
            ->orderBy('k.tanggal_laporan_keuangan', 'desc')
            ->get();

        return view('maker.laporan.keuangan.index_laporan_keuangan', compact(
            'data',
            'dari_tanggal',
            'sampai_tanggal'
        ));
    }

    public function store_maker_laporan_keuangan(Request $request)
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

    public function edit_maker_laporan_keuangan(Request $request)
    {
        $id = $request->id;
        $keuangan = DB::table('keuangan')->get();
        $data = DB::table('keuangan')->where('id_laporan_keuangan', $id)->first();
        return view('maker.laporan.keuangan.edit_laporan_keuangan',compact('keuangan','data'));
    }

    public function update_maker_laporan_keuangan($id, Request $request)
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

    public function cetak_maker_laporan_keuangan(Request $request)
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

        return view('maker.laporan.keuangan.cetak_laporan_keuangan', compact('data','sisa_dana'));
    }

    public function delete_maker_laporan_keuangan($id)
    {
        $delete = DB::table('keuangan')->where('id_laporan_keuangan', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
