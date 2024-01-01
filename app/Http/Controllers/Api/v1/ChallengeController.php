<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Gallery;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;

class ChallengeController extends Controller
{

    public $winners;

    public function savePhoto($file, $photo_name, $path, $exc, $index = 3, $preview = false)
    {
        $image = $file;
        $input['imagename'] = $photo_name;
        if(!Storage::exists("public/$path")) {
            Storage::makeDirectory("public/$path", 0775, true);
        }
        $destinationPath = storage_path()."/app/public/$path";
        $img = Image::make($image->getRealPath())->encode("webp", 70);



        $img->save($destinationPath.'/'.$input['imagename'].".$exc",70);
        $img->save($destinationPath.'/'.$input['imagename'].".webp",70);

        $img->resize(390, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']."_thumb.webp",65);
        $img->resize(390, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']."_thumb.$exc",65);

    }
    public function getChallengePage($alias){
        $challenge = Challenge::where("alias","=",$alias)->get()->first();
        if($challenge->deadline < date("Y-m-d")){
            return redirect()->to("/challenge/$alias/cards");
        }
        return view("challenges.page")->with("challenge",$challenge);
    }
    public function getAll(){
        $challenge = Challenge::query();
        return response()->json([
            "challenges"=>$challenge->take(10)->get()
        ]);
    }
    public function getChallengeResultsPage($alias, Request $request){
        $challenge = Challenge::query()->where("alias","=",$alias)->select("id","name","deadline")->get()->first();
        if($challenge->deadline < date("Y-m-d")){
            // if(!$request->get("page") || $request->get("page") == ){
                $this->winners = DB::table("challenge_winners")
                                ->where("challenge_id","=",$challenge->id)
                                ->select("gallery_id","prize_place")->get()->toArray();
            // }
        }
        $currentChallenge = Challenge::query()->whereDate("deadline",">=",date("Y-m-d"))->take(1)->select("alias")->get()->first();
        $cards = Gallery::where("galleries.challenge_id","=",$challenge->id)->where("moderation","=",true)->with(["user",
            "userAssessment"=>function($query){
                if(Auth::check()){
                    $user_id = Auth::id();
                    $query->where("user_id","=",$user_id);
                }else{
                    $query->where("user_id","=","-1");

                }
            }
        ])
        ->leftJoin('challenge_winners', 'galleries.id', '=', 'challenge_winners.gallery_id')
            ->orderBy('prize_place', 'DESC')
        ->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        },'userBookmarks'])
        ->paginate(9);
        if($this->winners != null){
            $cards = $cards->setCollection(
                $cards->getCollection()->map(
                    function($card){
                        $win_pos = array_search($card->id,array_column($this->winners,"gallery_id"));

                        if($win_pos !== false):
                            $card->winner_position = $this->winners[$win_pos]->prize_place;
                        endif;

                        return $card;
                    }
                )
            );
        }

        return view("challenges.cards",[
            "challengeInfo"=>$challenge,
            "cards"=>$cards,
            "currentChallenge"=>$currentChallenge,
            "alias"=>$alias,
        ]);
    }
    public function store(Request $request){
        $photos = array();
        $alias = mb_strtolower(str_replace(" ","-",$request->title));
        $alias = str_replace(["&","?","!"],"",$alias);
        if($request->photos){
          foreach ($request->photos as $key=>$image):
                $type = $image->getClientOriginalExtension();
                $photo_name = $key."_".time() . date("Y-m-d");
                $this->savePhoto($image,$photo_name,"/challenges/$alias",$type,$key);
                array_push($photos, $photo_name.".$type");
            endforeach;
        }
        $photos = count($photos) == 0 ? null : $photos;
        $image = $request->image;
        $photo_name = time().date("Y-m-d")."-preview";
        $type = $image->getClientOriginalExtension();
        $this->savePhoto($image,$photo_name,"/challenges/$alias",$type,0,true);
        $challenge = Challenge::create([
            'name'=>$request->title,
            'photos'=>json_encode($photos),
            'alias'=>$alias,
            'challenge_photo'=>$photo_name.".$type",
            'about'=>$request->about,
            'description'=>$request->description,
            'deadline'=>$request->deadline,
            'requirments'=>$request->requrements,
            'moderation'=>$request->moderation
        ]);
        if($challenge){
            return response()->json([
               'challenge'=>$challenge
            ]);
        }
        return response()->status(500);
    }
    public function destroy($id){
        $challenge = Challenge::find($id);
        Storage::deleteDirectory("public/challenges/".$challenge->alias);
        $challenge->delete();
        return response()->json([
            'success'=>true
        ],200);
    }
    public function getByAlias($alias){
        return Challenge::where("alias","=",$alias)->get()->first();
    }
    public function get(){
        $challenge = Challenge::query();
        return $challenge->select("id","alias","challenge_photo","name")->take(3)->get();
    }

    public function editChallenge($id,Request $request){
        $challenge = Challenge::find($id);
        $challenge->name = $request->title;
        $challenge->about = $request->about;
        $challenge->description = $request->description;
        $challenge->deadline = $request->deadline;
        $challenge->requirments = $request->requirments;
        $challenge->moderation =  $request->moderation;
        $alias = $challenge->alias;
        if($request->deletedPhotos){
            $photos = explode(",",$request->deletedPhotos);
            $now_photos = json_decode($challenge->photos);
            foreach($photos as $photo):
                $index = array_search($photo,$now_photos);
                $this->deletePhoto($photo,$alias);
                unset($now_photos[$index]);
            endforeach;
            $challenge->photos = json_encode($now_photos);
        }
        if($request->examples){
            $arr = [];
            foreach ($request->examples as $key=>$image):
                $type = $image->getClientOriginalExtension();
                if ($type == "jpg" || $type == "jpeg" || $type == "gif" || $type == "png") {
                    $photo_name = $key."_".time() . date("Y-m-d");
                    array_push($arr, $photo_name.".$type");
                    $this->savePhoto($image,$photo_name,"/challenges/",$alias,$type,$key);
                }
            endforeach;
            if($challenge->photos != null){
                $arr_ch = json_decode($challenge->photos);
                $arr = array_merge($arr_ch,$arr);
                $challenge->photos =  json_encode($arr);
            }else{
                $challenge->photos = json_encode($arr);
            }
        }
        if($request->deletedAvatar){
            $preview = explode(".", $challenge->challenge_photo);
            $this->deletePhoto($challenge->challenge_photo,$alias);
            $challenge->challenge_photo = null;
        }
        if($request->preview){
            if($challenge->challenge_photo != null){
                $this->deletePhoto($challenge->challenge_photo,$alias);
            }
            $image = $request->preview;
            $photo_name = time().date("Y-m-d")."-preview";
            $type = $image->getClientOriginalExtension();
            $this->savePhoto($image,$photo_name,"/challenges/".$alias,$type,0, true);
            $challenge->challenge_photo = $photo_name.".".$type;
        }
        $new_alias =  mb_strtolower(str_replace(" ","-",$request->title));
        if($alias != $new_alias){
            $challenge->alias = $new_alias;
            rename(storage_path()."/app/public/challenges/$alias",storage_path()."/app/public/challenges/$new_alias");

        }

        $challenge->save();
        return response()->json([
            "success"=>true,
            "challenge"=>$challenge,
        ],200);

    }

    public function deletePhoto($photo,$alias){
        $preview = explode(".",$photo);
        if(Storage::disk("public")->exists("/challenges/$alias/".$preview[0]."_thumb.".$preview[1])){
            Storage::disk("public")->delete("/challenges/$alias/".$preview[0]."_thumb.".$preview[1]);
        }
        if(Storage::disk("public")->exists("/challenges/$alias/".$preview[0]."_thumb.webp")){
            Storage::disk("public")->delete("/challenges/$alias/".$preview[0]."_thumb.webp");
        }
        if(Storage::disk("public")->exists("/challenges/$alias/".$preview[0].".".$preview[1])){
            Storage::disk("public")->delete("/challenges/$alias/".$preview[0].".".$preview[1]);
        }
        if(Storage::disk("public")->exists("/challenges/$alias/".$preview[0].".webp")){
            Storage::disk("public")->delete("/challenges/$alias/".$preview[0].".webp");
        }
    }

    public function getMaterials($id){
        $challenge = Challenge::query()->select("id","name")->where("id","=",$id)->with(["galleries.user","galleries.challengePosition"]);
        return $challenge->paginate("20");
    }
    public function winFunc(Request $request){
        /*if($request->prize_place == 1){
            DB::table("challenge_winners")
            ->where("challenge_id","=",$request->challenge)
            ->where("prize_place","=",$request->prize_place)
            ->delete();
        }*/
        DB::table("challenge_winners")
        ->updateOrInsert([
            "gallery_id"=>$request->gallery_id,
            "challenge_id"=>$request->challenge,
        ],[
            "prize_place"=>$request->prize_place,
        ]);
        return response()->json([
            'success'=>true
        ]);
    }

    public function getAllChallenges(){
        $challenge = Challenge::query();
        return $challenge->paginate(15);
    }

    public function removeWin(Request $request)
    {
        DB::table("challenge_winners")
            ->where("challenge_id", "=", $request->challenge_id)
            ->where("gallery_id", "=", $request->item_id)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function getAllGalleries($alias){
        $challenge = Challenge::query()
        ->where("alias","=",$alias)
        ->with(["galleries" => function($query){
            $query->with([
                "challengePosition",
                "user" => function($query){
                    $query->select("id","name");
                },
                "userAssessment"=>function($query){
                    if(isset($_COOKIE["user"])){
                        $user_id = json_decode($_COOKIE["user"])->id;
                        $query->where("user_id","=",$user_id);
                    }else{
                        $query->where("user_id","=","-1");

                    }
                }
                ])
                ->withCount(["likes AS likes_count" => function($query){
                    $query->where("like","=",true);
                },"likes as dislikes_count" => function($query){
                    $query->where("like","=",false);
                },'userBookmarks']);
        }]);
        return $challenge->paginate("20");
    }

    public function getOldChallenges(){
        $challenge = Challenge::whereDate("deadline","<",date("Y-m-d"))->select("id","name","alias")->take(15)->get();
        return response()->json(
            [
                "challenges"=>$challenge
            ]
        );
    }
}
