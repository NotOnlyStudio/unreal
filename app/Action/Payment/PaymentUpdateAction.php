<?php

namespace App\Action\Payment;

use App\Models\Payment;

class PaymentUpdateAction
{
    public function handle(string $uuid)
    {
        Payment::query()->where('payment_key', $uuid)->update(['status' => 1]);
    }
}
