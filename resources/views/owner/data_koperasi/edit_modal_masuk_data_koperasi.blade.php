@extends('layouts.owner.data_koperasi.layout_data_koperasi')
@section('content')
<form action="/owner/data_koperasi/{{ $data->id_data_koperasi }}/update_modal_masuk_data_koperasi" method="POST" id="frmmodalmasukdatakoperasi" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_data_koperasi }}" id="id" class="form-control" name="id" placeholder="id" hidden>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         class="icon icon-tabler icons-tabler-outline icon-tabler-category">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 4h6v6h-6z" />
                        <path d="M14 4h6v6h-6z" />
                        <path d="M4 14h6v6h-6z" />
                        <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    </svg>
                </span>
                <input type="text" 
                       value="{{ $data->kategori_data_koperasi }}" 
                       id="kategori_data_koperasi" 
                       class="form-control" 
                       name="kategori_data_koperasi" 
                       placeholder="Masukkan Sumber Modal Masuk">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-currency-dollar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg>
                </span>
                <input type="number" value="{{ $data->harga_data_koperasi }}" id="harga_data_koperasi" class="form-control" name="harga_data_koperasi" placeholder="Masukkan Modal Masuk">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                </span>
                <input type="text" value="{{ $data->tanggal_data_koperasi }}" id="tanggal_data_koperasi" name="tanggal_data_koperasi" class="form-control flatpickr" placeholder="Masukkan Tanggal" autocomplete="off">
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