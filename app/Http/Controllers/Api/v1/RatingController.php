<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use Auth;

class RatingController extends Controller
{
    /**
     * Edit or create new rating
     * 
     * @return Response
     */
    public function rating(Request $request){
        $user = Auth::user();
        if($user):
            $rating_type = $request->ratingType;
            $like = Like::where("likeable_type",'=',$request->type)
            ->where('likeable_id','=',$request->itemId)
            ->where("user_id",'=',Auth::id())->first();
            if(
                $like != ""
            ):
                if($like->like == $rating_type){
                    $like->delete();
                    $rating_type = null;
                }
                else{
                    $like->like = $rating_type;
                    $like->save();
                }

            else:
                $like = Like::create([
                    'user_id'=>$user->id,
                    'likeable_id'=>$request->itemId,
                    "like"=>$rating_type,
                    "likeable_type"=>$request->type,
                ]);

            endif;
            if($rating_type == 0 || $rating_type == 1){

                Notification::create([
                    "user_id"=>$request->author,
                    "from_user"=>$user->id,
                    "message"=>$rating_type == true ? "+ rating" : "- rating",
                    "message_from"=>$request->content,
                ]);
                $user = User::find($request->author);

                if($rating_type == true){
                $user->increment("rating");
                }else{
                    if($user->rating > 0){
                        $user->rating = $user->rating-1;
                    }
                }

                \App\Models\NotificationCounter::notificationsCount($request->author);
            }
            // User
            if($request->author){
                $user = User::find($request->author);
                $user->rang_id = \App\Models\Rang::whereBetween("balls",[$user->rating, 30000])->get()->first()->id-1;
                $user->save();
            }   
            return response()->json([
                'success'=>true,
                'like'=>$rating_type,
            ],200);
        else:
            return response()->json([
                "auth"=>false
            ],203);
        endif;
    }
  
}
