@extends('template')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="hasil">Klasterisasi</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Perhitungan Klasterisasi</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Perhitungan Klasterisasi</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <div class="col-12">
                            <div class="row">
                                @foreach ($dataPerTahun as $data)
                                    <strong>Tahun: {{ $data['tahun'] }}</strong>
                                    @foreach ($data['iterasi'] as $iterasi)
                                        <div class="col-3">
                                            Iterasi: {{ $iterasi->iteration }}
                                            <a
                                                href="{{ url('/klasterisasi/perhitungan/' . $data['tahun'] . '/' . $iterasi->iteration) }}">
                                                <button class="btn btn-success">Lihat Perhitungan Iterasi</button>
                                            </a>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
