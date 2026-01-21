<form action="/owner/data_induk/admin/{{ $data->id_admin }}/update_admin" method="POST" id="frmAdmin" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_admin }}" id="id_admin" class="form-control" name="id_admin" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </span>
                <input type="text" value="{{ $data->nama_admin}}" id="nama_admin" class="form-control" name="nama_admin" placeholder="Nama Lengkap">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                </span>
                <input type="text" value="{{ $data->email_admin}}" id="email_admin" class="form-control" name="email_admin" placeholder="E-Mail">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-analytics"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" /><path d="M7 20l10 0" /><path d="M9 16l0 4" /><path d="M15 16l0 4" /><path d="M8 12l3 -3l2 2l3 -3" /></svg>
                </span>
                <input type="text" value="{{ $data->alamat_admin }}" id="alamat_admin" class="form-control" name="alamat_admin" placeholder="Alamat">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                </span>
                <input type="text" value="{{ $data->no_hp_admin }}" id="no_hp_admin" class="form-control" name="no_hp_admin" placeholder="No. HP">
              </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <input type="file" name="foto_admin" class="form-control">
            <input type="hidden" name="old_foto_admin" value="{{ $data->foto_admin }}">
        </div>
        <div class="col-6 mt-2">
            <label>Masukkan Foto Pengenal</label>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <select name="kecamatan_admin" id="kecamatan_admin" class="form-select">
                <option value="">Kecamatan</option>
                <option value="Bandar Sribhawono" {{ $data->kecamatan_admin == 'Bandar Sribhawono' ? 'selected' : '' }}>Bandar Sribhawono</option>
                <option value="Batanghari" {{ $data->kecamatan_admin == 'Batanghari' ? 'selected' : '' }}>Batanghari</option>
                <option value="Batanghari Nuban" {{ $data->kecamatan_admin == 'Batanghari Nuban' ? 'selected' : '' }}>Batanghari Nuban</option>
                <option value="Braja Selebah" {{ $data->kecamatan_admin == 'Braja Selebah' ? 'selected' : '' }}>Braja Selebah</option>
                <option value="Bumi Agung" {{ $data->kecamatan_admin == 'Bumi Agung' ? 'selected' : '' }}>Bumi Agung</option>
                <option value="Gunung Pelindung" {{ $data->kecamatan_admin == 'Gunung Pelindung' ? 'selected' : '' }}>Gunung Pelindung</option>
                <option value="Jabung" {{ $data->kecamatan_admin == 'Jabung' ? 'selected' : '' }}>Jabung</option>
                <option value="Labuhan Maringgai" {{ $data->kecamatan_admin == 'Labuhan Maringgai' ? 'selected' : '' }}>Labuhan Maringgai</option>
                <option value="Labuhan Ratu" {{ $data->kecamatan_admin == 'Labuhan Ratu' ? 'selected' : '' }}>Labuhan Ratu</option>
                <option value="Marga Sekampung" {{ $data->kecamatan_admin == 'Marga Sekampung' ? 'selected' : '' }}>Marga Sekampung</option>
                <option value="Marga Tiga" {{ $data->kecamatan_admin == 'Marga Tiga' ? 'selected' : '' }}>Marga Tiga</option>
                <option value="Mataram Baru" {{ $data->kecamatan_admin == 'Mataram Baru' ? 'selected' : '' }}>Mataram Baru</option>
                <option value="Melinting" {{ $data->kecamatan_admin == 'Melinting' ? 'selected' : '' }}>Melinting</option>
                <option value="Metro Kibang" {{ $data->kecamatan_admin == 'Metro Kibang' ? 'selected' : '' }}>Metro Kibang</option>
                <option value="Pasir Sakti" {{ $data->kecamatan_admin == 'Pasir Sakti' ? 'selected' : '' }}>Pasir Sakti</option>
                <option value="Pekalongan" {{ $data->kecamatan_admin == 'Pekalongan' ? 'selected' : '' }}>Pekalongan</option>
                <option value="Purbolinggo" {{ $data->kecamatan_admin == 'Purbolinggo' ? 'selected' : '' }}>Purbolinggo</option>
                <option value="Raman Utara" {{ $data->kecamatan_admin == 'Raman Utara' ? 'selected' : '' }}>Raman Utara</option>
                <option value="Sekampung" {{ $data->kecamatan_admin == 'Sekampung' ? 'selected' : '' }}>Sekampung</option>
                <option value="Sekampung Udik" {{ $data->kecamatan_admin == 'Sekampung Udik' ? 'selected' : '' }}>Sekampung Udik</option>
                <option value="Sukadana" {{ $data->kecamatan_admin == 'Sukadana' ? 'selected' : '' }}>Sukadana</option>
                <option value="Waway Karya" {{ $data->kecamatan_admin == 'Waway Karya' ? 'selected' : '' }}>Waway Karya</option>
                <option value="Way Bungur" {{ $data->kecamatan_admin == 'Way Bungur' ? 'selected' : '' }}>Way Bungur</option>
                <option value="Way Jepara" {{ $data->kecamatan_admin == 'Way Jepara' ? 'selected' : '' }}>Way Jepara</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                </span>
                <input type="text" value="{{ $data->password_admin }}" id="password_admin" class="form-control" name="password_admin" placeholder="Password">
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