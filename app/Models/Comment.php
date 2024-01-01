<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use Auth;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,"id","user_id");
    }
    public function parent(){
        return $this->hasOne(Comment::class,'id',"parent_id");
    }
    public function likes(){
        return $this->morphMany(Like::class, 'likeable')->where("like","=",1);
    }
    public function dislikes(){
        return $this->morphMany(Like::class, 'likeable')->where("like","=",0);
    }
    public function userAssessment(){
        $user_id = -1;
        $user_id = Auth::check() ? Auth::id() : $user_id;
        return $this->morphOne(Like::class, 'likeable')->where("user_id","=", $user_id );
    }
}
