<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Save video to local settings file(.txt)
     */
    public function saveVideo(Request $request){
        // return $request->all();
        File::put(resource_path()."/local-settings/video.txt", $request->background);
        return response()->json([
            "success"=>true
        ],200);
    }

    public function socialsSave(Request $request){
        File::put(resource_path()."/local-settings/links.json", $request->links);
        return response()->json([
            "success"=>true
        ],200);
    }

    public function getLinks(Request $request){
        return response()->json([
            "links"=>File::get(resource_path()."/local-settings/links.json")
        ]);
    }

    public function getStatus(Request $request){
        return Setting::where('name', 'technical_service')->first()->value('value');
    }

    public function changeStatus(Request $request){
        Setting::where('name', 'technical_service')->first()->update(['value' => $request->get('active')]);
        return $request;
    }

    public function getVideo(){
        return response()->json([
            "video"=>File::get(resource_path()."/local-settings/video.txt")
        ]);
    }

    public function setMeta(Request $request){
        File::put(resource_path()."/local-settings/meta.txt", $request->meta);
        return response()->json([
            "success"=>true,
            "meta"=>$request->all()
        ],200);
    }

    public function getPolicyName($policy){
        $title = "";
        switch ($policy){
            case "terms-of-use":
                $title = "Terms of use";
                break;
            case "privacy-policy":
                $title = "Privacy policy";
                break;
            case "cookie-policy":
                $title = "Cookie policy";
                break;
            case "content-policy":
                $title = "Content policy";
                break;
        }
        return $title;
    }
    public function getPolicyPage($policy){
        if(File::exists(resource_path()."/local-settings/$policy.txt")):
            $title = $this->getPolicyName($policy);
            return view("policty",[
                "title"=>$title,
                "content"=>File::get(resource_path()."/local-settings/$policy.txt")
            ]);
        else:
            abort(404);
        endif;
    }

    public function getServicePage(){
            return view("technical");
    }

    public function setPolicy(Request $request){
        if($request->polytics_file){
            $name = $request->type;
            $request->polytics_file->move(public_path("/polytics/"),$name.".pdf");
            return response()->json([
                "success"=>true
            ]);
        }
        return response()->json([
            "error"=>"Files count = 0"
        ],500);
    }
    public function getPolicy(Request $request){
        $type = $request->get("name");
        if($type){
            return response()->json([
                "content"=>File::get(resource_path()."/local-settings/$type.txt"),
                "title"=>$this->getPolicyName($type)
            ]);
        }
    }

    public function getMainMeta(){
        return response()->json([
            "meta"=>\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt")
        ]);
    }
}
