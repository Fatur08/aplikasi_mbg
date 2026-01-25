<style>
    .bukti-barang_supplier-img {
        max-width: 500px;   /* atur ukuran maksimal */
        max-height: 450px;  /* biar tidak terlalu besar */
        object-fit: contain;
        display: block;
        margin: auto;       /* center */
    }
</style>

<div>
    @if(!empty($data->bukti_barang_supplier))
        <img src="{{ asset('storage/uploads/data_supplier/informasi_supplier/bukti_terima/' . $item->bukti_barang_supplier) }}"
             class="bukti-barang_supplier-img">
    @else
        <p class="text-muted text-center">Tidak ada Bukti Terima</p>
    @endif
</div>