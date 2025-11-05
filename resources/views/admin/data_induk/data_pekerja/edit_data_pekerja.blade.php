<form action="/admin/data_induk/data_pekerja/{{ $data->id_data_pekerja }}/update_data_pekerja" method="POST" id="frmDataPekerja" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_data_pekerja }}" id="id_data_pekerja" class="form-control" name="id_data_pekerja" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </span>
                <input type="text" value="{{ $data->nama_data_pekerja }}" id="nama_data_pekerja" class="form-control" name="nama_data_pekerja" placeholder="Masukkan Nama Data Pekerja">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                </span>
                <input type="text" value="" id="peran_data_pekerja" class="form-control" name="peran_data_pekerja" placeholder="Masukkan Peran Data Pekerja (Jika Peran Baru)">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <select name="old_peran_data_pekerja" id="old_peran_data_pekerja" class="form-select">
                <option value="">Pilih Peran (Jika Sebelumnya Ada)</option>
                @foreach($peranList as $peran)
                    <option value="{{ $peran->peran_data_pekerja }}">
                        {{ $peran->peran_data_pekerja }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                </span>
                <input type="text" value="{{ $data->no_hp_data_pekerja }}" id="no_hp_data_pekerja" class="form-control" name="no_hp_data_pekerja" placeholder="Masukkan No. HP Data Pekerja">
              </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <select name="nomor_dapur_data_pekerja" id="nomor_dapur_data_pekerja" class="form-select">
                <option value="">Pilih Dapur</option>
                @foreach($dapurList as $dapur)
                    <option value="{{ $dapur->nomor_dapur }}"
                        {{ $dapur->nomor_dapur == optional($data)->nomor_dapur_data_pekerja ? 'selected' : '' }}>
                        {{ $dapur->nama_dapur }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-6">
            <input type="file" id="foto_data_pekerja" name="foto_data_pekerja" class="form-control">
            <input type="hidden" name="old_foto_data_pekerja" value="{{ $data->foto_data_pekerja }}">
        </div>
        <div class="col-6 mt-2">
            <label>Masukkan Foto Pengenal</label>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-6">
            <input type="file" id="ktp_data_pekerja" name="ktp_data_pekerja" class="form-control">
            <input type="hidden" name="old_ktp_data_pekerja" value="{{ $data->ktp_data_pekerja }}">
        </div>
        <div class="col-6 mt-2">
            <label>Foto KTP Data Pekerja</label>
        </div>
    </div>
    <div class="row mt-2">
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