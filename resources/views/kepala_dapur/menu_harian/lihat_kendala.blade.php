<div>
    @if(!empty($kendala->kendala_jadwal_menu_harian))
        <p class="text-muted text-center">{{ $kendala->kendala_jadwal_menu_harian }}</p>
    @else
        <p class="text-muted text-center">Tidak Ada Kendala</p>
    @endif
</div>