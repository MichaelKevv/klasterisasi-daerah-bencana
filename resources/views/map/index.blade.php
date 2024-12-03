@extends('template')
@section('title', 'Pemetaan Daerah')
@section('navbar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url('dashboard') }}">Dashboard</a></li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Pemetaan Daerah</h6>
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
                        <div class="form-group mb-2">
                            <label for="tahun">Pilih Tahun:</label>
                            <select id="tahun" class="form-select">
                                <option disabled selected>Pilih Tahun</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="map" style="width: 100%; height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('css_legend')
        <style>
            .info.legend {
                background: white;
                padding: 6px 8px;
                font-size: 14px;
                line-height: 18px;
                color: #555;
                border-radius: 5px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }

            .info.legend i {
                width: 18px;
                height: 18px;
                float: left;
                margin-right: 8px;
                opacity: 0.7;
                border: 1px solid #ddd;
            }
        </style>
    @endpush
    @push('proses_pemetaan')
        <script>
            var map = L.map('map').setView([-7.670235012045887, 112.18156128524838], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap'
            }).addTo(map);

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

            // Custom Legend Control
            var legend = L.control({
                position: 'bottomright'
            });

            legend.onAdd = function(map) {
                var div = L.DomUtil.create('div', 'info legend');

                // Legend content
                div.innerHTML += 'Keterangan Warna<br>';
                div.innerHTML += '<i style="background: #00FF00FF"></i> Cluster 1 / Rendah<br>';
                div.innerHTML += '<i style="background: #FFCC00FF"></i> Cluster 2 / Sedang<br>';
                div.innerHTML += '<i style="background: #FF0000FF"></i> Cluster 3 / Tinggi<br>';
                div.innerHTML += '<i style="background: #d9d9d9"></i> Belum Ada Data<br>';

                return div;
            };

            // Add the legend to the map
            legend.addTo(map);

            function loadGeoJSON(geojsonData) {
                L.geoJSON(geojsonData, {
                    style: style,
                    onEachFeature: function(feature, layer) {
                        layer.on('mouseover', function(e) {
                            let clusterDescription;
                            switch (feature.properties.cluster) {
                                case 'C1':
                                    clusterDescription = "Rendah";
                                    break;
                                case 'C2':
                                    clusterDescription = "Sedang";
                                    break;
                                case 'C3':
                                    clusterDescription = "Tinggi";
                                    break;
                                default:
                                    clusterDescription = "Tidak Ada Data";
                            }
                            const popupContent = `
                    <b>Kabupaten:</b> ${feature.properties.NAME_2 || "N/A"}<br>
                    <b>Kecamatan:</b> ${feature.properties.NAME_3 || "N/A"}<br>
                    <b>Cluster:</b> ${clusterDescription}
                `;
                            const popup = L.popup({
                                    closeButton: false
                                })
                                .setLatLng(e.latlng)
                                .setContent(popupContent)
                                .openOn(map);
                            layer.popup = popup;
                        });

                        layer.on('mouseout', function() {
                            // Tutup popup saat mouse keluar dari layer
                            if (layer.popup) {
                                map.closePopup(layer.popup);
                                layer.popup = null; // Hapus referensi popup
                            }
                        });
                    }
                }).addTo(map);
            }

            $(document).ready(function() {
                // const initialGeoJSON = {!! $geojson !!};
                // loadGeoJSON(initialGeoJSON);

                $('#tahun').on('change', function() {
                    const selectedYear = $(this).val();
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Tunggu sebentar, sedang memuat data',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        url: "{{ route('map.fetch') }}",
                        method: "GET",
                        data: {
                            year: selectedYear
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Sukses mendapatkan data pemetaan tahun ' +
                                    selectedYear,
                                icon: 'success',
                            });
                            map.eachLayer(function(layer) {
                                if (!layer._url) {
                                    map.removeLayer(layer);
                                }
                            });

                            loadGeoJSON(response);

                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", status, error);
                        },
                    });
                });
            });
        </script>
    @endpush
@endsection
