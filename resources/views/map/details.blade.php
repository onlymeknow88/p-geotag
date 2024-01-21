<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details Mapping</title>

    <!--Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }


        /* custom popup leaflet */
        .custom-popup .leaflet-popup-content-wrapper {
            background: #fff;
            color: #000;
            font-size: 16px;
            line-height: 24px;
            border-radius: 0px;
        }

        .custom-popup .leaflet-popup-content-wrapper a {
            color: rgba(255, 255, 255, 0.1);
        }

        .custom-popup .leaflet-popup-tip-container {
            width: 30px;
            height: 15px;
        }

        .custom-popup .leaflet-popup-tip {
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .pohon_name {
            cursor: pointer;
        }
        .pohon_name:hover {
            color: blue;
        }
    </style>
</head>

<body>


    <table style="border:1px solid black;border-collapse:collapse">
        <tr>
            <th></th>
            <th style="border:1px solid black">Nama</th>
            <th style="border:1px solid black">Nama Ilmiah</th>
            <th style="border:1px solid black">Koordinate</th>
        </tr>
        @foreach ($pohon as $item)
            <tr>
                <td style="border:1px solid black"><input type="checkbox" id="pohon_id{{ $item->id }}"
                        value="{{ $item->id }}" checked>
                </td>
                <td style="border:1px solid black"><span class="pohon_name" id="pohon_name{{ $item->id }}">{{ $item->name }}</span>
                </td>
                <td style="border:1px solid black">{{ $item->nama_ilmiah }}</td>
                <td style="border:1px solid black">{{ $item->kordinate }}</td>
            </tr>
        @endforeach
    </table>

    <br>
    <br>

    Total Pohon : {{ $pohon->count() }}
    <div class="custom-popup" id="map" style="width: 1050px; height: 600px;" wire:ignore></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const url = "{{ url('/storage/map/geojson/' . $area->geoJson) }}";

        // var peta1 = L.tileLayer(
        //     'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW5vbnk3OTExIiwiYSI6ImNsYTZiYWwybzE2d2YzcnFxaWdvNGdsbHMifQ.LtkD7CYPKYyAmyXG6tqNbA', {
        //         attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        //             '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        //             'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        //         id: 'mapbox/streets-v11'
        //     });

        const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        });

        @foreach ($pohon as $data)
            var pohon{{ $data->id }} = L.layerGroup();
        @endforeach

        function zoomToFeature(e) {
            map.fitBounds(e.target.getBounds());
        }

        //checked pohon_id
        @foreach ($pohon as $data)
            //checked pohon_id hide/show marker
            $('#pohon_id{{ $data->id }}').click(function() {
                var pohon_id = $(this).val();
                if ($(this).prop("checked") == true) {
                    map.addLayer(pohon{{ $data->id }});
                } else if ($(this).prop("checked") == false) {
                    map.removeLayer(pohon{{ $data->id }});
                }
            });

            //click pohon_name zoom to marker
            $('#pohon_name{{ $data->id }}').click(function() {
                var pohon_id = $(this).val();
                $('#pohon_id{{ $data->id }}').prop('checked', true);
                map.addLayer(pohon{{ $data->id }});
                map.setView([{{ $data->kordinate }}], 18);
            });
        @endforeach

        //area
        var data{{ $area->id }} = L.layerGroup();

        var map = L.map('map', {
            center: [-0.08994646741225015,
                114.86507559850388
            ],
            zoom: 11,
            layers: [osm, data{{ $area->id }},
                @foreach ($pohon as $data)
                    pohon{{ $data->id }},
                @endforeach
            ]
        });

        var baseMaps = {
            // "Grayscale": peta1,
            "Satellite": osm,
        };

        var overlayer = {
            "{{ $area->name }}": data{{ $area->id }},
        };

        L.control.layers(baseMaps, overlayer).addTo(map);

        $.get(url, function(data) {
            var json = JSON.parse(data);

            var area = L.geoJSON(json, {
                style: {
                    color: 'white',
                    fillColor: '#000000',
                    fillOpacity: 0.7,
                },
            }).addTo(data{{ $area->id }});

            map.fitBounds(area.getBounds());
        })

        @foreach ($pohon as $data)
            var blueIcon = L.icon({
                iconUrl: '{{ asset('assets/icon/icon-1.png') }}',
                iconSize: [20, 20],
            });

            var coordinate = [{{ $data->kordinate }}];

            var informasi =
                '<table class="table table-bordered"><tr><td colspan="2"><img src="{{ url('storage/tanaman') }}/{{ $data->image }}" width="250px"></td></tr><tbody><tr><td>Nama Tanaman</td><td>: {{ $data->name }}</td></tr><tr><td>Nama Ilmiah</td><td>: {{ $data->nama_ilmiah }}</td></tr></tbody></table>';

            L.marker(coordinate, {
                    icon: blueIcon
                })
                .addTo(pohon{{ $data->id }})
                .bindPopup(informasi);
        @endforeach
    </script>
</body>

</html>
