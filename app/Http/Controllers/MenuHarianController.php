<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MenuHarianController extends Controller
{
    public function index_menu_harian_kepala_dapur(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

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

        return view('kepala_dapur.menu_harian.index_menu_harian_kepala_dapur', compact('jadwal_menu_harian', 'nomor_dapur', 'menu_harian'));
    }

    public function store_menu_harian(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();

        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;
    
        if (!empty($request->nama_menu_harian)) {
            $id_menu_harian = DB::table('menu_harian')->insertGetId([
                'nama_menu_harian' => $request->nama_menu_harian
            ]);
        } else {
            $id_menu_harian = $request->id_menu_harian;
        }

        // Tentukan status berdasarkan tanggal hari ini
        $tanggal_hari_ini = date('Y-m-d');
        $status = ($request->tanggal_jadwal_menu_harian == $tanggal_hari_ini) ? 1 : 0;

        $jadwal_menu_harian = [
            'id_menu_harian' => $id_menu_harian,
            'tanggal_jadwal_menu_harian'   => $request->tanggal_jadwal_menu_harian,
            'jumlah_porsi_menu_harian' => $request->jumlah_porsi_menu_harian,
            'nomor_dapur_jadwal_menu_harian' => $nomor_dapur_kepala_dapur,
            'status_jadwal_menu_harian' => $status
        ];

        $simpan_stok = DB::table('jadwal_menu_harian')->insert($jadwal_menu_harian);

        if ($simpan_stok) {
            return Redirect::back()->with(['success' => 'Data Menu Harian Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function tambah_bahan_terpakai(Request $request)
    {
        $id = $request->id;
        $kepala_dapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepala_dapur->nomor_dapur_kepala_dapur;
        $jadwal_menu_harian = DB::table('jadwal_menu_harian')->get();
        $data = DB::table('jadwal_menu_harian')->where('id_jadwal_menu_harian', $id)->first();
        // --- Dropdown filter bahan
        $bahan = DB::table('bahan')
            ->select('id_bahan', 'nama_bahan')
            ->orderBy('nama_bahan', 'asc')
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
        return view('kepala_dapur.menu_harian.tambah_bahan_terpakai',compact('jadwal_menu_harian','data','bahan','sisa_perbahan'));
    }

    public function store_tambah_bahan_terpakai(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        $id_jadwal_menu_harian = $request->id;

        // Ambil data jadwal berdasarkan id_jadwal_menu_harian
        $jadwal = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->first();

        if (!$jadwal) {
            return Redirect::back()->with(['warning' => 'Data jadwal tidak ditemukan']);
        }

        // Buat array bahan berdasarkan form (maks 3 bahan)
        $inputBahan = [
            ['id_bahan' => $request->id_menu_harian_1, 'jumlah' => $request->jumlah_bahan_menu_1],
            ['id_bahan' => $request->id_menu_harian_2, 'jumlah' => $request->jumlah_bahan_menu_2],
            ['id_bahan' => $request->id_menu_harian_3, 'jumlah' => $request->jumlah_bahan_menu_3],
        ];

        $dataInsert = [];

        foreach ($inputBahan as $bahanInput) {
            if (!empty($bahanInput['id_bahan'])) {
                $bahan = DB::table('bahan')->where('id_bahan', $bahanInput['id_bahan'])->first();

                if ($bahan) {
                    $dataInsert[] = [
                        'id_menu_harian'         => $jadwal->id_menu_harian,
                        'id_jadwal_menu_harian'  => $id_jadwal_menu_harian,
                        'id_bahan'               => $bahan->id_bahan,
                        'tanggal_bahan_menu'     => $jadwal->tanggal_jadwal_menu_harian,
                        'nama_bahan_menu'        => $bahan->nama_bahan,
                        'jumlah_bahan_menu'      => $bahanInput['jumlah'] ?? 0,
                        'satuan_bahan_menu'      => $bahan->satuan_bahan ?? '-',
                        'nomor_dapur_bahan_menu' => $nomor_dapur_kepala_dapur,
                    ];
                }
            }
        }

        if (empty($dataInsert)) {
            return Redirect::back()->with(['warning' => 'Minimal isi satu bahan terlebih dahulu']);
        }

        $simpan = DB::table('bahan_menu')->insert($dataInsert);

        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data bahan terpakai berhasil disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
        }
    }

    public function lihat_bahan_terpakai(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_jadwal_menu_harian = $request->id;

        // Ambil data bahan dari tabel bahan_menu
        $bahan_terpakai = DB::table('bahan_menu')
            ->join('bahan', 'bahan.id_bahan', '=', 'bahan_menu.id_bahan')
            ->where('bahan_menu.id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->where('bahan_menu.nomor_dapur_bahan_menu', $nomor_dapur_kepala_dapur)
            ->select(
                'bahan_menu.*',
                'bahan.nama_bahan',
                'bahan_menu.jumlah_bahan_menu',
                'bahan_menu.satuan_bahan_menu'
            )
            ->get();

        return view('kepala_dapur.menu_harian.lihat_bahan_terpakai', compact('bahan_terpakai'));
    }

    public function tambah_kendala(Request $request)
    {
        $id = $request->id;
        $kepala_dapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepala_dapur->nomor_dapur_kepala_dapur;
        $jadwal_menu_harian = DB::table('jadwal_menu_harian')->get();
        $data = DB::table('jadwal_menu_harian')->where('id_jadwal_menu_harian', $id)->first();
        return view('kepala_dapur.menu_harian.tambah_kendala',compact('jadwal_menu_harian','data'));
    }

    public function store_tambah_kendala(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        $id_jadwal_menu_harian = $request->id;
        $kendala = $request->kendala_jadwal_menu_harian;

        // Ambil data jadwal berdasarkan id_jadwal_menu_harian
        $jadwal = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->first();

        if (!$jadwal) {
            return Redirect::back()->with(['warning' => 'Data jadwal tidak ditemukan']);
        }

        // Update kolom kendala
        $update = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->update([
                'kendala_jadwal_menu_harian' => $kendala,
            ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Kendala berhasil disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Tidak ada perubahan yang disimpan']);
        }
    }

    public function lihat_kendala(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;

        // Ambil ID jadwal menu harian dari parameter URL atau request
        $id_jadwal_menu_harian = $request->id;

        // Ambil data kendala berdasarkan id_jadwal_menu_harian dan dapur yang sedang login
        $kendala = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->where('nomor_dapur_jadwal_menu_harian', $nomor_dapur_kepala_dapur)
            ->select('kendala_jadwal_menu_harian')
            ->first();

    if (!$kendala) {
        return Redirect::back()->with(['warning' => 'Data kendala tidak ditemukan atau tidak sesuai dengan dapur Anda']);
    }

        return view('kepala_dapur.menu_harian.lihat_kendala', compact('kendala'));
    }

    public function delete_jadwal_menu_harian(Request $request)
    {
        $id_jadwal_menu_harian = $request->id;
    
        // Hapus dulu data bahan_menu yang berhubungan dengan jadwal ini
        DB::table('bahan_menu')->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)->delete();
    
        // Lalu hapus data jadwal_menu_harian
        $delete_jadwal_menu_harian = DB::table('jadwal_menu_harian')
            ->where('id_jadwal_menu_harian', $id_jadwal_menu_harian)
            ->delete();
    
        if ($delete_jadwal_menu_harian) {
            return Redirect::back()->with(['success' => 'Data jadwal dan bahan berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data jadwal tidak ditemukan']);
        }
    }
}
