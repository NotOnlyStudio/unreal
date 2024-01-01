<?php

namespace App\Action\Payment;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentUpdateUserAction
{
    public function handle(int $count, string $uuid)
    {
        $user_id = Payment::query()->where('payment_key', $uuid)->first();
        User::query()->where('id', $user_id->user_id)->increment('models_count', $count);
    }
}
