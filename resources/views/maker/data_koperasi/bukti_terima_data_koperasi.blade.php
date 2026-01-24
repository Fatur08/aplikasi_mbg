<style>
    .bukti-terima-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->bukti_terima_data_koperasi))
        <img src="{{ asset('storage/uploads/data_koperasi/bukti_terima/'.$data->bukti_terima_data_koperasi) }}"
             class="bukti-terima-img">
    @else
        <p class="text-muted text-center">Tidak ada Bukti Terima</p>
    @endif
</div>