@extends('template')
@section('title', 'Dashboard')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm text-dark active" aria-current="dashboard">Dashboard</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Dashboard</h6>
    </nav>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Overview Cards Section -->
        <div class="row mb-2">
            <!-- Kota/Kabupaten Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">location_city</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Kota / Kabupaten</p>
                            <h4 class="mb-0">{{ $kotakab }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <a href="{{ url('kotakab') }}" class="cursor-pointer float-end">Selengkapnya</a>
                    </div>
                </div>
            </div>
            <!-- Kecamatan Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">home</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Kecamatan</p>
                            <h4 class="mb-0">{{ $kecamatan }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <a href="{{ url('kecamatan') }}" class="cursor-pointer float-end">Selengkapnya</a>
                    </div>
                </div>
            </div>
            <!-- Jenis Bencana Card -->
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">public</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Jenis Bencana</p>
                            <h4 class="mb-0">{{ $jenis_bencana }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <a href="{{ url('jenis_bencana') }}" class="cursor-pointer float-end">Selengkapnya</a>
                    </div>
                </div>
            </div>
            <!-- Data Bencana Card -->
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">warning</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Data Bencana</p>
                            <h4 class="mb-0">{{ $data_bencana }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <a href="{{ url('data_bencana') }}" class="cursor-pointer float-end">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Statistics Berdasarkan Jenis Bencana-->
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Statistik Berdasarkan Jenis Bencana</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartBencanaJenis"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Berdasarkan Tahun -->
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Statistik Berdasarkan Tahun</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chartBencanaTahun" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('stats_bencana')
        <script>
            // Data yang dikirimkan dari controller
            var jenisBencana = @json($bencanaByJenis->pluck('nama_bencana'));
            var jumlahBencana = @json($bencanaByJenis->pluck('total'));
            var colors = @json($colors);

            var ctxJenisBencana = document.getElementById('chartBencanaJenis').getContext('2d');
            var chartBencanaJenis = new Chart(ctxJenisBencana, {
                type: 'pie',
                data: {
                    labels: jenisBencana,
                    datasets: [{
                        label: 'Jumlah Bencana',
                        data: jumlahBencana,
                        backgroundColor: colors,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                }
            });

            var ctxTahun = document.getElementById('chartBencanaTahun').getContext('2d');
            var chartBencanaTahun = new Chart(ctxTahun, {
                type: 'bar',
                data: {
                    labels: @json($bencanaByTahun->pluck('tahun')),
                    datasets: [{
                        label: 'Jumlah Bencana',
                        data: @json($bencanaByTahun->pluck('total')),
                        backgroundColor: '#4CAF50',
                        borderColor: '#388E3C',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

@endsection
