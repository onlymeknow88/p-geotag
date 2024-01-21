<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Tanaman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Tanaman::with('map')->get();
        return view('tanaman.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['map'] = Map::all();
        return view('tanaman.add', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Tanaman();
        $data->name = $request->name;
        $data->nama_ilmiah = $request->nama_ilmiah;
        $data->tinggi_maks = $request->tinggi_maks;
        $data->diameter_maks = $request->diameter_maks;
        $data->radius = $request->radius;
        $data->year = $request->year;
        $data->kordinate = $request->posisi;
        $data->map_id = $request->map_id;
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
        Storage::disk('public')->put("tanaman/" . $filename, file_get_contents($file));

        $data->image = $filename;

        $data->save();

        return redirect('/tanaman')->with('status', 'Tanaman added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Tanaman::findorfail($id);
        $data['map'] = Map::all();
        return view('tanaman.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Tanaman::find($id);
        $data->name = $request->name;
        $data->nama_ilmiah = $request->nama_ilmiah;
        $data->tinggi_maks = $request->tinggi_maks;
        $data->diameter_maks = $request->diameter_maks;
        $data->radius = $request->radius;
        $data->year = $request->year;
        $data->kordinate = $request->posisi;
        $data->map_id = $request->map_id;

        // $file = $request->file('file');
        // $extension = $file->getClientOriginalExtension();
        // $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
        // Storage::disk('public')->put("tanaman/" . $filename, file_get_contents($file));

        // $data->image = $filename;

        $data->update();

        return redirect('/tanaman')->with('status', 'Tanaman added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function maps($id){
        $data = Map::find($id);
        $json = Storage::disk('public')->get('/map/geojson/' . $data->geoJson);

        return response()->json(['json'=> $json]);

        // return view('tanaman.maps', ['data' => $data]);
    }
}
