<?php

namespace App\Http\Controllers;

use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DataMakerController extends Controller
{
    public function index_maker_data_staff_maker(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
        $cari_nama   = $request->cari_nama;

        // Query data staff maker
        $query = Maker::query();

        // Filter berdasarkan nomor dapur
        if ($nomor_dapur) {
            $query->where('nomor_dapur_maker', $nomor_dapur);
        }

        // Filter pencarian nama (jika ada)
        if (!empty($cari_nama)) {
            $query->where('nama_maker', 'like', '%' . $cari_nama . '%');
        }

        // Pagination
        $maker = $query->paginate(100);

        return view('maker.data_staff.maker.index_maker', compact('maker'));
    }





    public function store_maker_data_staff_maker(Request $request)
    {
        // Ambil data maker yang login
        $makerLogin = DB::table('maker')
            ->where('id_maker', auth()->id())
            ->first();

        $nomor_dapur = $makerLogin->nomor_dapur_maker ?? null;
    
        $nama_maker   = $request->nama_maker;
        $email_maker   = $request->email_maker;
        $alamat_maker   = $request->alamat_maker;
        $no_hp_maker   = $request->no_hp_maker;
        $foto_maker   = $request->foto_maker;
        $dapur = DB::table('dapur')
            ->where('nomor_dapur', $nomor_dapur)
            ->first();

        $kecamatan_maker = $dapur->nama_dapur ?? null;
        $password_maker = 12345;

        if($request->hasFile('foto_maker')){
            $foto_maker = $nama_maker.".".$request
                ->file('foto_maker')
                ->getClientOriginalExtension();
        } else {
            $foto_maker = null;
        }

        $data = [
            'nama_maker' => $nama_maker,
            'email_maker' => $email_maker,
            'alamat_maker' => $alamat_maker,
            'no_hp_maker' => $no_hp_maker,
            'foto_maker'=>$foto_maker,
            'kecamatan_maker' => $kecamatan_maker,
            'password_maker' => $password_maker
        ];

        $simpan = DB::table('maker')->insert($data);
        if ($simpan){
            if ($request->hasFile('foto_maker')) {
                $foto_maker = $nama_maker.".".$request
                    ->file('foto_maker')
                    ->getClientOriginalExtension();
                $storagePath = 'public/uploads/data_staff/maker/';
                $request->file('foto_maker')->storeAs($storagePath, $foto_maker);
                $publicPath = public_path('storage/uploads/data_staff/maker/');
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourceFile = storage_path('app/' . $storagePath . $foto_maker);
                $destinationFile = public_path('storage/uploads/data_staff/maker/' . $foto_maker);
                copy($sourceFile, $destinationFile);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }
}
