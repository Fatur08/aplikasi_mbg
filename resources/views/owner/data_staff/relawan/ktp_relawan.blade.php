<style>
    .ktp-relawan-img {
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
    @if(!empty($data->ktp_relawan))
        <img src="{{ asset('storage/uploads/data_staff/relawan/ktp/' . $data->ktp_relawan) }}"
            alt="KTP {{ $data->nama_relawan }}" class="ktp-relawan-img">
    @else
        <p class="text-muted text-center">Tidak ada KTP</p>
    @endif
</div>