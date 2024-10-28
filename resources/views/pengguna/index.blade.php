@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Pengguna</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Pengguna</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    @if (Auth::user()->role == 'kepala_sekolah')
                        <a href="{{ url('export/pengguna') }}" target="_blank"><button class="btn btn-success">Export
                                PDF</button></a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $pengguna)
                                <tr>
                                    <td>{{ $pengguna->email }}</td>
                                    <td>{{ $pengguna->username }}</td>
                                    <td>{{ $pengguna->role }}</td>
                                    <td>
                                        <a href="{{ route('pengguna.edit', $pengguna->id_pengguna) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit Pengguna">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        {{-- <a href="{{ route('pengguna.destroy', $pengguna->id_pengguna) }}"
                                                class="btn btn-danger font-weight-bold text-xs" data-confirm-delete="true">
                                                Delete
                                            </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection
