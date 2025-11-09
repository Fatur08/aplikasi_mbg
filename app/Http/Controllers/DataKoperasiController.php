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
        $bulan          = $request->bulan;

        $query = DataKoperasi::query()
            ->leftJoin('dapur', 'data_koperasi.nomor_dapur_data_koperasi', '=', 'dapur.nomor_dapur')
            ->select('data_koperasi.*', 'dapur.nama_dapur')
            ->distinct();
        if ($pilih_dapur !== null && $pilih_dapur !== '') {
            $query->where('data_koperasi.nomor_dapur_data_koperasi', $pilih_dapur);
        }

        // Konversi format tanggal user ke format database (Y-m-d)
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

        // Filter berdasarkan rentang tanggal
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        // Filter berdasarkan bulan (jika dipilih)
        if (!empty($bulan)) {
            // Mapping nama bulan ke angka
            $bulan_map = [
                'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            ];

            if (isset($bulan_map[$bulan])) {
                $bulan_angka = $bulan_map[$bulan];
                $query->whereMonth('tanggal_data_koperasi', $bulan_angka);

                // Opsional: filter per tahun saat ini
                $query->whereYear('tanggal_data_koperasi', date('Y'));
            }
        }

        $query->orderBy('tanggal_data_koperasi', 'asc');

        $data_koperasi = $query->paginate(10);
        
        // 👉 Cek apakah hasil pencarian kosong
        $dataKosong = $data_koperasi->isEmpty();

        // 👉 Deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($request->dari_tanggal) ||
                     !empty($request->sampai_tanggal) ||
                     !empty($request->bulan) ||
                     !empty($request->pilih_dapur);

        // ✅ Tambahkan grouping per tanggal
        $grouped = $data_koperasi->getCollection()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_data_koperasi)->translatedFormat('d F Y');
        });

        // ✅ Perhitungan total harga (supplier dan non-supplier)
        foreach ($data_koperasi as $item) {
            if ($item->id_informasi_supplier > 0) {
                // Barang dari supplier
                $item->total_harga_supplier = DB::table('barang_supplier')
                    ->where('id_informasi_supplier', $item->id_informasi_supplier)
                    ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                    ->sum(DB::raw('harga_barang_supplier'));
            } else {
                // Barang non-supplier, ambil dari harga_data_koperasi
                $item->total_harga_supplier = DB::table('barang_modal_keluar')
                    ->where('id_data_koperasi', $item->id_data_koperasi)
                    ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                    ->sum(DB::raw('harga_barang_modal_keluar'));
            }
        }

        // Ambil semua data dapur
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
        $dari_tanggal         = $request->dari_tanggal ? date('Y-m-d', strtotime($request->dari_tanggal)) : null;
        $sampai_tanggal       = $request->sampai_tanggal ? date('Y-m-d', strtotime($request->sampai_tanggal)) : null;
        $bulan                = $request->bulan;
        $jenis_data_koperasi  = $request->jenis_data_koperasi; 
    
        $query = DB::table('data_koperasi');
    
        if (!empty($jenis_transaksi)) {
            $query->where('jenis_data_koperasi', $jenis_data_koperasi);
        }

        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $data = $query->get();
        $total_pemasukan = DB::table('data_koperasi')
            ->where('jenis_data_koperasi', 'Pemasukan')
            ->sum('harga_data_koperasi');
    
        $total_pengeluaran = DB::table('data_koperasi')
            ->where('jenis_data_koperasi', 'Pengeluaran')
            ->sum('harga_data_koperasi');
    
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        return view('owner.data_koperasi.cetak_data_koperasi', compact('data','sisa_dana'));
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
        return view('admin.data_koperasi.lihat_barang_modal_keluar', compact('barang_list'));
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
        $id = $request->id;
        $status_data_koperasi = $request->status_data_koperasi;

        try {
            // Ambil data koperasi berdasarkan id
            $koperasi = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();

            if (!$koperasi) {
                return Redirect::back()->with(['error' => 'Data koperasi tidak ditemukan']);
            }

            // Update status validasi
            DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update(['status_data_koperasi' => $status_data_koperasi]);

            // Jika status validasi adalah "disetujui" atau valid (silakan sesuaikan kondisi jika perlu)
            if ($status_data_koperasi == 1) {

                // Tentukan jenis transaksi
                $jenisTransaksi = null;
                if ($koperasi->jenis_data_koperasi == 'modal_masuk') {
                    $jenisTransaksi = 'Pemasukan';
                } elseif ($koperasi->jenis_data_koperasi == 'modal_keluar') {
                    $jenisTransaksi = 'Pengeluaran';
                }

                // Cek apakah data keuangan untuk id_data_koperasi ini sudah ada (agar tidak duplikat)
                $cekKeuangan = DB::table('keuangan')
                    ->where('id_data_koperasi', $id)
                    ->first();

                if (!$cekKeuangan) {
                    // Insert data baru ke tabel keuangan
                    DB::table('keuangan')->insert([
                        'id_data_koperasi' => $koperasi->id_data_koperasi,
                        'id_informasi_supplier' => $koperasi->id_informasi_supplier ?? null,
                        'nomor_dapur_keuangan' => $koperasi->nomor_dapur_data_koperasi,
                        'tanggal_laporan_keuangan' => $koperasi->tanggal_data_koperasi,
                        'jenis_transaksi' => $jenisTransaksi,
                    ]);
                }
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Divalidasi dan Disimpan ke Keuangan']);

        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Divalidasi: ' . $e->getMessage()]);
        }
    }


    public function batalkan_validasi_owner_data_koperasi($id, Request $request)
    {
        $id = $request->id;
    
        try {
            // 🔹 Ubah status validasi koperasi menjadi 0 (dibatalkan)
            $update = DB::table('data_koperasi')
                ->where('id_data_koperasi', $id)
                ->update(['status_data_koperasi' => 0]);
        
            if ($update) {
                // 🔹 Hapus data keuangan yang terkait dengan id_data_koperasi ini
                DB::table('keuangan')->where('id_data_koperasi', $id)->delete();
            
                return Redirect::back()->with(['success' => 'Validasi berhasil dibatalkan dan data keuangan telah dihapus']);
            }
        
            return Redirect::back()->with(['error' => 'Data tidak ditemukan atau gagal diperbarui']);
        
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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



























    
    // BAGIAN ADMIN
    public function index_admin_data_koperasi(Request $request)
    {        
        $dari_tanggal   = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;
        $bulan          = $request->bulan;

        $query = DataKoperasi::query();
        $query->select('*');

        // Konversi format tanggal user ke format database (Y-m-d)
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

        // Filter berdasarkan rentang tanggal
        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        // Filter berdasarkan bulan (jika dipilih)
        if (!empty($bulan)) {
            // Mapping nama bulan ke angka
            $bulan_map = [
                'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            ];

            if (isset($bulan_map[$bulan])) {
                $bulan_angka = $bulan_map[$bulan];
                $query->whereMonth('tanggal_data_koperasi', $bulan_angka);

                // Opsional: filter per tahun saat ini
                $query->whereYear('tanggal_data_koperasi', date('Y'));
            }
        }

        $query->orderBy('tanggal_data_koperasi', 'asc');

        $data_koperasi = $query->paginate(10);
        
        // 👉 Cek apakah hasil pencarian kosong
        $dataKosong = $data_koperasi->isEmpty();

        // 👉 Deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($request->dari_tanggal) ||
                     !empty($request->sampai_tanggal) ||
                     !empty($request->bulan);

        // ✅ Tambahkan grouping per tanggal
        $grouped = $data_koperasi->getCollection()->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_data_koperasi)->translatedFormat('d F Y');
        });

        // ✅ Perhitungan total harga (supplier dan non-supplier)
        foreach ($data_koperasi as $item) {
            if ($item->id_informasi_supplier > 0) {
                // Barang dari supplier
                $item->total_harga_supplier = DB::table('barang_supplier')
                    ->where('id_informasi_supplier', $item->id_informasi_supplier)
                    ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                    ->sum(DB::raw('harga_barang_supplier'));
            } else {
                // Barang non-supplier, ambil dari harga_data_koperasi
                $item->total_harga_supplier = DB::table('barang_modal_keluar')
                    ->where('id_data_koperasi', $item->id_data_koperasi)
                    ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                    ->sum(DB::raw('harga_barang_modal_keluar'));
            }
        }

        return view('admin.data_koperasi.index_data_koperasi', compact(
            'data_koperasi',
            'dataKosong',
            'sudahCari',
            'grouped'
        ));
    }

    public function cetak_admin_data_koperasi(Request $request)
    {
        $dari_tanggal         = $request->dari_tanggal ? date('Y-m-d', strtotime($request->dari_tanggal)) : null;
        $sampai_tanggal       = $request->sampai_tanggal ? date('Y-m-d', strtotime($request->sampai_tanggal)) : null;
        $bulan                = $request->bulan;
        $jenis_data_koperasi  = $request->jenis_data_koperasi; 
    
        $query = DB::table('data_koperasi');
    
        if (!empty($jenis_transaksi)) {
            $query->where('jenis_data_koperasi', $jenis_data_koperasi);
        }

        if (!empty($dari_tanggal) && !empty($sampai_tanggal)) {
            $query->whereBetween('tanggal_data_koperasi', [$dari_tanggal, $sampai_tanggal]);
        } elseif (!empty($dari_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '>=', $dari_tanggal);
        } elseif (!empty($sampai_tanggal)) {
            $query->whereDate('tanggal_data_koperasi', '<=', $sampai_tanggal);
        }

        $data = $query->get();
        $total_pemasukan = DB::table('data_koperasi')
            ->where('jenis_data_koperasi', 'Pemasukan')
            ->sum('harga_data_koperasi');
    
        $total_pengeluaran = DB::table('data_koperasi')
            ->where('jenis_data_koperasi', 'Pengeluaran')
            ->sum('harga_data_koperasi');
    
        $sisa_dana = $total_pemasukan - $total_pengeluaran;
    
        return view('admin.data_koperasi.cetak_data_koperasi', compact('data','sisa_dana'));
    }

    public function store_admin_data_koperasi(Request $request)
    {
        // Ambil data admin yang sedang login
        $admin = Auth::guard('admin')->user();
        $nomor_dapur_admin = $admin->nomor_dapur_admin;

        $jenis_data_koperasi    = $request->jenis_data_koperasi;
        $kategori_data_koperasi = $request->kategori_data_koperasi;
        $tanggal_data_koperasi  = $request->tanggal_data_koperasi;
        $harga_data_koperasi    = $request->harga_data_koperasi;

        // Mulai transaksi database agar aman jika salah satu gagal
        DB::beginTransaction();

        try {
            // 1️⃣ Simpan data ke tabel data_koperasi
            $id_data_koperasi = DB::table('data_koperasi')->insertGetId([
                'nomor_dapur_data_koperasi' => $nomor_dapur_admin,
                'jenis_data_koperasi'       => $jenis_data_koperasi,
                'kategori_data_koperasi'    => $kategori_data_koperasi,
                'tanggal_data_koperasi'     => $tanggal_data_koperasi,
                'harga_data_koperasi'       => $harga_data_koperasi,
                'status_data_koperasi'      => 0, // default: menunggu validasi
            ]);

            // 2️⃣ Jika jenis_data_koperasi adalah "modal_masuk", tambahkan juga ke tabel keuangan
            if ($jenis_data_koperasi === 'modal_masuk') {
                DB::table('keuangan')->insert([
                    'id_data_koperasi'           => $id_data_koperasi,
                    'nomor_dapur_keuangan'       => $nomor_dapur_admin,
                    'tanggal_laporan_keuangan'   => $tanggal_data_koperasi,
                    'kategori_laporan_keuangan'  => $kategori_data_koperasi,
                    'jenis_transaksi'            => 'Pemasukan',
                    'jumlah_dana'                => $harga_data_koperasi,
                ]);
            } elseif ($jenis_data_koperasi === 'modal_keluar') {
                DB::table('keuangan')->insert([
                    'id_data_koperasi'           => $id_data_koperasi,
                    'nomor_dapur_keuangan'       => $nomor_dapur_admin,
                    'tanggal_laporan_keuangan'   => $tanggal_data_koperasi,
                    'kategori_laporan_keuangan'  => $kategori_data_koperasi,
                    'jenis_transaksi'            => 'Pengeluaran',
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

    public function edit_modal_masuk_admin_data_koperasi(Request $request)
    {
        $id = $request->id;
        $data_koperasi = DB::table('data_koperasi')->get();
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        return view('admin.data_koperasi.edit_modal_masuk_data_koperasi',compact('data_koperasi','data'));
    }


    public function update_modal_masuk_admin_data_koperasi($id, Request $request)
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




    public function tambah_admin_barang_modal_keluar(Request $request)
    {
        $id = $request->id;
        $data_koperasi = DB::table('data_koperasi')->get();
        $data = DB::table('data_koperasi')->where('id_data_koperasi', $id)->first();
        // Ambil semua data dapur
        $dapurList = DB::table('dapur')
            ->select('nomor_dapur', 'nama_dapur')
            ->groupBy('nomor_dapur', 'nama_dapur')
            ->get();
        return view('admin.data_koperasi.tambah_barang_modal_keluar',compact('data_koperasi','data','dapurList'));
    }

    public function store_admin_barang_modal_keluar(Request $request)
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




    public function edit_modal_keluar_admin_data_koperasi(Request $request)
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

        return view('admin.data_koperasi.edit_modal_keluar_data_koperasi', compact(
            'data_koperasi',
            'data',
            'barang_list'
        ));
    }



    public function update_modal_keluar_admin_data_koperasi($id, Request $request)
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

    public function lihat_admin_barang_modal_keluar(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $nomor_dapur_admin = $admin->nomor_dapur_admin;

        // id_data_koperasi dikirim dari request
        $id_data_koperasi = $request->id;

        // Cek data koperasi untuk ambil id_informasi_supplier-nya
        $data_koperasi = DB::table('data_koperasi')
            ->where('id_data_koperasi', $id_data_koperasi)
            ->first();

        $id_informasi_supplier = $data_koperasi->id_informasi_supplier ?? null;

        // Siapkan variabel barang_list
        $barang_list = collect();

        // 1) Coba ambil dari barang_supplier berdasar id_informasi_supplier
        $barang_list = DB::table('barang_supplier')
            ->join('informasi_supplier', 'informasi_supplier.id_informasi_supplier', '=', 'barang_supplier.id_informasi_supplier')
            ->where('barang_supplier.id_informasi_supplier', $id_informasi_supplier)
            ->where('barang_supplier.nomor_dapur_barang_supplier', $nomor_dapur_admin)
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
                ->where('barang_modal_keluar.nomor_dapur_barang_modal_keluar', $nomor_dapur_admin)
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
        return view('admin.data_koperasi.lihat_barang_modal_keluar', compact('barang_list'));
    }

    public function delete_admin_data_koperasi($id)
    {
        $delete = DB::table('data_koperasi')->where('id_data_koperasi', $id)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Berhasil Dihapus']);
        }
    }
}
