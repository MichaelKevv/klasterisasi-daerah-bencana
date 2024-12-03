<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>
        Klasterisasi Daerah Rawan Bencana | @yield('title')
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" />

    <link rel="stylesheet" crossorigin href="{{ asset('compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('extensions/simple-datatables/style.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .label {
            font-size: 12px;
            color: #333;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 2px 5px;
            border-radius: 3px;
            text-align: center;
        }
    </style>
    @stack('css_legend')
</head>

<body class="g-sidenav-show  bg-gray-200">
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="{{ url('dashboard') }}" target="_blank">
                <img src="{{ asset('img/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-white">KDRB Jatim</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('kotakab') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">location_city</i>
                        </div>
                        <span class="nav-link-text ms-1">Kota Kabupaten</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('kecamatan') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">home</i>
                        </div>
                        <span class="nav-link-text ms-1">Kecamatan</span>
                    </a>
                </li>
                @if (session('userdata.role') == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('pengguna') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">people</i>
                            </div>
                            <span class="nav-link-text ms-1">Pengguna</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('jenis_bencana') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">public</i>
                        </div>
                        <span class="nav-link-text ms-1">Jenis Bencana</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('data_bencana') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">warning</i>
                        </div>
                        <span class="nav-link-text ms-1">Data Bencana</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#klasterisasi" class="nav-link text-white collapsed"
                        aria-controls="klasterisasi" role="button" aria-expanded="false">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">bar_chart</i>
                        </div>
                        <span class="nav-link-text ms-1">Klasterisasi</span>
                    </a>
                    <div class="collapse" id="klasterisasi" style="">
                        <ul class="nav">
                            @if (session('userdata.role') == 'superadmin')
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('klasterisasi/perhitungan') }}">
                                    <span class="sidenav-normal  ms-1  ps-1">Perhitungan Klasterisasi</span>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('klasterisasi/hasil') }}">
                                    <span class="sidenav-normal  ms-1  ps-1">Hasil Klasterisasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ url('pemetaan') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">map</i>
                        </div>
                        <span class="nav-link-text ms-1">Pemetaan</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl shadow-none position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky"
            id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                @yield('navbar')
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>
                    <ul class="navbar-nav  justify-content-end">

                        <li class="nav-item d-xl-none ps-3 me-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">{{ session('userdata.nama_user') }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                                <li class="">
                                    <a href="{{ url('logout') }}" class="nav-link text-body font-weight-bold px-0">
                                        <span class="d-sm-inline d-none">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item d-flex align-items-center">

                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        @yield('content')
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>

    <script src="{{ asset('extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('static/js/pages/simple-datatables.js') }}"></script>

    @stack('stats_bencana')

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Mendapatkan URL saat ini
            var currentUrl = window.location.href;

            // Mencari setiap link di sidebar
            var sidebarItems = document.querySelectorAll('.nav-item a');

            sidebarItems.forEach(function(item) {
                var href = item.getAttribute('href');

                // Memeriksa apakah URL saat ini cocok dengan URL link sidebar
                if (href && currentUrl.includes(href)) {
                    var navLink = item.closest('.nav-link');
                    var collapseDiv = item.closest('.collapse');

                    if (navLink) {
                        // Menambahkan kelas active pada elemen parent dari link yang cocok
                        navLink.classList.add('active');
                        navLink.classList.add('bg-gradient-primary');
                    }

                    if (collapseDiv) {
                        // Menambahkan kelas show pada div jika ada submenu yang cocok
                        collapseDiv.classList.add('show');

                        // Tambahkan kelas active ke parent nav-link yang terkait
                        var parentNavLink = collapseDiv.previousElementSibling;
                        if (parentNavLink && parentNavLink.classList.contains('nav-link')) {
                            parentNavLink.classList.add('active');
                        }
                    }
                }
            });
        });
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/material-dashboard.min.js?v=3.1.0') }}"></script>
    @include('sweetalert::alert')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('get_kecamatan')
    @stack('proses_klasterisasi')

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    @stack('proses_pemetaan')
</body>

</html>
