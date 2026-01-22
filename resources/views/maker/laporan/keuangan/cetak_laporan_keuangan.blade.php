<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
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

    <div class="header" style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0;">LAPORAN KEUANGAN</h2>
        <h4 style="margin:0;">
            Periode 
            @if(request('dari_tanggal') && request('sampai_tanggal'))
                {{ \Carbon\Carbon::parse(request('dari_tanggal'))->translatedFormat('d F Y') }} 
                s/d 
                {{ \Carbon\Carbon::parse(request('sampai_tanggal'))->translatedFormat('d F Y') }}
            @elseif(request('jenis_transaksi'))
                Total {{ request('jenis_transaksi') }}
            @else
                Semua Periode
            @endif
        </h4>
    </div>

    <div style="margin-bottom:15px; font-size:16px;">
        <strong>Sisa Seluruh Dana : Rp {{ number_format($sisa_dana, 0, ',', '.') }}</strong>
    </div>

    @php
        $grouped = $data->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_laporan_keuangan)->translatedFormat('d F Y');
        });
    @endphp

    <table border="1" cellspacing="0" cellpadding="6" width="100%" style="border-collapse: collapse; font-size: 14px;">
        <thead style="background-color:#e3f2fd; text-align:center;">
            <tr>
                <th rowspan="2" style="vertical-align:middle;">No.</th>
                <th rowspan="2" style="vertical-align:middle;">Tanggal</th>
                <th colspan="2" style="vertical-align:middle;">Sumber</th>
                <th colspan="2" style="vertical-align:middle;">Jumlah</th>
                <th rowspan="2" style="vertical-align:middle;">Selisih</th>
            </tr>
            <tr>
                <th style="vertical-align:middle;">Koperasi</th>
                <th style="vertical-align:middle;">Supplier</th>
                <th style="vertical-align:middle;">Pemasukan</th>
                <th style="vertical-align:middle;">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grouped as $tanggal => $data_per_tanggal)
                @php
                    $pemasukan = $data_per_tanggal->where('jenis_transaksi', 'Pemasukan');
                    $pengeluaran = $data_per_tanggal->where('jenis_transaksi', 'Pengeluaran');
                    $total_pemasukan = $pemasukan->sum('jumlah_dana');
                    $total_pengeluaran = $pengeluaran->sum('jumlah_dana');
                    $selisih = $total_pemasukan - $total_pengeluaran;

                    $ada_koperasi = $data_per_tanggal->contains('id_data_koperasi', '!=', null);
                    $ada_supplier = $data_per_tanggal->contains('id_informasi_supplier', '!=', null);
                @endphp

                <tr>
                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td>{{ $tanggal }}</td>
                    <td style="text-align: center; vertical-align: middle;">@if($ada_koperasi) ✅ @endif</td>
                    <td style="text-align: center; vertical-align: middle;">@if($ada_supplier) ✅ @endif</td>
                    <td>Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                    <td>
                        <strong class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($selisih, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:gray;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer" style="text-align:right; margin-top:40px;">
        <p>Kalianda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Admin Koperasi</p>
        <br><br><br>
        <p>______________________</p>
    </div>

    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 13px;
                color: #000;
                margin: 2cm 1cm;
            }

            table th, table td {
                border: 1px solid #000;
                padding: 6px;
            }

            th {
                background-color: #f2f2f2 !important;
            }

            .text-success {
                color: green !important;
            }

            .text-danger {
                color: red !important;
            }

            .footer {
                page-break-inside: avoid;
            }
        }
    </style>

</body>
</html>