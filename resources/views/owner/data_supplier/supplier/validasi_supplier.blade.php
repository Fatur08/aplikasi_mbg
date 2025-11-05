<form action="/owner/data_supplier/supplier/{{ $data->id_supplier }}/update_validasi_supplier" method="POST" id="frmVldsSpplr" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_supplier }}" id="id_supplier" class="form-control" name="id_supplier" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <select name="status_supplier" id="status_supplier" class="form-select">
                    <option value="1">Disetujui</option>
                    <option value="2">Ditolak</option>
                </select>
              </div>
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