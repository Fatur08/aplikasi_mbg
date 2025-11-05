@extends('layouts.owner.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Halaman
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="page-title mb-0">Informasi Pengiriman</h2>
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
                                    <form action="/owner/informasi/pengiriman" method="GET">
                                        <div class="row">
                                            <!--<div class="col-5">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="cari_nama_distributor" id="cari_nama_distributor" placeholder="Nama Distributor">
                                                </div>
                                            </div>-->
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <select name="cari_kecamatan_sekolah" id="cari_kecamatan_sekolah" class="form-select">
                                                        <option value="">Kecamatan</option>
                                                        <option value="Bandar Sribhawono">Bandar Sribhawono</option>
                                                        <option value="Batanghari">Batanghari</option>
                                                        <option value="Batanghari Nuban">Batanghari Nuban</option>
                                                        <option value="Braja Selebah">Braja Selebah</option>
                                                        <option value="Bumi Agung">Bumi Agung</option>
                                                        <option value="Gunung Pelindung">Gunung Pelindung</option>
                                                        <option value="Jabung">Jabung</option>
                                                        <option value="Labuhan Maringgai">Labuhan Maringgai</option>
                                                        <option value="Labuhan Ratu">Labuhan Ratu</option>
                                                        <option value="Marga Sekampung">Marga Sekampung</option>
                                                        <option value="Marga Tiga">Marga Tiga</option>
                                                        <option value="Mataram Baru">Mataram Baru</option>
                                                        <option value="Melinting">Melinting</option>
                                                        <option value="Metro Kibang">Metro Kibang</option>
                                                        <option value="Pasir Sakti">Pasir Sakti</option>
                                                        <option value="Pekalongan">Pekalongan</option>
                                                        <option value="Purbolinggo">Purbolinggo</option>
                                                        <option value="Raman Utara">Raman Utara</option>
                                                        <option value="Sekampung">Sekampung</option>
                                                        <option value="Sekampung Udik">Sekampung Udik</option>
                                                        <option value="Sukadana">Sukadana</option>
                                                        <option value="Waway Karya">Waway Karya</option>
                                                        <option value="Way Bungur">Way Bungur</option>
                                                        <option value="Way Jepara">Way Jepara</option>
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
                                                    <th colspan="3">Nama Distributor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_distributor }}</th>
                                                    <th colspan="3">Kecamatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $kecamatan_sekolah }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Tujuan</th>
                                                    <th>Bukti</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($distribusi as $d)
                                                <tr>
                                                    <td>{{ $loop->iteration + $distribusi->firstItem()-1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($d->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                    <td>{{ $d->tujuan_distribusi }}</td>
                                                    <td> 
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
                                                    <td>
                                                        @if ($d->status_distribusi==1)
                                                        <span class="badge bg-success">Sampai Tujuan</span>
                                                        @elseif ($d->status_distribusi==2)
                                                        <span class="badge bg-danger">Gagal Di Proses</span>
                                                        @else
                                                        <span class="badge bg-warning">Dalam Proses</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="edit_laporan_distribusi btn btn-info btn-sm" id="{{ $d->id_distribusi }}" >
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                                Edit
                                                            </a>
                                                            <form action="/owner/laporan/distribusi/{{ $d->id_distribusi }}/delete_laporan_distribusi" style="margin-left: 5px;" method="POST">
                                                                @csrf
                                                                <a class="btn btn-danger btn-sm delete-confirm-kepaladapur" >
                                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                                    Hapus
                                                                </a>
                                                            </form>
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
                url:'/owner/laporan/distribusi/edit_laporan_distribusi',
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
                url:'/owner/laporan/distribusi/bukti_pengiriman',
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
                url:'/owner/laporan/distribusi/kendala_distribusi',
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