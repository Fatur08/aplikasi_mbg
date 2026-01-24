<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\DataKoperasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DataKoperasiController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_data_koperasi(Request $request)
    {
        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;
        $pilih_dapur    = $request->pilih_dapur;

        $query = DataKoperasi::query()
            ->leftJoin(
                DB::raw('(SELECT nomor_dapur, MAX(nama_dapur) AS nama_dapur FROM dapur GROUP BY nomor_dapur) AS dapur'),
                'data_koperasi.nomor_dapur_data_koperasi',
                '=',
                'dapur.nomor_dapur'
            )
            ->select('data_koperasi.*', 'dapur.nama_dapur');

        /* ================= FILTER DAPUR ================= */
        if (!empty($pilih_dapur)) {
            $query->where('data_koperasi.nomor_dapur_data_koperasi', $pilih_dapur);
        }

        /* ================= FILTER RANGE TANGGAL ================= */
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_data_koperasi', [
                $dari_tanggal,
                $sampai_tanggal
            ]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $query->orderBy('tanggal_data_koperasi', 'asc');

        $data_koperasi = $query->paginate(1000);

        /* ================= FLAG STATUS ================= */
        $dataKosong = $data_koperasi->isEmpty();

        $sudahCari =
            !empty($dari_tanggal) ||
            !empty($sampai_tanggal) ||
            !empty($pilih_dapur);

        /* ================= GROUPING ================= */
        $grouped = $data_koperasi->getCollection()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_data_koperasi)
                ->translatedFormat('d F Y');
        });

        /* ================= HITUNG TOTAL ================= */
        foreach ($data_koperasi as $item) {

            if ($item->jenis_data_koperasi !== 'modal_keluar') {
                $item->total_harga_supplier = $item->harga_data_koperasi;
                continue;
            }

            // MODAL KELUAR - SUPPLIER
            if (!empty($item->id_informasi_supplier)) {
                $item->total_harga_supplier = DB::table('barang_supplier')
                    ->where('id_informasi_supplier', $item->id_informasi_supplier)
                    ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_supplier');
            }
            // MODAL KELUAR - NON SUPPLIER
            else {
                $item->total_harga_supplier = DB::table('barang_modal_keluar')
                    ->where('id_data_koperasi', $item->id_data_koperasi)
                    ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_modal_keluar');
            }
        }

        /* ================= LIST DAPUR ================= */
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();

        return view('owner.data_koperasi.index_data_koperasi', compact(
            'data_koperasi',
            'dataKosong',
            'sudahCari',
            'grouped',
            'dapurList'
        ));
    }

    public function cetak_owner_data_koperasi(Request $request)
    {
        $pilih_dapur    = $request->dapur;
        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;

        $query = DataKoperasi::query()
            ->leftJoin('dapur', 'data_koperasi.nomor_dapur_data_koperasi', '=', 'dapur.nomor_dapur')
            ->select('data_koperasi.*', 'dapur.nama_dapur');

        /* ================= FILTER DAPUR ================= */
        if (!empty($pilih_dapur)) {
            $query->where('data_koperasi.nomor_dapur_data_koperasi', $pilih_dapur);
        }

        /* ================= KONVERSI TANGGAL ================= */
        try {
            $dari_tanggal = $dari_tanggal
                ? Carbon::parse($dari_tanggal)->startOfDay()->toDateString()
                : null;

            $sampai_tanggal = $sampai_tanggal
                ? Carbon::parse($sampai_tanggal)->endOfDay()->toDateString()
                : null;
        } catch (\Exception $e) {
            $dari_tanggal = null;
            $sampai_tanggal = null;
        }

        /* ================= FILTER RENTANG TANGGAL ================= */
        if ($dari_tanggal && $sampai_tanggal) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif ($dari_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif ($sampai_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $query->orderBy('tanggal_data_koperasi', 'asc');

        $data_koperasi = $query->get();

        /* ================= GROUPING PER TANGGAL ================= */
        $data_koperasi = $query->get()
            ->unique('id_data_koperasi') // 🔥 PENTING: HILANGKAN DUPLIKAT
            ->values();

        $grouped = $data_koperasi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_data_koperasi)
                ->translatedFormat('d F Y');
        });

        /* ================= HITUNG TOTAL HARGA ================= */
        foreach ($data_koperasi as $item) {

            // MODAL MASUK
            if ($item->jenis_data_koperasi !== 'modal_keluar') {
                $item->total_harga_supplier = $item->harga_data_koperasi;
                continue;
            }

            // MODAL KELUAR - SUPPLIER
            if (!empty($item->id_informasi_supplier)) {
                $item->total_harga_supplier = DB::table('barang_supplier')
                    ->where('id_informasi_supplier', $item->id_informasi_supplier)
                    ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_supplier');
            }
            // MODAL KELUAR - NON SUPPLIER
            else {
                $item->total_harga_supplier = DB::table('barang_modal_keluar')
                    ->where('id_data_koperasi', $item->id_data_koperasi)
                    ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_modal_keluar');
            }
        }

        return view('owner.data_koperasi.cetak_data_koperasi', compact(
            'data_koperasi',
            'grouped',
            'dari_tanggal',
            'sampai_tanggal'
        ));
    }

    public function store_owner_data_koperasi(Request $request)
    {
        $modal_masuk = $request->modal_masuk;
        $modal_keluar = $request->modal_keluar;
        $tanggal_data_koperasi = $request->tanggal_data_koperasi;

        $data = [
            'modal_masuk' => $modal_masuk,
            'modal_keluar' => $modal_keluar,
            'tanggal_data_koperasi' => $tanggal_data_koperasi
        ];

        $simpan = DB::table('data_koperasi')->insert($data);
        if ($simpan){
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit_modal_masuk_owner_data_koperasi(Request $request)
    {
        $id = $request->id;
        $data_koperasi = DB::table('data_koperasi')->get();
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        return view('owner.data_koperasi.edit_modal_masuk_data_koperasi',compact('data_koperasi','data'));
    }

    public function update_modal_masuk_owner_data_koperasi($id, Request $request)
    {
        $kategori_data_koperasi = $request->kategori_data_koperasi;
        $harga_data_koperasi = $request->harga_data_koperasi;
        $tanggal_data_koperasi = $request->tanggal_data_koperasi;

        try {
            $data = [
                'kategori_data_koperasi' => $kategori_data_koperasi,
                'harga_data_koperasi' => $harga_data_koperasi,
                'tanggal_data_koperasi' => $tanggal_data_koperasi
            ];
            $update = DB::table('data_koperasi')->where('id_data_koperasi', $id)->update($data);
            if ($update){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }


    public function edit_modal_keluar_owner_data_koperasi(Request $request)
    {
        $id = $request->id;

        // Ambil semua data koperasi (opsional, kalau untuk dropdown)
        $data_koperasi = DB::table('data_koperasi')->get();

        // Ambil data koperasi berdasarkan ID
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();

        // Ambil nomor dapur dan id_informasi_supplier
        $nomor_dapur = $data ? $data->nomor_dapur_data_koperasi : null;
        $id_informasi_supplier = $data->id_informasi_supplier ?? null;

        // Inisialisasi variabel hasil
        $barang_list = collect(); // pakai collect biar bisa kosong tapi tetap iterable

        if (!empty($id_informasi_supplier) && $id_informasi_supplier > 0) {
            // ✅ Jika ada id_informasi_supplier → ambil dari barang_supplier
            $barang_list = DB::table('barang_supplier')
                ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
                ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
                ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
                ->select(
                    'barang_supplier.id_barang_supplier as id_barang',
                    'barang_supplier.nama_barang_supplier as nama_barang',
                    'barang_supplier.jumlah_barang_supplier as jumlah',
                    'barang_supplier.satuan_barang_supplier as satuan',
                    'barang_supplier.harga_barang_supplier as harga',
                    'informasi_supplier.nama_informasi_supplier as supplier'
                )
                ->get();
        } else {
            // ✅ Jika tidak ada id_informasi_supplier → ambil dari barang_modal_keluar
            $barang_list = DB::table('barang_modal_keluar')
                ->where('id_data_koperasi', $id)
                ->where('nomor_dapur_barang_modal_keluar', $nomor_dapur)
                ->select(
                    'id_barang_modal_keluar as id_barang',
                    'nama_barang_modal_keluar as nama_barang',
                    'jumlah_barang_modal_keluar as jumlah',
                    'satuan_barang_modal_keluar as satuan',
                    'harga_barang_modal_keluar as harga'
                )
                ->get();
        }

        return view('owner.data_koperasi.edit_modal_keluar_data_koperasi', compact(
            'data_koperasi',
            'data',
            'barang_list'
        ));
    }



    public function update_modal_keluar_owner_data_koperasi($id, Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Ambil data koperasi terkait
            $dataKoperasi = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();

            if (!$dataKoperasi) {
                throw new \Exception("Data koperasi tidak ditemukan.");
            }

            // 2️⃣ Update data utama di tabel data_koperasi
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update([
                    'kategori_data_koperasi' => $request->kategori_data_koperasi,
                    'tanggal_data_koperasi' => $request->tanggal_data_koperasi,
                ]);

            // 3️⃣ Ambil data barang dari form
            $barangList = $request->barang;

            if (!empty($barangList)) {
                foreach ($barangList as $item) {
                    // Pastikan ada ID barang
                    if (!empty($item['id_barang'])) {

                        // 4️⃣ Jika data berasal dari supplier
                        if (!empty($dataKoperasi->id_informasi_supplier) && $dataKoperasi->id_informasi_supplier > 0) {
                            DB::table('barang_supplier')
                                ->where('id_barang_supplier', $item['id_barang'])
                                ->update([
                                    'nama_barang_supplier' => $item['nama_barang'],
                                    'jumlah_barang_supplier' => $item['jumlah'],
                                    'satuan_barang_supplier' => $item['satuan'],
                                    'harga_barang_supplier' => $item['harga'],
                                ]);

                        } else {
                            // 5️⃣ Jika data berasal dari non-supplier (barang_modal_keluar)
                            DB::table('barang_modal_keluar')
                                ->where('id_barang_modal_keluar', $item['id_barang'])
                                ->update([
                                    'nama_barang_modal_keluar' => $item['nama_barang'],
                                    'jumlah_barang_modal_keluar' => $item['jumlah'],
                                    'satuan_barang_modal_keluar' => $item['satuan'],
                                    'harga_barang_modal_keluar' => $item['harga'],
                                ]);
                        }
                    }
                }
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diubah']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }



    public function lihat_owner_barang_modal_keluar(Request $request)
    {
        // id_data_koperasi dikirim dari request
        $id_data_koperasi = $request->id;

        // Cek data koperasi untuk ambil id_informasi_supplier-nya
        $data_koperasi = DB::table('data_koperasi')
            ->where('id_data_koperasi', $id_data_koperasi)
            ->first();

        $id_informasi_supplier = $data_koperasi->id_informasi_supplier ?? null;
        $nomor_dapur = $data_koperasi->nomor_dapur_data_koperasi;

        // Siapkan variabel barang_list
        $barang_list = collect();

        // 1) Coba ambil dari barang_supplier berdasar id_informasi_supplier
        $barang_list = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
            ->select(
                'barang_supplier.id_barang_supplier as id_barang',
                'barang_supplier.nama_barang_supplier as nama_barang',
                'barang_supplier.jumlah_barang_supplier as jumlah',
                'barang_supplier.satuan_barang_supplier as satuan',
                'barang_supplier.harga_barang_supplier as harga',
                DB::raw("'Supplier' as sumber_data")
            )
            ->get();

        // 2) Jika tidak ada (kosong) → ambil dari barang_modal_keluar berdasar id_data_koperasi
        if ($barang_list->isEmpty()) {
            $barang_list = DB::table('barang_modal_keluar')
                ->where('barang_modal_keluar.id_data_koperasi', $id_data_koperasi)
                ->where('barang_modal_keluar.nomor_dapur_barang_modal_keluar', $nomor_dapur)
                ->select(
                    'barang_modal_keluar.id_barang_modal_keluar as id_barang',
                    'barang_modal_keluar.nama_barang_modal_keluar as nama_barang',
                    'barang_modal_keluar.jumlah_barang_modal_keluar as jumlah',
                    'barang_modal_keluar.satuan_barang_modal_keluar as satuan',
                    'barang_modal_keluar.harga_barang_modal_keluar as harga',
                    DB::raw("'Modal Keluar' as sumber_data")
                )
                ->get();
        }

        // Kirim ke view sebagai barang_list (lebih jelas)
        return view('maker.data_koperasi.lihat_barang_modal_keluar', compact('barang_list'));
    }



    public function delete_owner_data_koperasi($id)
    {
        $delete = DB::table('data_koperasi')->where('id_data_koperasi', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }


    public function validasi_owner_data_koperasi(Request $request)
    {
        $id = $request->id;
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        return view('owner.data_koperasi.validasi_data_koperasi',compact('data'));
    }


    public function update_validasi_owner_data_koperasi($id, Request $request)
    {
        $id                     = $request->id; 
        $status_data_koperasi   = $request->status_data_koperasi;
    
        DB::beginTransaction();
    
        try {
            // ✅ 1. Ambil data koperasi berdasarkan id_data_koperasi
            $koperasi = DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->first();
        
            if (!$koperasi) {
                return Redirect::back()->with(['error' => 'Data koperasi tidak ditemukan']);
            }
        
            // ✅ 2. Update status_data_koperasi
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update([
                    'status_data_koperasi' => $status_data_koperasi
                ]);
            
            // ✅ 3. Update status_informasi_supplier juga (1 atau 2 mengikuti status_koperasi)
            if (!empty($koperasi->id_informasi_supplier)) {
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $koperasi->id_informasi_supplier)
                    ->update([
                        'status_informasi_supplier' => $status_data_koperasi
                    ]);
            }
        
            // ✅ 4. Proses keuangan jika status = 1 ATAU 2
            if (in_array($status_data_koperasi, [1, 2])) {

                // Cek apakah data keuangan sudah ada
                $cekKeuangan = DB::table('keuangan')
                    ->where('id_data_koperasi', $id)
                    ->first();
            
                if (!$cekKeuangan) {
                    // ✅ Insert keuangan hanya jika belum ada
                    DB::table('keuangan')->insert([
                        'id_data_koperasi'          => $koperasi->id_data_koperasi,
                        'id_informasi_supplier'     => $koperasi->id_informasi_supplier ?? null,
                        'nomor_dapur_keuangan'      => $koperasi->nomor_dapur_data_koperasi,
                        'tanggal_laporan_keuangan'  => $koperasi->tanggal_data_koperasi,
                        'jenis_transaksi'           => $koperasi->jenis_data_koperasi,
                    ]);
                }
            }
        
            DB::commit();
        
            return Redirect::back()->with([
                'success' => 'Status koperasi & supplier berhasil diperbarui dan data keuangan diproses'
            ]);
        
        } catch (\Exception $e) {
            DB::rollBack();
        
            return Redirect::back()->with([
                'error' => 'Data Gagal Divalidasi: ' . $e->getMessage()
            ]);
        }
    }


    public function batalkan_validasi_owner_data_koperasi($id, Request $request)
    {
        $id = $request->id; // id_data_koperasi

        DB::beginTransaction();

        try {
            // ✅ 1. Ambil data koperasi berdasarkan id_data_koperasi
            $dataKoperasi = DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->first();

            if (!$dataKoperasi) {
                return Redirect::back()->with(['error' => 'Data koperasi tidak ditemukan']);
            }

            // ✅ 2. Ambil id_informasi_supplier dari data koperasi
            $id_informasi_supplier = $dataKoperasi->id_informasi_supplier;

            // ✅ 3. Update status_data_koperasi jadi 0
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update([
                    'status_data_koperasi' => 0
                ]);

            // ✅ 4. Update status_informasi_supplier jadi 0
            DB::table('informasi_supplier')
                ->where('id_informasi_supplier', $id_informasi_supplier)
                ->update([
                    'status_informasi_supplier' => 0
                ]);

            // ✅ 5. Hapus data keuangan yang terkait
            DB::table('keuangan')
                ->where('id_data_koperasi', $id)
                ->delete();

            DB::commit();

            return Redirect::back()->with([
                'success' => 'Validasi berhasil dibatalkan, status supplier diperbarui, dan data keuangan dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return Redirect::back()->with([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function tambah_owner_barang_modal_keluar(Request $request)
    {
        $id = $request->id;
        $data_koperasi = DB::table('data_koperasi')->get();
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        return view('owner.data_koperasi.tambah_barang_modal_keluar',compact('data_koperasi','data','dapurList'));
    }

    public function store_owner_barang_modal_keluar(Request $request)
    {
        DB::beginTransaction();
        try {
            $nomor_dapur = $request->pilih_dapur_modal_keluar;
            $id_data_koperasi = $request->id_data_koperasi;

            // Cek apakah data koperasi memiliki id_informasi_supplier
            $dataKoperasi = DB::table('data_koperasi')->where('id_data_koperasi', $id_data_koperasi)->first();

            // Daftar input barang dari form
            $inputBarang = [
                [
                    'nama'   => $request->nama_barang_modal_keluar_1,
                    'jumlah' => $request->jumlah_barang_modal_keluar_1,
                    'satuan' => $request->satuan_barang_modal_keluar_1,
                    'harga'  => $request->harga_barang_modal_keluar_1,
                ],
                [
                    'nama'   => $request->nama_barang_modal_keluar_2,
                    'jumlah' => $request->jumlah_barang_modal_keluar_2,
                    'satuan' => $request->satuan_barang_modal_keluar_2,
                    'harga'  => $request->harga_barang_modal_keluar_2,
                ],
                [
                    'nama'   => $request->nama_barang_modal_keluar_3,
                    'jumlah' => $request->jumlah_barang_modal_keluar_3,
                    'satuan' => $request->satuan_barang_modal_keluar_3,
                    'harga'  => $request->harga_barang_modal_keluar_3,
                ],
            ];

            // Hanya masukkan data yang tidak kosong
            foreach ($inputBarang as $barang) {
                if (!empty($barang['nama']) && !empty($barang['jumlah'])) {

                    // Jika ada id_informasi_supplier di data koperasi → masuk ke tabel barang_supplier
                    if (!empty($dataKoperasi->id_informasi_supplier)) {
                        DB::table('barang_supplier')->insert([
                            'id_informasi_supplier' => $dataKoperasi->id_informasi_supplier,
                            'nama_barang_supplier'  => $barang['nama'],
                            'jumlah_barang_supplier'=> $barang['jumlah'],
                            'satuan_barang_supplier'=> $barang['satuan'],
                            'harga_barang_supplier' => $barang['harga'],
                            'nomor_dapur_barang_supplier' => $nomor_dapur
                        ]);
                    } 
                    // Jika tidak ada → masuk ke tabel barang_modal_keluar
                    else {
                        DB::table('barang_modal_keluar')->insert([
                            'nama_barang_modal_keluar'    => $barang['nama'],
                            'jumlah_barang_modal_keluar'  => $barang['jumlah'],
                            'satuan_barang_modal_keluar'  => $barang['satuan'],
                            'harga_barang_modal_keluar'   => $barang['harga'],
                            'nomor_dapur_barang_modal_keluar'    => $nomor_dapur,
                            'id_data_koperasi'            => $id_data_koperasi
                        ]);
                    }
                }
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data berhasil disimpan ke tabel yang sesuai']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }































































    
    // BAGIAN MAKER
    public function index_maker_data_koperasi(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $dapur = $maker->nomor_dapur_maker;

        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;

        $query = DataKoperasi::where('nomor_dapur_data_koperasi', $dapur);

        /* ================= FILTER RANGE TANGGAL ================= */
        if ($dari_tanggal && $sampai_tanggal) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif ($dari_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif ($sampai_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $data_koperasi = $query
            ->orderBy('tanggal_data_koperasi', 'asc')
            ->get();

        /* ================= OLAH DATA UNTUK BLADE ================= */
        foreach ($data_koperasi as $d) {

            // Format tanggal Indonesia
            $d->tanggal_format = Carbon::parse($d->tanggal_data_koperasi)
                ->translatedFormat('d F Y');

            // Total harga dari tabel barang_modal_keluar
            $d->total_harga = DB::table('barang_modal_keluar')
                ->where('id_data_koperasi', $d->id_data_koperasi)
                ->where('nomor_dapur_barang_modal_keluar', $d->nomor_dapur_data_koperasi)
                ->sum('harga_barang_modal_keluar');
        }

        $dataKosong = $data_koperasi->isEmpty();

        return view('maker.data_koperasi.index_data_koperasi', compact(
            'data_koperasi',
            'dataKosong'
        ));
    }

    

    public function store_maker_data_koperasi(Request $request)
    {
        // Ambil data maker yang sedang login
        $maker                  = Auth::guard('maker')->user();
        $nomor_dapur_maker      = $maker->nomor_dapur_maker;
        $tanggal_data_koperasi  = $request->tanggal_data_koperasi;


        /**
         * 🔴 CEK APAKAH DATA KOPERASI SUDAH ADA DI TANGGAL TERSEBUT
         */
        $cekDataKoperasi = DB::table('data_koperasi')
            ->where('nomor_dapur_data_koperasi', $nomor_dapur_maker)
            ->where('tanggal_data_koperasi', $tanggal_data_koperasi)
            ->first();

        if ($cekDataKoperasi) {
            $tanggalFormat = Carbon::parse($tanggal_data_koperasi)
                ->translatedFormat('l, d F Y');
            return Redirect::back()->with([
                'warning' => 'Data koperasi pada tanggal ' . $tanggalFormat . ' sudah ada. Silakan input melalui menu Input Barang.'
            ]);
        }



        if($request->hasFile('bukti_terima_data_koperasi')){
            $bukti_terima_data_koperasi = "Bukti Terima_Data Koperasi_".$tanggal_data_koperasi.".".$request
                ->file('bukti_terima_data_koperasi')
                ->getClientOriginalExtension();
        } else {
            $bukti_terima_data_koperasi = null;
        }

        // Mulai transaksi database agar aman jika salah satu gagal
        DB::beginTransaction();

        try {
            // 1️⃣ Simpan data ke tabel data_koperasi
            $id_data_koperasi = DB::table('data_koperasi')->insertGetId([
                'nomor_dapur_data_koperasi'        => $nomor_dapur_maker,
                'tanggal_data_koperasi'            => $tanggal_data_koperasi,
                'bukti_terima_data_koperasi'       => $bukti_terima_data_koperasi,
                'status_data_koperasi'             => 0, // default: menunggu validasi
            ]);

            if ($request->hasFile('bukti_terima_data_koperasi')) {
                $storagePath = 'public/uploads/data_koperasi/bukti_terima/';
                $request->file('bukti_terima_data_koperasi')->storeAs($storagePath, $bukti_terima_data_koperasi);
                $publicPath = public_path('storage/uploads/data_koperasi/bukti_terima/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $bukti_terima_data_koperasi);
                $destinationFile = public_path('storage/uploads/data_koperasi/bukti_terima/' . $bukti_terima_data_koperasi);
                copy($sourceFile, $destinationFile);
            }

            // 2️⃣ Jika belum ada data ditanggal tanggal_data_koperasi, tambahkan juga ke tabel keuangan. Jika sudah ada cukup update kolom id_data_koperasi nya saja
            $dataKeuangan = DB::table('keuangan')
                ->where('tanggal_laporan_keuangan', $tanggal_data_koperasi)
                ->where('nomor_dapur_keuangan', $nomor_dapur_maker)
                ->first();
            if (!$dataKeuangan) {
                DB::table('keuangan')->insert([
                'id_data_koperasi'           => $id_data_koperasi,
                'nomor_dapur_keuangan'       => $nomor_dapur_maker,
                'tanggal_laporan_keuangan'   => $tanggal_data_koperasi,
                ]);
            } else {
                // ✅ SUDAH ADA → UPDATE id_data_koperasi SAJA
                DB::table('keuangan')
                    ->where('id_laporan_keuangan', $dataKeuangan->id_laporan_keuangan)
                    ->update([
                        'id_data_koperasi' => $id_data_koperasi
                ]);
            }

            // 3️⃣ Commit transaksi jika semua berhasil
            DB::commit();

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);

        } catch (\Exception $e) {
            // Jika ada error, rollback agar data tidak setengah masuk
            DB::rollBack();

            return Redirect::back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }



    public function tambah_maker_barang_modal_keluar(Request $request)
    {
        $id                 = $request->id;
        $data_koperasi      = DB::table('data_koperasi')->get();
        $data               = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        return view('maker.data_koperasi.tambah_barang_modal_keluar',compact('data_koperasi','data'));
    }





    public function store_maker_barang_modal_keluar(Request $request)
    {
        DB::beginTransaction();

        try {

            // ============================================================
            // 1. AMBIL DATA DARI FORM
            // ============================================================
            $nomor_dapur       = $request->pilih_dapur_modal_keluar;
            $id_data_koperasi  = $request->id_data_koperasi;

            // ============================================================
            // 2. CEK DATA KOPERASI
            // ============================================================
            $dataKoperasi = DB::table('data_koperasi')
                ->where('id_data_koperasi', $id_data_koperasi)
                ->first();

            if (!$dataKoperasi) {
                return Redirect::back()->with([
                    'error' => 'Data koperasi tidak ditemukan'
                ]);
            }

            // ============================================================
            // 3. RESET STATUS VALIDASI DATA KOPERASI
            // ============================================================
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id_data_koperasi)
                ->update([
                    'status_data_koperasi' => 0 // menunggu validasi ulang
                ]);

            // ============================================================
            // 4. AMBIL DATA BARANG DINAMIS DARI FORM
            // ============================================================
            $nama_barang   = $request->nama_barang_modal_keluar;
            $jumlah_barang = $request->jumlah_barang_modal_keluar;
            $satuan_barang = $request->satuan_barang_modal_keluar;
            $harga_barang  = $request->harga_barang_modal_keluar;
            
            // ============================================================
            // 5. INSERT BARANG MODAL KELUAR
            // ============================================================
            if (is_array($nama_barang)) {
            
                foreach ($nama_barang as $index => $nama) {
            
                    // validasi minimal
                    if (!empty($nama) && !empty($jumlah_barang[$index])) {
            
                        DB::table('barang_modal_keluar')->insert([
                            'nama_barang_modal_keluar'        => $nama,
                            'jumlah_barang_modal_keluar'      => $jumlah_barang[$index],
                            'satuan_barang_modal_keluar'      => $satuan_barang[$index] ?? null,
                            'harga_barang_modal_keluar'       => $harga_barang[$index] ?? 0,
                            'nomor_dapur_barang_modal_keluar' => $nomor_dapur,
                            'id_data_koperasi'                => $id_data_koperasi,
                        ]);
                    }
                }
            }

            DB::commit();

            return Redirect::back()->with([
                'success' => 'Data barang modal keluar berhasil disimpan dan status koperasi direset'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return Redirect::back()->with([
                'warning' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }





    public function lihat_maker_barang_modal_keluar(Request $request)
    {
        $maker = Auth::guard('maker')->user();
        $nomor_dapur_maker = $maker->nomor_dapur_maker;

        // id_data_koperasi dikirim dari request
        $id_data_koperasi = $request->id;

        // Siapkan variabel barang_list
        $barang_list = collect();

        // Ambil dari barang_modal_keluar berdasar id_data_koperasi
        if ($barang_list->isEmpty()) {
            $barang_list = DB::table('barang_modal_keluar')
                ->where('barang_modal_keluar.id_data_koperasi', $id_data_koperasi)
                ->where('barang_modal_keluar.nomor_dapur_barang_modal_keluar', $nomor_dapur_maker)
                ->select(
                    'barang_modal_keluar.id_barang_modal_keluar as id_barang',
                    'barang_modal_keluar.nama_barang_modal_keluar as nama_barang',
                    'barang_modal_keluar.jumlah_barang_modal_keluar as jumlah',
                    'barang_modal_keluar.satuan_barang_modal_keluar as satuan',
                    'barang_modal_keluar.harga_barang_modal_keluar as harga',
                    DB::raw("'Modal Keluar' as sumber_data")
                )
                ->get();
        }

        // Kirim ke view sebagai barang_list (lebih jelas)
        return view('maker.data_koperasi.lihat_barang_modal_keluar', compact('barang_list'));
    }

    public function delete_maker_data_koperasi($id)
    {
        $delete = DB::table('data_koperasi')->where('id_data_koperasi', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }






    public function cetak_maker_data_koperasi(Request $request)
    {
        // Ambil data maker yang sedang login
        $maker          = Auth::guard('maker')->user();
        $dapur          = $maker->nomor_dapur_maker;

        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;

        $query = DataKoperasi::query()
            ->leftJoin('dapur', 'data_koperasi.nomor_dapur_data_koperasi', '=', 'dapur.nomor_dapur')
            ->select('data_koperasi.*', 'dapur.nama_dapur');

        /* ================= FILTER DAPUR ================= */
        if (!empty($dapur)) {
            $query->where('data_koperasi.nomor_dapur_data_koperasi', $dapur);
        }

        /* ================= KONVERSI TANGGAL ================= */
        try {
            $dari_tanggal = $dari_tanggal
                ? Carbon::parse($dari_tanggal)->startOfDay()->toDateString()
                : null;

            $sampai_tanggal = $sampai_tanggal
                ? Carbon::parse($sampai_tanggal)->endOfDay()->toDateString()
                : null;
        } catch (\Exception $e) {
            $dari_tanggal = null;
            $sampai_tanggal = null;
        }

        /* ================= FILTER RENTANG TANGGAL ================= */
        if ($dari_tanggal && $sampai_tanggal) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif ($dari_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif ($sampai_tanggal) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $query->orderBy('tanggal_data_koperasi', 'asc');

        $data_koperasi = $query->get();

        /* ================= GROUPING PER TANGGAL ================= */
        $data_koperasi = $query->get()
            ->unique('id_data_koperasi') // 🔥 PENTING: HILANGKAN DUPLIKAT
            ->values();

        $grouped = $data_koperasi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_data_koperasi)
                ->translatedFormat('d F Y');
        });

        /* ================= HITUNG TOTAL HARGA ================= */
        foreach ($data_koperasi as $item) {

            // MODAL MASUK
            if ($item->jenis_data_koperasi !== 'modal_keluar') {
                $item->total_harga_supplier = $item->harga_data_koperasi;
                continue;
            }

            // MODAL KELUAR - SUPPLIER
            if (!empty($item->id_informasi_supplier)) {
                $item->total_harga_supplier = DB::table('barang_supplier')
                    ->where('id_informasi_supplier', $item->id_informasi_supplier)
                    ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_supplier');
            }
            // MODAL KELUAR - NON SUPPLIER
            else {
                $item->total_harga_supplier = DB::table('barang_modal_keluar')
                    ->where('id_data_koperasi', $item->id_data_koperasi)
                    ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                    ->sum('harga_barang_modal_keluar');
            }
        }

        return view('maker.data_koperasi.cetak_data_koperasi', compact(
            'data_koperasi',
            'grouped',
            'dari_tanggal',
            'sampai_tanggal'
        ));
    }




    public function edit_modal_masuk_maker_data_koperasi(Request $request)
    {
        $id = $request->id;
        $data_koperasi = DB::table('data_koperasi')->get();
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        return view('maker.data_koperasi.edit_modal_masuk_data_koperasi',compact('data_koperasi','data'));
    }


    public function update_modal_masuk_maker_data_koperasi($id, Request $request)
    {
        $kategori_data_koperasi = $request->kategori_data_koperasi;
        $harga_data_koperasi = $request->harga_data_koperasi;
        $tanggal_data_koperasi = $request->tanggal_data_koperasi;

        try {
            $data = [
                'kategori_data_koperasi' => $kategori_data_koperasi,
                'harga_data_koperasi' => $harga_data_koperasi,
                'tanggal_data_koperasi' => $tanggal_data_koperasi
            ];
            $update = DB::table('data_koperasi')->where('id_data_koperasi', $id)->update($data);
            if ($update){
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }






    public function edit_modal_keluar_maker_data_koperasi(Request $request)
    {
        $id = $request->id;

        // Ambil data koperasi berdasarkan ID
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();

        if (!$data) {
            return Redirect::back()->with(['error' => 'Data koperasi tidak ditemukan']);
        }

        // Ambil id_informasi_supplier
        $id_informasi_supplier = $data->id_informasi_supplier ?? null;

        try {

            DB::beginTransaction();

            // ✅ 1. Update status_data_koperasi ke 0 (Menunggu)
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update([
                    'status_data_koperasi' => 0
                ]);

            // ✅ 2. Update status_informasi_supplier ke 0 jika ada supplier
            if (!empty($id_informasi_supplier)) {
                DB::table('informasi_supplier')
                    ->where('id_informasi_supplier', $id_informasi_supplier)
                    ->update([
                        'status_informasi_supplier' => 0
                    ]);
            }

            // ✅ 3. Hapus data keuangan yang terkait dengan id_data_koperasi
            DB::table('keuangan')->where('id_data_koperasi', $id)->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['error' => 'Gagal memperbarui status: ' . $e->getMessage()]);
        }

        // Ambil semua data koperasi (opsional)
        $data_koperasi = DB::table('data_koperasi')->get();

        // Ambil nomor dapur
        $nomor_dapur = $data->nomor_dapur_data_koperasi;

        // Inisialisasi
        $barang_list = collect();

        // === Ambil barang berdasarkan supplier atau modal keluar ===
        if (!empty($id_informasi_supplier) && $id_informasi_supplier > 0) {

            // Barang supplier
            $barang_list = DB::table('barang_supplier')
                ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
                ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
                ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur)
                ->select(
                    'barang_supplier.id_barang_supplier as id_barang',
                    'barang_supplier.nama_barang_supplier as nama_barang',
                    'barang_supplier.jumlah_barang_supplier as jumlah',
                    'barang_supplier.satuan_barang_supplier as satuan',
                    'barang_supplier.harga_barang_supplier as harga',
                    'informasi_supplier.nama_informasi_supplier as supplier'
                )
                ->get();

        } else {

            // Barang modal keluar
            $barang_list = DB::table('barang_modal_keluar')
                ->where('id_data_koperasi', $id)
                ->where('nomor_dapur_barang_modal_keluar', $nomor_dapur)
                ->select(
                    'id_barang_modal_keluar as id_barang',
                    'nama_barang_modal_keluar as nama_barang',
                    'jumlah_barang_modal_keluar as jumlah',
                    'satuan_barang_modal_keluar as satuan',
                    'harga_barang_modal_keluar as harga'
                )
                ->get();
        }

        return view('maker.data_koperasi.edit_modal_keluar_data_koperasi', compact(
            'data_koperasi',
            'data',
            'barang_list'
        ));
    }



    public function update_modal_keluar_maker_data_koperasi($id, Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Ambil data koperasi terkait
            $dataKoperasi = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();

            if (!$dataKoperasi) {
                throw new \Exception("Data koperasi tidak ditemukan.");
            }

            // 2️⃣ Update data utama di tabel data_koperasi
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update([
                    'kategori_data_koperasi' => $request->kategori_data_koperasi,
                    'tanggal_data_koperasi' => $request->tanggal_data_koperasi,
                ]);

            // 3️⃣ Ambil data barang dari form
            $barangList = $request->barang;

            if (!empty($barangList)) {
                foreach ($barangList as $item) {
                    // Pastikan ada ID barang
                    if (!empty($item['id_barang'])) {

                        // 4️⃣ Jika data berasal dari supplier
                        if (!empty($dataKoperasi->id_informasi_supplier) && $dataKoperasi->id_informasi_supplier > 0) {
                            DB::table('barang_supplier')
                                ->where('id_barang_supplier', $item['id_barang'])
                                ->update([
                                    'nama_barang_supplier' => $item['nama_barang'],
                                    'jumlah_barang_supplier' => $item['jumlah'],
                                    'satuan_barang_supplier' => $item['satuan'],
                                    'harga_barang_supplier' => $item['harga'],
                                ]);

                        } else {
                            // 5️⃣ Jika data berasal dari non-supplier (barang_modal_keluar)
                            DB::table('barang_modal_keluar')
                                ->where('id_barang_modal_keluar', $item['id_barang'])
                                ->update([
                                    'nama_barang_modal_keluar' => $item['nama_barang'],
                                    'jumlah_barang_modal_keluar' => $item['jumlah'],
                                    'satuan_barang_modal_keluar' => $item['satuan'],
                                    'harga_barang_modal_keluar' => $item['harga'],
                                ]);
                        }
                    }
                }
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diubah']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    
}
