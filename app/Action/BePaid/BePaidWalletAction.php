<?php

namespace App\Action\BePaid;

use App\Models\BalanceProject;
use Illuminate\Support\Facades\Http;

class BePaidWalletAction
{
    public function handle(int $number, int $month, int $year)
    {

        $payloadArray = [
            'request' => [
                'number' => $number,
//                'holder' => $name,
                'exp_month' => $month,
                'exp_year' => $year,
                'contract' => ['recurring']
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->withBasicAuth(1177, '115544832cb7fbf6eaf51c2b0643165882db0b3c7b86971c0473ce7ea7c35f50')
            ->post('https://gateway.bepaid.tech/credit_cards', $payloadArray);

        return $response->json();
    }
}
