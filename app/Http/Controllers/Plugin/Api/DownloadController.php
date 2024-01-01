<?php

namespace App\Http\Controllers\Plugin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use \App\Models\User;
use ZipArchive;

class DownloadController extends Controller
{
    public function download($id){
        $model = \App\Models\Purchase::query();
        $user = Auth::user();
        $model->where("user_id","=",$user->id);
        $model->where("product_id","=",$id);
        $permisions = Auth::check() ? Auth::user()->permisions : "USER";

        if($model->count() != 0){
            $product = $model->with("product:id,user_id,filename")->get()->first()->product;
            $author = $product->user_id;
            $folder = "models/user-".$author.'/'.$product->filename;
            $zip = new ZipArchive;
            $private = Storage::disk("private");
            $files = $private->allFiles($folder);
            $folder .= "/";
            $file = $product->filename.'.zip';
            $path = storage_path("app/private/models/tmp/$file");
            if ($zip->open($path, ZipArchive::CREATE) === TRUE)
            {
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = str_replace($folder,"",$value);
                    $zip->addFile(storage_path("app/private/".$value), $relativeNameInZipFile);
                }
                $zip->close();
            }
            $headers = array(
                'Content-Type: archive/zip',
            );
            return \Illuminate\Support\Facades\Response::download($path, $file, $headers)->deleteFileAfterSend();
        }else{
            return response()->json([
                "Message" => "Model does not was selled to this user"
            ],403);
        }
    }
}
