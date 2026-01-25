<style>
    .bukti-terima-img {
        max-width: 500px;
        max-height: 450px;
        object-fit: contain;
        display: block;
        margin: auto;
    }
</style>

<div>
    @if($bukti && $sumber === 'koperasi')
        <img
            src="{{ asset('storage/uploads/data_koperasi/bukti_terima/'.$bukti) }}"
            class="bukti-terima-img"
            alt="Bukti Koperasi"
        >

    @elseif($bukti && $sumber === 'supplier')
        <img
            src="{{ asset('storage/uploads/data_supplier/informasi_supplier/bukti_terima/'.$bukti) }}"
            class="bukti-terima-img"
            alt="Bukti Supplier"
        >

    @else
        <p class="text-muted text-center">
            Tidak ada Bukti Terima
        </p>
    @endif
</div>