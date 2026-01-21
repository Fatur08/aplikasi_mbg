@extends('layouts.admin.tabler')
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
                                    Data Staff
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

                        <!-- ===== MAKER ===== -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ url('/admin/data_staff/maker') }}" class="btn btn-primary w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/maker.png') }}" class="img-fluid mb-2" width="80"> -->

                                    <div class="fw-bold fs-2 text-white">MAKER</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== SPPI & AHLI GIZI ===== -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/sppi') }}" class="btn btn-success w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/sppi.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">SPPI</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/ahli-gizi') }}" class="btn btn-warning w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/ahli-gizi.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-dark">Ahli Gizi</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== AKUNTAN & ASLAP ===== -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/akuntan') }}" class="btn btn-info w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/akuntan.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Akuntan</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/aslap') }}" class="btn btn-secondary w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/aslap.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Aslap</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== DRIVER & RELAWAN ===== -->
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/driver') }}" class="btn btn-dark w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/driver.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Driver</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/admin/data_staff/relawan') }}" class="btn btn-danger w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/relawan.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Relawan</div>
                                </a>
                            </div>
                        </div>

                    </div>
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
                                <form action="/admin/data_induk/" method="GET">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>













<!-- DATA MAKER (ADMIN) -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">MAKER</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahMaker">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data Maker
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>E-Mail</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>Foto</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
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
                                                    <td></td>
                                                    <!--<td>
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
                                                    </td>-->
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

























<!-- DATA SPPI (SARJANA PENGGERAK PEMBANGUNAN INDONESIA) -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">SPPI</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahSPPI">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data SPPI
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>E-Mail</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>Foto</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
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
                                                    <td></td>
                                                    <!--<td>
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
                                                    </td>-->
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














<!-- DATA AHLI GIZI -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">AHLI GIZI</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahAhliGizi">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data Ahli Gizi
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>E-Mail</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>Foto</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
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
                                                    <td></td>
                                                    <!--<td>
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
                                                    </td>-->
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

















<!-- DATA AKUNTAN -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">AKUNTAN</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahAkuntan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data Akuntan
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>E-Mail</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>Foto</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
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
                                                    <td></td>
                                                    <!--<td>
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
                                                    </td>-->
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



















<!--<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">DISTRIBUTOR</span></h2>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->


















<!-- ASLAP (ASISTEN LAPANGAN) -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">ASLAP</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahAslap">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data Aslap
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
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
                                                @foreach ($aslap as $d)
                                                @php
                                                    $path = Storage::url('uploads/data_induk/aslap/foto/'.$d->foto_aslap);
                                                @endphp
                                                <tr>
                                                    <td style="text-align:center">{{ $loop->iteration + $aslap->firstItem()-1 }}</td>
                                                    <td>{{ $d->nama_aslap }}</td>
                                                    <td>{{ $d->peran_aslap }}</td>
                                                    <td>{{ $d->no_hp_aslap }}</td>
                                                    <td style="text-align:center">
                                                        @if (empty($d->foto_aslap))
                                                        <img src="{{ asset('assets/img/nophoto.jpg') }}" class="avatar" alt="">
                                                        @else
                                                        <img src="{{ url($path) }}" class="avatar" alt="">
                                                        @endif
                                                    </td>
                                                    <td style="text-align:center"> 
                                                        <a href="#" class="ktp_aslap btn btn-info btn-sm" id="{{ $d->id_aslap }}">
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
                                                        @if($d->status_validasi_aslap == 0)
                                                            <button class="btn btn-warning btn-sm">Menunggu</button>
                                                        @elseif($d->status_validasi_aslap == 1)
                                                            <button class="btn btn-success btn-sm">Disetujui</button>
                                                        @elseif($d->status_validasi_aslap == 2)
                                                            <button class="btn btn-danger btn-sm">Ditolak</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="edit_aslap btn btn-info btn-sm" id="{{ $d->id_aslap }}" >
                                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                                Edit
                                                            </a>
                                                            <form action="/owner/data_induk/aslap/{{ $d->id_aslap }}/delete_aslap" style="margin-left: 5px;" method="POST">
                                                                @csrf
                                                                <a class="btn btn-danger btn-sm delete-confirm-aslap" >
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ $aslap->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>














<!-- DRIVER -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row table-container">
                            <div class="col-12">
                                <div class="section-info">
                                    <div class="info-card">
                                        <h2><span style="color:#2563eb;">DRIVER</span></h2>
                                        <a href="#" class="btn btn-primary" id="btnTambahDriver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Tambah Data Driver
                                        </a>
                                    </div>
                                </div>
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama</th>
                                                    <th>E-Mail</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>Foto</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
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
                                                    <td></td>
                                                    <!--<td>
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
                                                    </td>-->
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














<!-- BAGIAN ASLAP -->
<div class="modal modal-blur fade" id="modal-inputdataaslap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Aslap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/data_induk/aslap/store_aslap" method="POST" id="FormDataAslap" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <input type="text" value="" id="nama_aslap" class="form-control" name="nama_aslap" placeholder="Masukkan Nama Data Pekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                                </span>
                                <input type="text" value="" id="peran_aslap" class="form-control" name="peran_aslap" placeholder="Masukkan Peran Data Pekerja">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="old_peran_aslap" id="old_peran_aslap" class="form-select">
                                <option value="">Pilih Peran (Jika Sebelumnya Ada)</option>
                                @foreach($peranList as $peran)
                                    <option value="{{ $peran->peran_aslap }}">
                                        {{ $peran->peran_aslap }}
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
                                <input type="text" value="" id="no_hp_aslap" class="form-control" name="no_hp_aslap" placeholder="Masukkan No. HP Data Pekerja">
                              </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="nomor_dapur_aslap" id="nomor_dapur_aslap" class="form-select">
                                <option value="">Pilih Dapur</option>
                                @foreach($dapurList as $dapur)
                                    <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="foto_aslap" name="foto_aslap" class="form-control">
                        </div>
                        <div class="col-6 mt-2">
                            <label>Masukkan Foto Pengenal</label>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <input type="file" id="ktp_aslap" name="ktp_aslap" class="form-control">
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
        $("#btnTambahAslap").click(function(){
            $("#modal-inputdataaslap").modal("show");
        });

        $(".edit_aslap").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/data_induk/aslap/edit_aslap',
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


        $(".ktp_aslap").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/data_induk/aslap/ktp_aslap',
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


        $(".delete-confirm-aslap").click(function(e){
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

        $("#FormDataAslap").submit(function(){
            var nama_lengkap = $("#nama_lengkap").val();
            var email = $("#email").val();
            var alamat = $("#alamat").val();
            var no_hp = $("#no_hp").val();
            var foto = $("#FormDataAslap").find("#foto").val();
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