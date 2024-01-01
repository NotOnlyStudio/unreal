<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Forum;
use Auth;

class ForumSection extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function forums(){
        return $this->hasMany(Forum::class);
    }

    public function bookmarksForums(){
        $forum = new Forum();
        return $forum->userBookmarks();
    }
}
