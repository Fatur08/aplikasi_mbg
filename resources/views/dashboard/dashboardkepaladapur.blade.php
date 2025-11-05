@extends('layouts.kepala_dapur.tabler')
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
                                    Dashboard Kepala Dapur
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahDataHarianDapur">
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
                            <div class="col-12">
                                <form action="/kepala_dapur/dashboardkepaladapur" method="GET">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <select name="cari_kecamatan_harian_dapur" id="cari_kecamatan_harian_dapur" class="form-select">
                                                    <option value="">Pilih Kecamatan</option>
                                                    @foreach ($data_kecamatan as $kecamatan)
                                                        <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <select name="cari_sekolah_harian_dapur" id="cari_sekolah_harian_dapur" class="form-select">
                                                    <option value="">Pilih Sekolah</option>
                                                    @foreach($sekolahList as $sekolah)
                                                        <option value="{{ $sekolah }}" {{ $sekolah == $searchSekolah ? 'selected' : '' }}>
                                                            {{ $sekolah }}
                                                        </option>
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
                                    @if($searchKecamatan && !$searchSekolah)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Nama Distributor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $laporan->first()->nama_distributor ?? '-' }}</th>
                                                    <th colspan="4">Kecamatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $searchKecamatan }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        @foreach($laporan->groupBy('tujuan_distribusi') as $tujuanDistribusi => $items)
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="7">{{ $loop->iteration }}. Nama Sekolah&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $tujuanDistribusi }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:center">No.</th>
                                                        <th style="text-align:center">Tanggal</th>
                                                        <th style="text-align:center">Menu</th>
                                                        <th style="text-align:center">J.Porsi</th>
                                                        <th style="text-align:center">Bahan</th>
                                                        <th style="text-align:center">Status</th>
                                                        <th style="text-align:center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($items as $key => $data)
                                                        <tr>
                                                            <td style="text-align:center">{{ $key+1 }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                            <td>{{ $data->menu_makanan }}</td>
                                                            <td>{{ $data->jumlah_paket }}</td>
                                                            <td style="text-align:center"> 
                                                                <div class="align-items-center">
                                                                    <a href="#" class="bahan_terpakai btn btn-info btn-sm" data-id="{{ $data->id_distribusi }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                     c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                        </svg>
                                                                        <span>Terpakai</span>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td style="text-align:center">
                                                                @if ($data->status_distribusi==1)
                                                                <span class="badge bg-success">Telah Diterima</span>
                                                                @elseif ($data->status_distribusi==2)
                                                                <span class="badge bg-danger">Belum Diterima</span>
                                                                @else
                                                                <span class="badge bg-warning">Menunggu</span>
                                                                @endif
                                                            </td>
                                                            <td style="text-align:center">
                                                                <div class="btn-group">
                                                                    <form action="/kepala_dapur/dashboardkepaladapur/{{ $data->id_distribusi }}/delete_laporan_distribusi_kepala_dapur" style="margin-left: 5px;" method="POST">
                                                                        @csrf
                                                                        <a class="btn btn-danger btn-sm delete-confirm-laporandistribusikepaladapur" >
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
                                    @elseif($searchKecamatan && $searchSekolah)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Nama Distributor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $laporan->first()->nama_distributor ?? '-' }}</th>
                                                    <th colspan="4">Kecamatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $searchKecamatan }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="7">Nama Sekolah&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $searchSekolah }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Menu</th>
                                                    <th>J.Porsi</th>
                                                    <th>Bahan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($laporan as $key => $data)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->menu_makanan }}</td>
                                                        <td>{{ $data->jumlah_paket }}</td>
                                                        <td> 
                                                            <div class="align-items-center">
                                                                <a href="#" class="bahan_terpakai btn btn-info btn-sm" data-id="{{ $data->id_distribusi }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                    </svg>
                                                                    <span>Terpakai</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($data->status_distribusi==1)
                                                            <span class="badge bg-success">Disetujui</span>
                                                            @elseif ($data->status_distribusi==2)
                                                            <span class="badge bg-danger">Ditolak</span>
                                                            @else
                                                            <span class="badge bg-warning">Menunggu</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/dashboardkepaladapur/{{ $data->id_distribusi }}/delete_laporan_distribusi_kepala_dapur" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-laporandistribusikepaladapur" >
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
                                    @elseif(!$searchKecamatan && $searchSekolah)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Nama Distributor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $laporan->first()->nama_distributor ?? '-' }}</th>
                                                    <th colspan="4">Kecamatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="7">Nama Sekolah&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $searchSekolah }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Menu</th>
                                                    <th>J.Porsi</th>
                                                    <th>Bahan</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($laporan as $key => $data)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->menu_makanan }}</td>
                                                        <td>{{ $data->jumlah_paket }}</td>
                                                        <td> 
                                                            <div class="align-items-center">
                                                                <a href="#" class="bahan_terpakai btn btn-info btn-sm" data-id="{{ $data->id_distribusi }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                    </svg>
                                                                    <span>Terpakai</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($data->status_distribusi==1)
                                                            <span class="badge bg-success">Disetujui</span>
                                                            @elseif ($data->status_distribusi==2)
                                                            <span class="badge bg-danger">Ditolak</span>
                                                            @else
                                                            <span class="badge bg-warning">Menunggu</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/dashboardkepaladapur/{{ $data->id_distribusi }}/delete_laporan_distribusi_kepala_dapur" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-laporandistribusikepaladapur" >
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Data Harian Dapur --}}
<div class="modal modal-blur fade" id="modal-inputdatahariandapur" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Harian Dapur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kepala_dapur/dashboardkepaladapur/store_harian_dapur_kepala_dapur" method="POST" id="frmHrnDpr" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="kecamatan_sekolah" id="kecamatan_sekolah" class="form-select">
                                <option value="">Pilih Kecamatan (Berdasarkan Dapur)</option>
                                @foreach ($data_kecamatan as $kecamatan)
                                    <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-school"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                                </span>
                                <input type="text" value="" id="sekolah_tujuan" class="form-control" name="sekolah_tujuan" placeholder="Masukkan Nama Sekolah (Jika Belum Ada)">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="tujuan_distribusi" id="tujuan_distribusi" class="form-select">
                                <option value="">Pilih Sekolah (Yang Sudah Ada)</option>
                                @foreach($sekolahList as $sekolah)
                                    <option value="{{ $sekolah }}" {{ $sekolah == $searchSekolah ? 'selected' : '' }}>
                                        {{ $sekolah }}
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
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_distribusi" name="tanggal_distribusi" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <select name="nama_menu_harian" id="nama_menu_harian" class="form-select">
                                <option value="">Pilih Menu (Yang Tersedia Hari Ini)</option>
                                @foreach($menu_harian as $menu)
                                    <option value="{{ $menu->nama_menu_harian }}">{{ $menu->nama_menu_harian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-package"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                                </span>
                                <input type="number" value="" id="jumlah_paket" class="form-control" name="jumlah_paket" placeholder="Masukkan Jumlah Porsi">
                              </div>
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


{{-- Modal Edit Data Harian Dapur --}}
<div class="modal modal-blur fade" id="modal-editadmin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Harian Dapur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformadmin">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Bahan Terpakai --}}
<div class="modal modal-blur fade" id="modal-bahanterpakai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bahan Yang Terpakai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadbahanterpakai">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Bahan Sisa --}}
<div class="modal modal-blur fade" id="modal-bahansisa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bahan Yang Tersisa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadbahansisa">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#tanggal_distribusi").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });



        $("#btnTambahDataHarianDapur").click(function(){
            $("#modal-inputdatahariandapur").modal("show");
        });


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

        $(".delete-confirm-laporandistribusikepaladapur").click(function(e){
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
                url:'/owner/laporan/harian_dapur/bahan_terpakai',
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
                url:'/owner/laporan/harian_dapur/bahan_sisa',
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
    });
</script>
@endpush