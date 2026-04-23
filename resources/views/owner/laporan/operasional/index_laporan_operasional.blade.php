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
                <div class="page-pretitle">
                    Halaman
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="page-title mb-0">Laporan Operasional</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>

</script>
@endpush