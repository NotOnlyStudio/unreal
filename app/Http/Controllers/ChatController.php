<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messange;
use Auth;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public $to_user;
    public $current_user;
    public $chat_id;

    public function newMessages(Request $request){
        $messange = Messange::create([
            'chat_id'=>$request->room_id, //there will be a chat id
            'message_body'=>$request->messange_body,
            'message_from'=>Auth::id(),
        ]);

        \App\Events\PrivateChat::dispatch($messange);
        \App\Models\NotificationCounter::messangesCounter($request->to_user);
        Chat::find($request->room_id)->touch();

        return response()->json([
            'messange'=>$messange,
        ]);
    }

    public function readMessanges($channel){
        $id = Auth::id();
        Messange::where("chat_id","=",$channel)->update(["view"=>"1"]);
        \App\Models\NotificationCounter::nullify($id,"messages_count");
        return true;
    }

    public function newChat(Request $request){
        $this->current_user = Auth::id();
        $this->to_user = $request->user_id;
        if(Chat::where(function($query){
            $query->where("user_1","=",$this->to_user)
            ->where("user_2","=",$this->current_user);
        })->orWhere(function($query){
            $query->where("user_2","=",$this->to_user)
            ->where("user_1","=",$this->current_user);
        })->count() == 0){
            Chat::create([
                "user_1"=>$this->current_user,
                "user_2"=>$this->to_user,
            ]);
        }



        return response()->json([
            "success"=>true,
        ],200);
    }

    public function getMessages(Request $request){
        // $this->to_user = $request->get("touser");
        $this->current_user = Auth::id();
        $this->chat_id = $request->get("channel");
        $counts = DB::select("
            SELECT (SELECT COUNT(*) FROM `messanges`  WHERE `chat_id` = '".$this->chat_id."'  ) as count_messanges,
            (SELECT COUNT(*) FROM `messanges`  WHERE `chat_id` = '".$this->chat_id."' AND `view` = '0' ) as to_view
        ");
        $messanges = Messange::where("chat_id","=",$this->chat_id)->take(30)->get();
        Messange::where("chat_id","=",$this->chat_id)->update([
            "view"=>true
        ]);
        $guest = explode(",",$request->get("users"));
        $user = Auth::user();
        $guest = $this->current_user == $guest[0] ? $guest[1] : $guest[0];
        \App\Models\NotificationCounter::nullify($user->id,"messages_count");
        $guest = \App\Models\User::where("id","=",$guest)->select("id","name","photo")->first();
        return response()->json([
            'messanges'=>$messanges,
            'channel_id'=>$this->chat_id,
            "countMessanges"=>$counts[0]->count_messanges,
            'current_user'=>$user,
            "view_counts"=>$counts[0]->to_view,
            "guest"=>$guest,
        ]);
    }

    public function getMoreMessages(Request $request){
        $this->current_user = Auth::id();
        $this->chat_id = $request->get("channel");
        return response()->json([
            'messanges'=>Messange::where("chat_id","=",$this->chat_id)->skip($request->get("messanges-count"))->take(30)->get(),
        ]);
    }
}
