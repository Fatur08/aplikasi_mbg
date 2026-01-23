@extends('layouts.distributor.layout')
@section('content')
@php
    use Carbon\Carbon;
    use App\Models\Dapur;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;

    $nomorDapur = Auth::guard('distributor')->user()->nomor_dapur_distributor;
    $namaDapur = \App\Models\Dapur::where('nomor_dapur', $nomorDapur)
        ->distinct('nama_dapur')
        ->pluck('nama_dapur')
        ->first();
    $path = Storage::url('uploads/data_induk/distributor/'.Auth::guard('distributor')->user()->foto_distributor);

    Carbon::setLocale('id');
    $tanggalSekarang = Carbon::now()->translatedFormat('l, d F Y');


    // BAGIAN TOTAL DISTRIBUSI
    // Ambil data user yang sedang login (kepala dapur)
    $distributor = Auth::guard('distributor')->user();

    // Ambil nomor dapur terkait
    $nomor_dapur = $distributor->nomor_dapur_distributor;

    // Tentukan tanggal hari ini (format sesuai database, biasanya YYYY-MM-DD)
    $tanggal_hari_ini = Carbon::now()->toDateString();

    // Hitung total distribusi hari ini
    $total_distribusi = DB::table('distribusi')
        ->where(function ($query) use ($nomor_dapur) {
            $query->where('nomor_dapur_distribusi', $nomor_dapur);
        })
        ->whereDate('tanggal_distribusi', $tanggal_hari_ini)
        ->count();
@endphp
<style>
.logout {
    position: absolute;
    right: 12px;
    top: 16px;                /* atur posisi vertikal (sesuaikan jika perlu) */
    display: flex;            /* buat konten sejajar */
    align-items: center;      /* sejajarkan vertikal icon & teks */
    gap: 6px;                 /* jarak antara icon & teks */
    color: white;
    font-size: 16px;
    text-decoration: none;
    transition: color 0.2s ease, opacity 0.2s ease;
    line-height: 1;           /* pastikan tinggi baris rapih */
}

/* Atur ukuran icon Ionicons agar sejajar dan tidak membesar */
.logout ion-icon {
    font-size: 18px;          /* sesuaikan ukuran icon */
    display: inline-flex;
    vertical-align: middle;
    line-height: 1;
}

/* Kalau mau teks sedikit lebih kecil daripada sekarang, ubah font-size di .logout */
.logout span {
    display: inline-block;
    transform: translateY(0); /* membantu stabilitas vertikal pada beberapa browser */
}

/* Hover effect */
.logout:hover {
    color: #d9534f;
}
</style>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section" id="user-section">
        <a href="/proseslogoutdistributor" class="logout">
            <span>Logout</span>
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                @if(Auth::guard('distributor')->user()->foto_distributor)
                    <!--php artisan storage:link -> Jalankan Apabila Gambar Tidak Muncul-->
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64">
                    <!--<img src="assets/img/sample/avatar/12345.png" alt="avatar" class="imaged w64 rounded">-->
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h2 id="user-name">{{ Auth::guard('distributor')->user()->nama_distributor }}</h2>
                <span id="user-role">{{ $namaDapur ?? 'Nama Dapur Tidak Ditemukan' }}</span>
            </div>
        </div>
    </div>

    <div class="section mt-4" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="cube"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center d-block">Total Distribusi Hari Ini</span>
                            <strong style="font-size: 22px; display: block; margin-top: 5px;">
                                {{ $total_distribusi }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-4" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body text-center">
                            <div class="list-menu">
                                <div class="item-menu text-center">
                                    <div class="menu-icon">
                                        <a href="/distributor/izin" class="orange" style="font-size: 40px;">
                                            <ion-icon name="checkmark-circle"></ion-icon>
                                        </a>
                                    </div>
                                    <div class="menu-name">
                                        <span class="text-center">Total Terkirim</span>
                                        <strong style="font-size: 22px; display: block; margin-top: 5px;">
                                            {{ $totalTerkirim }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body text-center">
                            <div class="list-menu">
                                <div class="item-menu text-center">
                                    <div class="menu-icon">
                                        <a href="/distributor/histori" class="warning" style="font-size: 40px;">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </a>
                                    </div>
                                    <div class="menu-name">
                                        <span class="text-center">Total Belum Terkirim</span>
                                        <strong style="font-size: 22px; display: block; margin-top: 5px;">
                                            {{ $totalBelumTerkirim }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="presencetab">
            <h3>
                Daftar Distribusi Hari Ini <br>
                {{ $tanggalSekarang }}
            </h3>
            @foreach ($distribusi as $data)
                <div class="distribution-card">
                    <div class="row">
                        <div class="col">
                            <div class="section-sekolah">
                                Sekolah
                                <div class="value">{{ $data->tujuan_distribusi ?? '-' }}</div>
                            </div>
                            <div class="section-menu">
                                Menu
                                <div class="value">{{ $data->menu_makanan ?? '-' }}</div>
                            </div>
                            <div class="section-porsi">
                                Porsi
                                <div class="value">{{ $data->jumlah_paket ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-auto status-container text-end">
                            @php
                                switch ($data->status_distribusi) {
                                    case 1:
                                        $badge = 'bg-success';
                                        $status = 'Terkirim';
                                        break;
                                    case 0:
                                        $badge = 'bg-warning';
                                        $status = 'Dalam Perjalanan';
                                        break;
                                    default:
                                        $badge = 'bg-danger';
                                        $status = 'Belum Diterima';
                                        break;
                                }
                            @endphp
                            
                            <span class="badge {{ $badge }}">{{ $status }}</span><br>
                            
                            @if(!empty($data->bukti_pengiriman))
                                <a href="#"
                                   class="bukti_pengiriman btn btn-sm btn-outline-primary"
                                   id="{{ $data->id_distribusi }}"
                                   target="_blank">
                                   Lihat Bukti
                                </a>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    Belum Ada Bukti
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
                            
            {{-- Jika tidak ada data --}}
            @if($distribusi->isEmpty())
                <div class="alert alert-info text-center mt-3">
                    Belum ada data distribusi hari ini.
                </div>
            @endif
        </div>
    </div>
</div>


{{-- Modal Bukti Terima --}}
<div class="modal modal-blur fade" id="modal-buktipengiriman" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 px-3 align-items-center">
                <h6 class="modal-title mb-0">Bukti Pengiriman</h6>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
            <div class="modal-body" id="loadbuktipengiriman">
                
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->
@endsection
@push('myscript')
<script>
    $(function(){
        $(".bukti_pengiriman").click(function(e){
            e.preventDefault(); // supaya tidak reload atau ke target _blank
            var id = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'/distributor/pengiriman_distributor/lihat_bukti_pengiriman',
                cache:false,
                data:{
                    _token : "{{ csrf_token() }}",
                    id : id
                },
                success:function(respond){
                    $("#loadbuktipengiriman").html(respond);

                    // baru tampilkan modal setelah isi sudah dimasukkan
                    $("#modal-buktipengiriman").modal("show");
                },
                error: function(xhr, status, error) {
                    console.error("Gagal memuat bukti:", error);
                }
            });
        });
    });
</script>
@endpush