<?php

namespace App\Http\Controllers\Api\v1\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function currency()
    {
        $response = Http::get('https://www.cbr.ru/scripts/XML_daily.asp');
        $xml = simplexml_load_string($response->body());
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        foreach ($array['Valute'] as $valute) {
            if ($valute['CharCode'] == 'USD') {
                $usdToRub = str_replace(',', '.', $valute['Value']);
                return response()->json([
                    'usd' => intval($usdToRub)
                ]);
            }
        }
    }
}
