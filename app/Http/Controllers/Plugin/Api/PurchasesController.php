<?php

namespace App\Http\Controllers\Plugin\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasesController extends Controller
{
    public $searchBy;
    /**
     * @var mixed
     */
    private $cat;

    public function buyModel($id)
    {
        $purchase = $this->getPurchase($id);
        $count = Auth::user()->models_count;

        if ($id != null && $id != 0 && !$purchase && $count) {
            $user = Auth::id();
            $purchase = Purchase::create([
                "product_id" => $id,
                "user_id" => $user
            ]);
            Auth::user()->decrement("models_count",1);

            return response()->json([
                "Info" => "Purchase was created. Purchase id - ".$purchase->id
            ]);
        }

        return response()->json([
            "Error"=>"Data error. Please check api parms"
        ],500);
    }

    public function checkPurchase($id)
    {
        $purchase = $this->getPurchase($id);
        if ($purchase) {
            return response()->json([
                "status" => "buyed",
                "info" => $purchase
            ]);
        }
        return response()->json([
            "status" => "Not paid"
        ], 402);
    }

    public function getPurchasesList(Request $request){
        $id = Auth::id();
        $purchase = Purchase::where("user_id", $id)->pluck('product_id');

        $products = Product::query()
            ->whereIn('id', $purchase)
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
            $products->where("tags", "LIKE", "%" . $ar[0] . "%");
            foreach($arFind as $k=>$str){
                $products->where("tags","LIKE","%".$str."%");
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
        if ($request->has('is_pro')){
            
        }
        if($request->has('count') && $request->has('start')){
            $products = $products->paginate($request->count,['*'],'page', $request->start);
        }else{
            $products = $products->paginate(15,);
        }

        $products->makeHidden(['filename']);

        return response()->json([
            "models"=>$products
        ]);
    }

    public function getPurchasesListTt(Request $request)
    {
        $id = Auth::id();

    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getPurchase($id)
    {
        $user_id = Auth::id();
        $purchase = Purchase::where("product_id", $id)
            ->where("user_id", $user_id)
            ->get()
            ->first();

        return $purchase;
    }

}
