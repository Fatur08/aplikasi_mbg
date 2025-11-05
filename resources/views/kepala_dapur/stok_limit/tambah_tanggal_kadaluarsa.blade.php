@extends('layouts.kepala_dapur.stok_limit.layout_stok_limit')
@section('content')
<form action="/kepala_dapur/stok_limit/{{ $stok->id_stok_masuk }}/store_tambah_tanggal_kadaluarsa" method="POST" id="frmTmbhBhnTpk" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $stok->id_stok_masuk }}" id="id_stok_masuk" class="form-control" name="id_stok_masuk" hidden>
    <div class="row">
        <div class="col-12">
            <h4>Masukkan Tanggal Kadaluarsa</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                </span>
                <input type="text" value="" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="form-control flatpickr" placeholder="Masukkan Tanggal Kadaluarsa" autocomplete="off">
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

        $("#frmdatakoperasi").submit(function(){
            var modal_masuk = $("#modal_masuk").val();
            var modal_keluar = $("#modal_keluar").val();
            var tanggal_data_koperasi = $("#tanggal_data_koperasi").val();
            if(modal_masuk=0){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Modal Masuk Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#modal_masuk").focus();
                  });
                return false;
            } else if (modal_keluar=0){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Modal Keluar Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                  }).then(()=> {
                      $("#modal_keluar").focus();
                  });
                return false;
            }
        });
    });
</script>
@endpush