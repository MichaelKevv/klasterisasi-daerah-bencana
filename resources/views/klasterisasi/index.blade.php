@extends('template')
@section('title', 'Hasil Klasterisasi')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="hasil">Klasterisasi</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Hasil Klasterisasi</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Hasil Klasterisasi</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <form id="klasterisasi-form" action="{{ route('klasterisasi.proses') }}" method="POST">
                            @csrf
                            <button type="button" id="proses-klasterisasi" class="btn btn-success">Proses
                                Klasterisasi</button>
                        </form>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Kota/Kab</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Kecamatan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Frekuensi Kejadian</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Kerusakan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Luas Daerah Terdampak</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Korban Jiwa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Cluster</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $hasil)
                                        <tr>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $hasil->tb_kotakab->nama_kotakab }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $hasil->tb_kecamatan->nama_kecamatan }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $hasil->frekuensi_kejadian }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $hasil->total_kerusakan }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $hasil->luas_terdampak }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $hasil->total_korban }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @if ($hasil->cluster == 'C1')
                                                    <span
                                                        class="badge badge-sm bg-gradient-success">{{ $hasil->cluster }}</span>
                                                @elseif ($hasil->cluster == 'C2')
                                                    <span
                                                        class="badge badge-sm bg-gradient-warning">{{ $hasil->cluster }}</span>
                                                @elseif ($hasil->cluster == 'C3')
                                                    <span
                                                        class="badge badge-sm bg-gradient-danger">{{ $hasil->cluster }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('klasterisasi.detail', $hasil->tb_kecamatan->id) }}"
                                                    class="btn btn-primary font-weight-bold text-xs">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div id="hasil" class="d-none">
                            <h6>Centroid Awal</h6>
                            <div class="table-responsive p-0">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Cluster</th>
                                            <th>Nama Kabupaten/Kota</th>
                                            <th>Nama Kecamatan</th>
                                            <th>Total Frekuensi Kejadian</th>
                                            <th>Total Kerusakan</th>
                                            <th>Luas Daerah Terdampak</th>
                                            <th>Total Korban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($centroids as $clusterName => $centroid)
                                            <tr>
                                                <td>{{ ucfirst($clusterName + 1) }}</td>
                                                <td>{{ $centroid['nama_kotakab'] }}</td>
                                                <td>{{ $centroid['nama_kecamatan'] }}</td>
                                                <td>{{ $centroid['total_frekuensi'] }}</td>
                                                <td>{{ $centroid['total_kerusakan'] }}</td>
                                                <td>{{ $centroid['luas_terdampak'] }}</td>
                                                <td>{{ $centroid['total_korban'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="mt-1">Hasil Clustering</h6>
                            <div class="table-responsive p-0">
                                @foreach ($clusters as $index => $cluster)
                                    <h6 class="mt-2">Cluster {{ (int) $index + 1 }}</h6>
                                    <table class="table mb-0" id="table{{ (int) $index + 1 }}">
                                        <thead>
                                            <tr>
                                                <th>Kabupaten/Kota</th>
                                                <th>Kecamatan</th>
                                                <th>Total Frekuensi</th>
                                                <th>Total Kerusakan</th>
                                                <th>Total Luas Terdampak (km2)</th>
                                                <th>Total Korban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cluster as $point)
                                                <tr>
                                                    <td>{{ $point['nama_kabupaten'] }}</td>
                                                    <td>{{ $point['nama_kecamatan'] }}</td>
                                                    <td>{{ $point['total_frekuensi'] }}</td>
                                                    <td>{{ $point['total_kerusakan'] }}</td>
                                                    <td>{{ $point['luas_terdampak'] }}</td>
                                                    <td>{{ $point['total_korban'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('proses_klasterisasi')
        <script>
            $('#proses-klasterisasi').click(function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin memproses klasterisasi?',
                    text: "Proses ini akan memulai klasterisasi data!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, proses!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Tunggu sebentar, proses klasterisasi sedang berjalan.',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $('#klasterisasi-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
