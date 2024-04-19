<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index(): Collection
    {
        return Branch::all();
    }

    public function show($id): Model|Collection|Builder|array|null
    {
        return Branch::query()->findOrFail($id);
    }


    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'brand' => 'required|string',
            'region' => 'required|string',
            'district' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $attributes = $this->getAttributes($request['brand'], $request['region'], $request['district']);
        Branch::query()->create([
            'name' => $request['name'],
            'brand_id' => $attributes['brand'],
            'region_id' => $attributes['region'],
            'district_id' => $attributes['district'],
        ]);

        return response()->json(['success' => true, 'message' => 'Branch created']);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'brand' => 'required|string',
            'region' => 'required|string',
            'district' => 'required|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $branch = Branch::query()->findOrFail($id);
        $attributes = $this->getAttributes($request['brand'], $request['region'], $request['district']);
        $inputs['name'] = $request['name'];
        $inputs['brand_id'] = $attributes['brand'];
        $inputs['region_id'] = $attributes['region'];
        $inputs['district_id'] = $attributes['district'];
        $branch->update($inputs);

        return response()->json(['success' => true, 'message' => 'Branch updated']);
    }


    public function delete($id): JsonResponse
    {
        $branch = Branch::query()->findOrFail($id);
        $branch->delete();
        return response()->json(['success' => true, 'message' => 'Branch deleted']);
    }

    public function countOfBranch($brand): JsonResponse
    {
        $brandInfo = DB::table('brands')
            ->join('branches', 'brands.id', '=', 'branches.brand_id')
            ->join('regions', 'branches.region_id', '=', 'regions.id')
            ->join('districts', 'branches.district_id', '=', 'districts.id')
            ->select('brands.name AS brand', 'regions.name AS region', 'districts.name AS district',
                DB::raw('COUNT(branches.id) AS branchCount'))
            ->where('brands.name', $brand)
            ->groupBy('brands.id', 'brands.name', 'regions.name', 'districts.name')
            ->get();

        return response()->json(['success' => true, 'data' => $brandInfo]);
    }
}
