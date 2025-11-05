<form action="/kepala_dapur/menu_harian/{{ $data->id_jadwal_menu_harian }}/store_tambah_bahan_terpakai" method="POST" id="frmTmbhBhnTpk" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_jadwal_menu_harian }}" id="id" class="form-control" name="id" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <h4>Masukkan Bahan 1</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <select name="id_menu_harian_1" id="id_menu_harian_1" class="form-select">
                <option value="">Pilih Bahan (Yang Tersedia Di Dapur)</option>
                @foreach($bahan as $item)
                    <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }} Tersisa : {{ $sisa_perbahan[$item->nama_bahan]->sisa_per_bahan ?? 0 }} {{ $sisa_perbahan[$item->nama_bahan]->satuan_bahan ?? '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calculator"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" /><path d="M8 14l0 .01" /><path d="M12 14l0 .01" /><path d="M16 14l0 .01" /><path d="M8 17l0 .01" /><path d="M12 17l0 .01" /><path d="M16 17l0 .01" /></svg>
                </span>
                <input type="number" value="" id="jumlah_bahan_menu_1" name="jumlah_bahan_menu_1" class="form-control" placeholder="Masukkan Jumlah Bahan Yang Digunakan" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4>Masukkan Bahan 2 (Opsional)</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <select name="id_menu_harian_2" id="id_menu_harian_2" class="form-select">
                <option value="">Pilih Bahan (Yang Tersedia Di Dapur)</option>
                @foreach($bahan as $item)
                    <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }} Tersisa : {{ $sisa_perbahan[$item->nama_bahan]->sisa_per_bahan ?? 0 }} {{ $sisa_perbahan[$item->nama_bahan]->satuan_bahan ?? '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calculator"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" /><path d="M8 14l0 .01" /><path d="M12 14l0 .01" /><path d="M16 14l0 .01" /><path d="M8 17l0 .01" /><path d="M12 17l0 .01" /><path d="M16 17l0 .01" /></svg>
                </span>
                <input type="number" value="" id="jumlah_bahan_menu_2" name="jumlah_bahan_menu_2" class="form-control" placeholder="Masukkan Jumlah Bahan Yang Digunakan" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4>Masukkan Bahan 3 (Opsional)</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <select name="id_menu_harian_3" id="id_menu_harian_3" class="form-select">
                <option value="">Pilih Bahan (Yang Tersedia Di Dapur)</option>
                @foreach($bahan as $item)
                    <option value="{{ $item->id_bahan }}">{{ $item->nama_bahan }} Tersisa : {{ $sisa_perbahan[$item->nama_bahan]->sisa_per_bahan ?? 0 }} {{ $sisa_perbahan[$item->nama_bahan]->satuan_bahan ?? '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calculator"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" /><path d="M8 14l0 .01" /><path d="M12 14l0 .01" /><path d="M16 14l0 .01" /><path d="M8 17l0 .01" /><path d="M12 17l0 .01" /><path d="M16 17l0 .01" /></svg>
                </span>
                <input type="number" value="" id="jumlah_bahan_menu_3" name="jumlah_bahan_menu_3" class="form-control" placeholder="Masukkan Jumlah Bahan Yang Digunakan" autocomplete="off">
            </div>
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