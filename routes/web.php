<?php

use App\Http\Controllers\MakerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\DataKoperasiController;
use App\Http\Controllers\DataSupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAhliGiziController;
use App\Http\Controllers\DataAkuntanController;
use App\Http\Controllers\DataStaffController;
use App\Http\Controllers\DataAslapController;
use App\Http\Controllers\DataMakerController;
use App\Http\Controllers\DataRelawanController;
use App\Http\Controllers\DataSPPIController;
use App\Http\Controllers\DataDriverController;
use App\Http\Controllers\InformasiMenuHarianController;
use App\Http\Controllers\InformasiPengirimanController;
use App\Http\Controllers\InformasiStokLimitController;
use App\Http\Controllers\KepalaDapurController;
use App\Http\Controllers\LaporanDistribusiController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanDapurController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\LaporanSupplierController;
use App\Http\Controllers\MenuHarianController;
use App\Http\Controllers\PengirimanDataDriverController;
use App\Http\Controllers\ProfilDataDriverController;
use App\Http\Controllers\RiwayatDataDriverController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokLimitController;
use App\Http\Controllers\StokMasukController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('login');


Route::middleware(['guest:owner'])->group(function() {
    Route::get('/owner', function () {
        return view('auth.loginowner');
    })->name('loginowner');
    Route::post('/prosesloginowner', [AuthController::class,'prosesloginowner']);
});



Route::middleware(['guest:maker'])->group(function() {
    Route::get('/maker', function () {
        return view('auth.loginmaker');
    })->name('loginmaker');
    Route::post('/prosesloginmaker', [AuthController::class,'prosesloginmaker']);
});


Route::middleware(['guest:kepala_dapur'])->group(function() {
    Route::get('/kepala_dapur', function () {
        return view('auth.loginkepaladapur');
    })->name('loginkepaladapur');
    Route::post('/prosesloginkepaladapur', [AuthController::class,'prosesloginkepaladapur']);
});


Route::middleware(['guest:distributor'])->group(function() {
    Route::get('/distributor', function () {
        return view('auth.logindistributor');
    })->name('logindistributor');
    Route::post('/proseslogindistributor', [AuthController::class,'proseslogindistributor']);
});



Route::middleware(['auth:owner'])->group(function(){
    Route::get('/proseslogoutowner', [AuthController::class,'proseslogoutowner']);
    Route::get('/owner/dashboardowner',[DashboardController::class,'dashboardowner']);

    // Data Staff
    Route::get('/owner/data_staff/',[DataStaffController::class,'index_owner_data_staff']);
    // Data Maker
    Route::get('/owner/data_staff/maker',[DataMakerController::class,'index_owner_data_staff_maker']);
    Route::post('/owner/data_staff/maker/store_maker',[DataMakerController::class,'store_owner_data_staff_maker']);
    Route::post('/owner/data_staff/maker/validasi_maker',[DataMakerController::class,'validasi_owner_data_staff_maker']);
    Route::post('/owner/data_staff/maker/{id}/update_validasi_maker',[DataMakerController::class,'update_owner_validasi_maker']);
    Route::post('/owner/data_staff/maker/{id}/batalkan_validasi_maker',[DataMakerController::class,'batalkan_owner_validasi_maker']);

    // SPPI
    Route::get('/owner/data_staff/sppi',[DataSPPIController::class,'index_owner_data_staff_sppi']);
    Route::post('/owner/data_staff/sppi/store_sppi',[DataSPPIController::class,'store_owner_data_staff_sppi']);
    Route::post('/owner/data_staff/sppi/validasi_sppi',[DataSPPIController::class,'validasi_owner_data_staff_sppi']);
    Route::post('/owner/data_staff/sppi/{id}/update_validasi_sppi',[DataSPPIController::class,'update_owner_validasi_sppi']);
    Route::post('/owner/data_staff/sppi/{id}/batalkan_validasi_sppi',[DataSPPIController::class,'batalkan_owner_validasi_sppi']);
    
    // Ahli Gizi
    Route::get('/owner/data_staff/ahli_gizi',[DataAhliGiziController::class,'index_owner_data_staff_ahli_gizi']);
    Route::post('/owner/data_staff/ahli_gizi/store_ahli_gizi',[DataAhliGiziController::class,'store_owner_data_staff_ahli_gizi']);
    Route::post('/owner/data_staff/ahli_gizi/validasi_ahli_gizi',[DataAhliGiziController::class,'validasi_owner_data_staff_ahli_gizi']);
    Route::post('/owner/data_staff/ahli_gizi/{id}/update_validasi_ahli_gizi',[DataAhliGiziController::class,'update_owner_validasi_ahli_gizi']);
    Route::post('/owner/data_staff/ahli_gizi/{id}/batalkan_validasi_ahli_gizi',[DataAhliGiziController::class,'batalkan_owner_validasi_ahli_gizi']);

    // Akuntan
    Route::get('/owner/data_staff/akuntan',[DataAkuntanController::class,'index_owner_data_staff_akuntan']);
    Route::post('/owner/data_staff/akuntan/store_akuntan',[DataAkuntanController::class,'store_owner_data_staff_akuntan']);
    Route::post('/owner/data_staff/akuntan/validasi_akuntan',[DataAkuntanController::class,'validasi_owner_data_staff_akuntan']);
    Route::post('/owner/data_staff/akuntan/{id}/update_validasi_akuntan',[DataAkuntanController::class,'update_owner_validasi_akuntan']);
    Route::post('/owner/data_staff/akuntan/{id}/batalkan_validasi_akuntan',[DataAkuntanController::class,'batalkan_owner_validasi_akuntan']);

    // Data Aslap
    Route::get('/owner/data_staff/aslap',[DataAslapController::class,'index_owner_data_staff_aslap']);
    Route::post('/owner/data_staff/aslap/store_aslap',[DataAslapController::class,'store_owner_data_staff_aslap']);
    Route::post('/owner/data_staff/aslap/validasi_aslap',[DataAslapController::class,'validasi_owner_data_staff_aslap']);
    Route::post('/owner/data_staff/aslap/{id}/update_validasi_aslap',[DataAslapController::class,'update_owner_validasi_aslap']);
    Route::post('/owner/data_staff/aslap/{id}/batalkan_validasi_aslap',[DataAslapController::class,'batalkan_owner_validasi_aslap']);
    //Route::post('/owner/data_staff/aslap/edit_aslap',[DataAslapController::class,'edit_owner_aslap']);
    //Route::post('/owner/data_staff/aslap/ktp_aslap',[DataAslapController::class,'ktp_owner_aslap']);
    //Route::post('/owner/data_staff/aslap/{id}/update_aslap',[DataAslapController::class,'update_owner_aslap']);
    //Route::post('/owner/data_staff/aslap/{id}/delete_aslap',[DataAslapController::class,'delete_owner_aslap']);
    //Route::get('/owner/data_staff',[DataStaffController::class,'index_owner_data_staff']);
    
    // Data Driver
    Route::get('/owner/data_staff/driver',[DataDriverController::class,'index_owner_data_staff_driver']);
    Route::post('/owner/data_staff/driver/store_driver',[DataDriverController::class,'store_owner_data_staff_driver']);
    Route::post('/owner/data_staff/driver/validasi_driver',[DataDriverController::class,'validasi_owner_data_staff_driver']);
    Route::post('/owner/data_staff/driver/{id}/update_validasi_driver',[DataDriverController::class,'update_owner_validasi_driver']);
    Route::post('/owner/data_staff/driver/{id}/batalkan_validasi_driver',[DataDriverController::class,'batalkan_owner_validasi_driver']);
    //Route::post('/owner/data_staff/driver/edit_driver',[DataDriverController::class,'edit_owner_data_staff_driver']);
    //Route::post('/owner/data_staff/driver/{id}/update_driver',[DataDriverController::class,'update_owner_data_staff_driver']);
    //Route::post('/owner/data_staff/driver/{id}/delete_driver',[DataDriverController::class,'delete_owner_data_staff_driver']);

    // Relawan
    Route::get('/owner/data_staff/relawan',[DataRelawanController::class,'index_owner_data_staff_relawan']);
    Route::post('/owner/data_staff/relawan/store_relawan',[DataRelawanController::class,'store_owner_data_staff_relawan']);
    Route::post('/owner/data_staff/relawan/ktp_relawan',[DataRelawanController::class,'ktp_owner_data_staff_relawan']);
    Route::post('/owner/data_staff/relawan/validasi_relawan',[DataRelawanController::class,'validasi_owner_data_staff_relawan']);
    Route::post('/owner/data_staff/relawan/{id}/update_validasi_relawan',[DataRelawanController::class,'update_owner_validasi_relawan']);
    Route::post('/owner/data_staff/relawan/{id}/batalkan_validasi_relawan',[DataRelawanController::class,'batalkan_owner_validasi_relawan']);


    //Dapur
    Route::get('/owner/data_staff/dapur',[DapurController::class,'index_owner_dapur']);
    Route::post('/owner/data_staff/dapur/store_dapur',[DapurController::class,'store_owner_dapur']);
    Route::post('/owner/data_staff/dapur/{id}/delete_dapur',[DapurController::class,'delete_owner_dapur']);

    //Data Supplier
    //Supplier
    Route::get('/owner/data_supplier/supplier',[DataSupplierController::class,'index_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/store_supplier',[DataSupplierController::class,'store_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/edit_supplier',[DataSupplierController::class,'edit_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/validasi_supplier',[DataSupplierController::class,'validasi_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/{id}/update_validasi_supplier',[DataSupplierController::class,'update_validasi_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/{id}/batalkan_validasi_supplier',[DataSupplierController::class,'batalkan_validasi_owner_supplier']);
    //Route::post('/owner/data_supplier/supplier/{id}/update_supplier',[DataSupplierController::class,'update_owner_supplier']);
    Route::post('/owner/data_supplier/supplier/{id}/delete_supplier',[DataSupplierController::class,'delete_owner_supplier']);

    //Informasi Supplier
    Route::get('/owner/data_supplier/informasi_supplier',[DataSupplierController::class,'index_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/store_informasi_supplier',[DataSupplierController::class,'store_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/nota_informasi_supplier',[DataSupplierController::class,'nota_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/bukti_terima_informasi_supplier',[DataSupplierController::class,'bukti_terima_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/validasi_informasi_supplier',[DataSupplierController::class,'validasi_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/{id}/update_validasi_informasi_supplier',[DataSupplierController::class,'update_validasi_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/{id}/batalkan_validasi_informasi_supplier',[DataSupplierController::class,'batalkan_validasi_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/edit_informasi_supplier',[DataSupplierController::class,'edit_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/{id}/update_informasi_supplier',[DataSupplierController::class,'update_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/{id}/delete_informasi_supplier',[DataSupplierController::class,'delete_owner_informasi_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/tambah_barang_supplier',[DataSupplierController::class,'tambah_owner_barang_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/{id}/store_barang_supplier',[DataSupplierController::class,'store_owner_barang_supplier']);
    Route::post('/owner/data_supplier/informasi_supplier/lihat_barang_supplier',[DataSupplierController::class,'lihat_owner_barang_supplier']);
    

    //Data Koperasi
    Route::get('/owner/data_koperasi',[DataKoperasiController::class,'index_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/store_data_koperasi',[DataKoperasiController::class,'store_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/edit_modal_masuk_data_koperasi',[DataKoperasiController::class,'edit_modal_masuk_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/{id}/update_modal_masuk_data_koperasi',[DataKoperasiController::class,'update_modal_masuk_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/edit_modal_keluar_data_koperasi',[DataKoperasiController::class,'edit_modal_keluar_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/{id}/update_modal_keluar_data_koperasi',[DataKoperasiController::class,'update_modal_keluar_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/lihat_barang_modal_keluar',[DataKoperasiController::class,'lihat_owner_barang_modal_keluar']);
    Route::get('/owner/data_koperasi/cetak_data_koperasi',[DataKoperasiController::class,'cetak_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/validasi_data_koperasi',[DataKoperasiController::class,'validasi_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/{id}/update_validasi_data_koperasi',[DataKoperasiController::class,'update_validasi_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/{id}/batalkan_validasi_data_koperasi',[DataKoperasiController::class,'batalkan_validasi_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/{id}/delete_data_koperasi',[DataKoperasiController::class,'delete_owner_data_koperasi']);
    Route::post('/owner/data_koperasi/tambah_barang_modal_keluar',[DataKoperasiController::class,'tambah_owner_barang_modal_keluar']);
    Route::post('/owner/data_koperasi/{id}/store_barang_modal_keluar',[DataKoperasiController::class,'store_owner_barang_modal_keluar']);

    //Informasi
    //Menu Harian
    Route::get('/owner/informasi/menu_harian',[InformasiMenuHarianController::class,'index_owner_menu_harian']);

    //Stok Limit
    Route::get('/owner/informasi/stok_limit',[InformasiStokLimitController::class,'index_owner_stok_limit']);

    //Pengiriman
    Route::get('/owner/informasi/pengiriman',[InformasiPengirimanController::class,'index_owner_pengiriman']);


    //Laporan
    //Stok
    Route::get('/owner/laporan/stok',[LaporanStokController::class,'index_owner_laporan_stok']);

    //Stok Harian
    Route::get('/owner/laporan/stok_harian',[LaporanStokController::class,'index_owner_laporan_stok_harian']);

    //Stok Bulanan
    Route::get('/owner/laporan/stok_bulanan',[LaporanStokController::class,'index_owner_laporan_stok_bulanan']);

    //Distribusi
    Route::get('/owner/laporan/distribusi',[LaporanDistribusiController::class,'index_owner_laporan_distribusi']);
    Route::post('/owner/laporan/distribusi/edit_laporan_distribusi',[LaporanDistribusiController::class,'edit_owner_laporan_distribusi']);
    Route::post('/owner/laporan/distribusi/bukti_pengiriman',[LaporanDistribusiController::class,'bukti_owner_pengiriman']);
    Route::post('/owner/laporan/distribusi/kendala_distribusi',[LaporanDistribusiController::class,'kendala_owner_distribusi']);
    Route::post('/owner/laporan/distribusi/{id}/update_laporan_distribusi',[LaporanDistribusiController::class,'update_owner_laporan_distribusi']);
    Route::get('/owner/laporan/distribusi/{id}/batalkan_distribusi',[LaporanDistribusiController::class,'batalkan_owner_distribusi']);
    Route::post('/owner/laporan/distribusi/{id}/delete_laporan_distribusi',[LaporanDistribusiController::class,'delete_owner_laporan_distribusi']);

    //Keuangan
    Route::get('/owner/laporan/keuangan',[LaporanKeuanganController::class,'index_owner_laporan_keuangan']);
    Route::post('/owner/laporan/keuangan/store_laporan_keuangan',[LaporanKeuanganController::class,'store_owner_laporan_keuangan']);
    Route::post('/owner/laporan/keuangan/edit_laporan_keuangan',[LaporanKeuanganController::class,'edit_owner_laporan_keuangan']);
    Route::get('/owner/laporan/keuangan/cetak_laporan_keuangan',[LaporanKeuanganController::class,'cetak_owner_laporan_keuangan']);
    Route::post('/owner/laporan/keuangan/{id}/update_laporan_keuangan',[LaporanKeuanganController::class,'update_owner_laporan_keuangan']);
    Route::post('/owner/laporan/keuangan/{id}/delete_laporan_keuangan',[LaporanKeuanganController::class,'delete_owner_laporan_keuangan']);

    //Harian Dapur
    Route::get('/owner/laporan/dapur',[LaporanDapurController::class,'index_owner_dapur']);
    Route::post('/owner/laporan/dapur/lihat_bahan_terpakai',[LaporanDapurController::class,'lihat_bahan_terpakai']);
    Route::post('/owner/laporan/dapur/lihat_kendala',[LaporanDapurController::class,'lihat_kendala']);
    Route::post('/owner/laporan/dapur/kendala_dapur',[LaporanDapurController::class,'kendala_owner_dapur']);
});


Route::middleware(['auth:maker'])->group(function(){
    Route::get('/proseslogoutmaker', [AuthController::class,'proseslogoutmaker']);
    Route::get('/maker/dashboardmaker',[DashboardController::class,'dashboardmaker']);
    //Route::get('/maker/dashboardmaker',[LaporanDistribusiController::class,'index_maker_laporan_distribusi']);


    // Data Staff
    Route::get('/maker/data_staff/',[DataStaffController::class,'index_maker_data_staff']);
    // Data Maker
    Route::get('/maker/data_staff/maker',[DataMakerController::class,'index_maker_data_staff_maker']);
    Route::post('/maker/data_staff/maker/store_maker',[DataMakerController::class,'store_maker_data_staff_maker']);

    // SPPI
    Route::get('/maker/data_staff/sppi',[DataSPPIController::class,'index_maker_data_staff_sppi']);
    Route::post('/maker/data_staff/sppi/store_sppi',[DataSPPIController::class,'store_maker_data_staff_sppi']);
    
    // Ahli Gizi
    Route::get('/maker/data_staff/ahli_gizi',[DataAhliGiziController::class,'index_maker_data_staff_ahli_gizi']);
    Route::post('/maker/data_staff/ahli_gizi/store_ahli_gizi',[DataAhliGiziController::class,'store_maker_data_staff_ahli_gizi']);

    // Akuntan
    Route::get('/maker/data_staff/akuntan',[DataAkuntanController::class,'index_maker_data_staff_akuntan']);
    Route::post('/maker/data_staff/akuntan/store_akuntan',[DataAkuntanController::class,'store_maker_data_staff_akuntan']);

    // Data Aslap
    Route::get('/maker/data_staff/aslap',[DataAslapController::class,'index_maker_data_staff_aslap']);
    Route::post('/maker/data_staff/aslap/store_aslap',[DataAslapController::class,'store_maker_data_staff_aslap']);
    //Route::post('/maker/data_staff/aslap/edit_aslap',[DataAslapController::class,'edit_maker_aslap']);
    //Route::post('/maker/data_staff/aslap/ktp_aslap',[DataAslapController::class,'ktp_maker_aslap']);
    //Route::post('/maker/data_staff/aslap/{id}/update_aslap',[DataAslapController::class,'update_maker_aslap']);
    //Route::post('/maker/data_staff/aslap/{id}/delete_aslap',[DataAslapController::class,'delete_maker_aslap']);
    //Route::get('/maker/data_staff',[DataStaffController::class,'index_maker_data_staff']);
    
    // Data Driver
    Route::get('/maker/data_staff/driver',[DataDriverController::class,'index_maker_data_staff_driver']);
    Route::post('/maker/data_staff/driver/store_driver',[DataDriverController::class,'store_maker_data_staff_driver']);
    //Route::post('/maker/data_staff/driver/edit_driver',[DataDriverController::class,'edit_maker_data_staff_driver']);
    //Route::post('/maker/data_staff/driver/{id}/update_driver',[DataDriverController::class,'update_maker_data_staff_driver']);
    //Route::post('/maker/data_staff/driver/{id}/delete_driver',[DataDriverController::class,'delete_maker_data_staff_driver']);

    // Relawan
    Route::get('/maker/data_staff/relawan',[DataRelawanController::class,'index_maker_data_staff_relawan']);
    Route::post('/maker/data_staff/relawan/store_relawan',[DataRelawanController::class,'store_maker_data_staff_relawan']);
    Route::post('/maker/data_staff/relawan/ktp_relawan',[DataRelawanController::class,'ktp_maker_data_staff_relawan']);

    
    //Kepala Dapur
    //Route::get('/maker/data_staff/kepala_dapur',[KepalaDapurController::class,'index_maker_kepala_dapur']);
    //Route::post('/maker/data_staff/kepala_dapur/store_kepala_dapur',[KepalaDapurController::class,'store_maker_kepala_dapur']);
    //Route::post('/maker/data_staff/kepala_dapur/edit_kepala_dapur',[KepalaDapurController::class,'edit_maker_kepala_dapur']);
    //Route::post('/maker/data_staff/kepala_dapur/{id}/update_kepala_dapur',[KepalaDapurController::class,'update_maker_kepala_dapur']);
    //Route::post('/maker/data_staff/kepala_dapur/{id}/delete_kepala_dapur',[KepalaDapurController::class,'delete_maker_kepala_dapur']);

    

    //Dapur
    //Route::get('/maker/data_staff/dapur',[DapurController::class,'index_maker_dapur']);
    //Route::post('/maker/data_staff/dapur/store_dapur',[DapurController::class,'store_maker_dapur']);
    //Route::post('/maker/data_staff/dapur/{id}/delete_dapur',[DapurController::class,'delete_maker_dapur']);




    //Data Supplier
    //Supplier
    Route::get('/maker/data_supplier/supplier',[DataSupplierController::class,'index_maker_supplier']);
    Route::post('/maker/data_supplier/supplier/store_supplier',[DataSupplierController::class,'store_maker_supplier']);
    Route::post('/maker/data_supplier/supplier/edit_supplier',[DataSupplierController::class,'edit_maker_supplier']);
    Route::post('/maker/data_supplier/supplier/{id}/update_supplier',[DataSupplierController::class,'update_maker_supplier']);
    Route::post('/maker/data_supplier/supplier/{id}/delete_supplier',[DataSupplierController::class,'delete_maker_supplier']);

    //Informasi Supplier
    Route::get('/maker/data_supplier/informasi_supplier',[DataSupplierController::class,'index_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/store_informasi_supplier',[DataSupplierController::class,'store_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/tambah_barang_supplier',[DataSupplierController::class,'tambah_maker_barang_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/{id}/store_barang_supplier',[DataSupplierController::class,'store_maker_barang_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/lihat_barang_supplier',[DataSupplierController::class,'lihat_maker_barang_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/nota_informasi_supplier',[DataSupplierController::class,'nota_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/bukti_terima_informasi_supplier',[DataSupplierController::class,'bukti_terima_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/edit_informasi_supplier',[DataSupplierController::class,'edit_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/{id}/update_informasi_supplier',[DataSupplierController::class,'update_maker_informasi_supplier']);
    Route::post('/maker/data_supplier/informasi_supplier/{id}/delete_informasi_supplier',[DataSupplierController::class,'delete_maker_informasi_supplier']);
    

    //Data Koperasi
    Route::get('/maker/data_koperasi',[DataKoperasiController::class,'index_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/store_data_koperasi',[DataKoperasiController::class,'store_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/tambah_barang_modal_keluar',[DataKoperasiController::class,'tambah_maker_barang_modal_keluar']);
    Route::post('/maker/data_koperasi/{id}/store_barang_modal_keluar',[DataKoperasiController::class,'store_maker_barang_modal_keluar']);
    Route::post('/maker/data_koperasi/edit_modal_masuk_data_koperasi',[DataKoperasiController::class,'edit_modal_masuk_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/{id}/update_modal_masuk_data_koperasi',[DataKoperasiController::class,'update_modal_masuk_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/edit_modal_keluar_data_koperasi',[DataKoperasiController::class,'edit_modal_keluar_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/{id}/update_modal_keluar_data_koperasi',[DataKoperasiController::class,'update_modal_keluar_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/lihat_barang_modal_keluar',[DataKoperasiController::class,'lihat_maker_barang_modal_keluar']);
    Route::get('/maker/data_koperasi/cetak_data_koperasi',[DataKoperasiController::class,'cetak_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/{id}/update_data_koperasi',[DataKoperasiController::class,'update_maker_data_koperasi']);
    Route::post('/maker/data_koperasi/{id}/delete_data_koperasi',[DataKoperasiController::class,'delete_maker_data_koperasi']);

    //Informasi
    //Menu Harian
    Route::get('/maker/informasi/menu_harian',[InformasiMenuHarianController::class,'index_maker_menu_harian']);

    //Stok Limit
    Route::get('/maker/informasi/stok_limit',[InformasiStokLimitController::class,'index_maker_stok_limit']);

    //Pengiriman
    Route::get('/maker/informasi/pengiriman',[InformasiPengirimanController::class,'index_maker_pengiriman']);


    //Laporan
    //Stok
    Route::get('/maker/laporan/stok',[LaporanStokController::class,'index_maker_laporan_stok']);

    //Stok Harian
    Route::get('/maker/laporan/stok_harian',[LaporanStokController::class,'index_maker_laporan_stok_harian']);

    //Stok Bulanan
    Route::get('/maker/laporan/stok_bulanan',[LaporanStokController::class,'index_maker_laporan_stok_bulanan']);

    //Distribusi
    Route::get('/maker/laporan/distribusi',[LaporanDistribusiController::class,'index_maker_laporan_distribusi']);
    Route::post('/maker/laporan/distribusi/edit_laporan_distribusi',[LaporanDistribusiController::class,'edit_maker_laporan_distribusi']);
    Route::post('/maker/laporan/distribusi/bukti_pengiriman',[LaporanDistribusiController::class,'bukti_maker_pengiriman']);
    Route::post('/maker/laporan/distribusi/kendala_distribusi',[LaporanDistribusiController::class,'kendala_maker_distribusi']);
    Route::post('/maker/laporan/distribusi/{id}/update_laporan_distribusi',[LaporanDistribusiController::class,'update_maker_laporan_distribusi']);
    Route::get('/maker/laporan/distribusi/{id}/batalkan_distribusi',[LaporanDistribusiController::class,'batalkan_maker_distribusi']);
    Route::post('/maker/laporan/distribusi/{id}/delete_laporan_distribusi',[LaporanDistribusiController::class,'delete_maker_laporan_distribusi']);

    //Keuangan
    Route::get('/maker/laporan/keuangan',[LaporanKeuanganController::class,'index_maker_laporan_keuangan']);
    Route::post('/maker/laporan/keuangan/store_laporan_keuangan',[LaporanKeuanganController::class,'store_maker_laporan_keuangan']);
    Route::post('/maker/laporan/keuangan/edit_laporan_keuangan',[LaporanKeuanganController::class,'edit_maker_laporan_keuangan']);
    Route::get('/maker/laporan/keuangan/cetak_laporan_keuangan',[LaporanKeuanganController::class,'cetak_maker_laporan_keuangan']);
    Route::post('/maker/laporan/keuangan/{id}/update_laporan_keuangan',[LaporanKeuanganController::class,'update_maker_laporan_keuangan']);
    Route::post('/maker/laporan/keuangan/{id}/delete_laporan_keuangan',[LaporanKeuanganController::class,'delete_maker_laporan_keuangan']);

    
    //Supplier
    Route::get('/maker/laporan/supplier',[LaporanSupplierController::class,'index_maker_laporan_supplier']);
    Route::post('/maker/laporan/supplier/store_laporan_supplier',[LaporanSupplierController::class,'store_maker_laporan_supplier']);
    Route::post('/maker/laporan/supplier/edit_laporan_supplier',[LaporanSupplierController::class,'edit_maker_laporan_supplier']);
    Route::get('/maker/laporan/supplier/cetak_laporan_supplier',[LaporanSupplierController::class,'cetak_maker_laporan_supplier']);
    Route::post('/maker/laporan/supplier/{id}/update_laporan_supplier',[LaporanSupplierController::class,'update_maker_laporan_supplier']);
    Route::post('/maker/laporan/supplier/{id}/delete_laporan_supplier',[LaporanSupplierController::class,'delete_maker_laporan_supplier']);
    
    
    
    //Dapur
    Route::get('/maker/laporan/dapur',[LaporanDapurController::class,'index_maker_dapur']);
    Route::post('/maker/laporan/dapur/kendala_dapur',[LaporanDapurController::class,'kendala_maker_dapur']);
    Route::post('/maker/laporan/dapur/tambah_operasional_dapur',[LaporanDapurController::class,'tambah_operasional_dapur']);





    //Stok
    //Stok Masuk
    Route::get('/maker/stok_masuk',[StokMasukController::class,'index_stok_masuk_maker']);
    Route::post('/maker/stok_masuk/store_stok_masuk',[StokMasukController::class,'store_stok_masuk_maker']);
    Route::post('/maker/stok_masuk/edit_stok_masuk',[StokMasukController::class,'edit_stok_masuk_maker']);
    Route::post('/maker/stok_masuk/{id}/update_stok_masuk',[StokMasukController::class,'update_stok_masuk_maker']);
    Route::post('/maker/stok_masuk/{id}/delete_stok_masuk',[StokMasukController::class,'delete_stok_masuk_maker']);

    //Stok Keluar
    Route::get('/maker/stok_keluar',[StokKeluarController::class,'index_stok_keluar_maker']);
    Route::post('/maker/stok_keluar/store_stok_keluar',[StokKeluarController::class,'store_stok_keluar_maker']);
    Route::post('/maker/stok_keluar/edit_stok_keluar',[StokKeluarController::class,'edit_stok_keluar_maker']);
    Route::post('/maker/stok_keluar/{id}/update_stok_keluar',[StokKeluarController::class,'update_stok_keluar_maker']);
    Route::post('/maker/stok_keluar/{id}/delete_stok_keluar',[StokKeluarController::class,'delete_stok_keluar_maker']);

    //Stok Limit
    Route::get('/maker/stok_limit',[StokLimitController::class,'index_stok_limit_maker']);
    Route::post('/maker/stok_limit/store_stok_limit',[StokLimitController::class,'store_stok_limit_maker']);
    Route::post('/maker/stok_limit/tambah_tanggal_kadaluarsa',[StokLimitController::class,'tambah_tanggal_kadaluarsa_maker']);
    Route::post('/maker/stok_limit/{id}/store_tambah_tanggal_kadaluarsa',[StokLimitController::class,'store_tambah_tanggal_kadaluarsa_maker']);
});


Route::middleware(['auth:kepala_dapur'])->group(function(){
    Route::get('/proseslogoutkepaladapur', [AuthController::class,'proseslogoutkepaladapur']);
    Route::get('/kepala_dapur/dashboardkepaladapur',[DashboardController::class,'dashboardkepaladapur']);
    Route::post('/kepala_dapur/dashboardkepaladapur/store_dapur_kepala_dapur',[LaporanDapurController::class,'store_dapur_kepala_dapur']);
    Route::post('/kepala_dapur/dashboardkepaladapur/{id}/delete_laporan_distribusi_kepala_dapur',[LaporanDapurController::class,'delete_laporan_distribusi_kepala_dapur']);

    //Stok Masuk
    Route::get('/kepala_dapur/stok_masuk',[StokMasukController::class,'index_stok_masuk_kepala_dapur']);
    Route::post('/kepala_dapur/stok_masuk/store_stok_masuk',[StokMasukController::class,'store_stok_masuk']);
    Route::post('/kepala_dapur/stok_masuk/edit_stok_masuk',[StokMasukController::class,'edit_stok_masuk']);
    Route::post('/kepala_dapur/stok_masuk/{id}/update_stok_masuk',[StokMasukController::class,'update_stok_masuk']);
    Route::post('/kepala_dapur/stok_masuk/{id}/delete_stok_masuk',[StokMasukController::class,'delete_stok_masuk']);

    //Stok Keluar
    Route::get('/kepala_dapur/stok_keluar',[StokKeluarController::class,'index_stok_keluar_kepala_dapur']);
    Route::post('/kepala_dapur/stok_keluar/store_stok_keluar',[StokKeluarController::class,'store_stok_keluar']);
    Route::post('/kepala_dapur/stok_keluar/edit_stok_keluar',[StokKeluarController::class,'edit_stok_keluar']);
    Route::post('/kepala_dapur/stok_keluar/{id}/update_stok_keluar',[StokKeluarController::class,'update_stok_keluar']);
    Route::post('/kepala_dapur/stok_keluar/{id}/delete_stok_keluar',[StokKeluarController::class,'delete_stok_keluar']);

    //Stok Limit
    Route::get('/kepala_dapur/stok_limit',[StokLimitController::class,'index_stok_limit_kepala_dapur']);
    Route::post('/kepala_dapur/stok_limit/store_stok_limit',[StokLimitController::class,'store_stok_limit']);
    Route::post('/kepala_dapur/stok_limit/tambah_tanggal_kadaluarsa',[StokLimitController::class,'tambah_tanggal_kadaluarsa']);
    Route::post('/kepala_dapur/stok_limit/{id}/store_tambah_tanggal_kadaluarsa',[StokLimitController::class,'store_tambah_tanggal_kadaluarsa']);

    //Menu Harian
    Route::get('/kepala_dapur/menu_harian',[MenuHarianController::class,'index_menu_harian_kepala_dapur']);
    Route::post('/kepala_dapur/menu_harian/store_menu_harian',[MenuHarianController::class,'store_menu_harian']);
    Route::post('/kepala_dapur/menu_harian/tambah_bahan_terpakai',[MenuHarianController::class,'tambah_bahan_terpakai']);
    Route::post('/kepala_dapur/menu_harian/{id}/store_tambah_bahan_terpakai',[MenuHarianController::class,'store_tambah_bahan_terpakai']);
    Route::post('/kepala_dapur/menu_harian/lihat_bahan_terpakai',[MenuHarianController::class,'lihat_bahan_terpakai']);
    Route::post('/kepala_dapur/menu_harian/tambah_kendala',[MenuHarianController::class,'tambah_kendala']);
    Route::post('/kepala_dapur/menu_harian/{id}/store_tambah_kendala',[MenuHarianController::class,'store_tambah_kendala']);
    Route::post('/kepala_dapur/menu_harian/lihat_kendala',[MenuHarianController::class,'lihat_kendala']);
    Route::post('/kepala_dapur/menu_harian/{id}/delete_jadwal_menu_harian',[MenuHarianController::class,'delete_jadwal_menu_harian']);
});


Route::middleware(['auth:distributor'])->group(function () {
    //Login & Logout
    Route::get('/distributor/dashboarddistributor', [DashboardController::class, 'dashboarddistributor']);
    Route::get('/proseslogoutdistributor', [AuthController::class,'proseslogoutdistributor']);

    //Riwayat
    Route::get('/distributor/riwayat_distributor/index_riwayat_distributor', [RiwayatDataDriverController::class,'index_riwayat_distributor']);
    Route::post('/distributor/riwayat_distributor/get_riwayat_distributor', [RiwayatDataDriverController::class,'get_riwayat_distributor']);

    //Pengiriman
    Route::get('/distributor/pengiriman_distributor/index_pengiriman_distributor', [PengirimanDataDriverController::class,'index_pengiriman_distributor']);
    Route::get('/distributor/pengiriman_distributor/{id}/konfirmasi_pengiriman_distributor', [PengirimanDataDriverController::class,'konfirmasi_pengiriman_distributor']);
    Route::post('/distributor/pengiriman_distributor/store_pengiriman_distributor', [PengirimanDataDriverController::class,'store_pengiriman_distributor']);
    Route::post('/distributor/pengiriman_distributor/lihat_bukti_pengiriman', [PengirimanDataDriverController::class,'lihat_bukti_pengiriman']);

    //Profil
    Route::get('/distributor/profil_distributor/index_profil_distributor', [ProfilDataDriverController::class,'index_profil_distributor']);
    Route::post('/distributor/profil_distributor/update_profil_distributor', [ProfilDataDriverController::class,'update_profil_distributor']);
});
