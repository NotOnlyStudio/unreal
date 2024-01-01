<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Product;
use Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function checkAdministration($user){
        if($user->permisions == "ADMIN" || $user->permisions == "MODERATOR")
            return true;
        else
            return false;
    }

    public function comments(){
        return $this->hasMany(Comment::class,'user_id');
    }
    public static function getCookie(){
        if(isset($_COOKIE['user']) && $_COOKIE['user'] != null){
            return $_COOKIE['user'];
        }
        return 0;
    }
    public function products(){
        return $this->hasMany(Product::class,'user_id');
    }

    public function bookmarks(){
        return $this->morphToMany(Bookmark::class, 'user_id');
    }

    public function rang(){
        return $this->hasOne(Rang::class,"id","rang_id");
    }


    public function gallery(){
        return $this->belongsTo(Gallery::class,"id","user_id");
    }


    public function chats(){
        return $this->hasMany(Chat::class,"user_2","id");
    }

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }

    public function setMoneyAttribute($value){
        $this->attributes['money'] = $value;
    }

    public function setModelAttribute($value){
        $this->attributes['models_used'] = $value;
    }

    public static function checkModelsCount(){
        return Auth::user()->models_count == 0 ? false : true;
    }

    /**
     * Return count moneys in wallet
     */
    public function wallet(){
        return $this->hasOne(Wallet::class)/*->select("account")*/;
    }


    //-----------
    /**
     * Отправка уведомления о сбросе пароля.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPasswordNotification($token));
    }
}
