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
use OpenApi\Annotations as OA;

class BranchController extends Controller
{
    /**
     * Branches list.
     *
     * @OA\Get(
     *      path="/api/branches",
     *      operationId="branchList",
     *      summary="Branches list",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index(): JsonResponse
    {
        $branches = Branch::with('images')->get();

        return response()->json([
            'status' => true,
            'message' => 'Branch list',
            'result' => $branches
        ]);
    }

    /**
     * Branch show.
     *
     * @OA\Get(
     *      path="/api/branch/{id}",
     *      operationId="BranchShow",
     *      summary="Branch show",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Branch not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function show($id): Model|Collection|Builder|array|null
    {
        return Branch::query()->findOrFail($id)->with('images');
    }

    /**
     * Branch add.
     *
     * @OA\Post(
     *      path="/api/branch/create",
     *      operationId="BranchCreate",
     *      summary="Branch Create",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Branch",
     *         required=true,
     *         example="Branch",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand",
     *         in="query",
     *         description="Brand",
     *         required=true,
     *         example="Brand",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         description="Region",
     *         required=true,
     *         example="Region",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="district",
     *          in="query",
     *          description="District",
     *          required=true,
     *          example="District",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!"),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Branch update.
     *
     * @OA\Put(
     *      path="/api/branch/update/{id}",
     *      operationId="BranchUpdate",
     *      summary="Branch update",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch Id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Branch",
     *         required=true,
     *         example="Branch",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="brand",
     *         in="query",
     *         description="Brand",
     *         required=true,
     *         example="Brand",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="region",
     *          in="query",
     *          description="Region",
     *          required=true,
     *          example="Region",
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *         name="district",
     *         in="query",
     *         description="District",
     *         required=true,
     *         example="District",
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
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

    /**
     * Branch delete info.
     *
     * @OA\Delete(
     *      path="/api/branch/delete/{id}",
     *      operationId="BranchDelete",
     *      summary="Branch delete",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Branch id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Branch not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $branch = Branch::query()->findOrFail($id);
        $branch->delete();
        return response()->json(['success' => true, 'message' => 'Branch deleted']);
    }

    /**
     * Branch info location and count.
     *
     * @OA\Get(
     *      path="/api/countOfBranch/{brand}",
     *      operationId="findBranchLocationCount",
     *      summary="find Branch Location Count",
     *      tags={"Branches Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         description="Brand",
     *         required=true,
     *         example="Brand",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Branch not found!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $brand
     * @return JsonResponse
     */
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
