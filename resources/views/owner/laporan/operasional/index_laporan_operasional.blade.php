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
                                    Laporan Operasional
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahOperasional">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/owner/laporan/operasional" method="GET" id="FormLaporanOperasional">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                        <path d="M16 3l0 4" />
                                                        <path d="M8 3l0 4" />
                                                        <path d="M4 11l16 0" />
                                                        <path d="M8 15h2v2h-2z" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                        <path d="M16 3l0 4" />
                                                        <path d="M8 3l0 4" />
                                                        <path d="M4 11l16 0" />
                                                        <path d="M8 15h2v2h-2z" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
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
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Kebutuhan</th>
                                                    <th>Biaya</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>

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






<!-- Modal Tambah Operasional -->
<div class="modal modal-blur fade" id="modal-input-operasional" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Operasional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/owner/laporan/operasional/store_owner_operasional" method="POST" id="FormInputOperasional" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2z" />
                                    </svg>
                                </span>
                                <input type="text" value="" id="tanggal_operasional" name="tanggal_operasional" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart-star">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M9.5 17h-3.5v-14h-2" />
                                        <path d="M6 5l14 1l-.615 4.302m-6.885 2.698h-6.5" />
                                        <path d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138" />
                                    </svg>
                                </span>
                                <input type="text" value="" id="kebutuhan_operasional" class="form-control" name="kebutuhan_operasional" placeholder="Masukkan Kebutuhan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-coin">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                                        <path d="M12 7v10" />
                                    </svg>
                                </span>
                                <input type="number" value="" id="biaya_operasional" class="form-control" name="biaya_operasional" placeholder="Masukkan Biaya">
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
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l6 6v8a2 2 0 0 1 -2 2h-3" />
                                        <path d="M13 4v6h6" />
                                    </svg>
                                </span>
                                <textarea id="keterangan_operasional" name="keterangan_operasional" class="form-control" rows="1" placeholder="Tuliskan keterangan di sini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 14l11 -11" />
                                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
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
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnTambahOperasional").click(function() {
            $("#modal-input-operasional").modal("show");
        });



        $(".tambah_operasional_dapur").click(function() {
            $.ajax({
                type: 'POST',
                url: '/owner/laporan/dapur/tambah_operasional_dapur',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(respond) {
                    $("#loadformtambahoperasionaldapur").html(respond);
                }
            });
            $("#modal-tambahoperasionaldapur").modal("show");
        });

        $(".lihat_kendala").click(function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '/owner/laporan/harian_dapur/lihat_kendala',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(respond) {
                    $("#loadformlihatkendala").html(respond);
                }
            });
            $("#modal-lihatkendala").modal("show");
        });

        $(".delete-confirm-menuharian").click(function(e) {
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

        $("#FormLaporanOperasional").submit(function() {
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

        flatpickr("#tanggal_operasional", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });
    });
</script>
@endpush