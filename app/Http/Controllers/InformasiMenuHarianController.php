<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InformasiMenuHarianController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_menu_harian(Request $request)
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
                'distribusi.kecamatan_sekolah'
            )
            ->where('distribusi.status_distribusi', 1);
        
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
            ->where('status_distribusi', 1)
            ->select('tujuan_distribusi')
            ->distinct()
            ->orderBy('tujuan_distribusi', 'asc')
            ->pluck('tujuan_distribusi');
        
        $dataKosong = $laporan->isEmpty();

        $sudahCari = !empty($searchKecamatan) ||
                     !empty($searchSekolah);

        return view('owner.informasi.menu_harian.index_menu_harian', compact(
            'laporan',
            'sekolahList',
            'searchKecamatan',
            'searchSekolah',
            'dataKosong',
            'sudahCari'
        ));
    }


    // BAGIAN ADMIN
    public function index_admin_menu_harian(Request $request)
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
                'distribusi.kecamatan_sekolah'
            )
            ->where('distribusi.status_distribusi', 1);
        
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
            ->where('status_distribusi', 1)
            ->select('tujuan_distribusi')
            ->distinct()
            ->orderBy('tujuan_distribusi', 'asc')
            ->pluck('tujuan_distribusi');
        
        $dataKosong = $laporan->isEmpty();

        $sudahCari = !empty($searchKecamatan) ||
                     !empty($searchSekolah);

        return view('admin.informasi.menu_harian.index_menu_harian', compact(
            'laporan',
            'sekolahList',
            'searchKecamatan',
            'searchSekolah',
            'dataKosong',
            'sudahCari'
        ));
    }
}
