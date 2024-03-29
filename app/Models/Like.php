<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = "likeables";
    protected $fillable = [
        'likeable_type',
        'like',
        'likeable_id',
        'user_id',
    ];

    public function likeable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(App\Models\User::class,"user_id");
    }
}
