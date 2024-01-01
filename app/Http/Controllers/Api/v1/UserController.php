<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Chat;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{
    public function startChatting(Request $request)
    {
        $user = Auth::id();
        $user_to = $request->get('id');

        if(Chat::where(function($query) use ($user, $user_to){
                $query->where("user_1","=",$user_to)
                    ->where("user_2","=",$user);
            })->orWhere(function($query) use ($user, $user_to){
                $query->where("user_2","=",$user_to)
                    ->where("user_1","=",$user);
            })->count() == 0){
            Chat::create([
                "user_1"=>$user_to,
                "user_2"=>$user,
            ]);
        }

        return  $request->get('id');
    }

    public function getLogs(Request $request)
    {
        $purchases = Purchase::join('users', 'purchases.user_id', '=', 'users.id')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->orderBy("purchases.created_at","desc")
            ->select('purchases.product_id as product id', 'products.alias as url', 'users.name as user name', 'purchases.price', 'purchases.created_at as date of purchase')->get();
        return $purchases;
    }
}
