<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{   
    public function addReward(Request $request){
        $icon = $request->icon->getClientOriginalName();
        $request->icon->storeAs(
            "public/rewards",$icon
        );
        $reward = Reward::create([
            "title"=>$request->title,
            "icon"=>$request->icon,
        ]);
        if($reward){
            return response()->json([
                "reward"=>$reward
            ],200);
        }
        return response()->json([
            "failed"=>true
        ],500);
    }  

    public function editReward(){

    }
}
