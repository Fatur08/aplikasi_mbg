@php
use Illuminate\Support\Facades\DB;

// Ambil user maker yang sedang login
$maker = Auth::guard('maker')->user();

// Cek foto, tampilkan default jika kosong
$path = $maker->foto_maker
    ? asset('storage/uploads/data_induk/maker/' . $maker->foto_maker)
    : asset('assets/img/nophoto.jpg');

// Cocokkan nomor_dapur_maker dengan nomor_dapur di tabel dapur
$nama_dapur = DB::table('dapur')
    ->where('nomor_dapur', $maker->nomor_dapur_maker)
    ->value('nama_dapur');
@endphp

<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url('{{ $path }}')"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ $maker->nama_maker }}</div>
                        <div class="mt-1 small text-secondary">Maker ({{ $nama_dapur ?? 'Tidak Ada Dapur' }})</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/proseslogoutmaker" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>