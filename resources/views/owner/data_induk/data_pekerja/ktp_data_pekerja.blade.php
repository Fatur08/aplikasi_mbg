<style>
    .ktp-data_pekerja-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->ktp_data_pekerja))
        <img src="{{ asset('storage/uploads/data_induk/data_pekerja/ktp/'.$data->ktp_data_pekerja) }}" 
             alt="KTP {{ $data->nama_data_pekerja }}" 
             class="ktp-data_pekerja-img">
    @else
        <p class="text-muted text-center">Tidak ada KTP</p>
    @endif
</div>