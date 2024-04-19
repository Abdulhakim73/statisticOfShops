<?php

namespace App\Http\Controllers;

use App\Models\BranchImage;
use App\Models\Brand;
use App\Models\District;
use App\Models\Region;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Test task API Documentation",
 *     description="Test task API Documentation Description",
 *     @OA\Contact(name="Swagger API Team")
 * )
 * @OA\Server(
 *     url="http://localhost:8080/",
 *     description="Local API server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getAttributes($brand, $region, $district): array
    {
        $brand = Brand::query()->where('name', $brand)->first();
        $region = Region::query()->where('name', $region)->first();
        $district = District::query()->where('name', $district)->first();

        return [
            'brand' => $brand['id'],
            'region' => $region['id'],
            'district' => $district['id']
        ];
    }
}
