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
                <th style="text-align: center; vertical-align: middle;" colspan="2">Sumber</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Jumlah</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Selisih</th>
                <!--<th style="text-align: center; vertical-align: middle;" rowspan="2">Validasi</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>-->
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle;">Koperasi</th>
                <th style="text-align: center; vertical-align: middle;">Supplier</th>
                <th style="text-align: center; vertical-align: middle;">Pemasukan</th>
                <th style="text-align: center; vertical-align: middle;">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grouped as $tanggal => $data_per_tanggal)
                @php
                    // Pisahkan data berdasarkan jenis transaksi
                    $pemasukan = $data_per_tanggal->where('jenis_transaksi', 'Pemasukan');
                    $pengeluaran = $data_per_tanggal->where('jenis_transaksi', 'Pengeluaran');
                                                    
                    // Hitung total pemasukan
                    $total_pemasukan = $data_per_tanggal
                        ->where('jenis_data_koperasi', 'modal_masuk')
                        ->where('status_data_koperasi', 1)
                        ->sum('harga_data_koperasi');
                                                    
                    // Hitung total pengeluaran dari sumber berbeda
                    $total_pengeluaran_supplier = $data_per_tanggal
                        ->whereNotNull('harga_barang_supplier')
                        ->sum('harga_barang_supplier');
                    $total_pengeluaran_modal_keluar = $data_per_tanggal
                        ->whereNotNull('harga_barang_modal_keluar')
                        ->sum('harga_barang_modal_keluar');
                    $total_pengeluaran = $total_pengeluaran_supplier + $total_pengeluaran_modal_keluar;
                                                    
                    // Selisih total
                    $selisih = $total_pemasukan - $total_pengeluaran;
                                                    
                    // Ambil data pertama untuk id & status validasi
                    $laporan = $data_per_tanggal->first();
                    $id_laporan = optional($laporan)->id_laporan_keuangan;
                    $status_validasi = optional($laporan)->status_validasi;
                                                    
                    // Cek apakah pengeluaran dari data koperasi atau supplier
                    $ada_koperasi = $data_per_tanggal->contains('id_data_koperasi', '!=', null);
                    $ada_supplier = $data_per_tanggal->contains('id_informasi_supplier', '!=', null);
                @endphp
    
                <tr>
                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</td>
                    <td style="text-align: center; vertical-align: middle;">
                        {{ ($ada_koperasi && !$ada_supplier) ? '✅' : '' }}
                    </td>                         
                    <td style="text-align: center; vertical-align: middle;">
                        {{ ($ada_koperasi && $ada_supplier) ? '✅' : '' }}
                    </td>
                    <td class="text-success">
                        Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
                    </td>
                    <td class="text-danger">
                        Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}
                    </td>
                    <td>
                        <strong class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($selisih, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data</td>
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
        let koperasiData = @json($data);
        
        const labels      = koperasiData.map(item => item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal');
        const modalMasuk  = koperasiData.map(item => item.total_pemasukan);
        const modalKeluar = koperasiData.map(item => item.total_pengeluaran);
        const margin      = koperasiData.map(item => item.margin);
        
        const ctxBar = document.getElementById('koperasiChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: modalMasuk,
                        backgroundColor: 'rgba(0, 76, 255, 1)'
                    },
                    {
                        label: 'Pengeluaran',
                        data: modalKeluar,
                        backgroundColor: 'rgba(255, 0, 0, 0.7)'
                    },
                    {
                        label: 'Margin',
                        data: margin,
                        backgroundColor: 'rgba(47, 255, 0, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { font: { size: 14 } } }
                }
            }
        });
    
        // ✅ PRINT SETELAH GRAFIK SELESAI DIRAWAT
        setTimeout(() => {
            window.print();
        }, 1000);
    </script>

</body>
</html>