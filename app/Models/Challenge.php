<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function galleries(){
        return $this->hasMany(Gallery::class,"challenge_id");
    }
}
