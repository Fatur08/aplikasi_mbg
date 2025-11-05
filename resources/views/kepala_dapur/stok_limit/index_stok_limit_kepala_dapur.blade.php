@extends('layouts.kepala_dapur.tabler')
@section('content')
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
                                    Stok Limit
                                </h2>
                            </td>
                            <td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahStokLimit">
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
                            </td>
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
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/kepala_dapur/stok_limit" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="id_bahan" id="id_bahan" class="form-select">
                                                    <option value="">Pilih Bahan</option>
                                                    @foreach($bahan as $item)
                                                        <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
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
                        <!--<div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">No.</th>
                                            <th style="text-align:center">Bahan</th>
                                            <th style="text-align:center">Masuk</th>
                                            <th style="text-align:center">Kadaluarsa</th>
                                            <th style="text-align:center">Sisa</th>
                                            <th style="text-align:center">Limit</th>
                                            <th style="text-align:center">Selisih</th>
                                            <th style="text-align:center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stok->groupBy('nama_bahan') as $namaBahan => $items)
                                        @php
                                            $first = $items->first(); // Ambil data pertama per bahan
                                            $totalSisa = $items->sum('sisa_stok');
                                            $limit_bahan = $first->limit_bahan;
                                            $selisih = $totalSisa - $limit_bahan;
                                            // Tentukan status
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
                                            <tr @if($statusCode == 0) class="table-danger" 
                                                @elseif($statusCode == 1) class="table-warning" 
                                                @else class="table-success" @endif>
                                                <td style="text-align:center">{{ $loop->iteration }}</td>
                                                <td>{{ $namaBahan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($first->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                                <td></td>
                                                <td>
                                                    @if($totalSisa == 0)
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>{{ $totalSisa }} {{ $first->satuan_bahan }}</span>
                                                            <button class="btn btn-warning btn-sm" disabled>Habis</button>
                                                        </div>
                                                    @else
                                                        {{ $totalSisa }} {{ $first->satuan_bahan }}
                                                    @endif
                                                </td>
                                                <td>{{ $first->limit_bahan }} {{ $first->satuan_bahan }}</td>
                                                <td>
                                                    {{ $selisih }} {{ $first->satuan_bahan }}
                                                </td>
                                                <td style="text-align:center">
                                                    <span class="badge bg-{{ $statusColor }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
                        




                        <div class="row mt-2">
                            <div class="col-12">
                                @php
                                    use Carbon\Carbon;
                                    // Kelompokkan data stok berdasarkan id_bahan
                                    $groupedStok = $stok->groupBy('id_bahan');
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
                                                            <a href="#" class="tambah_tanggal_kadaluarsa" data-id="{{ $data->id_stok_masuk }}"> 
                                                                <button type="button" class="btn btn-sm btn-primary">    
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                                         class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M12 5l0 14" />
                                                                        <path d="M5 12l14 0" />
                                                                    </svg>
                                                                    Tambah
                                                                </button>
                                                            </a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Stok Limit --}}
<div class="modal modal-blur fade" id="modal-inputstoklimit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jumlah Limit Bahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kepala_dapur/stok_limit/store_stok_limit" method="POST" id="frmStkLmt" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <select name="id_bahan" id="id_bahan" class="form-select">
                                <option value="">Pilih Bahan (Yang Tersedia Di Dapur)</option>
                                @foreach($bahan as $item)
                                    <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calculator"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" /><path d="M8 14l0 .01" /><path d="M12 14l0 .01" /><path d="M16 14l0 .01" /><path d="M8 17l0 .01" /><path d="M12 17l0 .01" /><path d="M16 17l0 .01" /></svg>
                                </span>
                                <input type="number" value="" id="limit_bahan" class="form-control" name="limit_bahan" placeholder="Masukkan Jumlah Limit Bahan">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Modal Tambah Tanggal Kadaluarsa --}}
<div class="modal modal-blur fade" id="modal-tambahtanggalkadaluarsa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tanggal Kadaluarsa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahtanggalkadaluarsa">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#btnTambahStokLimit").click(function(){
            $("#modal-inputstoklimit").modal("show");
        });


        $(".tambah_tanggal_kadaluarsa").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/kepala_dapur/stok_limit/tambah_tanggal_kadaluarsa',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformtambahtanggalkadaluarsa").html(respond);
                }
            });
            $("#modal-tambahtanggalkadaluarsa").modal("show");
        });


        $("#frmStkLmt").submit(function(){
            var limit_bahan = $("#limit_bahan").val();
            if (limit_bahan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Limit Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#limit_bahan").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush