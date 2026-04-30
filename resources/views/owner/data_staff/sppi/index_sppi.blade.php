@extends('layouts.owner.tabler')
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
                                        Data SPPI
                                    </h2>
                                </td>
                                <td style="text-align:right">
                                    <a href="{{ url('/owner/data_staff') }}" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15 6l-6 6l6 6" />
                                        </svg>
                                        Kembali
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
                                <form action="/owner/data_staff/sppi" method="GET">
                                    <div class="col-12">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="cari_nama" id="cari_nama"
                                                        placeholder="Masukkan Nama Lengkap"
                                                        value="{{ Request('cari_nama') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                        <option value="">Pilih Dapur</option>
                                                        @foreach($dapurList as $dapur)
                                                            <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-2 table-container">
                                <div class="col-12">
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="table custom-table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="7" style="text-align: left;">Nama Dapur :
                                                            {{ $namaDapur }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Nama</th>
                                                        <th>E-Mail</th>
                                                        <th>Alamat</th>
                                                        <th>No. HP</th>
                                                        <th>Foto</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sppi as $d)
                                                        @php
                                                            $path = Storage::url('uploads/data_staff/sppi/' . $d->foto_sppi);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration + $sppi->firstItem() - 1 }}</td>
                                                            <td>{{ $d->nama_sppi }}</td>
                                                            <td>{{ $d->email_sppi }}</td>
                                                            <td>{{ $d->alamat_sppi }}</td>
                                                            <td>{{ $d->no_hp_sppi }}</td>
                                                            <td>
                                                                @if (empty($d->foto_sppi))
                                                                    <img src="{{ asset('assets/img/nophoto.jpg') }}" class="avatar"
                                                                        alt="">
                                                                @else
                                                                    <img src="{{ url($path) }}" class="avatar" alt="">
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($d->status_validasi_sppi == 0)
                                                                    <button class="btn btn-warning btn-sm">Menunggu</button>
                                                                @elseif($d->status_validasi_sppi == 1)
                                                                    <button class="btn btn-success btn-sm">Disetujui</button>
                                                                @else
                                                                    <button class="btn btn-danger btn-sm">Ditolak</button>
                                                                @endif
                                                                <div class="btn-group">
                                                                    @if ($d->status_validasi_sppi == 0)
                                                                        <a href="#" class="validasi_sppi btn btn-info btn-sm"
                                                                            id="{{ $d->id_sppi }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                                <path
                                                                                    d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                                <path
                                                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                                <path d="M16 5l3 3" />
                                                                            </svg>
                                                                            Validasi
                                                                        </a>
                                                                    @else
                                                                        <form
                                                                            action="/owner/data_staff/sppi/{{ $d->id_sppi }}/batalkan_validasi_sppi"
                                                                            style="margin-left: 5px;" method="POST">
                                                                            @csrf
                                                                            <a class="btn btn-sm bg-danger batalkan_validasi_sppi">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-square-rounded-x">
                                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                                        fill="none" />
                                                                                    <path d="M10 10l4 4m0 -4l-4 4" />
                                                                                    <path
                                                                                        d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                                                                </svg>
                                                                                Batalkan
                                                                            </a>
                                                                        </form>
                                                                    @endif
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
    </div>









    <!-- VALIDASI DATA SPPI -->
    <div class="modal modal-blur fade" id="modal-validasisppi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validasi Data SPPI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="formvalidasisppi">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $("#TambahSPPI").click(function () {
                $("#modal-inputsppi").modal("show");
            });

            $(".validasi_sppi").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/owner/data_staff/sppi/validasi_sppi',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#formvalidasisppi").html(respond);
                    }
                });
                $("#modal-validasisppi").modal("show");
            });



            $(".batalkan_validasi_sppi").click(function (e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Apakah Anda Yakin ingin batalkan",
                    text: "Jika Ya Maka Status Validasi akan berubah",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Batalkan Saja"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                            title: "Deleted!",
                            text: "Data Berhasil Di Batalkan",
                            icon: "success"
                        });
                    }
                });
            });

            $("#FormTambahSPPI").submit(function () {
                var nama_sppi = $("#nama_sppi").val();
                var email_sppi = $("#email_sppi").val();
                var alamat_sppi = $("#alamat_sppi").val();
                var no_hp_sppi = $("#no_hp_sppi").val();
                var foto_sppi = $("#FormTambahSPPI").find("#foto_sppi").val();
                if (nama_sppi == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Lengkap Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#nama_sppi").focus();
                    });
                    return false;
                } else if (email_sppi == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'E-Mail Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#email_sppi").focus();
                    });
                    return false;
                } else if (alamat_sppi == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Alamat Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#alamat_sppi").focus();
                    });
                    return false;
                } else if (no_hp_sppi == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No. HP Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#no_hp_sppi").focus();
                    });
                    return false;
                } else if (foto_sppi == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Foto Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#foto_sppi").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush