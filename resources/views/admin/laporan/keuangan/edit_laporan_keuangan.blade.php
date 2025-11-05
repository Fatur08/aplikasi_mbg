@extends('layouts.owner.laporan.keuangan.layout_laporan_keuangan')
@section('content')
<form action="/owner/laporan/keuangan/{{ $data->id_laporan_keuangan }}/update_laporan_keuangan" method="POST" id="frmeditlaporankeuangan" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_laporan_keuangan }}" id="id" class="form-control" name="id" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                </span>
                <input type="text" value="{{ $data->tanggal_laporan_keuangan }}" id="edit_tanggal_laporan_keuangan" name="edit_tanggal_laporan_keuangan" class="form-control flatpickr" placeholder="Masukkan Tanggal" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <select name="edit_jenis_laporan_keuangan" id="edit_jenis_laporan_keuangan" class="form-select">
                    <option value="">Jenis Transaksi</option>
                    <option value="Pemasukan">Pemasukan</option>
                    <option value="Pengeluaran">Pengeluaran</option>
                </select>
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
                <input type="text" value="{{ $data->kategori_laporan_keuangan }}" id="edit_kategori_laporan_keuangan" class="form-control" name="edit_kategori_laporan_keuangan" placeholder="Kategori">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                        class="icon icon-tabler icon-tabler-note">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M13 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l6 6v8a2 2 0 0 1 -2 2h-3" />
                        <path d="M13 4v6h6" />
                    </svg>
                </span>
                <textarea id="edit_keterangan_laporan_keuangan" name="edit_keterangan_laporan_keuangan" class="form-control" rows="1" placeholder="Tuliskan keterangan di sini...">{{ $data->keterangan_laporan_keuangan }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-cashapp"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" /></svg>
                </span>
                <input type="number" value="{{ $data->jumlah_dana }}" id="edit_jumlah_dana" class="form-control" name="edit_jumlah_dana" placeholder="Jumlah Dana">
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
@endsection
@push('myscript')
<script>
    $(function(){
        $(".flatpickr").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        })

        $("#frmeditlaporankeuangan").submit(function(){
            var edit_tanggal_laporan_keuangan = $("#edit_tanggal_laporan_keuangan").val();
            var edit_jenis_laporan_keuangan = $("#edit_jenis_laporan_keuangan").val();
            var edit_kategori_laporan_keuangan = $("#edit_kategori_laporan_keuangan").val();
            var edit_keterangan_laporan_keuangan = $("#edit_keterangan_laporan_keuangan").val();
            var edit_jumlah_dana = $("#edit_jumlah_dana").val();
            if(edit_tanggal_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#edit_tanggal_laporan_keuangan").focus();
                  });
                return false;
            } else if (edit_jenis_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jenis Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#edit_jenis_laporan_keuangan").focus();
                  });
                return false;
            } else if (edit_kategori_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Kategori Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#edit_kategori_laporan_keuangan").focus();
                  });
                return false;
            } else if (edit_keterangan_laporan_keuangan==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#edit_keterangan_laporan_keuangan").focus();
                  });
                return false;
            } else if (edit_jumlah_dana==""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jumlah Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#edit_jumlah_dana").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush