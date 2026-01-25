@extends('layouts.maker.tabler')
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
                                    Informasi Supplier
                                </h2>
                            </td>
                            <td style="text-align:right">
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
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/maker/data_supplier/informasi_supplier" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nama_supplier_cari" id="nama_supplier_cari" placeholder="Nama Supplier" value="{{ Request('nama_supplier_cari') }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
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
                        <div class="row mt-2 table-container">
                            <div class="col-12">
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center">No.</th>
                                                    <th style="text-align:center">Nama Supplier</th>
                                                    <th style="text-align:center">Barang</th>
                                                    <th style="text-align:center">Alamat</th>
                                                    <th style="text-align:center">No.Hp</th>
                                                    <th style="text-align:center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($informasi_supplier as $d)
                                                <tr>
                                                    <td style="text-align:center">{{ $loop->iteration + $informasi_supplier->firstItem()-1 }}</td>
                                                    <td>{{ $d->nama_informasi_supplier }}</td>
                                                    <td>
                                                        <div class="align-items-center">
                                                            <a href="#" class="tambah_barang_supplier btn btn-info btn-sm"
                                                               data-id="{{ $d->id_informasi_supplier }}">
                                                                ➕ Tambah
                                                            </a>

                                                            <a href="#" class="lihat_barang_modal_keluar btn btn-info btn-sm"
                                                               data-id="{{ $d->id_informasi_supplier }}">
                                                                👁 Lihat
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>{{ $d->alamat_informasi_supplier }}</td>
                                                    <td>{{ $d->no_hp_informasi_supplier }}</td>
                                                    <td style="text-align:center">
                                                        @if($d->status_informasi_supplier == 0)
                                                            <button class="btn btn-warning btn-sm">Menunggu</button>
                                                        @elseif($d->status_informasi_supplier == 1)
                                                            <button class="btn btn-success btn-sm">Disetujui</button>
                                                        @elseif($d->status_informasi_supplier == 2)
                                                            <button class="btn btn-danger btn-sm">Ditolak</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
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

{{-- Modal Input Informasi Supplier --}}
<div class="modal modal-blur fade" id="modal-inputsupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/maker/data_supplier/informasi_supplier/store_informasi_supplier" method="POST" id="frmSpplr" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <input type="text" value="" id="nama_supplier" class="form-control" name="nama_supplier" placeholder="Masukkan Nama Lengkap">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                </span>
                                <input type="text" value="" id="alamat_supplier" class="form-control" name="alamat_supplier" placeholder="Masukkan Alamat">
                              </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4
                                                 a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"/>
                                    </svg>
                                </span>
                    
                                <input
                                    type="text"
                                    id="no_hp_supplier"
                                    name="no_hp_supplier"
                                    class="form-control"
                                    placeholder="Masukkan Nomor HP"
                                    inputmode="numeric"
                                    pattern="[0-9]+"
                                    minlength="10"
                                    maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                >
                            </div>
                    
                            <small class="text-muted">
                                Nomor HP hanya boleh angka (contoh: 08xxxxxxxxxx)
                            </small>
                        </div>
                    </div>
                    <div class="row">
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


{{-- Modal Tambah Barang Supplier --}}
<div class="modal modal-blur fade" id="modal-tambahbarangsupplier" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahbarangsupplier">
                
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
@endsection
@push('myscript')
<script>
    $(function(){
        $("#btnTambahInformasiSupplier").click(function(){
            $("#modal-inputsupplier").modal("show");
        });

        

        $(".tambah_barang_supplier").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/maker/data_supplier/informasi_supplier/tambah_barang_supplier',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformtambahbarangsupplier").html(respond);
                }
            });
            $("#modal-tambahbarangsupplier").modal("show");
        });


        $(".lihat_barang_modal_keluar").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/maker/data_supplier/informasi_supplier/lihat_barang_supplier',
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