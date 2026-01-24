@extends('layouts.owner.tabler')
@section('content')
<style>
.section-info {
    margin-bottom: 15px;
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
                                    Data Koperasi
                                </h2>
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
                                <form action="/owner/data_koperasi" method="GET" id="FormDataKoperasiOwner">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                    <option value="">Pilih Dapur</option>
                                                    @foreach($dapurList as $dapur)
                                                        <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
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
                                </form>
                                        <div class="col-md-6">
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
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Barang</th>
                                                    <th>Harga</th>
                                                    <th>Bukti</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($data_koperasi as $no => $d)
                                                <tr>
                                                    <td>{{ $no + 1 }}</td>

                                                    <td>{{ $d->tanggal_format }}</td>

                                                    <td>
                                                        <div class="align-items-center">
                                                            <!--<a href="#" class="tambah_barang_modal_keluar btn btn-info btn-sm"
                                                               data-id="{{ $d->id_data_koperasi }}">
                                                                ➕ Tambah
                                                            </a>-->

                                                            <a href="#" class="lihat_barang_modal_keluar btn btn-info btn-sm"
                                                               data-id="{{ $d->id_data_koperasi }}">
                                                                👁 Lihat
                                                            </a>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        Rp. {{ number_format($d->total_harga, 0, ',', '.') }}
                                                    </td>

                                                    <td class="text-center">
                                                        <a href="#" class="bukti_terima_data_koperasi btn btn-info btn-sm"
                                                           data-id="{{ $d->id_data_koperasi }}">
                                                            👁 Lihat
                                                        </a>
                                                    </td>

                                                    <td>
                                                        @if($d->status_data_koperasi == 0)
                                                            <button class="btn btn-warning btn-sm">Menunggu</button>
                                                        @elseif($d->status_data_koperasi == 1)
                                                            <button class="btn btn-success btn-sm">Disetujui</button>
                                                        @else
                                                            <button class="btn btn-danger btn-sm">Ditolak</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        Data koperasi belum tersedia
                                                    </td>
                                                </tr>
                                                @endforelse
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






{{-- Modal Lihat Barang Modal Keluar --}}
<div class="modal modal-blur fade" id="modal-lihatbarangmodalkeluar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Barang Data Koperasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbarangmodalkeluar">
                
            </div>
        </div>
    </div>
</div>




{{-- Modal Lihat Bukti Terima Data Koperasi --}}
<div class="modal modal-blur fade" id="modal-lihatbuktiterima" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Bukti Terima</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbuktiterima">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
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


        $(".bukti_terima_data_koperasi").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/data_koperasi/bukti_terima_data_koperasi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbuktiterima").html(respond);
                }
            });
            $("#modal-lihatbuktiterima").modal("show");
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

        $("#FormDataKoperasiOwner").submit(function(){
            var pilih_dapur         = $("#pilih_dapur").val();
            var dari_tanggal        = $("#dari_tanggal").val();
            var sampai_tanggal      = $("#sampai_tanggal").val();
            if(pilih_dapur==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#pilih_dapur").focus();
                  });
                return false;
            } else if (dari_tanggal==""){
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
    });

    $("#cetak_data_koperasi").click(function(e){
        e.preventDefault(); // Mencegah langsung pindah halaman
            
        var pilih_dapur    = $("#pilih_dapur").val();
        var dari_tanggal   = $("#dari_tanggal").val();
        var sampai_tanggal = $("#sampai_tanggal").val();
            
        if(pilih_dapur == ""){
            Swal.fire({
                title: 'Warning!',
                text: 'Dapur Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(()=> {
                $("#pilih_dapur").focus();
            });
            return false;
        
        } else if(dari_tanggal == ""){
            Swal.fire({
                title: 'Warning!',
                text: 'Dari Tanggal Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(()=> {
                $("#dari_tanggal").focus();
            });
            return false;
        } else if(sampai_tanggal == ""){
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
    
        // ✅ JIKA SUDAH LENGKAP, BARU BUKA HALAMAN CETAK
        let url = `/owner/data_koperasi/cetak_data_koperasi?dapur=${pilih_dapur}&dari_tanggal=${dari_tanggal}&sampai_tanggal=${sampai_tanggal}`;
        window.open(url, '_blank');
    });
</script>
@endpush