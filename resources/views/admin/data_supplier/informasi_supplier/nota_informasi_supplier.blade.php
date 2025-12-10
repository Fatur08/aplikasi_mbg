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
        @php
            $notaPath = 'storage/uploads/data_supplier/informasi_supplier/nota/' . $data->nota_informasi_supplier;
            $notaUrl  = asset($notaPath) . '?v=' . filemtime(public_path($notaPath));
        @endphp

        <img src="{{ $notaUrl }}" 
             alt="Nota {{ $data->nama_informasi_supplier }}" 
             class="nota-img">
    @else
        <p class="text-muted text-center">Tidak Ada Nota</p>
    @endif
</div>