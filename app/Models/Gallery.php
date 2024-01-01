<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Gallery extends Model
{
    use HasFactory;
    protected $guarded =[];
    
    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }
    
    public function user(){
        return $this->hasOne(User::class,"id","user_id");
    }
    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }
    public function userAssessment(){
       return $this->morphOne(Like::class, 'likeable');
    }
    public function galleryForUser(){
        return $this->bookmarks()->where("user_id","=",Auth::id());
    }
    public function challengePosition(){
        return $this->hasOne(ChallengeWinner::class,"gallery_id","id");
    }
    public function userBookmarks(){
        return $this->bookmarks()->where("user_id","=",Auth::id());
    }
    public function comments(){
        return $this->morphMany(Comment::class,"commentable");
    }
}
