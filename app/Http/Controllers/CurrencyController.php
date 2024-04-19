<?php

namespace App\Http\Controllers;

use App\Services\getCurrencyDriver;
use App\Services\CurrencyService;
use OpenApi\Annotations as OA;

class CurrencyController extends Controller
{

    /**
     * Brands list.
     *
     * @OA\Get(
     *      path="/api/getCurrency",
     *      operationId="CountryCurrencyList",
     *      summary="CountryCurrency list",
     *      tags={"CountryCurrency Route"},
     *      security={{ "bearerAuth": {} }},
     *           @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="id",
     *          required=true,
     *          example="2",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\Response(response=200, description="Success!", @OA\JsonContent()),
     *      @OA\Response(response=401, description="Unauthorized!")
     * )
     *
     */
    public function getCurrency($appId = 1): \Illuminate\Http\JsonResponse
    {
        $service = new CurrencyService(new getCurrencyDriver());
        $service->getCountryAndCurrency($appId);

        return response()->json(['success' => true, 'message' => 'Country and currency added to db']);
    }
}
