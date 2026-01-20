<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DataKoperasi;
use App\Models\LaporanDistribusi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1; // 1 atau Januari
        $tahunini = date("Y"); // 2024
        $nisn = Auth::guard('murid')
            ->user()
            ->nisn;

        $presensihariini = DB::table('presensi')
            ->where('nisn', $nisn)
            ->where('tgl_presensi',$hariini)
            ->first();

        $historibulanini = DB::table('presensi')
            ->where('nisn',$nisn)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
            ->whereRaw('YEAR(tgl_presensi)="'. $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nisn) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmlterlambat')  
            ->where('nisn',$nisn)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
            ->whereRaw('YEAR(tgl_presensi)="'. $tahunini . '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('murid','presensi.nisn', '=', 'murid.nisn')
            ->where('tgl_presensi',$hariini)
            ->orderBy('jam_in')
            ->get();
        
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('nisn',$nisn)
            ->whereRaw('MONTH(tgl_izin)="'.$bulanini.'"')
            ->whereRaw('YEAR(tgl_izin)="'. $tahunini . '"')
            ->where('status_approved', 1)
            ->first();
        return view('dashboard.dashboard', compact('presensihariini','historibulanini','namabulan','bulanini','tahunini','rekappresensi','leaderboard','rekapizin'));
    }

    public function dashboardowner(Request $request)
    {
        $pilih_dapur = $request->input('pilih_dapur');

        // Ambil bulan & tahun sekarang
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;
            
        // Query data berdasarkan dapur yang dipilih
        $dataDapur = \App\Models\Dapur::when($pilih_dapur, function ($query, $pilihDapur) {
            $query->where('nomor_dapur', $pilihDapur);
        })->get();
        
        // Data pendukung
        $admins       = \App\Models\Admin::all();
        $kepalaDapur  = \App\Models\KepalaDapur::all();
        $distributors = \App\Models\Distributor::all();
        
        // ✅ Data distribusi (filter dapur + bulan berjalan)
        $dataDistribusi = LaporanDistribusi::when($pilih_dapur, function ($query, $pilih_dapur) {
                $query->where('nomor_dapur_distribusi', $pilih_dapur);
            })
            ->whereMonth('tanggal_distribusi', $bulanSekarang)
            ->whereYear('tanggal_distribusi', $tahunSekarang)
            ->orderBy('status_distribusi', 'asc')
            ->orderBy('tanggal_distribusi', 'desc')
            ->paginate(300);

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        // Cek apakah user sudah menekan tombol "Cari"
        $sudahCari = $request->has('pilih_dapur') && $pilih_dapur !== null && $pilih_dapur !== '';
        
            // Deteksi apakah data dapur kosong
        $dataKosong = $sudahCari && $dataDapur->isEmpty();
        
        // Kirim ke view
        return view('dashboard.dashboardowner', compact('dapurList', 'dataDapur', 'admins', 'kepalaDapur', 'distributors', 'dataKosong', 'sudahCari', 'dataDistribusi'));
    }

    public function dashboardadmin(Request $request)
    {
        $admin               = DB::table('admin')->where('id_admin', auth()->id())->first();
        $nomor_dapur         = $admin->nomor_dapur_admin ?? null;
        $kecamatan           = $request->cari_kecamatan;
        $bulan               = $request->cari_bulan;
    
        // 🔹 Mulai query dasar
        $query = LaporanDistribusi::query();

        // 🔹 Filter berdasarkan kecamatan
        if (!empty($kecamatan)) {
            $query->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%');
        }

        // 🔹 Filter berdasarkan dapur
        if (!empty($nomor_dapur)) {
            $query->where('nomor_dapur_distribusi', $nomor_dapur);
        }

        // ✅ 🔹 Filter berdasarkan bulan saja (TANPA tahun)
        if (!empty($bulan)) {
            $query->whereMonth('tanggal_distribusi', $bulan);
        }

        // 🔹 Eksekusi query
        $distribusi = $query->orderBy('tanggal_distribusi', 'desc')->paginate(300);
        $dataKosong = $distribusi->isEmpty();// 🔹 Cek apakah hasilnya kosong
        $dataKosong = $distribusi->isEmpty();

        // 🔹 Ambil nama distributor (jika ada kecamatan atau dapur dipilih)
        $nama_distributor = '';
        if (!$dataKosong && (!empty($kecamatan) || !empty($nomor_dapur))) {
            $nama_distributor = LaporanDistribusi::query()
                ->when($kecamatan, fn($q) => $q->where('kecamatan_sekolah', 'like', '%' . $kecamatan . '%'))
                ->when($nomor_dapur, fn($q) => $q->where('nomor_dapur_distribusi', $nomor_dapur))
                ->value('nama_distributor');
        }

        // 🔹 Deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($kecamatan) || !empty($nomor_dapur);

        
        // 🔹 Ambil nama dapur berdasarkan nomor dapur
            $nama_dapur = '';
            if (!empty($nomor_dapur)) {
                $nama_dapur = DB::table('dapur')
                    ->where('nomor_dapur', $nomor_dapur)
                    ->value('nama_dapur');
            }

        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        
        
        $data_kecamatan = [];
        if ($nomor_dapur) {
            $data_kecamatan = DB::table('dapur')
                ->where('nomor_dapur', $nomor_dapur)
                ->pluck('dapur_kecamatan')
                ->unique()
                ->values();
        }
        
    
        return view('dashboard.dashboardadmin', compact(
            'distribusi',
            'nama_distributor',
            'kecamatan',
            'dataKosong',
            'sudahCari',
            'dapurList',
            'data_kecamatan',
            'nomor_dapur'
        ));
    }

    public function dashboardkepaladapur(Request $request)
    {
        $searchKecamatan = $request->input('cari_kecamatan_harian_dapur');
        $searchSekolah   = $request->input('cari_sekolah_harian_dapur');
        
        $laporanQuery = DB::table('distribusi')
            ->leftJoin(
                DB::raw('(SELECT tanggal_keluar_stok,
                                 MAX(nama_kepala_dapur) AS nama_kepala_dapur,
                                 SUM(jumlah_stok_keluar) as jumlah_stok_keluar, 
                                 MAX(sisa_stok) as sisa_stok,
                                 GROUP_CONCAT(keterangan_stok SEPARATOR "; ") as keterangan_stok
                          FROM stok 
                          GROUP BY tanggal_keluar_stok) as stok'),
                'distribusi.tanggal_distribusi',
                '=',
                'stok.tanggal_keluar_stok'
            )
            ->select(
                'distribusi.id_distribusi',
                'distribusi.tanggal_distribusi',
                'distribusi.menu_makanan',
                'distribusi.jumlah_paket',
                'stok.nama_kepala_dapur',
                'stok.jumlah_stok_keluar',
                'stok.sisa_stok',
                'stok.keterangan_stok',
                'distribusi.kendala_distribusi',
                'distribusi.tujuan_distribusi',
                'distribusi.status_distribusi',
                'distribusi.kecamatan_sekolah',
                'distribusi.nama_distributor'
            );
        
        if (!empty($searchKecamatan)) {
            $laporanQuery->where('distribusi.kecamatan_sekolah', 'like', '%'.$searchKecamatan.'%');
        }
    
        if (!empty($searchSekolah)) {
            $laporanQuery->where('distribusi.tujuan_distribusi', $searchSekolah);
        }

        $laporan = $laporanQuery
            ->orderBy('distribusi.tanggal_distribusi', 'asc')
            ->get();

        $sekolahList = DB::table('distribusi')
            ->select('tujuan_distribusi')
            ->distinct()
            ->orderBy('tujuan_distribusi', 'asc')
            ->pluck('tujuan_distribusi');
        
        $dataKosong = $laporan->isEmpty();

        $sudahCari = !empty($searchKecamatan) ||
                     !empty($searchSekolah);

        $kepala_dapur = DB::table('kepala_dapur')->where('id', auth()->id())->first();
        $nomor_dapur = $kepala_dapur->nomor_dapur_kepala_dapur ?? null;
        $data_kecamatan = [];
        if ($nomor_dapur) {
            $data_kecamatan = DB::table('dapur')
                ->where('nomor_dapur', $nomor_dapur)
                ->pluck('dapur_kecamatan')
                ->unique()
                ->values();
        }

        $menu_harian = DB::table('menu_harian')
            ->select('id_menu_harian', 'nama_menu_harian')
            ->get();

        return view('dashboard.dashboardkepaladapur', compact(
            'laporan',
            'sekolahList',
            'searchKecamatan',
            'searchSekolah',
            'dataKosong',
            'sudahCari',
            'data_kecamatan',
            'menu_harian'
        ));
    }

    public function dashboarddistributor(Request $request)
    {
        $distributor = Auth::guard('distributor')->user();
        $nomor_dapur_distributor = $distributor->nomor_dapur_distributor;

        $distribusi = DB::table('distribusi')
        ->where(function ($query) use ($distributor) {
            $query->where('nomor_dapur_distribusi', $distributor->nomor_dapur_distributor);
        })
        ->whereDate('tanggal_distribusi', now()->toDateString())
        ->orderByRaw("FIELD(status_distribusi, 0, 1, 2)")
        ->get();
        
        $today = Carbon::today();
        
        $totalDistribusi = DB::table('distribusi')
            ->where('nomor_dapur_distribusi', $nomor_dapur_distributor)
            ->whereDate('tanggal_distribusi', $today)
            ->count();
        
        $totalTerkirim = DB::table('distribusi')
            ->where('nomor_dapur_distribusi', $nomor_dapur_distributor)
            ->whereDate('tanggal_distribusi', $today)
            ->where('status_distribusi', 1)
            ->count();
        
        $totalBelumTerkirim = DB::table('distribusi')
            ->where('nomor_dapur_distribusi', $nomor_dapur_distributor)
            ->whereDate('tanggal_distribusi', $today)
            ->whereIn('status_distribusi', [0, 2])
            ->count();
        
        return view('dashboard.dashboarddistributor', compact(
            'distribusi',
            'totalDistribusi',
            'totalTerkirim',
            'totalBelumTerkirim'
        ));
    }
}
