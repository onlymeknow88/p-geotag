<?php

namespace App\Http\Controllers\API;

use App\Models\Tanaman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PohonController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $search = $request->input('search');
            $id = $request->input('id');

            $query = Tanaman::query()->with('area');

            //get pohon or tanaman by ID
            if ($id) {
                $data = $query->find($id);


                if ($data) {
                    return ResponseFormatter::success($data, 'data found');
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
            $data = $request->except('image');

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
            Storage::disk('public')->put("tanaman/" . $filename, file_get_contents($file));

            $data['image'] = $filename;

            $tanaman = Tanaman::create($data);

            return ResponseFormatter::success($tanaman, 'data created');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->except('image');

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug(basename($file->getClientOriginalName(), '.' . $extension)) . '.' . $extension;
            Storage::disk('public')->put("tanaman/" . $filename, file_get_contents($file));

            $data['image'] = $filename;

            $tanaman = Tanaman::find($id);
            $tanaman->update($data);

            return ResponseFormatter::success($tanaman, 'data updated');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $tanaman = Tanaman::find($id);
            $tanaman->delete();

            return ResponseFormatter::success($tanaman, 'data deleted');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }
}
