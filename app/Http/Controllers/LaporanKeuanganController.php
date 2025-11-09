<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_laporan_keuangan(Request $request)
    {
        $dari_tanggal     = $request->dari_tanggal;
        $sampai_tanggal   = $request->sampai_tanggal;
        $jenis_transaksi  = $request->cari_jenis_transaksi;
    
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;
    
        // 🔹 Query utama laporan keuangan
        $query = DB::table('keuangan')->select('*');
    
        // Filter tanggal
        if (empty($dari_tanggal) && empty($sampai_tanggal)) {
            $query->whereMonth('tanggal_laporan_keuangan', $bulanSekarang)
                  ->whereYear('tanggal_laporan_keuangan', $tahunSekarang);
        } else {
            if (!empty($dari_tanggal)) {
                try {
                    $dari_tanggal = Carbon::parse($dari_tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dari_tanggal = null;
                }
            }
        
            if (!empty($sampai_tanggal)) {
                try {
                    $sampai_tanggal = Carbon::parse($sampai_tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $sampai_tanggal = null;
                }
            }
        
            if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
                $query->whereBetween('tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            } elseif (!empty($dari_tanggal)) {
                $query->whereDate('tanggal_laporan_keuangan', '>=', $dari_tanggal);
            } elseif (!empty($sampai_tanggal)) {
                $query->whereDate('tanggal_laporan_keuangan', '<=', $sampai_tanggal);
            }
        }
    
        // Filter jenis transaksi
        if (!empty($jenis_transaksi)) {
            $query->where('jenis_transaksi', $jenis_transaksi);
        }
    
        // 🔹 Ambil daftar laporan (pagination tetap sama)
        $laporan_keuangan = $query->orderBy('tanggal_laporan_keuangan', 'desc')->paginate(300);
    
        /**
         * 🔹 Perhitungan total pemasukan & pengeluaran
         * Sekarang diambil dari tabel data_koperasi
         * berdasarkan jenis_data_koperasi ('modal_masuk' / 'modal_keluar')
         */
        $baseKoperasi = DB::table('data_koperasi')
            ->join('keuangan', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->select('data_koperasi.*', 'keuangan.tanggal_laporan_keuangan');
    
        // Terapkan filter tanggal yang sama
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $baseKoperasi->whereBetween('keuangan.tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $baseKoperasi->whereDate('keuangan.tanggal_laporan_keuangan', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $baseKoperasi->whereDate('keuangan.tanggal_laporan_keuangan', '<=', $sampai_tanggal);
        } else {
            $baseKoperasi->whereMonth('keuangan.tanggal_laporan_keuangan', $bulanSekarang)
                         ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
        }
    
        // Hitung total pemasukan & pengeluaran langsung dari data_koperasi
        $total_pemasukan = (clone $baseKoperasi)
            ->where('data_koperasi.jenis_data_koperasi', 'modal_masuk')
            ->sum('data_koperasi.harga_data_koperasi');
    
        $total_pengeluaran = (clone $baseKoperasi)
            ->where('data_koperasi.jenis_data_koperasi', 'modal_keluar')
            ->sum('data_koperasi.harga_data_koperasi');
    
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        /**
         * 🔹 Data grafik batang berdasarkan tanggal laporan keuangan
         * Dikelompokkan berdasarkan tanggal, dengan sum dari data_koperasi
         */
        $data = DB::table('keuangan')
            ->join('data_koperasi', 'data_koperasi.id_data_koperasi', '=', 'keuangan.id_data_koperasi')
            ->select(
                'keuangan.tanggal_laporan_keuangan',
                DB::raw('SUM(CASE WHEN data_koperasi.jenis_data_koperasi = "modal_masuk" THEN data_koperasi.harga_data_koperasi ELSE 0 END) AS total_pemasukan'),
                DB::raw('SUM(CASE WHEN data_koperasi.jenis_data_koperasi = "modal_keluar" THEN data_koperasi.harga_data_koperasi ELSE 0 END) AS total_pengeluaran'),
                DB::raw('(SUM(CASE WHEN data_koperasi.jenis_data_koperasi = "modal_masuk" THEN data_koperasi.harga_data_koperasi ELSE 0 END) -
                          SUM(CASE WHEN data_koperasi.jenis_data_koperasi = "modal_keluar" THEN data_koperasi.harga_data_koperasi ELSE 0 END)) AS margin')
            )
            ->when($dari_tanggal, function ($query) use ($dari_tanggal) {
                $query->whereDate('keuangan.tanggal_laporan_keuangan', '>=', $dari_tanggal);
            })
            ->when($sampai_tanggal, function ($query) use ($sampai_tanggal) {
                $query->whereDate('keuangan.tanggal_laporan_keuangan', '<=', $sampai_tanggal);
            })
            ->when(empty($dari_tanggal) && empty($sampai_tanggal), function ($query) use ($bulanSekarang, $tahunSekarang) {
                $query->whereMonth('keuangan.tanggal_laporan_keuangan', $bulanSekarang)
                      ->whereYear('keuangan.tanggal_laporan_keuangan', $tahunSekarang);
            })
            ->groupBy('keuangan.tanggal_laporan_keuangan')
            ->orderBy('keuangan.tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });
        
        return view('owner.laporan.keuangan.index_laporan_keuangan', compact(
            'laporan_keuangan',
            'total_pemasukan',
            'total_pengeluaran',
            'sisa_dana',
            'data',
            'bulanSekarang'
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

    public function cetak_owner_laporan_keuangan(Request $request)
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

        return view('owner.laporan.keuangan.cetak_laporan_keuangan', compact('data','sisa_dana'));
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


























    // BAGIAN ADMIN
    public function index_admin_laporan_keuangan(Request $request)
    {
        $dari_tanggal     = $request->dari_tanggal;
        $sampai_tanggal   = $request->sampai_tanggal;
        $jenis_transaksi  = $request->cari_jenis_transaksi;

        // ✅ Definisikan di awal agar tidak undefined
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $query = DB::table('keuangan')->select('*');

        // 🔹 Jika user tidak memilih tanggal, tampilkan bulan berjalan
        if (empty($dari_tanggal) && empty($sampai_tanggal)) {
            $query->whereMonth('tanggal_laporan_keuangan', $bulanSekarang)
                  ->whereYear('tanggal_laporan_keuangan', $tahunSekarang);
        } else {
            // 🔹 Jika user memilih tanggal, konversi formatnya dengan aman
            if (!empty($dari_tanggal)) {
                try {
                    $dari_tanggal = Carbon::parse($dari_tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dari_tanggal = null;
                }
            }

            if (!empty($sampai_tanggal)) {
                try {
                    $sampai_tanggal = Carbon::parse($sampai_tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    $sampai_tanggal = null;
                }
            }

            // 🔹 Terapkan filter tanggal sesuai input
            if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
                $query->whereBetween('tanggal_laporan_keuangan', [$dari_tanggal, $sampai_tanggal]);
            } elseif (!empty($dari_tanggal)) {
                $query->whereDate('tanggal_laporan_keuangan', '>=', $dari_tanggal);
            } elseif (!empty($sampai_tanggal)) {
                $query->whereDate('tanggal_laporan_keuangan', '<=', $sampai_tanggal);
            }
        }

        // 🔹 Filter jenis transaksi jika ada
        if (!empty($jenis_transaksi)) {
            $query->where('jenis_transaksi', $jenis_transaksi);
        }

        // 🔹 Ambil data laporan keuangan
        $laporan_keuangan = $query->orderBy('tanggal_laporan_keuangan', 'desc')->paginate(300);

        // 🔹 Perhitungan total berdasarkan filter yang sama
        $total_pemasukan = (clone $query)
            ->where('jenis_transaksi', 'Pemasukan')
            ->sum('jumlah_dana');

        $total_pengeluaran = (clone $query)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->sum('jumlah_dana');

        $sisa_dana = $total_pemasukan - $total_pengeluaran;


        // 🔹 Data untuk grafik batang
        $data = DB::table('keuangan')
            ->select(
                'tanggal_laporan_keuangan',
                DB::raw('SUM(CASE WHEN jenis_transaksi = "Pemasukan" THEN jumlah_dana ELSE 0 END) AS total_pemasukan'),
                DB::raw('SUM(CASE WHEN jenis_transaksi = "Pengeluaran" THEN jumlah_dana ELSE 0 END) AS total_pengeluaran'),
                DB::raw('(SUM(CASE WHEN jenis_transaksi = "Pemasukan" THEN jumlah_dana ELSE 0 END) -
                          SUM(CASE WHEN jenis_transaksi = "Pengeluaran" THEN jumlah_dana ELSE 0 END)) AS margin')
            )
            ->when($dari_tanggal, function ($query) use ($dari_tanggal) {
                $query->whereDate('tanggal_laporan_keuangan', '>=', $dari_tanggal);
            })
            ->when($sampai_tanggal, function ($query) use ($sampai_tanggal) {
                $query->whereDate('tanggal_laporan_keuangan', '<=', $sampai_tanggal);
            })
            ->when(empty($dari_tanggal) && empty($sampai_tanggal), function ($query) use ($bulanSekarang, $tahunSekarang) {
                // ✅ Pastikan bulan sekarang tetap difilter kalau tidak ada input tanggal
                $query->whereMonth('tanggal_laporan_keuangan', $bulanSekarang)
                      ->whereYear('tanggal_laporan_keuangan', $tahunSekarang);
            })
            ->groupBy('tanggal_laporan_keuangan')
            ->orderBy('tanggal_laporan_keuangan', 'asc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_laporan_keuangan = Carbon::parse($item->tanggal_laporan_keuangan)
                    ->translatedFormat('d F Y');
                return $item;
            });

        return view('admin.laporan.keuangan.index_laporan_keuangan', compact(
            'laporan_keuangan',
            'sisa_dana',
            'data',
            'bulanSekarang'
        ));
    }

    public function store_admin_laporan_keuangan(Request $request)
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

    public function edit_admin_laporan_keuangan(Request $request)
    {
        $id = $request->id;
        $keuangan = DB::table('keuangan')->get();
        $data = DB::table('keuangan')->where('id_laporan_keuangan', $id)->first();
        return view('admin.laporan.keuangan.edit_laporan_keuangan',compact('keuangan','data'));
    }

    public function update_admin_laporan_keuangan($id, Request $request)
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

    public function cetak_admin_laporan_keuangan(Request $request)
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

        return view('admin.laporan.keuangan.cetak_laporan_keuangan', compact('data','sisa_dana'));
    }

    public function delete_admin_laporan_keuangan($id)
    {
        $delete = DB::table('keuangan')->where('id_laporan_keuangan', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
