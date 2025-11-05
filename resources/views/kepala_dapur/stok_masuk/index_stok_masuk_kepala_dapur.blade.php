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
                                    Stok Masuk
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahStokMasuk">
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
                                <form action="/kepala_dapur/stok_masuk" method="GET">
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
                                                <select name="id_bahan" id="id_bahan" class="form-select">
                                                    <option value="">Pilih Bahan</option>
                                                    @foreach($bahan as $item)
                                                        <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }}</option>
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
                                @if(!$sudahCari)
                                    <div class="alert alert-info text-center">
                                        Silakan lakukan pencarian terlebih dahulu
                                    </div>
                                @elseif($dataKosong)
                                    <div class="alert alert-warning text-center">
                                        Data tidak ditemukan
                                    </div>
                                @else
                                    @if($filter_bulan && !$filter_bahan)
                                        @foreach($stok->groupBy('nama_bahan') as $namaBahan => $items)
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">{{ $loop->iteration }}. Nama Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $namaBahan }}</th>
                                                        <th colspan="4">Sisa Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $items->sum('sisa_stok') }} {{ $stok->first()->satuan_bahan ?? '' }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Tanggal</th>
                                                        <th>Jumlah</th>
                                                        <th>Sisa</th>
                                                        <th>Sumber</th>
                                                        <th>Ket.</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($items as $key => $data)
                                                        <tr @if($data->sisa_stok == 0 || $data->status_stok = 0) class="table-warning" @endif>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                            <td>{{ $data->jumlah_masuk }} {{ $data->satuan_bahan }}</td>
                                                            <td>
                                                                @if($data->sisa_stok == 0)
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span>{{ $data->sisa_stok }} {{ $data->satuan_bahan }}</span>
                                                                        <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                                    </div>
                                                                @else
                                                                    {{ $data->sisa_stok }} {{ $data->satuan_bahan }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $data->sumber_stok_masuk }}</td>
                                                            <td>{{ $data->keterangan_stok_masuk }}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <form action="/kepala_dapur/stok_masuk/{{ $data->id_stok_masuk }}/delete_stok_masuk" style="margin-left: 5px;" method="POST">
                                                                        @csrf
                                                                        <a class="btn btn-danger btn-sm delete-confirm-stokmasuk" >
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
                                    @elseif($filter_bulan && $filter_bahan)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">Nama Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_bahan_filter }}</th>
                                                    <th colspan="4">Sisa Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $total_sisa_keseluruhan }} {{ $stok->first()->satuan_bahan ?? '' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Sisa</th>
                                                    <th>Sumber</th>
                                                    <th>Ket.</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stok as $key => $data)
                                                    <tr @if($data->sisa_stok == 0 || $data->status_stok = 0) class="table-warning" @endif>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->jumlah_masuk }} {{ $data->satuan_bahan }}</td>
                                                        <td>
                                                            @if($data->sisa_stok == 0)
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>{{ $data->sisa_stok }} {{ $data->satuan_bahan }}</span>
                                                                    <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                                </div>
                                                            @else
                                                                {{ $data->sisa_stok }} {{ $data->satuan_bahan }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $data->sumber_stok_masuk }}</td>
                                                        <td>{{ $data->keterangan_stok_masuk }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/stok_masuk/{{ $data->id_stok_masuk }}/delete_stok_masuk" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-stokmasuk" >
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
                                    @elseif(!$filter_bulan && $filter_bahan)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">Nama Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_bahan_filter }}</th>
                                                    <th colspan="4">Sisa Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $total_sisa_keseluruhan }} {{ $stok->first()->satuan_bahan ?? '' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Sisa</th>
                                                    <th>Sumber</th>
                                                    <th>Ket.</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stok as $key => $data)
                                                    <tr @if($data->sisa_stok == 0 || $data->status_stok = 0) class="table-warning" @endif>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->jumlah_masuk }} {{ $data->satuan_bahan }}</td>
                                                        <td>
                                                            @if($data->sisa_stok == 0)
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>{{ $data->sisa_stok }} {{ $data->satuan_bahan }}</span>
                                                                    <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                                </div>
                                                            @else
                                                                {{ $data->sisa_stok }} {{ $data->satuan_bahan }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $data->sumber_stok_masuk }}</td>
                                                        <td>{{ $data->keterangan_stok_masuk }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/stok_masuk/{{ $data->id_stok_masuk }}/delete_stok_masuk" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-stokmasuk" >
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


{{-- Modal Input Stok Masuk --}}
<div class="modal modal-blur fade" id="modal-inputstokmasuk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stok Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kepala_dapur/stok_masuk/store_stok_masuk" method="POST" id="frmStkMsk" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_masuk" name="tanggal_masuk" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bowl-chopsticks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1z" /><path d="M19 7l-14 1" /><path d="M19 2l-14 3" /></svg>                                </span>
                                <input type="text" value="" id="nama_bahan" class="form-control" name="nama_bahan" placeholder="Masukkan Nama Bahan (Jika Belum Ada)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <select name="id_bahan" id="id_bahan" class="form-select">
                                <option value="">Pilih Bahan (Yang Tersedia Di Dapur)</option>
                                @foreach($bahan as $item)
                                    <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calculator"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" /><path d="M8 14l0 .01" /><path d="M12 14l0 .01" /><path d="M16 14l0 .01" /><path d="M8 17l0 .01" /><path d="M12 17l0 .01" /><path d="M16 17l0 .01" /></svg>
                                </span>
                                <input type="number" value="" id="jumlah_masuk" class="form-control" name="jumlah_masuk" placeholder="Masukkan Jumlah Stok Masuk">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-number-1-small"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 8h1v8" /></svg>
                                </span>
                                <input type="text" value="" id="satuan_bahan" class="form-control" name="satuan_bahan" placeholder="Masukkan Satuan Stok Yang Terkecil">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-bitbucket"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.648 4a.64 .64 0 0 0 -.64 .744l3.14 14.528c.07 .417 .43 .724 .852 .728h10a.644 .644 0 0 0 .642 -.539l3.35 -14.71a.641 .641 0 0 0 -.64 -.744l-16.704 -.007z" /><path d="M14 15h-4l-1 -6h6z" /></svg>
                                </span>
                                <input type="text" value="" id="sumber_stok_masuk" class="form-control" name="sumber_stok_masuk" placeholder="Masukkan Sumber Masuk Stok">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                        class="icon icon-tabler icon-tabler-note">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M13 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l6 6v8a2 2 0 0 1 -2 2h-3" />
                                        <path d="M13 4v6h6" />
                                    </svg>
                                </span>
                                <textarea id="keterangan_stok_masuk" name="keterangan_stok_masuk" class="form-control" rows="1" placeholder="Tuliskan keterangan di sini..."></textarea>
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


{{-- Modal Edit Stok Masuk --}}
<div class="modal modal-blur fade" id="modal-editstokmasuk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Stok Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformstokmasuk">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#tanggal_masuk").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });



        $("#btnTambahStokMasuk").click(function(){
            $("#modal-inputstokmasuk").modal("show");
        });

        $(".edit_stok_masuk").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/kepala_dapur/stok_masuk/edit_stok_masuk',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformstokmasuk").html(respond);
                }
            });
            $("#modal-editstokmasuk").modal("show");
        });

        $(".delete-confirm-stokmasuk").click(function(e){
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

        $("#frmStkMsk").submit(function(){
            var tanggal_masuk = $("#tanggal_masuk").val();
            var jumlah_masuk = $("#jumlah_masuk").val();
            var satuan_bahan = $("#satuan_bahan").val();
            var sumber_stok_masuk = $("#sumber_stok_masuk").val();
            var keterangan_stok_masuk = $("#keterangan_stok_masuk").val();
            var nama_bahan = $("#nama_bahan").val();
            var id_bahan = $("#id_bahan").val();
            if(tanggal_masuk==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#tanggal_masuk").focus();
                  });
                return false;
            } else if (jumlah_masuk==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jumlah Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#jumlah_masuk").focus();
                  });
                return false;
            } else if (satuan_bahan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Satuan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#satuan_bahan").focus();
                  });
                return false;
            } else if (sumber_stok_masuk==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Sumber Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#sumber_stok_masuk").focus();
                  });
                return false;
            } else if (keterangan_stok_masuk==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#keterangan_stok_masuk").focus();
                  });
                return false;
            }
        });

        flatpickr("#dari_tanggal", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
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
</script>
@endpush