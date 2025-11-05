<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StokKeluarController extends Controller
{
    public function index_stok_keluar_kepala_dapur(Request $request)
    {
        $kepala_dapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur = $kepala_dapur->nomor_dapur_kepala_dapur;
    
        $filter_bulan = $request->input('bulan');
        $filter_bahan = $request->input('id_bahan');
        $namaBahan = null;

        if ($filter_bahan) {
            $namaBahan = DB::table('bahan')
                ->where('id_bahan', $filter_bahan)
                ->value('nama_bahan'); // ✅ ambil value langsung, bukan object
        }

    
        // --- Ambil data stok keluar dan hitung sisa per baris
        $stok = DB::table('stok_keluar as sk')
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
            $stok->whereRaw("MONTH(sk.tanggal_keluar) = ?", [$filter_bulan]);
        }
    
        if (!empty($filter_bahan)) {
            $stok->where('sk.id_bahan', $filter_bahan);
        }
    
        $stok = $stok->orderBy('sk.tanggal_keluar', 'asc')
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

        // --- Dropdown filter bahan
        $bahan = DB::table('bahan')
            ->select('id_bahan', 'nama_bahan')
            ->orderBy('nama_bahan', 'asc')
            ->get();
        

            // --- Nama bahan yang difilter
        $nama_bahan_filter = null;
        if (!empty($filter_bahan)) {
            $nama_bahan_filter = DB::table('bahan')->where('id_bahan', $filter_bahan)->value('nama_bahan');
        }

    
        $dataKosong = $stok->isEmpty();
        $sudahCari = !empty($filter_bahan) || !empty($filter_bulan);
    
        return view('kepala_dapur.stok_keluar.index_stok_keluar_kepala_dapur', compact(
            'stok',
            'kepala_dapur',
            'bahan',
            'dataKosong',
            'sudahCari',
            'filter_bulan',
            'filter_bahan',
            'nama_bahan_filter',
            'sisa_perbahan',
            'namaBahan'
        ));
    }

    public function store_stok_keluar(Request $request)
    {
        $kepalaDapur = Auth::guard('kepala_dapur')->user();
        $nomor_dapur_kepala_dapur = $kepalaDapur->nomor_dapur_kepala_dapur;
        $tanggal_keluar          = $request->tanggal_keluar;
        $id_bahan                = $request->id_bahan;
        $jumlah_keluar_total     = $request->jumlah_keluar;
        $tujuan_stok_keluar      = $request->tujuan_stok_keluar;
        $keterangan_stok_keluar  = $request->keterangan_stok_keluar;

        // Ambil semua stok masuk bahan ini (urutan lama dulu) di dapur ini
        $stokMasukList = DB::table('stok_masuk')
            ->where('id_bahan', $id_bahan)
            ->where('nomor_dapur_stok_masuk', $nomor_dapur_kepala_dapur)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        $sisa_keluar = $jumlah_keluar_total; // jumlah yang masih harus dikurangi

        DB::beginTransaction(); // biar aman kalau gagal separuh

        try {
            foreach ($stokMasukList as $stokMasuk) {
                // Hitung total keluar dari stok masuk ini
                $total_keluar = DB::table('stok_keluar')
                    ->where('id_stok_masuk', $stokMasuk->id_stok_masuk)
                    ->sum('jumlah_keluar');

                $sisa_stok = $stokMasuk->jumlah_masuk - $total_keluar;

                if ($sisa_stok <= 0) {
                    continue; // stok masuk ini sudah habis, lanjut ke berikutnya
                }

                if ($sisa_keluar <= $sisa_stok) {
                    // cukup pakai sebagian dari stok ini
                    DB::table('stok_keluar')->insert([
                        'id_bahan' => $id_bahan,
                        'id_stok_masuk' => $stokMasuk->id_stok_masuk,
                        'nomor_dapur_stok_keluar' => $nomor_dapur_kepala_dapur,
                        'tanggal_keluar' => $tanggal_keluar,
                        'jumlah_keluar' => $sisa_keluar,
                        'tujuan_stok_keluar' => $tujuan_stok_keluar,
                        'keterangan_stok_keluar' => $keterangan_stok_keluar,
                    ]);

                    $sisa_keluar = 0; // sudah terpenuhi
                    break; // selesai
                } else {
                    // pakai semua stok dari stok masuk ini
                    DB::table('stok_keluar')->insert([
                        'id_bahan' => $id_bahan,
                        'id_stok_masuk' => $stokMasuk->id_stok_masuk,
                        'nomor_dapur_stok_keluar' => $nomor_dapur_kepala_dapur,
                        'tanggal_keluar' => $tanggal_keluar,
                        'jumlah_keluar' => $sisa_stok,
                        'tujuan_stok_keluar' => $tujuan_stok_keluar,
                        'keterangan_stok_keluar' => $keterangan_stok_keluar,
                    ]);

                    $sisa_keluar -= $sisa_stok; // masih ada sisa yang harus dikeluarkan
                }
            }

            DB::commit();

            if ($sisa_keluar > 0) {
                // Artinya stok di gudang tidak cukup untuk jumlah keluar yang diminta
                return Redirect::back()->with(['warning' => 'Stok bahan tidak mencukupi!']);
            }

            return Redirect::back()->with(['success' => 'Data Stok Keluar Berhasil Disimpan (FIFO).']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit_stok_keluar(Request $request)
    {
        $id = $request->id;
        $stok = DB::table('stok')->get();
        $data = DB::table('stok')->where('id_stok', $id)->first();
        return view('kepala_dapur.stok_keluar.edit_stok_keluar_kepala_dapur',compact('stok','data'));
    }

    public function update_stok_keluar($id, Request $request)
    {
        try {
            $data = [
                'tanggal_keluar_stok'   => $request->tanggal_keluar_stok,
                'nama_stok' => $request->nama_stok,
                'jumlah_stok_keluar' => $request->jumlah_stok_keluar,
                'satuan_stok' => $request->satuan_stok,
                'sumber_keluar_stok' => $request->sumber_keluar_stok,
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

    public function delete_stok_keluar($id)
    {
        // Ambil data stok dulu untuk mengetahui id_bahan-nya
        $stok = DB::table('stok_keluar')->where('id_stok_keluar', $id)->first();
    
        if ($stok) {
            // Hapus stok terlebih dahulu
            DB::table('stok_keluar')->where('id_stok_keluar', $id)->delete();
        
            return Redirect::back()->with(['success' => 'Data stok dan bahan berhasil dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data stok tidak ditemukan']);
        }
    }
}