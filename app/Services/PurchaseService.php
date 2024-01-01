<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

class PurchaseService
{
    public function makePurchase($userId, $productId, $price)
    {
        Purchase::query()->create([
            "user_id" => $userId,
            "product_id" => $productId,
            "price" => $price,
        ]);
        Log::info("PurchaseService: Совершена покупка для пользователя с ID: {$userId}, продукт ID: {$productId}");
    }

    public function updateWallet($userId, $amount)
    {
        Wallet::query()
            ->where('user_id', $userId)
            ->increment('bill', $amount);
        Log::info("PurchaseService: Кошелек обновлен для пользователя с ID: {$userId}, сумма: {$amount}");

    }

    public function checkPurchaseExists($userId, $productId): bool
    {
        $exists = Purchase::query()
            ->where("user_id", $userId)
            ->where("product_id", $productId)
            ->exists();
        Log::info("PurchaseService: Проверка наличия покупки для пользователя с ID: {$userId}, продукт ID: {$productId}, наличие: {$exists}");
        return $exists;
    }
}
