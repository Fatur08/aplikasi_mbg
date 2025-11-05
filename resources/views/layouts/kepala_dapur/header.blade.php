@php
use Illuminate\Support\Facades\DB;

$kepala_dapur = Auth::guard('kepala_dapur')->user();

// Cek foto, tampilkan default jika kosong
$path = $kepala_dapur->foto
    ? asset('storage/uploads/data_induk/kepala_dapur/' . $kepala_dapur->foto)
    : asset('assets/img/nophoto.jpg');

// Ambil nama dapur
$nama_dapur = DB::table('dapur')
    ->where('nomor_dapur', $kepala_dapur->nomor_dapur_kepala_dapur)
    ->value('nama_dapur');
@endphp

<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url('{{ $path }}')"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ $kepala_dapur->nama_lengkap }}</div>
                        <div class="mt-1 small text-secondary">
                            Kepala Dapur ({{ $nama_dapur ?? 'Tidak Ada Dapur' }})
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/proseslogoutkepaladapur" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>