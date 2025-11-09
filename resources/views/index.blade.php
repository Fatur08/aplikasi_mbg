<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama Login - Program MBG Lampung Timur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #eaf3ff, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
        }

        .login-container {
            text-align: center;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 15px;
            max-width: 500px;
            width: 90%;
        }

        .login-title {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }

        .role-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .role-card img {
            width: 80%;
            height: 93px;
            object-fit: contain;
        }

        .role-card button {
            width: 80%;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-title">
        Sistem Program MBG<br>Kabupaten Lampung Timur dan Selatan
    </div>

    <img src="{{ asset('assets/img/logo_SPPG_lampung_timur(200x200).png') }}" 
         alt="Logo SPPG Lampung Timur" 
         style="width:230px; height:220px; margin-bottom: 15px;">

    <p>Silakan pilih login sesuai peran Anda:</p>

    <div class="role-grid">

        <!-- Owner -->
        <div class="role-card">
            <img src="{{ asset('assets/img/owner/login/loginowner.png') }}" alt="Login Owner">
            <a href="{{ url('/owner') }}" class="btn btn-primary">Login Owner</a>
        </div>

        <!-- Admin -->
        <div class="role-card">
            <img src="{{ asset('assets/img/owner/login/loginadmin.png') }}" alt="Login Admin">
            <a href="{{ url('/admin') }}" class="btn btn-success">Login Admin</a>
        </div>

        <!-- Kepala Dapur -->
        <div class="role-card">
            <img src="{{ asset('assets/img/login/login_chef.png') }}" alt="Login Kepala Dapur">
            <a href="{{ url('/kepala_dapur') }}" class="btn btn-warning text-white">Login <br> Kp. Dapur</a>
        </div>

        <!-- Distributor -->
        <div class="role-card">
            <img src="{{ asset('assets/img/login/login_distributor.png') }}" alt="Login Distributor">
            <a href="{{ url('/distributor') }}" class="btn btn-info text-white">Login Distributor</a>
        </div>

    </div>
</div>

</body>
</html>