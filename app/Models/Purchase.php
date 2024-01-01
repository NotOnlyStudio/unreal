<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded =[];


    /**
     * Make a payment
     *
     * @return array
     */
    public static function unlimintTransfer(){

    }

    public function product(){
        return $this->hasOne(Product::class,"id","product_id");
    }

    public function productBb(){
        return $this->belongsTo(Product::class,"product_id","id");
    }


    public function user(){
        return $this->belongsTo(User::class,"id","user_id");
    }

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function bookmarks(){
        return $this->morphMany(Bookmark::class, 'productable');
    }

    public function userBookmarks(){
        return $this->product()->bookmarks()->where("user_id","=",Auth::id());
    }

    public function likes(){
        return $this->productBb->morphMany(Like::class, 'likeable');
    }
}
