<?php

namespace App\Http\Controllers;

use App\Services\getCurrencyDriver;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    public function getCurrency($appId): \Illuminate\Http\JsonResponse
    {
        $service = new CurrencyService(new getCurrencyDriver());
        $service->getCountryAndCurrency($appId);

        return response()->json(['success' => true, 'message' => 'Country and currency added to db']);
    }
}
