<style>
    .bukti_pengiriman_img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->bukti_pengiriman))
        <img src="{{ asset('storage/uploads/bukti_pengiriman/'.$data->bukti_pengiriman) }}" 
             alt="Bukti Pengiriman {{ $data->nama_distributor }}" 
             class="img-fluid bukti_pengiriman_img">
    @else
        <p class="text-muted text-center">Tidak Ada Bukti Pengiriman</p>
    @endif
</div>