<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avards;
use App\Models\Gallery;

class AvardsController extends Controller
{
    public static $avard_id;

    public static function checkAndAdd(){
        Avards::truncate();
        $top_gallery = Gallery::query()->select("user_id")->withCount("likes")->orderBy("likes_count","desc")->get()->take(15);
        $top_gallery = $top_gallery->map(function($item){
            return collect($item->toArray())
                ->only(["user_id"])
                ->all();
        });
        $avards = Avards::insert($top_gallery->all());
        return response()->json([
            "avards"=>$avards
        ]);
    }

    public function avardBanner($id){
        self::$avard_id = $id;
        return Gallery::where("user_id","=",$id)
        ->with(["user"])->withCount("likes")->orderBy("likes_count","desc")->take(1)->get()->first();
    }

    /**
     * Return avard page
     * 
     * @return View
     */
    public function getAvardPage($id){
        self::$avard_id = $id;
        $avard = Avards::where("user_id","=",$id)
        ->with(["user.gallery"=>function($query){
            $query->where("user_id","=",self::$avard_id)->withCount("likes")->orderBy("likes_count","desc")->take(1);
        },"user:id,name"])->get()->first();
        return view("gallery/gallery-avards",[
            "avard"=>$avard,
            "gallery"=>Gallery::where("user_id","=",$id)->where("moderation","=",true)->with("user")->orderBy("created_at","desc")->paginate(9)
        ]);
    }

    public function getAvardsList(){
        return response()->json([
            "avards"=>Avards::query()->select("user_id","id")->with("user:id,name")->get()
        ]);
    }
}
