<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Log In Kepala Dapur - Sistem Program MBG Lampung Timur</title>
    <meta name="description" content="Log In Kepala Cabang - Program MBG Lampung Timur">
    <meta name="keywords" content="mbg, program makanan bergizi, lampung timur" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/icon/192x192.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="__manifest.json">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: linear-gradient(to bottom, #ded4aeff, #f6df4aff);
            background-size: 100% 100%;
            background-attachment: fixed;
        }

        .login-form {
            max-width: 300px;
            margin: 40px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-image {
            max-width: 150px;
            margin: 0 auto;
            display: block;
            animation: fadeIn 1s ease-in-out;
        }

        h2, h4 {
            text-align: center;
            color: #2c3e50;
            margin-top: 10px;
            font-weight: bold;
            animation: slideDown 1s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
            animation: fadeInUp 1s ease-in-out;
        }

        .btn {
            background-color: #2ecc71;
            border: none;
            transition: background-color 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #27ae60;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Media Query untuk membuat konten menyesuaikan dengan ukuran layar */
        @media (min-width: 1200px) and (max-width: 2160px) {
            .login-form {
                max-width: 30%; /* adjust the width to your liking */
                margin: 0 auto; /* center the form horizontally */
            }
        }
        
        @media (max-width: 768px) {
            .login-form {
                max-width: 80%;
                margin: 20px auto;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                max-width: 85%;
                margin: 10px auto;
            }
            .form-image {
                max-width: 100px;
            }
        }
    </style>
</head>

<body class="bg-white">

    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-form mt-5">
            <div class="section mt-3">
                <img src="{{ asset('assets/img/login/login_chef.png') }}" alt="Sistem Distribusi Program MBG" class="form-image">
            </div>
            <div class="section">
                <h2 class="fs-1 fw-bolder text-center">Login Kepala Dapur</h2>
                <p class="fs-4 text-secondary text-center">Sistem Program MBG <br> Kabupaten Lampung Timur</p>
            </div>
            <div class="section mb-5">
                @php
                $messagewarning = Session::get('warning');
                @endphp
                @if ($messagewarning)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ $messagewarning }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form action="/prosesloginkepaladapur" method="POST">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" name="email" class="form-control" id="email" placeholder="Masukkan E-Mail Anda" style="opacity: 0.5;">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Anda" style="opacity: 0.5;">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Log in</button>
                    </div>
                </form>
                <a href="{{ url('/') }}" class="btn btn-primary rounded-2 w-100 fw-bold form-group boxed" style="margin-top: 10px;">Kembali ke Halaman Utama</a>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

    <!-- JS Files -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('assets/js/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/base.js') }}"></script>
</body>

</html>