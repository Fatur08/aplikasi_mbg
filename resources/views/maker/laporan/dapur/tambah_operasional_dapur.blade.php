@extends('layouts.maker.laporan.dapur.layout_dapur')
@section('content')
    <form action="/maker/laporan/dapur/update_operasional_dapur" method="POST" id="FormTambahOperasionalDapur"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
                    <select name="pilih_instansi" id="pilih_instansi" class="form-select" required>
                        <option value="">Pilih Instansi</option>
                        @foreach ($distribusi as $d)
                            <option value="{{ $d->tujuan_distribusi }}">
                                {{ $d->tujuan_distribusi }} ({{ $d->jumlah_paket }} Paket)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
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
                    <input type="text" value="" id="tanggal_operasional_dapur" name="tanggal_operasional_dapur"
                        class="form-control" placeholder="Masukkan Tanggal" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-soup">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1" />
                            <path d="M12 4a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                            <path d="M16 4a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                            <path d="M8 4a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                        </svg>
                    </span>
                    <input type="text" value="" id="menu_operasional_dapur" name="menu_operasional_dapur"
                        class="form-control" placeholder="Masukkan Menu" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icon-tabler-note">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M13 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l6 6v8a2 2 0 0 1 -2 2h-3" />
                            <path d="M13 4v6h6" />
                        </svg>
                    </span>
                    <textarea id="kendala_operasional_dapur" name="kendala_operasional_dapur" class="form-control" rows="1"
                        placeholder="Tuliskan kendalanya di sini..."></textarea>
                </div>
            </div>
        </div>
        <div class="row">
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
@endsection
@push('myscript')
    <script>
        $(function () {
            flatpickr("#tanggal_operasional_dapur", {
                altInput: true,
                altFormat: "d F Y",      // 15 September 2025
                dateFormat: "Y-m-d",     // dikirim ke backend
                locale: "id",
                allowInput: true,

                appendTo: document.body,
                position: "auto",

                disableMobile: true, // 🔥 WAJIB
                clickOpens: true,
                allowInput: false,
            });

            $("#FormTambahOperasionalDapur").submit(function () {
                var modal_masuk = $("#modal_masuk").val();
                var modal_keluar = $("#modal_keluar").val();
                var tanggal_data_koperasi = $("#tanggal_data_koperasi").val();
                if (modal_masuk = 0) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Modal Masuk Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#modal_masuk").focus();
                    });
                    return false;
                } else if (modal_keluar = 0) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Modal Keluar Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#modal_keluar").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush