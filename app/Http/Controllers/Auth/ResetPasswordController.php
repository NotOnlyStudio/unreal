<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    // use SendsPasswordResetEmails;
    
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = "/";


    // public function getResetPage(){
    //     return view("auth.passwords.email")->with("token",Str::random(30));
    // }

    // public function resetSend(Request $request){
    //     if($request->email):
    //         $user = \App\Models\User::where("email","=",$request->email)->firstOrFail();
    //         if($user){
    //             $user->sendPasswordResetNotification(Str::random(30));
    //             return response()->json([
    //                 "message"=>"Token was sended"
    //             ]);
    //         }else{
    //             return response()->json([
    //                 "message"=>"User not found"
    //             ],404); 
    //         }
    //     else:
    //         return response()->json(
    //             [
    //                 "message"=>"No data"
    //             ],
    //             400
    //         );
    //     endif;
    // }
}
