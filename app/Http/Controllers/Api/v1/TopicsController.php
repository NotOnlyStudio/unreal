<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Auth;

class TopicsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store',"moderateArticle","update","destroy"]);
    }
    public $parrent;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display all result from search in the forum
     * 
     * @return \Illuminate\Http\Response
     */
    public function getResultsByTitle($title, Request $request){
        $topics = Topic::where("title","LIKE","%$title%")->orWhere("tags","LIKE","%".$request->searchBy."%")
        ->with(["parent","user"])->where("moderate","=",true)
        ->paginate(10);
        if($request->api && $request->api == true):
            return response()->json([
                "searchData"=>$topics
            ]);
        else:
            return view("forum.search")->with("searchData",$topics);
        endif;
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
        $forum = $request->forum;
        $user = Auth::user();
        if($forum && $user){
            $forum = json_decode($forum);
            $alias = trim(mb_strtolower(str_replace(" ","-",trim($request->title))));
            $count = Topic::where("title","=",$request->title)->count();
            if($count > 0){
                $count+=1;
                $alias = $count."-".$alias;
            }
                
            Topic::create([
                'title'=>trim($request->title),
                'alias'=>$alias,
                'content'=>$request->content,
                'tags'=>trim($request->tags),
                'forum_id'=>$forum->id,
                'user_id'=>$user->id,
            ]);
            return response()->json([
                'success'=>true,
                'alias'=>$alias,
                'forum'=>$forum->alias,
            ],200);

        }else{
            return response()->json([
                'error'=>true,
            ],500);
        }
    }

    public function getTopicsPage($alias){
        $info = Forum::query()->where("alias","=",$alias)->select("id","title")->get()->first();
        
        $this->parrent = $alias;
        $topics =  Topic::where("forum_id","=",$info->id)
        ->with([
            "user","parent"=>function($query){
                $query->select("id","title");
            }
        ])
        ->withCount("comments")
        ->where("moderate", true)
        ->paginate(15);
        return view("topics",[
            "topics"=>$topics,
            "info"=>$info,
        ]);
    }

    /**
     * view topic page
     * 
     * @return View
     */
    public function getTopicPage($sectionAlias,$alias){
        if($sectionAlias != "search"):
            Topic::where("alias","=",$alias)
            ->increment("views");
            $topic = Topic::query()->where("alias","=",$alias)->with([
                "user" => function($query){
                    $query->select("id","name","photo");
                },
                "userAssessment"=>function($query){
                    if(Auth::check()){
                        $user_id = Auth::id();
                        $query->where("user_id","=",$user_id);
                    }else{
                        $query->where("user_id","=","-1");

                    }
                }
            
            ])->where("moderate", true)->withCount(["likes AS likes_count" => function($query){
                    $query->where("like","=",true);
                },"likes as dislikes_count" => function($query){
                    $query->where("like","=",false);
                }])->withCount(["userBookmarks"])->get();
            return view("topic",[
                "topic"=>$topic->first()
            ]);
        else:
            
        endif;
    }   

    public function topicsByParent(Request $request){


        if($request->get("parent")){
            $topics = Topic::query();
            $this->parrent = $request->get("parent");
            return $topics->orderBy("created_at","desc")->where("moderate", true)->whereHas("parent", function(Builder $query){
                $query->where("alias",$this->parrent);
            })->with([
                "user","parent"=>function($query){
                    $query->select("id","title");
                }
            ])->paginate(15);
        }
        return "Parent not found";
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $alias
     * @return \Illuminate\Http\Response
     */
    public function show($alias)
    {
        Topic::where("alias","=",$alias)
        ->increment("views");
        return Topic::query()->where("moderate", true)->where("alias","=",$alias)->with([
            "user" => function($query){
                $query->select("id","name","photo");
            },
            "userAssessment"=>function($query){
                if(Auth::check()){
                    $user_id = Auth::id();
                    $query->where("user_id","=",$user_id);
                }else{
                    $query->where("user_id","=","-1");

                }
            }
            ])->withCount(["likes AS likes_count" => function($query){
                $query->where("like","=",true);
            },"likes as dislikes_count" => function($query){
                $query->where("like","=",false);
            }])->withCount(["userBookmarks"])->get();
    }

    public function moderateArticle($id){
        $blog = Topic::find($id);
        $blog->moderate = !$blog->moderate;
        $blog->save();
        return response()->json([
            'success'=>true
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $title = $request->title;
        $alias = trim(mb_strtolower(str_replace(" ","-",trim($request->title))));
        $count = Topic::where("title","=",$request->title)->where("id","!=",$id)->count();
        if($count > 0){
            $count+=1;
            $alias = $count."-".$alias;
        }
        $topic = Topic::find($id)->first();
        $topic->title = $title;
        $topic->alias = $alias;
        $topic->tags = $request->tags;
        $topic->content = $request->content;
        $topic->meta = $request->meta;
        $topic->save();
        
        return response()->json([
            "item"=>$topic
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Topic::where("id",$id)->delete();
        return true;
    }

    public function getUploadPage($alias){
        $section = Forum::where("alias","=",$alias)->select("title","id","alias");
        return view("uploads/topic",[
            "alias"=>$alias
        ])->with("forum",$section->get()->first());
    }

    public function adminTopics(){
        return response()->json(Topic::query()->orderBy("created_at","desc")->paginate(15));
    }
}
