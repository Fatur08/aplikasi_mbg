<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/distributor/dashboarddistributor" class="item {{ request()->is('distributor/dashboarddistributor') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="/distributor/riwayat_distributor/index_riwayat_distributor" class="item {{ request()->is('distributor/riwayat_distributor/index_riwayat_distributor') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>Riwayat</strong>
        </div>
    </a>
    <a href="/distributor/pengiriman_distributor/index_pengiriman_distributor" class="item {{ request()->is('distributor/pengiriman_distributor/*') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="car-outline"></ion-icon>
            <strong>Pengiriman</strong>
        </div>
    </a>
    <a href="/distributor/profil_distributor/index_profil_distributor" class="item {{ request()->is('distributor/profil_distributor/index_profil_distributor') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profil</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->