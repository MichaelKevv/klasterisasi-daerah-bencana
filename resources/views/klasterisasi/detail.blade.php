@extends('template')
@section('title', 'Hasil Klasterisasi')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="hasil">Klasterisasi</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Detail Klasterisasi</h6>
    </nav>
@endsection
@section('content')
    @php
        function getClusterLabel($cluster)
        {
            switch ($cluster) {
                case 'C1':
                    return 'Rendah';
                case 'C2':
                    return 'Sedang';
                case 'C3':
                    return 'Tinggi';
                default:
                    return 'Tidak Diketahui';
            }
        }
    @endphp
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Detail Clustering</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <p>Kabupaten / Kota : {{ $clustering[0]->nama_kotakab }}</p>
                        <p>Kecamatan : {{ $clustering[0]->nama_kecamatan }}</p>
                        <p>Cluster : {{ $clustering[0]->cluster }} / {{ getClusterLabel($clustering[0]->cluster) }}</p>
                        <p>Bencana : </p>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tahun</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Jenis Bencana</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Frekuensi Kejadian</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Kerusakan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Luas Daerah Terdampak</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Korban Jiwa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clustering as $data_bencana)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $data_bencana->tahun }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $data_bencana->nama_bencana }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $data_bencana->frekuensi_kejadian }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $data_bencana->total_kerusakan }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $data_bencana->total_korban }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
