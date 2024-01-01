<?php

namespace App\Models\Plugin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    use HasFactory,HasApiTokens;
    protected $table = 'users';
    
    public function rang(){
        return $this->hasOne(Rang::class,"rang_id","id");
    }
}
