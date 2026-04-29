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
                                        Laporan Supplier
                                    </h2>
                                </td>
                                <td style="text-align:right">
                                    <a href="#" class="btn btn-primary" id="TambahLaporansupplier">
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
                                <form action="/maker/laporan/supplier" method="GET" id="FormLaporansupplier">
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-4">
                                                    <div class="input-icon">
                                                        <span class="input-icon-addon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
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
                                                        <input type="text" value="" id="dari_tanggal" name="dari_tanggal"
                                                            class="form-control" placeholder="Dari Tanggal"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-icon">
                                                        <span class="input-icon-addon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
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
                                                        <input type="text" value="" id="sampai_tanggal"
                                                            name="sampai_tanggal" class="form-control"
                                                            placeholder="Sampai Tanggal" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
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
                                    </div>
                                </form>
                                <div class="row mt-2 table-container">
                                    <div class="col-12">
                                        <div class="table-wrapper">
                                            <div class="table-responsive">
                                                <table class="table custom-table">
                                                    <thead class="table-primary text-center">
                                                        <tr>
                                                            <th style="text-align: center; vertical-align: middle;">No.</th>
                                                            <th style="text-align: center; vertical-align: middle;">Tanggal
                                                            </th>
                                                            <th style="text-align: center; vertical-align: middle;">Barang
                                                            </th>
                                                            <th style="text-align: center; vertical-align: middle;">Jumlah
                                                            </th>
                                                            <th style="text-align: center; vertical-align: middle;">Harga
                                                            </th>
                                                            <th style="text-align: center; vertical-align: middle;">Nota
                                                            </th>
                                                            <th style="text-align: center; vertical-align: middle;">Status
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($barangSupplier as $key => $item)
                                                                                                        <tr class="text-center">
                                                                                                            <td>{{ $key + 1 }}</td>

                                                                                                            <td>
                                                                                                                {{ \Carbon\Carbon::parse($item->tanggal_barang_supplier)
                                                            ->locale('id')
                                                            ->translatedFormat('d F Y') }}
                                                                                                            </td>

                                                                                                            <td>{{ $item->nama_barang_supplier }}</td>

                                                                                                            <td>
                                                                                                                {{ number_format($item->jumlah_barang_supplier) }}
                                                                                                                {{ $item->satuan_barang_supplier }}
                                                                                                            </td>

                                                                                                            <td>
                                                                                                                Rp
                                                                                                                {{ number_format($item->harga_barang_supplier, 0, ',', '.') }}
                                                                                                            </td>

                                                                                                            <td>
                                                                                                                @if ($item->bukti_barang_supplier)
                                                                                                                    <a href="#"
                                                                                                                        class="bukti_barang_supplier btn btn-info btn-sm"
                                                                                                                        data-id="{{ $item->id_barang_supplier }}">
                                                                                                                        👁 Lihat
                                                                                                                    </a>
                                                                                                                @else
                                                                                                                    <span class="badge bg-secondary">Tidak ada</span>
                                                                                                                @endif
                                                                                                            </td>


                                                                                                            <td style="text-align:center">
                                                                                                                @if($item->status_barang_supplier == 0)
                                                                                                                    <button class="btn btn-warning btn-sm">Menunggu</button>
                                                                                                                @elseif($item->status_barang_supplier == 1)
                                                                                                                    <button class="btn btn-success btn-sm">Disetujui</button>
                                                                                                                @elseif($item->status_barang_supplier == 2)
                                                                                                                    <button class="btn btn-danger btn-sm">Ditolak</button>
                                                                                                                @endif
                                                                                                            </td>
                                                                                                        </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center text-muted">
                                                                    Data barang supplier belum tersedia
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
    </div>


    {{-- Modal Input Laporan supplier --}}
    <div class="modal modal-blur fade" id="modal-inputlaporansupplier" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Laporan supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/maker/laporan/supplier/store_laporan_supplier" method="POST" id="FormLaporansupplier"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
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
                                    <input type="text" value="" id="tanggal_laporan_supplier"
                                        name="tanggal_laporan_supplier" class="form-control" placeholder="Masukkan Tanggal"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Supplier</label>
                                    <select name="id_informasi_supplier" id="id_informasi_supplier" class="form-select"
                                        required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($supplier as $s)
                                            <option value="{{ $s->id_informasi_supplier }}">
                                                {{ $s->nama_informasi_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Jenis Barang</label>
                                    <select id="jumlah_item" class="form-select">
                                        <option value="">-- Pilih Jumlah --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="container-barang"></div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <input type="file" id="bukti_barang_supplier" name="bukti_barang_supplier"
                                    class="form-control">
                            </div>
                            <div class="col-6 mt-2">
                                <label>Masukkan Nota Pembayaran (Foto)</label>
                            </div>
                        </div>
                        <div class="row">
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


    {{-- Modal Lihat Bukti Barang Supplier --}}
    <div class="modal modal-blur fade" id="modal-lihatbuktibarangsupplier" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lihat Bukti Barang Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="formlihatbuktibarangsupplier">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $("#TambahLaporansupplier").click(function () {
                $("#modal-inputlaporansupplier").modal("show");
            });



            $('#jumlah_item').on('change', function () {
                let jumlah = $(this).val();
                let supplier = $('#id_informasi_supplier').val();

                if (!supplier) {
                    alert('Pilih supplier terlebih dahulu');
                    $(this).val('');
                    return;
                }

                $('#container-barang').html('');

                for (let i = 0; i < jumlah; i++) {
                    $('#container-barang').append(`
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="text-center">Barang Ke-${i + 1}</h5>

                                                            <div class="mb-2">
                                                                <label>Nama Barang</label>
                                                                <select name="barang[${i}][nama_barang_supplier]" class="form-select nama-barang" required>
                                                                    <option value="">-- Pilih Barang --</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-2">
                                                                <label>Satuan</label>
                                                                <input 
                                                                    type="text"
                                                                    name="barang[${i}][satuan_barang_supplier]"
                                                                    class="form-control"
                                                                    placeholder="Masukkan Satuan (kg, liter, dll)"
                                                                    pattern="[A-Za-z\s]+"
                                                                    title="Satuan hanya boleh berisi huruf"
                                                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                                                    required
                                                                >
                                                            </div>

                                                            <div class="mb-2">
                                                                <label>Jumlah</label>
                                                                <input type="number" name="barang[${i}][jumlah_barang_supplier]" class="form-control" placeholder="Masukkan Jumlah" required>
                                                            </div>

                                                            <div class="mb-2">
                                                                <label>Harga</label>
                                                                <input type="number" name="barang[${i}][harga_barang_supplier]" class="form-control" placeholder="Masukkan Total Harga" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `);
                }

                loadBarangSupplier();
            });

            function loadBarangSupplier() {
                let supplier = $('#id_informasi_supplier').val();

                $.get(`/maker/laporan/supplier/get-barang/${supplier}`, function (data) {
                    $('.nama-barang').each(function () {
                        let select = $(this);
                        select.html('<option value="">-- Pilih Barang --</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.nama_barang_supplier}">
                                                            ${item.nama_barang_supplier} Barang
                                                        </option>`);
                        });
                    });
                });
            }




            $('#id_informasi_supplier').on('change', function () {
                let supplierId = $(this).val();
                $('#jumlah_item').html('').prop('disabled', true);
                $('#container-barang').html('');

                if (!supplierId) return;

                $.get(`/maker/laporan/supplier/get-jumlah-barang/${supplierId}`, function (res) {
                    let jumlah = res.jumlah;

                    $('#jumlah_item').append('<option value="">-- Pilih Jumlah --</option>');

                    if (jumlah === 0) {
                        $('#jumlah_item').append(
                            '<option value="">Barang supplier belum tersedia</option>'
                        );
                        return;
                    }

                    for (let i = 1; i <= jumlah; i++) {
                        $('#jumlah_item').append(
                            `<option value="${i}">${i}</option>`
                        );
                    }

                    $('#jumlah_item').prop('disabled', false);
                });
            });




            $(".bukti_barang_supplier").click(function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: '/maker/laporan/supplier/bukti_barang_supplier',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#formlihatbuktibarangsupplier").html(respond);
                    }
                });
                $("#modal-lihatbuktibarangsupplier").modal("show");
            });






            $(".edit_laporan_supplier").click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/owner/laporan/supplier/edit_laporan_supplier',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $("#loadeditformlaporansupplier").html(respond);
                    }
                });
                $("#modal-editlaporansupplier").modal("show");
            });

            $(".delete-confirm-laporan-supplier").click(function (e) {
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

            $("#FormLaporansupplier").submit(function () {
                var dari_tanggal = $("#dari_tanggal").val();
                var sampai_tanggal = $("#sampai_tanggal").val();
                if (dari_tanggal == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Dari Tanggal Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#dari_tanggal").focus();
                    });
                    return false;
                } else if (sampai_tanggal == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Sampai Tanggal Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#sampai_tanggal").focus();
                    });
                    return false;
                }
            });


            $("#cetak_laporan_supplier").click(function (e) {
                e.preventDefault(); // Mencegah langsung pindah halaman

                var pilih_bulan = $("#pilih_bulan").val();
                var pilih_dapur = $("#pilih_dapur").val();

                if (pilih_bulan == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Bulan Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#pilih_bulan").focus();
                    });
                    return false;

                } else if (pilih_dapur == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Dapur Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#pilih_dapur").focus();
                    });
                    return false;
                }

                // ✅ JIKA SUDAH LENGKAP, BARU BUKA HALAMAN CETAK
                let url = `/owner/laporan/supplier/cetak_laporan_supplier?bulan=${pilih_bulan}&dapur=${pilih_dapur}`;
                window.open(url, '_blank');
            });



            flatpickr("#dari_tanggal", {
                altInput: true,
                altFormat: "d F Y",      // 15 September 2025
                dateFormat: "Y-m-d",     // dikirim ke backend
                locale: "id",
                allowInput: true,

                appendTo: document.body,
                position: "auto",

                disableMobile: true, // 🔥 WAJIB
                clickOpens: true,
                allowInput: false,
            });

            flatpickr("#sampai_tanggal", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,

                appendTo: document.body,
                position: "auto",

                disableMobile: true, // 🔥 WAJIB
                clickOpens: true,
                allowInput: false,
            });


            flatpickr("#tanggal_laporan_supplier", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,

                appendTo: document.body,
                position: "auto",

                disableMobile: true, // 🔥 WAJIB
                clickOpens: true,
                allowInput: false,
            });
        });
    </script>
@endpush