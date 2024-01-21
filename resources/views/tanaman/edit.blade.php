<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Tanaman</title>

    <!--Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
    <br>
    <form action="{{ route('tanaman.update',$data->id) }}" method="post" enctype="multipart/form-data">

        @csrf
        @method('PUT')
        <div stlye="display:flex;flex-direction:column">
            <span>Name: </span>
            <input type="text" name="name" value="{{ $data->name }}">
        </div>
        <div stlye="display:flex;flex-direction:column">
            <span>Map Area: </span>
            <select name="map_id" id="map_id">
                <option value="0">Pilih</option>
                @foreach ($data['map'] as $item)
                    <option value="{{ $item->id }}" {{ $data->map_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div stlye="display:flex;flex-direction:column">
            <span>Nama Ilmiah: </span>
            <input type="text" name="nama_ilmiah" value="{{ $data->nama_ilmiah }}">
        </div>
        <div stlye="display:flex;flex-direction:column">
            <span>Tinggi Maks: </span>
            <input type="text" name="tinggi_maks" value="{{ $data->tinggi_maks }}">
        </div>
        <div stlye="display:flex;flex-direction:column">
            <span>Diameter Maks: </span>
            <input type="text" name="diameter_maks" value="{{ $data->diameter_maks }}">
        </div>
        <div stlye="display:flex;flex-direction:column">
            <span>Upload Image</span>
            <img src="{{ asset('storage/tanaman/' . $data->image) }}" alt="" style="width:300px;height:300px">
            <input type="file" name="file">
        </div>

        <div stlye="display:flex;flex-direction:column">
            <span>Posisi Tanaman/pohon</span>
            <input type="text" name="posisi" id="posisi" style="width:300px" value="{{ $data->kordinate }}">
        </div>

        <div stlye="display:flex;flex-direction:column">
            <span>Map: </span>
            <div id="map" style="width: 500px; height: 500px;"></div>
        </div>

        <button type="submit">Submit</button>

    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        var map = L.map('map').setView([{{ $data->kordinate }}], 13);

        const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        // var positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png', {
        //     attribution: '©CartoDB'
        // }).addTo(map);

        // var positronLabels = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
        //     attribution: '©CartoDB',
        //     pane: 'labels'
        // }).addTo(map);

        var curLocation = [{{ $data->kordinate }}];
        map.attributionControl.setPrefix(false);

        var marker = new L.marker(curLocation, {
            draggable: 'true',
        });
        map.addLayer(marker);

        var myStyle = {
            fillColor: "#DDDCFE",
            weight: 3,
            opacity: 1,
            color: '#ff7800',
            dashArray: '3',
            fillOpacity: 0.7
        };

        function zoomToFeature(e) {
            map.fitBounds(e.target.getBounds());
        }

        function onEachFeature(feature, layer) {
            layer.on({
                click: zoomToFeature
            });
        }



        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            marker.setLatLng(position, {
                draggable: 'true',
            }).bindPopup(position).update();
            //console.log(position.lat + "," + position.lng);
            $("#posisi").val(position.lat + "," + position.lng).keyup();
        });

        var posisi = document.querySelector("[name=posisi]");
        map.on("click", function(event) {
            var lat = event.latlng.lat;
            var lng = event.latlng.lng;
            if (!marker) {
                marker = L.marker(event.latlng).addTo(map);
            } else {
                marker.setLatLng(event.latlng);
            }
            posisi.value = lat + "," + lng;
        });

        $('#map_id').on('change', function(data) {
            var id_map = $(this).val();


            $.get('/tanaman/' + id_map + '/map', function(data) {
                const map_data = JSON.parse(data.json);
                const geojson = L.geoJSON(map_data, {
                    style: myStyle,
                    onEachFeature: onEachFeature
                }).addTo(map);
            })
        })
        // console.log(map_area)

        var id_map = $('#map_id').val();

        $.get('/tanaman/'+id_map+'/map', function(data) {
            const map_data = JSON.parse(data.json);
            console.log(map_data)

            const geojson = L.geoJSON(map_data, {
                style: myStyle,
                onEachFeature: onEachFeature
            }).addTo(map);
        })
    </script>
</body>

</html>
