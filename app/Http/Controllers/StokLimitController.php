<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StokLimitController extends Controller
{
    public function index_stok_limit_kepala_dapur(Request $request)
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
                'stok_masuk.tanggal_kadaluarsa',
                'stok_masuk.id_bahan',
                'bahan.nama_bahan',
                'bahan.satuan_bahan',
                'bahan.limit_bahan',
                'stok_masuk.jumlah_masuk',
                DB::raw('IFNULL(keluar.total_keluar, 0) as total_keluar'),
                DB::raw('(stok_masuk.jumlah_masuk - IFNULL(keluar.total_keluar, 0)) as sisa_stok'),
                'stok_masuk.sumber_stok_masuk',
                'stok_masuk.keterangan_stok_masuk'
            )
            ->orderBy('stok_masuk.id_bahan', 'asc')
            ->orderBy('stok_masuk.tanggal_masuk', 'asc');
        
        if (!empty($filter_bulan)) {
            $stok->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok = $stok->orderBy('stok_masuk.id_bahan', 'asc')->get();
    
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
    
        return view('kepala_dapur.stok_limit.index_stok_limit_kepala_dapur', compact(
            'stok',
            'kepala_dapur',
            'bahan',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'total_sisa_keseluruhan'
        ));
    }


    public function store_stok_limit(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();

        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        $simpan_limit = DB::table('bahan')
            ->where('id_bahan', $request->id_bahan)
            ->update([
                'limit_bahan' => $request->limit_bahan
            ]);

        if ($simpan_limit) {
            return Redirect::back()->with(['success' => 'Data Bahan dan Stok Masuk Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }



    public function tambah_tanggal_kadaluarsa(Request $request)
    {
        $id_stok_masuk = $request->id;
    
        // Ambil data stok berdasarkan id_stok_masuk
        $stok = \App\Models\StokMasuk::find($id_stok_masuk);
    
        // Jika data tidak ditemukan
        if (!$stok) {
            return redirect()->back()->with('error', 'Data stok tidak ditemukan.');
        }
    
        // Kirim data stok ke view
        return view('kepala_dapur.stok_limit.tambah_tanggal_kadaluarsa', compact('stok'));
    }


    public function store_tambah_tanggal_kadaluarsa(Request $request)
    {
        $id_stok_masuk = $request->id;
        $tanggal_kadaluarsa = $request->tanggal_kadaluarsa;

        // Update data stok_masuk berdasarkan id_stok_masuk
        $simpan = DB::table('stok_masuk')
            ->where('id_stok_masuk', $id_stok_masuk)
            ->update(['tanggal_kadaluarsa' => $tanggal_kadaluarsa]);

        // Cek hasil update
        if ($simpan) {
            return redirect()->back()->with([
                'success' => 'Tanggal kadaluarsa berhasil ditambahkan.'
            ]);
        } else {
            return redirect()->back()->with([
                'warning' => 'Tanggal kadaluarsa gagal disimpan.'
            ]);
        }
    }
}