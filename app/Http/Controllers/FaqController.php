<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class FaqController extends Controller
{
    public function faqControl(Request $request){
        DB::table("faq")->truncate();
        DB::table("faq")->insert($request->all());
        return response()->json([
            "status"=>true
        ]);
    }

    public function getFaq(){
        return response()->json(
            [
                "faq"=>Cache::get("faq", function(){
                    return DB::table("faq")->get();
                }, now()->addMinutes(1440))
            ]
        );
    }
    public function getFaqPage(){
        return view("faq")->with("faq",DB::table("faq")->get());
    }
}
