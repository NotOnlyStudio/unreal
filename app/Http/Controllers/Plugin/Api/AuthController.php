<?php

namespace App\Http\Controllers\Plugin\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Pasport;
use App\Models\Plugin\User;
use Illuminate\Support\Str;
use Auth;

class AuthController extends Controller
{
    public function getToken(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=> ['required','email'],
            'password'=>['required','string','min:8'],
        ]);

        if($validator->fails()){
            return 500;
        }

        $credentials = $request->only('email', 'password');
        if(\Illuminate\Support\Facades\Auth::attempt($credentials)){
            $user = \App\Models\User::where('email', $request['email'])->firstOrFail();
            $user->api_token = $user->createToken('auth_token')->plainTextToken;
            $user->mails = $request->mails ? true : false;
            $user->save();
            Auth::login($user);

            return response()->json([
                "message"=>"Auth was succeeded",
                "user"=>$user->only(["id","api_token","name"]),
            ],200);
        }

        return response()->json([
            'error'=>[
                "Error" => "Auth failed",
            ]
        ], 401);

       /* if($request->email && $request->password){
            $user = User::where("email","=",$request->email)
                            ->select("id","api_token","password","name")
                            ->first();
            if($user){
                if(\Hash::check($request->password,$user->password)){
                    $token = null;
                    if(!$user->api_token){
                        $user->api_token = Str::random(30);
                        $user->save();
                    }
                    $token = $user->api_token;
                    return response()->json([
                        "message"=>"Auth was succeeded",
                        "user"=>$user->only(["id","api_token","name"]),
                    ],200);
                }
            }
        }
        return response()->json([
            "Error" => "Auth failed",
        ],401);*/
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            "message"=>"Lof out succeeded"
        ],200);
    }

    public function getUserData(Request $request){
        $user = collect(Auth::user());
        $user['rang_text'] = Auth::user()->rang->name;
        $user->forget("email_verified_at");
        $user->forget("email_verification_token");
        $user->forget("password");

        if($request->get("fields")){
            $fields = explode(",",$request->fields);

            return response()->json([
                "user"=>$user->only($fields)
            ]);
        }else{
            return response()->json([
                "user"=>$user
            ]);
        }
    }

    public function support(Request $request)
    {

        $data = [
            'title' => $request->subject,
            'email' => $request->email,
            'description' => $request->description];


        $pos = strripos($request->description, '<');
        $pos2 = strripos($request->description, '>');

        if ($pos === false && $pos2===false) {
            $report = Report::insert($data);
            if($report)
            {
                return response()->json([
                    "success"=>true
                ]);
            }
            return response()->json([
                "success"=>false
            ]);
        }

        return response()->json([
            "Message" => "Forbidden....."
        ],403);
    }

    public function getSocialToken(Request $request){
        try{
            $g_user = null;
            $social_name = $request->get('driver');
            $user_id = $request->get('user_token');
            $username = $request->get('name');
            $email = $request->get('email');
            $user = null;

            if($social_name == "twitter")
                $user = \App\Models\User::where($social_name."_id",'=',$user_id)->get()->first();
            elseif ($social_name == "facebook")
                $user = User::where($social_name."_id",'=',$user_id)->get()->first();
            elseif ($social_name == "google")
                $user = User::where($social_name."_id",'=',$user_id)->get()->first();

            if(!$user):
                $driver_id = $social_name."_id";
                $user = \App\Models\User::firstOrCreate([
                    'name' =>  $username,
                    'email' =>$email,
                    'password'=>Hash::make($user_id),
                    $social_name."_id"=>$user_id,
                ]);
                $user->api_token = $user->createToken('auth_token')->plainTextToken;

                //Avatar

                //---
                $user->verified = 1;
                $user->save();
                \App\Models\NotificationCounter::create([
                    "user_id"=>$user->id
                ]);

                \Illuminate\Support\Facades\Auth::login($user);
                return [
                    "message"=>"Auth was succeeded",
                    "user"=>$user->only(["id","api_token","name"]),
                ];
            else:
                \Illuminate\Support\Facades\Auth::login($user);
                return [
                    "message"=>"Auth was succeeded",
                    "user"=>$user->only(["id","api_token","name"]),
                ];
            endif;
        }catch(Exception $e){
            return 500;
        }
    }
}
