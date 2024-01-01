<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Auth;
use Illuminate\Http\Request;


class BlogController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store',"edit","getBookmarks","blogByUser","moderateArticle","destroy"]);
    }

    public function getBlogPage(){
        $blog =  Blog::where("moderation",'!=',0)
        ->with([
            "user" => function($query){
                $query->select("id","name");
            },
            "userAssessment"=>function($query){
                if(Auth::check()){
                    $user_id = Auth::id();
                    $query->where("user_id","=",$user_id);
                }else{
                    $query->where("user_id","=","-1");
                }
            }
        ])
        ->withCount(["likes AS likes_count" => function($query){
                $query->where("like","=",true);
            },"likes as dislikes_count" => function($query){
                $query->where("like","=",false);
        },"userBookmarks"])
       ->orderBy("first","desc")
       ->orderBy("created_at","desc")
       ->paginate(3);

        return view("blog",[
            "blog"=>$blog
        ]);
    }

    public function bookmarksPage(Request $request)
    {
        $blog = Blog::query()->where("moderation", "=", true);
        $blog->has("userBookmarks")
            ->orderBy("created_at", "desc")
            ->with(["userAssessment" => function ($query) {
                if (Auth::check()) {
                    $user_id = Auth::id();
                    $query->where("user_id", "=", $user_id);
                } else {
                    $query->where("user_id", "=", "-1");

                }
            }])->withCount(["likes AS likes_count" => function ($query) {
                $query->where("like", "=", true);
            }, "likes as dislikes_count" => function ($query) {
                $query->where("like", "=", false);
            }]);

        if ($request->get("searchBy")) {
            $blog->where("title", "LIKE", "%" . $request->searchBy . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }

        return view("cabinet/bookmarks/blog")->with("blogs", $blog->paginate(3));
    }

    public function getBookmarks(Request $request)
    {
        $blog = Blog::query()->where("moderation", "=", true);
        $blog->has("userBookmarks")
            ->with(["userAssessment" => function ($query) {
                if (Auth::check()) {
                    $user_id = Auth::id();
                    $query->where("user_id", "=", $user_id);
                } else {
                    $query->where("user_id", "=", "-1");

                }
            }])->withCount(["likes AS likes_count" => function ($query) {
                $query->where("like", "=", true);
            }, "likes as dislikes_count" => function ($query) {
                $query->where("like", "=", false);
            }]);

        if ($request->get("searchBy")) {
            $blog->where("title", "LIKE", "%" . $request->searchBy . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }

        return response()->json([
            "blogs" => $blog->paginate(3)
        ]);
    }

    public function getCabinetBlogPage(Request $request, $userNoOwner = null)
    {
        $path = "cabinet/blog";
        if (is_null($userNoOwner)) {
            $user_id = Auth::id();
        } elseif ($userNoOwner != Auth::id()) {
            $user_id = $userNoOwner;
            $path = "user/blog";
        }

        return view($path, compact('userNoOwner'))
            ->with("blog", Blog::where("author_id", "=", $user_id)
                ->orderBy("created_at", "desc")
                ->with([
                    "user" => function ($query) {
                        $query->select("id", "name");
                    },
                    "userAssessment" => function ($query) use ($user_id) {
                        if (Auth::check()) {
                            $query->where("user_id", "=", $user_id);
                        } else {
                            $query->where("user_id", "=", "-1");
                        }
                    }
                ])->withCount(["likes AS likes_count" => function ($query) {
                    $query->where("like", "=", true);
                }, "likes as dislikes_count" => function ($query) {
                    $query->where("like", "=", false);
                }])->withCount(["userBookmarks"])
                ->paginate(3));
    }

    public function index(Request $request)
    {
        $query = Blog::query()->orderBy("created_at", "desc");
        if ($request->title) {
            $query->where("title", "LIKE", "%" . $request->title . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }

        if ($request->get("count")) {
            return Blog::where("id",'!=',0)->orderBy("created_at","desc")->with([
            "user" => function($query){
                $query->select("id","name");
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
            }])->withCount(["userBookmarks"])
            ->paginate($request->get("count"));
        }

        return Blog::query()->with([
            "user" => function($query){
                $query->select("id","name");
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
            }])->withCount(["userBookmarks"])
            ->paginate(3);
    }

    public function blogByUser(){
        $id = Auth::id();
        $blog = Blog::where("author_id","=",$id)->with([
            "user" => function($query){
                $query->select("id","name");
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
            }])->withCount(["userBookmarks"])
            ->paginate(3);

        return response()->json($blog);
    }

    public function adminBlog(){
        return Blog::query()->orderBy("first", "desc")->orderBy("created_at","desc")->paginate("10");
    }

    public function blogForUser(Request $request)
    {
        $blog = Blog::query();
        if ($request->searchBy) {
            $blog->where("title", "LIKE", "%" . $request->searchBy . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }
        return $blog->whereHas("blogForUser")->withCount(["userBookmarks"])->paginate(3);
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $alias = mb_strtolower(str_replace([" ","/","="],"-",$request->title));
        $alias = str_replace(["&","?","!"],"",$alias);
        $counts = Blog::where("alias","=",$alias)->count();
        if($counts != 0){
            $counts +=1;
            $alias = $counts."-".$alias;
        }

        $article = Blog::create([
            'author_id'=>$user_id,
            'title'=>$request->title,
            'alias'=>$alias,
            'tags'=>$request->tags,
            'content'=>$request->content,
        ]);

        if($article){
            return response()->json([
                'success'=>true,
                'alias'=>$article->alias,
            ]);
        }

        return response()->json([
            'success'=>false,
        ]);
    }

    public function show($alias,$count = 6)
    {
        $item = Blog::where("alias","=",$alias)
        ->with(["user","userAssessment"=>function($query){
            if(Auth::check()){
                $user_id = Auth::id();
                $query->where("user_id","=",$user_id);
            }else{
                $query->where("user_id","=","-1");
            }
        }])->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        }])->withCount(["userBookmarks"])->get()->first();

        return view("blog-item")->with("item",$item);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function editBlog(Request $request){
        $blog = Blog::find($request->id);
        $blog->content = $request->content;
        $blog->meta = $request->meta;
        $blog->title = $request->title;
        $blog->first = $request->first ? true : false;
        if($request->first){
            Blog::where("first",1)->update([
                "first"=>0
            ]);
        }        
        $alias = mb_strtolower(str_replace([" ","/","="],"-",$request->title));
        $alias = str_replace(["&","?","!"],"",$alias);
        $counts = Blog::where("alias","=",$alias)->where("id","!=",$blog->id)->count();
        if($counts != 0){
            $counts +=1;
            $alias = $counts."-".$alias;
        }
        $blog->alias = $alias;

        if($blog->save()){
            return response()->json([
                'success'=>true,
                'item'=>$blog,
            ]);
        }

        return response()->json([
            'success'=>false,
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
        Blog::find($id)->delete();

        return response()->json([
            'success'=>true,
        ],200);
    }


    public function moderateArticle($id){
        $blog = Blog::find($id);
        $blog->moderation = !$blog->moderation;
        $blog->save();

        return response()->json([
            'success'=>true
        ],200);
    }

    public function getUploadPage(){
        return view("uploads/blog");
    }

    public function getBlogItem($alias){
        $item = Blog::where("alias","=",$alias)
        ->with("user")->get()->firstOrFail();

        return view("blog-item")->with("item",$item);
    }

    public function editPage($alias){
        $authId = Auth::id();
        $info = Blog::where("alias","=",$alias)->where("author_id","=",$authId)->firstOrFail();
        if($info){
            return view("edit.article")->with("info",$info);
        }else{
            abort(404);
        }
    }
}
