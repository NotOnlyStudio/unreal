<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SkrillPayment;
use Illuminate\Http\Request;
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;
use Redirect;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use Auth;
use User;

class SkrillPaymentController extends Controller
{


    /**
     * Construct.
    */
    private $skrilRequest;

    public function __construct()
    {


        $this->skrilRequest = new SkrillRequest();
        $this->skrilRequest->pay_to_email = 'unreal.shop7@gmail.com';
        $this->skrilRequest->return_url = '127.0.0.1:8000/payment-completed';
        $this->skrilRequest->cancel_url = '127.0.0.1:8000/payment-cancelled';
        $this->skrilRequest->logo_url = 'https://cdn.iconscout.com/icon/free/png-512/unreal-engine-555438.png';
        $this->skrilRequest->status_url = 'unreal.shop7@gmail.com';
        $this->skrilRequest->status_url2 = 'unreal.shop7@gmail.com';
    }


   /**
     * Make Payment
     */
    public function makePayment(Request $request)
    {
        $tr_id = 'ushop_trs_'.time();
        $this->skrilRequest->transaction_id = $tr_id; // generate transaction id
        $this->skrilRequest->amount = $request->counts;
        $this->skrilRequest->currency = 'USD';
        $this->skrilRequest->language = 'EN';
        $this->skrilRequest->prepare_only = '1';
        $this->skrilRequest->merchant_fields = 'UnrealShop, '.$request->email;
        $this->skrilRequest->site_name = 'UnrealShop';
        $this->skrilRequest->customer_email = $request->email;
        // $this->skrilRequest->detail1_description = 'User '.$request->name.' request sell '$request->counts.' models';
        $this->skrilRequest->detail1_text = '1';
    
        // create object instance of SkrillClient
        $client = new SkrillClient($this->skrilRequest);
        $sid = $client->generateSID(); //return SESSION ID

        // handle error
        $jsonSID = json_decode($sid, true);
        
        if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
            return $jsonSID->message;

        $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url

        if($redirectUrl != null)
        {
            Payment::insert([
                "user_id"=>Auth::id(),
                "counts"=>$request->counts,
                "price"=>$request->counts,
                "transaction_id"=>$tr_id,
            ]);
            return response()->json([
                "url"=>$redirectUrl
            ]);
        }
        // return Redirect::to($redirectUrl); // redirect user to Skrill payment page
    }

    /**
     * Do Refund
     */
    public function doRefund()
    {
        // Create object instance of SkrillRequest
        $prepare_refund_request = new SkrillRequest();
        // config
        $prepare_refund_request->email = 'merchant_email';
        $prepare_refund_request->password = 'api_password';
        $prepare_refund_request->refund_status_url = 'refund_status_url';
        // request
        $prepare_refund_request->transaction_id = 'MNPTTX0001';
        $prepare_refund_request->amount = '5.56';
        $prepare_refund_request->refund_note = 'Product no longer in stock';
        $prepare_refund_request->merchant_fields = 'site_name, customer_email';
        $prepare_refund_request->site_name = 'Your Website';
        $prepare_refund_request->customer_email = 'customer@example.com';

        // do prepare refund request
        $client_prepare_refund = new SkrillClient($prepare_refund_request);
        $refund_prepare_response = $client_prepare_refund->prepareRefund(); // return sid or error code

        // refund requests
        $refund_request = new SkrillRequest();
        $refund_request->sid = $refund_prepare_response;

        // do refund
        $client_refund = new SkrillClient($refund_request);
        $do_refund = $client_refund->doRefund();
        dd($do_refund); // response
    }


    /**
     * Instant Payment Notification (IPN) from Skrill
     */
    public function ipn(Request $request)
    {
        // skrill data - get more fields from Skrill Quick Checkout Integration Guide 7.9 (page 23)
        $transaction_id = $request->transaction_id;
        $mb_transaction_id = $request->mb_transaction_id;
        $invoice_id = $request->invoice_id; // custom field
        $order_from = $request->order_from; // custom field
        $customer_email = $request->customer_email; // custom field
        $biller_email = $request->pay_from_email;
        $customer_id = $request->customer_id;
        $amount = $request->amount;
        $currency = $request->currency;
        $status = $request->status;

        // status message
        if ($status == '-2') {
            $status_message = 'Failed';
        } else if ($status == '2') {
            $status_message = 'Processed';
            $payment = Payment::where("transaction_id","=",$transaction_id)->first();
            $user = User::find($payment->user_id);
            $user->models = $payment->counts;
            $user->save();
            $payment->status = "payed";
            $payment->save();
        } else if ($status == '0') {
            $status_message = 'Pending';
        } else if ($status == '-1') {
            $status_message = 'Cancelled';
        }
        // now store data to database
        $skrill_ipn = new SkrillPayment();
        $skrill_ipn->transaction_id = $transaction_id;
        $skrill_ipn->mb_transaction_id = $mb_transaction_id;
        $skrill_ipn->invoice_id = $invoice_id;
        $skrill_ipn->order_from = $order_from;
        $skrill_ipn->customer_email = $customer_email;
        $skrill_ipn->biller_email = $biller_email;
        $skrill_ipn->customer_id = $customer_id;
        $skrill_ipn->amount = $amount;
        $skrill_ipn->currency = $currency;
        $skrill_ipn->status = $status_message;
        $skrill_ipn->created_at = Date('Y-m-d H:i:s');
        $skrill_ipn->updated_at = Date('Y-m-d H:i:s');
        $skrill_ipn->save();
        

    }
}
