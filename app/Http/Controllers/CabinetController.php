<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Auth;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function __construct(){
        $this->middleware(['verify']);
    }
    public function notificationsGet(){
        $notification = \App\Models\Notification::where("user_id","=",Auth::id())
        ->with("sender:id,name")->orderBy("created_at","desc")->take(50)->get();
        \App\Models\NotificationCounter::nullify(Auth::id(),"notifications_count");
        return view("cabinet/notifications",['notification' => $notification]);
    }

    public function messangerGet(){
        $users = Chat::query()
            ->where("user_1", "=", Auth::id())
            ->orWhere("user_2", "=", Auth::id())
            ->with(["user_one" => function ($query) {
                $query->with("rang:id,name");
            }, "user_two" => function ($query) {
                $query->with("rang:id,name");
            }, "lastmessage"])->orderBy("updated_at", "desc")->take(20)->get();

        $chats_count = Chat::query()
            ->where("user_1", "=", Auth::id())
            ->orWhere("user_2", "=", Auth::id())->count();
        
        return view("cabinet/messanger", [
            'users' => $users,
            "counts" => $chats_count
        ]);
    }

    public function getMoreChannels(Request $request){
        $users = Chat::query()
        ->where("user_1","=",Auth::id())
        ->orWhere("user_2","=",Auth::id())
        ->with(["user_one"=>function($query){
            $query->with("rang:id,name");
        },"user_two"=>function($query){
            $query->with("rang:id,name");
        },"lastmessage"])->orderBy("updated_at","desc")->skip($request->get("used-channels"))->take(20)->get();
        return response()->json([
            "users"=>$users
        ]);
    }
}
