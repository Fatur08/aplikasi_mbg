@extends('layouts.owner.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Halaman
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="page-title mb-0">Laporan Stok</h2>
                </div>
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
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <select name="dapur_kecamatan" id="dapur_kecamatan" class="form-select">
                                                        <option value="">Pilih Dapur</option>
                                                        <option value="1">Dapur 1</option>
                                                        <option value="2">Dapur 2</option>
                                                        <option value="3">Dapur 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <select name="dapur_kecamatan" id="dapur_kecamatan" class="form-select">
                                                        <option value="">Pilih Bahan</option>
                                                        @foreach($bahan as $item)
                                                            <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
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
                                            <div class="col-3">
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No.</th>
                                                <th rowspan="2">Bahan</th>
                                                <th rowspan="2">Satuan</th>
                                                <th rowspan="2">Awal</th>
                                                <th colspan="2">Stok</th>
                                                <th rowspan="2">Akhir</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th>Masuk</th>
                                                <th>Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>2</td>
                                                <td>3</td>
                                                <td>4</td>
                                                <td>5</td>
                                                <td>6</td>
                                                <td>7</td>
                                                <td>8</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No.</th>
                                                <th rowspan="2">Bahan</th>
                                                <th rowspan="2">Satuan</th>
                                                <th colspan="2">Stok</th>
                                                <th rowspan="2">Akhir</th>
                                                <th rowspan="2">Ket.</th>
                                            </tr>
                                            <tr>
                                                <th>Masuk</th>
                                                <th>Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>2</td>
                                                <td>3</td>
                                                <td>4</td>
                                                <td>5</td>
                                                <td>6</td>
                                                <td>7</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h3 style="text-align: center;">STOK MASUK</h3>
                                    @foreach($stok_masuk->groupBy('nama_bahan') as $namaBahan => $items)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">{{ $loop->iteration }}. Nama Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $namaBahan }}</th>
                                                    <th colspan="4">Sisa Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $items->sum('sisa_stok') }} {{ $stok_masuk->first()->satuan_bahan ?? '' }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Sisa</th>
                                                    <th>Sumber</th>
                                                    <th>Ket.</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $key => $data)
                                                    <tr @if($data->sisa_stok == 0 || $data->status_stok = 0) class="table-warning" @endif>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->jumlah_masuk }} {{ $data->satuan_bahan }}</td>
                                                        <td>
                                                            @if($data->sisa_stok == 0)
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>{{ $data->sisa_stok }} {{ $data->satuan_bahan }}</span>
                                                                    <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                                </div>
                                                            @else
                                                                {{ $data->sisa_stok }} {{ $data->satuan_bahan }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $data->sumber_stok_masuk }}</td>
                                                        <td>{{ $data->keterangan_stok_masuk }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/stok_masuk/{{ $data->id_stok_masuk }}/delete_stok_masuk" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-stokmasuk" >
                                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                                        Hapus
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h3 style="text-align: center;">STOK KELUAR</h3>
                                    @foreach($stok_keluar->groupBy('nama_bahan') as $namaBahan => $items)
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">{{ $loop->iteration }}. Nama Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $namaBahan }}</th>
                                                    <th colspan="4">
                                                        Sisa Bahan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                        {{ $sisa_perbahan[$namaBahan]->sisa_per_bahan ?? 0 }}
                                                        {{ $sisa_perbahan[$namaBahan]->satuan_bahan ?? '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Keluar</th>
                                                    <th>Masuk</th>
                                                    <th>Jumlah</th>
                                                    <th>Sisa</th>
                                                    <th>Tujuan</th>
                                                    <th>Ket.</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $key => $data)
                                                    <tr @if($data->sisa_stok == 0 || $data->sisa_perbahan <= 0) class="table-warning" @endif>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_keluar)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                        <td>{{ $data->jumlah_keluar }} {{ $data->satuan_bahan }}</td>
                                                        <td>
                                                            @if($data->sisa_stok == 0)
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>{{ $data->sisa_stok }} {{ $data->satuan_bahan }}</span>
                                                                    <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                                </div>
                                                            @else
                                                                {{ $data->sisa_stok }} {{ $data->satuan_bahan }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $data->tujuan_stok_keluar }}</td>
                                                        <td>{{ $data->keterangan_stok_keluar }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <form action="/kepala_dapur/stok_keluar/{{ $data->id_stok_keluar }}/delete_stok_keluar" style="margin-left: 5px;" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm-stokmasuk" >
                                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                                                        Hapus
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h3 style="text-align: center;">STOK LIMIT</h3>
                                    @php
                                        use Carbon\Carbon;
                                        // Kelompokkan data stok berdasarkan id_bahan
                                        $groupedStok = $stok_limit->groupBy('id_bahan');
                                    @endphp

                                    @foreach ($groupedStok as $id_bahan => $items)
                                        @php
                                            $nama_bahan = $items->first()->nama_bahan;
                                            $limit = $items->first()->limit_bahan;
                                            $satuan = $items->first()->satuan_bahan;

                                            // Hitung total sisa hanya dari stok yang belum kadaluarsa
                                            $hariIni = Carbon::today();
                                            $totalSisa = $items->sum(function ($item) use ($hariIni) {
                                                if (!empty($item->tanggal_kadaluarsa)) {
                                                    $tglKadaluarsa = Carbon::parse($item->tanggal_kadaluarsa);
                                                    // Jika sudah kadaluarsa, hitung sisa sebagai 0 agar tidak mempengaruhi total
                                                    return $hariIni->greaterThanOrEqualTo($tglKadaluarsa) ? 0 : $item->sisa_stok;
                                                }
                                                // Jika belum ada tanggal kadaluarsa, tetap dihitung
                                                return $item->sisa_stok;
                                            });
                                        
                                            $limit_bahan = $items->first()->limit_bahan;
                                            $selisih = $totalSisa - $limit_bahan;
                                        
                                            // Tentukan status stok
                                            if ($totalSisa == 0) {
                                                $statusText = 'Habis';
                                                $statusCode = 0;
                                                $statusColor = 'danger';
                                            } elseif ($totalSisa <= $limit_bahan) {
                                                $statusText = 'Limit / Hampir Habis';
                                                $statusCode = 1;
                                                $statusColor = 'warning';
                                            } else {
                                                $statusText = 'Cukup';
                                                $statusCode = 2;
                                                $statusColor = 'success';
                                            }
                                        @endphp

                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>{{ $loop->iteration }}. <strong>Nama Bahan :</strong> {{ $nama_bahan }}</th>
                                                    <th><strong>Sisa :</strong> {{ $totalSisa }} {{ $satuan }}</th>
                                                    <th><strong>Limit :</strong> {{ $limit }} {{ $satuan }}</th>
                                                    <th>
                                                        <strong>Selisih :</strong>
                                                        <span class="{{ $selisih <= 0 ? 'text-danger' : 'text-success' }}">
                                                            {{ $selisih }} {{ $satuan }}
                                                        </span>
                                                    </th>
                                                    <th style="text-align:center">
                                                        <strong>
                                                            <span class="badge bg-{{ $statusColor }}">
                                                                {{ $statusText }}
                                                            </span>
                                                        </strong>
                                                    </th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th>No.</th>
                                                    <th>Masuk</th>
                                                    <th>Kadaluarsa</th>
                                                    <th colspan="2">Sisa per Masuk</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $index => $data)
                                                    @php
                                                        // Reset nilai sisa stok setiap loop agar tidak terbawa
                                                        $sisaStok = $data->sisa_stok;
                                                        $hariIni = Carbon::today();
                                                    @endphp
                                        
                                                    <tr class="text-center">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                        <td>
                                                            @if (empty($data->tanggal_kadaluarsa))
                                                                {{-- Jika belum ada tanggal kadaluarsa --}}
                                                                Belum Ada Tanggal Kadaluarsa
                                                            @else
                                                                {{-- Jika sudah ada tanggal kadaluarsa --}}
                                                                {{ Carbon::parse($data->tanggal_kadaluarsa)->translatedFormat('d F Y') }}
                                        
                                                                @php
                                                                    $tanggalKadaluarsa = Carbon::parse($data->tanggal_kadaluarsa);
                                                                    $selisihHari = $hariIni->diffInDays($tanggalKadaluarsa, false);
                                                                @endphp
                                        
                                                                {{-- Jika sudah kadaluarsa --}}
                                                                @if ($hariIni->greaterThanOrEqualTo($tanggalKadaluarsa))
                                                                    @php
                                                                        $sisaStok = -abs($sisaStok); // ubah ke minus hanya untuk baris ini
                                                                    @endphp
                                                                    <button type="button" class="btn btn-sm btn-danger ms-2">
                                                                        <i class="bi bi-exclamation-triangle"></i> Kadaluarsa
                                                                    </button>
                                                                {{-- 1 hari sebelum kadaluarsa --}}
                                                                @elseif ($selisihHari == 1)
                                                                    <button type="button" class="btn btn-sm btn-warning ms-2">
                                                                        <i class="bi bi-exclamation-circle"></i> Hampir Kadaluarsa
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td colspan="2">
                                                            <span class="{{ $sisaStok < 0 ? 'text-danger fw-bold' : '' }}">
                                                                {{ $sisaStok }} {{ $satuan }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                            <!--<div class="row mt-2">
                                <div class="col-12">
                                    <form action="/owner/laporan/stok" method="GET">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="nama_kepala_dapur" id="nama_kepala_dapur" placeholder="Nama Kepala Dapur">
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <select name="dapur_kecamatan" id="dapur_kecamatan" class="form-select">
                                                        <option value="">Pilih Dapur</option>
                                                        @foreach($dapurs as $dapur)
                                                            <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2">
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    @if(!$sudahCari)
                                        <div class="alert alert-info text-center">
                                            Silakan lakukan pencarian terlebih dahulu
                                        </div>
                                    @elseif($dataKosong)
                                        <div class="alert alert-warning text-center">
                                            Data tidak ditemukan
                                        </div>
                                    @else
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th colspan="5">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_kepala_dapur }}</th>
                                                    <th colspan="4">Nama Dapur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $nama_dapur }}</th>
                                                </tr>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Stok</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Jumlah Masuk</th>
                                                    <th>Tanggal Keluar</th>
                                                    <th>Jumlah Keluar</th>
                                                    <th>Sisa</th>
                                                    <th>Sumber</th>
                                                    <th>Ket.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($stok as $d)
                                                <tr>
                                                    <td>{{ $loop->iteration + $stok->firstItem()-1 }}</td>
                                                    <td>{{ $d->nama_stok }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($d->tanggal_masuk_stok)->translatedFormat('d F Y') }}</td>
                                                    <td>{{ $d->jumlah_stok_masuk }} {{ $d->satuan_stok }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($d->tanggal_keluar_stok)->translatedFormat('d F Y') }}</td>
                                                    <td>{{ $d->jumlah_stok_keluar }} {{ $d->satuan_stok }}</td>
                                                    <td>{{ $d->sisa_stok }} {{ $d->satuan_stok }}</td>
                                                    <td>{{ $d->sumber_masuk_stok }}</td>
                                                    <td>{{ $d->keterangan_stok }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection