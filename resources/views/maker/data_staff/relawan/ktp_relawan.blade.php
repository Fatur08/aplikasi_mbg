<style>
    .ktp-relawan-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->ktp_relawan))
        <img src="{{ asset('storage/uploads/data_staff/relawan/ktp/'.$data->ktp_relawan) }}" 
             alt="KTP {{ $data->nama_relawan }}" 
             class="ktp-relawan-img">
    @else
        <p class="text-muted text-center">Tidak ada KTP</p>
    @endif
</div>