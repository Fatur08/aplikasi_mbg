<div>
    @if(!empty($data->kendala_distribusi))
        <p class="text-muted text-center">{{ $data->kendala_distribusi }}</p>
    @else
        <p class="text-muted text-center">Tidak Ada Bukti Pengiriman</p>
    @endif
</div>