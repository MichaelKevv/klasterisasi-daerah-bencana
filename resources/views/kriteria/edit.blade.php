@extends('template')
@section('title', 'Edit Kriteria')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="kriteria">Kriteria</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Edit Kriteria</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Edit Kriteria</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form method="POST" action="{{ route('kriteria.update', $kriteria->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Kode Kriteria</label>
                                        <input type="text" name="kode_kriteria" value="{{ $kriteria->kode_kriteria }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Kriteria</label>
                                        <input type="text" name="nama_kriteria" value="{{ $kriteria->nama_kriteria }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success pt-2 float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
