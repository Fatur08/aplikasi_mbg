<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LaporanDapurController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_dapur(Request $request)
    {
        // ✅ Ambil filter dari form
        $nomor_dapur = $request->pilih_dapur;
        $tanggal     = $request->pilih_tanggal ?? date('Y-m-d');
        $id_menu     = $request->id_menu_harian;

        // ✅ Query utama
        $query = DB::table('jadwal_menu_harian')
            ->join('menu_harian', 'jadwal_menu_harian.id_menu_harian', '=', 'menu_harian.id_menu_harian')
            ->whereDate('jadwal_menu_harian.tanggal_jadwal_menu_harian', $tanggal);

        // ✅ Filter dapur jika dipilih
        if (!empty($nomor_dapur)) {
            $query->where('jadwal_menu_harian.nomor_dapur_jadwal_menu_harian', $nomor_dapur);
        }

        // ✅ Filter menu jika dipilih
        if (!empty($id_menu)) {
            $query->where('jadwal_menu_harian.id_menu_harian', $id_menu);
        }

        // ✅ Ambil data final
        $jadwal_menu_harian = $query->select(
            'jadwal_menu_harian.id_jadwal_menu_harian',
            'jadwal_menu_harian.nomor_dapur_jadwal_menu_harian',
            'jadwal_menu_harian.id_menu_harian',
            'menu_harian.nama_menu_harian',
            'jadwal_menu_harian.tanggal_jadwal_menu_harian',
            'jadwal_menu_harian.jumlah_porsi_menu_harian',
            'jadwal_menu_harian.status_jadwal_menu_harian',
            'jadwal_menu_harian.kendala_jadwal_menu_harian'
        )
        ->orderBy('jadwal_menu_harian.tanggal_jadwal_menu_harian', 'asc')
        ->get();

        // ✅ Ambil master menu
        $menu_harian = DB::table('menu_harian')
            ->select('id_menu_harian', 'nama_menu_harian')
            ->get();

        // ✅ Ambil master dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        // ✅ Kirim ke view
        return view('owner.laporan.dapur.index_dapur', compact(
            'jadwal_menu_harian',
            'nomor_dapur',
            'menu_harian',
            'dapurList',
            'tanggal'
        ));
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

        return view('owner.laporan.dapur.lihat_bahan_terpakai', compact('bahan_terpakai'));
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

        return view('owner.laporan.dapur.kendala_dapur', compact('kendala'));
    }





















    // BAGIAN MAKER
    public function index_maker_dapur(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $nomor_dapur = $maker->nomor_dapur_maker;
    
        $pilih_instansi  = $request->pilih_instansi;
        $dari_tanggal    = $request->dari_tanggal;
        $sampai_tanggal  = $request->sampai_tanggal;
    
        // ===============================
        // Query utama data distribusi
        // ===============================
        $dataDistribusi = DB::table('distribusi')
            ->when($nomor_dapur, function ($q) use ($nomor_dapur) {
                $q->where('nomor_dapur', $nomor_dapur);
            })
            ->when($pilih_instansi, function ($q) use ($pilih_instansi) {
                $q->where('tujuan_distribusi', $pilih_instansi);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($q) use ($dari_tanggal, $sampai_tanggal) {
                $q->whereBetween('tanggal_distribusi', [$dari_tanggal, $sampai_tanggal]);
            })
            ->orderBy('tanggal_distribusi', 'asc')
            ->get();
    
        // ===============================
        // Total porsi
        // ===============================
        $totalPorsi = $dataDistribusi->sum('jumlah_paket');
    
        // ===============================
        // Dropdown instansi (tanpa duplikat)
        // ===============================
        $distribusi = DB::table('distribusi')
            ->select('tujuan_distribusi')
            ->groupBy('tujuan_distribusi')
            ->orderBy('tujuan_distribusi', 'asc')
            ->get();
    
        return view('maker.laporan.dapur.index_dapur', compact(
            'dataDistribusi',
            'totalPorsi',
            'distribusi',
            'pilih_instansi',
            'dari_tanggal',
            'sampai_tanggal'
        ));
    }




    public function store_maker_pm_dapur(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();
    
        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;

        $data = [
            'nomor_dapur_distribusi'   => $nomor_dapur,
            'kategori_distribusi'   => $request->pilih_sekolah_atau_b3,
            'tujuan_distribusi'   => $request->nama_sekolah_atau_b3,
            'jumlah_paket' => (int) $request->jumlah_pm
        ];

        $simpan = DB::table('distribusi')->insert($data);

        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }






    public function kendala_maker_dapur(Request $request)
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

        return view('maker.laporan.dapur.kendala_dapur', compact('kendala', 'tanggal'));
    }


    public function tambah_maker_operasional_dapur(Request $request)
    {
        $distribusi = DB::table('distribusi')
            ->select('tujuan_distribusi', 'jumlah_paket')
            ->groupBy('tujuan_distribusi', 'jumlah_paket')
            ->orderBy('tujuan_distribusi', 'asc')
            ->get();

        return view('maker.laporan.dapur.tambah_operasional_dapur', compact('distribusi'));
    }

    public function update_maker_operasional_dapur(Request $request)
    {
        DB::table('distribusi')->insert([
            'tanggal_distribusi'   => $request->tanggal_operasional_dapur,
            'menu_makanan'         => $request->menu_operasional_dapur,
            'kendala_distribusi'   => $request->kendala_operasional_dapur,
            'tujuan_distribusi'    => $request->pilih_instansi,
        ]);

        return redirect()->back()->with('success', 'Data operasional dapur berhasil disimpan');
    }


    

































    // BAGIAN KEPALA DAPUR
    public function store_dapur_kepala_dapur(Request $request)
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
