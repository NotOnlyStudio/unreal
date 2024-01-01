<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProductService
{
    public function findProduct($productId)
    {
        Log::info("ProductService: Найден продукт с ID: {$productId}");
        return Product::query()->find($productId);
    }

    public function createZipForProduct($product): ?string
    {
        $zip = new ZipArchive;
        $zipPath = storage_path("app/private/models/tmp/" . $product->filename . '.zip');

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach (Storage::disk('private')->allFiles("models/user-" . $product->user_id . '/' . $product->filename) as $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile(storage_path("app/private/" . $value), $relativeNameInZipFile);
            }
            $zip->close();
            Log::info("ProductService: Создан zip-архив для продукта с ID: {$product->id}");
            return $zipPath;
        }

        Log::error("ProductService: Не удалось создать zip-архив для продукта с ID: {$product->id}");
        return null;
    }
}
