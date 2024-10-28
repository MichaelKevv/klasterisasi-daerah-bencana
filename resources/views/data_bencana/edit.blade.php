@extends('template')
@section('title', 'Edit Data Bencana')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="data_bencana">Data Bencana</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Edit Data Bencana</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Edit Data Bencana</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form method="POST" action="{{ route('data_bencana.update', $data_bencana->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Tahun</label>
                                        <input type="text" name="tahun" value="{{ $data_bencana->tahun }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Kota/Kabupaten</label>
                                        <select name="id_kotakab" id="id_kotakab" class="form-control">
                                            <option selected disabled>Pilih Kota/Kab</option>
                                            @foreach ($kotakab as $kb)
                                                <option {{ $kb->id == $data_bencana->id_kotakab ? 'selected' : '' }}
                                                    value="{{ $kb->id }}">{{ $kb->nama_kotakab }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Kecamatan</label>
                                        <select name="id_kecamatan" id="id_kecamatan" class="form-control">
                                            <option selected disabled>Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Nama Bencana</label>
                                        <select name="id_jenisbencana" id="id_jenisbencana" class="form-control">
                                            <option selected disabled>Pilih Bencana</option>
                                            @foreach ($jenis_bencana as $j)
                                                <option {{ $j->id == $data_bencana->id_jenisbencana ? 'selected' : '' }}
                                                    value="{{ $j->id }}">{{ $j->nama_bencana }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Frekuensi Kejadian</label>
                                        <input type="number" name="frekuensi_kejadian"
                                            value="{{ $data_bencana->frekuensi_kejadian }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Total Kerusakan</label>
                                        <input type="number" name="total_kerusakan"
                                            value="{{ $data_bencana->total_kerusakan }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Luas Daerah Terdampak</label>
                                        <input type="number" name="luas_terdampak"
                                            value="{{ $data_bencana->luas_terdampak }}" class="form-control"
                                            step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-static my-3">
                                        <label class="">Total Korban Jiwa</label>
                                        <input type="number" name="total_korban" value="{{ $data_bencana->total_korban }}"
                                            class="form-control">
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
                // Load kecamatan on page load based on selected kota/kab
                var kotakabID = $('#id_kotakab').val();
                var selectedKecamatan = '{{ $data_bencana->id_kecamatan }}';

                if (kotakabID) {
                    $.ajax({
                        url: `{{ url('get-kecamatan/${kotakabID}') }}`,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#id_kecamatan').empty();
                            $('#id_kecamatan').append('<option selected disabled>Pilih Kecamatan</option>');
                            $.each(data, function(key, value) {
                                var selected = value.id == selectedKecamatan ? 'selected' : '';
                                $('#id_kecamatan').append('<option value="' + value.id + '" ' +
                                    selected + '>' + value.nama_kecamatan + '</option>');
                            });
                        }
                    });
                }

                // Load kecamatan dynamically when kota/kab is changed
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
