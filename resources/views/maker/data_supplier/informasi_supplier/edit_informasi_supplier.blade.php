<form action="/admin/data_supplier/informasi_supplier/{{ $data->id_informasi_supplier }}/update_informasi_supplier" method="POST" id="frmdata" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_informasi_supplier }}" id="id" class="form-control" name="id" placeholder="id" hidden>
    <input type="text" value="{{ $data->nama_informasi_supplier}}" id="nama_informasi_supplier" class="form-control" name="nama_informasi_supplier" placeholder="Nama Supplier" hidden>
    <div class="row mb-3">
        <div class="col-6">
            <input type="hidden" name="old_nota_informasi_supplier" value="{{ $data->nota_informasi_supplier }}">
            <input type="file" id="nota_informasi_supplier" name="nota_informasi_supplier" class="form-control">
        </div>
        <div class="col-6 mt-2">
            <label>Masukkan Nota</label>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-6">
            <input type="hidden" name="old_bukti_terima_informasi_supplier" value="{{ $data->bukti_terima_informasi_supplier }}">
            <input type="file" id="bukti_terima_informasi_supplier" name="bukti_terima_informasi_supplier" class="form-control">
        </div>
        <div class="col-6 mt-2">
            <label>Masukkan Bukti Terima</label>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</form>