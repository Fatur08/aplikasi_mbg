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
                                    Laporan Stok Bulanan
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
                                    <form action="/owner/laporan/stok" method="GET">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <select name="bulan" id="bulan" class="form-select">
                                                        <option value="">Pilih Bulan</option>
                                                        <option value="Januari">Januari</option>
                                                        <option value="Februari">Februari</option>
                                                        <option value="Maret">Maret</option>
                                                        <option value="April">April</option>
                                                        <option value="Mei">Mei</option>
                                                        <option value="Juni">Juni</option>
                                                        <option value="Juli">Juli</option>
                                                        <option value="Agustus">Agustus</option>
                                                        <option value="September">September</option>
                                                        <option value="Oktober">Oktober</option>
                                                        <option value="November">November</option>
                                                        <option value="Desember">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
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

                                        // Cari nama dapur jika nomor ada
                                        $namaDapur = $nomor_dapur
                                            ? DB::table('dapur')
                                                ->where('nomor_dapur', $nomor_dapur)
                                                ->pluck('nama_dapur')
                                                ->unique()
                                                ->join(', ')
                                            : '-';

                                        // --- Tentukan nama bulan & tahun dari filter ---
                                        if (!empty($filter_bulan)) {
                                            $tanggalObj = Carbon::parse($filter_bulan . '-01');
                                            $tanggalDisplay = $tanggalObj->translatedFormat('F Y'); // Contoh: Oktober 2025
                                        } else {
                                            $tanggalDisplay = '-';
                                        }
                                    @endphp
                                    
                                    <!-- === Section Info Dapur === -->
                                    <div class="section-info">
                                        <div class="info-card">
                                            <h4>Nama Dapur : <span style="color:#2563eb;">{{ $namaDapur }}</span></h4>
                                            <p>Bulan : <strong>{{ $tanggalDisplay }}</strong></p>
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
                                                        <th colspan="2">Stok Bulan Ini</th>
                                                        <th rowspan="2">Sisa</th>
                                                        <th rowspan="2">Validasi</th>
                                                        <th rowspan="2">Aksi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Masuk</th>
                                                        <th>Keluar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach($sisa_perbahan as $item)
                                                        @php
                                                            $stok_akhir = $item->sisa_per_bahan;
                                                            $statusBadge = $stok_akhir <= 0 
                                                                ? '<span class="badge bg-danger">Habis</span>' 
                                                                : '<span class="badge bg-warning">Menunggu</span>';
                                                        @endphp
                                    
                                                        <tr @if($stok_akhir <= 0) class="table-warning" @endif>
                                                            <td style="text-align: center; vertical-align: middle;">{{ $no++ }}</td>
                                                            <td>{{ $item->nama_bahan }}</td>
                                                            <td>{{ $item->satuan_bahan }}</td>
                                                            <td>{{ $item->total_masuk }}</td>
                                                            <td>{{ $item->total_keluar }}</td>
                                                            <td>{{ $stok_akhir }}</td>
                                                            <td style="text-align: center; vertical-align: middle;">{!! $statusBadge !!}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="#" class="btn btn-info btn-sm">Edit</a>
                                                                    <form action="#" method="POST" style="margin-left: 5px;">
                                                                        @csrf
                                                                        <a class="btn btn-danger btn-sm delete-confirm-stokmasuk">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection