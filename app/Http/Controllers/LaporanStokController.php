<?php

namespace App\Http\Controllers;

use App\Models\LaporanStok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LaporanStokController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_laporan_stok(Request $request)
    {
        $nomor_dapur = 1;
    
        $filter_bulan = $request->input('bulan');
        $filter_bahan = $request->input('id_bahan');
    
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_masuk = DB::table('stok_masuk')
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
            $stok_masuk->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_masuk->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok_masuk = $stok_masuk->orderBy('stok_masuk.tanggal_masuk', 'asc')->get();
    
        // --- 2️⃣ Hitung total sisa keseluruhan dari semua data di atas
        $total_sisa_keseluruhan = $stok_masuk
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

        $dapurs = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nama_dapur', 'nomor_dapur') // pastikan unik
            ->get();
    
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }








        $namaBahan = null;

        if ($filter_bahan) {
            $namaBahan = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan'); // ✅ ambil value langsung, bukan object
        }

    
        // --- Ambil data stok keluar dan hitung sisa per baris
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    SELECT COALESCE(SUM(s2.jumlah_keluar), 0)
                    FROM stok_keluar s2
                    WHERE s2.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s2.tanggal_keluar < sk.tanggal_keluar
                            OR (s2.tanggal_keluar = sk.tanggal_keluar AND s2.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as cumulative_keluar"),
                DB::raw("sm.jumlah_masuk - (
                    SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                    FROM stok_keluar s3
                    WHERE s3.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s3.tanggal_keluar < sk.tanggal_keluar
                            OR (s3.tanggal_keluar = sk.tanggal_keluar AND s3.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as sisa_stok"),
                'sk.tujuan_stok_keluar',
                'sk.keterangan_stok_keluar',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            );
        
        if (!empty($filter_bulan)) {
            $stok_keluar->whereRaw("MONTH(sk.tanggal_keluar) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_keluar->where('sk.id_bahan', $filter_bahan);
        }
    
        $stok_keluar = $stok_keluar->orderBy('sk.tanggal_keluar', 'asc')
                     ->orderBy('sk.id_stok_keluar', 'asc')
                     ->get();

        // --- Hitung sisa per bahan tanpa double counting ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->where(function ($q) use ($nomor_dapur) {
                $q->whereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_masuk')->where('nomor_dapur_stok_masuk', $nomor_dapur);
                })
                ->orWhereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_keluar')->where('nomor_dapur_stok_keluar', $nomor_dapur);
                });
            })
            ->orderBy('b.nama_bahan', 'asc')
            ->get()
            ->keyBy('nama_bahan');
    
        
        




        
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_limit = DB::table('stok_masuk')
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
            $stok_limit->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_limit->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok_limit = $stok_limit->orderBy('stok_masuk.id_bahan', 'asc')->get();
        
        
        
        
        
        
        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($filter_bahan) || !empty($filter_bulan);
    
        return view('owner.laporan.stok.index_laporan_stok', compact(
            'stok_masuk',
            'stok_keluar',
            'stok_limit',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'total_sisa_keseluruhan',
            'sisa_perbahan',
            'namaBahan'
        ));
    }


    public function index_owner_laporan_stok_harian(Request $request)
    {
        $nomor_dapur = $request->pilih_dapur;
        $dari_tanggal = $request->dari_tanggal;
    
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_masuk = DB::table('stok_masuk')
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

        if (!empty($dari_tanggal)) {
            $stok_masuk->whereDate('stok_masuk.tanggal_masuk', $dari_tanggal);
        }
    
        $stok_masuk = $stok_masuk->orderBy('stok_masuk.tanggal_masuk', 'asc')->get();
    
        // --- 2️⃣ Hitung total sisa keseluruhan dari semua data di atas
        $total_sisa_keseluruhan = $stok_masuk
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

        $dapurs = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nama_dapur', 'nomor_dapur') // pastikan unik
            ->get();
    
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }










    
        // --- Ambil data stok keluar dan hitung sisa per baris
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    SELECT COALESCE(SUM(s2.jumlah_keluar), 0)
                    FROM stok_keluar s2
                    WHERE s2.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s2.tanggal_keluar < sk.tanggal_keluar
                            OR (s2.tanggal_keluar = sk.tanggal_keluar AND s2.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as cumulative_keluar"),
                DB::raw("sm.jumlah_masuk - (
                    SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                    FROM stok_keluar s3
                    WHERE s3.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s3.tanggal_keluar < sk.tanggal_keluar
                            OR (s3.tanggal_keluar = sk.tanggal_keluar AND s3.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as sisa_stok"),
                'sk.tujuan_stok_keluar',
                'sk.keterangan_stok_keluar',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            );

        
        if (!empty($dari_tanggal)) {
            $stok_keluar->whereDate('sk.tanggal_keluar', $dari_tanggal);
        }

    
        $stok_keluar = $stok_keluar->orderBy('sk.tanggal_keluar', 'asc')
                     ->orderBy('sk.id_stok_keluar', 'asc')
                     ->get();

        // --- Hitung sisa per bahan tanpa double counting ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->where(function ($q) use ($nomor_dapur) {
                $q->whereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_masuk')->where('nomor_dapur_stok_masuk', $nomor_dapur);
                })
                ->orWhereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_keluar')->where('nomor_dapur_stok_keluar', $nomor_dapur);
                });
            })
            ->orderBy('b.nama_bahan', 'asc')
            ->get()
            ->keyBy('nama_bahan');
    
        
        




        
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_limit = DB::table('stok_masuk')
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


        if (!empty($dari_tanggal)) {
            $stok_limit->whereDate('stok_masuk.tanggal_masuk', $dari_tanggal);
        }

    
        $stok_limit = $stok_limit->orderBy('stok_masuk.id_bahan', 'asc')->get();
        
        
        
        
        
        
        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($nomor_dapur) || !empty($dari_tanggal);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
    
        return view('owner.laporan.stok_harian.index_laporan_stok_harian', compact(
            'stok_masuk',
            'stok_keluar',
            'stok_limit',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'nama_bahan_filter',
            'total_sisa_keseluruhan',
            'sisa_perbahan',
            'dapurList',
            'nomor_dapur',
            'dari_tanggal'
        ));
    }


    public function index_owner_laporan_stok_bulanan(Request $request)
    {
        $nomor_dapur = $request->pilih_dapur;
        $bulan = $request->bulan; // Format: YYYY-MM (misal: 2025-10)
        $filter_bahan = $request->filter_bahan;

        // Pecah bulan dan tahun dari input
        $bulan = null;
        $tahun = null;
        if (!empty($bulan)) {
            $tanggalObj = Carbon::parse($bulan . '-01');
            $bulan = $tanggalObj->month;
            $tahun = $tanggalObj->year;
        }

        // --- 1️⃣ Ambil detail stok masuk per bahan berdasarkan bulan ---
        $stok_masuk = DB::table('stok_masuk')
            ->leftJoin('bahan', 'stok_masuk.id_bahan', '=', 'bahan.id_bahan')
            ->leftJoin(DB::raw('(
                SELECT id_stok_masuk, SUM(jumlah_keluar) as total_keluar
                FROM stok_keluar
                GROUP BY id_stok_masuk
            ) as keluar'), 'stok_masuk.id_stok_masuk', '=', 'keluar.id_stok_masuk')
            ->where('stok_masuk.nomor_dapur_stok_masuk', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('stok_masuk.tanggal_masuk', $bulan)
                      ->whereYear('stok_masuk.tanggal_masuk', $tahun);
            })
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
            )
            ->orderBy('stok_masuk.tanggal_masuk', 'asc')
            ->get();

        // --- 2️⃣ Ambil stok keluar berdasarkan bulan ---
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('sk.tanggal_keluar', $bulan)
                      ->whereYear('sk.tanggal_keluar', $tahun);
            })
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            )
            ->orderBy('sk.tanggal_keluar', 'asc')
            ->get();

        // --- 3️⃣ Hitung total masuk & keluar per bahan selama bulan tersebut ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_masuk', $bulan)
                      ->whereYear('tanggal_masuk', $tahun);
            })
            ->groupBy('id_bahan');

        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_keluar', $bulan)
                      ->whereYear('tanggal_keluar', $tahun);
            })
            ->groupBy('id_bahan');

        // --- 4️⃣ Hitung sisa stok per bahan ---
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->orderBy('b.nama_bahan', 'asc')
            ->get();

        // --- 5️⃣ Hitung total keseluruhan bulan ini ---
        $total_masuk_bulan = $sisa_perbahan->sum('total_masuk');
        $total_keluar_bulan = $sisa_perbahan->sum('total_keluar');
        $total_sisa_bulan = $sisa_perbahan->sum('sisa_per_bahan');

        // --- 6️⃣ Data tambahan ---
        $bahan = DB::table('bahan')->select('id_bahan', 'nama_bahan')->orderBy('nama_bahan')->get();
        $dapurs = DB::table('dapur')->select('nomor_dapur', 'nama_dapur')->groupBy('nama_dapur', 'nomor_dapur')->get();

        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($bulan);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('owner.laporan.stok_bulanan.index_laporan_stok_bulanan', compact(
            'stok_masuk',
            'stok_keluar',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'sisa_perbahan',
            'total_masuk_bulan',
            'total_keluar_bulan',
            'total_sisa_bulan',
            'nomor_dapur',
            'dapurList'
        ));
    }





























    // BAGIAN ADMIN 
    public function index_admin_laporan_stok(Request $request)
    {
        $nomor_dapur = 1;
    
        $filter_bulan = $request->input('bulan');
        $filter_bahan = $request->input('id_bahan');
    
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_masuk = DB::table('stok_masuk')
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
            $stok_masuk->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_masuk->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok_masuk = $stok_masuk->orderBy('stok_masuk.tanggal_masuk', 'asc')->get();
    
        // --- 2️⃣ Hitung total sisa keseluruhan dari semua data di atas
        $total_sisa_keseluruhan = $stok_masuk
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

        $dapurs = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nama_dapur', 'nomor_dapur') // pastikan unik
            ->get();
    
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }








        $namaBahan = null;

        if ($filter_bahan) {
            $namaBahan = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan'); // ✅ ambil value langsung, bukan object
        }

    
        // --- Ambil data stok keluar dan hitung sisa per baris
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    SELECT COALESCE(SUM(s2.jumlah_keluar), 0)
                    FROM stok_keluar s2
                    WHERE s2.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s2.tanggal_keluar < sk.tanggal_keluar
                            OR (s2.tanggal_keluar = sk.tanggal_keluar AND s2.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as cumulative_keluar"),
                DB::raw("sm.jumlah_masuk - (
                    SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                    FROM stok_keluar s3
                    WHERE s3.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s3.tanggal_keluar < sk.tanggal_keluar
                            OR (s3.tanggal_keluar = sk.tanggal_keluar AND s3.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as sisa_stok"),
                'sk.tujuan_stok_keluar',
                'sk.keterangan_stok_keluar',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            );
        
        if (!empty($filter_bulan)) {
            $stok_keluar->whereRaw("MONTH(sk.tanggal_keluar) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_keluar->where('sk.id_bahan', $filter_bahan);
        }
    
        $stok_keluar = $stok_keluar->orderBy('sk.tanggal_keluar', 'asc')
                     ->orderBy('sk.id_stok_keluar', 'asc')
                     ->get();

        // --- Hitung sisa per bahan tanpa double counting ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->where(function ($q) use ($nomor_dapur) {
                $q->whereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_masuk')->where('nomor_dapur_stok_masuk', $nomor_dapur);
                })
                ->orWhereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_keluar')->where('nomor_dapur_stok_keluar', $nomor_dapur);
                });
            })
            ->orderBy('b.nama_bahan', 'asc')
            ->get()
            ->keyBy('nama_bahan');
    
        
        




        
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_limit = DB::table('stok_masuk')
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
            $stok_limit->whereRaw("MONTH(stok_masuk.tanggal_masuk) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok_limit->where('stok_masuk.id_bahan', $filter_bahan);
        }
    
        $stok_limit = $stok_limit->orderBy('stok_masuk.id_bahan', 'asc')->get();
        
        
        
        
        
        
        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($filter_bahan) || !empty($filter_bulan);
    
        return view('admin.laporan.stok.index_laporan_stok', compact(
            'stok_masuk',
            'stok_keluar',
            'stok_limit',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'total_sisa_keseluruhan',
            'sisa_perbahan',
            'namaBahan'
        ));
    }



    public function index_admin_laporan_stok_harian(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur = $admin->nomor_dapur_admin;

        $dari_tanggal = $request->dari_tanggal;
    
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_masuk = DB::table('stok_masuk')
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

        if (!empty($dari_tanggal)) {
            $stok_masuk->whereDate('stok_masuk.tanggal_masuk', $dari_tanggal);
        }
    
        $stok_masuk = $stok_masuk->orderBy('stok_masuk.tanggal_masuk', 'asc')->get();
    
        // --- 2️⃣ Hitung total sisa keseluruhan dari semua data di atas
        $total_sisa_keseluruhan = $stok_masuk
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

        $dapurs = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nama_dapur', 'nomor_dapur') // pastikan unik
            ->get();
    
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan');
        }










    
        // --- Ambil data stok keluar dan hitung sisa per baris
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    SELECT COALESCE(SUM(s2.jumlah_keluar), 0)
                    FROM stok_keluar s2
                    WHERE s2.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s2.tanggal_keluar < sk.tanggal_keluar
                            OR (s2.tanggal_keluar = sk.tanggal_keluar AND s2.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as cumulative_keluar"),
                DB::raw("sm.jumlah_masuk - (
                    SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                    FROM stok_keluar s3
                    WHERE s3.id_stok_masuk = sk.id_stok_masuk
                      AND (
                            s3.tanggal_keluar < sk.tanggal_keluar
                            OR (s3.tanggal_keluar = sk.tanggal_keluar AND s3.id_stok_keluar <= sk.id_stok_keluar)
                          )
                ) as sisa_stok"),
                'sk.tujuan_stok_keluar',
                'sk.keterangan_stok_keluar',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            );

        
        if (!empty($dari_tanggal)) {
            $stok_keluar->whereDate('sk.tanggal_keluar', $dari_tanggal);
        }

    
        $stok_keluar = $stok_keluar->orderBy('sk.tanggal_keluar', 'asc')
                     ->orderBy('sk.id_stok_keluar', 'asc')
                     ->get();

        // --- Hitung sisa per bahan tanpa double counting ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->groupBy('id_bahan');
            
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->where(function ($q) use ($nomor_dapur) {
                $q->whereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_masuk')->where('nomor_dapur_stok_masuk', $nomor_dapur);
                })
                ->orWhereIn('b.id_bahan', function ($sub) use ($nomor_dapur) {
                    $sub->select('id_bahan')->from('stok_keluar')->where('nomor_dapur_stok_keluar', $nomor_dapur);
                });
            })
            ->orderBy('b.nama_bahan', 'asc')
            ->get()
            ->keyBy('nama_bahan');
    
        
        




        
        // --- 1️⃣ Ambil detail stok masuk per baris (supaya sisa per batch tetap ada)
        $stok_limit = DB::table('stok_masuk')
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


        if (!empty($dari_tanggal)) {
            $stok_limit->whereDate('stok_masuk.tanggal_masuk', $dari_tanggal);
        }

    
        $stok_limit = $stok_limit->orderBy('stok_masuk.id_bahan', 'asc')->get();
        
        
        
        
        
        
        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($nomor_dapur) || !empty($dari_tanggal);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
    
        return view('admin.laporan.stok_harian.index_laporan_stok_harian', compact(
            'stok_masuk',
            'stok_keluar',
            'stok_limit',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'nama_bahan_filter',
            'total_sisa_keseluruhan',
            'sisa_perbahan',
            'dapurList',
            'nomor_dapur',
            'dari_tanggal'
        ));
    }


    public function index_admin_laporan_stok_bulanan(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur = $admin->nomor_dapur_admin;
        
        $bulan = $request->bulan; // Format: YYYY-MM (misal: 2025-10)
        $filter_bahan = $request->filter_bahan;

        // Pecah bulan dan tahun dari input
        $bulan = null;
        $tahun = null;
        if (!empty($bulan)) {
            $tanggalObj = Carbon::parse($bulan . '-01');
            $bulan = $tanggalObj->month;
            $tahun = $tanggalObj->year;
        }

        // --- 1️⃣ Ambil detail stok masuk per bahan berdasarkan bulan ---
        $stok_masuk = DB::table('stok_masuk')
            ->leftJoin('bahan', 'stok_masuk.id_bahan', '=', 'bahan.id_bahan')
            ->leftJoin(DB::raw('(
                SELECT id_stok_masuk, SUM(jumlah_keluar) as total_keluar
                FROM stok_keluar
                GROUP BY id_stok_masuk
            ) as keluar'), 'stok_masuk.id_stok_masuk', '=', 'keluar.id_stok_masuk')
            ->where('stok_masuk.nomor_dapur_stok_masuk', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('stok_masuk.tanggal_masuk', $bulan)
                      ->whereYear('stok_masuk.tanggal_masuk', $tahun);
            })
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
            )
            ->orderBy('stok_masuk.tanggal_masuk', 'asc')
            ->get();

        // --- 2️⃣ Ambil stok keluar berdasarkan bulan ---
        $stok_keluar = DB::table('stok_keluar as sk')
            ->leftJoin('stok_masuk as sm', 'sk.id_stok_masuk', '=', 'sm.id_stok_masuk')
            ->leftJoin('bahan as b', 'sk.id_bahan', '=', 'b.id_bahan')
            ->where('sk.nomor_dapur_stok_keluar', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('sk.tanggal_keluar', $bulan)
                      ->whereYear('sk.tanggal_keluar', $tahun);
            })
            ->select(
                'sk.id_stok_keluar',
                'sk.tanggal_keluar',
                'sk.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                'sk.jumlah_keluar',
                'sm.jumlah_masuk',
                'sm.tanggal_masuk',
                DB::raw("(
                    sm.jumlah_masuk - (
                        SELECT COALESCE(SUM(s3.jumlah_keluar), 0)
                        FROM stok_keluar s3
                        WHERE s3.id_stok_masuk = sk.id_stok_masuk
                    )
                ) as sisa_perbahan")
            )
            ->orderBy('sk.tanggal_keluar', 'asc')
            ->get();

        // --- 3️⃣ Hitung total masuk & keluar per bahan selama bulan tersebut ---
        $totalMasukSub = DB::table('stok_masuk')
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total_masuk'))
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_masuk', $bulan)
                      ->whereYear('tanggal_masuk', $tahun);
            })
            ->groupBy('id_bahan');

        $totalKeluarSub = DB::table('stok_keluar')
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->when($bulan && $tahun, function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_keluar', $bulan)
                      ->whereYear('tanggal_keluar', $tahun);
            })
            ->groupBy('id_bahan');

        // --- 4️⃣ Hitung sisa stok per bahan ---
        $sisa_perbahan = DB::table('bahan as b')
            ->leftJoinSub($totalMasukSub, 'sm', function ($join) {
                $join->on('b.id_bahan', '=', 'sm.id_bahan');
            })
            ->leftJoinSub($totalKeluarSub, 'sk', function ($join) {
                $join->on('b.id_bahan', '=', 'sk.id_bahan');
            })
            ->select(
                'b.id_bahan',
                'b.nama_bahan',
                'b.satuan_bahan',
                DB::raw('COALESCE(sm.total_masuk, 0) as total_masuk'),
                DB::raw('COALESCE(sk.total_keluar, 0) as total_keluar'),
                DB::raw('(COALESCE(sm.total_masuk, 0) - COALESCE(sk.total_keluar, 0)) as sisa_per_bahan')
            )
            ->orderBy('b.nama_bahan', 'asc')
            ->get();

        // --- 5️⃣ Hitung total keseluruhan bulan ini ---
        $total_masuk_bulan = $sisa_perbahan->sum('total_masuk');
        $total_keluar_bulan = $sisa_perbahan->sum('total_keluar');
        $total_sisa_bulan = $sisa_perbahan->sum('sisa_per_bahan');

        // --- 6️⃣ Data tambahan ---
        $bahan = DB::table('bahan')->select('id_bahan', 'nama_bahan')->orderBy('nama_bahan')->get();
        $dapurs = DB::table('dapur')->select('nomor_dapur', 'nama_dapur')->groupBy('nama_dapur', 'nomor_dapur')->get();

        $dataKosong = $stok_masuk->isEmpty();
        $sudahCari = !empty($bulan);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('admin.laporan.stok_bulanan.index_laporan_stok_bulanan', compact(
            'stok_masuk',
            'stok_keluar',
            'dapurs',
            'bahan',
            'dataKosong',
            'sudahCari',
            'sisa_perbahan',
            'total_masuk_bulan',
            'total_keluar_bulan',
            'total_sisa_bulan',
            'nomor_dapur',
            'dapurList'
        ));
    }
}
