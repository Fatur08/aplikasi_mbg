@extends('layouts.owner.tabler')
@section('content')
<style>
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
</style>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Halaman
                </div>
                <h2 class="page-title">
                    Dashboard
                </h2>
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
                                <h3 style="text-align: center;">KETERANGAN DAPUR</h3>
                                <form action="/owner/dashboardowner" method="GET">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-icon">
                                                <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                    <option value="">Pilih Dapur</option>
                                                    @foreach($dapurList as $dapur)
                                                        <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th colspan="4" style="text-align:center">
                                                {{ $dataDapur->first()->nama_dapur ?? 'DAPUR' }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">KECAMATAN</th>
                                            <th style="text-align:center">ADMIN</th>
                                            <th style="text-align:center">KEPALA DAPUR</th>
                                            <th style="text-align:center">DISTRIBUTOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$sudahCari)
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="alert alert-info m-0">
                                                        Silakan Pilih Dapur Terlebih Dahulu
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif($dataKosong)
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="alert alert-warning m-0">
                                                        Data tidak ditemukan
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($dataDapur as $dapur)
                                                <tr>
                                                    <td style="text-align:center">{{ $dapur->dapur_kecamatan }}</td>
                                                    <td style="text-align:center">
                                                        @php
                                                            $admin = $admins->where('nomor_dapur_admin', $dapur->nomor_dapur)->first();
                                                        @endphp
                                                        {{ $admin->nama_admin ?? '-' }}
                                                    </td>
                                                    <td style="text-align:center">
                                                        @php
                                                            $kepala = $kepalaDapur->where('nomor_dapur_kepala_dapur', $dapur->nomor_dapur)->first();
                                                        @endphp
                                                        {{ $kepala->nama_lengkap ?? '-' }}
                                                    </td>
                                                    <td style="text-align:center">
                                                        @php
                                                            $dist = $distributors->where('nomor_dapur_distributor', $dapur->nomor_dapur)->first();
                                                        @endphp
                                                        {{ $dist->nama_distributor ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-2 table-container">
                            <div class="col-12">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th colspan="7" style="text-align:center">INFORMASI DISTRIBUSI</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">NO.</th>
                                            <th style="text-align:center">TANGGAL</th>
                                            <th style="text-align:center">SEKOLAH</th>
                                            <th style="text-align:center">MENU</th>
                                            <th style="text-align:center">PORSI</th>
                                            <th style="text-align:center">STATUS</th>
                                            <th style="text-align:center">VALIDASI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$sudahCari)
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-info m-0">
                                                        Silakan Pilih Dapur Terlebih Dahulu
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif($dataKosong)
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-warning m-0">
                                                        Data tidak ditemukan
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($dataDistribusi as $index => $distribusi)
                                                <tr>
                                                    <td style="text-align:center">{{ $index + 1 }}</td>
                                                    <td style="text-align:center">{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('d F Y') }}</td>
                                                    <td style="text-align:center">{{ $distribusi->tujuan_distribusi }}</td>
                                                    <td style="text-align:center">{{ $distribusi->menu_makanan }}</td>
                                                    <td style="text-align:center">{{ $distribusi->jumlah_paket }}</td>
                                                    <td style="text-align:center">
                                                        @if($distribusi->status_distribusi == 0)
                                                            <span class="badge bg-warning">Dalam Proses</span>
                                                        @elseif($distribusi->status_distribusi == 1)
                                                            <span class="badge bg-success">Sudah Diterima</span>
                                                        @elseif($distribusi->status_distribusi == 2)
                                                            <span class="badge bg-danger">Belum Diterima</span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align:center">
                                                        @if($distribusi->status_distribusi == 0)
                                                            <span class="badge bg-warning">Menunggu</span>
                                                        @elseif($distribusi->status_distribusi == 1)
                                                            <span class="badge bg-success">Disetujui</span>
                                                        @elseif($distribusi->status_distribusi == 2)
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <!--<h3 style="text-align: center;">GRAFIK BATANG LAPORAN KEUANGAN</h3>
                                <form action="/owner/dashboardowner" method="GET">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="dari_tanggal" name="dari_tanggal" class="form-control" placeholder="Dari Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <input type="text" value="" id="sampai_tanggal" name="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                    Cari    
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>-->
                            </div>
                        </div>
                        
                        
                        
                        <!--<div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                            <canvas id="koperasiChart" height="340"></canvas>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
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

    document.addEventListener("DOMContentLoaded", function () {
        let dari = document.getElementById("dari_tanggal");
        let sampai = document.getElementById("sampai_tanggal");
        let bulan = document.getElementById("bulan");
            
        function toggleBulan() {
            if (dari.value && sampai.value) {
                bulan.disabled = true;   // disable select bulan
                bulan.value = "";        // reset pilihannya
            } else {
                bulan.disabled = false;  // aktifkan lagi
            }
        }
    
        function toggleTanggal() {
            if (bulan.value) {
                dari.readOnly = true;    
                sampai.readOnly = true;  
                dari.value = "";         
                sampai.value = "";       
            } else {
                dari.readOnly = false;   
                sampai.readOnly = false; 
            }
        }
    
        dari.addEventListener("change", toggleBulan);
        sampai.addEventListener("change", toggleBulan);
        bulan.addEventListener("change", toggleTanggal);
    });
</script>
@endpush