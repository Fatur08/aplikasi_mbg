<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Koperasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 1.5cm 1cm;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 30px;
        }
        .col {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 10.5pt;
        }
        th {
            background: #f2f2f2;
            text-align: center;
        }
        h5 {
            text-align: center;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .selisih-box {
            text-align: center;
            border: 1px solid #000;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20%;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11pt;
        }
        .footer p {
            margin: 5px 0;
        }

        .badge-box {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10pt;
            font-weight: bold;
            border: 1px solid #000;
        }

        /* Warna kotak */
        .bg-warning-box { background: #ffc107 !important; color: #000 !important; }
        .bg-success-box { background: #28a745 !important; color: #fff !important; }
        .bg-danger-box  { background: #dc3545 !important; color: #fff !important; }

        /* Auto print */
        @media print {
            @page {
                size: F4 portrait;
                margin: 1.5cm 1cm;
            }
        }

        /* Paksa warna background muncul saat print */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN DATA KOPERASI</h2>
        <h4 style="margin:0;">
            Periode:
            {{ \Carbon\Carbon::parse($dari_tanggal)->translatedFormat('d F Y') }}
            s/d
            {{ \Carbon\Carbon::parse($sampai_tanggal)->translatedFormat('d F Y') }}
        </h4>
    </div>

    @foreach ($grouped as $tanggal => $data)
        @php
            $totalMasuk  = $data->where('jenis_data_koperasi', 'modal_masuk')
                                ->sum('harga_data_koperasi');

            $totalKeluar = $data->where('jenis_data_koperasi', 'modal_keluar')
                                ->sum('total_harga_supplier');

            $selisih = $totalMasuk - $totalKeluar;
        @endphp
        
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2><b>{{ $tanggal }}</b></h2>
                <h2>Selisih: <b>Rp {{ number_format($selisih, 0, ',', '.') }}</b></h2>
            </div>
        
            <div class="card-body">
                <div class="row">
        
                    {{-- ===================== MODAL MASUK ===================== --}}
                    <div class="col-md-6">
                        <h3 class="text-success"><b>Modal Masuk</b></h3>
        
                        <table class="table table-bordered table-sm">
                            <thead class="table-success">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th>Validasi</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @php $noMasuk = 1; @endphp
                                @foreach ($data->where('jenis_data_koperasi', 'modal_masuk') as $d)
                                    <tr>
                                        <td class="text-center">{{ $noMasuk++ }}</td>
                                        <td>{{ $d->kategori_data_koperasi ?? '-' }}</td>
                                        <td>Rp {{ number_format($d->harga_data_koperasi, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if($d->status_data_koperasi == 0)
                                                <span class="badge-box bg-warning-box">Menunggu</span>
                                            @elseif($d->status_data_koperasi == 1)
                                                <span class="badge-box bg-success-box">Disetujui</span>
                                            @else
                                                <span class="badge-box bg-danger-box">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
        
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th colspan="3">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
        
                    {{-- ===================== MODAL KELUAR ===================== --}}
                    <div class="col-md-6">
                        <h3 class="text-danger"><b>Modal Keluar</b></h3>
        
                        <table class="table table-bordered table-sm">
                            <thead class="table-danger">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Validasi</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @php $noKeluar = 1; @endphp
                                @foreach ($data->where('jenis_data_koperasi', 'modal_keluar') as $d)
                                    <tr>
                                        <td class="text-center">{{ $noKeluar++ }}</td>
                                        <td>{{ $d->kategori_data_koperasi ?? '-' }}</td>
        
                                        {{-- total_harga_supplier sudah dihitung dari query join --}}
                                        <td>Rp {{ number_format($d->total_harga_supplier, 0, ',', '.') }}</td>
        
                                        <td class="text-center">
                                            @if($d->status_data_koperasi == 0)
                                                <span class="badge-box bg-warning-box">Menunggu</span>
                                            @elseif($d->status_data_koperasi == 1)
                                                <span class="badge-box bg-success-box">Disetujui</span>
                                            @else
                                                <span class="badge-box bg-danger-box">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
        
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th colspan="3">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
        
                </div> {{-- row --}}
            </div> {{-- card-body --}}
        </div> {{-- card --}}
    @endforeach

    <div class="footer">
        <p>Kalianda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Admin Koperasi</p>
        <br><br><br>
        <p>______________________</p>
    </div>
</body>
</html>