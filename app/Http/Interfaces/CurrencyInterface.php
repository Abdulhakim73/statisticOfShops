<?php

namespace App\Http\Interfaces;


interface  CurrencyInterface
{
    public function getCountryAndCurrencies(int $appId);
}
