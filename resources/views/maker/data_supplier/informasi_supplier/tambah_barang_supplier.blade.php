@extends('layouts.maker.supplier.informasi_supplier.layout_informasi_supplier')
@section('content')
<form action="/maker/data_supplier/informasi_supplier/{{ $data->id_informasi_supplier }}/store_barang_supplier" method="POST" id="frmTmbhBrgSpplr" enctype="multipart/form-data">
    @csrf
    <input type="text" readonly value="{{ $data->id_informasi_supplier }}"               id="id_informasi_supplier"           class="form-control"    name="id_informasi_supplier"             hidden>
    <input type="text" readonly value="{{ $data->nomor_dapur_informasi_supplier }}"      id="pilih_dapur_modal_keluar"   class="form-control"    name="pilih_dapur_modal_keluar"     hidden>
    <div class="row">
        <div class="col-12">
            <label class="form-label">Jumlah Barang</label>
            <select class="form-control" id="jumlah_barang">
                <option value="">-- Pilih Jumlah Barang --</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }} Barang</option>
                @endfor
            </select>
        </div>
    </div>
    <div id="container-barang"></div>
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
$(document).ready(function () {

    $('#jumlah_barang').on('change', function () {

        let jumlah = $(this).val();
        let html   = '';

        if (jumlah === '') {
            $('#container-barang').html('');
            return;
        }

        for (let i = 1; i <= jumlah; i++) {

            html += `
            <div class="border rounded p-3 mb-3">
                <h4>Masukkan Barang Ke-${i}</h4>

                <div class="input-icon mb-3">
                    <span class="input-icon-addon">📦</span>
                    <input type="text" name="nama_barang_supplier[]" class="form-control"
                        placeholder="Nama Barang Ke-${i}" required>
                </div>

                <div class="input-icon mb-3">
                    <span class="input-icon-addon">🔢</span>
                    <input type="number" name="jumlah_barang_supplier[]" class="form-control"
                        placeholder="Jumlah Barang Ke-${i}" required>
                </div>

                <div class="input-icon mb-3">
                    <span class="input-icon-addon">📐</span>
                    <input type="text" name="satuan_barang_supplier[]" class="form-control"
                        placeholder="Satuan Barang Ke-${i}" required>
                </div>

                <div class="input-icon mb-3">
                    <span class="input-icon-addon">💰</span>
                    <input type="number" name="harga_barang_supplier[]" class="form-control"
                        placeholder="Total Harga Barang Ke-${i}" required>
                </div>
            </div>`;
        }

        $('#container-barang').html(html);
    });

});
</script>
@endpush