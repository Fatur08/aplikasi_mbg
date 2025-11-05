@extends('layouts.distributor.layout')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/distributor/pengiriman_distributor/index_pengiriman_distributor" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Halaman Konfirmasi Pengiriman</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
 <style>
    .webcam-capture,
    .webcam-capture video{
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
    .swal2-container {
      z-index: 999999 !important;
    }
    #map {
        height: 300px;
    }
 </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<!-- App Capsule -->
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <div id="map"></div>
    </div>
</div>

<div class="row mt-2 mb-5">
    <div class="col">
        <input type="input" value="{{ $distribusi->id_distribusi }}" class="form-control" name="id_distribusi" id="id_distribusi" hidden>
        <!-- Bagian Lokasi User -->
        <div class="form-group boxed">
            <h4>Lokasi Distributor</h4>
            <div class="input-wrapper">
                <input type="input" class="form-control" id="lokasi_distribusi" name="lokasi_distribusi" readonly>
            </div>
        </div>
        <!-- Bagian Sekolah (Tujuan Distribusi) -->
        <div class="form-group boxed">
            <h4>Sekolah</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control"
                       value="{{ $distribusi->tujuan_distribusi ?? '-' }}" id="tujuan_distribusi" name="tujuan_distribusi" readonly>
            </div>
        </div>
        <!-- Bagian Tanggal (Tanggal Distribusi) -->
        <div class="form-group boxed">
            <h4>Tanggal Distribusi</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control"
                       value="{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('d F Y') ?? '-' }}" id="tanggal_distribusi" name="tanggal_distribusi" readonly>
            </div>
        </div>
        <!-- Bagian Menu (Menu Makanan) -->
        <div class="form-group boxed">
            <h4>Menu</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control"
                       value="{{ $distribusi->menu_makanan ?? '-' }}" id="menu_makanan" name="menu_makanan" readonly>
            </div>
        </div>
        <!-- Bagian Porsi (Jumlah Paket) -->
        <div class="form-group boxed">
            <h4>Porsi</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control"
                       value="{{ $distribusi->jumlah_paket ?? '-' }}" id="jumlah_paket" name="jumlah_paket" readonly>
            </div>
        </div>
        <!-- Bagian Bukti Pengiriman -->
        <div class="form-group boxed" id="fileUpload1">
            <h4>Foto Bukti Terima</h4>
            <input type="file" name="bukti_pengiriman" id="bukti_pengiriman" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput"></label>
        </div>
        <button type="submit" id="konfirmasi_pengiriman" class="btn btn-primary btn-block mt-2 mb-4">
            Konfirmasi Pengiriman
        </button>
    </div>
</div>
@endsection

@push('myscript')
<script>
    Webcam.set({
        height:480,
        widht:640,
        image_format:'jpeg',
        jpeg_quality:80
    });
    Webcam.attach('.webcam-capture');

    var lokasi_distribusi = document.getElementById('lokasi_distribusi');

    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }


    function successCallback(position) {
        //var lat_distributor = -5.736904;
        //var long_distributor = 105.591711;
        //var lat_distributor = -5.7074060550542045;
        //var long_distributor = 105.58920064156884;

        // Lokasi Value Adalah Titik Lokasinya User
        //lokasi.value = lat_distributor + "," + long_distributor;
        lokasi_distribusi.value = position.coords.latitude + "," + position.coords.longitude;

        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 28);
        //var map = L.map('map').setView([lat_distributor, long_distributor], 28);

        var radius = 50
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        //var marker = L.marker([lat_distributor, long_distributor]).addTo(map);
        
        var circle = L.circle([position.coords.latitude, position.coords.longitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);
    }

    function errorCallback() {
        alert("Terjadi kesalahan");
    }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#konfirmasi_pengiriman").click(function(e) {
        e.preventDefault(); // Hindari perilaku default
        var id_distribusi = $('#id_distribusi').val();
        var lokasi_distribusi = $('#lokasi_distribusi').val();
        var tujuan_distribusi = $('#tujuan_distribusi').val();
        var tanggal_distribusi = $('#tanggal_distribusi').val();
        var menu_makanan = $('#menu_makanan').val();
        var jumlah_paket = $('#jumlah_paket').val();
        var bukti_pengiriman = $('#bukti_pengiriman')[0].files[0]; // ambil file

        // buat objek FormData untuk kirim file + data text
        var formData = new FormData();
        formData.append('id_distribusi', id_distribusi);
        formData.append('lokasi_distribusi', lokasi_distribusi);
        formData.append('tujuan_distribusi', tujuan_distribusi);
        formData.append('tanggal_distribusi', tanggal_distribusi);
        formData.append('menu_makanan', menu_makanan);
        formData.append('jumlah_paket', jumlah_paket);
        formData.append('bukti_pengiriman', bukti_pengiriman);

        
        $.ajax({
            type:'POST',
            url:'/distributor/pengiriman_distributor/store_pengiriman_distributor',
            data: formData,
            processData: false, // wajib
            contentType: false, // wajib
            cache:false,
            success: function (response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Konfirmasi Pengiriman Berhasil Dikirim',
                    icon: 'success'
                });

                setTimeout(function () {
                    window.location.href = '/distributor/pengiriman_distributor/index_pengiriman_distributor';
                }, 2000);
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan: ' + xhr.responseText,
                    icon: 'error'
                });
            }
        });
    });
</script>
@endpush