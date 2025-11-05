<form action="/admin/data_induk/kepala_dapur/{{ $data->id }}/update_kepala_dapur" method="POST" id="frmdata" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id }}" id="id" class="form-control" name="id" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </span>
                <input type="text" value="{{ $data->nama_lengkap}}" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
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
                <input type="text" value="{{ $data->email}}" id="email" class="form-control" name="email" placeholder="E-Mail">
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
                <input type="text" value="{{ $data->alamat }}" id="alamat" class="form-control" name="alamat" placeholder="Alamat">
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
                <input type="text" value="{{ $data->no_hp }}" id="no_hp" class="form-control" name="no_hp" placeholder="No. HP">
              </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <input type="file" name="foto" class="form-control">
            <input type="hidden" name="old_foto" value="{{ $data->foto }}">
        </div>
        <div class="col-6 mt-2">
            <label>Masukkan Foto Pengenal</label>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <select name="kecamatan" id="kecamatan" class="form-select">
                <option value="">Kecamatan</option>
                <option value="Bandar Sribhawono" {{ $data->kecamatan == 'Bandar Sribhawono' ? 'selected' : '' }}>Bandar Sribhawono</option>
                <option value="Batanghari" {{ $data->kecamatan == 'Batanghari' ? 'selected' : '' }}>Batanghari</option>
                <option value="Batanghari Nuban" {{ $data->kecamatan == 'Batanghari Nuban' ? 'selected' : '' }}>Batanghari Nuban</option>
                <option value="Braja Selebah" {{ $data->kecamatan == 'Braja Selebah' ? 'selected' : '' }}>Braja Selebah</option>
                <option value="Bumi Agung" {{ $data->kecamatan == 'Bumi Agung' ? 'selected' : '' }}>Bumi Agung</option>
                <option value="Gunung Pelindung" {{ $data->kecamatan == 'Gunung Pelindung' ? 'selected' : '' }}>Gunung Pelindung</option>
                <option value="Jabung" {{ $data->kecamatan == 'Jabung' ? 'selected' : '' }}>Jabung</option>
                <option value="Labuhan Maringgai" {{ $data->kecamatan == 'Labuhan Maringgai' ? 'selected' : '' }}>Labuhan Maringgai</option>
                <option value="Labuhan Ratu" {{ $data->kecamatan == 'Labuhan Ratu' ? 'selected' : '' }}>Labuhan Ratu</option>
                <option value="Marga Sekampung" {{ $data->kecamatan == 'Marga Sekampung' ? 'selected' : '' }}>Marga Sekampung</option>
                <option value="Marga Tiga" {{ $data->kecamatan == 'Marga Tiga' ? 'selected' : '' }}>Marga Tiga</option>
                <option value="Mataram Baru" {{ $data->kecamatan == 'Mataram Baru' ? 'selected' : '' }}>Mataram Baru</option>
                <option value="Melinting" {{ $data->kecamatan == 'Melinting' ? 'selected' : '' }}>Melinting</option>
                <option value="Metro Kibang" {{ $data->kecamatan == 'Metro Kibang' ? 'selected' : '' }}>Metro Kibang</option>
                <option value="Pasir Sakti" {{ $data->kecamatan == 'Pasir Sakti' ? 'selected' : '' }}>Pasir Sakti</option>
                <option value="Pekalongan" {{ $data->kecamatan == 'Pekalongan' ? 'selected' : '' }}>Pekalongan</option>
                <option value="Purbolinggo" {{ $data->kecamatan == 'Purbolinggo' ? 'selected' : '' }}>Purbolinggo</option>
                <option value="Raman Utara" {{ $data->kecamatan == 'Raman Utara' ? 'selected' : '' }}>Raman Utara</option>
                <option value="Sekampung" {{ $data->kecamatan == 'Sekampung' ? 'selected' : '' }}>Sekampung</option>
                <option value="Sekampung Udik" {{ $data->kecamatan == 'Sekampung Udik' ? 'selected' : '' }}>Sekampung Udik</option>
                <option value="Sukadana" {{ $data->kecamatan == 'Sukadana' ? 'selected' : '' }}>Sukadana</option>
                <option value="Waway Karya" {{ $data->kecamatan == 'Waway Karya' ? 'selected' : '' }}>Waway Karya</option>
                <option value="Way Bungur" {{ $data->kecamatan == 'Way Bungur' ? 'selected' : '' }}>Way Bungur</option>
                <option value="Way Jepara" {{ $data->kecamatan == 'Way Jepara' ? 'selected' : '' }}>Way Jepara</option>
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
                <input type="text" value="{{ $data->password }}" id="password" class="form-control" name="password" placeholder="Password">
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