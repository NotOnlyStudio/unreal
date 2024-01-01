<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Auth;

class NotificationController extends Controller
{
    public function notifications(){
        $user = Auth::id();
        return Notification::where("user_id","=",$user)
        ->with("sender:id,name")->take(50)->get();
    }
}
