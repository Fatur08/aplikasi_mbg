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
        $maker         = Auth::guard('maker')->user();
        $dapur         = $maker->nomor_dapur_maker;
    
        // =======================
        // TANGGAL PERIODE
        // =======================
        $dari_tanggal   = $request->dari_tanggal ?? date('Y-m-01');
        $sampai_tanggal = $request->sampai_tanggal ?? date('Y-m-d');
    
        // =======================
        // STOK AWAL (sebelum dari_tanggal)
        // =======================
        $stok_awal_masuk = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', '<', $dari_tanggal)
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        $stok_awal_keluar = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', '<', $dari_tanggal)
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // STOK MASUK PERIODE
        // =======================
        $stok_masuk_periode = DB::table('stok_masuk')
            ->whereBetween('tanggal_masuk', [$dari_tanggal, $sampai_tanggal])
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // STOK KELUAR PERIODE
        // =======================
        $stok_keluar_periode = DB::table('stok_keluar')
            ->whereBetween('tanggal_keluar', [$dari_tanggal, $sampai_tanggal])
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // KUMPULKAN SEMUA ID BAHAN
        // =======================
        $semua_bahan = collect()
            ->merge($stok_awal_masuk->keys())
            ->merge($stok_awal_keluar->keys())
            ->merge($stok_masuk_periode->keys())
            ->merge($stok_keluar_periode->keys())
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();
    
        $data_laporan = [];
    
        foreach ($semua_bahan as $id_bahan) {
            $awal_masuk  = $stok_awal_masuk->get($id_bahan)->total ?? 0;
            $awal_keluar = $stok_awal_keluar->get($id_bahan)->total ?? 0;
    
            $stok_awal = $awal_masuk - $awal_keluar;
    
            $masuk  = $stok_masuk_periode->get($id_bahan)->total ?? 0;
            $keluar = $stok_keluar_periode->get($id_bahan)->total ?? 0;
    
            $stok_akhir = $stok_awal + $masuk - $keluar;
    
            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
    
            $data_laporan[] = [
                'id_bahan'   => $id_bahan,
                'nama_bahan' => $bahan->nama_bahan ?? ('ID-' . $id_bahan),
                'satuan'     => $bahan->satuan_bahan ?? '-',
                'stok_awal'  => $stok_awal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stok_akhir,
            ];
        }



        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();


    
        return view('owner.laporan.stok.index_laporan_stok', compact(
            'data_laporan',
            'dapur',
            'dari_tanggal',
            'sampai_tanggal',
            'dapurList'
        ));
    }


    public function index_owner_laporan_stok_harian(Request $request)
    {
        $nomor_dapur        = $request->pilih_dapur;
        $tanggal            = $request->pilih_tanggal ?? date('Y-m-d');
        $tanggal_kemarin    = date('Y-m-d', strtotime($tanggal . ' -1 day'));

        // =======================
        // STOK AWAL (s/d kemarin)
        // =======================
        $stok_awal_masuk = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', '<=', $tanggal_kemarin)
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
            
        $stok_awal_keluar = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', '<=', $tanggal_kemarin)
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
            
        // =======================
        // STOK MASUK HARI INI (FIX)
        // =======================
        $stok_masuk_hari_ini = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', $tanggal)
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // =======================
        // STOK KELUAR HARI INI (FIX)
        // =======================
        $stok_keluar_hari_ini = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', $tanggal)
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
        

            
        // pastikan semua collection sudah di-keyBy id_bahan untuk konsistensi
        $stok_awal_masuk = $stok_awal_masuk->keyBy('id_bahan');
        $stok_awal_keluar = $stok_awal_keluar->keyBy('id_bahan');
        $stok_masuk_hari_ini = $stok_masuk_hari_ini->keyBy('id_bahan');
        $stok_keluar_hari_ini = $stok_keluar_hari_ini->keyBy('id_bahan');

        // kumpulkan semua id_bahan yang terlibat (id numerik)
        $semua_bahan = collect()
            ->merge($stok_awal_masuk->keys())
            ->merge($stok_awal_keluar->keys())
            ->merge($stok_masuk_hari_ini->keys())
            ->merge($stok_keluar_hari_ini->keys())
            ->filter(fn($id) => $id > 0)   // ✅ buang ID 0 / null
            ->unique()
            ->values();

        $data_laporan = [];

        foreach ($semua_bahan as $id_bahan) {
            $awal_masuk_obj  = $stok_awal_masuk->get($id_bahan);
            $awal_keluar_obj = $stok_awal_keluar->get($id_bahan);
        
            $awal_masuk_total  = $awal_masuk_obj->total ?? 0;
            $awal_keluar_total = $awal_keluar_obj->total ?? 0;
        
            $stok_awal = $awal_masuk_total - $awal_keluar_total;
        
            $stok_masuk_hari = $stok_masuk_hari_ini->get($id_bahan)->total ?? 0;
            $stok_keluar_hari = $stok_keluar_hari_ini->get($id_bahan)->total ?? 0;
        
            $stok_akhir = $stok_awal + $stok_masuk_hari - $stok_keluar_hari;
        
            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
        
            $data_laporan[] = [
                'id_bahan'    => $id_bahan,
                'nama_bahan'  => $bahan->nama_bahan ?? ('ID-'.$id_bahan),
                'satuan'      => $bahan->satuan_bahan ?? '-',
                'stok_awal'   => $stok_awal,
                'masuk'       => $stok_masuk_hari,
                'keluar'      => $stok_keluar_hari,
                'stok_akhir'  => $stok_akhir,
            ];
        }







        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
    
        return view('owner.laporan.stok_harian.index_laporan_stok_harian', compact(
            'data_laporan',
            'dapurList',
            'nomor_dapur',
            'tanggal'
        ));
    }


    public function index_owner_laporan_stok_bulanan(Request $request)
    {
        $nomor_dapur = $request->pilih_dapur;

        // ============================
        // PROSES BULAN & TAHUN
        // ============================
        $bulan_map = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $nama_bulan = $request->bulan;
        $bulan = $bulan_map[$nama_bulan] ?? date('m');
        $tahun = date('Y');

        $filter_bulan = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // ============================
        // STOK MASUK BULANAN
        // ============================
        $stok_masuk_bulanan = DB::table('stok_masuk')
            ->whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->where('nomor_dapur_stok_masuk', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // ============================
        // STOK KELUAR BULANAN
        // ============================
        $stok_keluar_bulanan = DB::table('stok_keluar')
            ->whereMonth('tanggal_keluar', $bulan)
            ->whereYear('tanggal_keluar', $tahun)
            ->where('nomor_dapur_stok_keluar', $nomor_dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // ============================
        // GABUNG SEMUA ID BAHAN
        // ============================
        $semua_bahan = collect()
            ->merge($stok_masuk_bulanan->keys())
            ->merge($stok_keluar_bulanan->keys())
            ->unique()
            ->values();

        $data_laporan = [];

        foreach ($semua_bahan as $id_bahan) {

            $total_masuk  = $stok_masuk_bulanan->get($id_bahan)->total ?? 0;
            $total_keluar = $stok_keluar_bulanan->get($id_bahan)->total ?? 0;
            $stok_akhir   = $total_masuk - $total_keluar;

            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
            $limit = $bahan->batas_limit ?? 0;

            // ============================
            // KETERANGAN
            // ============================
            if ($stok_akhir < 0) {
                $keterangan = 'Minus';
            } elseif ($stok_akhir == 0) {
                $keterangan = 'Habis';
            } elseif ($stok_akhir == $limit) {
                $keterangan = 'Menipis';
            } else {
                $keterangan = 'Aman';
            }

            $data_laporan[] = [
                'id_bahan'     => $id_bahan,
                'nama_bahan'   => $bahan->nama_bahan ?? ('ID-'.$id_bahan),
                'satuan'       => $bahan->satuan_bahan ?? '-',
                'total_masuk'  => $total_masuk,
                'total_keluar' => $total_keluar,
                'stok_akhir'   => $stok_akhir,
                'keterangan'   => $keterangan,
            ];
        }

        // ============================
        // DATA DAPUR
        // ============================
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('owner.laporan.stok_bulanan.index_laporan_stok_bulanan', compact(
            'data_laporan',
            'dapurList',
            'nomor_dapur',
            'filter_bulan'
        ));
    }





























    // BAGIAN MAKER
    public function index_maker_laporan_stok(Request $request)
    {
        $maker         = Auth::guard('maker')->user();
        $dapur         = $maker->nomor_dapur_maker;
    
        // =======================
        // TANGGAL PERIODE
        // =======================
        $dari_tanggal   = $request->dari_tanggal ?? date('Y-m-01');
        $sampai_tanggal = $request->sampai_tanggal ?? date('Y-m-d');
    
        // =======================
        // STOK AWAL (sebelum dari_tanggal)
        // =======================
        $stok_awal_masuk = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', '<', $dari_tanggal)
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        $stok_awal_keluar = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', '<', $dari_tanggal)
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // STOK MASUK PERIODE
        // =======================
        $stok_masuk_periode = DB::table('stok_masuk')
            ->whereBetween('tanggal_masuk', [$dari_tanggal, $sampai_tanggal])
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // STOK KELUAR PERIODE
        // =======================
        $stok_keluar_periode = DB::table('stok_keluar')
            ->whereBetween('tanggal_keluar', [$dari_tanggal, $sampai_tanggal])
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
    
        // =======================
        // KUMPULKAN SEMUA ID BAHAN
        // =======================
        $semua_bahan = collect()
            ->merge($stok_awal_masuk->keys())
            ->merge($stok_awal_keluar->keys())
            ->merge($stok_masuk_periode->keys())
            ->merge($stok_keluar_periode->keys())
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();
    
        $data_laporan = [];
    
        foreach ($semua_bahan as $id_bahan) {
            $awal_masuk  = $stok_awal_masuk->get($id_bahan)->total ?? 0;
            $awal_keluar = $stok_awal_keluar->get($id_bahan)->total ?? 0;
    
            $stok_awal = $awal_masuk - $awal_keluar;
    
            $masuk  = $stok_masuk_periode->get($id_bahan)->total ?? 0;
            $keluar = $stok_keluar_periode->get($id_bahan)->total ?? 0;
    
            $stok_akhir = $stok_awal + $masuk - $keluar;
    
            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
    
            $data_laporan[] = [
                'id_bahan'   => $id_bahan,
                'nama_bahan' => $bahan->nama_bahan ?? ('ID-' . $id_bahan),
                'satuan'     => $bahan->satuan_bahan ?? '-',
                'stok_awal'  => $stok_awal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stok_akhir,
            ];
        }
    
        return view('maker.laporan.stok.index_laporan_stok', compact(
            'data_laporan',
            'dapur',
            'dari_tanggal',
            'sampai_tanggal'
        ));
    }



    public function index_maker_laporan_stok_harian(Request $request)
    {
        $maker              = Auth::guard('maker')->user();
        $dapur              = $maker->nomor_dapur_maker;
        $tanggal            = $request->pilih_tanggal ?? date('Y-m-d');
        $tanggal_kemarin    = date('Y-m-d', strtotime($tanggal . ' -1 day'));

        // =======================
        // STOK AWAL (s/d kemarin)
        // =======================
        $stok_awal_masuk = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', '<=', $tanggal_kemarin)
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
            
        $stok_awal_keluar = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', '<=', $tanggal_kemarin)
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
            
        // =======================
        // STOK MASUK HARI INI (FIX)
        // =======================
        $stok_masuk_hari_ini = DB::table('stok_masuk')
            ->whereDate('tanggal_masuk', $tanggal)
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // =======================
        // STOK KELUAR HARI INI (FIX)
        // =======================
        $stok_keluar_hari_ini = DB::table('stok_keluar')
            ->whereDate('tanggal_keluar', $tanggal)
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');
        

            
        // pastikan semua collection sudah di-keyBy id_bahan untuk konsistensi
        $stok_awal_masuk = $stok_awal_masuk->keyBy('id_bahan');
        $stok_awal_keluar = $stok_awal_keluar->keyBy('id_bahan');
        $stok_masuk_hari_ini = $stok_masuk_hari_ini->keyBy('id_bahan');
        $stok_keluar_hari_ini = $stok_keluar_hari_ini->keyBy('id_bahan');

        // kumpulkan semua id_bahan yang terlibat (id numerik)
        $semua_bahan = collect()
            ->merge($stok_awal_masuk->keys())
            ->merge($stok_awal_keluar->keys())
            ->merge($stok_masuk_hari_ini->keys())
            ->merge($stok_keluar_hari_ini->keys())
            ->filter(fn($id) => $id > 0)   // ✅ buang ID 0 / null
            ->unique()
            ->values();

        $data_laporan = [];

        foreach ($semua_bahan as $id_bahan) {
            $awal_masuk_obj  = $stok_awal_masuk->get($id_bahan);
            $awal_keluar_obj = $stok_awal_keluar->get($id_bahan);
        
            $awal_masuk_total  = $awal_masuk_obj->total ?? 0;
            $awal_keluar_total = $awal_keluar_obj->total ?? 0;
        
            $stok_awal = $awal_masuk_total - $awal_keluar_total;
        
            $stok_masuk_hari = $stok_masuk_hari_ini->get($id_bahan)->total ?? 0;
            $stok_keluar_hari = $stok_keluar_hari_ini->get($id_bahan)->total ?? 0;
        
            $stok_akhir = $stok_awal + $stok_masuk_hari - $stok_keluar_hari;
        
            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
        
            $data_laporan[] = [
                'id_bahan'    => $id_bahan,
                'nama_bahan'  => $bahan->nama_bahan ?? ('ID-'.$id_bahan),
                'satuan'      => $bahan->satuan_bahan ?? '-',
                'stok_awal'   => $stok_awal,
                'masuk'       => $stok_masuk_hari,
                'keluar'      => $stok_keluar_hari,
                'stok_akhir'  => $stok_akhir,
            ];
        }
    
        return view('maker.laporan.stok_harian.index_laporan_stok_harian', compact(
            'data_laporan',
            'dapur',
            'tanggal'
        ));
    }


    public function index_maker_laporan_stok_bulanan(Request $request)
    {
        $maker  = Auth::guard('maker')->user();
        $dapur  = $maker->nomor_dapur_maker;

        // ============================
        // PROSES BULAN & TAHUN
        // ============================
        $bulan_map = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $nama_bulan = $request->bulan;
        $bulan = $bulan_map[$nama_bulan] ?? date('m');
        $tahun = date('Y');

        $filter_bulan = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // ============================
        // STOK MASUK BULANAN
        // ============================
        $stok_masuk_bulanan = DB::table('stok_masuk')
            ->whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->where('nomor_dapur_stok_masuk', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_masuk) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // ============================
        // STOK KELUAR BULANAN
        // ============================
        $stok_keluar_bulanan = DB::table('stok_keluar')
            ->whereMonth('tanggal_keluar', $bulan)
            ->whereYear('tanggal_keluar', $tahun)
            ->where('nomor_dapur_stok_keluar', $dapur)
            ->select('id_bahan', DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('id_bahan')
            ->get()
            ->keyBy('id_bahan');

        // ============================
        // GABUNG SEMUA ID BAHAN
        // ============================
        $semua_bahan = collect()
            ->merge($stok_masuk_bulanan->keys())
            ->merge($stok_keluar_bulanan->keys())
            ->unique()
            ->values();

        $data_laporan = [];

        foreach ($semua_bahan as $id_bahan) {

            $total_masuk  = $stok_masuk_bulanan->get($id_bahan)->total ?? 0;
            $total_keluar = $stok_keluar_bulanan->get($id_bahan)->total ?? 0;
            $stok_akhir   = $total_masuk - $total_keluar;

            $bahan = DB::table('bahan')->where('id_bahan', $id_bahan)->first();
            $limit = $bahan->batas_limit ?? 0;

            // ============================
            // KETERANGAN
            // ============================
            if ($stok_akhir < 0) {
                $keterangan = 'Minus';
            } elseif ($stok_akhir == 0) {
                $keterangan = 'Habis';
            } elseif ($stok_akhir == $limit) {
                $keterangan = 'Menipis';
            } else {
                $keterangan = 'Aman';
            }

            $data_laporan[] = [
                'id_bahan'     => $id_bahan,
                'nama_bahan'   => $bahan->nama_bahan ?? ('ID-'.$id_bahan),
                'satuan'       => $bahan->satuan_bahan ?? '-',
                'total_masuk'  => $total_masuk,
                'total_keluar' => $total_keluar,
                'stok_akhir'   => $stok_akhir,
                'keterangan'   => $keterangan,
            ];
        }

        return view('maker.laporan.stok_bulanan.index_laporan_stok_bulanan', compact(
            'data_laporan',
            'dapur',
            'filter_bulan'
        ));
    }
}
