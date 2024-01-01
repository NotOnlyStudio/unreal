<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;

class GalleryController extends Controller
{
    public function getGalleryPage(Request $request)
    {
        $query = Gallery::query()->where("moderation", "=", true);
        if ($request->challenge) {
            $query->where("challenge_id", "=", $request->challenge);
        }
        if ($request->title) {
            $query->where("title", "LIKE", "%" . $request->title . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }

        $query->with([
            "user" => function ($query) {
                $query->select("id", "name");
            },
            "userAssessment" => function ($query) {
                if (Auth::check()) {
                    $user_id = Auth::id();
                    $query->where("user_id", "=", $user_id);
                } else {
                    $query->where("user_id", "=", "-1");
                }
            }
        ])
        ->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        },'userBookmarks'])->where("moderation","=",true);

        if($request->order){
            $order = $request->order;
            if($order == "new"){
                $query->orderBy("created_at","desc");
            }
            if($order == "old"){
                $query->orderBy("created_at","asc");
            }
            if($order == "lik"){
                $query->orderBy("likes_count","desc");
            }
            if ($order == "dis") {
                $query->orderBy("likes_count", "asc");
            }
            if ($order == "com") {
                $query->withCount([
                    "comments as comments_count" => function ($q) {
                        $q->where("moderated", "=", true);
                    }
                ])->orderBy("comments_count");
            }
        } else {
            $query->orderBy("created_at", "desc");
        }
        $gallery = $query->paginate(9);

        return view("gallery/gallery", [
            "gallery" => $gallery
        ]);
    }

    public function getGalleryCabinetPage($userNoOwner = null)
    {
        $path = "cabinet/gallery";
        if (is_null($userNoOwner)) {
            $id = Auth::id();
        } elseif ($userNoOwner != Auth::id()) {
            $id = $userNoOwner;
            $path = "user/gallery";
        }

        $moderated = Gallery::where("user_id", "=", $id)
            ->where("moderation", '=', false)
            ->orderBy("created_at", "desc")
            ->paginate(3);
        $moderation = Gallery::where("user_id", "=", $id)
            ->where("moderation", '=', true)
            ->orderBy("created_at", "desc")
            ->paginate(6);

        return view($path, [
            "moderated" => $moderated,
            "moderation" => $moderation,
            'userNoOwner' => $userNoOwner
        ]);
    }

    public function bookmarksGet(Request $request)
    {
        $gallery = Gallery::query();
        $gallery->orderBy("created_at", "desc")
            ->where("moderation", "=", true)
            ->has("userBookmarks")
            ->withCount(["likes AS likes_count" => function ($query) {
            $query->where("like", "=", true);
        }, "likes as dislikes_count" => function ($query) {
            $query->where("like", "=", false);
        }]);
        if ($request->get("searchBy")) {
            $gallery->where("title", "LIKE", "%" . $request->get("searchBy") . "%")
                ->orWhere("tags", "LIKE", "%" . $request->searchBy . "%");
        }

        return response()->json(
            $gallery->paginate(6)
        );
        // return view("cabinet/bookmarks/gallery")->with("gallery",$gallery->paginate(6));
    }

    public function bookmarksPage(Request $request){
        $gallery = Gallery::query();
        $gallery->orderBy("created_at","desc")
            ->where("moderation","=",true)
            ->has("userBookmarks")
            ->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        }]);

        if($request->get("searchBy")){
            $gallery->where("title","LIKE","%".$request->get("searchBy")."%")
                ->orWhere("tags","LIKE","%".$request->searchBy."%");
        }

        return view("cabinet/bookmarks/gallery")->with("gallery",$gallery->paginate(6));
    }

    public function photos(Request $request){
        $image = $request->image;
        $ex = $image->getClientOriginalExtension();
        $user_id = Auth::id();
        $photo_name = "казявка";
        $this->savePhoto($image,$photo_name.".".$ex,"gallery",$user_id);

        return $photo_name;
    }

    public function approve($id){
        $gallery = Gallery::find($id);
        $gallery->moderation = !$gallery->moderation;
        $gallery->save();

        return response()->json([
            'success'=>true
        ],200);
    }

    public function savePhoto($file,$photo_name,$path,$user_id,$exc,$index = 3){
        $image = $file;
        $input['imagename'] = $photo_name;
        if(!Storage::exists("public/$path/user-$user_id")) {
            Storage::makeDirectory("public/$path/user-$user_id", 0775, true);
        }
        $destinationPath = storage_path()."/app/public/$path/user-$user_id";
        $img = Image::make($image->getRealPath())->encode("webp", 85);

        if($image->getSize() > 1000000 || $img->width() > 1400):
            $img->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename'].".webp",85);
            $img->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename'].".$exc",85);
        else:
            $img->save($destinationPath.'/'.$input['imagename'].".$exc",85);
            $img->save($destinationPath.'/'.$input['imagename'].".webp",85);
        endif;

        if($index == 0){
            $img->resize(420, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']."_thumb.webp",85);
            $img->resize(420, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']."_thumb.$exc",85);
        }
    }

    public function editGallery(Request $request){
        $gallery = Gallery::find($request->gallery_id);
        $gallery -> title = $request->title;
        $alias = mb_strtolower(str_replace([" ","/","="],"-",$request->title));
        $alias = str_replace(["&","?","!"],"",$alias);
        $count_gal = Gallery::where("alias","=",$alias)->where("id","!=",$gallery->id)->count();
        if($count_gal != 0){
            $count_gal += 1;
            $alias = $count_gal."-".$alias;
        }
        $gallery->alias = $alias;
        $gallery -> tags = $request->tags;
        $video = $request->video;
        $video = $video == "null" ? null : $video;
        $gallery -> video = $video;
        $gallery->meta = $request->meta;
        $gallery->description = $request->description;
        if($request->deletedPhotos){
            $deletable = explode(",",$request->deletedPhotos);
            $gallery_photos = json_decode($gallery->photos);
            $new_photos = array();
            foreach($deletable as $photo_name){
                $image = explode(".", $photo_name);
                if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0].".".$image[1]))
                {
                    Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0].".".$image[1]);
                }
                if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0].".webp"))
                {
                    Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0].".webp");
                }
                if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.".$image[1]))
                {
                    Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.".$image[1]);
                }
                if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.webp"))
                {
                    Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.webp");
                }
                array_push($new_photos,$photo_name);
            }
            $gallery->photos = json_encode(array_values(array_diff($gallery_photos,array_reverse($new_photos))));
        }

        if($request->photos){
            $photos = array();
            foreach ($request->photos as $key=>$image):
                $type = $image->getClientOriginalExtension();
                if ($type == "jpg" || $type == "jpeg" || $type == "gif" || $type == "png") {
                    $photo_name = $key."_".time() . date("Y-m-d");
                    array_push($photos, $photo_name.".$type");
                    $this->savePhoto($image,$photo_name,"/gallery/",$gallery->user_id,$type,$key);
                }
            endforeach;
            $old_photos = $gallery->photos == null ? null : json_decode($gallery->photos);
            if($old_photos != null){
                $photos = array_merge($photos,$old_photos);
            }
            $gallery->photos = json_encode($photos);
        }

        if($gallery->save()){
            return response()->json([
                'success'=>true,
                'item'=>$gallery,
            ],200);
        }

        return response()->json([
            'success'=>false,
        ],500);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Gallery::query()
            ->where("moderation","=",true)
            ->orderBy("created_at","desc");
        if($request->challenge){
            $query->where("challenge_id","=",$request->challenge);
        }
        if($request->title){
            $query->where("title","LIKE","%".$request->title."%")
                ->orWhere("tags","LIKE","%".$request->searchBy."%");
        }
        if($request->get("count")){
            $query->with([
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
            },'userBookmarks']);

            if($request->order){
                $order = $request->order;
                if($order == "new"){
                    $query->orderBy("created_at","desc");
                }
                if($order == "old"){
                    $query->orderBy("created_at","asc");
                }
                if($order == "lik"){
                    $query->orderBy("likes_count","desc");
                }
                if($order == "dis"){
                    $query->orderBy("likes_count","asc");
                }
                if($order == "com"){
                    $query->withCount([
                        "comments as comments_count" => function($q){
                            $q->where("moderated","=",true);
                        }
                    ])->orderBy("comments_count");
                }
            }

            return $query->paginate($request->get("count"));
        }

        return $query->paginate("3");
    }

    public function galleryForUser(Request $request){
        $gallery = Gallery::query();
        if($request->searchBy){
            $gallery->where("title","LIKE","%".$request->searchBy."%")->orWhere("tags","LIKE","%".$request->searchBy."%");
        }

        return $gallery->whereHas("galleryForUser")->withCount(["userBookmarks"]) ->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        },'userBookmarks'])->paginate(6);
    }

    public function bookmarks(){
        return Gallery::with(["bookmarks"=>function($query){
            $query->where("productable_type",'=','Gallery');
        }])->get();
    }

    public function store(Request $request)
    {
        $images = array();
        $video = array();
        $id = Auth::id();

        if (!array_reduce($request->video, function ($res, $item) {
            return $res += empty($item) ? 0 : 1;
        })) {
            $video = null;
        } else {
            foreach ($request->video as $key => $item) {
                if(!empty($item)) {
                    array_push($video, $item);
                }
            }
        }

        foreach ($request->img as $key => $image):
            $type = $image->getClientOriginalExtension();
            if ($type == "jpg" || $type == "jpeg" || $type == "gif" || $type == "png") {
                $photo_name = $key . "_" . time() . date("Y-m-d");
                array_push($images, $photo_name . ".$type");
                $this->savePhoto($image, $photo_name, "/gallery/", $id, $type, $key);
            }
        endforeach;

        $alias = mb_strtolower(str_replace([" ", "/", "="], "-", $request->title));
        $alias = str_replace(["&", "?", "!"], "", $alias);
        $count_gal = Gallery::where("alias", "=", $alias)->count();

        if ($count_gal != 0) {
            $count_gal += 1;
            $alias = $count_gal . "-" . $alias;
        }

        $gallery = Gallery::create(
            [
                'user_id' => Auth::id(),
                'title' => $request->title,
                'alias' => $alias,
                "description" => $request->description,
                'tags' => $request->tags,
                "video" => json_encode($video),
                'photos' => json_encode($images),
                'challenge_id' => $request->challenge_id ? $request->challenge_id : null,
            ]
        );

        if (!$gallery) {
            return response()->json([
                "status" => false,
            ]);
        }

        return response()->json([
            "status" => true,
            "alias" => $alias,
        ]);
    }

    public function show($alias)
    {
        return Gallery::where("alias","=",$alias)->with([
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
            },'userBookmarks'])->get()->first();
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

    public function getModerations(){
        $moderated = Gallery::where("user_id","=",Auth::id())
            ->where("moderation",'=',false)
            ->orderBy("created_at","desc")
            ->paginate(3);
        return response()->json($moderated);
    }

    public function getModerated(){
        $moderated = Gallery::where("user_id","=",Auth::id())
            ->where("moderation",'=',true)
            ->orderBy("created_at","desc")
            ->paginate(6);
        return response()->json($moderated);
    }

    public function getByUser($id){
        return Gallery::where("user_id","=",$id)->where("moderation",'=',true)->with([
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
            },'userBookmarks'])->paginate(9);
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
        $gallery = Gallery::find($id);
        foreach (json_decode($gallery->photos) as $image):
            $image = explode(".", $image);
            if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0].".".$image[1]))
            {
                Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0].".".$image[1]);
            }
            if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0].".webp"))
            {
                Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0].".webp");
            }
            if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.".$image[1]))
            {
                Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.".$image[1]);
            }
            if(Storage::disk("public")->exists("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.webp"))
            {
                Storage::disk("public")->delete("/gallery/user-".$gallery->user_id."/".$image[0]."_thumb.webp");
            }
        endforeach;
        $gallery->delete();

        return response()->json([
            "success"=>true
        ]);
    }

    public function adminGallery(){
        return Gallery::query()->orderBy("created_at","desc")->paginate(15);
    }

    public function getUploadPage(){
        return view("uploads/gallery");
    }

    public function getGalleryItem($alias){
        $gallery = Gallery::where("alias","=",$alias)
            ->where("moderation","=",true)
            ->with([
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
            ])
            ->withCount(["likes AS likes_count" => function($query){
                $query->where("like","=",true);
            },"likes as dislikes_count" => function($query){
                $query->where("like","=",false);
            },'userBookmarks'])->get()->first();
        if($gallery){
            return view("gallery/gallery-item")
            ->with("item",$gallery);
        }else{
            abort(404);
        }
    }

    /**
     * Save RULES FILE
     *
     *
     */
    public function saveRules(Request $request){
        File::put(resource_path()."\json\gallery-rules.json", $request->rules);

        return response()->json([
            "success"=>true
        ],200);
    }

    /**
     * Get rules from file
     *
     *
     */
    public function getRules(){
        $rules = File::get(resource_path()."\json\gallery-rules.json");

        return response()->json([
            "rules" => $rules
        ], 200);
    }
}
