<style>
    .bukti-terima-img {
        width: 100%;
        /* isi penuh container */
        max-width: 100%;
        /* jangan lebih dari modal */
        height: auto;
        /* jaga rasio */
        max-height: 80vh;
        /* biar tidak keluar layar HP */
        object-fit: contain;
        display: block;
        margin: auto;
    }
</style>

<div>
    @if(!empty($data->bukti_terima_data_koperasi))
        <img src="{{ asset('storage/uploads/data_koperasi/bukti_terima/' . $data->bukti_terima_data_koperasi) }}"
            class="bukti-terima-img">
    @else
        <p class="text-muted text-center">Tidak ada Bukti Terima</p>
    @endif
</div>