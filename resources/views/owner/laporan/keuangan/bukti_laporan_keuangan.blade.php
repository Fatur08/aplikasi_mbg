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
    @if($bukti && $sumber === 'koperasi')
        <img src="{{ asset('storage/uploads/data_koperasi/bukti_terima/' . $bukti) }}" class="bukti-terima-img"
            alt="Bukti Koperasi">

    @elseif($bukti && $sumber === 'supplier')
        <img src="{{ asset('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $bukti) }}"
            class="bukti-terima-img" alt="Bukti Supplier">

    @else
        <p class="text-muted text-center">
            Tidak ada Bukti Terima
        </p>
    @endif
</div>