<?php

namespace App\Action\Payment;

use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentStoreAction
{
    public function handle($count, $uuid, $price)
    {
        Payment::query()->create([
            "user_id"=> auth()->id(),
            "payment_key"=> $uuid,
            "counts"=> $count,
            "price"=> $price,
        ]);
    }
}
