<form
    action="/maker/operasional/laporan_operasional/{{ $data->id_laporan_operasional }}/update_maker_laporan_operasional"
    method="POST" id="FormEditInformasiOperasional" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Tanggal Operasional</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-4">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="text"
                    value="{{ \Carbon\Carbon::parse($data->tanggal_laporan_operasional)->format('d-m-Y') }}"
                    id="edit_tanggal_laporan_operasional" name="edit_tanggal_laporan_operasional" class="form-control"
                    placeholder="Sampai Tanggal" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Jenis Operasional</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-4">
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

                <select id="edit_id_informasi_operasional" name="edit_id_informasi_operasional" class="form-control">

                    <option value="">-- Pilih Jenis Operasional --</option>

                    @foreach ($jenisOperasional as $item)
                        <option value="{{ $item->id_informasi_operasional }}" {{ $item->id_informasi_operasional == $data->id_informasi_operasional ? 'selected' : '' }}>
                            {{ $item->jenis_informasi_operasional }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Jumlah (pcs)</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-4">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-calculator">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -14" />
                        <path d="M8 8a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1l0 -1" />
                        <path d="M8 14l0 .01" />
                        <path d="M12 14l0 .01" />
                        <path d="M16 14l0 .01" />
                        <path d="M8 17l0 .01" />
                        <path d="M12 17l0 .01" />
                        <path d="M16 17l0 .01" />
                    </svg>
                </span>
                <input type="number" value="{{ $data->jumlah_laporan_operasional }}"
                    id="edit_jumlah_laporan_operasional" class="form-control" name="edit_jumlah_laporan_operasional"
                    placeholder="Masukkan Jumlah Jenis">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Harga Yang Dibeli (Beli)</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-4">
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
                <input type="number" value="{{ $data->beli_laporan_operasional }}" id="edit_beli_laporan_operasional"
                    class="form-control" name="edit_beli_laporan_operasional" placeholder="Masukkan Harga Yang Dibeli">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="text-start" style="font-size:12pt;">Harga Diajukan (Jual)</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-4">
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
                <input type="number" value="{{ $data->jual_laporan_operasional }}" id="edit_jual_laporan_operasional"
                    class="form-control" name="edit_jual_laporan_operasional" placeholder="Masukkan Harga Diajukan">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <input type="file" id="edit_nota_laporan_operasional" name="edit_nota_laporan_operasional"
                class="form-control">
        </div>
        <div class="col-6 mt-2">
            <h5 class="text-start" style="font-size:12pt;">Foto Nota</h5>
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