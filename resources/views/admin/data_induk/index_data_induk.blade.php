@extends('layouts.admin.tabler')
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
                                    Data Induk
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahDapur">
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
                                <form action="/admin/data_induk/kepala_dapur" method="GET">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="cari_nama" id="cari_nama" placeholder="Nama Lengkap" value="{{ Request('nama_lengkap_cari') }}">
                                            </div>
                                        </div>
                                        <!--<div class="col-4">
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
                                        </div>-->
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
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="m-0">KEPALA DAPUR</h3>
                                    <a href="#" class="btn btn-primary" id="btnTambahDapur">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Tambah Kepala Dapur
                                    </a>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>E-Mail</th>
                                            <th>Alamat</th>
                                            <th>No. HP</th>
                                            <th>Foto</th>
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
                                            <td>{{ $loop->iteration }}</td>
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
                                            <td>{{ $d->password }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="edit_kepala_dapur btn btn-info btn-sm" id="{{ $d->id }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    <form action="/admin/data_induk/kepala_dapur/{{ $d->id }}/delete_kepala_dapur" style="margin-left: 5px;" method="POST">
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
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="m-0">DISTRIBUTOR</h3>
                                    <a href="#" class="btn btn-primary" id="btnTambahDapur">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Tambah Distributor
                                    </a>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>E-Mail</th>
                                            <th>Alamat</th>
                                            <th>No. HP</th>
                                            <th>Foto</th>
                                            <th>Password</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributor as $d)
                                        @php
                                            $path = Storage::url('uploads/data_induk/distributor/'.$d->foto_distributor);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->nama_distributor }}</td>
                                            <td>{{ $d->email_distributor }}</td>
                                            <td>{{ $d->alamat_distributor }}</td>
                                            <td>{{ $d->no_hp_distributor }}</td>
                                            <td>
                                                @if (empty($d->foto_distributor))
                                                <img src="{{ asset('assets/img/nophoto.jpg') }}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $d->password_distributor }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="edit_distributor btn btn-info btn-sm" id="{{ $d->id_distributor }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    <form action="/admin/data_induk/distributor/{{ $d->id_distributor }}/delete_distributor" style="margin-left: 5px;" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm-distributor" >
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
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="m-0">DATA PEKERJA</h3>
                                    <a href="#" class="btn btn-primary" id="btnTambahDataPekerja">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Tambah Data Pekerja
                                    </a>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">No.</th>
                                            <th style="text-align:center">Nama</th>
                                            <th style="text-align:center">Peran</th>
                                            <th style="text-align:center">No. HP</th>
                                            <th style="text-align:center">Foto</th>
                                            <th style="text-align:center">KTP</th>
                                            <th style="text-align:center">Validasi</th>
                                            <th style="text-align:center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_pekerja as $d)
                                        @php
                                            $path = Storage::url('uploads/data_induk/data_pekerja/foto/'.$d->foto_data_pekerja);
                                        @endphp
                                        <tr>
                                            <td style="text-align:center">{{ $loop->iteration + $data_pekerja->firstItem()-1 }}</td>
                                            <td>{{ $d->nama_data_pekerja }}</td>
                                            <td>{{ $d->peran_data_pekerja }}</td>
                                            <td>{{ $d->no_hp_data_pekerja }}</td>
                                            <td style="text-align:center">
                                                @if (empty($d->foto_data_pekerja))
                                                <img src="{{ asset('assets/img/nophoto.jpg') }}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td style="text-align:center"> 
                                                <a href="#" class="ktp_data_pekerja btn btn-info btn-sm" id="{{ $d->id_data_pekerja }}">
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
                                                @if($d->status_validasi_data_pekerja == 0)
                                                    <button class="btn btn-warning btn-sm">Menunggu</button>
                                                @elseif($d->status_validasi_data_pekerja == 1)
                                                    <button class="btn btn-success btn-sm">Disetujui</button>
                                                @elseif($d->status_validasi_data_pekerja == 2)
                                                    <button class="btn btn-danger btn-sm">Ditolak</button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="edit_data_pekerja btn btn-info btn-sm" id="{{ $d->id_data_pekerja }}" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                    <form action="/owner/data_induk/data_pekerja/{{ $d->id_data_pekerja }}/delete_data_pekerja" style="margin-left: 5px;" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm-data_pekerja" >
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
                        {{ $data_pekerja->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- BAGIAN DATA PEKERJA -->
{{-- Modal Input Data Pekerja --}}
<div class="modal modal-blur fade" id="modal-inputdatapekerja" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/data_induk/data_pekerja/store_data_pekerja" method="POST" id="frmDtPkrj" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <input type="text" value="" id="nama_data_pekerja" class="form-control" name="nama_data_pekerja" placeholder="Masukkan Nama Data Pekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                                </span>
                                <input type="text" value="" id="peran_data_pekerja" class="form-control" name="peran_data_pekerja" placeholder="Masukkan Peran Data Pekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="old_peran_data_pekerja" id="old_peran_data_pekerja" class="form-select">
                                <option value="">Pilih Peran (Jika Sebelumnya Ada)</option>
                                @foreach($peranList as $peran)
                                    <option value="{{ $peran->peran_data_pekerja }}">
                                        {{ $peran->peran_data_pekerja }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                </span>
                                <input type="text" value="" id="no_hp_data_pekerja" class="form-control" name="no_hp_data_pekerja" placeholder="Masukkan No. HP Data Pekerja">
                              </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="nomor_dapur_data_pekerja" id="nomor_dapur_data_pekerja" class="form-select">
                                <option value="">Pilih Dapur</option>
                                @foreach($dapurList as $dapur)
                                    <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="foto_data_pekerja" name="foto_data_pekerja" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Masukkan Foto Pengenal</label>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="ktp_data_pekerja" name="ktp_data_pekerja" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Foto KTP Data Pekerja</label>
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


{{-- Modal Edit Data Pekerja --}}
<div class="modal modal-blur fade" id="modal-editdatapekerja" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformdatapekerja">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal KTP Data Pekerja --}}
<div class="modal modal-blur fade" id="modal-ktpdatapekerja" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">KTP Data Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadktpdatapekerja">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#btnTambahDataPekerja").click(function(){
            $("#modal-inputdatapekerja").modal("show");
        });

        $(".edit_data_pekerja").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/data_induk/data_pekerja/edit_data_pekerja',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformdatapekerja").html(respond);
                }
            });
            $("#modal-editdatapekerja").modal("show");
        });


        $(".ktp_data_pekerja").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/data_induk/data_pekerja/ktp_data_pekerja',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadktpdatapekerja").html(respond);
                }
            });
            $("#modal-ktpdatapekerja").modal("show");
        });


        $(".delete-confirm-data_pekerja").click(function(e){
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

        $("#frmDtPkrj").submit(function(){
            var nama_lengkap = $("#nama_lengkap").val();
            var email = $("#email").val();
            var alamat = $("#alamat").val();
            var no_hp = $("#no_hp").val();
            var foto = $("#frmDtPkrj").find("#foto").val();
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