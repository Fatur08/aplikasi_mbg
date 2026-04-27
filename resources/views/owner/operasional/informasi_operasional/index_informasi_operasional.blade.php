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
                                        Informasi Operasional
                                    </h2>
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
                                    <form action="/owner/operasional/informasi_operasional" method="GET"
                                        id="FormLaporanOperasional">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input-icon mb-3">
                                                    <span class="input-icon-addon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                    <input type="text"
                                                        value="{{ request('cari_jenis_informasi_operasional') }}"
                                                        id="cari_jenis_informasi_operasional" class="form-control"
                                                        name="cari_jenis_informasi_operasional"
                                                        placeholder="Cari Jenis Operasional">
                                                </div>
                                            </div>
                                            <div class="col-6">
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
                            <div class="row mt-1 table-container">
                                <div class="col-12">
                                    <!-- === Table Section === -->
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="table custom-table">
                                                <thead style="text-align: center; vertical-align: middle;">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Jenis Operasional</th>
                                                        <th>Jumlah Jenis</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Harga Operasional</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data as $key => $item)
                                                        @php
                                                            $total = $item->jumlah_jenis_informasi_operasional * $item->harga_satuan_informasi_operasional;
                                                        @endphp
                                                        <tr style="text-align: center;">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->jenis_informasi_operasional }}</td>
                                                            <td>{{ $item->jumlah_jenis_informasi_operasional }}</td>
                                                            <td>Rp
                                                                {{ number_format($item->harga_satuan_informasi_operasional, 0, ',', '.') }}
                                                            </td>
                                                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                                            <td>
                                                                <div class="d-flex flex-column align-items-stretch gap-1">

                                                                    <a href="#"
                                                                        class="edit_informasi_operasional btn btn-info btn-sm w-100"
                                                                        id="{{ $item->id_informasi_operasional }}">
                                                                        Edit
                                                                    </a>

                                                                    <form
                                                                        action="/owner/operasional/informasi_operasional/{{ $item->id_informasi_operasional }}/delete_owner_informasi_operasional"
                                                                        method="POST" class="w-100">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm delete-confirm-informasi-operasional w-100">
                                                                            Hapus
                                                                        </button>
                                                                    </form>

                                                                    @if($item->validasi_informasi_operasional == 0)
                                                                        <button
                                                                            class="btn btn-warning btn-sm w-100">Menunggu</button>
                                                                    @elseif($item->validasi_informasi_operasional == 1)
                                                                        <button
                                                                            class="btn btn-success btn-sm w-100">Disetujui</button>
                                                                    @elseif($item->validasi_informasi_operasional == 2)
                                                                        <button class="btn btn-danger btn-sm w-100">Ditolak</button>
                                                                    @endif
                                                                    @if ($item->validasi_informasi_operasional == 0)
                                                                        <a href="#"
                                                                            class="validasi_informasi_operasional btn btn-secondary btn-sm w-100"
                                                                            id="{{ $item->id_informasi_operasional }}">
                                                                            Validasi
                                                                        </a>
                                                                    @else
                                                                        <form
                                                                            action="/owner/operasional/informasi_operasional/{{ $item->id_informasi_operasional }}/batalkan_validasi_informasi_operasional"
                                                                            method="POST">
                                                                            @csrf
                                                                            <a
                                                                                class="btn btn-sm bg-danger batalkan_validasi_informasi_operasional w-100">
                                                                                Batalkan
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" style="text-align: center;">Data belum tersedia</td>
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






    {{-- Modal Edit Informasi Operasional --}}
    <div class="modal modal-blur fade" id="modal-editinformasioperasional" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Informasi Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadeditinformasioperasional">

                </div>
            </div>
        </div>
    </div>




    {{-- Modal Validasi Informasi Operasional --}}
    <div class="modal modal-blur fade" id="modal-validasi-informasi-operasional" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validasi Informasi Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="load-validasi-informasi-operasional">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $(".edit_informasi_operasional").click(function () {
                var id = $(this).attr('id');

                $.ajax({
                    type: 'POST',
                    url: '/owner/operasional/informasi_operasional/edit_owner_informasi_operasional',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#loadeditinformasioperasional").html(respond);

                        // 🔥 WAJIB: jalankan setelah HTML masuk
                        hitungTotalEdit();

                        // 🔥 pasang event setelah elemen ada
                        $("#edit_jumlah_jenis_informasi_operasional, #edit_harga_satuan_informasi_operasional")
                            .on('input', function () {
                                hitungTotalEdit();
                            });
                    }
                });

                $("#modal-editinformasioperasional").modal("show");
            });






            $(".validasi_informasi_operasional").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/owner/operasional/informasi_operasional/validasi_informasi_operasional',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#load-validasi-informasi-operasional").html(respond);
                    }
                });
                $("#modal-validasi-informasi-operasional").modal("show");
            });




            $(".batalkan_validasi_informasi_operasional").click(function (e) {
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







            $(".delete-confirm-informasi-operasional").click(function (e) {
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

            $("#modal-input-informasi-operasional").submit(function () {
                var jenis_informasi_operasional = $("#jenis_informasi_operasional").val();
                var jumlah_jenis_informasi_operasional = $("#jumlah_jenis_informasi_operasional").val();
                var harga_satuan_informasi_operasional = $("#harga_satuan_informasi_operasional").val();
                if (jenis_informasi_operasional == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jenis Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#jenis_informasi_operasional").focus();
                    });
                    return false;
                } else if (jumlah_jenis_informasi_operasional == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jumlah Jenis Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#jumlah_jenis_informasi_operasional").focus();
                    });
                    return false;
                } else if (harga_satuan_informasi_operasional == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Harga Satuan Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#harga_satuan_informasi_operasional").focus();
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


        // BAGIAN EDIT HARGA OPERASIONAL
        function hitungTotalEdit() {
            let jumlah = document.getElementById('edit_jumlah_jenis_informasi_operasional')?.value;
            let harga = document.getElementById('edit_harga_satuan_informasi_operasional')?.value;

            let total = (parseFloat(jumlah) || 0) * (parseFloat(harga) || 0);

            let totalView = document.getElementById('edit_harga_informasi_operasional_view');

            if (totalView) {
                totalView.value = total.toLocaleString('id-ID');
            }
        }
    </script>
@endpush