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
        <h4 style="margin:0;">
            Periode Bulan 
            {{ $bulan }}
        </h4>
    </div>

    <div style="margin-bottom:10px;">
        <strong>Sisa Seluruh Dana : Rp {{ number_format($sisa_dana, 0, ',', '.') }}</strong>
    </div>

    @foreach ($grouped as $tanggal => $data)
        @php
            $totalMasuk = 0;
            $totalKeluar = 0;
        
            foreach ($data as $item) {
                if ($item->jenis_data_koperasi == 'modal_masuk') {
                    $totalMasuk += $item->harga_data_koperasi;
                
                } elseif ($item->jenis_data_koperasi == 'modal_keluar') {
                
                    // Jika berasal dari supplier
                    if (!empty($item->id_informasi_supplier) && $item->id_informasi_supplier > 0) {
                        $totalKeluar += DB::table('barang_supplier')
                            ->where('id_informasi_supplier', $item->id_informasi_supplier)
                            ->where('nomor_dapur_barang_supplier', $item->nomor_dapur_data_koperasi)
                            ->sum(DB::raw('harga_barang_supplier'));
                    } 
                    else {
                        // Non-supplier
                        $totalKeluar += DB::table('barang_modal_keluar')
                            ->where('id_data_koperasi', $item->id_data_koperasi)
                            ->where('nomor_dapur_barang_modal_keluar', $item->nomor_dapur_data_koperasi)
                            ->sum(DB::raw('harga_barang_modal_keluar'));
                    }
                }
            }
        
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
                                    <th>Aksi</th>
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
                                                <button class="btn btn-warning btn-sm">Menunggu</button>
                                            @elseif($d->status_data_koperasi == 1)
                                                <button class="btn btn-success btn-sm">Disetujui</button>
                                            @else
                                                <button class="btn btn-danger btn-sm">Ditolak</button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group-vertical">
        
                                                @if($d->status_data_koperasi == 0)
                                                <a href="#" 
                                                   class="validasi_data_koperasi btn btn-primary btn-sm mb-1"
                                                   id="{{ $d->id_data_koperasi }}">
                                                    <i class="bi bi-pencil-square"></i> Validasi
                                                </a>
                                                @else
                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/batalkan_validasi_data_koperasi" method="POST">
                                                    @csrf
                                                    <a class="btn btn-danger btn-sm batalkan_validasi_data_koperasi mb-1">
                                                        Batalkan
                                                    </a>
                                                </form>
                                                @endif
        
                                                <a href="#" class="edit_modal_masuk_data_koperasi btn btn-info btn-sm mb-1"
                                                   id="{{ $d->id_data_koperasi }}">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
        
                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/delete_data_koperasi" method="POST">
                                                    @csrf
                                                    <a class="btn btn-danger btn-sm delete-confirm-data-koperasi mb-1">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </a>
                                                </form>
        
                                            </div>
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
                                    <th>Aksi</th>
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
                                                <button class="btn btn-warning btn-sm">Menunggu</button>
                                            @elseif($d->status_data_koperasi == 1)
                                                <button class="btn btn-success btn-sm">Disetujui</button>
                                            @else
                                                <button class="btn btn-danger btn-sm">Ditolak</button>
                                            @endif
                                        </td>
        
                                        <td class="text-center">
                                            <div class="btn-group-vertical">
                                                @if($d->status_data_koperasi == 0)
                                                <a href="#" class="validasi_data_koperasi btn btn-primary btn-sm mb-1"
                                                   id="{{ $d->id_data_koperasi }}">
                                                   Validasi
                                                </a>
                                                @else
                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/batalkan_validasi_data_koperasi" method="POST">
                                                    @csrf
                                                    <a class="btn btn-danger btn-sm batalkan_validasi_data_koperasi mb-1">Batalkan</a>
                                                </form>
                                                @endif
        
                                                <a href="#" class="edit_modal_keluar_data_koperasi btn btn-info btn-sm mb-1"
                                                   id="{{ $d->id_data_koperasi }}">
                                                    Edit
                                                </a>
        
                                                <form action="/owner/data_koperasi/{{ $d->id_data_koperasi }}/delete_data_koperasi" method="POST">
                                                    @csrf
                                                    <a class="btn btn-danger btn-sm delete-confirm-data-koperasi mb-1">
                                                        Hapus
                                                    </a>
                                                </form>
        
                                                <a href="#" class="tambah_barang_modal_keluar btn btn-success btn-sm mb-1"
                                                   data-id="{{ $d->id_data_koperasi }}">
                                                    Tambah
                                                </a>
        
                                                <a href="#" class="lihat_barang_modal_keluar btn btn-secondary btn-sm"
                                                   data-id="{{ $d->id_data_koperasi }}">
                                                    Lihat
                                                </a>
                                            </div>
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