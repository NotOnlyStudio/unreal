<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, "author_id", "id");
    }
    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }
    public function blogForUser(){
        return $this->bookmarks()->where("user_id","=",Auth::id());

    }
    public function userBookmarks(){
        return $this->bookmarks()->where("user_id","=",Auth::id());
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
