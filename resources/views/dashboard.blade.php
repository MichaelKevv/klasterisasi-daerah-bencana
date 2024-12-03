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
        <div class="row mb-3">
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
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
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

        <div class="row mb-3">
            <!-- Table Hasil Klasterisasi -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Hasil Klasterisasi<span id="selectedTahun"></span></h5>
                    </div>
                    <div class="card-body">
                        <form id="klasterisasi-form" action="{{ route('klasterisasi.proses') }}" method="POST">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="tahun">Pilih Tahun:</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        <div id="klasterisasi-result">
                            <p>Silakan pilih tahun untuk melihat hasil klasterisasi.</p>
                        </div>
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
    @push('proses_klasterisasi')
        <script>
            $(document).ready(function() {
                $('#tahun').change(function() {
                    let tahun = $(this).val();
                    let selectedTahunLbl = $('#selectedTahun');
                    let resultDiv = $('#klasterisasi-result');
                    let btnProses = $('#proses-klasterisasi');
                    let btnPerhitungan = $('#perhitungan-klasterisasi');

                    if (!tahun) {
                        resultDiv.html('<p>Silakan pilih tahun.</p>');
                        return;
                    }

                    // Tampilkan pesan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Tunggu sebentar, sedang memuat data',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim permintaan AJAX
                    $.ajax({
                        url: "{{ route('klasterisasi.fetch') }}",
                        method: "GET",
                        data: {
                            tahun: tahun
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Sukses mendapatkan data klasterisasi tahun ' +
                                        tahun,
                                    icon: 'success',
                                });
                                let table = `
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Nama Kota/Kab</th>
                                            <th>Nama Kecamatan</th>
                                            <th>Frekuensi Kejadian</th>
                                            <th>Total Kerusakan</th>
                                            <th>Total Korban Jiwa</th>
                                            <th>Cluster</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                                response.data.forEach(function(item) {
                                    table += `
                                <tr>
                                    <td>${item.tb_kotakab.nama_kotakab}</td>
                                    <td>${item.tb_kecamatan.nama_kecamatan}</td>
                                    <td>${item.frekuensi_kejadian}</td>
                                    <td>${item.total_kerusakan}</td>
                                    <td>${item.total_korban}</td>
                                    <td>
                                        <span class="badge badge-sm ${
                                            item.cluster === 'C1' ? 'bg-gradient-success' :
                                            item.cluster === 'C2' ? 'bg-gradient-warning' : 'bg-gradient-danger'
                                        }">${item.cluster}</span>
                                    </td>
                                      <td class="align-middle">
            <a href="/klasterisasi/detail/${item.tb_kecamatan.id}"
                class="btn btn-primary font-weight-bold text-xs">
                Detail
            </a>
        </td>
                                </tr>
                            `;
                                });
                                table += `</tbody></table></div>`;
                                resultDiv.html(table);
                                const tableElement = document.getElementById('table1');
                                if (tableElement) {
                                    new simpleDatatables.DataTable(tableElement);
                                }
                            } else if (response.status === 'empty') {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Data klasterisasi tahun ' +
                                        tahun + ' tidak ditemukan',
                                    icon: 'info',
                                });
                                resultDiv.html(
                                    '<p>Data klasterisasi tidak ditemukan untuk tahun ini. Silakan tekan tombol <b>Proses Klasterisasi</b></p>'
                                );
                            }

                            selectedTahunLbl.text(' - Tahun ' + tahun);
                        },
                        error: function(xhr) {
                            resultDiv.html('<p>Terjadi kesalahan saat memuat data.</p>');
                        }
                    });
                });
            });
        </script>
    @endpush
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
