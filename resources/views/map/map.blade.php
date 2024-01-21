<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!--Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        table, th, td{
            border: 1px solid black;
            border-collapse: collapse;

        }
    </style>
    </head>

<body>

    <a href="{{ url('/tanaman') }}">Tanaman</a>
    <br>
    <br>
    <br>

    <form action="{{ route('map.store') }}" method="post" enctype="multipart/form-data">

        @csrf
        <div stlye="display:flex;flex-direction:column">
            <span>Name: </span>
            <input type="text" name="name">
        </div>


        <div stlye="display:flex;flex-direction:column">
            <span>File GeoJson</span>
            <input type="file" name="file">
        </div>

        <button type="submit">Submit</button>

    </form>

    <br>
    <br>
    <br>
    <table>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>File</th>
            <th>Detail</th>
        </tr>

        @foreach ($maps as $file)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $file->name }}</td>
                <td>{{ $file->geoJson }}</td>
                <td><a href="{{ route('map.show', $file->id) }}">Detail</a></td>
            </tr>
        @endforeach


    </table>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script>
        var map = L.map('map').setView([-0.08995098308488562, 114.86516263573411], 13);

        const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 14,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        $.get(" {{ asset('assets/map/Reklamasi_2023.geojson') }}", function(data){
            console.log(data)
            const json = JSON.parse(data);

            L.geoJSON(json).addTo(map);
        })



    </script> --}}
</body>

</html>
