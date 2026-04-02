<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StokMasukController extends Controller
{
    // -- MAKER --
    public function index_stok_masuk_maker(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $nomor_dapur = $maker->nomor_dapur_maker;
    
        $filter_bulan = $request->input('bulan');
        $filter_bahan = $request->input('id_bahan');
    
        // --- 1️⃣ Ambil detail stok masuk
        $stok = DB::table('stok_masuk')
            ->leftJoin('bahan', 'stok_masuk.id_bahan', '=', 'bahan.id_bahan')
            ->leftJoin(DB::raw('(
                SELECT id_stok_masuk, SUM(jumlah_keluar) as total_keluar
                FROM stok_keluar
                GROUP BY id_stok_masuk
            ) as keluar'), 'stok_masuk.id_stok_masuk', '=', 'keluar.id_stok_masuk')
            ->where('stok_masuk.nomor_dapur_stok_masuk', $nomor_dapur)
            ->select(
                'stok_masuk.id_stok_masuk',
                'stok_masuk.tanggal_masuk',
                'stok_masuk.id_bahan',
                'bahan.nama_bahan',
                'bahan.satuan_bahan',
                'stok_masuk.jumlah_masuk',
                DB::raw('IFNULL(keluar.total_keluar,0) as total_keluar'),
                DB::raw('(stok_masuk.jumlah_masuk - IFNULL(keluar.total_keluar,0)) as sisa_stok'),
                'stok_masuk.sumber_stok_masuk',
                'stok_masuk.keterangan_stok_masuk'
            );
    
        if (!empty($filter_bulan)) {
            $stok->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok = $stok->orderBy('stok_masuk.tanggal_masuk','asc')->get();
    
    
        // --- 2️⃣ Hitung total sisa stok keseluruhan
        $total_sisa_keseluruhan = $stok
            ->groupBy('id_bahan')
            ->map(function ($items) {
                return $items->sum('sisa_stok');
            })
            ->sum();
    
    
        // --- 3️⃣ Data dropdown bahan (supplier + koperasi)
        $bahan_supplier = DB::table('barang_supplier')
            ->select('nama_barang_supplier as nama_bahan')
            ->where('nomor_dapur_barang_supplier', $nomor_dapur);

        $bahan_koperasi = DB::table('barang_modal_keluar')
            ->select('nama_barang_modal_keluar as nama_bahan')
            ->where('nomor_dapur_barang_modal_keluar', $nomor_dapur);

        $union_bahan = $bahan_supplier->union($bahan_koperasi);

        $bahan = DB::table('bahan')
            ->joinSub($union_bahan, 'union_bahan', function ($join) {
                $join->on('bahan.nama_bahan', '=', 'union_bahan.nama_bahan');
            })
            ->select('bahan.id_bahan', 'bahan.nama_bahan')
            ->distinct()
            ->orderBy('bahan.nama_bahan','asc')
            ->get();
    
    
        // --- 4️⃣ Data supplier
        $supplier = DB::table('informasi_supplier')
            ->where('nomor_dapur_informasi_supplier', $nomor_dapur)
            ->get();
    
    
        // --- 5️⃣ Ambil nama bahan dari filter
        $nama_bahan_filter = null;
    
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }
    
    
        $dataKosong = $stok->isEmpty();
        $sudahCari = !empty($filter_bahan) || !empty($filter_bulan);
    
    
        return view('maker.stok.stok_masuk.index_stok_masuk_maker', compact(
            'stok',
            'supplier',
            'maker',
            'bahan',
            'dataKosong',
            'sudahCari',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'total_sisa_keseluruhan'
        ));
    }

    public function store_stok_masuk_maker(Request $request)
    {
        $maker              = Auth::guard('maker')->user();
        $nomor_dapur_maker  = $maker->nomor_dapur_maker;
        $nama_lengkap       = $maker->nama_lengkap;
    
        // ambil nama bahan dari dropdown
        $nama_bahan = $request->nama_bahan;

        // cek apakah bahan sudah ada di tabel bahan
        $cek_bahan = DB::table('bahan')
            ->where('nama_bahan', $nama_bahan)
            ->where('nomor_dapur_bahan', $nomor_dapur_maker)
            ->first();

        if (!$cek_bahan) {

            // jika belum ada → insert
            $id_bahan = DB::table('bahan')->insertGetId([
                'nama_bahan' => $nama_bahan,
                'satuan_bahan' => $request->satuan_bahan, // satuan bahan ini seharusnya bisa dihapus untuk menyederhanakan tabel bahan
                'nomor_dapur_bahan' => $nomor_dapur_maker
            ]);

        } else {

            // jika sudah ada → gunakan id yang ada
            $id_bahan = $cek_bahan->id_bahan;
        }

        $data_stok_masuk = [
            'id_bahan' => $id_bahan,
            'nomor_dapur_stok_masuk' => $nomor_dapur_maker,
            'tanggal_masuk'   => $request->tanggal_masuk,
            'jumlah_masuk' => $request->jumlah_masuk,
            'sumber_stok_masuk' => $request->sumber_stok_masuk,
            'keterangan_stok_masuk' => $request->keterangan_stok_masuk
        ];

        $simpan_stok = DB::table('stok_masuk')->insert($data_stok_masuk);

        if ($simpan_stok) {
            return Redirect::back()->with(['success' => 'Data Bahan dan Stok Masuk Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_stok_masuk_maker(Request $request)
    {
        $id = $request->id;
        $stok = DB::table('stok')->get();
        $data = DB::table('stok')->where('id_stok', $id)->first();
        return view('maker.stok.stok_masuk.edit_stok_masuk_maker',compact('stok','data'));
    }

    public function update_stok_masuk_maker($id, Request $request)
    {
        try {
            $data = [
                'tanggal_masuk_stok'   => $request->tanggal_masuk_stok,
                'nama_stok' => $request->nama_stok,
                'jumlah_stok_masuk' => $request->jumlah_stok_masuk,
                'satuan_stok' => $request->satuan_stok,
                'sumber_masuk_stok' => $request->sumber_masuk_stok,
                'keterangan_stok' => $request->keterangan_stok
            ];
            $update = DB::table('stok')->where('id_stok', $id)->update($data);
            if ($update){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_stok_masuk_maker($id)
    {
        // Ambil data stok dulu untuk mengetahui id_bahan-nya
        $stok = DB::table('stok_masuk')->where('id_stok_masuk', $id)->first();
    
        if ($stok) {
            // Hapus stok masuk terlebih dahulu
            DB::table('stok_masuk')->where('id_stok_masuk', $id)->delete();

            // Hapus stok keluar
            DB::table('stok_keluar')->where('id_stok_masuk', $id)->delete();
        
            return Redirect::back()->with(['success' => 'Data stok dan bahan berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data stok tidak ditemukan']);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // -- KEPALA DAPUR --
    public function index_stok_masuk_kepala_dapur(Request $request)
    {
        $kepala_dapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepala_dapur->nomor_dapur_kepala_dapur;
    
        $filter_bulan = $request->input('bulan');
        $filter_bahan = $request->input('id_bahan');
    
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok = DB::table('stok_masuk')
            ->leftJoin('bahan', 'stok_masuk.id_bahan', '=', 'bahan.id_bahan')
            ->leftJoin(DB::raw('(
                SELECT id_stok_masuk, SUM(jumlah_keluar) as total_keluar
                FROM stok_keluar
                GROUP BY id_stok_masuk
            ) as keluar'), 'stok_masuk.id_stok_masuk', '=', 'keluar.id_stok_masuk')
            ->where('stok_masuk.nomor_dapur_stok_masuk', $nomor_dapur)
            ->select(
                'stok_masuk.id_stok_masuk',
                'stok_masuk.tanggal_masuk',
                'stok_masuk.id_bahan',
                'bahan.nama_bahan',
                'bahan.satuan_bahan',
                'stok_masuk.jumlah_masuk',
                DB::raw('IFNULL(keluar.total_keluar, 0) as total_keluar'),
                DB::raw('(stok_masuk.jumlah_masuk - IFNULL(keluar.total_keluar, 0)) as sisa_stok'),
                'stok_masuk.sumber_stok_masuk',
                'stok_masuk.keterangan_stok_masuk'
            );
        
        if (!empty($filter_bulan)) {
            $stok->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok = $stok->orderBy('stok_masuk.tanggal_masuk', 'asc')->get();
    
        // --- 2️⃣ Hitung total sisa keseluruhan dari semua data di atas
        $total_sisa_keseluruhan = $stok
            ->groupBy('id_bahan') // kelompokkan per bahan
            ->map(function ($items) {
                return $items->sum('sisa_stok'); // jumlahkan sisa per bahan
            })
            ->sum(); // lalu jumlahkan semua bahan
    
        // --- 3️⃣ Data filter dropdown bahan
        $bahan = DB::table('bahan')
            ->select('id_bahan', 'nama_bahan')
            ->orderBy('nama_bahan', 'asc')
            ->get();
    
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }
    
        $dataKosong = $stok->isEmpty();
        $sudahCari = !empty($filter_bahan) || !empty($filter_bulan);
    
        return view('kepala_dapur.stok_masuk.index_stok_masuk_kepala_dapur', compact(
            'stok',
            'kepala_dapur',
            'bahan',
            'dataKosong',
            'sudahCari',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'total_sisa_keseluruhan'
        ));
    }

    public function store_stok_masuk(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();

        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;
        $nama_lengkap = $kepalaDapur->nama_lengkap;
    
        if (!empty($request->nama_bahan)) {
            $id_bahan = DB::table('bahan')->insertGetId([
                'nama_bahan' => $request->nama_bahan,
                'satuan_bahan' => $request->satuan_bahan
            ]);
        } else {
            $id_bahan = $request->id_bahan;
        }

        $data_stok_masuk = [
            'id_bahan' => $id_bahan,
            'nomor_dapur_stok_masuk' => $nomor_dapur_kepala_dapur,
            'tanggal_masuk'   => $request->tanggal_masuk,
            'jumlah_masuk' => $request->jumlah_masuk,
            'sumber_stok_masuk' => $request->sumber_stok_masuk,
            'keterangan_stok_masuk' => $request->keterangan_stok_masuk
        ];

        $simpan_stok = DB::table('stok_masuk')->insert($data_stok_masuk);

        if ($simpan_stok) {
            return Redirect::back()->with(['success' => 'Data Bahan dan Stok Masuk Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_stok_masuk(Request $request)
    {
        $id = $request->id;
        $stok = DB::table('stok')->get();
        $data = DB::table('stok')->where('id_stok', $id)->first();
        return view('kepala_dapur.stok_masuk.edit_stok_masuk_kepala_dapur',compact('stok','data'));
    }

    public function update_stok_masuk($id, Request $request)
    {
        try {
            $data = [
                'tanggal_masuk_stok'   => $request->tanggal_masuk_stok,
                'nama_stok' => $request->nama_stok,
                'jumlah_stok_masuk' => $request->jumlah_stok_masuk,
                'satuan_stok' => $request->satuan_stok,
                'sumber_masuk_stok' => $request->sumber_masuk_stok,
                'keterangan_stok' => $request->keterangan_stok
            ];
            $update = DB::table('stok')->where('id_stok', $id)->update($data);
            if ($update){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function delete_stok_masuk($id)
    {
        // Ambil data stok dulu untuk mengetahui id_bahan-nya
        $stok = DB::table('stok_masuk')->where('id_stok_masuk', $id)->first();
    
        if ($stok) {
            // Hapus stok masuk terlebih dahulu
            DB::table('stok_masuk')->where('id_stok_masuk', $id)->delete();

            // Hapus stok keluar
            DB::table('stok_keluar')->where('id_stok_masuk', $id)->delete();
        
            return Redirect::back()->with(['success' => 'Data stok dan bahan berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data stok tidak ditemukan']);
        }
    }
}
