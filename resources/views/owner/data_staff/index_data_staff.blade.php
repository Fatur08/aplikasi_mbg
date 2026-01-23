@extends('layouts.owner.tabler')
@section('content')
<style>
.section-info {
    margin-bottom: 15px;
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
                                    Data Staff
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahDapur">
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

                        <!-- ===== MAKER ===== -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ url('/owner/data_staff/maker') }}" class="btn btn-primary w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/maker.png') }}" class="img-fluid mb-2" width="80"> -->

                                    <div class="fw-bold fs-2 text-white">MAKER</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== SPPI & AHLI GIZI ===== -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/sppi') }}" class="btn btn-success w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/sppi.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">SPPI</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/ahli_gizi') }}" class="btn btn-warning w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/ahli-gizi.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-dark">Ahli Gizi</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== AKUNTAN & ASLAP ===== -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/akuntan') }}" class="btn btn-info w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/akuntan.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Akuntan</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/aslap') }}" class="btn btn-secondary w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/aslap.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Aslap</div>
                                </a>
                            </div>
                        </div>

                        <!-- ===== DRIVER & RELAWAN ===== -->
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/driver') }}" class="btn btn-dark w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/driver.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Driver</div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ url('/owner/data_staff/relawan') }}" class="btn btn-danger w-100 text-center py-4 text-decoration-none">

                                    <!-- <img src="{{ asset('images/relawan.png') }}" class="img-fluid mb-2" width="70"> -->

                                    <div class="fw-bold fs-2 text-white">Relawan</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection