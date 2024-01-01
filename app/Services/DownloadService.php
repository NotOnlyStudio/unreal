<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DownloadService
{
    public function download(array $data, ProductService $productService, PurchaseService $purchaseService)
    {
        $product = $productService->findProduct($data['product_id']);

        if (!$product) {
            return response()->json(["error" => "Product not found"], 404);
        }

        if ($product->is_free != 1) {
            if (!$purchaseService->checkPurchaseExists(Auth::id(), $data['product_id'])) {
                if (Auth::user()->models_count != 0) {
                    $full_price = $data['price'] * config("app.payments.price");
                    $final_price = ($full_price * 90) / 100;

                    $purchaseService->makePurchase(Auth::id(), $data['product_id'], $final_price);
                    $purchaseService->updateWallet($product->user_id, $final_price);

                    Auth::user()->decrement("models_count");

                    return response()->json(["answer" => true]);
                } else {
                    return response()->json(["answer" => "mod"], 402);
                }
            }
        } else {
            if (Auth::user()->free_models != 0) {
                $zipPath = $productService->createZipForProduct($product);

                if ($zipPath) {
                    Auth::user()->decrement("free_models");
                    return Response::download($zipPath, $data['filename'] . '.zip', ['Content-Type' => 'archive/zip'])->deleteFileAfterSend();
                } else {
                    return response()->json(["error" => "Failed to create ZIP file"], 500);
                }
            } else {
                return response()->json(["error" => "user doesn't have free models to download today"], 500);
            }
        }

        return response()->json(["answer" => "Too many models"], 409);
    }
}
