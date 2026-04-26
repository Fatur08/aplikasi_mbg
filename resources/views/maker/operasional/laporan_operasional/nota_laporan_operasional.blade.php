<style>
    .nota-laporan-operasional-img {
        max-width: 500px;
        /* atur ukuran maksimal */
        max-height: 450px;
        /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;
        /* center */
    }
</style>

<div>
    @if(!empty($data->nota_laporan_operasional))
        <img src="{{ asset('storage/uploads/maker/operasional/laporan_operasional/' . $data->nota_laporan_operasional) }}"
            alt="{{ $data->nota_laporan_operasional }}" class="nota-laporan-operasional-img">
    @else
        <p class="text-muted text-center">Tidak ada Nota</p>
    @endif
</div>