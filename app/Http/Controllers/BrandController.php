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
use OpenApi\Annotations as OA;

class BrandController extends Controller
{

    /**
     * Brands list.
     *
     * @OA\Get(
     *      path="/api/brands",
     *      operationId="brandList",
     *      summary="Brands list",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     *
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function index(): Collection
    {
        return Brand::with('images')->get();
    }


    /**
     * Brand show.
     *
     * @OA\Get(
     *      path="/api/brand/{id}",
     *      operationId="BrandShow",
     *      summary="Brand show",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="2",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=404, description="Brand not found!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function show($id): Model|Collection|Builder|array|null
    {
        return Brand::query()->findOrFail($id)->with('images');
    }

    /**
     * Brand add.
     *
     * @OA\Post(
     *      path="/api/brand/create",
     *      operationId="BrandCreate",
     *      summary="Brand Create",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Brand name",
     *         required=true,
     *         example="Brand Name",
     *         @OA\Schema(type="string")
     *     ),
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
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        DB::table('brands')->insert(['name' => $request['name']]);

        return response()->json(['success' => true, 'message' => 'Brand created']);
    }

    /**
     * Brand update.
     *
     * @OA\Put(
     *      path="/api/brand/update{id}",
     *      operationId="BrandUpdate",
     *      summary="Brand update",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Brand name",
     *         required=true,
     *         example="New Brand Name",
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
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()], 400);
        }

        $brand = Brand::query()->findOrFail($id);
        $brand->update($request->input('name'));

        return response()->json(['success' => true, 'message' => 'Brand updated']);
    }


    /**
     * Brand delete info.
     *
     * @OA\Delete(
     *      path="/api/brand/delete/{id}",
     *      operationId="BrandDelete",
     *      summary="Brand delete",
     *      tags={"Brands Routes"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand id",
     *         required=true,
     *         example="143",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *     @OA\Response(response=404, description="Brand not found!", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Delete error!", @OA\JsonContent()),
     *     @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $brand = Brand::query()->findOrFail($id);
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted']);
    }
}
