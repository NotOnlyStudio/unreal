<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\ApiOperations\Create;
use \Stripe\Charge;
use \Stripe\StripeClient;
use App\Models\Payment;
use App\Models\User;

class PaymentsController extends Controller
{
    private $secret_key;
    private $public_key;
    private $webhook_key;
    public $default_price;
    public $site;

    public function __construct()
    {
        $this->secret_key = config('app.payments.secret');
        $this->public_key = config('app.payments.publish');
        $this->webhook_key = config('app.payments.webhook');
        $this->default_price = config('app.payments.price');
        $this->site = config('app.url');
    }

    /**
     * Get and return public api token for FrontEnd
     *
     * @return Response
     */
    public function getPublicToken()
    {
        return response()->json([
            "key" => $this->public_key,
        ]);
    }

    /**
     * Set api key for Stripe SDK
     *
     * @return void
     */
    public  function setApiKey()
    {
        Stripe::setApiKey($this->secret_key);
    }

    /**
     * Попытка чекаута
     */
    public function autoCheckout(Request $request)
    {
        $count = $request->models_count;
        if ($count && $count > 0) :
            $user = Auth::id();
            $price = config('app.payments.price');
            $price = $count * $price * 100;
            Stripe::setApiKey($this->secret_key);
            $payment_key = \Illuminate\Support\Str::random();
            Payment::create([
                "user_id" => $user,
                "payment_key" => $payment_key,
                "counts" => $count,
                "price" => $price
            ]);
            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $price,
                        'product_data' => [
                            'name' => 'Buying ' . $count . ' models',
                            'images' => [$this->site . "/assets/logo-unreal.png"]
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->site . "/stripe/checkout/confirm/" . $payment_key,
                'cancel_url' => $this->site . "/stripe/checkout/cancel/" . $payment_key,
                'metadata' => ["user_id" => $user,"payment_key" => $payment_key,"counts" => $count],
            ]);
            return redirect()->to($checkout_session->url);
        else :
            return redirect()->back();
        endif;
    }

    public function cancelPayment($token)
    {
        $user = Auth::id();
        Payment::where("user_id", $user)->where("payment_key", $token)->delete();
        return redirect()->route("home");
    }
    public function confirmPayment($token)
    {
//        $user = Auth::user();
//        $payment = Payment::where("payment_key", $token)->first();
//        $user->models_count += $payment->counts;
//        $user->save();
//        $payment->status = true;
//        $payment->save();
        return redirect()->route("cabinet-notifications");
    }
    public function     confirmPaymentWH(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $this->webhook_key
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }
        $requestData = $event->data->object->metadata;

        $user = User::where("id", $requestData->user_id)->first();
        $payment = Payment::where("payment_key", $requestData->payment_key)->first();
        $user->models_count += $payment->counts;
        $user->save();
        $payment->status = true;
        $payment->save();
//        return redirect()->route("cabinet-notifications");
    }

    public function deleteAccount($acc)
    {
        $stripe = new \Stripe\StripeClient($this->secret_key);
        $stripe->accounts->delete($acc);
    }

    public function checkAccount()
    {
        $stripe = new \Stripe\StripeClient(
            $this->secret_key
        );
        $user = Auth::user();
        $info = $stripe->accounts->retrieve(
            $user->stripe_account,
        );
        if ($info->details_submitted) {
            $user->stripe_success = 1;
            $user->save();
        }
        return redirect()->route("cabinet-notifications");
    }

    public function register()
    {
        Stripe::setApiKey($this->secret_key);
//        Config::get('constants.langs');
        $user = Auth::user();
        $country_sub = stripos($user->location, " ");
        if ($country_sub !== false) {
            $country_sub = $user->location[0] . $user->location[$country_sub + 1];
        } else {
            $country_sub = $user->location[0] . $user->location[1];
        }
        $account = \Stripe\Account::create([
            'type' => 'express',
            'country' => mb_strtoupper($country_sub),
            'email' => $user->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);
        $account = $account->id;
        $user->stripe_account = $account;
        $account_links = \Stripe\AccountLink::create([
            'account' => $account,
            'refresh_url' => $this->site . '/stripe/register',
            'return_url' => $this->site . '/stripe/check',
            'type' => 'account_onboarding',
        ]);
        $user->stripe_link = $account_links->url;
        $user->save();
        return redirect()->to($user->stripe_link);
    }

    public function finish_register()
    {
        $user = Auth::user();
        $user->stripe_success = 1;
        $user->save();
        /**
         * There was be check logic
         */
        return redirect()->route("/cabinet");
    }

    public function transfer(Request $request)
    {
        $user = Auth::user();
        if (!$user->location) return response()->json([
//            "answer" => config('errorMessages.mustSpecifyRegion')
            "answer" => "To receive payments, you must specify the region of residence."
        ], 400);
        if (!$user->stripe_account) return response()->json([
//            "answer" => config('ErrorMessages.needStripeRegistration')
            "answer" => "To receive payments, you need to go through an additional mini-registration."
        ], 400);
        if ($user->stripe_success == 0){
            $stripe = new \Stripe\StripeClient($this->secret_key);

            $info = $stripe->accounts->retrieve(
                $user->stripe_account,
            );

            if (!empty($info->requirements->errors[0]->reason)){
                return response()->json([
                    "answer" => $info->requirements->errors[0]->reason
                ], 400);
            }

            if ($info->details_submitted) {
                $user->stripe_success = 1;
                $user->save();
            }
            else{
                return response()->json([
                    "answer" => "To receive payments, you must go through an fill in all required fields in your account in Stripe."
                ], 400);
            }
        }
        $user_wallet = \App\Models\Wallet::where("user_id", $user->id)->first();
        if ($user_wallet->bill >= $request->money) {
            Stripe::setApiKey($this->secret_key);

            $payout = \Stripe\Transfer::create([
                "amount" => $request->money * 100,
                "currency" => "usd",
                "destination" => $user->stripe_account,
            ]);
            $user_wallet->bill -= $request->money;
            return response()->json([
                "answer" => "Transfer was succesed",
                "stripe_answer" => $payout
            ]);
        } else {
            return response()->json([
                "answer" => "Insufficient funds"
            ], 402);
        }
        return response()->json([
            "answer" => "Undefined error"
        ], 500);
    }

    public function getClientInfo($account)
    {
        $stripe = new \Stripe\StripeClient(
            $this->secret_key
        );
        $info = $stripe->accounts->retrieve(
            $account,
        );
        dd($info);
    }

    // public function transfer($transfer){
    //     \Stripe\Stripe::setApiKey($this->secret_key);
    //     // $stripe = new \Stripe\StripeClient(
    //         // $this->secret_key
    //     // );
    //     $transfer = \Stripe\Transfer::create([
    //         "amount" => 1000,
    //         "currency" => "usd",
    //         "destination" => "acct_1JD66KPit7hl92Mz",
    //       ]);

    //     // $acc = $stripe->accounts->retrieve(
    //         // 'acct_1JD66KPit7hl92Mz',
    //         // []
    //     // );
    //       dd($transfer);
    // }
}
