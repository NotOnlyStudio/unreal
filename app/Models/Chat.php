<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user_one()
    {
        return $this->hasOne(User::class, "id", "user_1")
            ->select("id", "rating", "name", "photo", "rang_id")
            ->where("id", "!=", Auth::id());
    }

    public function user_two(){
        return $this->hasOne(User::class,"id","user_2")
            ->select("id","rating","name","photo","rang_id")
            ->where("id","!=",Auth::id());
    }

    public function lastmessage(){
        return $this->hasMany(Messange::class,"chat_id","id")
            ->select("chat_id","message_body","created_at")
            ->latest()
            ->take(1);
    }
}
