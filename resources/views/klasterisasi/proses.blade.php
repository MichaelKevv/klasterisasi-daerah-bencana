@extends('template')
@section('title', 'Data Klasterisasi')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="data_bencana">Klasterisasi</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Data Klasterisasi</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Klasterisasi</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <h3>Proses Iterasi</h3>
                        @foreach ($iterationData as $iteration)
                            <div class="iteration">
                                <h4>Iterasi ke-{{ $iteration['iteration'] }}</h4>

                                {{-- Tabel centroid --}}
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Centroid</th>
                                            <th>Total Frekuensi</th>
                                            <th>Total Kerusakan</th>
                                            <th>Luas Terdampak</th>
                                            <th>Total Korban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iteration['centroids'] as $index => $centroid)
                                            <tr>
                                                <td>C{{ $index + 1 }}</td>
                                                <td>{{ $centroid['centroid']['total_frekuensi'] }}</td>
                                                <td>{{ $centroid['centroid']['total_kerusakan'] }}</td>
                                                <td>{{ $centroid['centroid']['total_korban'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Tabel cluster --}}
                                <h5>Data Kluster:</h5>
                                @foreach ($iteration['clusters'] as $clusterIndex => $cluster)
                                    <h6>Cluster C{{ $clusterIndex + 1 }}</h6>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kecamatan</th>
                                                <th>Total Frekuensi</th>
                                                <th>Total Kerusakan</th>
                                                <th>Luas Terdampak</th>
                                                <th>Total Korban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cluster as $data)
                                                <tr>
                                                    <td>{{ $data['nama_kecamatan'] }}</td>
                                                    <td>{{ $data['total_frekuensi'] }}</td>
                                                    <td>{{ $data['total_kerusakan'] }}</td>
                                                    <td>{{ $data['total_korban'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
