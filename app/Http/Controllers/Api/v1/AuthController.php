<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use \App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Cookie;
use Mail;
use App\Mails\VerificationEmail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterConfirmation;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function __construct()
    {

        $this->middleware("auth:api")->only("logout");
    }

    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }

    /**
     * Make view for login page
     *
     * @return View
     */
    public function getLoginPage(){
        return view("login");
    }

    public function getRegisterPage(){
        return view("register");
    }
    public function redirectTo($driver)
    {
        if($driver == "google" || $driver == "facebook" || $driver == "twitter")
            return Socialite::driver($driver)->redirect();
        else
            return redirect("/login");
    }

    public function handleCallback($driver)
    {
        try{
            $g_user = null;
            if($driver == "twitter")
                $g_user = Socialite::driver('twitter')->user();
            else
                $g_user = $driver == "facebook" ? Socialite::driver($driver)->user() : Socialite::driver($driver)->stateless()->user();

            $user = User::where($driver."_id",'=',$g_user->id)->get()->first();
            if(!$user):
                $driver_id = $driver."_id";
                $user = User::firstOrCreate([
                    'name' =>  $g_user->name,
                    'api_token' => $g_user->token,
                    'email' =>$g_user->email,
                    'password'=>Hash::make($g_user->token),
                    $driver."_id"=>$g_user->id,
                ]);
                //Avatar
                if($g_user->getAvatar()):
                    $fileContents = curl_get_file_contents($g_user->getAvatar());
                    $file_name = "user-".$user->id.".jpg";
                    Storage::disk("public")->put("avatars/$file_name",$fileContents);

                    $user->photo = $file_name;
                endif;
                //---
                $user->verified = 1;
                $user->save();
                \App\Models\NotificationCounter::create([
                    "user_id"=>$user->id
                ]);
                Auth::login($user);
                return redirect("/cabinet/notifications");
            else:
                Auth::login($user);
                return redirect("/cabinet/notifications");
            endif;
        }catch(Exception $e){
            return redirect("/login");
        }

    }

    public function register(Request $request){
        $validator =  Validator::make($request->all(), [
            'email' => ['required', 'email', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validator->fails()){
            return 500;
        }

        $user = new User($request->all());
        $user->password = bcrypt($request->password);

        if($user->save()){
            $user->email_verification_token = $user->createToken('auth_token')->plainTextToken;
            $user->save();

            \App\Models\NotificationCounter::create([
                "user_id"=>$user->id
            ]);
            \App\Models\Wallet::create([
                "user_id"=>$user->id
            ]);

            $url = config('base_url') . 'register/confirm/' . $user->email_verification_token;

            try{
                $user->notify(new \App\Notifications\RegisterConfirmation($url));
            }
            catch(Exception $err){
                return response()->json(["err"=>true],300);
            }

            \Cookie::queue('emailConfirm', true, 5);

            return response()->json([
                'success'=>true,
             ]);
        }else{
            return "Ошибка";
        }

    }
    public function confirmEmail($token){
        $user = User::where("email_verification_token",$token)->first();
        $user->verified=true;
        $user->save();
        \Cookie::queue(Cookie::forget("emailConfirm"));
        Auth::login($user);

        return \Redirect::to("/cabinet");
    }

    public function loginStandart(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where("email","=",$request->email)->update([
                "mails"=>$request->mails == "false" ? false : true
            ]);
            return "is auth";
        }else{
            return response()->json([
                'error'=>[
                    'error_msg'=>"Invalid data",
                ]
            ],422);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=> ['required','email'],
            'password'=>['required','string','min:8'],
        ]);

        if($validator->fails()){
            return 500;
        }

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            $user = User::where('email', $request['email'])->firstOrFail();
            $user->api_token = $user->createToken('auth_token')->plainTextToken;
            $user->mails = $request->mails ? true : false;
            $user->save();
            Auth::login($user);

            return response()->json([
                'user'=>$user
            ],200);
        }

        return response()->json([
            'error'=>[
                'error_msg'=>"Invalid data", //Invalid login details
            ]
        ], 422);
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        $user->tokens()->delete();
        Auth::logout();

        return redirect()->route("home");
    }

    public function getUserData($id){
        $user = User::where("id","=",$id)
        ->with(["rang"])
        ->get()->first();

        return response()->json([
            'user'=>$user
        ]);
    }

    public function getSocialToken(Request $request){
        try{
            $g_user = null;
            $social_name = $request->get('driver');
            $user_id = $request->get('user_id');

            if($social_name == "twitter")
                $user = User::where($social_name."_id",'=',$user_id)->get()->first();
            elseif ($social_name == "facebook")
                $user = User::where($social_name."_id",'=',$user_id)->get()->first();
            elseif ($social_name == "google")
                $user = User::where($social_name."_id",'=',$user_id)->get()->first();

            if(!$user):
                $driver_id = $social_name."_id";
                $user = User::firstOrCreate([
                    'name' =>  $g_user->name,
                    'api_token' => $g_user->token,
                    'email' =>$g_user->email,
                    'password'=>Hash::make($g_user->token),
                    $social_name."_id"=>$g_user->id,
                ]);
                //Avatar
                if($g_user->getAvatar()):
                    $fileContents = curl_get_file_contents($g_user->getAvatar());
                    $file_name = "user-".$user->id.".jpg";
                    Storage::disk("public")->put("avatars/$file_name",$fileContents);

                    $user->photo = $file_name;
                endif;
                //---
                $user->verified = 1;
                $user->save();
                \App\Models\NotificationCounter::create([
                    "user_id"=>$user->id
                ]);
                Auth::login($user);
                return redirect("/cabinet/notifications");
            else:
                Auth::login($user);
                return redirect("/cabinet/notifications");
            endif;
        }catch(Exception $e){
            return redirect("/login");
        }
    }

}
