<!DOCTYPE html>
<html>

<head>
    <title>Laporan Artikel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .table th,
        .table td {
            font-size: 9pt;
        }

        .table thead th {
            color: #000;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }

        h5 {
            font-size: 16pt;
            margin-bottom: 5px;
        }

        .table {
            margin-top: 20px;
        }

        .printed-date {
            font-size: 9pt;
            margin-bottom: 20px;
        }
        .kop-surat hr {
            border: 0;
            border-top: 1px solid black;
            margin: 5px 0;
        }

        .kop-surat .line {
            border-bottom: 2px solid black;
            margin-top: 2px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="{{ public_path('images/kop_surat.png') }}" class="img-fluid">
        <hr>
        <div class="line"></div>
    </div>
    <center>
        <h5 class="mb-5">Laporan Artikel</h5>
    </center>
    <p>Dicetak pada tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
    <table class='table table-bordered table-striped'>
        <thead>
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Judul</th>
                <th>Konten</th>
                <th>Kategori</th>
                <th>Tanggal Upload</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $artikel)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ storage_path('app/public/foto-artikel/' . $artikel->gambar) }}" width="100px">
                    </td>
                    <td>{{ $artikel->judul }}</td>
                    <td>{!! $artikel->konten !!}</td>
                    <td>{{ $artikel->kategori }}</td>
                    <td>{{ \Carbon\Carbon::parse($artikel->created_at)->isoFormat('D MMMM YYYY HH:mm:ss') }}</td>
                    <td>{{ $artikel->author }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
