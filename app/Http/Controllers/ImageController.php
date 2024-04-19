<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchImage;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as Files;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use OpenApi\Annotations as OA;

class ImageController extends Controller
{
    /**
     * Image post.
     *
     * @OA\Post(
     *      path="/api/createImage",
     *      operationId="ImageCreate",
     *      summary="Image add",
     *      tags={"Image Route"},
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *         name="image",
     *         in="query",
     *         description="Image name",
     *         required=true,
     *         example="Image Name",
     *         @OA\Schema(type="file")
     *     ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          description="Brand or Branch",
     *          required=true,
     *          example="Brand & Branch Name",
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *           name="nameOfBrand",
     *           in="query",
     *           description="Brand",
     *           required=true,
     *           example="Brand Name",
     *           @OA\Schema(type="string")
     *       ),
     *     @OA\Parameter(
     *            name="nameOfBranch",
     *            in="query",
     *            description="Branch",
     *            required=true,
     *            example="Branch Name",
     *            @OA\Schema(type="string")
     *        ),
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!"),
     *      @OA\Response(response=422, description="Validation error!", @OA\JsonContent()),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'image' => 'nullable|file|mimes:jpg,jpeg,png,svg',
            'type' => ['required', Rule::in('brand', 'branch')],
            'nameOfBrand' => 'requiredIf:type,brand|string',
            'nameOfBranch' => 'requiredIf:type,branch|string',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => true, 'message' => $validate->messages()]);
        }


        $file = $request->file('image');          // originalName
        $ex = $file->getClientOriginalExtension(); //jpg
        $img_name = str::random(20) . '.' . $ex;

        $img_path = $request->type . '/' . date('Y-m-d'); //product/2024-02-16
        if (!file_exists(base_path() . '/storage/app/public/' . $img_path)) {
            Files::makeDirectory(base_path() . '/storage/app/public/' . $img_path, 0775, true, true);
        }
        $image = Image::make($file);
        $image->save(base_path() . '/storage/app/public/' . $img_path . '/' . $img_name);

        //brand
        if ($request->type == "brand") {
            $brandId = Brand::query()->where('name', $request['nameOfBrand'])->first();
            //save to database
            $brandImage = new BrandImage();
            $brandImage->name = $img_name;
            $brandImage->brand_id = $brandId['id'];
            $brandImage->path = '/storage/' . $img_path . '/' . $img_name;
            $brandImage->mime_type = $ex;
            $brandImage->save();
        }

        //branch
        if ($request->type == "branch") {
            $branchId = Branch::query()->where('name', $request['nameOfBranch'])->first();
            //save to database
            $branchImage = new BranchImage();
            $branchImage->name = $img_name;
            $branchImage->branch_id = $branchId['id'];
            $branchImage->path = '/storage/' . $img_path . '/' . $img_name;
            $branchImage->mime_type = $ex;
            $branchImage->save();
        }

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Image successfully uploaded',
        ]);
    }
}
