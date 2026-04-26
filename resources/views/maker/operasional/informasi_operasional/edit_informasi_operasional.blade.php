<form
    action="/maker/operasional/informasi_operasional/{{ $data->id_informasi_operasional }}/update_informasi_operasional"
    method="POST" id="FormEditInformasiOperasional" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Jenis Operasional</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 21l18 0" />
                        <path d="M9 8l1 0" />
                        <path d="M9 12l1 0" />
                        <path d="M9 16l1 0" />
                        <path d="M14 8l1 0" />
                        <path d="M14 12l1 0" />
                        <path d="M14 16l1 0" />
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                    </svg>
                </span>
                <input type="text" value="" id="jenis_informasi_operasional" class="form-control"
                    name="jenis_informasi_operasional" placeholder="Masukkan Jenis Operasional">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Jumlah Jenis</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M12.483 20.935c-.862 .239 -1.898 -.178 -2.158 -1.252a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.08 .262 1.496 1.308 1.247 2.173" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                    </svg>
                </span>
                <input type="number" value="" id="jumlah_jenis_informasi_operasional" class="form-control"
                    name="jumlah_jenis_informasi_operasional" placeholder="Masukkan Jumlah Jenis">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Harga Satuan</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings-dollar">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M13.038 20.666c-.902 .665 -2.393 .337 -2.713 -.983a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 .402 2.248" />
                        <path d="M15 12a3 3 0 1 0 -1.724 2.716" />
                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                        <path d="M19 21v1m0 -8v1" />
                    </svg>
                </span>
                <input type="number" value="" id="harga_satuan_informasi_operasional" class="form-control"
                    name="harga_satuan_informasi_operasional" placeholder="Masukkan Harga Satuan">
            </div>
        </div>
    </div>
    <div class="row" id="wrapper_harga_informasi_operasional" style="display:none;">

        <!-- Judul ikut disembunyikan -->
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Harga Operasional</h5>
        </div>

        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">💰</span>

                <!-- Untuk ditampilkan ke user -->
                <input type="text" id="harga_informasi_operasional_view" class="form-control mb-2"
                    placeholder="Total Harga Operasional (Rp)" readonly>

                <!-- Untuk dikirim ke server -->
                <input type="hidden" id="harga_informasi_operasional" name="harga_informasi_operasional">
            </div>
        </div>

    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 14l11 -11" />
                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</form>