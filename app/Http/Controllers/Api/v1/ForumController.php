<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumSection;
use App\Models\NotificationCounter;
use Auth;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public static $user_id;
    public $search_title;

    public function getPage(){
        return view("forum")
        ->with("forum",ForumSection::with(["forums"=>function($query){
            $query->where("showing","=",true)->withCount(["topics"=>function($query){
                $query->where("moderate",true);
            },"userBookmarks"])->with("userBookmarks");
        }])->where("showing","=",true)->orderBy("id","desc")->get());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Forum::query();
        return $query->get();
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
        if($request->parent != "none" && $request->title){
            $forum = Forum::create(
                [
                    'title'=>trim($request->title),
                    'alias'=>mb_strtolower(str_replace([" ","/","="],"-",trim($request->title))),
                    'forum_section_id'=>$request->parent,
                ]
            );
            if($forum){
                return response()->json([
                    'success'=>true,
                    'forum'=>$forum
                ]);
            }
            return response()->json([
                'success'=>false
            ]);
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
    public function edit($id)
    {
        //
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
        $forum = Forum::find($id);
        if($request->title) $forum -> title = $request->title;
        if($request->parent) $forum->forum_section_id = $request->parent;
        $forum->save();

        if($forum){
            return response()->json([
                'success'=>true,
                'forum'=>$forum,
            ]);
        }

        return response()->json([
            'success'=>false
        ]);
    }

    /**
     * Updating field `showing` in record
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function changeShowing(Request $request, $id){
        $forum = Forum::find($id);
        $forum -> showing = $request->showing;
        $forum -> save();
        if($forum){
            return response()->json([
                'success'=>true,
            ]);
        }

        return response()->json([
            'success'=>false
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Forum::where("id",'=',$id)->delete();
            return response()->json([
                'success'=>true,
            ]);
        }
        catch(Exception $ex){
            return responce()->json([
                'success'=>false,
                'error'=>$ex
            ]);
        }
    }

    /**
     *
     * Get comments for user articles on forum
     *
     * @return \Illuminate\Http\Response
     *
     */
    public static function forumGet($userNoOwner = null)
    {
        if (is_null($userNoOwner)) {
            self::$user_id = Auth::id();
        } elseif ($userNoOwner != Auth::id()) {
            self::$user_id = $userNoOwner;
        }

        $forum = Forum::query();
        $forum->whereHas("topics", function ($query) {
            $query->where("user_id", "=", self::$user_id);
        });

        $forum->with(["topics" => function ($query) {
            $query->where("user_id", "=", self::$user_id)->with(["comments" => function ($query) {
                $query->with("user.rang")->with("parent", function ($query) {
                    $query->with("user");
                })->limit(30);
            }]);
        }]);
        NotificationCounter::nullify(Auth::id(), "forum_count");

        if ($userNoOwner) {
            return view("user/forum", ["topics" => $forum->limit(30)->get(), 'userNoOwner' => $userNoOwner]);
        } else {
            return view("cabinet/forum", ["topics" => $forum->limit(30)->get(), 'userNoOwner' => $userNoOwner]);
        }
    }

    public function bookmarksPage(Request $request){
        $sections = ForumSection::query();
        $forums = Forum::query();
        if($request->get("searchBy")){
            $forums->where("title","LIKE","%".$request->searchBy."%");
        }
        $forums->with("userBookmarks:id")->withCount(["topics"=>function($query){
            $query->where("moderate",true);
        }])->has("userBookmarks");

        return view("cabinet/bookmarks/forum",[
            "forums"=>$forums->paginate(12),
            "sections"=>$sections->get(),
        ]);
    }
}
