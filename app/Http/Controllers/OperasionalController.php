<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class OperasionalController extends Controller
{
    // OWNER
    // Informasi Operasional
    public function index_owner_informasi_operasional(Request $request)
    {
        $owner = Auth::guard('owner')->user();

        // CEK APAKAH AKUN SUKADANA ILIR ATAU BUKAN 
        $allowedOwner = 2;

        if (!($owner->id == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        $query = DB::table('informasi_operasional')
            ->where('id_owner', $owner->id);

        // 🔍 Filter pencarian
        if ($request->filled('cari_jenis_informasi_operasional')) {
            $query->where('jenis_informasi_operasional', 'like', '%' . $request->cari_jenis_informasi_operasional . '%');
        }

        $data = $query->orderBy('jenis_informasi_operasional', 'asc')->get();

        return view('owner.operasional.informasi_operasional.index_informasi_operasional', compact('data'));
    }



    // Laporan Operasional
    public function index_owner_laporan_operasional(Request $request)
    {
        $owner = Auth::guard('owner')->user();

        // CEK APAKAH AKUN SUKADANA ILIR ATAU BUKAN 
        $allowedOwner = 2;

        if (!($owner->id == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        // ✅ Dropdown jenis operasional
        $jenisOperasional = DB::table('informasi_operasional')
            ->select('id_informasi_operasional', 'jenis_informasi_operasional')
            ->where('id_owner', $owner->id_owner)
            ->where('nomor_dapur_informasi_operasional', $owner->nomor_dapur_owner)
            ->distinct()
            ->get();

        // ✅ Query dasar laporan
        $laporanQuery = DB::table('laporan_operasional')
            ->join(
                'informasi_operasional',
                'laporan_operasional.id_informasi_operasional',
                '=',
                'informasi_operasional.id_informasi_operasional'
            )
            ->where('laporan_operasional.id_owner', $owner->id_owner)
            ->where('laporan_operasional.nomor_dapur_laporan_operasional', $owner->nomor_dapur_owner);

        // 🔍 FILTER TANGGAL (fleksibel)
        if ($request->filled('dari_tanggal')) {
            $dari = Carbon::parse($request->dari_tanggal)->format('Y-m-d');
            $laporanQuery->whereDate('laporan_operasional.tanggal_laporan_operasional', '>=', $dari);
        }

        if ($request->filled('sampai_tanggal')) {
            $sampai = Carbon::parse($request->sampai_tanggal)->format('Y-m-d');
            $laporanQuery->whereDate('laporan_operasional.tanggal_laporan_operasional', '<=', $sampai);
        }

        // ✅ Eksekusi query
        $laporan = $laporanQuery
            ->select(
                'laporan_operasional.*',
                'informasi_operasional.jenis_informasi_operasional'
            )
            ->orderBy('laporan_operasional.tanggal_laporan_operasional', 'desc')
            ->get();

        return view(
            'owner.operasional.laporan_operasional.index_laporan_operasional',
            compact('jenisOperasional', 'laporan')
        );
    }




































    // MAKER
    // Informasi Operasional
    public function index_maker_informasi_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        // CEK APAKAH AKUN SUKADANA ILIR ATAU BUKAN 
        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        $query = DB::table('informasi_operasional')
            ->where('id_owner', $maker->id_owner)
            ->where('nomor_dapur_informasi_operasional', $maker->nomor_dapur_maker);

        // 🔍 Filter pencarian
        if ($request->filled('cari_jenis_informasi_operasional')) {
            $query->where('jenis_informasi_operasional', 'like', '%' . $request->cari_jenis_informasi_operasional . '%');
        }

        $data = $query->orderBy('jenis_informasi_operasional', 'asc')->get();

        return view('maker.operasional.informasi_operasional.index_informasi_operasional', compact('data'));
    }


    public function store_maker_informasi_operasional(Request $request)
    {
        // Ambil data maker login
        $maker = Auth::guard('maker')->user();

        // Simpan ke tabel informasi_operasional
        DB::table('informasi_operasional')->insert([
            'id_owner' => $maker->id_owner,
            'nomor_dapur_informasi_operasional' => $maker->nomor_dapur_maker,

            'jenis_informasi_operasional' => $request->jenis_informasi_operasional,
            'jumlah_jenis_informasi_operasional' => $request->jumlah_jenis_informasi_operasional,
            'harga_satuan_informasi_operasional' => $request->harga_satuan_informasi_operasional
        ]);

        return redirect()->back()->with('success', 'Data operasional berhasil disimpan');
    }




    public function edit_maker_informasi_operasional(Request $request)
    {
        $id = $request->id;
        $informasi_operasional = DB::table('informasi_operasional')->get();
        $data = DB::table('informasi_operasional')->where('id_informasi_operasional', $id)->first();
        return view('maker.operasional.informasi_operasional.edit_informasi_operasional', compact('informasi_operasional', 'data'));
    }



    public function update_maker_informasi_operasional(Request $request, $id)
    {
        // 🔥 Update data
        DB::table('informasi_operasional')
            ->where('id_informasi_operasional', $id)
            ->update([
                'jenis_informasi_operasional' => $request->edit_jenis_informasi_operasional,
                'jumlah_jenis_informasi_operasional' => $request->edit_jumlah_jenis_informasi_operasional,
                'harga_satuan_informasi_operasional' => $request->edit_harga_satuan_informasi_operasional,
            ]);

        // 🔥 Redirect / response
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }


    public function delete_maker_informasi_operasional(Request $request, $id)
    {
        // 🔥 Cek apakah data ada
        $data = DB::table('informasi_operasional')
            ->where('id_informasi_operasional', $id)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // 🔥 Hapus data
        DB::table('informasi_operasional')
            ->where('id_informasi_operasional', $id)
            ->delete();

        // 🔥 Redirect
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }










    // Laporan Operasional
    public function index_maker_laporan_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        // 🔒 Validasi akses
        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        // ✅ Dropdown jenis operasional
        $jenisOperasional = DB::table('informasi_operasional')
            ->select('id_informasi_operasional', 'jenis_informasi_operasional')
            ->where('id_owner', $maker->id_owner)
            ->where('nomor_dapur_informasi_operasional', $maker->nomor_dapur_maker)
            ->distinct()
            ->get();

        // ✅ Query dasar laporan
        $laporanQuery = DB::table('laporan_operasional')
            ->join(
                'informasi_operasional',
                'laporan_operasional.id_informasi_operasional',
                '=',
                'informasi_operasional.id_informasi_operasional'
            )
            ->where('laporan_operasional.id_owner', $maker->id_owner)
            ->where('laporan_operasional.nomor_dapur_laporan_operasional', $maker->nomor_dapur_maker);

        // 🔍 FILTER TANGGAL (fleksibel)
        if ($request->filled('dari_tanggal')) {
            $dari = Carbon::parse($request->dari_tanggal)->format('Y-m-d');
            $laporanQuery->whereDate('laporan_operasional.tanggal_laporan_operasional', '>=', $dari);
        }

        if ($request->filled('sampai_tanggal')) {
            $sampai = Carbon::parse($request->sampai_tanggal)->format('Y-m-d');
            $laporanQuery->whereDate('laporan_operasional.tanggal_laporan_operasional', '<=', $sampai);
        }

        // ✅ Eksekusi query
        $laporan = $laporanQuery
            ->select(
                'laporan_operasional.*',
                'informasi_operasional.jenis_informasi_operasional'
            )
            ->orderBy('laporan_operasional.tanggal_laporan_operasional', 'desc')
            ->get();

        return view(
            'maker.operasional.laporan_operasional.index_laporan_operasional',
            compact('jenisOperasional', 'laporan')
        );
    }



    public function store_maker_laporan_operasional(Request $request)
    {
        $maker = Auth::guard('maker')->user();

        // 🔒 Validasi akses
        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        // ✅ Upload file (jika ada)
        if ($request->hasFile('nota_laporan_operasional')) {
            $file = $request->file('nota_laporan_operasional');
            $timestamp = date('Ymd_His'); // contoh: 20260426_153045
            $nota_laporan_operasional = "Nota_Laporan_Operasional_" . $timestamp . "." . $file->getClientOriginalExtension();
        } else {
            $nota_laporan_operasional = null;
        }

        // ✅ Simpan ke database
        $data = [
            'id_owner' => $maker->id_owner,
            'nomor_dapur_laporan_operasional' => $maker->nomor_dapur_maker,

            'tanggal_laporan_operasional' => $request->tanggal_laporan_operasional,
            'id_informasi_operasional' => $request->id_informasi_operasional,

            'jumlah_laporan_operasional' => $request->jumlah_laporan_operasional,
            'beli_laporan_operasional' => $request->beli_laporan_operasional,
            'jual_laporan_operasional' => $request->jual_laporan_operasional,

            'nota_laporan_operasional' => $nota_laporan_operasional
        ];

        $simpan = DB::table('laporan_operasional')->insert($data);
        if ($simpan) {
            if ($request->hasFile('nota_laporan_operasional')) {
                $storagePath = 'public/uploads/maker/operasional/laporan_operasional/';
                $request->file('nota_laporan_operasional')->storeAs($storagePath, $nota_laporan_operasional);
                $publicPath = public_path('storage/uploads/maker/operasional/laporan_operasional/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $nota_laporan_operasional);
                $destinationFile = public_path('storage/uploads/maker/operasional/laporan_operasional/' . $nota_laporan_operasional);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }







    public function nota_maker_laporan_operasional(Request $request)
    {
        $id = $request->id;
        $laporan_operasional = DB::table('laporan_operasional')->get();
        $data = DB::table('laporan_operasional')->where('id_laporan_operasional', $id)->first();
        return view('maker.operasional.laporan_operasional.nota_laporan_operasional', compact('laporan_operasional', 'data'));
    }







    public function edit_maker_laporan_operasional(Request $request)
    {
        $id = $request->id;

        $data = DB::table('laporan_operasional')
            ->where('id_laporan_operasional', $id)
            ->first();

        // 🔥 INI YANG KURANG
        $jenisOperasional = DB::table('informasi_operasional')->get();

        return view(
            'maker.operasional.laporan_operasional.edit_laporan_operasional',
            compact('data', 'jenisOperasional')
        );
    }




    public function update_maker_laporan_operasional($id, Request $request)
    {
        $edit_tanggal_laporan_operasional = $request->edit_tanggal_laporan_operasional;
        $edit_id_informasi_operasional = $request->edit_id_informasi_operasional;
        $edit_jumlah_laporan_operasional = $request->edit_jumlah_laporan_operasional;
        $edit_beli_laporan_operasional = $request->edit_beli_laporan_operasional;
        $edit_jual_laporan_operasional = $request->edit_jual_laporan_operasional;


        // Ambil data laporan operasional
        $laporan_operasional = DB::table('laporan_operasional')
            ->where('id_laporan_operasional', $id)
            ->first();



        // --- Handle Nota Laporan Operasional ---
        if ($request->hasFile('edit_nota_laporan_operasional')) {
            $file = $request->file('edit_nota_laporan_operasional');
            $timestamp = date('Ymd_His'); // contoh: 20260426_153045
            $newNota = "Nota_Laporan_Operasional_" . $timestamp . "." . $file->getClientOriginalExtension();


            $folderNota = "public/uploads/maker/operasional/laporan_operasional/";
            $oldFile = $folderNota . $laporan_operasional->nota_laporan_operasional;
            $newFile = $folderNota . $newNota;

            if (Storage::exists($oldFile)) {
                Storage::delete($oldFile);
            }

            // hapus file lama di public 🔥 (INI YANG KURANG)
            $oldPublicFile = public_path('storage/uploads/maker/operasional/laporan_operasional/' . $laporan_operasional->nota_laporan_operasional);
            if (file_exists($oldPublicFile)) {
                unlink($oldPublicFile);
            }


            $file->storeAs($folderNota, $newNota);
            $publicPath = public_path('storage/uploads/maker/operasional/laporan_operasional/');
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
            $sourceFile = storage_path('app/' . $folderNota . $newNota);
            $destinationFile = public_path('storage/uploads/maker/operasional/laporan_operasional/' . $newNota);
            copy($sourceFile, $destinationFile);
        } else {
            $newNota = $laporan_operasional->nota_laporan_operasional;
        }

        try {
            // Update Laporan Operasional
            $data = [
                'tanggal_laporan_operasional' => $edit_tanggal_laporan_operasional,
                'id_informasi_operasional' => $edit_id_informasi_operasional,
                'jumlah_laporan_operasional' => $edit_jumlah_laporan_operasional,
                'beli_laporan_operasional' => $edit_beli_laporan_operasional,
                'jual_laporan_operasional' => $edit_jual_laporan_operasional,
                'nota_laporan_operasional' => $newNota,
                'validasi_laporan_operasional' => 0
            ];
            $update = DB::table('laporan_operasional')->where('id_laporan_operasional', $id)->update($data);

            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }






    public function delete_maker_laporan_operasional(Request $request, $id)
    {
        // 🔥 Cek apakah data ada
        $data = DB::table('laporan_operasional')
            ->where('id_laporan_operasional', $id)
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // 🔥 Hapus data
        DB::table('laporan_operasional')
            ->where('id_laporan_operasional', $id)
            ->delete();

        // 🔥 Redirect
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
