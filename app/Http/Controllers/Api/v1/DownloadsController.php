<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\DownloadService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{

    public function buyModel(Request $request, DownloadService  $services, ProductService $productService, PurchaseService $purchaseService)
    {
        return $services->download($request->all(), $productService, $purchaseService);
    }

    public function downloadFreeModel($id)
    {
        $user = Auth::user();

        if ($user->free_models > 0) {
            $product = Product::where("id", $id)->select("id", "filename", "user_id")->first();
            $author = $product->user_id;
            $folder = "models/user-" . $author . '/' . $product->filename;
            $zip = new ZipArchive;
            $private = Storage::disk("private");
            $files = $private->allFiles($folder);
            $folder .= "/";
            $file = $product->filename . '.zip';
            $path = storage_path("app/private/models/tmp/$file");
            if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = str_replace($folder, "", $value);
                    $zip->addFile(storage_path("app/private/" . $value), $relativeNameInZipFile);
                }
                $zip->close();
            }

            $headers = array(
                'Content-Type: archive/zip',
            );

            $user->update([
                'free_models_downloaded' => $user->free_models_downloaded . "," . $id
            ]);
            $user->decrement("free_models");
            return Response::download($path, $file, $headers);
        } else {
            return response()->json(["error" => "user dont have free models to download today"], 500);
        }
    }
}
