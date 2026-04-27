@extends('layouts.maker.tabler')
@section('content')
    <style>
        /* === Section Info Dapur === */
        .section-info {
            margin-top: 40px;
            margin-bottom: 25px;
            text-align: center;
        }

        .info-card {
            display: inline-block;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            padding: 25px 40px;
            border: 1px solid #e5e7eb;
            transition: 0.2s;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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
                                        Laporan Operasional
                                    </h2>
                                </td>
                                <td style="text-align:right">
                                    <a href="#" class="btn btn-primary" id="btnTambahInformasiOperasional">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
                            <div class="row mt-2 mb-3">
                                <div class="col-12">
                                    <form action="/maker/operasional/laporan_operasional" method="GET"
                                        id="FormLaporanOperasional">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                            <path d="M16 3l0 4" />
                                                            <path d="M8 3l0 4" />
                                                            <path d="M4 11l16 0" />
                                                            <path d="M8 15h2v2h-2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" value="{{ request('dari_tanggal') }}"
                                                        id="dari_tanggal" name="dari_tanggal" class="form-control"
                                                        placeholder="Dari Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                            <path d="M16 3l0 4" />
                                                            <path d="M8 3l0 4" />
                                                            <path d="M4 11l16 0" />
                                                            <path d="M8 15h2v2h-2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" value="{{ request('sampai_tanggal') }}"
                                                        id="sampai_tanggal" name="sampai_tanggal" class="form-control"
                                                        placeholder="Sampai Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                            <path d="M21 21l-6 -6" />
                                                        </svg>
                                                        Cari
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-2 table-container">
                                <div class="col-12">
                                    <!-- === Table Section === -->
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="table custom-table">
                                                <thead style="text-align: center; vertical-align: middle;">
                                                    <tr>
                                                        <th rowspan="2">No.</th>
                                                        <th rowspan="2">Tanggal</th>
                                                        <th rowspan="2">Jenis Operasional</th>
                                                        <th colspan="2">Ket</th>
                                                        <th rowspan="2">Saldo</th>
                                                        <th rowspan="2">Nota</th>
                                                        <th rowspan="2">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Beli</th>
                                                        <th>Jual</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($laporan as $item)
                                                        @php
                                                            $saldo = $item->jual_laporan_operasional - $item->beli_laporan_operasional;
                                                        @endphp
                                                        <tr style="text-align:center; vertical-align:middle;">
                                                            <td>{{ $loop->iteration }}</td>

                                                            <!-- Format tanggal -->
                                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan_operasional)->translatedFormat('d F Y') }}
                                                            </td>

                                                            <td>{{ $item->jenis_informasi_operasional }}</td>

                                                            <!-- Ket -->
                                                            <td>Rp
                                                                {{ number_format($item->beli_laporan_operasional, 0, ',', '.') }}
                                                            </td>
                                                            <td>Rp
                                                                {{ number_format($item->jual_laporan_operasional, 0, ',', '.') }}
                                                            </td>

                                                            <!-- Saldo -->
                                                            <td>
                                                                Rp {{ number_format($saldo, 0, ',', '.') }}
                                                            </td>

                                                            <!-- Nota -->
                                                            <td>
                                                                @if ($item->nota_laporan_operasional)
                                                                    <a href="#"
                                                                        class="lihat_nota_laporan_operasional btn btn-info btn-sm"
                                                                        id="{{ $item->id_laporan_operasional }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                            <path
                                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                                                                                                                                                         c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                        </svg>
                                                                        <span>Lihat</span>
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex flex-column align-items-stretch gap-1">

                                                                    <a href="#"
                                                                        class="edit_laporan_operasional btn btn-info btn-sm w-100"
                                                                        id="{{ $item->id_laporan_operasional }}">
                                                                        Edit
                                                                    </a>

                                                                    <form
                                                                        action="/maker/operasional/laporan_operasional/{{ $item->id_laporan_operasional }}/delete_maker_laporan_operasional"
                                                                        method="POST" class="w-100">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm delete-confirm-laporan-operasional w-100">
                                                                            Hapus
                                                                        </button>
                                                                    </form>

                                                                    @if($item->validasi_laporan_operasional == 0)
                                                                        <button
                                                                            class="btn btn-warning btn-sm w-100">Menunggu</button>
                                                                    @elseif($item->validasi_laporan_operasional == 1)
                                                                        <button
                                                                            class="btn btn-success btn-sm w-100">Disetujui</button>
                                                                    @elseif($item->validasi_laporan_operasional == 2)
                                                                        <button class="btn btn-danger btn-sm w-100">Ditolak</button>
                                                                    @endif

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" style="text-align:center;">
                                                                Data belum tersedia
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






    <!-- Modal Tambah Informasi Operasional -->
    <div class="modal modal-blur fade" id="modal-input-informasi-operasional" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/maker/operasional/laporan_operasional/store_maker_laporan_operasional" method="POST"
                        id="FormInputOperasional" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start" style="font-size:12pt;">Tanggal Operasional</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-4">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                            <path d="M16 3l0 4" />
                                            <path d="M8 3l0 4" />
                                            <path d="M4 11l16 0" />
                                            <path d="M8 15h2v2h-2z" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="tanggal_laporan_operasional"
                                        name="tanggal_laporan_operasional" class="form-control" placeholder="Sampai Tanggal"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start" style="font-size:12pt;">Jenis Operasional</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-4">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l18 0" />
                                            <path d="M9 8l1 0" />
                                            <path d="M9 12l1 0" />
                                            <path d="M9 16l1 0" />
                                            <path d="M14 8l1 0" />
                                            <path d="M14 12l1 0" />
                                            <path d="M14 16l1 0" />
                                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                                        </svg>
                                    </span>

                                    <select id="id_informasi_operasional" name="id_informasi_operasional"
                                        class="form-control">

                                        <option value="">-- Pilih Jenis Operasional --</option>

                                        @foreach ($jenisOperasional as $item)
                                            <option value="{{ $item->id_informasi_operasional }}">
                                                {{ $item->jenis_informasi_operasional }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start" style="font-size:12pt;">Jumlah (pcs)</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-4">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-calculator">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -14" />
                                            <path
                                                d="M8 8a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1l0 -1" />
                                            <path d="M8 14l0 .01" />
                                            <path d="M12 14l0 .01" />
                                            <path d="M16 14l0 .01" />
                                            <path d="M8 17l0 .01" />
                                            <path d="M12 17l0 .01" />
                                            <path d="M16 17l0 .01" />
                                        </svg>
                                    </span>
                                    <input type="number" value="" id="jumlah_laporan_operasional" class="form-control"
                                        name="jumlah_laporan_operasional" placeholder="Masukkan Jumlah Jenis">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start" style="font-size:12pt;">Harga Yang Dibeli (Beli)</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-4">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-settings-dollar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M13.038 20.666c-.902 .665 -2.393 .337 -2.713 -.983a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 .402 2.248" />
                                            <path d="M15 12a3 3 0 1 0 -1.724 2.716" />
                                            <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                            <path d="M19 21v1m0 -8v1" />
                                        </svg>
                                    </span>
                                    <input type="number" value="" id="beli_laporan_operasional" class="form-control"
                                        name="beli_laporan_operasional" placeholder="Masukkan Harga Yang Dibeli">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-start" style="font-size:12pt;">Harga Diajukan (Jual)</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-4">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-settings-dollar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M13.038 20.666c-.902 .665 -2.393 .337 -2.713 -.983a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 .402 2.248" />
                                            <path d="M15 12a3 3 0 1 0 -1.724 2.716" />
                                            <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                            <path d="M19 21v1m0 -8v1" />
                                        </svg>
                                    </span>
                                    <input type="number" value="" id="jual_laporan_operasional" class="form-control"
                                        name="jual_laporan_operasional" placeholder="Masukkan Harga Diajukan">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="file" id="nota_laporan_operasional" name="nota_laporan_operasional"
                                    class="form-control">
                            </div>
                            <div class="col-6 mt-2">
                                <h5 class="text-start" style="font-size:12pt;">Foto Nota</h5>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 14l11 -11" />
                                            <path
                                                d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                        </svg>
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



    {{-- Modal Nota Laporan Operasional --}}
    <div class="modal modal-blur fade" id="modal-nota-laporan-operasional" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nota Laporan Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="load-nota-laporan-operasional">

                </div>
            </div>
        </div>
    </div>




    {{-- Modal Edit Laporan Operasional --}}
    <div class="modal modal-blur fade" id="modal-edit-laporan-operasional" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Laporan Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="load-edit-laporan-operasional">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $("#btnTambahInformasiOperasional").click(function () {
                $("#modal-input-informasi-operasional").modal("show");
            });



            $(".lihat_nota_laporan_operasional").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/maker/operasional/laporan_operasional/nota_maker_laporan_operasional',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#load-nota-laporan-operasional").html(respond);
                    }
                });
                $("#modal-nota-laporan-operasional").modal("show");
            });




            $(".edit_laporan_operasional").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/maker/operasional/laporan_operasional/edit_maker_laporan_operasional',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#load-edit-laporan-operasional").html(respond);

                        // 🔥 INIT DATEPICKER DI SINI
                        $("#edit_tanggal_laporan_operasional").flatpickr({
                            altInput: true,
                            altFormat: "d F Y",
                            dateFormat: "Y-m-d",
                            locale: "id",
                            allowInput: true
                        });

                    }
                });
                $("#modal-edit-laporan-operasional").modal("show");
            });



            $(".delete-confirm-laporan-operasional").click(function (e) {
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

            $("#FormLaporanOperasional").submit(function () {
                var pilih_dapur = $("#pilih_dapur").val();
                var pilih_tanggal = $("#pilih_tanggal").val();
                if (pilih_dapur == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Dapur Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#pilih_dapur").focus();
                    });
                    return false;
                } else if (pilih_tanggal == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Tanggal Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#pilih_tanggal").focus();
                    });
                    return false;
                }
            });



            flatpickr("#dari_tanggal", {
                altInput: true,
                altFormat: "d F Y", // 15 September 2025
                dateFormat: "Y-m-d", // dikirim ke backend
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

            flatpickr("#tanggal_laporan_operasional", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true
            });
        });
    </script>
@endpush