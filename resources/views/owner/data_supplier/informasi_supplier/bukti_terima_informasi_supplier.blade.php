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
    @if(!empty($data->bukti_terima_informasi_supplier))
        <img src="{{ asset('storage/uploads/data_supplier/informasi_supplier/bukti_terima/'.$data->bukti_terima_informasi_supplier) }}" 
             alt="Bukti Terima {{ $data->nama_informasi_supplier }}" 
             class="bukti-terima-img">
    @else
        <p class="text-muted text-center">Tidak ada Bukti Terima</p>
    @endif
</div>