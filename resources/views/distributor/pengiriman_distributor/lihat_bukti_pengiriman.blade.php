<style>
    .bukti-pengiriman-img {
        max-width: 250px;   /* atur ukuran maksimal */
        max-height: 600px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->bukti_pengiriman))
        <img src="{{ asset('storage/uploads/bukti_pengiriman/'.$data->bukti_pengiriman) }}" 
             alt="Bukti Terima {{ $data->bukti_pengiriman }}" 
             class="bukti-pengiriman-img">
    @else
        <p class="text-muted text-center">Tidak ada Bukti Pengiriman</p>
    @endif
</div>