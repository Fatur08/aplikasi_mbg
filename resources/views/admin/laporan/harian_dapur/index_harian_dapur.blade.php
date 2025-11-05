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
                    <h2 class="page-title mb-0">Laporan Harian Dapur</h2>
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
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/admin/laporan/harian_dapur" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <select name="bulan" id="bulan" class="form-select">
                                                    <option value="">Bulan</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="id_menu_harian" id="id_menu_harian" class="form-select">
                                                    <option value="">Pilih Menu</option>
                                                    @foreach($menu_harian as $menu)
                                                        <option value="{{ $menu->id_menu_harian }}">{{ $menu->nama_menu_harian }}</option>
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
                                @foreach ($jadwal_menu_harian as $id_menu_harian => $menus)
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="7">
                                                Nama Menu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                {{ $menus->first()->nama_menu_harian ?? '-' }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center; width:3%">No</th>
                                            <th style="text-align: center; width:15%">Tanggal</th>
                                            <th style="text-align: center; width:10%">Porsi</th>
                                            <th style="text-align: center; width:15%">Bahan</th>
                                            <th style="text-align: center; width:15%">Kendala</th>
                                            <th style="text-align: center; width:15%">Status</th>
                                            <th style="text-align: center; width:10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td style="text-align: center; width:3%">{{ $no++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($menu->tanggal_jadwal_menu_harian)->translatedFormat('d F Y') }}</td>
                                                <td style="text-align: center; width:6%">{{ $menu->jumlah_porsi_menu_harian }}</td>
                                                <td style="text-align: center; width:25%"> 
                                                    <div class="align-items-center">
                                                        <!--<a href="#" class="tambah_bahan_terpakai btn btn-info btn-sm" data-id="{{ $menu->id_jadwal_menu_harian }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                                            <span>Tambah</span>
                                                        </a>-->
                                                        <a href="#" class="lihat_bahan_terpakai btn btn-info btn-sm" data-id="{{ $menu->id_jadwal_menu_harian }}">
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
                                                <td style="text-align: center; width:25%"> 
                                                    <div class="align-items-center">
                                                        <!--<a href="#" class="tambah_kendala btn btn-info btn-sm" data-id="{{ $menu->id_jadwal_menu_harian }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                                            <span>Tambah</span>
                                                        </a>-->
                                                        <a href="#" class="lihat_kendala btn btn-info btn-sm" data-id="{{ $menu->id_jadwal_menu_harian }}">
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
                                                <td style="text-align: center; width:10%">
                                                    @if ($menu->status_jadwal_menu_harian == 2)
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif ($menu->status_jadwal_menu_harian == 1)
                                                        <span class="badge bg-warning text-dark">Sekarang</span>
                                                    @elseif ($menu->status_jadwal_menu_harian == 3)
                                                        <span class="badge bg-danger text-dark">Tersisa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Besok</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; width:10%">
                                                    <div class="btn-group">
                                                        <form action="/kepala_dapur/menu_harian/{{ $menu->id_jadwal_menu_harian }}/delete_jadwal_menu_harian" style="margin-left: 5px;" method="POST">
                                                            @csrf
                                                            <a class="btn btn-danger btn-sm delete-confirm-menuharian" >
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
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Tambah Bahan Terpakai --}}
<div class="modal modal-blur fade" id="modal-tambahbahanterpakai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bahan Terpakai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahbahanterpakai">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Lihat Bahan Terpakai --}}
<div class="modal modal-blur fade" id="modal-lihatbahanterpakai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Bahan Terpakai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbahanterpakai">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Kendala --}}
<div class="modal modal-blur fade" id="modal-tambahkendala" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kendala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahkendala">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Lihat Kendala --}}
<div class="modal modal-blur fade" id="modal-lihatkendala" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Kendala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatkendala">
                
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


        $(".lihat_bahan_terpakai").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/harian_dapur/lihat_bahan_terpakai',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbahanterpakai").html(respond);
                }
            });
            $("#modal-lihatbahanterpakai").modal("show");
        });

        $(".lihat_kendala").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/harian_dapur/lihat_kendala',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatkendala").html(respond);
                }
            });
            $("#modal-lihatkendala").modal("show");
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

        $(".bahan_terpakai").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/harian_dapur/bahan_terpakai',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadbahanterpakai").html(respond);
                }
            });
            $("#modal-bahanterpakai").modal("show");
        });

        $(".bahan_sisa").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/harian_dapur/bahan_sisa',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadbahansisa").html(respond);
                }
            });
            $("#modal-bahansisa").modal("show");
        });

        $(".kendala_harian_dapur").click(function(){
            var tanggal = $(this).attr('tanggal-id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/harian_dapur/kendala_harian_dapur',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    tanggal : tanggal
                },
                success:function(respond){
                    $("#loadkendalahariandapur").html(respond);
                }
            });
            $("#modal-kendalahariandapur").modal("show");
        });
    });
</script>
@endpush