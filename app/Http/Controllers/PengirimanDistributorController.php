<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PengirimanDistributorController extends Controller
{
    public function index_pengiriman_distributor(Request $request)
    {
        $distributor = Auth::guard('distributor')->user();
        $nomor_dapur_distributor = $distributor->nomor_dapur_distributor;

        $distribusi = DB::table('distribusi')
        ->where(function ($query) use ($distributor) {
            $query->where('nomor_dapur_distribusi', $distributor->nomor_dapur_distributor);
        })
        ->whereDate('tanggal_distribusi', now()->toDateString())
        ->orderByRaw("FIELD(status_distribusi, 0, 1, 2)")
        ->get();


        Carbon::setLocale('id');
        $tanggalSekarang = Carbon::now()->translatedFormat('l, d F Y');
        
        return view('distributor.pengiriman_distributor.index_pengiriman_distributor', compact(
            'distribusi',
            'tanggalSekarang'
        ));
    }
    
    
    public function konfirmasi_pengiriman_distributor($id_distribusi)
    {
        $distributor = Auth::guard('distributor')->user();
        $nomor_dapur_distributor = $distributor->nomor_dapur_distributor;

        // Ambil data distribusi berdasarkan ID dan nomor dapur distributor
        $distribusi = DB::table('distribusi')
            ->where('id_distribusi', $id_distribusi)
            ->where('nomor_dapur_distribusi', $nomor_dapur_distributor)
            ->first();

        // Jika tidak ditemukan
        if (!$distribusi) {
            return redirect()->back()->with('warning', 'Data distribusi tidak ditemukan.');
        }

        return view('distributor.pengiriman_distributor.konfirmasi_pengiriman_distributor', compact('distribusi'));
    }


    public function store_pengiriman_distributor(Request $request)
    {
        $id_distribusi = $request->id_distribusi;
        $lokasi_distribusi = $request->lokasi_distribusi;

        $distributor = Auth::guard('distributor')->user();
        $nomor_dapur_distributor = $distributor->nomor_dapur_distributor;

        // Ambil data distribusi
        $distribusi = DB::table('distribusi')
            ->where('id_distribusi', $id_distribusi)
            ->where('nomor_dapur_distribusi', $nomor_dapur_distributor)
            ->first();

        if (!$distribusi) {
            return Redirect::back()->with(['error' => 'Data distribusi tidak ditemukan.']);
        }

        $nama_distributor = $distribusi->nama_distributor ?? 'Distributor';
        $old_bukti_pengiriman = $distribusi->bukti_pengiriman ?? null;
        $bukti_pengiriman = $old_bukti_pengiriman;

        // Jika ada file baru diupload
        if ($request->hasFile('bukti_pengiriman')) {
            $file = $request->file('bukti_pengiriman');
            $timestamp = now()->format('Y-m-d_H-i-s');
            $nama_file = "Bukti Terima_{$nama_distributor}_{$timestamp}." . $file->getClientOriginalExtension();
            $folderpath = 'public/uploads/bukti_pengiriman';

            // Hapus file lama (jika ada)
            if ($old_bukti_pengiriman) {
                $oldPath = 'uploads/bukti_pengiriman/' . $old_bukti_pengiriman;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                // fallback: jika kamu punya salinan di public/storage (copy sebelumnya), hapus juga
                $publicCopy = public_path('storage/uploads/bukti_pengiriman/' . $old_bukti_pengiriman);
                if (file_exists($publicCopy)) {
                    @unlink($publicCopy);
                }
            }

            // Simpan file baru ke storage
            $file->storeAs($folderpath, $nama_file);

            // Salin ke public path untuk akses web
            $publicDir = public_path('storage/uploads/bukti_pengiriman');
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0777, true);
            }
            copy(storage_path('app/' . $folderpath . '/' . $nama_file), $publicDir . '/' . $nama_file);

            $bukti_pengiriman = $nama_file;
        }

        try {
            $data = [
                'lokasi_distribusi' => $lokasi_distribusi,
                'bukti_pengiriman' => $bukti_pengiriman,
                'status_distribusi' => 1
            ];

            $update = DB::table('distribusi')
                ->where('id_distribusi', $id_distribusi)
                ->update($data);

            if ($update) {
                return Redirect::back()->with(['success' => 'Konfirmasi pengiriman berhasil dikirim.']);
            } else {
                return Redirect::back()->with(['warning' => 'Data gagal disimpan atau tidak berubah.']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function lihat_bukti_pengiriman(Request $request)
    {        
        $id = $request->id;
        $distribusi = DB::table('distribusi')->get();
        $data = DB::table('distribusi')->where('id_distribusi', $id)->first();
        return view('distributor.pengiriman_distributor.lihat_bukti_pengiriman',compact('distribusi','data'));
    }
}
