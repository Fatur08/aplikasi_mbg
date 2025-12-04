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
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    padding: 25px 40px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}
.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
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
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <div class="page-pretitle">
                                    Halaman
                                </div>
                                <h2 class="page-title">
                                    Laporan Harian Dapur
                                </h2>
                            </td>
                            <!--<td style="text-align:right">
                                <a href="#" class="btn btn-primary" id="btnTambahMenuHarian">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Tambah Menu
                                </a>
                            </td>-->
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
                                <form action="/owner/laporan/harian_dapur" method="GET">
                                    <div class="row">
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
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                                </span>
                                                <!-- Yang tampil -->
                                                <input type="text" id="tampilan_tanggal" class="form-control" placeholder="Pilih Tanggal" autocomplete="off">
                                                
                                                <!-- Yang dikirim ke server -->
                                                 <input type="hidden" id="pilih_tanggal" name="pilih_tanggal">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="id_menu_harian" id="id_menu_harian" class="form-select">
                                                    <option value="">Pilih Menu</option>
                                                    @foreach($menu_harian as $menu)
                                                        <option value="{{ $menu->id_menu_harian }}">{{ $menu->nama_menu_harian }}</option>
                                                    @endforeach
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
                        <div class="row mt-2 table-container">
                            <div class="col-12">
                                @php
                                    use Illuminate\Support\Facades\DB;
                                    use Carbon\Carbon;
                                    // ✅ Ambil nama dapur
                                    $namaDapur = $nomor_dapur
                                        ? DB::table('dapur')
                                            ->where('nomor_dapur', $nomor_dapur)
                                            ->value('nama_dapur')
                                        : '-';
                                @endphp
                                <!-- === Section Info Dapur === -->
                                <div class="section-info">
                                    <div class="info-card">
                                        <h4>Nama Dapur : <span style="color:#2563eb;">{{ $namaDapur }}</span></h4>
                                        <p>
                                            Tanggal :
                                            <strong>
                                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <!-- === Table Section === -->
                                <div class="table-wrapper">
                                    <div class="table-responsive">
                                        <table class="table custom-table">
                                            <thead style="text-align: center; vertical-align: middle;">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Menu</th>
                                                    <th>Porsi</th>
                                                    <th>Bahan</th>
                                                    <th>Kendala</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($jadwal_menu_harian as $item)
                                                    <tr class="text-center">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->nama_menu_harian }}</td>
                                                        <td>{{ $item->jumlah_porsi_menu_harian }}</td>
                                                        <td style="text-align: center; width:25%"> 
                                                            <div class="align-items-center">
                                                                <a href="#" class="lihat_bahan_terpakai btn btn-info btn-sm" data-id="{{ $item->id_jadwal_menu_harian }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                    </svg>
                                                                    <span>Lihat</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center; width:25%"> 
                                                            <div class="align-items-center">
                                                                <a href="#" class="lihat_kendala btn btn-info btn-sm" data-id="{{ $item->id_jadwal_menu_harian }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6
                                                                                 c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                    </svg>
                                                                    <span>Lihat</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($item->status_jadwal_menu_harian == 1)
                                                                <span class="badge bg-success">Selesai</span>
                                                            @else
                                                                <span class="badge bg-warning">Belum</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-danger">
                                                            Tidak ada data laporan pada tanggal ini
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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


{{-- Modal Input Menu Harian --}}
<div class="modal modal-blur fade" id="modal-inputmenuharian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kepala_dapur/menu_harian/store_menu_harian" method="POST" id="frmMnHrn" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                </span>
                                <input type="text" value="" id="tanggal_jadwal_menu_harian" name="tanggal_jadwal_menu_harian" class="form-control" placeholder="Masukkan Tanggal (Hari ini / Besok)" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bowl-chopsticks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1z" /><path d="M19 7l-14 1" /><path d="M19 2l-14 3" /></svg>                                </span>
                                <input type="text" value="" id="nama_menu_harian" class="form-control" name="nama_menu_harian" placeholder="Masukkan Nama Menu (Jika Belum Ada)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <select name="id_menu_harian" id="id_menu_harian" class="form-select">
                                <option value="">Pilih Menu (Yang Sudah Pernah Dibuat)</option>
                                @foreach($menu_harian as $menu)
                                    <option value="{{ $menu->id_menu_harian }}">{{ $menu->nama_menu_harian }}</option>
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
                                <input type="number" value="" id="jumlah_porsi_menu_harian" class="form-control" name="jumlah_porsi_menu_harian" placeholder="Masukkan Jumlah Porsi">
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


{{-- Modal Tambah Bahan Terpakai --}}
<div class="modal modal-blur fade" id="modal-tambahbahanterpakai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bahan Terpakai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahbahanterpakai">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Lihat Bahan Terpakai --}}
<div class="modal modal-blur fade" id="modal-lihatbahanterpakai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Bahan Terpakai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatbahanterpakai">
                
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Kendala --}}
<div class="modal modal-blur fade" id="modal-tambahkendala" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kendala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformtambahkendala">
                
            </div>
        </div>
    </div>
</div>


{{-- Modal Lihat Kendala --}}
<div class="modal modal-blur fade" id="modal-lihatkendala" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lihat Kendala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadformlihatkendala">
                
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    flatpickr("#tampilan_tanggal", {
        dateFormat: "d F Y", // format tampilan: 15 September 2025
        altInput: true,
        altFormat: "d F Y",
        locale: "id", // biar bulan pakai bahasa Indonesia

        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                let date = selectedDates[0];

                let yyyy = date.getFullYear();
                let mm = String(date.getMonth() + 1).padStart(2, '0');
                let dd = String(date.getDate()).padStart(2, '0');

                // ini yang DIKIRIM ke controller
                document.getElementById('pilih_tanggal').value = `${yyyy}-${mm}-${dd}`;
            }
        }
    });




    $(function(){
        $("#tanggal_jadwal_menu_harian").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:'yyyy-mm-dd'
        });


        $("#btnTambahMenuHarian").click(function(){
            $("#modal-inputmenuharian").modal("show");
        });

        $(".lihat_bahan_terpakai").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/harian_dapur/lihat_bahan_terpakai',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatbahanterpakai").html(respond);
                }
            });
            $("#modal-lihatbahanterpakai").modal("show");
        });

        $(".lihat_kendala").click(function(){
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url:'/owner/laporan/harian_dapur/lihat_kendala',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadformlihatkendala").html(respond);
                }
            });
            $("#modal-lihatkendala").modal("show");
        });

        $(".delete-confirm-menuharian").click(function(e){
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

        $("#frmMnHrn").submit(function(){
            var tanggal_jadwal_menu_harian = $("#tanggal_jadwal_menu_harian").val();
            var jumlah_porsi_menu_harian = $("#jumlah_porsi_menu_harian").val();
            if(tanggal_jadwal_menu_harian==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#tanggal_jadwal_menu_harian").focus();
                  });
                return false;
            } else if (jumlah_porsi_menu_harian==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jumlah Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#jumlah_porsi_menu_harian").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush