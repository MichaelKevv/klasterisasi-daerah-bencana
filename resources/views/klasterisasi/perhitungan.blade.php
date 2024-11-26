@extends('template')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                    href="{{ url('klasterisasi/perhitungan') }}">Perhitungan Klasterisasi</a>
            </li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Perhitungan Iterasi {{ $iteration }} untuk Tahun
            {{ $tahun }}</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Perhitungan Iterasi {{ $iteration }} untuk Tahun
                                {{ $tahun }}</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        @if ($iteration == 1)
                            <h5>Centroid Awal</h5>
                        @else
                            <h5>Centroid Baru</h5>
                        @endif
                        <table class="table align-items-center mb-0" id="table1">
                            <thead>
                                <tr>
                                    <th>Frekuensi Kejadian</th>
                                    <th>Total Kerusakan</th>
                                    <th>Total Korban</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logCentroid as $index => $item)
                                    <tr>
                                        <td>{{ $item->centroid_frekuensi }}</td>
                                        <td>{{ $item->centroid_kerusakan }}</td>
                                        <td>{{ $item->centroid_korban }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h5>Proses Perhitungan</h5>
                        <table class="table align-items-center mb-0" id="table2">
                            <thead>
                                <tr>
                                    <th>Data ke</th>
                                    <th>Frekuensi Kejadian</th>
                                    <th>Total Kerusakan</th>
                                    <th>Total Korban</th>
                                    <th>C1</th>
                                    <th>C2</th>
                                    <th>C3</th>
                                    <th>Terdekat</th>
                                    <th>Cluster</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logIterasi as $index => $item)
                                    <tr>
                                        <td><a href="{{ url('data_bencana/' . $item->id_kecamatan . '/' . $tahun) }}">
                                                {{ $index + 1 }}</a>
                                        </td>
                                        <td>{{ $item->frekuensi_kejadian }}</td>
                                        <td>{{ $item->total_kerusakan }}</td>
                                        <td>{{ $item->total_korban }}</td>
                                        <td>{{ number_format($item->c1, 4) }}</td> <!-- Format angka hingga 4 desimal -->
                                        <td>{{ number_format($item->c2, 4) }}</td>
                                        <td>{{ number_format($item->c3, 4) }}</td>
                                        <td>{{ $item->terdekat }}</td>
                                        <td>{{ $item->terdekat }}</td> <!-- Menampilkan cluster terdekat -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
