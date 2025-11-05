@php
use Illuminate\Support\Facades\DB;

// Ambil user admin yang sedang login
$admin = Auth::guard('admin')->user();

// Cek foto, tampilkan default jika kosong
$path = $admin->foto_admin
    ? asset('storage/uploads/data_induk/admin/' . $admin->foto_admin)
    : asset('assets/img/nophoto.jpg');

// Cocokkan nomor_dapur_admin dengan nomor_dapur di tabel dapur
$nama_dapur = DB::table('dapur')
    ->where('nomor_dapur', $admin->nomor_dapur_admin)
    ->value('nama_dapur');
@endphp

<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url('{{ $path }}')"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ $admin->nama_admin }}</div>
                        <div class="mt-1 small text-secondary">Admin ({{ $nama_dapur ?? 'Tidak Ada Dapur' }})</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/proseslogoutadmin" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>