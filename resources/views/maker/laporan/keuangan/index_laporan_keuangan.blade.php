@extends('layouts.admin.tabler')
@section('content')
<style>
/* === Section Info Dapur === */
.section-info {
    margin-top: 40px;
    margin-bottom: 25px;
    text-align: center;
}
.info-card {
    display: inline-block;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    padding: 25px 40px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}
.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.info-card h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 20px;
}
.info-card p {
    color: #6b7280;
    margin: 0;
    font-size: 18px;
}

/* === Table Style === */
.custom-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
}

.custom-table thead th {
    background: linear-gradient(135deg, #007bff, #00bcd4);
    color: white;
    text-align: center;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.5px;
    padding: 12px;
    border: none;
}

.custom-table thead tr:first-child th {
    background: linear-gradient(135deg, #0069d9, #17a2b8);
    font-size: 17px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.custom-table tbody td, 
.custom-table tbody th {
    padding: 12px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    font-size: 16px;
    color: #333;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.custom-table tbody tr:hover {
    background-color: #e9f5ff;
    transition: 0.3s;
}

.table-container {
    max-width: 1600px;
}

/* === Buttons === */
.btn-status {
    font-size: 13px;
    padding: 4px 14px;
    border-radius: 20px;
    font-weight: 600;
    border: none;
    color: #fff;
}
.btn-menunggu {
    background-color: #facc15;
    color: #111827;
}
.btn-validasi {
    background-color: #38bdf8;
}
.btn-menunggu:hover {
    background-color: #eab308;
}
.btn-validasi:hover {
    background-color: #0ea5e9;
}

/* === Responsive === */
@media (max-width: 768px) {
    .info-card {
        width: 100%;
        padding: 20px;
    }
    .info-card h4 {
        font-size: 18px;
    }
    .table-modern {
        font-size: 13px;
    }
}
</style>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <div class="page-pretitle">
                                    Halaman
                                </div>
                                <h2 class="page-title">
                                    Laporan Keuangan
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="TambahLaporanKeuangan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Tambah Data
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Sisa Seluruh Dana :</strong> Rp {{ number_format($sisa_dana, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <form action="/admin/laporan/keuangan" method="GET" id="FormLaporanKeuangan">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-6">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-6">
                                                <div class="input-icon">
                                                    <select name="pilih_instansi" id="pilih_instansi" class="form-select">
                                                        <option value="">Pilih Supplier / Koperasi</option>
                                                        <option value="Supplier">Supplier</option>
                                                        <option value="Koperasi">Koperasi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                        Cari    
                                                    </button>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-success w-100" id="cetak_laporan_keuangan" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        Cetak
                                                    </a>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row mt-2 table-container">
                                <div class="col-12">
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="table custom-table">
                                                <thead class="table-primary text-center">
                                                    <tr>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Tanggal</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Barang</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Jumlah</th>
                                                        <th style="text-align: center; vertical-align: middle;" colspan="2">Keterangan</th>
                                                        <!--<th style="text-align: center; vertical-align: middle;" rowspan="2">Pengeluaran</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Margin</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Validasi</th>
                                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>-->
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center; vertical-align: middle;">Koperasi</th>
                                                        <th style="text-align: center; vertical-align: middle;">Supplier</th>
                                                        <!--<th style="text-align: center; vertical-align: middle;">Pemasukan</th>
                                                        <th style="text-align: center; vertical-align: middle;">Pengeluaran</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-inputlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/laporan/keuangan/store_laporan_keuangan" method="POST" id="FormLaporanKeuangan" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_laporan_keuangan" name="tanggal_laporan_keuangan" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-category"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                </span>
                                <input type="text" value="" id="barang_laporan_keuangan" name="barang_laporan_keuangan" class="form-control"  placeholder="Masukkan Nama Barang">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" /></svg>
                                </span>
                                <input type="number" value="" id="jumlah_barang_laporan_keuangan" name="jumlah_barang_laporan_keuangan" class="form-control" placeholder="Masukkan Jumlah Barang">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" /></svg>
                                </span>
                                <input type="number" value="" id="harga_barang_laporan_keuangan" name="harga_barang_laporan_keuangan" class="form-control" placeholder="Masukkan Total Harga Barang">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Modal Edit Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-editlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformlaporankeuangan">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#TambahLaporanKeuangan").click(function(){
            $("#modal-inputlaporankeuangan").modal("show");
        });

        $(".edit_laporan_keuangan").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/keuangan/edit_laporan_keuangan',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformlaporankeuangan").html(respond);
                }
            });
            $("#modal-editlaporankeuangan").modal("show");
        });

        $(".delete-confirm-laporan-keuangan").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin Data ini Mau Di Hapus?",
                text: "Jika Ya Maka Data Akan Terhapus Permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di Hapus",
                        icon: "success"
                  });
                }
            });
        });

        $("#FormLaporanKeuangan").submit(function(){
            var dari_tanggal   = $("#dari_tanggal").val();
            var sampai_tanggal = $("#sampai_tanggal").val();
            if(dari_tanggal==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dari Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#dari_tanggal").focus();
                  });
                return false;
            } else if (sampai_tanggal==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Sampai Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#sampai_tanggal").focus();
                  });
                return false;
            }
        });


        $("#cetak_laporan_keuangan").click(function(e){
            e.preventDefault(); // Mencegah langsung pindah halaman
                
            var pilih_bulan = $("#pilih_bulan").val();
            var pilih_dapur = $("#pilih_dapur").val();
                
            if(pilih_bulan == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bulan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_bulan").focus();
                });
                return false;
            
            } else if(pilih_dapur == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_dapur").focus();
                });
                return false;
            }
        
            // ✅ JIKA SUDAH LENGKAP, BARU BUKA HALAMAN CETAK
            let url = `/owner/laporan/keuangan/cetak_laporan_keuangan?bulan=${pilih_bulan}&dapur=${pilih_dapur}`;
            window.open(url, '_blank');
        });



        flatpickr("#dari_tanggal", {
            altInput: true,
            altFormat: "d F Y",      // 15 September 2025
            dateFormat: "Y-m-d",     // dikirim ke backend
            locale: "id",
            allowInput: true
        });
        
        flatpickr("#sampai_tanggal", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });


        flatpickr("#tanggal_laporan_keuangan", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });
    });








    // === BAGIAN DIAGRAM BATANG ===
    let koperasiData = @json($data);

    const labels      = koperasiData.map(item => item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal');
    const modalMasuk  = koperasiData.map(item => item.total_pemasukan);
    const modalKeluar = koperasiData.map(item => item.total_pengeluaran);
    const margin      = koperasiData.map(item => item.margin);

    const ctxBar = document.getElementById('koperasiChartAdmin').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: modalMasuk,
                    backgroundColor: 'rgba(0, 76, 255, 1)'
                },
                {
                    label: 'Pengeluaran',
                    data: modalKeluar,
                    backgroundColor: 'rgba(255, 0, 0, 0.7)'
                },
                {
                    label: 'Margin',
                    data: margin,
                    backgroundColor: 'rgba(47, 255, 0, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { size: 14 } } }
            }
        }
    });
</script>
@endpush