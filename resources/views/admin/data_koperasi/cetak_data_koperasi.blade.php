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

        /* Auto print */
        @media print {
            @page {
                size: F4 portrait;
                margin: 1.5cm 1cm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN DATA KOPERASI</h2>
        <h4>Periode 
            @if(request('dari_tanggal') && request('sampai_tanggal'))
                {{ request('dari_tanggal') }} s/d {{ request('sampai_tanggal') }}
            @elseif(request('bulan'))
                Bulan {{ request('bulan') }}
            @else
                Semua Periode
            @endif
        </h4>
    </div>

    <div style="margin-bottom:10px;">
        <strong>Sisa Seluruh Dana : Rp {{ number_format($sisa_dana, 0, ',', '.') }}</strong>
    </div>

    @php
        $grouped = $data->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_data_koperasi)->translatedFormat('d F Y');
        });
    @endphp

    @foreach ($grouped as $tanggal => $data_per_tanggal)
        @php
            $pemasukan = $data_per_tanggal->where('jenis_data_koperasi', 'Pemasukan');
            $pengeluaran = $data_per_tanggal->where('jenis_data_koperasi', 'Pengeluaran');
            $total_pemasukan = $pemasukan->sum('harga_data_koperasi');
            $total_pengeluaran = $pengeluaran->sum('harga_data_koperasi');
            $selisih = $total_pemasukan - $total_pengeluaran;
        @endphp

        <div class="row">
            <!-- PEMASUKAN -->
            <div class="col">
                <h5>PEMASUKAN</h5>
                <table>
                    <thead>
                        <tr><th colspan="3">Tanggal: {{ $tanggal }}</th></tr>
                        <tr>
                            <th>No.</th>
                            <th>Sumber</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemasukan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kategori_data_koperasi }}</td>
                                <td>Rp {{ number_format($item->harga_data_koperasi, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center;">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total Pemasukan</th>
                            <th>Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- SELISIH -->
            <div class="col" style="max-width: 180px; display:flex; align-items:center; justify-content:center;">
                <div class="selisih-box">
                    <div>SELISIH</div>
                    <div>Rp {{ number_format($selisih, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- PENGELUARAN -->
            <div class="col">
                <h5>PENGELUARAN</h5>
                <table>
                    <thead>
                        <tr><th colspan="3">Tanggal: {{ $tanggal }}</th></tr>
                        <tr>
                            <th>No.</th>
                            <th>Tujuan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kategori_data_koperasi }}</td>
                                <td>Rp {{ number_format($item->harga_data_koperasi, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center;">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total Pengeluaran</th>
                            <th>Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach

    <div class="footer">
        <p>Kalianda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Admin Koperasi</p>
        <br><br><br>
        <p>______________________</p>
    </div>
</body>
</html>