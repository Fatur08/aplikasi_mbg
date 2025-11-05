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
                                    Informasi Supplier
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahInformasiSupplier">
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
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/owner/data_supplier/informasi_supplier" method="GET">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nama_supplier_cari" id="nama_supplier_cari" placeholder="Nama Lengkap" value="{{ Request('nama_supplier_cari') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                    <option value="">Pilih Dapur</option>
                                                    @foreach($dapurList as $dapur)
                                                        <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                    Cari    
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">No.</th>
                                            <th style="text-align:center">Nama</th>
                                            <th style="text-align:center">Dapur</th>
                                            <th style="text-align:center">Barang</th>
                                            <th style="text-align:center">Total</th>
                                            <th style="text-align:center">Nota</th>
                                            <th style="text-align:center">Bukti Terima</th>
                                            <th style="text-align:center">Validasi</th>
                                            <th style="text-align:center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($informasi_supplier as $d)
                                        <tr>
                                            <td style="text-align:center">{{ $loop->iteration + $informasi_supplier->firstItem()-1 }}</td>
                                            <td>{{ $d->nama_informasi_supplier }}</td>
                                            <td style="text-align:center">{{ $d->nama_dapur ?? '-' }}</td>
                                            <td style="text-align: center;"> 
                                                <div class="align-items-center">
                                                    <a href="#" class="tambah_barang_supplier btn btn-info btn-sm" data-id="{{ $d->id_informasi_supplier }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                                        <span>Tambah</span>
                                                    </a>
                                                    <a href="#" class="lihat_barang_supplier btn btn-info btn-sm" data-id="{{ $d->id_informasi_supplier }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                     c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                        <span>Lihat</span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($d->total_otomatis, 0, ',', '.') }}</td>
                                            <td style="text-align:center"> 
                                                <a href="#" class="nota_informasi_supplier btn btn-info btn-sm" id="{{ $d->id_informasi_supplier }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg>
                                                    <span>Lihat</span>
                                                </a>
                                            </td>
                                            <td style="text-align:center"> 
                                                <a href="#" class="bukti_terima_informasi_supplier btn btn-info btn-sm" id="{{ $d->id_informasi_supplier }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                    </svg>
                                                    <span>Lihat</span>
                                                </a>
                                            </td>
                                            <td style="text-align:center">
                                                @if($d->status_informasi_supplier == 0)
                                                    <button class="btn btn-warning btn-sm">Menunggu</button>
                                                @elseif($d->status_informasi_supplier == 1)
                                                    <button class="btn btn-success btn-sm">Disetujui</button>
                                                @elseif($d->status_informasi_supplier == 2)
                                                    <button class="btn btn-danger btn-sm">Ditolak</button>
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                <div class="btn-group">
                                                    @if($d->status_informasi_supplier == 0)
                                                    <a href="#" class="validasi_informasi_supplier btn btn-info btn-sm" id="{{ $d->id_informasi_supplier }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        Validasi
                                                    </a>
                                                    @else
                                                    <form action="/owner/data_supplier/informasi_supplier/{{ $d->id_informasi_supplier }}/batalkan_validasi_informasi_supplier" style="margin-left: 5px;" method="POST">
                                                        @csrf    
                                                        <a class="btn btn-danger btn-sm batalkan_validasi_informasi_supplier" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                            Batalkan
                                                        </a>
                                                    </form>
                                                    @endif
                                                    <!--<a href="#" class="edit_informasi_supplier btn btn-info btn-sm" id="{{ $d->id_informasi_supplier }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    <form action="/owner/data_supplier/informasi_supplier/{{ $d->id_informasi_supplier }}/delete_informasi_supplier" style="margin-left: 5px;" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm-informasi-supplier" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                        </a>
                                                    </form>-->
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $informasi_supplier->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Input Informasi Supplier --}}
<div class="modal modal-blur fade" id="modal-inputsupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/owner/data_supplier/informasi_supplier/store_informasi_supplier" method="POST" id="frmSpplr" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <select id="id_supplier" name="id_supplier" class="form-control">
                                    <option value="">-- Pilih Nama Supplier --</option>
                                    @foreach($nama_supplier as $d)
                                        <option value="{{ $d->id_supplier }}">
                                            {{ $d->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-coin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" /><path d="M12 7v10" /></svg>
                                </span>
                                <input type="number" value="" id="total_harga_informasi_supplier" class="form-control" name="total_harga_informasi_supplier" placeholder="Total Harga">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="file" id="nota_informasi_supplier" name="nota_informasi_supplier" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Masukkan Nota</label>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="bukti_terima_informasi_supplier" name="bukti_terima_informasi_supplier" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Masukkan Bukti Terima</label>
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


{{-- Modal Edit Supplier --}}
<div class="modal modal-blur fade" id="modal-editinformasisupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditforminformasisupplier">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Barang Terpakai --}}
<div class="modal modal-blur fade" id="modal-tambahbahansupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahbahansupplier">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Lihat Barang Supplier --}}
<div class="modal modal-blur fade" id="modal-lihatbarangsupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Barang Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbarangsupplier">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Nota --}}
<div class="modal modal-blur fade" id="modal-notainformasisupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadnotainformasisupplier">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Bukti Terima --}}
<div class="modal modal-blur fade" id="modal-buktiterimainformasisupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Terima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadbuktiterimainformasisupplier">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Validasi Data Supplier --}}
<div class="modal modal-blur fade" id="modal-valdiasiinformasisupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validasi Data Informasi Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadvalidasiforminformasisupplier">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#btnTambahInformasiSupplier").click(function(){
            $("#modal-inputsupplier").modal("show");
        });

        $(".edit_informasi_supplier").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/edit_informasi_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditforminformasisupplier").html(respond);
                }
            });
            $("#modal-editinformasisupplier").modal("show");
        });

        $(".tambah_barang_supplier").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/tambah_barang_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformtambahbahansupplier").html(respond);
                }
            });
            $("#modal-tambahbahansupplier").modal("show");
        });

        $(".lihat_barang_supplier").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/lihat_barang_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbarangsupplier").html(respond);
                }
            });
            $("#modal-lihatbarangsupplier").modal("show");
        });

        $(".nota_informasi_supplier").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/nota_informasi_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadnotainformasisupplier").html(respond);
                }
            });
            $("#modal-notainformasisupplier").modal("show");
        });

        $(".bukti_terima_informasi_supplier").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/bukti_terima_informasi_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadbuktiterimainformasisupplier").html(respond);
                }
            });
            $("#modal-buktiterimainformasisupplier").modal("show");
        });

        $(".validasi_informasi_supplier").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_supplier/informasi_supplier/validasi_informasi_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadvalidasiforminformasisupplier").html(respond);
                }
            });
            $("#modal-valdiasiinformasisupplier").modal("show");
        });


        $(".batalkan_validasi_informasi_supplier").click(function(e){
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


        $("#frmSpplr").submit(function(){
            var nama_supplier = $("#nama_supplier").val();
            var total_harga = $("#total_harga").val();
            var nota = $("#nota").val();
            var bukti_terima = $("#bukti_terima").val();
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
            } else if (total_harga==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Total Harga Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#total_harga").focus();
                  });
                return false;
            } else if (nota==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nota Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#nota").focus();
                  });
                return false;
            } else if (bukti_terima==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bukti Terima Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#bukti_terima").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush