<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use DB;
use Illuminate\Support\Str;
use \App\Models\User;
use Hash;
use Auth;
use function PHPUnit\Framework\returnArgument;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function getResetPage(){
        return view("auth.passwords.email");
    }

    public function resetSend(Request $request){
        $user = User::where('email', '=', $request->email)
        ->firstOrFail();
        //Check if the user exists
        if (!$user) {
            return response()->json([
                "User not found"
            ],422);
        }
        $token = Str::random(60);
        //Create Password Reset Token
        if(DB::table('password_resets')->where("email","=",$request->email)->count() == 0):
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        else:
            DB::table('password_resets')->where("email","=",$request->email)->update([
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        endif;

        if ($this->sendResetEmail($user, $token)) {
            return response()->json(
                ["message"=>"Message was been sended"]
            );
        } else {
            return response()->json(
                ["message"=>"Message wasnt been sended"],
                500
            );
        }
    }

    public function sendResetEmail($user,$token){
        try{
            $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);
            // Notification::send(new ResetPasswordNotification($link));
            $user->notify(new ResetPasswordNotification($link));
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function doReset(Request $request){
        if($request->password != ""){
            $user = User::where("email",$request->email)->first();
            $user->password = bcrypt($request->toArray()['password']);
            $user->save();
            DB::table('password_resets')->where("email","=",$request->email)->delete();
            Auth::login($user);
            return response()->json([
                "message"=>"Reset"
            ]);
        }
        return response()->json([
            "error"=>true
        ]);
    }

    public function getReset($token,Request $request){
        $email = null;
        if($request->get('email'))
            $email = $request->get('email');
        if($email != null)
            return view("auth.passwords.reset",[
                "email"=>$email,
                "token"=>$token
            ]);
        else
            abort(404);
    }
}
