<?php

namespace App\Http\Controllers\API;

use App\Models\Map;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AreaController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $search = $request->input('search');
            $id = $request->input('id');

            $query = Map::query();

            //get area by ID
            if ($id) {
                $area = $query->find($id);


                if ($area) {
                    return ResponseFormatter::success($area, 'data found');
                }

                return ResponseFormatter::error('data not found', 404);
            } else {
                $query = $query;
            }


            if ($search) {
                $query->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%$search%");
                });
            }

            return ResponseFormatter::success(
                $query->orderBy('id', 'desc')->paginate($limit),
                'fetch success'
            );
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->except('file');

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
            Storage::disk('public')->put("map/geojson/" . $filename, file_get_contents($file));

            $data['image'] = $filename;

            $area = Map::create($data);

            return ResponseFormatter::success($area, 'data created');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $area = Map::find($id);

            $data = $request->except('file');

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
            Storage::disk('public')->put("map/geojson/" . $filename, file_get_contents($file));

            $data['image'] = $filename;

            $area->update($data);

            return ResponseFormatter::success($area, 'data updated');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $area = Map::find($id);
            $area->delete();

            return ResponseFormatter::success($area, 'data deleted');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }
}
