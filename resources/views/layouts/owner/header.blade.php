<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(../../assets/img/nophoto.jpg)"></span>
                    <div class="ps-2" style="max-width: 250px;">
                        <div class="fw-bold text-truncate">Owner</div>
                        <div class="small text-secondary text-truncate">({{ Auth::guard('owner')->user()->name }})</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="/proseslogoutadmin" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>