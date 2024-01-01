<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use Illuminate\Support\Str;
use Auth;

class BookmarksController extends Controller
{
    public function addNew(Request $request){
        $user = Auth::id();
        if(
            Bookmark::where("user_id","=",$user)
            ->where("productable_id","=",$request->product_id)
            ->where("productable_type","=",$request->type)->count() == 0
        ){
            if($request->checked == false){
                Bookmark::create(
                    [
                        'user_id'=>$user,
                        'productable_id'=>$request->product_id,
                        'productable_type'=>$request->type,
                    ]
                );
            }
        }else{
            self::removeByParams($user,$request->type,$request->product_id);
        }
        return response() -> json([
            'success'=> true,
        ]);
    }

    public static function removeByParams($user,$type,$product){
        Bookmark::where("user_id",'=',$user)
        ->where("productable_type","=",$type)
        ->where("productable_id",'=',$product)
        ->delete();
    }

    public function remove(Request $request){
        Bookmark::where("id",'=',$request->id)->where("token",'=',$request->token)->delete();
        return response() -> json([
            'success'=> true,
            'id' => $request->id,
        ]);
    }
}
