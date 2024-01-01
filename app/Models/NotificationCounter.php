<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCounter extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function incrementForum($user){
        self::where("user_id","=",$user)->increment("forum_count");
    }
    public static function messangesCounter($user){
        self::where("user_id",$user)->increment("messages_count",1);
    }
    public static function notificationsCount($user){
        self::where("user_id",$user)->increment("notifications_count",1);
    }
    public static function nullify($user,$field){
        self::where("user_id",$user)->update(["$field"=>0]);
    }
    public static function getCountsData($user){
        $counts = array();
        $countsTmp = self::where("user_id",$user)->select("messages_count","forum_count","profit_count","notifications_count")->first();

        if ($countsTmp){
            $counts = $countsTmp->ToArray();
            $all = 0;
            foreach($counts as $count){
                $all += $count;
            }
            $counts['summary'] = $all;
        }
        
        return $counts;
    }
}
