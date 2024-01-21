<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tanaman</title>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

    <a href="{{ url('/tanaman/create') }}">Add</a>
    <br>
    <br>
    <br>

    <table>
        <tr>
            <th>No</th>
            <th>Map</th>
            <th>Name</th>
            <th>Nama Ilmiah</th>
            <th>Tinggi Maks</th>
            <th>Diameter Maks</th>
            <th>Kordinate </th>
            <th>Image</th>
            <th>Action</th>
        </tr>

        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->area['name'] }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->nama_ilmiah }}</td>
                <td>{{ $item->tinggi_maks }}</td>
                <td>{{ $item->diameter_maks }}</td>
                <td>{{ $item->kordinate }}</td>
                <td><img src="{{ asset('storage/tanaman/' . $item->image) }}" alt="" width="100"></td>
                <td><a href="{{ route('tanaman.edit', $item->id) }}">Edit</a></td>
            </tr>
        @endforeach


    </table>
</body>
</html>
