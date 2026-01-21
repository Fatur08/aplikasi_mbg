@extends('layouts.admin.tabler')
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
                                    Laporan Stok
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahDataKoperasi">
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/admin/laporan/stok_harian" method="GET" id="FormLaporanStokHarian">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-icon">
                                                    <span class="input-icon-addon">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                    </span>
                                                    <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
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
                            <div class="row mt-2 table-container">
                                <div class="col-12">
                                    @php
                                        use Illuminate\Support\Facades\DB;
                                        use Carbon\Carbon;

                                        // ✅ Ambil nama dapur
                                        $namaDapur = $dapur
                                            ? DB::table('dapur')
                                                ->where('nomor_dapur', $dapur)
                                                ->value('nama_dapur')
                                            : '-';
                                    @endphp
                                    <!-- === Section Info Dapur === -->
                                    <div class="section-info">
                                        <div class="info-card">
                                            <h4>Nama Dapur : <span style="color:#2563eb;">{{ $namaDapur }}</span></h4>
                                            <p>
                                                Periode Tanggal :
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($dari_tanggal)->translatedFormat('d F Y') }}
                                                    s/d
                                                    {{ \Carbon\Carbon::parse($sampai_tanggal)->translatedFormat('d F Y') }}
                                                </strong>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- === Table Section === -->
                                    <div class="table-wrapper">
                                        <div class="table-responsive">
                                            <table class="table custom-table">
                                                <thead style="text-align: center; vertical-align: middle;">
                                                    <tr>
                                                        <th rowspan="2">No.</th>
                                                        <th rowspan="2">Bahan</th>
                                                        <th rowspan="2">Satuan</th>
                                                        <th rowspan="2">Stok <br>Terakhir</th>
                                                        <th colspan="2">Stok</th>
                                                        <th rowspan="2">Sisa</th>
                                                        <th rowspan="2">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Masuk</th>
                                                        <th>Keluar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($data_laporan as $no => $row)
                                                        <tr>
                                                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                                                            <td>{{ $row['nama_bahan'] }}</td>
                                                            <td style="text-align:center;">{{ $row['satuan'] }}</td>
                                                            <td style="text-align:left;">{{ number_format($row['stok_awal'], 0, ',', '.') }}</td>
                                                            <td style="text-align:left;">{{ number_format($row['masuk'], 0, ',', '.') }}</td>
                                                            <td style="text-align:left;">{{ number_format($row['keluar'], 0, ',', '.') }}</td>
                                                            <td style="text-align:left; font-weight:bold;">
                                                                {{ number_format($row['stok_akhir'], 0, ',', '.') }}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" style="text-align:center; color:red;">
                                                                Tidak ada data stok pada tanggal ini
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
@endsection
@push('myscript')
<script>
    flatpickr("#dari_tanggal", {
        altInput: true,
        altFormat: "d F Y",      // 15 September 2025
        dateFormat: "Y-m-d",     // dikirim ke backend
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



    $("#FormLaporanStokHarian").submit(function(){
        var tampilan_tanggal = $("#tampilan_tanggal").val();
        if(tampilan_tanggal==""){
            Swal.fire({
                title: 'Warning!',
                text: 'Tanggal Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'OK'
              }).then(()=> {
                  $("#tampilan_tanggal").focus();
              });
            return false;
        }
    });
</script>
@endpush