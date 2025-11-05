<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\DataKoperasiController;
use App\Http\Controllers\DataSupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataIndukController;
use App\Http\Controllers\DataPekerjaController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\InformasiMenuHarianController;
use App\Http\Controllers\InformasiPengirimanController;
use App\Http\Controllers\InformasiStokLimitController;
use App\Http\Controllers\KepalaDapurController;
use App\Http\Controllers\LaporanDistribusiController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanHarianDapurController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\MenuHarianController;
use App\Http\Controllers\PengirimanDistributorController;
use App\Http\Controllers\ProfilDistributorController;
use App\Http\Controllers\RiwayatDistributorController;
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



Route::middleware(['guest:admin'])->group(function() {
    Route::get('/admin', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/prosesloginadmin', [AuthController::class,'prosesloginadmin']);
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

    //Data Induk
    //Admin
    Route::get('/owner/data_induk/admin',[AdminController::class,'index_owner_admin']);
    Route::post('/owner/data_induk/admin/store_admin',[AdminController::class,'store_owner_admin']);
    Route::post('/owner/data_induk/admin/edit_admin',[AdminController::class,'edit_owner_admin']);
    Route::post('/owner/data_induk/admin/{id}/update_admin',[AdminController::class,'update_owner_admin']);
    Route::post('/owner/data_induk/admin/{id}/delete_admin',[AdminController::class,'delete_owner_admin']);

    //Kepala Dapur
    Route::get('/owner/data_induk/kepala_dapur',[KepalaDapurController::class,'index_owner_kepala_dapur']);
    Route::post('/owner/data_induk/kepala_dapur/store_kepala_dapur',[KepalaDapurController::class,'store_owner_kepala_dapur']);
    Route::post('/owner/data_induk/kepala_dapur/edit_kepala_dapur',[KepalaDapurController::class,'edit_owner_kepala_dapur']);
    Route::post('/owner/data_induk/kepala_dapur/{id}/update_kepala_dapur',[KepalaDapurController::class,'update_owner_kepala_dapur']);
    Route::post('/owner/data_induk/kepala_dapur/{id}/delete_kepala_dapur',[KepalaDapurController::class,'delete_owner_kepala_dapur']);

    //Distributor
    Route::get('/owner/data_induk/distributor',[DistributorController::class,'index_owner_distributor']);
    Route::post('/owner/data_induk/distributor/store_distributor',[DistributorController::class,'store_owner_distributor']);
    Route::post('/owner/data_induk/distributor/edit_distributor',[DistributorController::class,'edit_owner_distributor']);
    Route::post('/owner/data_induk/distributor/{id}/update_distributor',[DistributorController::class,'update_owner_distributor']);
    Route::post('/owner/data_induk/distributor/{id}/delete_distributor',[DistributorController::class,'delete_owner_distributor']);


    //Data Pekerja
    Route::get('/owner/data_induk/data_pekerja',[DataPekerjaController::class,'index_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/store_data_pekerja',[DataPekerjaController::class,'store_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/edit_data_pekerja',[DataPekerjaController::class,'edit_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/ktp_data_pekerja',[DataPekerjaController::class,'ktp_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/{id}/update_data_pekerja',[DataPekerjaController::class,'update_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/{id}/delete_data_pekerja',[DataPekerjaController::class,'delete_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/status_validasi_data_pekerja',[DataPekerjaController::class,'status_validasi_owner_data_pekerja']);
    Route::post('/owner/data_induk/data_pekerja/{id}/update_status_validasi_data_pekerja',[DataPekerjaController::class,'update_status_validasi_owner_data_pekerja']);
    Route::get('/owner/data_induk/data_pekerja/{id}/batalkan_status_validasi_data_pekerja',[DataPekerjaController::class,'batalkan_status_validasi_owner_data_pekerja']);


    //Dapur
    Route::get('/owner/data_induk/dapur',[DapurController::class,'index_owner_dapur']);
    Route::post('/owner/data_induk/dapur/store_dapur',[DapurController::class,'store_owner_dapur']);
    Route::post('/owner/data_induk/dapur/{id}/delete_dapur',[DapurController::class,'delete_owner_dapur']);

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
    Route::get('/owner/laporan/harian_dapur',[LaporanHarianDapurController::class,'index_owner_harian_dapur']);
    Route::post('/owner/laporan/harian_dapur/lihat_bahan_terpakai',[LaporanHarianDapurController::class,'lihat_bahan_terpakai']);
    Route::post('/owner/laporan/harian_dapur/lihat_kendala',[LaporanHarianDapurController::class,'lihat_kendala']);
    Route::post('/owner/laporan/harian_dapur/kendala_harian_dapur',[LaporanHarianDapurController::class,'kendala_owner_harian_dapur']);
});


Route::middleware(['auth:admin'])->group(function(){
    Route::get('/proseslogoutadmin', [AuthController::class,'proseslogoutadmin']);
    //Route::get('/admin/dashboardadmin',[DashboardController::class,'dashboardadmin']);
    Route::get('/admin/dashboardadmin',[LaporanDistribusiController::class,'index_admin_laporan_distribusi']);

    //Data Induk
    Route::get('/admin/data_induk',[DataIndukController::class,'index_admin_data_induk']);
    Route::post('/admin/data_induk/data_pekerja/store_data_pekerja',[DataPekerjaController::class,'store_admin_data_pekerja']);
    Route::post('/admin/data_induk/data_pekerja/edit_data_pekerja',[DataPekerjaController::class,'edit_admin_data_pekerja']);
    Route::post('/admin/data_induk/data_pekerja/ktp_data_pekerja',[DataPekerjaController::class,'ktp_admin_data_pekerja']);
    Route::post('/admin/data_induk/data_pekerja/{id}/update_data_pekerja',[DataPekerjaController::class,'update_admin_data_pekerja']);
    Route::post('/admin/data_induk/data_pekerja/{id}/delete_data_pekerja',[DataPekerjaController::class,'delete_admin_data_pekerja']);
    
    //Kepala Dapur
    Route::get('/admin/data_induk/kepala_dapur',[KepalaDapurController::class,'index_admin_kepala_dapur']);
    Route::post('/admin/data_induk/kepala_dapur/store_kepala_dapur',[KepalaDapurController::class,'store_admin_kepala_dapur']);
    Route::post('/admin/data_induk/kepala_dapur/edit_kepala_dapur',[KepalaDapurController::class,'edit_admin_kepala_dapur']);
    Route::post('/admin/data_induk/kepala_dapur/{id}/update_kepala_dapur',[KepalaDapurController::class,'update_admin_kepala_dapur']);
    Route::post('/admin/data_induk/kepala_dapur/{id}/delete_kepala_dapur',[KepalaDapurController::class,'delete_admin_kepala_dapur']);

    //Distributor
    Route::get('/admin/data_induk/distributor',[DistributorController::class,'index_admin_distributor']);
    Route::post('/admin/data_induk/distributor/store_distributor',[DistributorController::class,'store_admin_distributor']);
    Route::post('/admin/data_induk/distributor/edit_distributor',[DistributorController::class,'edit_admin_distributor']);
    Route::post('/admin/data_induk/distributor/{id}/update_distributor',[DistributorController::class,'update_admin_distributor']);
    Route::post('/admin/data_induk/distributor/{id}/delete_distributor',[DistributorController::class,'delete_admin_distributor']);

    //Dapur
    Route::get('/admin/data_induk/dapur',[DapurController::class,'index_admin_dapur']);
    Route::post('/admin/data_induk/dapur/store_dapur',[DapurController::class,'store_admin_dapur']);
    Route::post('/admin/data_induk/dapur/{id}/delete_dapur',[DapurController::class,'delete_admin_dapur']);

    //Data Supplier
    //Supplier
    Route::get('/admin/data_supplier/supplier',[DataSupplierController::class,'index_admin_supplier']);
    Route::post('/admin/data_supplier/supplier/store_supplier',[DataSupplierController::class,'store_admin_supplier']);
    Route::post('/admin/data_supplier/supplier/edit_supplier',[DataSupplierController::class,'edit_admin_supplier']);
    Route::post('/admin/data_supplier/supplier/{id}/update_supplier',[DataSupplierController::class,'update_admin_supplier']);
    Route::post('/admin/data_supplier/supplier/{id}/delete_supplier',[DataSupplierController::class,'delete_admin_supplier']);

    //Informasi Supplier
    Route::get('/admin/data_supplier/informasi_supplier',[DataSupplierController::class,'index_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/store_informasi_supplier',[DataSupplierController::class,'store_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/tambah_barang_supplier',[DataSupplierController::class,'tambah_admin_barang_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/{id}/store_barang_supplier',[DataSupplierController::class,'store_admin_barang_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/lihat_barang_supplier',[DataSupplierController::class,'lihat_admin_barang_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/nota_informasi_supplier',[DataSupplierController::class,'nota_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/bukti_terima_informasi_supplier',[DataSupplierController::class,'bukti_terima_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/edit_informasi_supplier',[DataSupplierController::class,'edit_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/{id}/update_informasi_supplier',[DataSupplierController::class,'update_admin_informasi_supplier']);
    Route::post('/admin/data_supplier/informasi_supplier/{id}/delete_informasi_supplier',[DataSupplierController::class,'delete_admin_informasi_supplier']);
    

    //Data Koperasi
    Route::get('/admin/data_koperasi',[DataKoperasiController::class,'index_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/store_data_koperasi',[DataKoperasiController::class,'store_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/edit_modal_masuk_data_koperasi',[DataKoperasiController::class,'edit_modal_masuk_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/{id}/update_modal_masuk_data_koperasi',[DataKoperasiController::class,'update_modal_masuk_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/tambah_barang_modal_keluar',[DataKoperasiController::class,'tambah_admin_barang_modal_keluar']);
    Route::post('/admin/data_koperasi/{id}/store_barang_modal_keluar',[DataKoperasiController::class,'store_admin_barang_modal_keluar']);
    Route::post('/admin/data_koperasi/edit_modal_keluar_data_koperasi',[DataKoperasiController::class,'edit_modal_keluar_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/{id}/update_modal_keluar_data_koperasi',[DataKoperasiController::class,'update_modal_keluar_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/lihat_barang_modal_keluar',[DataKoperasiController::class,'lihat_admin_barang_modal_keluar']);
    Route::get('/admin/data_koperasi/cetak_data_koperasi',[DataKoperasiController::class,'cetak_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/{id}/update_data_koperasi',[DataKoperasiController::class,'update_admin_data_koperasi']);
    Route::post('/admin/data_koperasi/{id}/delete_data_koperasi',[DataKoperasiController::class,'delete_admin_data_koperasi']);

    //Informasi
    //Menu Harian
    Route::get('/admin/informasi/menu_harian',[InformasiMenuHarianController::class,'index_admin_menu_harian']);

    //Stok Limit
    Route::get('/admin/informasi/stok_limit',[InformasiStokLimitController::class,'index_admin_stok_limit']);

    //Pengiriman
    Route::get('/admin/informasi/pengiriman',[InformasiPengirimanController::class,'index_admin_pengiriman']);


    //Laporan
    //Stok
    Route::get('/admin/laporan/stok',[LaporanStokController::class,'index_admin_laporan_stok']);

    //Stok Harian
    Route::get('/admin/laporan/stok_harian',[LaporanStokController::class,'index_admin_laporan_stok_harian']);

    //Stok Bulanan
    Route::get('/admin/laporan/stok_bulanan',[LaporanStokController::class,'index_admin_laporan_stok_bulanan']);

    //Distribusi
    Route::get('/admin/laporan/distribusi',[LaporanDistribusiController::class,'index_admin_laporan_distribusi']);
    Route::post('/admin/laporan/distribusi/edit_laporan_distribusi',[LaporanDistribusiController::class,'edit_admin_laporan_distribusi']);
    Route::post('/admin/laporan/distribusi/bukti_pengiriman',[LaporanDistribusiController::class,'bukti_admin_pengiriman']);
    Route::post('/admin/laporan/distribusi/kendala_distribusi',[LaporanDistribusiController::class,'kendala_admin_distribusi']);
    Route::post('/admin/laporan/distribusi/{id}/update_laporan_distribusi',[LaporanDistribusiController::class,'update_admin_laporan_distribusi']);
    Route::get('/admin/laporan/distribusi/{id}/batalkan_distribusi',[LaporanDistribusiController::class,'batalkan_admin_distribusi']);
    Route::post('/admin/laporan/distribusi/{id}/delete_laporan_distribusi',[LaporanDistribusiController::class,'delete_admin_laporan_distribusi']);

    //Keuangan
    Route::get('/admin/laporan/keuangan',[LaporanKeuanganController::class,'index_admin_laporan_keuangan']);
    Route::post('/admin/laporan/keuangan/store_laporan_keuangan',[LaporanKeuanganController::class,'store_admin_laporan_keuangan']);
    Route::post('/admin/laporan/keuangan/edit_laporan_keuangan',[LaporanKeuanganController::class,'edit_admin_laporan_keuangan']);
    Route::get('/admin/laporan/keuangan/cetak_laporan_keuangan',[LaporanKeuanganController::class,'cetak_admin_laporan_keuangan']);
    Route::post('/admin/laporan/keuangan/{id}/update_laporan_keuangan',[LaporanKeuanganController::class,'update_admin_laporan_keuangan']);
    Route::post('/admin/laporan/keuangan/{id}/delete_laporan_keuangan',[LaporanKeuanganController::class,'delete_admin_laporan_keuangan']);

    //Harian Dapur
    Route::get('/admin/laporan/harian_dapur',[LaporanHarianDapurController::class,'index_admin_harian_dapur']);
    Route::post('/admin/laporan/harian_dapur/kendala_harian_dapur',[LaporanHarianDapurController::class,'kendala_admin_harian_dapur']);
});


Route::middleware(['auth:kepala_dapur'])->group(function(){
    Route::get('/proseslogoutkepaladapur', [AuthController::class,'proseslogoutkepaladapur']);
    Route::get('/kepala_dapur/dashboardkepaladapur',[DashboardController::class,'dashboardkepaladapur']);
    Route::post('/kepala_dapur/dashboardkepaladapur/store_harian_dapur_kepala_dapur',[LaporanHarianDapurController::class,'store_harian_dapur_kepala_dapur']);
    Route::post('/kepala_dapur/dashboardkepaladapur/{id}/delete_laporan_distribusi_kepala_dapur',[LaporanHarianDapurController::class,'delete_laporan_distribusi_kepala_dapur']);

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
    Route::get('/distributor/riwayat_distributor/index_riwayat_distributor', [RiwayatDistributorController::class,'index_riwayat_distributor']);
    Route::post('/distributor/riwayat_distributor/get_riwayat_distributor', [RiwayatDistributorController::class,'get_riwayat_distributor']);

    //Pengiriman
    Route::get('/distributor/pengiriman_distributor/index_pengiriman_distributor', [PengirimanDistributorController::class,'index_pengiriman_distributor']);
    Route::get('/distributor/pengiriman_distributor/{id}/konfirmasi_pengiriman_distributor', [PengirimanDistributorController::class,'konfirmasi_pengiriman_distributor']);
    Route::post('/distributor/pengiriman_distributor/store_pengiriman_distributor', [PengirimanDistributorController::class,'store_pengiriman_distributor']);
    Route::post('/distributor/pengiriman_distributor/lihat_bukti_pengiriman', [PengirimanDistributorController::class,'lihat_bukti_pengiriman']);

    //Profil
    Route::get('/distributor/profil_distributor/index_profil_distributor', [ProfilDistributorController::class,'index_profil_distributor']);
    Route::post('/distributor/profil_distributor/update_profil_distributor', [ProfilDistributorController::class,'update_profil_distributor']);
});
