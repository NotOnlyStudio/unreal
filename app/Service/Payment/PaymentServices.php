<?php

namespace App\Service\Payment;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PaymentServices
{
    public function store(int $product_id, string $token): void
    {
        Payment::query()->create([
            'user_id' => Auth::user()->id,
            'product_id' => $product_id,
            'token' => $token,
            'amount' => Product::query()->where('id', $product_id)->first()->price,
            'status' => 1
        ]);
    }
}
