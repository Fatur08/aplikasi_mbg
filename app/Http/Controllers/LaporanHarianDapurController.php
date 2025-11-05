<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LaporanHarianDapurController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_harian_dapur(Request $request)
    {
        $nomor_dapur = 1;

        $bulan = $request->input('bulan') ?? date('m');
        $id_menu_harian = $request->input('id_menu_harian');

        // Ambil data berdasarkan dapur login
        $query = DB::table('jadwal_menu_harian')
            ->join('menu_harian', 'jadwal_menu_harian.id_menu_harian', '=', 'menu_harian.id_menu_harian')
            ->where('jadwal_menu_harian.nomor_dapur_jadwal_menu_harian', $nomor_dapur)
            ->select(
                'jadwal_menu_harian.id_jadwal_menu_harian',
                'jadwal_menu_harian.nomor_dapur_jadwal_menu_harian',
                'jadwal_menu_harian.id_menu_harian',
                'menu_harian.nama_menu_harian',
                'jadwal_menu_harian.tanggal_jadwal_menu_harian',
                'jadwal_menu_harian.jumlah_porsi_menu_harian',
                'jadwal_menu_harian.status_jadwal_menu_harian'
            );
        
        // Filter berdasarkan bulan (jika dipilih)
        $query->whereMonth('jadwal_menu_harian.tanggal_jadwal_menu_harian', $bulan);

        // Filter berdasarkan id_menu_harian (jika dipilih)
        if (!empty($id_menu_harian)) {
            $query->where('jadwal_menu_harian.id_menu_harian', $id_menu_harian);
        }

        // Ambil hasil dan kelompokkan berdasarkan id_menu_harian
        $jadwal_menu_harian = $query
            ->orderBy('jadwal_menu_harian.tanggal_jadwal_menu_harian', 'desc')
            ->get()
            ->groupBy('id_menu_harian');
        
        
        $menu_harian = DB::table('menu_harian')
            ->select('id_menu_harian', 'nama_menu_harian')
            ->get();

        return view('owner.laporan.harian_dapur.index_harian_dapur', compact('jadwal_menu_harian', 'nomor_dapur', 'menu_harian'));
    }


    public function lihat_bahan_terpakai(Request $request)
    {
        $nomor_dapur = 1;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_jadwal_menu_harian = $request->id;

        // Ambil data bahan dari tabel bahan_menu
        $bahan_terpakai = DB::table('bahan_menu')
            ->join('bahan', 'bahan.id_bahan', '=', 'bahan_menu.id_bahan')
            ->where('bahan_menu.id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->where('bahan_menu.nomor_dapur_bahan_menu', $nomor_dapur)
            ->select(
                'bahan_menu.*',
                'bahan.nama_bahan',
                'bahan_menu.jumlah_bahan_menu',
                'bahan_menu.satuan_bahan_menu'
            )
            ->get();

        return view('owner.laporan.harian_dapur.lihat_bahan_terpakai', compact('bahan_terpakai'));
    }


    public function lihat_kendala(Request $request)
    {
        $nomor_dapur = 1;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_jadwal_menu_harian = $request->id;

        // Ambil data kendala berdasarkan id_jadwal_menu_harian dan dapur yang sedang login
        $kendala = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->where('nomor_dapur_jadwal_menu_harian', $nomor_dapur)
            ->select('kendala_jadwal_menu_harian')
            ->first();

    if (!$kendala) {
        return Redirect::back()->with(['warning' => 'Data kendala tidak ditemukan atau tidak sesuai dengan dapur Anda']);
    }

        return view('owner.laporan.harian_dapur.kendala_harian_dapur', compact('kendala'));
    }





















    // BAGIAN ADMIN
    public function index_admin_harian_dapur(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur = $admin->nomor_dapur_admin;

        $bulan = $request->input('bulan') ?? date('m');
        $id_menu_harian = $request->input('id_menu_harian');

        // Ambil data berdasarkan dapur login
        $query = DB::table('jadwal_menu_harian')
            ->join('menu_harian', 'jadwal_menu_harian.id_menu_harian', '=', 'menu_harian.id_menu_harian')
            ->where('jadwal_menu_harian.nomor_dapur_jadwal_menu_harian', $nomor_dapur)
            ->select(
                'jadwal_menu_harian.id_jadwal_menu_harian',
                'jadwal_menu_harian.nomor_dapur_jadwal_menu_harian',
                'jadwal_menu_harian.id_menu_harian',
                'menu_harian.nama_menu_harian',
                'jadwal_menu_harian.tanggal_jadwal_menu_harian',
                'jadwal_menu_harian.jumlah_porsi_menu_harian',
                'jadwal_menu_harian.status_jadwal_menu_harian'
            );
        
        // Filter berdasarkan bulan (jika dipilih)
        $query->whereMonth('jadwal_menu_harian.tanggal_jadwal_menu_harian', $bulan);

        // Filter berdasarkan id_menu_harian (jika dipilih)
        if (!empty($id_menu_harian)) {
            $query->where('jadwal_menu_harian.id_menu_harian', $id_menu_harian);
        }

        // Ambil hasil dan kelompokkan berdasarkan id_menu_harian
        $jadwal_menu_harian = $query
            ->orderBy('jadwal_menu_harian.tanggal_jadwal_menu_harian', 'desc')
            ->get()
            ->groupBy('id_menu_harian');
        
        
        $menu_harian = DB::table('menu_harian')
            ->select('id_menu_harian', 'nama_menu_harian')
            ->get();

        return view('admin.laporan.harian_dapur.index_harian_dapur', compact('jadwal_menu_harian', 'nomor_dapur', 'menu_harian'));
    }


    public function kendala_admin_harian_dapur(Request $request)
    {
        $tanggal = $request->tanggal;

        $kendala = DB::table('distribusi')
            ->leftJoin(
                DB::raw('(SELECT tanggal_keluar_stok, 
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
                'stok.keterangan_stok',
                'distribusi.kendala_distribusi'
            )
            ->where('distribusi.tanggal_distribusi', $tanggal)
            ->first();

        return view('admin.laporan.harian_dapur.kendala_harian_dapur', compact('kendala', 'tanggal'));
    }

    public function store_admin_harian_dapur(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();

        $id = $kepalaDapur->id;

        $distributor = DB::table('distributor')
                        ->where('id_distributor', $id)
                        ->first();

        if (!$distributor) {
            return Redirect::back()->with(['warning' => 'Distributor tidak ditemukan untuk dapur ini']);
        }

        $data = [
            'nama_distributor' => $distributor->nama_distributor,
            'kecamatan_sekolah' => $request->kecamatan_sekolah,
            'tujuan_distribusi'   => $request->tujuan_distribusi,
            'tanggal_distribusi' => $request->tanggal_distribusi,
            'menu_makanan' => $request->menu_makanan,
            'jumlah_paket' => (int) $request->jumlah_paket,
            'status_distribusi' => 0
        ];

        $simpan = DB::table('distribusi')->insert($data);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

































    // BAGIAN KEPALA DAPUR
    public function store_harian_dapur_kepala_dapur(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        $distributor = DB::table('distributor')
                        ->where('nomor_dapur_distributor', $nomor_dapur)
                        ->first();

        if (!$distributor) {
            return Redirect::back()->with(['warning' => 'Distributor tidak ditemukan untuk dapur ini']);
        }

        // 🔹 Tentukan tujuan distribusi:
        // Jika ada input sekolah_tujuan, maka pakai itu
        // Jika tidak ada, pakai yang dari select tujuan_distribusi
        $tujuan_distribusi = !empty($request->sekolah_tujuan)
            ? $request->sekolah_tujuan
            : $request->tujuan_distribusi;

        $data = [
            'nomor_dapur_distribusi'      => $nomor_dapur,
            'nama_distributor'            => $distributor->nama_distributor,
            'kecamatan_sekolah'           => $request->kecamatan_sekolah,
            'tujuan_distribusi'           => $tujuan_distribusi,
            'tanggal_distribusi'          => $request->tanggal_distribusi,
            'menu_makanan'                => $request->nama_menu_harian,
            'jumlah_paket'                => (int) $request->jumlah_paket,
            'status_distribusi'           => 0
        ];

        $simpan = DB::table('distribusi')->insert($data);

        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function delete_laporan_distribusi_kepala_dapur($id_distribusi)
    {
        $delete_laporan_distribusi_kepala_dapur = DB::table('distribusi')
            ->where('id_distribusi', $id_distribusi)
            ->delete();
    
        if ($delete_laporan_distribusi_kepala_dapur) {
            return Redirect::back()->with(['success' => 'Data berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data tidak ditemukan']);
        }
    }
}
