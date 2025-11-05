<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatDistributorController extends Controller
{
    public function index_riwayat_distributor(Request $request)
    {
        return view('distributor.riwayat_distributor.index_riwayat_distributor');
    }

    public function get_riwayat_distributor(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nomorDapurDistributor = Auth::guard('distributor')->user()->nomor_dapur_distributor;

        // Ambil data berdasarkan bulan dan tahun (opsional)
        $data = DB::table('distribusi')
            ->whereRaw('MONTH(tanggal_distribusi)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal_distribusi)="' . $tahun . '"')
            ->where('nomor_dapur_distribusi', $nomorDapurDistributor)
            ->orderBy('tanggal_distribusi')
            ->get();

        // Kirim ke tampilan partial
        return view('distributor.riwayat_distributor.get_riwayat_distributor', compact('data'));
    }
}
