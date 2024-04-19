<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(): Collection
    {
        return Brand::all();
    }

    public function show($id): Model|Collection|Builder|array|null
    {
        return Brand::query()->findOrFail($id);
    }


    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        DB::table('brands')->insert(['name' => $request['name']]);

        return response()->json(['success' => true, 'message' => 'Brand created']);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $brand = Brand::query()->findOrFail($id);
        $brand->update($request->input('name'));

        return response()->json(['success' => true, 'message' => 'Brand updated']);
    }


    public function delete($id): JsonResponse
    {
        $brand = Brand::query()->findOrFail($id);
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted']);
    }
}
