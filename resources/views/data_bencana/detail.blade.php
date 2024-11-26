@extends('template')
@section('title', 'Hasil Klasterisasi')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="hasil">Data Bencana</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Detail Bencana</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Detail Data Bencana</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <p>Kabupaten / Kota : {{ $data_bencana->tb_kotakab->nama_kotakab }}</p>
                        <p>Kecamatan : {{ $data_bencana->tb_kecamatan->nama_kecamatan }}</p>
                        <p>Bencana : {{ $data_bencana->tb_jenisbencana->nama_bencana }}</p>
                        <p>Bencana : {{ $data_bencana->frekuensi_kejadian }}</p>
                        <p>Bencana : {{ $data_bencana->total_kerusakan }}</p>
                        <p>Bencana : {{ $data_bencana->total_korban }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
