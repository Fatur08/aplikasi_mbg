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
                                    Laporan Keuangan
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahLaporanKeuangan">
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Sisa Seluruh Dana :</strong> Rp {{ number_format($sisa_dana, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/admin/laporan/keuangan" method="GET">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-icon">
                                                    <select name="cari_jenis_transaksi" id="cari_jenis_transaksi" class="form-select">
                                                        <option value="">Jenis Transaksi</option>
                                                        <option value="Pemasukan">Pemasukan</option>
                                                        <option value="Pengeluaran">Pengeluaran</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                        Cari    
                                                    </button>
                                                </div>
                                            </div>
                                    </form>
                                            <!--<div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-success w-100" id="cetak_laporan_keuangan" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        Cetak
                                                    </a>
                                                </div>
                                            </div>-->
                                        </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                                        <canvas id="koperasiChart" height="340"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    @php
                                        // Grup data berdasarkan tanggal laporan keuangan
                                        $grouped = $laporan_keuangan->groupBy(function ($item) {
                                            return \Carbon\Carbon::parse($item->tanggal_laporan_keuangan)->translatedFormat('d F Y');
                                        });
                                    @endphp
                                    
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th style="text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                                                <th style="text-align: center; vertical-align: middle;" rowspan="2">Tanggal</th>
                                                <th style="text-align: center; vertical-align: middle;" colspan="2">Sumber</th>
                                                <th style="text-align: center; vertical-align: middle;" colspan="2">Jumlah</th>
                                                <th style="text-align: center; vertical-align: middle;" rowspan="2">Selisih</th>
                                                <th style="text-align: center; vertical-align: middle;" rowspan="2">Validasi</th>
                                                <th style="text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center; vertical-align: middle;">Koperasi</th>
                                                <th style="text-align: center; vertical-align: middle;">Supplier</th>
                                                <th style="text-align: center; vertical-align: middle;">Pemasukan</th>
                                                <th style="text-align: center; vertical-align: middle;">Pengeluaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($grouped as $tanggal => $data_per_tanggal)
                                                @php
                                                    $pemasukan = $data_per_tanggal->where('jenis_transaksi', 'Pemasukan');
                                                    $pengeluaran = $data_per_tanggal->where('jenis_transaksi', 'Pengeluaran');
                                                    $total_pemasukan = $pemasukan->sum('jumlah_dana');
                                                    $total_pengeluaran = $pengeluaran->sum('jumlah_dana');
                                                    $selisih = $total_pemasukan - $total_pengeluaran;
                                    
                                                    // ambil id pertama (misalnya untuk tombol edit/hapus)
                                                    $id_laporan = optional($data_per_tanggal->first())->id_laporan_keuangan;

                                                    // cek apakah ada data koperasi atau supplier
                                                    $ada_koperasi = $data_per_tanggal->contains('id_data_koperasi', '!=', null);
                                                    $ada_supplier = $data_per_tanggal->contains('id_informasi_supplier', '!=', null);
                                                @endphp
                                    
                                                <tr style="text-align: center; vertical-align: middle;">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $tanggal }}</td>
                                                    {{-- kolom koperasi --}}
                                                    <td>
                                                        @if($ada_koperasi)
                                                            ✅
                                                        @endif
                                                    </td>

                                                    {{-- kolom supplier --}}
                                                    <td>
                                                        @if($ada_supplier)
                                                            ✅
                                                        @endif
                                                    </td>
                                                    <td>
                                                        Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
                                                        <a href="#" class="edit_laporan_keuangan btn btn-info btn-sm" id="" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}
                                                        <a href="#" class="edit_laporan_keuangan btn btn-info btn-sm" id="" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <strong class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }}">
                                                            Rp {{ number_format($selisih, 0, ',', '.') }}
                                                        </strong>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <button class="btn btn-warning btn-sm">Menunggu</button>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <!--<a href="#" class="btn btn-info btn-sm">Validasi</a>-->
                                                            <form action="#" method="POST" style="margin-left: 5px;">
                                                                @csrf
                                                                <a class="btn btn-danger btn-sm delete-confirm-stokmasuk">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                        viewBox="0 0 24 24" fill="currentColor"
                                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-trash">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" />
                                                                        <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                                                                    </svg>
                                                                    Hapus
                                                                </a>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    
                                    {{-- Pagination --}}
                                    <div class="mt-3">
                                        {{ $laporan_keuangan->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>







                            <!--<div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Kategori</th>
                                                <th>Ket.</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($laporan_keuangan as $d)
                                            <tr>
                                                <td>{{ $loop->iteration + $laporan_keuangan->firstItem()-1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($d->tanggal_laporan_keuangan)->translatedFormat('d F Y') }}</td>
                                                <td>{{ $d->jenis_transaksi }}</td>
                                                <td>{{ $d->kategori_laporan_keuangan }}</td>
                                                <td>{{ $d->keterangan_laporan_keuangan }}</td>
                                                <td>Rp {{ number_format($d->jumlah_dana, 0, ',', '.') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="edit_laporan_keuangan btn btn-info btn-sm" id="{{ $d->id_laporan_keuangan }}" >
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                        </a>
                                                        <form action="/owner/laporan/keuangan/{{ $d->id_laporan_keuangan }}/delete_laporan_keuangan" style="margin-left: 5px;" method="POST">
                                                            @csrf
                                                            <a class="btn btn-danger btn-sm delete-confirm-laporan-keuangan" >
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
                            {{ $laporan_keuangan->links('vendor.pagination.bootstrap-5') }}-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-inputlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/laporan/keuangan/store_laporan_keuangan" method="POST" id="frmLprnKngn" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_laporan_keuangan" name="tanggal_laporan_keuangan" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <select name="jenis_laporan_keuangan" id="jenis_laporan_keuangan" class="form-select">
                                    <option value="">Jenis Transaksi</option>
                                    <option value="Pemasukan">Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                </span>
                                <input type="text" value="" id="kategori_laporan_keuangan" class="form-control" name="kategori_laporan_keuangan" placeholder="Kategori">
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
                                <textarea id="keterangan_laporan_keuangan" name="keterangan_laporan_keuangan" class="form-control" rows="1" placeholder="Tuliskan keterangan di sini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" /></svg>
                                </span>
                                <input type="number" value="" id="jumlah_dana" class="form-control" name="jumlah_dana" placeholder="Jumlah Dana">
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


{{-- Modal Edit Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-editlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformlaporankeuangan">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function(){
        $("#tanggal_laporan_keuangan").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });

        $("#dari_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#sampai_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });

        $("#btnTambahLaporanKeuangan").click(function(){
            $("#modal-inputlaporankeuangan").modal("show");
        });

        $(".edit_laporan_keuangan").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/admin/laporan/keuangan/edit_laporan_keuangan',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformlaporankeuangan").html(respond);
                }
            });
            $("#modal-editlaporankeuangan").modal("show");
        });

        $(".delete-confirm-laporan-keuangan").click(function(e){
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

        $("#frmLprnKngn").submit(function(){
            var tanggal_laporan_keuangan = $("#tanggal_laporan_keuangan").val();
            var jenis_laporan_keuangan = $("#jenis_laporan_keuangan").val();
            var kategori_laporan_keuangan = $("#kategori_laporan_keuangan").val();
            var keterangan_laporan_keuangan = $("#keterangan_laporan_keuangan").val();
            var jumlah_dana = $("#jumlah_dana").val();
            if(tanggal_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#tanggal_laporan_keuangan").focus();
                  });
                return false;
            } else if (jenis_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jenis Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#jenis_laporan_keuangan").focus();
                  });
                return false;
            } else if (kategori_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Kategori Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#kategori_laporan_keuangan").focus();
                  });
                return false;
            } else if (keterangan_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#keterangan_laporan_keuangan").focus();
                  });
                return false;
            } else if (jumlah_dana==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jumlah Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#jumlah_dana").focus();
                  });
                return false;
            }
        });

        flatpickr("#dari_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });

        flatpickr("#sampai_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });
    });

    document.getElementById("cetak_laporan_keuangan").addEventListener("click", function() {
        let dari = document.getElementById("dari_tanggal").value;
        let sampai = document.getElementById("sampai_tanggal").value;
        let jenis_transaksi = document.getElementById("cari_jenis_transaksi").value;

        let url = `/admin/laporan/keuangan/cetak_laporan_keuangan?dari_tanggal=${dari}&sampai_tanggal=${sampai}&jenis_transaksi=${jenis_transaksi}`;
        window.open(url, "_blank");
    });









    // === BAGIAN DIAGRAM BATANG ===
    let koperasiData = @json($data);

    const labels      = koperasiData.map(item => item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal');
    const modalMasuk  = koperasiData.map(item => item.total_pemasukan);
    const modalKeluar = koperasiData.map(item => item.total_pengeluaran);
    const margin      = koperasiData.map(item => item.margin);

    const ctxBar = document.getElementById('koperasiChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: modalMasuk,
                    backgroundColor: 'rgba(0, 76, 255, 1)'
                },
                {
                    label: 'Pengeluaran',
                    data: modalKeluar,
                    backgroundColor: 'rgba(255, 0, 0, 0.7)'
                },
                {
                    label: 'Margin',
                    data: margin,
                    backgroundColor: 'rgba(47, 255, 0, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah (Rp)',
                        font: { size: 16 }
                    },
                    ticks: { font: { size: 14 } }
                }
            },
            plugins: {
                legend: { labels: { font: { size: 14 } } }
            }
        }
    });
</script>
@endpush