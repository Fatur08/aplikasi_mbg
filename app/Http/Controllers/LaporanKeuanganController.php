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
        $pilih_dapur             = $request->pilih_dapur;
        $dari_tanggal            = $request->dari_tanggal;
        $sampai_tanggal          = $request->sampai_tanggal;
        $pilih_supllier_koperasi = $request->pilih_supllier_koperasi;
    
        $data = DB::table('keuangan as k')
            ->leftJoin('barang_modal_keluar as bmk', function ($join) use ($pilih_dapur) {
                $join->on('k.id_data_koperasi', '=', 'bmk.id_data_koperasi')
                     ->where('bmk.nomor_dapur_barang_modal_keluar', $pilih_dapur);
            })
            ->leftJoin('barang_supplier as bs', function ($join) use ($pilih_dapur) {
                $join->on('k.id_informasi_supplier', '=', 'bs.id_informasi_supplier')
                     ->where('bs.nomor_dapur_barang_supplier', $pilih_dapur);
            })
            ->where('k.nomor_dapur_keuangan', $pilih_dapur)
    
            // HANYA TAMPILKAN JIKA ADA BARANG
            ->where(function ($q) {
                $q->whereNotNull('bmk.id_data_koperasi')
                  ->orWhereNotNull('bs.id_informasi_supplier');
            })
    
            // FILTER TANGGAL
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                $query->whereBetween('k.tanggal_laporan_keuangan', [
                    $dari_tanggal,
                    $sampai_tanggal
                ]);
            })
    
            // FILTER INSTANSI
            ->when($pilih_supllier_koperasi === 'Supplier', function ($query) {
                $query->whereNotNull('bs.id_informasi_supplier');
            })
            ->when($pilih_supllier_koperasi === 'Koperasi', function ($query) {
                $query->whereNotNull('bmk.id_data_koperasi');
            })
            ->select(
                'k.id_data_koperasi',
                'k.tanggal_laporan_keuangan',

                DB::raw('
                    SUM(COALESCE(bmk.harga_barang_modal_keluar,0)) +
                    SUM(COALESCE(bs.harga_barang_supplier,0))
                    AS total_harga
                '),

                DB::raw('MAX(CASE WHEN bmk.id_data_koperasi IS NOT NULL THEN 1 ELSE 0 END) AS dari_koperasi'),
                DB::raw('MAX(CASE WHEN bs.id_informasi_supplier IS NOT NULL THEN 1 ELSE 0 END) AS dari_supplier')
            )
            ->groupBy(
                'k.id_data_koperasi',
                'k.tanggal_laporan_keuangan'
            )
            ->orderBy('k.tanggal_laporan_keuangan', 'desc')
            ->get();
        
        
        /* ================= FLAG STATUS ================= */
        $dataKosong = $data->isEmpty();

        $sudahCari =
            !empty($dari_tanggal) ||
            !empty($sampai_tanggal) ||
            !empty($pilih_dapur);
        



        // ===================== DATA GRAFIK =====================
        $grafik = DB::table('keuangan')
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
            'data',
            'dari_tanggal',
            'sampai_tanggal',
            'pilih_supllier_koperasi',
            'grafik',
            'dapurList',
            'dataKosong',
            'sudahCari'
        ));
    }




    public function barang_owner_laporan_keuangan(Request $request)
    {
        // id_data_koperasi dikirim dari request
        $id_data_koperasi = $request->id;

        // Cek data koperasi untuk ambil id_informasi_supplier-nya
        $keuangan = DB::table('keuangan')
            ->where('id_data_koperasi', $id_data_koperasi)
            ->first();

        $id_informasi_supplier = $keuangan->id_informasi_supplier ?? null;
        $nomor_dapur = $keuangan->nomor_dapur_keuangan;

        // Siapkan variabel barang_list
        $barang_list = collect();

        // 1) Coba ambil dari barang_supplier berdasar id_informasi_supplier
        $barang_list = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
            ->select(
                'barang_supplier.id_barang_supplier as id_barang',
                'barang_supplier.nama_barang_supplier as nama_barang',
                'barang_supplier.jumlah_barang_supplier as jumlah',
                'barang_supplier.satuan_barang_supplier as satuan',
                'barang_supplier.harga_barang_supplier as harga',
                DB::raw("'Supplier' as sumber_data")
            )
            ->get();

        // 2) Jika tidak ada (kosong) → ambil dari barang_modal_keluar berdasar id_data_koperasi
        if ($barang_list->isEmpty()) {
            $barang_list = DB::table('barang_modal_keluar')
                ->where('barang_modal_keluar.id_data_koperasi', $id_data_koperasi)
                ->where('barang_modal_keluar.nomor_dapur_barang_modal_keluar', $nomor_dapur)
                ->select(
                    'barang_modal_keluar.id_barang_modal_keluar as id_barang',
                    'barang_modal_keluar.nama_barang_modal_keluar as nama_barang',
                    'barang_modal_keluar.jumlah_barang_modal_keluar as jumlah',
                    'barang_modal_keluar.satuan_barang_modal_keluar as satuan',
                    'barang_modal_keluar.harga_barang_modal_keluar as harga',
                    DB::raw("'Modal Keluar' as sumber_data")
                )
                ->get();
        }

        // Kirim ke view sebagai barang_list (lebih jelas)
        return view('owner.laporan.keuangan.barang_laporan_keuangan', compact('barang_list'));
    }




    public function cetak_owner_laporan_keuangan(Request $request)
    {
        $pilih_dapur             = $request->pilih_dapur;
        $dari_tanggal            = $request->dari_tanggal;
        $sampai_tanggal          = $request->sampai_tanggal;
        $pilih_supllier_koperasi = $request->pilih_supllier_koperasi;
    
        $laporan = DB::table('keuangan as k')
            ->join('data_koperasi as dk', 'dk.id_data_koperasi', '=', 'k.id_data_koperasi')
            ->leftJoin('barang_modal_keluar as bmk', 'dk.id_data_koperasi', '=', 'bmk.id_data_koperasi')
            ->leftJoin('barang_supplier as bs', 'dk.id_informasi_supplier', '=', 'bs.id_informasi_supplier')

            ->where('k.nomor_dapur_keuangan', $pilih_dapur)

            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('k.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            })

            ->when($pilih_supllier_koperasi === 'Koperasi', function ($q) {
                $q->whereNotNull('bmk.id_data_koperasi');
            })

            ->when($pilih_supllier_koperasi === 'Supplier', function ($q) {
                $q->whereNotNull('bs.id_informasi_supplier');
            })

            ->select(
                'k.tanggal_laporan_keuangan',

                'bmk.nama_barang_modal_keluar',
                'bmk.jumlah_barang_modal_keluar',
                'bmk.harga_barang_modal_keluar',

                'bs.nama_barang_supplier',
                'bs.jumlah_barang_supplier',
                'bs.harga_barang_supplier'
            )
            ->orderBy('k.tanggal_laporan_keuangan', 'asc')
            ->get();
        



        // ===================== DATA GRAFIK =====================
        $grafik = DB::table('keuangan as k')
        ->select(
            'k.tanggal_laporan_keuangan',
    
            DB::raw('
                (
                    SELECT SUM(dk.harga_data_koperasi)
                    FROM data_koperasi dk
                    WHERE dk.id_data_koperasi = k.id_data_koperasi
                    AND dk.jenis_data_koperasi = "modal_masuk"
                ) AS total_pemasukan
            '),
    
            DB::raw('
                (
                    SELECT COALESCE(SUM(bs.harga_barang_supplier),0)
                    FROM barang_supplier bs
                    WHERE bs.id_informasi_supplier = (
                        SELECT id_informasi_supplier
                        FROM data_koperasi
                        WHERE id_data_koperasi = k.id_data_koperasi
                    )
                ) +
                (
                    SELECT COALESCE(SUM(bmk.harga_barang_modal_keluar),0)
                    FROM barang_modal_keluar bmk
                    WHERE bmk.id_data_koperasi = k.id_data_koperasi
                )
                AS total_pengeluaran
            '),
    
            DB::raw('
                (
                    (
                        SELECT SUM(dk.harga_data_koperasi)
                        FROM data_koperasi dk
                        WHERE dk.id_data_koperasi = k.id_data_koperasi
                        AND dk.jenis_data_koperasi = "modal_masuk"
                    )
                    -
                    (
                        (
                            SELECT COALESCE(SUM(bs.harga_barang_supplier),0)
                            FROM barang_supplier bs
                            WHERE bs.id_informasi_supplier = (
                                SELECT id_informasi_supplier
                                FROM data_koperasi
                                WHERE id_data_koperasi = k.id_data_koperasi
                            )
                        ) +
                        (
                            SELECT COALESCE(SUM(bmk.harga_barang_modal_keluar),0)
                            FROM barang_modal_keluar bmk
                            WHERE bmk.id_data_koperasi = k.id_data_koperasi
                        )
                    )
                ) AS margin
            ')
        )
        ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
            $q->whereBetween('k.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
        })
        ->when($pilih_dapur, function ($q) use ($pilih_dapur) {
            $q->where('k.nomor_dapur_keuangan', $pilih_dapur);
        })
        ->groupBy('k.tanggal_laporan_keuangan', 'k.id_data_koperasi')
        ->orderBy('k.tanggal_laporan_keuangan', 'asc')
        ->get()
        ->map(function ($item) {
            $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                ->translatedFormat('d F Y');
            return $item;
        });
    
        return view('owner.laporan.keuangan.cetak_laporan_keuangan', compact(
            'grafik',
            'laporan',
            'dari_tanggal',
            'sampai_tanggal',
            'pilih_supllier_koperasi'
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
        $pilih_supllier_koperasi = $request->pilih_supllier_koperasi;
    
        $data = DB::table('keuangan as k')
            ->leftJoin('barang_modal_keluar as bmk', function ($join) use ($dapur) {
                $join->on('k.id_data_koperasi', '=', 'bmk.id_data_koperasi')
                     ->where('bmk.nomor_dapur_barang_modal_keluar', $dapur);
            })
            ->leftJoin('barang_supplier as bs', function ($join) use ($dapur) {
                $join->on('k.id_informasi_supplier', '=', 'bs.id_informasi_supplier')
                     ->where('bs.nomor_dapur_barang_supplier', $dapur);
            })
            ->where('k.nomor_dapur_keuangan', $dapur)
    
            // HANYA TAMPILKAN JIKA ADA BARANG
            ->where(function ($q) {
                $q->whereNotNull('bmk.id_data_koperasi')
                  ->orWhereNotNull('bs.id_informasi_supplier');
            })
    
            // FILTER TANGGAL
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                $query->whereBetween('k.tanggal_laporan_keuangan', [
                    $dari_tanggal,
                    $sampai_tanggal
                ]);
            })
    
            // FILTER INSTANSI
            ->when($pilih_supllier_koperasi === 'Supplier', function ($query) {
                $query->whereNotNull('bs.id_informasi_supplier');
            })
            ->when($pilih_supllier_koperasi === 'Koperasi', function ($query) {
                $query->whereNotNull('bmk.id_data_koperasi');
            })
    
            ->select(
                'k.id_data_koperasi',
                'k.tanggal_laporan_keuangan',
                DB::raw('MAX(CASE WHEN bmk.id_data_koperasi IS NOT NULL THEN 1 ELSE 0 END) AS dari_koperasi'),
                DB::raw('MAX(CASE WHEN bs.id_informasi_supplier IS NOT NULL THEN 1 ELSE 0 END) AS dari_supplier')
            )
            ->groupBy(
                'k.id_data_koperasi',
                'k.tanggal_laporan_keuangan'
            )
            ->orderBy('k.tanggal_laporan_keuangan', 'desc')
            ->get();
    
        return view('maker.laporan.keuangan.index_laporan_keuangan', compact(
            'data',
            'dari_tanggal',
            'sampai_tanggal',
            'pilih_supllier_koperasi'
        ));
    }




    public function barang_maker_laporan_keuangan(Request $request)
    {
        // id_data_koperasi dikirim dari request
        $id_data_koperasi = $request->id;

        // Cek data koperasi untuk ambil id_informasi_supplier-nya
        $keuangan = DB::table('keuangan')
            ->where('id_data_koperasi', $id_data_koperasi)
            ->first();

        $id_informasi_supplier = $keuangan->id_informasi_supplier ?? null;
        $nomor_dapur = $keuangan->nomor_dapur_keuangan;

        // Siapkan variabel barang_list
        $barang_list = collect();

        // 1) Coba ambil dari barang_supplier berdasar id_informasi_supplier
        $barang_list = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
            ->select(
                'barang_supplier.id_barang_supplier as id_barang',
                'barang_supplier.nama_barang_supplier as nama_barang',
                'barang_supplier.jumlah_barang_supplier as jumlah',
                'barang_supplier.satuan_barang_supplier as satuan',
                'barang_supplier.harga_barang_supplier as harga',
                DB::raw("'Supplier' as sumber_data")
            )
            ->get();

        // 2) Jika tidak ada (kosong) → ambil dari barang_modal_keluar berdasar id_data_koperasi
        if ($barang_list->isEmpty()) {
            $barang_list = DB::table('barang_modal_keluar')
                ->where('barang_modal_keluar.id_data_koperasi', $id_data_koperasi)
                ->where('barang_modal_keluar.nomor_dapur_barang_modal_keluar', $nomor_dapur)
                ->select(
                    'barang_modal_keluar.id_barang_modal_keluar as id_barang',
                    'barang_modal_keluar.nama_barang_modal_keluar as nama_barang',
                    'barang_modal_keluar.jumlah_barang_modal_keluar as jumlah',
                    'barang_modal_keluar.satuan_barang_modal_keluar as satuan',
                    'barang_modal_keluar.harga_barang_modal_keluar as harga',
                    DB::raw("'Koperasi' as sumber_data")
                )
                ->get();
        }

        // Kirim ke view sebagai barang_list (lebih jelas)
        return view('maker.laporan.keuangan.barang_laporan_keuangan', compact('barang_list'));
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
