@extends('template')
@section('title', 'Tambah Data Bencana')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="data_bencana">Data Bencana</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Tambah Data Bencana</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Tambah Data Bencana</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form method="POST" action="{{ route('data_bencana.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Tahun</label>
                                        <input type="text" name="tahun" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Kota/Kabupaten</label>
                                        <select name="id_kotakab" id="id_kotakab" class="form-select">
                                            <option selected disabled>Pilih Kota/Kab</option>
                                            @foreach ($kotakab as $kb)
                                                <option value="{{ $kb->id }}">{{ $kb->nama_kotakab }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Kecamatan</label>
                                        <select name="id_kecamatan" id="id_kecamatan" class="form-select">
                                            <option selected disabled>Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Bencana</label>
                                        <select name="id_jenisbencana" id="id_jenisbencana" class="form-select">
                                            <option selected disabled>Pilih Bencana</option>
                                            @foreach ($jenis_bencana as $j)
                                                <option value="{{ $j->id }}">{{ $j->nama_bencana }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Frekuensi Kejadian</label>
                                        <input type="number" name="frekuensi_kejadian" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Total Kerusakan</label>
                                        <input type="number" name="total_kerusakan" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Total Korban Jiwa</label>
                                        <input type="number" name="total_korban" class="form-control">
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
    @push('get_kecamatan')
        <script>
            $(document).ready(function() {
                $('#id_kotakab').on('change', function() {
                    var kotakabID = $(this).val();
                    if (kotakabID) {
                        $.ajax({
                            url: `{{ url('get-kecamatan/${kotakabID}') }}`,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#id_kecamatan').empty();
                                $('#id_kecamatan').append(
                                    '<option selected disabled>Pilih Kecamatan</option>');
                                $.each(data, function(key, value) {
                                    $('#id_kecamatan').append('<option value="' + value.id +
                                        '">' + value.nama_kecamatan + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#id_kecamatan').empty();
                        $('#id_kecamatan').append('<option selected disabled>Pilih Kecamatan</option>');
                    }
                });
            });
        </script>
    @endpush


@endsection
