<?php

namespace App\Http\Controllers\Api\v1\Payment;

use App\Http\Controllers\Controller;
use App\Service\Freekassa\FreekassaServices;
use App\Service\Freekassa\FreekassaWebhookServices;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function generate(int $alias, FreekassaServices $services)
    {
        return $services->freekassa($alias);
    }

    public function freekassaWebhook(Request $request, FreekassaWebhookServices $services)
    {
        return $services->webhook($request->all());
    }
}
