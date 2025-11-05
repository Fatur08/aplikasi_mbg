@extends('layouts.owner.tabler')
@section('content')
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
                                    Data Koperasi
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahDataKoperasi">
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
                            </td>-->
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
                                <form action="/owner/data_koperasi" method="GET">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-2">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-icon">
                                                <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                    <option value="">Pilih Dapur</option>
                                                    @foreach($dapurList as $dapur)
                                                        <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-icon">
                                                <select name="bulan" id="bulan" class="form-select">
                                                    <option value="">Bulan</option>
                                                    <option value="Januari">Januari</option>
                                                    <option value="Februari">Februari</option>
                                                    <option value="Maret">Maret</option>
                                                    <option value="April">April</option>
                                                    <option value="Mei">Mei</option>
                                                    <option value="Juni">Juni</option>
                                                    <option value="Juli">Juli</option>
                                                    <option value="Agustus">Agustus</option>
                                                    <option value="September">September</option>
                                                    <option value="Oktober">Oktober</option>
                                                    <option value="November">November</option>
                                                    <option value="Desember">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                    Cari    
                                                </button>
                                            </div>
                                        </div>
                                </form>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <a href="#" class="btn btn-success w-100" id="cetak_data_koperasi" >
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                    Cetak
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                @if(!$sudahCari)
                                    <div class="alert alert-info text-center">
                                        Silakan lakukan pencarian terlebih dahulu
                                    </div>
                                @elseif($dataKosong)
                                    <div class="alert alert-warning text-center">
                                        Data tidak ditemukan
                                    </div>
                                @else
                                    @foreach ($grouped as $tanggal => $data)
                                        @php
                                            $totalMasuk = 0;
                                            $totalKeluar = 0;

                                            foreach ($data as $item) {
                                                if ($item->jenis_data_koperasi == 'modal_masuk') {
                                                    // ✅ Total masuk dari harga_data_koperasi
                                                    $totalMasuk += $item->harga_data_koperasi;
                                                } elseif ($item->jenis_data_koperasi == 'modal_keluar') {
                                                
                                                    // ✅ Jika dari supplier
                                                    if (!empty($item->id_informasi_supplier) && $item->id_informasi_supplier > 0) {
                                                        $totalKeluar += DB::table('barang_supplier')
                                                            ->where('id_informasi_supplier', $item->id_informasi_supplier)
                                                            ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                                                            ->sum(DB::raw('harga_barang_supplier'));
                                                    } else {
                                                        // ✅ Jika non-supplier (langsung dari tabel barang_modal_keluar)
                                                        $totalKeluar += DB::table('barang_modal_keluar')
                                                            ->where('id_data_koperasi', $item->id_data_koperasi)
                                                            ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                                                            ->sum(DB::raw('harga_barang_modal_keluar'));
                                                    }
                                                }
                                            }
                                        
                                            $selisih = $totalMasuk - $totalKeluar;
                                        @endphp

                                        <div class="card mb-4 shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <h2><b>{{ $tanggal }}</b></h2>
                                                <h2>Selisih: <b>Rp {{ number_format($selisih, 0, ',', '.') }}</b></h2>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- ===== Modal Masuk ===== -->
                                                    <div class="col-md-6">
                                                        <h3 class="text-success"><b>Modal Masuk</b></h3>
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="table-success">
                                                                <tr class="text-center">
                                                                    <th>No</th>
                                                                    <th>Sumber</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Validasi</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $noMasuk = 1; @endphp
                                                                @foreach ($data->where('jenis_data_koperasi', 'modal_masuk') as $d)
                                                                    <tr>
                                                                        <td class="text-center">{{ $noMasuk++ }}</td>
                                                                        <td>{{ $d->kategori_data_koperasi ?? '-' }}</td>
                                                                        <td>Rp {{ number_format($d->harga_data_koperasi, 0, ',', '.') }}</td>
                                                                        <td class="text-center">
                                                                            @if($d->status_data_koperasi == 0)
                                                                                <button class="btn btn-warning btn-sm">Menunggu</button>
                                                                            @elseif($d->status_data_koperasi == 1)
                                                                                <button class="btn btn-success btn-sm">Disetujui</button>
                                                                            @else
                                                                                <button class="btn btn-danger btn-sm">Ditolak</button>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div class="btn-group-vertical">
                                                                                @if($d->status_data_koperasi == 0)
                                                                                <a href="#" class="validasi_data_koperasi btn btn-primary btn-sm mb-1" id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-pencil-square"></i> Validasi
                                                                                </a>
                                                                                @else
                                                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/batalkan_validasi_data_koperasi" method="POST">
                                                                                    @csrf    
                                                                                    <a class="btn btn-danger btn-sm batalkan_validasi_data_koperasi mb-1" >
                                                                                        Batalkan
                                                                                    </a>
                                                                                </form>
                                                                                @endif
                                                                                <a href="#" class="edit_modal_masuk_data_koperasi btn btn-info btn-sm mb-1" id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-pencil-square"></i> Edit
                                                                                </a>
                                                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/delete_data_koperasi" method="POST">
                                                                                    @csrf
                                                                                    <a class="btn btn-danger btn-sm delete-confirm-data-koperasi mb-1">
                                                                                        <i class="bi bi-trash"></i> Hapus
                                                                                    </a>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="table-light">
                                                                <tr>
                                                                    <th colspan="2" class="text-end">Total</th>
                                                                    <th colspan="3">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                    <!-- ===== Modal Keluar ===== -->
                                                    <div class="col-md-6">
                                                        <h3 class="text-danger"><b>Modal Keluar</b></h3>
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="table-danger">
                                                                <tr class="text-center">
                                                                    <th>No</th>
                                                                    <th>Tujuan</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Validasi</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $noKeluar = 1; @endphp
                                                                @foreach ($data->where('jenis_data_koperasi', 'modal_keluar') as $d)
                                                                    <tr>
                                                                        <td class="text-center">{{ $noKeluar++ }}</td>
                                                                        <td>{{ $d->kategori_data_koperasi ?? '-' }}</td>
                                                                        <td>Rp {{ number_format($d->total_harga_supplier, 0, ',', '.') }}</td>
                                                                        <td class="text-center">
                                                                            @if($d->status_data_koperasi == 0)
                                                                                <button class="btn btn-warning btn-sm">Menunggu</button>
                                                                            @elseif($d->status_data_koperasi == 1)
                                                                                <button class="btn btn-success btn-sm">Disetujui</button>
                                                                            @else
                                                                                <button class="btn btn-danger btn-sm">Ditolak</button>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div class="btn-group-vertical">
                                                                                @if($d->status_data_koperasi == 0)
                                                                                <a href="#" class="validasi_data_koperasi btn btn-primary btn-sm mb-1" id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-pencil-square"></i> Validasi
                                                                                </a>
                                                                                @else
                                                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/batalkan_validasi_data_koperasi" method="POST">
                                                                                    @csrf    
                                                                                    <a class="btn btn-danger btn-sm batalkan_validasi_data_koperasi mb-1" >
                                                                                        Batalkan
                                                                                    </a>
                                                                                </form>
                                                                                @endif
                                                                                <a href="#" class="edit_modal_keluar_data_koperasi btn btn-info btn-sm mb-1" id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-pencil-square"></i> Edit
                                                                                </a>
                                                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/delete_data_koperasi" method="POST">
                                                                                    @csrf
                                                                                    <a class="btn btn-danger btn-sm delete-confirm-data-koperasi mb-1">
                                                                                        <i class="bi bi-trash"></i> Hapus
                                                                                    </a>
                                                                                </form>
                                                                                <a href="#" class="tambah_barang_modal_keluar btn btn-success btn-sm mb-1" data-id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-plus-circle"></i> Tambah
                                                                                </a>
                                                                                <a href="#" class="lihat_barang_modal_keluar btn btn-secondary btn-sm" data-id="{{ $d->id_data_koperasi }}">
                                                                                    <i class="bi bi-eye"></i> Lihat
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="table-light">
                                                                <tr>
                                                                    <th colspan="2" class="text-end">Total</th>
                                                                    <th colspan="3">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="mt-3">
                                        
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Edit Modal Masuk Data Koperasi --}}
<div class="modal modal-blur fade" id="modal-editmodalmasukdatakoperasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Modal Masuk Data Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditmodalmasukformdatakoperasi">
                
            </div>
        </div>
    </div>
</div>


{{-- Edit Modal Keluar Data Koperasi --}}
<div class="modal modal-blur fade" id="modal-editmodalkeluardatakoperasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Modal Keluar Data Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditmodalkeluarformdatakoperasi">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Validasi Data Koperasi --}}
<div class="modal modal-blur fade" id="modal-valdiasiinformdatakoperasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validasi Data Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadvalidasiformdatakoperasi">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Barang Modal Keluar --}}
<div class="modal modal-blur fade" id="modal-tambahbarangmodalkeluar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Modal Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahbarangmodalkeluar">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Lihat Barang Modal Keluar --}}
<div class="modal modal-blur fade" id="modal-lihatbarangmodalkeluar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Barang Modal Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbarangmodalkeluar">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#tanggal_data_koperasi").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#dari_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#sampai_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#btnTambahDataKoperasi").click(function(){
            $("#modal-inputdatakoperasi").modal("show");
        });


        $(".edit_modal_masuk_data_koperasi").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/edit_modal_masuk_data_koperasi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditmodalmasukformdatakoperasi").html(respond);
                }
            });
            $("#modal-editmodalmasukdatakoperasi").modal("show");
        });


        $(".edit_modal_keluar_data_koperasi").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/edit_modal_keluar_data_koperasi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditmodalkeluarformdatakoperasi").html(respond);
                }
            });
            $("#modal-editmodalkeluardatakoperasi").modal("show");
        });


        $(".tambah_barang_modal_keluar").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/tambah_barang_modal_keluar',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformtambahbarangmodalkeluar").html(respond);
                }
            });
            $("#modal-tambahbarangmodalkeluar").modal("show");
        });


        $(".lihat_barang_modal_keluar").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/lihat_barang_modal_keluar',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbarangmodalkeluar").html(respond);
                }
            });
            $("#modal-lihatbarangmodalkeluar").modal("show");
        });


        $(".validasi_data_koperasi").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/validasi_data_koperasi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadvalidasiformdatakoperasi").html(respond);
                }
            });
            $("#modal-valdiasiinformdatakoperasi").modal("show");
        });


        $(".batalkan_validasi_data_koperasi").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin ingin batalkan",
                text: "Jika Ya Maka Status Validasi akan berubah",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Batalkan Saja"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di Batalkan",
                        icon: "success"
                  });
                }
            });
        });



        $(".delete-confirm-data-koperasi").click(function(e){
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

        $("#frmDtKprs").submit(function(){
            var nama_supplier = $("#nama_supplier").val();
            var alamat = $("#alamat").val();
            var no_hp = $("#no_hp").val();
            if(nama_supplier==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Supplier Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#nama_supplier").focus();
                  });
                return false;
            } else if (alamat==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Alamat Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#alamat").focus();
                  });
                return false;
            } else if (no_hp==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'No. HP Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#no_hp").focus();
                  });
                return false;
            }
        });


        flatpickr("#dari_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });

        flatpickr("#sampai_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        let dari = document.getElementById("dari_tanggal");
        let sampai = document.getElementById("sampai_tanggal");
        let bulan = document.getElementById("bulan");
            
        function toggleBulan() {
            if (dari.value && sampai.value) {
                bulan.disabled = true;   // disable select bulan
                bulan.value = "";        // reset pilihannya
            } else {
                bulan.disabled = false;  // aktifkan lagi
            }
        }
    
        function toggleTanggal() {
            if (bulan.value) {
                dari.readOnly = true;    
                sampai.readOnly = true;  
                dari.value = "";         
                sampai.value = "";       
            } else {
                dari.readOnly = false;   
                sampai.readOnly = false; 
            }
        }
    
        dari.addEventListener("change", toggleBulan);
        sampai.addEventListener("change", toggleBulan);
        bulan.addEventListener("change", toggleTanggal);
    });

    document.getElementById("cetak_data_koperasi").addEventListener("click", function() {
        let dari = document.getElementById("dari_tanggal").value;
        let sampai = document.getElementById("sampai_tanggal").value;
        let bulan = document.getElementById("bulan").value;

        let url = `/owner/data_koperasi/cetak_data_koperasi?dari_tanggal=${dari}&sampai_tanggal=${sampai}&bulan=${bulan}`;
        window.open(url, "_blank");
    });
</script>
@endpush