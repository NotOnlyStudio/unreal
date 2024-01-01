<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Topic extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function parent(){
        return $this->belongsTo(Forum::class,"forum_id", "id");
    }
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }
    public function blogForUser(){
        if(Auth::check()){
            $user = Auth::id();
            return $this->bookmarks()->where("user_id","=",$user);
        }

    }
    public function userBookmarks(){
        $user = -1;
        if(Auth::check()){
            $user = Auth::id();
        }
        return $this->bookmarks()->where("user_id","=",$user);
    }
    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }
    public function userAssessment(){
       return $this->morphOne(Like::class, 'likeable');
    }
    public function comments(){
        return $this->morphMany(Comment::class,"commentable");
    }
}
