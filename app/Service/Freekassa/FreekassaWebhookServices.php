<?php

namespace App\Service\Freekassa;

use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FreekassaWebhookServices
{
    public function webhook(array $data)
    {
        Storage::put('test.txt', print_r($data, 1));
        $find = Payment::query()->where('token', $data['us_sign'])->first();
        $product = Product::query()->where('id', $find->product_id)->first();

        if ($find->status == 1) {
            if (intval($find->amount) == intval($data['AMOUNT'])) {
                $user = User::query()->find($product->user_id);
                $user['balance'] += intval($find->amount);
                $user->save();

                $product['sell'] += 1;
                $product->save();

                $find->update(['status' => 3]);
            }
        }

        return 200;
    }
}
