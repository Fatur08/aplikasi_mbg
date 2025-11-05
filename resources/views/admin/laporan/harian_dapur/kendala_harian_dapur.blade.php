<div>
    @if(!empty($kendala->keterangan_stok) || !empty($kendala->kendala_distribusi))
        <p class="text-muted text-center">
            {{ $kendala->keterangan_stok }} 
            @if(!empty($kendala->keterangan_stok) && !empty($kendala->kendala_distribusi))
                -
            @endif
            {{ $kendala->kendala_distribusi }}
        </p>
    @else
        <p class="text-muted text-center">Tidak Ada Kendala</p>
    @endif
</div>