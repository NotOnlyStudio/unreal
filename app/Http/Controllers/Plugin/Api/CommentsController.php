<?php

namespace App\Http\Controllers\Plugin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentsController extends Controller
{
    public function getComments(Request $request)
    {
        $comments = Comment::with(["user","parent.user"])
        ->where("commentable_type","=",$request->get("type"))
        ->where("commentable_id","=",$request->get("itemId"))
        ->orderBy("created_at","desc")
        ->with([
            "user:id,name,photo",
            "userAssessment",
            "parent"=>function($query){
                $query->with("user:id,name");
            }
        ])
        ->withCount(["likes","dislikes"])
        ->paginate(20);
        if(count($comments) != 0){
            return response()->json([
                "comments"=>$comments
            ]);
        }else{
            return response()->json([
                "Message"=>"Model don't have comments"
            ],204);
        }
        return response()->json([
            "Error" => "Undefined error"
        ],500);

    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $comment = Comment::create([
            'comment_body'=>$request->comment_body,
            'user_id'=>$user->id,
            'commentable_id'=>$request->itemId,
            'commentable_type'=>$request->commentType,
            'parent_id'=>$request->parent ? $request->parent : null 
        ]);
        
        if($request->commentType == "App\Models\Topic")
            \App\Models\NotificationCounter::incrementForum($request->itemUser);

        if($comment){
            return response()->json([
                'comment'=>$comment,
                'user'=>$user,
                'id'=>$comment->id,
            ],200);
        }
        return response()->status(500);

    }
}
