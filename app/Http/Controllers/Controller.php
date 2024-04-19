<?php

namespace App\Http\Controllers;

use App\Models\BranchImage;
use App\Models\Brand;
use App\Models\District;
use App\Models\Region;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
