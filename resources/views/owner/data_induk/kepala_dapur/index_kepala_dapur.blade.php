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
                                    Data Kepala Dapur
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahkepaladapur">
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
                                <form action="/owner/data_induk/kepala_dapur" method="GET">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nama_lengkap_cari" id="nama_lengkap_cari" placeholder="Nama Lengkap" value="{{ Request('nama_lengkap_cari') }}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="dapur_kecamatan" id="dapur_kecamatan" class="form-select">
                                                    <option value="">Pilih Dapur</option>
                                                    <option value="1">Dapur 1</option>
                                                    <option value="2">Dapur 2</option>
                                                    <option value="3">Dapur 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="kecamatan_cari" id="kecamatan_cari" class="form-select">
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
                                        <div class="col-3">
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
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>E-Mail</th>
                                            <th>Alamat</th>
                                            <th>No. HP</th>
                                            <th>Foto</th>
                                            <th>Kecamatan</th>
                                            <th>Password</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kepala_dapur as $d)
                                        @php
                                            $path = Storage::url('uploads/data_induk/kepala_dapur/'.$d->foto);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration + $kepala_dapur->firstItem()-1 }}</td>
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->email }}</td>
                                            <td>{{ $d->alamat }}</td>
                                            <td>{{ $d->no_hp }}</td>
                                            <td>
                                                @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/nophoto.jpg') }}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $d->kecamatan }}</td>
                                            <td>{{ $d->password }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="edit_kepala_dapur btn btn-info btn-sm" id="{{ $d->id }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    <form action="/owner/data_induk/kepala_dapur/{{ $d->id }}/delete_kepala_dapur" style="margin-left: 5px;" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm-kepaladapur" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                        </a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $kepala_dapur->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Data Kepala Dapur --}}
<div class="modal modal-blur fade" id="modal-inputkepalacabang" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Kepala Dapur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/owner/data_induk/kepala_dapur/store_kepala_dapur" method="POST" id="frmKplDpr" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <input type="text" value="" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
                              </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="kecamatan" id="kecamatan" class="form-select">
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
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                                </span>
                                <input type="text" value="" id="email" class="form-control" name="email" placeholder="E-Mail">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-analytics"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" /><path d="M7 20l10 0" /><path d="M9 16l0 4" /><path d="M15 16l0 4" /><path d="M8 12l3 -3l2 2l3 -3" /></svg>
                                </span>
                                <input type="text" value="" id="alamat" class="form-control" name="alamat" placeholder="Alamat">
                              </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                </span>
                                <input type="text" value="" id="no_hp" class="form-control" name="no_hp" placeholder="Nomor HP">
                              </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="foto" name="foto" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Masukkan Foto Pengenal</label>
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


{{-- Modal Edit Data Kepala Cabang --}}
<div class="modal modal-blur fade" id="modal-editkepaladapur" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Kepala Cabang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformkepaladapur">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#btnTambahkepaladapur").click(function(){
            $("#modal-inputkepalacabang").modal("show");
        });

        $(".edit_kepala_dapur").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/data_induk/kepala_dapur/edit_kepala_dapur',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformkepaladapur").html(respond);
                }
            });
            $("#modal-editkepaladapur").modal("show");
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

        $("#frmKplDpr").submit(function(){
            var nama_lengkap = $("#nama_lengkap").val();
            var email = $("#email").val();
            var alamat = $("#alamat").val();
            var no_hp = $("#no_hp").val();
            var foto = $("#frmKplDpr").find("#foto").val();
            var kecamatan = $("#kecamatan").val();
            if(nama_lengkap==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Lengkap Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#nama_lengkap").focus();
                  });
                return false;
            } else if (email==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'E-Mail Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#email").focus();
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
            } else if (foto==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Foto Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#foto").focus();
                  });
                return false;
            } else if (kecamatan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Kecamatan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#kecamatan").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush