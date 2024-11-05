@extends('template')
@section('title', 'Pemetaan Daerah')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="kecamatan">Pemetaan Daerah</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Peta Hasil Klasterisasi</h6>
    </nav>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Pemetaan Daerah</h6>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-2">
                        <div id="map" style="width: 100%; height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('proses_pemetaan')
        <script>
            var map = L.map('map').setView([-7.670235012045887, 112.18156128524838], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var geojsonData = {!! $geojson !!};

            function style(feature) {
                let color;
                switch (feature.properties.cluster) {
                    case 'C1':
                        color = "#00FF00FF";
                        break;
                    case 'C2':
                        color = "#FFCC00FF";
                        break;
                    case 'C3':
                        color = "#FF0000FF";
                        break;
                    default:
                        color = "#d9d9d9";
                }
                return {
                    color: '#000000',
                    weight: 1,
                    opacity: 1,
                    fillColor: color,
                    fillOpacity: 0.7
                };
            }

            L.geoJSON(geojsonData, {
                style: style,
                onEachFeature: function(feature, layer) {
                    layer.bindPopup(
                        "<b>Kabupaten:</b> " + (feature.properties.NAME_2 || "N/A") +
                        "<br><b>Kecamatan:</b> " + (feature.properties.NAME_3 || "N/A") +
                        "<br><b>Cluster Level:</b> " + (feature.properties.cluster || "Tidak Ada Data")
                    );
                }
            }).addTo(map);
        </script>
    @endpush
@endsection
