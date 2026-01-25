<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                margin: 1cm 1cm;
            }
        }
    </style>
</head>
<body>

    <div class="header" style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0;">LAPORAN KEUANGAN</h2>
        <h4 style="margin:0;">
            Periode :
            @if($dari_tanggal && $sampai_tanggal)
                {{ \Carbon\Carbon::parse($dari_tanggal)->translatedFormat('d F Y') }}
                s/d
                {{ \Carbon\Carbon::parse($sampai_tanggal)->translatedFormat('d F Y') }}
            @elseif($dari_tanggal)
                Mulai {{ \Carbon\Carbon::parse($dari_tanggal)->translatedFormat('d F Y') }}
            @elseif($sampai_tanggal)
                Sampai {{ \Carbon\Carbon::parse($sampai_tanggal)->translatedFormat('d F Y') }}
            @else
                Semua Periode
            @endif
        </h4>
    </div>


    <div class="row mt-2">
        <div class="col-12">
            <div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                <canvas id="koperasiChart" height="340"></canvas>
            </div>
        </div>
    </div>

    

    <table class="table table-bordered align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Tanggal</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Barang</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Jumlah</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Keterangan</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Dana</th>
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle;">Koperasi</th>
                <th style="text-align: center; vertical-align: middle;">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $i => $row)
            <tr>
                {{-- No --}}
                <td class="text-center">{{ $i + 1 }}</td>

                {{-- Tanggal --}}
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($row->tanggal_laporan_keuangan)->translatedFormat('d F Y') }}
                </td>

                {{-- Barang --}}
                <td>
                    {{ $row->nama_barang_modal_keluar ?? $row->nama_barang_supplier }}
                </td>

                {{-- Jumlah --}}
                <td class="text-center">
                    {{ $row->jumlah_barang_modal_keluar ?? $row->jumlah_barang_supplier }}
                </td>

                {{-- Koperasi --}}
                <td class="text-center">
                    @if ($row->nama_barang_modal_keluar)
                        ✔
                    @endif
                </td>

                {{-- Supplier --}}
                <td class="text-center">
                    @if ($row->nama_barang_supplier)
                        ✔
                    @endif
                </td>

                {{-- Dana --}}
                <td class="text-end">
                    Rp {{ number_format(
                        $row->harga_barang_modal_keluar ?? $row->harga_barang_supplier,
                        0, ',', '.'
                    ) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">
                    Tidak ada data laporan keuangan
                </td>
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

    <script>
        // === BAGIAN DIAGRAM BATANG ===
        let koperasiData = @json($grafik);

        if (!koperasiData || koperasiData.length === 0) {
            console.warn('DATA GRAFIK KOSONG');
        }

        const labels = koperasiData.map(item =>
            item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal'
        );

        const modalKeluar = koperasiData.map(item =>
            Number(item.total_pengeluaran) || 0
        );

        // FORMAT RUPIAH
        function formatRupiah(angka) {
            return 'Rp.' + angka.toLocaleString('id-ID');
        }

        const canvas = document.getElementById('koperasiChartOwner');

        if (canvas) {
            const ctxBar = canvas.getContext('2d');

            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: modalKeluar,
                        backgroundColor: 'rgba(255, 0, 0, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { font: { size: 14 } }
                        },
                        datalabels: {
                            display: true,
                            anchor: 'end',   // nempel di ujung atas batang
                            align: 'end',    // arah ke atas
                            offset: -4,      // naik sedikit biar tidak nempel batang
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function (value) {
                                return value > 0
                                    ? 'Rp.' + value.toLocaleString('id-ID')
                                    : '';
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return formatRupiah(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>
</html>