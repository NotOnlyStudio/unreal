<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'photos',
        'title',
        'alias',
        'user_id',
        'tags',
        'category_id',
        'subcategory_id',
        'props',
        'filename',
        'is_vr'
    ];
    public function userAssessment(){
        return $this->morphOne(Like::class, 'likeable');
     }
     public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }
    public function users(){
        $columns = Schema::getColumnListing('users');
        $columns = array_diff($columns, ['email']);

        return $this->belongsTo(User::class,'user_id','id')->select($columns);
    }

    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }

    public function comments(){
        return $this->morphMany(Comment::class,"commentable");
    }

    public function productsForUser(){
        $user = Auth::id();
        return $this->bookmarks()->where("user_id","=",$user);
    }

    public function userBookmarks(){
        return $this->bookmarks()->where("user_id","=",Auth::id());
    }

    public function category(){
        return $this->hasOne(Category::class, "id", "category_id");
    }

    public function subcategory(){
        return $this->hasOne(Category::class, "id","subcategory_id");
    }

    public function purchases(){
        return $this->belongsTo(Purchase::class);
    }

    public function purchasesCc(){
        $row = Purchase::find($this->id);
        return is_null($row) ? '456':'789';
    }

    public function userPurchases(){
        $user_id = Auth::check() ? Auth::id() : -1;
        return $this->belongsTo(Purchase::class,"id","product_id")->where("user_id","=",$user_id);
    }

    public function apiPurchases(){
        $_token = null;
        $request = Request();
        $user_id = -1;
        if($request->hasHeader('Authorization')){
            $_token =  $request->header('Authorization');
            $_token = str_replace('Bearer ','',$_token);
            $user_id = \App\Models\User::where('api_token',$_token)->first()->id;
        }

        return $this->belongsTo(Purchase::class,"id","product_id")->where("user_id","=",$user_id);
    }
}
