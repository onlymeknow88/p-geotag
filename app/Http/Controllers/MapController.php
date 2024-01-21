<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Tanaman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    public function index()
    {
        $maps = Map::all();

        $tanamans = Tanaman::all();

        return view('map.map', ['maps' => $maps,'tanamans' => $tanamans]);
    }

    public function store(Request $request)
    {

        //upload file geojson to parse
        $file = $request->file('file');
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
                Storage::disk('public')->put('/map/geojson/' . $filename, file_get_contents($file));

                $map = new Map();
                $map->name = $request->name;
                $map->geoJson = $filename;

                $map->save();
            }
        }

        return redirect('/map')->with('status', 'Map added successfully');
    }

    public function show($id) {
        $map = Map::findorfail($id);
        $map['path'] = '/storage/map/geojson/'.$map->geoJson;
        $map['json'] = Storage::disk('public')->get('/map/geojson/' . $map->geoJson);


        $data = [
            'title' => 'Area'.$map->name,
            'areas' => Map::get(),
            'pohon' => Tanaman::where('map_id',$map->id)->get(),
            'area' => $map
        ];
        // dd($data);

        return view('map.details', $data);
    }
}
