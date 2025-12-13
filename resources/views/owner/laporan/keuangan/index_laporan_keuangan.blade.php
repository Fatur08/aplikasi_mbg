@extends('layouts.owner.tabler')
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
                                    Laporan Keuangan
                                </h2>
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
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-primary">
                                        <strong>Sisa Seluruh Dana :</strong> Rp {{ number_format($sisa_dana, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/owner/laporan/keuangan" method="GET" id="FormLaporanKeuangan">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="pilih_bulan" id="pilih_bulan" class="form-select">
                                                        <option value="">Pilih Bulan</option>
                                                        <option value="1">Januari</option>
                                                        <option value="2">Februari</option>
                                                        <option value="3">Maret</option>
                                                        <option value="4">April</option>
                                                        <option value="5">Mei</option>
                                                        <option value="6">Juni</option>
                                                        <option value="7">Juli</option>
                                                        <option value="8">Agustus</option>
                                                        <option value="9">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="pilih_dapur" id="pilih_dapur" class="form-select">
                                                        <option value="">Pilih Dapur</option>
                                                        @foreach($dapurList as $dapur)
                                                            <option value="{{ $dapur->nomor_dapur }}">{{ $dapur->nama_dapur }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                        Cari    
                                                    </button>
                                                </div>
                                            </div>
                                    </form>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-success w-100" id="cetak_laporan_keuangan" >
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div style="width: 100%; max-width: 1100px; margin: 0 auto;">
                                        <canvas id="koperasiChartOwner" height="340"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    @php
                                        // Grup data berdasarkan tanggal laporan keuangan
                                        $grouped = $laporan_keuangan->groupBy(function ($item) {
                                            return \Carbon\Carbon::parse($item->tanggal_laporan_keuangan)->translatedFormat('d F Y');
                                        });
                                    @endphp
                                    
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
                                    
                                                <tr style="text-align: center; vertical-align: middle;">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $tanggal }}</td>
                                                    {{-- kolom koperasi --}}
                                                    <td>
                                                        {{ ($ada_koperasi && !$ada_supplier) ? '✅' : '' }}
                                                    </td>
                                                                                        
                                                    <td>
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
                                    
                                    {{-- Pagination --}}
                                    <div class="mt-3">
                                        {{ $laporan_keuangan->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Modal Input Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-inputlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/owner/laporan/keuangan/store_laporan_keuangan" method="POST" id="frmLprnKngn" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_laporan_keuangan" name="tanggal_laporan_keuangan" class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <select name="jenis_laporan_keuangan" id="jenis_laporan_keuangan" class="form-select">
                                    <option value="">Pilih Jenis Transaksi</option>
                                    <option value="Pemasukan">Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-category"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                </span>
                                <input type="text" value="" id="kategori_laporan_keuangan" class="form-control" name="kategori_laporan_keuangan" placeholder="Masukkan Sumber/Tujuan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                        class="icon icon-tabler icon-tabler-note">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M13 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l6 6v8a2 2 0 0 1 -2 2h-3" />
                                        <path d="M13 4v6h6" />
                                    </svg>
                                </span>
                                <textarea id="keterangan_laporan_keuangan" name="keterangan_laporan_keuangan" class="form-control" rows="1" placeholder="Tuliskan keterangan di sini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" /></svg>
                                </span>
                                <input type="number" value="" id="jumlah_dana" class="form-control" name="jumlah_dana" placeholder="Masukkan Jumlah Dana">
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


{{-- Modal Edit Laporan Keuangan --}}
<div class="modal modal-blur fade" id="modal-editlaporankeuangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditformlaporankeuangan">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function(){
        $("#tanggal_laporan_keuangan").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });

        $("#dari_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#sampai_tanggal").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });

        $("#btnTambahLaporanKeuangan").click(function(){
            $("#modal-inputlaporankeuangan").modal("show");
        });

        $(".edit_laporan_keuangan").click(function(){
            var id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/keuangan/edit_laporan_keuangan',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadeditformlaporankeuangan").html(respond);
                }
            });
            $("#modal-editlaporankeuangan").modal("show");
        });

        $(".delete-confirm-laporan-keuangan").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin Data ini Mau Di Hapus?",
                text: "Jika Ya Maka Data Akan Terhapus Permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di Hapus",
                        icon: "success"
                  });
                }
            });
        });

        $("#FormLaporanKeuangan").submit(function(){
            var pilih_bulan = $("#pilih_bulan").val();
            var pilih_dapur = $("#pilih_dapur").val();
            if(pilih_bulan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bulan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#pilih_bulan").focus();
                  });
                return false;
            } else if (pilih_dapur==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#pilih_dapur").focus();
                  });
                return false;
            }
        });


        $("#cetak_laporan_keuangan").click(function(e){
            e.preventDefault(); // Mencegah langsung pindah halaman
                
            var pilih_bulan = $("#pilih_bulan").val();
            var pilih_dapur = $("#pilih_dapur").val();
                
            if(pilih_bulan == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bulan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_bulan").focus();
                });
                return false;
            
            } else if(pilih_dapur == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Dapur Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(()=> {
                    $("#pilih_dapur").focus();
                });
                return false;
            }
        
            // ✅ JIKA SUDAH LENGKAP, BARU BUKA HALAMAN CETAK
            let url = `/owner/laporan/keuangan/cetak_laporan_keuangan?bulan=${pilih_bulan}&dapur=${pilih_dapur}`;
            window.open(url, '_blank');
        });



        flatpickr("#dari_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });

        flatpickr("#sampai_tanggal", {
            dateFormat: "d F Y", // format tampilan: 15 September 2025
            altInput: true,
            altFormat: "d F Y",
            locale: "id" // biar bulan pakai bahasa Indonesia
        });
    });








    // === BAGIAN DIAGRAM BATANG ===
    let koperasiData = @json($data);

    const labels      = koperasiData.map(item => item.tanggal_laporan_keuangan ?? 'Tidak Ada Tanggal');
    const modalMasuk  = koperasiData.map(item => item.total_pemasukan);
    const modalKeluar = koperasiData.map(item => item.total_pengeluaran);
    const margin      = koperasiData.map(item => item.margin);

    const ctxBar = document.getElementById('koperasiChartOwner').getContext('2d');
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
</script>
@endpush