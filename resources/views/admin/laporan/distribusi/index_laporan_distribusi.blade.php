@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Halaman
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="page-title mb-0">Dashboard Admin</h2>
                </div>
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
                                    <form action="/admin/laporan/distribusi" method="GET">
                                        <div class="row">
                                            <!--<div class="col-5">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="cari_nama_distributor" id="cari_nama_distributor" placeholder="Nama Distributor">
                                                </div>
                                            </div>-->
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <select name="cari_kecamatan_sekolah" id="cari_kecamatan_sekolah" class="form-select">
                                                        <option value="">Pilih Kecamatan</option>
                                                        @foreach ($data_kecamatan as $kecamatan)
                                                            <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
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
                                    @if(!$sudahCari)
                                        <div class="alert alert-info text-center">
                                            Silakan lakukan pencarian terlebih dahulu
                                        </div>
                                    @elseif($dataKosong)
                                        <div class="alert alert-warning text-center">
                                            Data tidak ditemukan
                                        </div>
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">Nama Distributor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_distributor }}</th>
                                                    <th colspan="4">Kecamatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $kecamatan_sekolah }}</th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align:center">No.</th>
                                                    <th style="text-align:center">Tanggal</th>
                                                    <th style="text-align:center">Tujuan</th>
                                                    <th style="text-align:center">Jumlah</th>
                                                    <th style="text-align:center">Menu</th>
                                                    <th style="text-align:center">Bukti</th>
                                                    <th style="text-align:center">Kendala</th>
                                                    <th style="text-align:center">Status</th>
                                                    <th style="text-align:center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($distribusi as $d)
                                                <tr>
                                                    <td style="text-align:center">{{ $loop->iteration + $distribusi->firstItem()-1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($d->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                    <td>{{ $d->tujuan_distribusi }}</td>
                                                    <td>{{ $d->jumlah_paket }}</td>
                                                    <td>{{ $d->menu_makanan }}</td>
                                                    <td style="text-align:center"> 
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <a href="#" class="bukti_pengiriman btn btn-info btn-sm" data-id="{{ $d->id_distribusi }}">
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
                                                    <td style="text-align:center"> 
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <a href="#" class="kendala_distribusi btn btn-info btn-sm" data-id="{{ $d->id_distribusi }}">
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
                                                    <td style="text-align:center">
                                                        @if ($d->status_distribusi==1)
                                                        <span class="badge bg-success">Telah Diterima</span>
                                                        @elseif ($d->status_distribusi==2)
                                                        <span class="badge bg-danger">Belum Diterima</span>
                                                        @else
                                                        <span class="badge bg-warning">Menunggu</span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align:center">
                                                        <div class="btn-group">
                                                            @if ($d->status_distribusi==0)
                                                            <a href="#" class="edit_laporan_distribusi btn btn-info btn-sm" id="{{ $d->id_distribusi }}" >
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                                Edit
                                                            </a>
                                                            @else
                                                            <a href="/admin/laporan/distribusi/{{ $d->id_distribusi }}/batalkan_distribusi" class="btn btn-sm bg-danger">
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-square-rounded-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10l4 4m0 -4l-4 4" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                                                                Batalkan
                                                            </a>
                                                            @endif
                                                            <!--<form action="/admin/laporan/distribusi/{{ $d->id_distribusi }}/delete_laporan_distribusi" style="margin-left: 5px;" method="POST">
                                                                @csrf
                                                                <a class="btn btn-danger btn-sm delete-confirm-kepaladapur" >
                                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                                    Hapus
                                                                </a>
                                                            </form>-->
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            {{ $distribusi->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Validasi Data Laporan Distribusi --}}
<div class="modal modal-blur fade" id="modal-editlaporandistribusi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validasi Data Laporan Distribusi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformlaporandistribusi">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Bukti Pengiriman --}}
<div class="modal modal-blur fade" id="modal-buktipengirimanlaporandistribusi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadnotainformasibuktipengiriman">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Kendala Distribusi --}}
<div class="modal modal-blur fade" id="modal-kendaladistribusi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kendala Distribusi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadkendaladistribusi">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $(".edit_laporan_distribusi").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/distribusi/edit_laporan_distribusi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformlaporandistribusi").html(respond);
                }
            });
            $("#modal-editlaporandistribusi").modal("show");
        });

        $(".delete-confirm-kepaladapur").click(function(e){
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

        $(".bukti_pengiriman").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/distribusi/bukti_pengiriman',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadnotainformasibuktipengiriman").html(respond);
                }
            });
            $("#modal-buktipengirimanlaporandistribusi").modal("show");
        });

        $(".kendala_distribusi").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/distribusi/kendala_distribusi',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadkendaladistribusi").html(respond);
                }
            });
            $("#modal-kendaladistribusi").modal("show");
        });
    });
</script>
@endpush