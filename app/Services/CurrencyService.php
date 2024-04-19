<?php

namespace App\Services;

use App\Http\Interfaces\CurrencyInterface;

class CurrencyService
{
    public function __construct(public CurrencyInterface $getCurrency)
    {
    }

    public function getCountryAndCurrency(int $appId): void
    {
        $this->getCurrency->getCountryAndCurrencies($appId);
    }
}
