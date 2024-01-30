<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletUser;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{

    public $searchBy;
    public $sortQuery;

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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        if ($purchase) {
//            Auth::user()->increments("models_count", $request->count);
//            return response()->json([
//                "success" => true,
//            ]);
//        }
//        return response()->json([
//            "error" => "Failed transaction"
//        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Get purchases page
     *
     * @return View
     */
    public function getPurchasesPage(Request $request)
    {
        $user_id = Auth::id();
        $purchases = Purchase::query();
        // Purchase::where("user_id","=",$user_id)->update([
        //     "view"=>true
        // ]);

        $purchases->where("user_id", "=", $user_id)->orderBy('created_at', 'DESC');

        $this->searchBy = null;
        $this->sortQuery = null;

        if ($request->searchBy)
            $this->searchBy = $request->searchBy;

        if ($request->order)
            $this->sortQuery = $request->order;

        if ($this->sortQuery == null):

            $purchases = $purchases->with("product", function ($query) {
                if ($this->searchBy) {
                    $query->where("title", "LIKE", "%" . $this->searchBy . "%");
                }
                $query->select("id", "title", "alias", "photos", "user_id");
                $query->withCount(["userBookmarks"]);
            });

            return view("cabinet.purchases")->with("products", $purchases->paginate("9"));
        else:

            $purchases = $purchases->select("product_id")->paginate();
            $id_arr = array();
            foreach ($purchases as $purchase) {
                array_push($id_arr, $purchase->product_id);
            }
            $products = Product::query()->whereIn("id", $id_arr)->withCount("userBookmarks");
            if ($this->searchBy) {
                $products->where("title", "LIKE", "%" . $this->searchBy . "%");
            }
            $ordering = $this->sortQuery;
            if ($ordering == "com") {
                $products->withCount("comments")->orderBy("comments_count", "desc");
            }
            if ($ordering == "old") {
                $products->orderBy("created_at", "asc");
            }
            if ($ordering == "new") {
                $products->orderBy("created_at", "desc");
            }
            $products = $products->get();
            return view("cabinet.purchases", array(
                "purchases" => $purchases,
                "products" => $products,
            ))->with("cl", 1);


        endif;
    }

    public function getProfitPage()
    {
//        return Purchase::query()->where('user_id', auth()->user()->getAuthIdentifier())
//            ->get()
//            ->sum(function ($purchase) {
//                return (float) $purchase->price;
//            });
        $subdays = Carbon::now()->subDays(30);
        $purchases_counts = WalletUser::query()
            ->whereHas('product', function ($query) {
                return $query->where('user_id', '=', Auth::id());
            })
            ->select([
                DB::raw('SUM(price) as `price`'),
                DB::raw('DATE(created_at) as day'),
            ])->groupBy('day')
            ->get()->toArray();

        $purchases_prices = WalletUser::query()
            ->with("product:id,title,user_id")
            ->whereHas('product', function ($query) {
                return $query->where('user_id', '=', auth()->user()->getAuthIdentifier());
            })
            ->select([
                DB::raw('count(*) as count'),
                DB::raw('SUM(price) as bill'),
                DB::raw('product_id'),
            ])
            ->groupBy("product_id")
            ->get();

        $user_id = Auth::id();
        if (isset($purchases_counts[0]['price'])) {
            return view("cabinet.profit", [
                "purchasesCount" => array_reverse($purchases_counts),
                "models" => $purchases_prices,
                "transactions" => \App\Models\Transactions::query()->where("user_id", $user_id)->take(30)->orderBy("created_at", "desc")->get(),
                "wallet" => Wallet::query()->where("user_id", $user_id)->first()->bill
            ]);
        } else {
            return view("cabinet.profit", [
                "purchasesCount" => array_reverse($purchases_counts),
                "models" => $purchases_prices,
                "transactions" => \App\Models\Transactions::query()->where("user_id", $user_id)->take(30)->orderBy("created_at", "desc")->get(),
                "wallet" => 0
            ]);
        }

    }
}
