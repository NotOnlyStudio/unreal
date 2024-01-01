<?php

namespace App\Http\Controllers\Plugin\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['store']);
    }

    public function modelsList(Request $request){
        $products = Product::query()
            ->with(["users"=>function($query) {
                $query->select(
                    'id', 'name',  'specialization', 'website',
                    'photo', 'location', 'rating');
            }, "apiPurchases"])
            ->withCount(["userBookmarks", "likes"])
            //->with('purchases')
            ->orderBy("likes_count", "desc")
            ->orderBy("created_at", "desc")
            ->where("moderation", "=", 1)
            ->orderBy("created_at", "desc");

        if($request->has('searchBy')){
            //$products->where("tags", "LIKE", "%" . $request->searchBy . "%");
            $ar = explode(',', $request->searchBy);
            $ar = array_filter($ar);

            $arFind = array_slice($ar, 1, 3);
            $products = $products->where("title", "LIKE", "%{$ar[0]}%")->orWhere("tags", "LIKE", "%{$ar[0]}%");
            foreach($arFind as $k=>$str){
                $products->where("tags","LIKE","%".$str."%")->orWhere("title", "LIKE", "%" . $str . "%")->get();
            }
        }
        if($request->cat){
            $products->whereRaw("(FIND_IN_SET($request->cat, category_id) OR FIND_IN_SET($request->cat, subcategory_id))");
        }
        if ($request->has('is_vr')){
            $products->where('is_vr', $request->is_vr);
        }
        if ($request->has('is_free')){
            $products->where('is_free', $request->is_free);
        }
        if($request->has('count') && $request->has('start')){
            $products = $products->paginate($request->count,['*'],'page', $request->start);
        }else{
            $products = $products->paginate(15);
        }

        $products->makeHidden(['filename']);

        return response()->json([
            "models"=>$products
        ]);
    }

    public function show($alias){
        $product = Product::with("users:id,name,photo","userPurchases")
        ->select("id","title","alias","tags","price","photos","props","moderation","category_id","subcategory_id")
        ->where("alias","=",$alias)
        ->with(
            "userAssessment",function($query){
                if(Auth::check()){
                    $user_id = Auth::id();
                    $query->where("user_id","=",$user_id);
                }else{
                    $query->where("user_id","=","-1");

                }
            }
        )
        ->withCount(
            [
                "likes AS likes_count" =>
                    function ($query) {
                        $query->where("like", "=", true);
                    },
                "likes as dislikes_count" =>
                    function ($query) {
                        $query->where("like", "=", false);
                    }
            ]
        )
        ->firstOrFail();
        if($product){
            $subcategory = explode(",",$product->subcategory_id);
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
                return response()->json([
                    "info"=>$product,
                ]);
            }
        }
        return response()->json([
            "Error" => "Please check data"
        ],404);
    }

    public function getBookmarks(Request $request){
        $product = Product::query()
        ->with(["users","userPurchases"])
        ->orderBy("created_at","desc")->where("moderation","=",true);
        $product->has("userBookmarks");
        if($request->searchBy){
            $product->where("title","LIKE","%".$request->searchBy."%")->orWhere("tags","LIKE","%".$request->searchBy."%");
        }
        $product = $product->paginate(9);
        if(count($product) != 0){
            return response()->json([
                "models"=>$product,
            ]);
        }
        return response()->json([
            "Message"=>"The user don't have bookmarks"
        ],204);
    }

        /**
     * Get purchases page
         *
         * @return View
     */
    public function getPurchasesPage(Request $request){
        $user_id = Auth::id();
        $purchases = Purchase::query();
        $purchases->where("user_id","=",$user_id);

        $this->searchBy = null;
        $this->sortQuery = null;

        if($request->searchBy)
            $this->searchBy = $request->searchBy;

        if($request->order)
            $this->sortQuery = $request->order;

        if($this->sortQuery == null):
            $purchases = $purchases->with("product",function($query){
                if($this->searchBy){
                    $query->where("title","LIKE","%".$this->searchBy."%");
                }
                $query->select("id","title","alias","photos","user_id");
                $query->withCount(["userBookmarks"]);
            });

            return response()->json([
                "products"=>$purchases->paginate("9")
            ]);
        else:

            $purchases = $purchases->select("product_id")->paginate();
            $id_arr = array();
            foreach($purchases as $purchase){
                array_push($id_arr,$purchase->product_id);
            }
            $products = Product::query()->whereIn("id",$id_arr)->withCount("userBookmarks");
            if($this->searchBy){
                $products->where("title","LIKE","%".$this->searchBy."%");
            }
            $ordering = $this->sortQuery;
            if($ordering == "com"){
                $products->withCount("comments")->orderBy("comments_count","desc");
            }
            if($ordering == "old"){
                $products->orderBy("created_at","asc");
            }
            if($ordering == "new"){
                $products->orderBy("created_at","desc");
            }
            $products = $products->get();
            return response()->json([
                "purchases"=>$purchases,
                "products"=>$products,
            ]);


        endif;
    }
}
