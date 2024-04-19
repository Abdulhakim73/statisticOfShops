<?php

namespace App\Services;

use App\Models\Currency;
use GuzzleHttp\Client;
use App\Http\Interfaces\CurrencyInterface;
use GuzzleHttp\Exception\GuzzleException;

class getCurrencyDriver implements CurrencyInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://openexchangerates.org/api/',
        ]);
    }

    public function getResponse($appId)
    {
        try {
            $response = $this->client->request('GET', "currencies.json?app_id={$appId}");
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return null;
        } catch (GuzzleException $e) {
        }
    }

    public function getCountryAndCurrencies($appId): void
    {
        $currencies = $this->getResponse($appId);

        if ($currencies) {
            $currencyTable = '';

            foreach ($currencies as $code => $name) {
                $currencyTable .= "$name\n";
            }

            $currencyArray = explode("\n", $currencyTable);

            foreach ($currencyArray as $currency) {
                $words = explode(" ", $currency);
                $CountryCurrency = end($words);
                array_pop($words);
                $CountryName = implode(" ", $words);

                $db = new Currency();
                $db->currency = $CountryCurrency;
                $db->country = $CountryName;
                $db->save();
            }
        }
    }
}
