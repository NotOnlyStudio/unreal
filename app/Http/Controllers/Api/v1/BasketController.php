<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\Basket;

class BasketController extends Controller
{

    /**
     * Make transfer from localstorage to database
     *
     * @return Response
     *
    */
    public function authTranslate(Request $request){
        $transfer = array();
        $arr = $request->basket;
        foreach($arr as $item):

            array_push($transfer,[
                "product_id"=>$item->product_id,
                "user_id"=>Auth::id(),
            ]);

        endforeach;
        unset($arr);
        Basket::insert($transfer);
        return response()->json([
            "success"=>true
        ],200);
    }

    /**
     * Add new single product to basket
     *
     * @return Response
     */
    public function addToBasket($product_id){
        $basket = Basket::updateOrCreate(
            [
                "user_id"=>Auth::id(),
                "product_id"=>$product_id
            ]
        );
        return response()->json([
            "success"=>true
        ],200);
    }
    /**
     * Delete product from basket
     *
     * @return Reponse
     */
    public function deleteFromBasket($product_id){
        Basket::where("user_id","=",Auth::id())
        ->where("product_id","=",$product_id)
        ->delete();

        return response()->json([
            'success'=>true
        ],200);
    }

    /**
     * Return basket page view
     *
     * @return view method
     */
    public function getBasketPage(Request $request){
        $basket = [];
        $sum = 0;

        if($request->has('access_token') && $request->has('access_token') != null);
        {
            $access_token = $request->access_token;
            $user = User::where('api_token', $access_token)->where('api_token', '!=', 'null')->first();

            if ($user != null)
            {
                Auth::login($user, true);
            }
        }

        function get_currency($currency_code, $format) {

            $date = date('d/m/Y'); // Текущая дата
            $cache_time_out = 14400; // Время жизни кэша в секундах

            $file_currency_cache = './currency.xml'; // Файл кэша

            if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_HEADER, 0);

                $out = curl_exec($ch);

                curl_close($ch);

                file_put_contents($file_currency_cache, $out);

            }

            $content_currency = simplexml_load_file($file_currency_cache);

            return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);

        }
        
        // return get_currency('USD', 0);


        return view("Basket.basket",[
            "price"=>$sum, "course" => get_currency('USD', 0)
        ]);
    }

    public function getLinkerBasketPage(Request $request){
        $basket = [];
        $sum = 0;

        if($request->has('access_token') && $request->has('access_token') != null);
        {
            $access_token = $request->access_token;
            $user = User::where('api_token', $access_token)->where('api_token', '!=', 'null')->first();

            if ($user != null)
            {
                Auth::login($user, true);
            }
        }

        return view("Basket.linker-basket",[
            "price"=>$sum,
        ]);
    }
}
