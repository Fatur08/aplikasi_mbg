<?php

namespace App\Http\Controllers;

use App\Models\InformasiPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class InformasiPengirimanController extends Controller
{
    // BAGIAN OWNER
    public function index_owner_pengiriman(Request $request)
    {        
        $nama_distributor = $request->cari_nama_distributor;
        $kecamatan_sekolah   = $request->cari_kecamatan_sekolah;
    
        $query = InformasiPengiriman::query();
    
        // filter berdasarkan kecamatan
        if (!empty($kecamatan_sekolah)) {
            $query->where('kecamatan_sekolah', 'like', '%' . $kecamatan_sekolah . '%');
        }
    
        // kalau user juga isi nama kepala dapur manual (opsional)
        if (!empty($nama_distributor)) {
            $query->where('nama_distributor', 'like', '%' . $nama_distributor . '%');
        }
    
        $distribusi = $query->paginate(300);
        $dataKosong = $distribusi->isEmpty();
    
        // 👉 ambil nama kepala dapur berdasarkan dapur_kecamatan
        if (!empty($kecamatan_sekolah) && !$dataKosong) {
            $nama_distributor = $distribusi->first()->nama_distributor;
        } else {
            $nama_distributor = '';
        }
    
        // deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($kecamatan_sekolah) || !empty($request->nama_distributor);
    
        return view('owner.informasi.pengiriman.index_pengiriman', compact(
            'distribusi',
            'nama_distributor',
            'kecamatan_sekolah',
            'dataKosong',
            'sudahCari'
        ));
    }



    // BAGIAN ADMIN
    public function index_admin_pengiriman(Request $request)
    {        
        $nama_distributor = $request->cari_nama_distributor;
        $kecamatan_sekolah   = $request->cari_kecamatan_sekolah;
    
        $query = InformasiPengiriman::query();
    
        // filter berdasarkan kecamatan
        if (!empty($kecamatan_sekolah)) {
            $query->where('kecamatan_sekolah', 'like', '%' . $kecamatan_sekolah . '%');
        }
    
        // kalau user juga isi nama kepala dapur manual (opsional)
        if (!empty($nama_distributor)) {
            $query->where('nama_distributor', 'like', '%' . $nama_distributor . '%');
        }
    
        $distribusi = $query->paginate(300);
        $dataKosong = $distribusi->isEmpty();
    
        // 👉 ambil nama kepala dapur berdasarkan dapur_kecamatan
        if (!empty($kecamatan_sekolah) && !$dataKosong) {
            $nama_distributor = $distribusi->first()->nama_distributor;
        } else {
            $nama_distributor = '';
        }
    
        // deteksi apakah user sudah melakukan pencarian
        $sudahCari = !empty($kecamatan_sekolah) || !empty($request->nama_distributor);
    
        return view('owner.informasi.pengiriman.index_pengiriman', compact(
            'distribusi',
            'nama_distributor',
            'kecamatan_sekolah',
            'dataKosong',
            'sudahCari'
        ));
    }
}
