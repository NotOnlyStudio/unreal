<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class CommentsController extends Controller
{

    public function getAdminComments(){
        return response()->json([
            "comments"=>Comment::query()
            ->with(["user:id,name","parent:id"])
            ->paginate(30)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getComments(Request $request)
    {
        return Comment::with(["user","parent.user"])
        ->where("commentable_type","=",$request->get("type"))
        ->where("commentable_id","=",$request->get("itemId"))
        ->with([
            "user:id,name,photo",
            "userAssessment",
            "parent"=>function($query){
                $query->with("user:id,name");
            }
        ])->orderBy("created_at","asc")
        ->withCount(["likes","dislikes"])
        ->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user){
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
        }else{
            return response()->json([
                "error"=>"Unauth"
            ],200);
        }
      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $comment = Comment::find($id);
        $comment->comment_body = $request->text;
        $comment->save();
        return response()->json([
            "true"=>true
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::where("id","=",$id)->delete();
        return response()->json([
            "success"=>true
        ]);
    }
}
