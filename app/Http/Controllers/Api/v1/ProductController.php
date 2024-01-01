<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;
use ZipArchive;

class ProductController extends Controller
{
    public function bookmarksPage(Request $request){
        $product = Product::query();
        $product->has("userBookmarks");
        if($request->searchBy){
            $product->orderBy("created_at","desc")->where("moderation","=",true)->where("title","LIKE","%".$request->searchBy."%")->orWhere("tags","LIKE","%".$request->searchBy."%");
        }

        return view("cabinet/bookmarks/models")->with("models",$product->paginate(9));
    }

    public function getStorePage(Request $request){
        $product = Product::query()->where("moderation", "=", 1);
        if ($request->title) {
            $product->where(function ($query) use ($request) {
                $query->orWhere("title", "LIKE", "%" . $request->title . "%")
                    ->orWhere("tags", "LIKE", "%" . $request->title . "%");
            });
        }

        if ($request->dop == 'free')
        {
            $product->where("is_free", '=',1);
        } else if ($request->dop == 'vr')
        {
            $product->where("is_vr", '=',1);
        }

        if ($request->cat) {
            $product->whereRaw("(FIND_IN_SET($request->cat, category_id) OR FIND_IN_SET($request->cat, subcategory_id))");
        }

        $product->with(["users", "userPurchases"])
            ->withCount(["userBookmarks", "likes"])
            ->orderBy("likes_count", "desc")
            ->orderBy("created_at", "desc");

        return view("store", [
            "products" => $product->paginate(9),
            "challengeShow" => false,
        ]);
    }

    public function getModerations(Request $request){
        $id = Auth::id();
        $r = $request->all();
        if (preg_match("/user\/models\/(\d+)$/", array_keys($r)[1], $res)) {
            $id = $res[1];
        }

        $moderation = Product::query();
        $moderation->where("user_id",'=', $id)
            ->where("moderation","=",0)
            ->orderBy("created_at","desc");

        if($request->searchBy){
            $moderation->where("title","LIKE","%".$request->searchBy."%");
        }

        return response()->json($moderation->paginate(3));
    }

    public function getModerated(Request $request){
        $id = Auth::id();
        $r = $request->all();
        if (preg_match("/user\/models\/(\d+)$/", array_keys($r)[1], $res)) {
            $id = $res[1];
        }

        $moderation = Product::query();
        $moderation->where("user_id",'=', $id)
            ->where("moderation","=",1)
            ->orderBy("created_at","desc");

        if($request->searchBy){
            $moderation->where("title","LIKE","%".$request->searchBy."%");
        }

        return response()->json($moderation->paginate(6));
    }

    public function getModelsCabinetPage(Request $request, $userNoOwner=null){
        $path = 'cabinet/models';
        if(is_null($userNoOwner)) {
            $id = Auth::id();
        }elseif ($userNoOwner != Auth::id()){
            $id = $userNoOwner;
            $path = "user/models";
        }

        $moderation = Product::query()
            ->orderBy("created_at", "desc")
            ->where("user_id", '=', $id)
            ->where("moderation", "=", 0);
        $moderated = Product::query()
            ->orderBy("created_at", "desc")
            ->where("user_id", '=', $id)
            ->where("moderation", "=", 1);

        if ($request->searchBy) {
            $moderation->where("title", "LIKE", "%" . $request->searchBy . "%");
            $moderated->where("title", "LIKE", "%" . $request->searchBy . "%");
        }

        return view($path, [
            "moderation" => $moderation->paginate(3),
            "moderated" => $moderated->paginate(6),
            'userNoOwner'=>$userNoOwner
        ]);
    }

    public function getProductPage($alias){
        $product = Product::where("alias", "=", $alias)
            ->with("users:id,name,photo")
            ->with(["category:id,name","userPurchases",
                "userAssessment"=>function($query){
                    if(Auth::check()){
                        $user_id = Auth::id();
                        $query->where("user_id","=",$user_id);
                    }else{
                        $query->where("user_id","=","-1");

                    }
                }
            ])
            ->withCount(
                [
                    "likes AS likes_count" =>
                        function($query){
                            $query->where("like","=",true);
                        },
                    "likes as dislikes_count" =>
                        function($query){
                            $query->where("like","=",false);
                        }
                ]
            )
            ->firstOrFail();

        $subcategory = $product -> subcategory_id;
        $subcategory = explode(",",$subcategory);
        $categories = explode(",",$product->category_id);
        $categories = \App\Models\Category::where("id","=",$subcategory[0])->orWhere("id","=",$categories[0])->get();

        $subcategory = $categories->filter(
            function($item){
                return $item->parent != null;
            }
        )->first();
        $categories =  $categories->filter(
            function($item){
                return $item->parent == null;
            }
        )->first();

        if($subcategory != null){
            $product['subcategory'] = array(
                "name" => $subcategory->name,
                "id" => $subcategory -> id
            );
        }else{
            $product['subcategory'] = array(
                "name" => "Not found",
                "id" => "0"
            );
        }
        if($categories != null){
            $product['categories'] = array(
                "name" => $categories->name,
                "id" => $categories -> id
            );
        }else{
            $product['categories'] = array(
                "name" => "Not found",
                "id" => "0"
            );
        }

        if($product->moderation == true || $product->user_id == Auth::id()){
            return view("Models.ModelPage",[
                "info"=>$product,
                "challengeShow"=>false
            ]);
        }
        abort(404);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_admin(Request $request)
    {
        $product = Product::query();
        if($request->title){
            $product->where("title","LIKE","%".$request->title."%")->orWhere("tags","LIKE","%".$request->title."%");
        }

        if ($request->fuser)
        {
            $user = User::where("name", "LIKE", $request->fuser. "%")->get();
            $product->whereIn("user_id", $user->pluck('id'));
        }
        if ($request->filter_moderate)
        {
            $product->where("moderation", '=',($request->filter_moderate==2)?0:1);
        }
        if ($request->filter_free)
        {
            $product->where("is_free", '=',($request->filter_free==2)?0:1);
        }

        $temp = $product->with(["users","userPurchases"])
            ->withCount(["userBookmarks"])
            ->orderBy("moderation","asc")
            ->orderBy("created_at","desc")
            ->paginate(8);

        foreach ($temp as $product){
            $nbsp = html_entity_decode("&nbsp;");
            $product->tags = str_replace($nbsp, " ", $product->tags);
        }
        return $temp;
    }

    public function index(Request $request)
    {
        $product = Product::query();
        if($request->title){
            $product->where(function ($query) use ($request) {
                $query->orWhere("title","LIKE","%".$request->title."%")
                    ->orWhere("tags","LIKE","%".$request->title."%");
            });
        }
        if($request->cat){
            $product->where("category_id",'=',$request->cat)
                ->orWhere("subcategory_id",'=',$request->cat);
        }

        if ($request->dop == 'free')
        {
            $product->where("is_free", '=',1);
        } else if ($request->dop == 'vr')
        {
            $product->where("is_vr", '=',1);
        }

        if($request->get("count")){
            return  $product
                ->with(["users", "userPurchases"])
                ->where("moderation","=",1)
                ->withCount(["userBookmarks"])
                ->orderBy("created_at","desc")
                ->paginate($request->get("count"));
        }

        // TODO проверить весь метод
        //
        // if($request->filled('fuser')){
        //     $user = User::where("name", "LIKE", $request->fuser. "%")->get();
        //     $product->whereIn("user_id", $user->pluck('id'));
        // }

        // if($request->filled('filter_moderate')){
        //     $product->where("moderation",'=', $request->filter_moderate==1 ? 1:0);
        // }

        // if($request->filled('filter_free')){
        //     $product->where("is_free",'=', $request->filter_free==1 ? 1:0 );
        // }

        // return $product->with(["users","userPurchases"])
        //     ->withCount(["userBookmarks"])
        //     ->orderBy("created_at","desc")
        //     ->paginate(6);
    }
    /**
     * ONLY FOR USERS
     */
    public function index_get(Request $request)
    {
        $product = Product::query()->where("moderation","=",true);
        if($request->title){
            $product->where("title","LIKE","%".$request->title."%")->orWhere("tags","LIKE","%".$request->title."%");
        }

        if($request->get("count")){
            if($request->cat){
                $product->where("category_id",'=',$request->cat)->orWhere("subcategory_id","=",$request->cat);
            }
            return $product
                ->with(["users"])
                ->withCount(["userBookmarks"])
                ->orderBy("created_at","desc")
                ->paginate($request->get("count"));
        }

        return $product->with(["users","userPurchases"])
            ->withCount(["userBookmarks"])
            ->orderBy("created_at","desc")
            ->paginate(6);
    }
    public function productsForUser(Request $request){

        $products = Product::query()->where("moderation","=",true);
        if($request->searchBy){
            $products->where("title","LIKE","%".$request->searchBy."%")->orWhere("tags","LIKE","%".$request->searchBy."%");
        }

        return $products->whereHas("productsForUser")->withCount(["userBookmarks"])->paginate(9);
    }

    public function fastEdit(Request $request){
        Product::where("id","=",$request->id)->update(
            [
                'price'=>$request->price,
                'title'=>$request->title,
                'tags'=>$request->tags,
            ]
        );
        return true;
    }
    /**
     * Moderate model.
     *
     * @return \Illuminate\Http\Response
     */
    public function moderate($id){
        $product = Product::find($id);
        $mod = $product->moderation;
        $product->moderation = !$mod;
        $message = $mod == 1? "approved" : "removed from viewing";

        \App\Models\Notification::create([
            "user_id"=>$product->user_id,
            "from_user"=>Auth::id(),
            "message"=>"Model was ".$message,
            "message_from"=>$product->title,
        ]);


        $product->save();
        if($product){
            return response()->json([
                'success'=>true
            ]);
        }
        return response()->json([
            'success'=>false
        ]);
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

    public function getForUser(Request $request){
        $user = Auth::id();
        if($request->get("moderation")){
            $product = Product::query()->where("user_id",'=',$user);
            if($request->get("moderation") == 'false')
                $product->where("moderation","=",false);
            else
                $product->where("moderation","=",true);
            if($request->searchBy)
                $product->where("title","LIKE","%".$request->searchBy."%")->orWhere("tags","LIKE","%".$request->searchBy."%");
            return $product->paginate(6);
        }
    }
    public function savePhoto($file,$photo_name,$path,$user_id,$exc,$index = 3){
        $image = $file;
        $input['imagename'] = $photo_name;
        if(!Storage::exists("public/$path/user-$user_id")) {
            Storage::makeDirectory("public/$path/user-$user_id", 0775, true);
        }
        $destinationPath = storage_path()."/app/public/$path/user-$user_id";
        $img = Image::make($image->getRealPath())->encode("webp", 85);

        $watermark =  Image::make(public_path('assets/logo-opacity.png'));
        $resizePercentage = 30;
        $watermarkSize = round(($img->width()/1.3) * ((100 - $resizePercentage) / 150), 2);
        $watermark->resize($watermarkSize, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $watermarkSize = $img->width() - 20; //size of the image minus 20 margins
        $watermarkSize = $img->width() / 2; //half of the image size
        $img->insert($watermark, 'bottom-right', 30, 30);

        // $img->insert(public_path('assets/logo-opacity.png'), 'bottom-right', 10, 10);
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
        $thumb = Image::make($image->getRealPath())->encode("webp", 85);
        $thumb->resize(420, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']."_thumb.webp",85);
        $thumb->resize(420, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']."_thumb.$exc",85);
    }

    public function changeMode(Request $request){
        Product::where("id",$request->id)->update([
            "is_free"=>$request->is_free
        ]);
        return response()->json([
            "message"=>"parametr was changed"
        ]);
    }

    public function changeVR(Request $request){
        Product::where("id",$request->id)->update([
            "is_vr"=>$request->is_vr
        ]);
        return response()->json([
            "message"=>"parametr was changed"
        ]);
    }

    public function adminEdit(Request $request){
        $product = Product::find($request->id);
        $category = Category::find(7);

        if($product){
            $product->photos = $request->photosPositions;
            $product->title = $request->title;
            if ($category->parent != null){
                $product->category_id = $category->parent;
                $product->subcategory_id = $category->id;
            } else{
                $product->category_id = $category->id;
            }
            $alias = mb_strtolower(str_replace([" ","/","="],"-",$request->title));
            $alias = str_replace(["&","?","!","/"],"",$alias);
            $counts = Product::where("alias",'=',$alias)->where("id","!=",$product->id)->count();
            if($counts != 0){
                $counts += 1;
                $alias = $counts."-".$alias;
            }
            $product->alias = $alias;
            $product->tags = $request->tags;
            $product->meta = $request->meta;
            $product->price = $request->price;
            //created
            $product->is_free = $request->is_free ? true : false;
            $product->props = json_encode([
                'description'=>$request->description,
                'version'=>$request->version,
                "bake"=>$request->bake ? true : false,
                "rtx" => $request->rtx ? true : false,
            ]);
            if($request->model){
                if(Storage::disk("private")->exists("/models/user-".$product->user_id."/".$product->filename)):
                    Storage::deleteDirectory("private/models/user-".$product->user_id."/".$product->filename);
                endif;
                //Model uploading
                $folder = "ufi".time();
                $fileSystem = json_decode($request->fileSystemStructure);
                $modelFolderName = "private/models/user-".$product->user_id."/".$folder."/".$request->folderName;
                $product->filename = $folder;
                foreach($request->model  as $key=>$item){
                    $item_dir = $fileSystem[$key]->directoriesStructure;
                    $item_dir = $item_dir != null ?  $modelFolderName."/$item_dir" : $modelFolderName;
                    $item->storeAs(
                        $item_dir, $item->getClientOriginalName()
                    );
                }
            }
            if($request->deletedPhotos){
                $photos = explode(",",$request->deletedPhotos);
                foreach($photos as $photo){
                    $image = explode(".",$photo);
                    if(Storage::disk("public")->exists("/models/user-".$product->user_id."/".$image[0].".".$image[1]))
                    {
                        Storage::disk("public")->delete("/models/user-".$product->user_id."/".$image[0].".".$image[1]);
                    }
                    if(Storage::disk("public")->exists("/models/user-".$product->user_id."/".$image[0].".webp"))
                    {
                        Storage::disk("public")->delete("/models/user-".$product->user_id."/".$image[0].".webp");
                    }
                    if(Storage::disk("public")->exists("/models/user-".$product->user_id."/".$image[0]."_thumb.".$image[1]))
                    {
                        Storage::disk("public")->delete("/models/user-".$product->user_id."/".$image[0]."_thumb.".$image[1]);
                    }
                    if(Storage::disk("public")->exists("/models/user-".$product->user_id."/".$image[0]."_thumb.webp"))
                    {
                        Storage::disk("public")->delete("/models/user-".$product->user_id."/".$image[0]."_thumb.webp");
                    }
                }
                $photos_arr = [];

                foreach(json_decode($product->photos) as $photo){
                    if(array_search($photo,$photos) === false){
                        array_push($photos_arr,$photo);
                    }
                }
                $product->photos = json_encode($photos_arr);
            }

            if($request->photos){
                $photos_all = $request->photos;
                $photos = array();

                foreach ($photos_all as $key=>$image):
                    $type = $image->getClientOriginalExtension();
                    if ($type == "jpg" || $type == "jpeg" || $type == "gif" || $type == "png") {
                        $photo_name = $key.'_'.time() . date("Y-m-d");
                        $this->savePhoto($image,$photo_name,"/models/",$product->user_id,$type,$key);
                        array_push($photos, $photo_name.".$type");
                    }
                endforeach;
                if(is_array(json_decode($product->photos))){
                    $photos = array_merge($photos,json_decode($product->photos));
                }

                $product->photos = json_encode($photos);
            }
            $product->save();
            return response()->json([
                'product'=>$product,
                'key'=>$request->key
            ],200);
        }
        return false;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->photo){
            $user_id = Auth::id();
            $date = date("Y-m");
            $photos = [];
            $photos_all = $request->file('photo');
            foreach ($photos_all as $key=>$image):
                $type = $image->getClientOriginalExtension();
                if ($type == "jpg" || $type == "jpeg" || $type == "gif" || $type == "png") {
                    $photo_name = $key."_".time() . date("Y-m-d");
                    array_push($photos, $photo_name.".$type");
                    $this->savePhoto($image,$photo_name,"/models/",$user_id,$type,$key);
                }
            endforeach;

            //Model uploading
            $folder = "ufi".time();
            $fileSystem = json_decode($request->fileSystemStructure);
            $modelFolderName = "private/models/user-$user_id/".$folder."/".$request->folderName;
            foreach($request->modelFile  as $key=>$item){
                $item_dir = $fileSystem[$key]->directoriesStructure;
                $item_dir = $item_dir != null ?  $modelFolderName."/$item_dir" : $modelFolderName;
                $item->storeAs(
                    $item_dir, $item->getClientOriginalName()
                );
            }

            $alias = mb_strtolower(str_replace([" ","="],"-",$request->title));
            $alias = str_replace(["&","?","!","/"],"",$alias);
            $counts = Product::where("alias",'=',$alias)->count();
            if($counts != 0){
                $counts += 1;
                $alias = $counts."-".$alias;
            }

            $preCat = explode(',', $request->categories);
            $preSub= explode(',', $request->subcategories);
            $product = Product::create(
                [
                    'photos' => json_encode($photos),
                    'title' => $request->title,
                    'user_id'=>$user_id,
                    'alias'=>$alias,
                    'tags'=>$request->tags,
                    'filename'=>$folder,
                    'is_vr' => $request->vr ? 1 : 0,
                    'category_id'=> collect($preCat)->unique()->implode(','),
                    'subcategory_id'=>collect($preSub)->unique()->implode(','),
                    'props' => json_encode([
                        'description'=>$request->description,
                        'version'=>$request->version,
                        "rtx"=>$request->rtx ? true : false,
                        "bake"=>$request->bake ? true : false,
                        "vr"=>$request->vr ? true : false
                    ])
                ]
            );
            Auth::user()->increment("rating");
            Auth::user()->rang_id = \App\Models\Rang::whereBetween("balls",[Auth::user()->rating, 30000])->get()->first()->id-1;
            return response() -> json([
                'success'=>true,
                'alias'=>$product->alias,
            ]);
        }
    }
    public function changeUser(Request $request){
        if($request->user != "none"){
            $product = Product::find($request->product);
            $photos = json_decode($product->photos);
            $old_user = $product->user_id;
            $new_user = $request->user;
            $file_name = $product->filename;

            $locale_path = "/app/public/models";
            $path = storage_path().$locale_path;
            if(!Storage::disk("public")->exists("/models/user-".$new_user)){
                Storage::disk("public")->makeDirectory("/models/user-".$new_user);
            }
            if(!Storage::disk("private")->exists("/models/user-$new_user/$file_name")){
                Storage::disk("private")->makeDirectory("/models/user-$new_user/$file_name");
            }
            foreach($photos as $photo):
                $photo = explode(".",$photo);
                if(file_exists($path."/user-".$old_user."/".$photo[0].".".$photo[1])):
                    \Storage::move("/public/models/user-$old_user/".$photo[0].".".$photo[1],"/public/models/user-$new_user/".$photo[0].".".$photo[1]);
                endif;
                if(file_exists($path."/user-".$old_user."/".$photo[0]."_thumb.".$photo[1])):
                    \Storage::move("/public/models/user-$old_user/".$photo[0]."_thumb.".$photo[1],"/public/models/user-$new_user/".$photo[0]."_thumb.".$photo[1]);
                endif;
                if(file_exists($path."/user-".$old_user."/".$photo[0]."_thumb.webp")):
                    \Storage::move("/public/models/user-$old_user/".$photo[0]."_thumb.webp","/public/models/user-$new_user/".$photo[0]."_thumb.webp");
                endif;
                if(file_exists($path."/user-".$old_user."/".$photo[0].".webp")):
                    \Storage::move("/public/models/user-$old_user/".$photo[0].".webp","/public/models/user-$new_user/".$photo[0].".webp");
                endif;

            endforeach;
            $directory = "models/user-$old_user/$file_name/";
            $directories = \Storage::disk("private")->allFiles($directory);
            foreach($directories as $dir){
                Storage::move(
                    '/private/models/user-'.$old_user.'/'.str_replace("models/user-$old_user/",'',$dir),
                    "/private/models/user-$new_user/$file_name/".str_replace("models/user-$old_user/$file_name/",'',$dir)
                );
            }
            Storage::disk("private")->deleteDirectory("/models/user-".$old_user."/".$file_name);
            $product->user_id = $new_user;
            $product->save();

            return response()->json([
                'success'=>true,
            ]);
        }
        return false;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($alias)
    {
        $product = Product::with("users")->where("alias","=",$alias)->get();
        if($product){
            return response()->json([
                'failed'=>false,
                'info'=>$product,
            ]);
        }
        return response()->json([
            'failed'=>true,
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

        Purchase::where("product_id","=",$id)->delete();
        Like::where("likeable_type", "=", "App\Models\Product")->where("likeable_id", "=", $id)->delete();
        Comment::where("commentable_type", "=", "App\Models\Product")->where("commentable_id", "=", $id)->delete();

        $product = Product::find($id);
        foreach (json_decode($product->photos) as $image) {
            $image = explode(".", $image);
            if (Storage::disk("public")->exists("/models/user-" . $product->user_id . "/" . $image[0] . "." . $image[1])) {
                Storage::disk("public")->delete("/models/user-" . $product->user_id . "/" . $image[0] . "." . $image[1]);
            }
            if (Storage::disk("public")->exists("/models/user-" . $product->user_id . "/" . $image[0] . ".webp")) {
                Storage::disk("public")->delete("/models/user-" . $product->user_id . "/" . $image[0] . ".webp");
            }
            if (Storage::disk("public")->exists("/models/user-" . $product->user_id . "/" . $image[0] . "_thumb." . $image[1])) {
                Storage::disk("public")->delete("/models/user-" . $product->user_id . "/" . $image[0] . "_thumb." . $image[1]);
            }
            if (Storage::disk("public")->exists("/models/user-" . $product->user_id . "/" . $image[0] . "_thumb.webp")) {
                Storage::disk("public")->delete("/models/user-" . $product->user_id . "/" . $image[0] . "_thumb.webp");
            }
        }
        //
        $product->delete();

        return response()->json([
            'success'=>true
        ]);
    }



    public function getUploadPage(){
        return view("uploads/models");
    }

    /**
     * Get response for downloading file
     *
     * @return Response
     */
    public function downloadFile($id){
        if(Auth::check()){
            $model = \App\Models\Purchase::query();
            $user = Auth::user();
            if(!User::checkAdministration($user)){
                $model->where("user_id","=",$user->id);
                $model->where("product_id","=",$id);
                $permisions = Auth::check() ? Auth::user()->permisions : "USER";
                if($model->count() != 0){
                    $product = $model->with("product:id,user_id,filename")->get()->first()->product;
                    $author = $product->user_id;
                    $folder = "models/user-".$author.'/'.$product->filename;
                    $zip = new ZipArchive;
                    $private = Storage::disk("private");
                    $files = $private->allFiles($folder);
                    $folder .= "/";
                    $file = $product->filename.'.zip';
                    $path = storage_path("app/private/models/tmp/$file");
                    if ($zip->open($path, ZipArchive::CREATE) === TRUE)
                    {
                        foreach ($files as $key => $value) {
                            $relativeNameInZipFile = str_replace($folder,"",$value);
                            $zip->addFile(storage_path("app/private/".$value), $relativeNameInZipFile);
                        }
                        $zip->close();
                    }
                    $headers = array(
                        'Content-Type: archive/zip',
                    );
                    return \Illuminate\Support\Facades\Response::download($path, $file, $headers)->deleteFileAfterSend();
                }
            }else{
                $product = Product::where("id","=",$id)->select("filename","user_id")->get()->first();
                $author = $product->user_id;
                $folder = "models/user-".$author.'/'.$product->filename;
                $zip = new ZipArchive;
                $private = Storage::disk("private");
                $files = $private->allFiles($folder);
                $folder .= "/";
                $file = $product->filename.'.zip';
                $path = storage_path("app/private/models/tmp/$file");
                if ($zip->open($path, ZipArchive::CREATE) === TRUE)
                {
                    foreach ($files as $key => $value) {
                        $relativeNameInZipFile = str_replace($folder,"",$value);
                        $zip->addFile(storage_path("app/private/".$value), $relativeNameInZipFile);
                    }
                    $zip->close();
                }
                $headers = array(
                    'Content-Type: archive/zip',
                );
                return \Illuminate\Support\Facades\Response::download($path, $file, $headers)->deleteFileAfterSend();

            }
        }
        abort(404);
    }

    /**
     * Save RULES FILE
     *
     *
     */
    public function saveRules(Request $request){
        File::put(resource_path()."\json\models-rules.json", $request->rules);
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
        $rules = File::get(resource_path()."\json\models-rules.json");
        return response()->json([
            "rules"=>$rules
        ],200);
    }

}
