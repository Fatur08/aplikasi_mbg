<style>
    .ktp-aslap-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->ktp_aslap))
        <img src="{{ asset('storage/uploads/data_induk/aslap/ktp/'.$data->ktp_aslap) }}" 
             alt="KTP {{ $data->nama_aslap }}" 
             class="ktp-aslap-img">
    @else
        <p class="text-muted text-center">Tidak ada KTP</p>
    @endif
</div>