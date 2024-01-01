<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;


class UserController extends Controller
{
    public $tablesList = [
        "Notifications" => 'notifications'
    ];

    //возможно не понадобится
    public function clearNotifications($notifyName){
        $table = $this->tableList[$notifyName];
        $user_id = Auth::id();
        DB::upate("
            UPDATE `$table` SET `view` = `1` WHERE `user_id` = `$user_id`
        ");

        return true;
    }

    public function uploadArticleImg(Request $request){
        $user_id = Auth::id();

        $image = $request->file('file');
        $exc = $image->getClientOriginalExtension();
        $input['imagename'] = time().'.'.$exc;

        if(!Storage::exists("public/articles-photos/user-$user_id")) {
            Storage::makeDirectory("public/articles-photos/user-$user_id", 0775, true);
        }

        $destinationPath = storage_path()."/app/public/articles-photos/user-$user_id";
        $img = Image::make($image->getRealPath())->encode($exc, 70);

        if($image->getSize() > 1000000):
            $img->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename'],70);
        else:
            $img->save($destinationPath.'/'.$input['imagename'],45);
        endif;

        return response()->json([
            'location' => "/storage/app/public/articles-photos/user-$user_id/" . $input['imagename']
        ],200);
    }

    public function getNotificationsList($inner = false){
        $user_id = Auth::id();
        $result = DB::select("
            SELECT
                (SELECT COUNT(*) FROM `notifications` WHERE `user_id`= '".$user_id."' AND `view` = 0) as Notifications
        ");

        if($inner == false){
            return response()->json($result);
        }else{
            return $result;
        }
    }

    public function getNotificationsCount(){
        $notifications = $this->getNotificationsList(true);
    }

    public function getUserPage($id)
    {
        if (Auth::check()):
            if (Auth::id() != $id) {
                $user = User::where("id", "=", $id)
                    ->with("rang")
                    ->where("ban", false)
                    ->select("id", "name", "specialization", "website", "signature", "photo", "rating", "email")
                    ->get()
                    ->first();
                if ($user) {
                    return redirect("/user/forum/$id");
                } else
                    abort(404);
            }
            return redirect("/cabinet/notifications");
        else:
            return redirect("/login");
        endif;
    }
    /**
     * Changing user photo
     *
     * @return Response
     */
    public function uploadAvatar(Request $request){
        if($request->avatar){
            $user = User::find(Auth::id());
            $file_name = "user-".$user->id.".".$request->avatar->getClientOriginalExtension();

            if($user->photo){
                if(Storage::disk("public")->exists("/avatars/".$user->photo))
                {
                    Storage::disk("public")->delete("/avatars/".$user->photo);
                }
            }
            $request->avatar->storeAs(
                "public/avatars/",$file_name
            );
            $user->photo = $file_name;

            if($user->save()){
                return response()->json([
                    'user_photo'=>$user->photo
                ],200);
            }
        }

        return response("failed",500);
    }

    public function getUsers(Request $request){
        $user = User::query();

        if($request->get('name')){
            $user->where('name','LIKE','%'.$request->get("name").'%');
        }

        if($request->get("banned") && $request->get("banned") == "1"){
            $user->where("ban","=",true);
        }

        if ($request->has('delete')){
            $user->onlyTrashed();
        }

        $users = $user->with("purchases")->orderBy("created_at","desc")->paginate(20);
        $modelsUsed = 0;
        foreach ($users as $user){
            $modelsUsed = count($user->purchases);
            $user->models_used = $modelsUsed;
        }


        return $users;
    }

    public function AddModelToUser($id, $models){
        $user = User::find($id);
        $user->models_count += $models;
        $user->save();

        return response() -> json([
            'status'=>true
        ]);
    }

    public function changeData(Request $request){
        $user = Auth::user();

        if( $user->permisions == 'ADMIN' || $user->id == $request->user_id){
            $user = User::find($request->user_id);
            if($request->avatar){
                if($user->photo != null){
                    Storage::disk("public")->delete("/avatars/user-".$user->id);
                }
                $request->avatar->storeAs(
                    "public/avatars", "user-".$user->id
                );
                $user->photo = "user-".$user->id;
            }
            if($request->password){
                $password = Hash::make($request->password);
                $user->password = $password;
            }
            if($request->name) $user->name = $request->name;
            if($request->email) $user->email = $request->email;
            if($request->specialization) $user->specialization = $request->specialization;
            if($request->location) $user->location = $request->location;
            if($request->website) $user->website = $request->website;
            if($request->signature) $user->signature = $request->signature;
            $user->save();
            if($user){
                return response()->json([
                    'user'=>$user
                ]);
            }
        }

        return response()->json([
            'error'=>true
        ], 500);
    }

    public function changeLocation(Request $request){
        $location = $request->location;
        $user = Auth::user();
        if($user->location == null){
            $user->location = $location == 'None' ? null : $location;
            $user->save();

            return response()->json([
                "answer"=>"success"
            ]);
        }

        return response()->json([
            "answer"=>"failed"
        ]);
    }

    public function setBanned($id){
        $user = User::find($id);
        $user->ban = !$user->ban;
        $user->save();

        return response() -> json([
            'status'=>true
        ]);
    }

    public function unban($id)
    {
        User::where("id",'=',$id)->update([
            'ban'=>false
        ]);

        return response() -> json([
            'status'=>true
        ]);
    }

    public function deleteUser($id){
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function forceDeleteUser($id){
        $user = User::withTrashed()
            ->where('id', $id)
            ->forceDelete();

        return response()->json([
            'success' => true
        ]);
    }

    public function restoreUser($id): \Illuminate\Http\JsonResponse
    {
        $user = User::withTrashed()
            ->where('id', $id)
            ->restore();

        return response()->json([
            'success' => true
        ]);
    }

    public function queryUser(Request $request){
        if($request->get("name")){
            return User::where("name","LIKE","%".$request->get("name")."%")->take(10)->get();
        }
    }

    public function getUser($id){
        $user = User::find($id);
        if($user){
            return  response()->json([
                'success'=>true,
                'user'=>$user
            ]);
        }

        return response()->json([
            'success'=>false
        ]);
    }

    public function editUser(Request $request){
        $photo = null;
        $user = User::find($request->id);

        if($request->file("photo")){
            if($user->photo){
                if(Storage::disk("public")->exists("/avatars/".$user->photo))
                {
                    Storage::disk("public")->delete("/avatars/".$user->photo);
                }
                $user->photo = null;
            }
            $photo = "user-".$request->id.".".$request->file('photo')->getClientOriginalExtension();

            $request->file("photo")->storeAs(
                'public/avatars/',$photo
            );
            $user->photo = $photo;
        }

        if($request->password){
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
           'success'=>true,
        ]);
    }

    public function getPermitedUsers(){
        $users = User::where("permisions","=","ADMIN")
        ->orWhere("permisions","=","MODERATOR")
        ->select("id","name","permisions")
        ->get();

        return response()->json(
            [
                "users"=>$users
            ]
        );
    }

    public function removePermission($id){
        User::where("id","=",$id)->update([
            "permisions"=>"USER"
        ]);

        return response()->json([
            "success"=>true
        ]);
    }

    public function setPermission($id,Request $request){
        $user = User::find($id);
        $user->permisions = $request->permission;
        $user->save();

        return response()->json([
            "user"=>[
                "name"=>$user->name,
                "id"=>$user->id,
                "permissions"=>$user->permisions,
            ]
        ]);
    }

    public function getUserByName(Request $request){
        $users = User::where("name","LIKE","%".$request->name."%")->take(10)->select("id","name","permisions")->get();

        return response()->json([
            "users"=>$users
        ]);
    }


    public function usersStatistic()
    {
        $subdays = \Carbon\Carbon::now()->subDays(30);
        $users = User::query();
        $users = $users->select([
            DB::raw('count(id) as `count`'),
            DB::raw('DATE(created_at) as day'),
        ])->groupBy("created_at")
            ->where('created_at', '>=', $subdays)
            ->get()->toArray();

        return response()->json([
            "users" => $users
        ]);
    }
}
