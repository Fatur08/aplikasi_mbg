<style>
    .nota-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->nota_informasi_supplier))
        <img src="{{ asset('storage/uploads/data_supplier/informasi_supplier/nota/'.$data->nota_informasi_supplier) }}" 
             alt="Nota {{ $data->nama_informasi_supplier }}" 
             class="nota-img">
    @else
        <p class="text-muted text-center">Tidak Ada Nota</p>
    @endif
</div>