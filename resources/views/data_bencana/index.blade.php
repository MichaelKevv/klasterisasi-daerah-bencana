@extends('template')
@section('title', 'Data Bencana')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="data_bencana">Data Bencana</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Data Bencana</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Bencana</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <a href="{{ route('data_bencana.create') }}"><button class="btn btn-success">Tambah
                                Data</button></a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalImport">
                            Import Data
                        </button>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tahun</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Kota/Kab</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama Kecamatan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Jenis Bencana</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Frekuensi Kejadian</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Kerusakan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Korban Jiwa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $data_bencana)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="text-sm font-weight-bold">{{ $data_bencana->tahun }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $data_bencana->tb_kotakab->nama_kotakab }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $data_bencana->tb_kecamatan->nama_kecamatan }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="text-sm font-weight-bold">{{ $data_bencana->tb_jenisbencana->nama_bencana }}
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
                                            <td class="align-middle">
                                                <a href="{{ route('data_bencana.edit', $data_bencana->id) }}"
                                                    class="btn btn-primary font-weight-bold text-xs">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('data_bencana.destroy', $data_bencana->id) }}"
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
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="ModalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalImportLabel">Import Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('data_bencana.import') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="file" name="file" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
