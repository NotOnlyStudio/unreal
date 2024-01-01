<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = ForumSection::query();
        return $query->get();
    }

    public function forumsForUser(Request $request){
        $gallery = ForumSection::query();
        if($request->searchBy){
            $gallery->where("title","LIKE","%".$request->searchBy."%");
        }
        return $gallery->with(["forums"=>function($query){
            $query->whereHas("forumsForUser")->withCount(["topics","userBookmarks"]);
        }])->get();
    }

    public function getAll(){
        return ForumSection::with(["forums"=>function($query){
            $query->withCount(["topics","userBookmarks"]);
        }])->orderBy("id","desc")->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->icon && $request->title){
            $icon = null;
            if($request->icon){
                $icon = $request->icon->getClientOriginalName();
                $request->icon->storeAs(
                    'public/forums/icons/',$icon
                );
            }
            $section = ForumSection::create(
                [
                    'title'=>$request->title,
                    'icon'=>$icon,
                ]
            );
            if($section){
                return response()->json([
                    'success'=>true,
                    'section'=>$section
                ]);
            }
            return response()->json([
                'success'=>false
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $forum = ForumSection::find($id);
        if($request->title) $forum -> title = $request->title;
        if($request->icon){
            Storage::disk("public")->delete("/forums/icons/".$forum->icon);
            $icon = $request->icon->getClientOriginalName();
            $request->icon->storeAs(
                'public/forums/icons/',$icon
            );
            $forum->icon = $icon;
        }
        $forum->save();
        if($forum){
            return response()->json([
                'success'=>true,
                'section'=>$forum,
            ]);
        }
        return response()->json([
            'success'=>false
        ]);
    }
    public function changeShowing(Request $request, $id){
        $forum = ForumSection::find($id);
        $forum -> showing = $request->showing;
        $forum -> save();
        if($forum){
            return response()->json([
                'success'=>true,
            ]);
        }
        return response()->json([
            'success'=>false
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $section = ForumSection::find($id);
            if($section->icon){
                Storage::disk("public")->delete("/forums/icons/".$section->icon);
            }
            ForumSection::where("id",'=',$id)->delete();
            return response()->json([
                'success'=>true,
            ]);
        }
        catch(Exception $ex){
            return responce()->json([
                'success'=>false,
                'error'=>$ex
            ]);
        }
    }
}
