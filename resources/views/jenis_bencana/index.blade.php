@extends('template')
@section('title', 'Data Jenis Bencana')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="jenis_bencana">Jenis Bencana</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Data Jenis Bencana</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Jenis Bencana</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <a href="{{ route('jenis_bencana.create') }}"><button class="btn btn-success">Tambah Data</button></a>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Bencana</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $jenis_bencana)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $jenis_bencana->nama_bencana }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('jenis_bencana.edit', $jenis_bencana->id) }}"
                                                    class="btn btn-primary font-weight-bold text-xs">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('jenis_bencana.destroy', $jenis_bencana->id) }}"
                                                    class="btn btn-danger font-weight-bold text-xs"
                                                    data-confirm-delete="true">
                                                    <i class="bi bi-trash"></i>
                                                </a>
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