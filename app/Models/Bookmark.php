<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function gallery()
    {
        return $this->morphedByMany(Gallery::class, 'productable');
    }
    public function products(){
        return $this->morphedByMany(Product::class, 'productable');
    }
    public function productsForUser(){
        if(isset($_COOKIE['user'])){
            $user = json_decode(User::getCookie())->id;
             return $this->products()->where("user_id","=",$user);
        }

    }
    public function productByUser(){
        $user = -1;
        if(isset($_COOKIE['user'])){
            $user = json_decode(User::getCookie())->id;
        }
        return $this->products()->where("user_id","=",$user);
    }
}
