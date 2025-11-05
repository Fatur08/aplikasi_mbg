@extends('layouts.distributor.layout')
@section('header')
<!--- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Profil Distributor</div>
    <div class="right"></div>
</div>
<!--- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if(Session::get('success'))
            <div class="alert alert-success">
                {{ $messagesuccess }}
            </div>
        @endif
        @if(Session::get('error'))
            <div class="alert alert-error">
                {{ $messageerror }}
            </div>
        @endif
    </div>
</div>
<form action="/distributor/profil_distributor/update_profil_distributor" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col">
        <!-- Foto Distributor -->
        <div class="text-center mb-3">
            @php
                $foto = Auth::guard('distributor')->user()->foto_distributor ?? null;
            @endphp

            @if($foto)
                @php
                    $path = Storage::url('uploads/data_induk/distributor/' . $foto);
                @endphp
                <img src="{{ url($path) }}" 
                     alt="Foto Distributor" 
                     class="rounded-circle" 
                     style="width: 120px; height: 170px; object-fit: cover; border: 3px solid #007bff;">
            @else
                <img src="{{ asset('assets/img/nophoto.jpg') }}" 
                     alt="No Image" 
                     class="rounded-circle" 
                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ccc;">
            @endif

            <div class="mt-2">
                <label for="foto_distributor" class="btn btn-sm btn-outline-primary">
                    <ion-icon name="camera-outline"></ion-icon> Ganti Foto
                </label>
            </div>
        </div>

        <!-- Input Data Distributor -->
        <div class="form-group boxed">
            <h4>Nama Lengkap</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ old('nama_distributor', $data_distributor->nama_distributor ?? '') }}" name="nama_distributor" placeholder="Masukkan Nama Lengkap" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <h4>E-Mail</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ old('email_distributor', $data_distributor->email_distributor ?? '') }}" name="email_distributor" placeholder="Masukkan E-Mail" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <h4>Nomor HP</h4>
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ old('no_hp_distributor', $data_distributor->no_hp_distributor ?? '') }}" name="no_hp_distributor" placeholder="No. HP" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <h4>Alamat</h4>
            <div class="input-wrapper">
                <textarea id="alamat_distributor" name="alamat_distributor" class="form-control" rows="1" placeholder="Masukkan Alamat">{{ old('alamat_distributor', $data_distributor->alamat_distributor ?? '') }}</textarea>
            </div>
        </div>
        <div class="form-group boxed">
            <h4>Password</h4>
            <div class="input-wrapper">
                <input type="password" class="form-control" name="password_distributor" placeholder="Password" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed" id="fileUpload1">
            <h4>Foto</h4>
            <input type="file" name="foto_distributor" id="foto_distributor" accept=".png, .jpg, .jpeg">
            <label for="foto_distributor">
            </label>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection