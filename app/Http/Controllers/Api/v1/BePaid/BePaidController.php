<?php

namespace App\Http\Controllers\Api\v1\BePaid;

use App\Action\Balance\BalanceUpdateAction;
use App\Action\Payment\PaymentStoreAction;
use App\Action\Payment\PaymentUpdateAction;
use App\Action\Payment\PaymentUpdateUserAction;
use App\Action\BePaid\BePaidWalletAction;
use App\Http\Controllers\Controller;
use App\Models\BalanceProject;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Transactions;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletUser;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use \Stripe\Stripe;

class BePaidController extends Controller
{
    public function getDonate(
        Request                 $request,
        PaymentUpdateUserAction $paymentUpdateUserAction,
        BalanceUpdateAction     $balanceUpdateAction,
        PaymentUpdateAction     $paymentUpdateAction
    )
    {
        Storage::put(rand() . '.txt', print_r($request->all(), true));
        $data = $request->input('transaction');
        $payment = Payment::query()->where([['payment_key', $data['tracking_id']], ['status', '!=', true]])->first();

        if ($data['status'] == 'successful' and $payment) {
            $paymentUpdateAction->handle($data['tracking_id']);
            $paymentUpdateUserAction->handle($payment->price, $data['tracking_id']);
        }
    }

    public function store(Request $request, PaymentStoreAction $paymentStoreAction)
    {
        $paymentStoreAction->handle($request->input('money'), $request->input('uuid'), $request->input('money') / 3);
    }

    public function getURL(Request $request): JsonResponse
    {
        return response()->json([
            'url' => ($request->input('status') == 'successful') ? route("cabinet-notifications") : route('home')
        ]);
    }

    public function wallet(Request $request, BePaidWalletAction $bePaidWalletAction)
    {
        $card = str_replace(' ', '', $request->input('cardNumber'));
        $data = explode('/',str_replace(' ', '', $request->input('expirationDate'))); // 0 - month 1 - year

        $getToken = $bePaidWalletAction->handle(
            $card,
            $data[0],
            2000 + $data[1]
        );

        if (isset($getToken['response']['message'])) {
            return response()->json([
                'message' => $getToken['response']['message']
            ], 400);
        }

        $client = new Client();
        $code = $getToken['issuer_country'];
        $response = $client->request('GET', "https://restcountries.com/v3.1/alpha/{$code}");

        $countries = json_decode($response->getBody()->getContents(), true);
        $currency = '';
        foreach ($countries[0]['currencies'] as $key => $item) {
            $currency = $key;
        }

        $exchangeResponse = $client->request('GET', "https://api.exchangerate-api.com/v4/latest/{$currency}");
        $exchangeRates = json_decode($exchangeResponse->getBody(), true)['rates'];

        $purchases_counts = WalletUser::query()
            ->whereHas('product', function ($query) {
                return $query->where('user_id', '=', auth()->user()->getAuthIdentifier());
            })
            ->select([
                DB::raw('SUM(price) as `price`'),
                DB::raw('DATE(created_at) as day'),
            ])->groupBy('day')
            ->get();

        if (count($purchases_counts) == 0) {
            return response()->json(['message' => 'Ваш баланс равен 0'], 400);
        }

//        return intval($purchases_counts[0]['price'] / $exchangeRates['USD'] - ($purchases_counts[0]['price'] / $exchangeRates['USD'] * 0.1)) ;

//        $user = Wallet::query()->where('user_id', auth()->user()->getAuthIdentifier())->first();

        $rateToUSD = $purchases_counts[0]['price'] / $exchangeRates['USD'] - ($purchases_counts[0]['price'] / $exchangeRates['USD'] * 0.1) ?? 'No rate available';

//
//        if ($purchases_counts[0]['price'] < 10) {
//            return response()->json(['message' => "The minimum payout amount is $10."], 400);
//        }

        if ($rateToUSD == 'No rate available') {
            return response()->json(['message' => $rateToUSD], 400);
        }


        $payload = [
            'amount' => intval($rateToUSD * 100),
            'currency' => $currency,
            'description' => $this->generateRandomUUID() . rand(),
            'recipient_credit_card' => [
                'number' => $card,
            ],
            'language' => ($currency === 'RUB') ? 'ru' : 'en',
            'tracking_id' => $this->generateRandomUUID() . rand(),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->withBasicAuth(1177, '115544832cb7fbf6eaf51c2b0643165882db0b3c7b86971c0473ce7ea7c35f50')
            ->post('https://gateway.bepaid.tech/transactions/payouts', ['request' => $payload]);

        $data = $response->json();

        if (isset($response['response']['message']) or isset($response['transaction']['status']) && $response['transaction']['status'] === 'failed') {
            Transactions::query()->create([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'sum' => $purchases_counts[0]['price'],
                'status' => 0,
                'stripe_info' => json_encode([])
            ]);


            if (isset($response['transaction']['status']) && $response['transaction']['status'] === 'failed') {
                return response()->json(['message' => $response['transaction']['message']], 400);
            }
            return response()->json(['message' => $data['response']['message']], 400);
        }

        if ($data['transaction']['status'] == 'successful') {
            Transactions::query()->create([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'sum' => $purchases_counts[0]['price'],
                'status' => 1,
                'stripe_info' => json_encode([])
            ]);

//            WalletUser::query()->where("user_id", auth()->user()->getAuthIdentifier())->update([
//                'bill' => 0
//            ]);

            User::query()->where('id', auth()->user()->getAuthIdentifier())->update([
                'models_count' => 0
            ]);

            WalletUser::query()
                ->whereHas('product', function ($query) {
                    return $query->where('user_id', '=', auth()->user()->getAuthIdentifier());
                })
                ->delete();

            return response()->json([
                'message' => 'You have successfully withdrawn the money. The page will be refreshed in 3 seconds',
            ]);
        }

    }

    public function generateRandomUUID()
    {
        return $this->generateRandomString(8) . '-' .
            $this->generateRandomString(4) . '-4' .
            $this->generateRandomString(3) . '-' .
            $this->generateRandomString(4) . '-' .
            $this->generateRandomString(12);
    }

    public function generateRandomString($length)
    {
        $characters = '0123456789abcdef';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }


}
