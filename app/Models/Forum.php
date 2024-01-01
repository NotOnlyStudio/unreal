<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class Forum extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function section(){
        return $this->belongsTo(ForumSection::class,'id');
    }
    public function topics(){
        return $this->belongsTo(Topic::class,"id","forum_id");
    }
    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }
    public function userBookmarks(){
        return $this->morphMany(Bookmark::class, 'productable')->where("user_id","=",Auth::id());
    }
    public function forumsForUser(){
        if(Auth::check()){
            $user = Auth::id();
            return $this->bookmarks()->where("user_id","=",$user);
        }

    }
}
