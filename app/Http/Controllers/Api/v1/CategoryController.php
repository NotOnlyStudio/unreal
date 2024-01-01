<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Category::query();
        if($request->get("order")){
            $query = $query -> orderBy("order", (String)$request ->get("order"));
        }
        return $query->get();
    }

    /**
     * Display for API
     */
    public function apiCategories(Request $request){
        $query = Category::query()
                ->select("id","name","image","parent","order");
        if($request->get("order")){
            $query = $query -> orderBy("order", (String)$request ->get("order"));
        }
        return response()->json([
            "categories"=>$query->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cat_photo = null;
        if($request->photo):
        $cat_photo = time().$request->photo->getClientOriginalName();
        $request->photo->storeAs(
            "public/categories/",$cat_photo
        );
        endif;
        $category = Category::create([
            'name'=>$request->name,
            'parent'=> $request->parent,
            'image'=>json_encode($cat_photo),
        ]);

        if(!$category){
            return response()->json([
                'success'=>false,
            ]);
        }

        return response()->json([
            'success'=>true,
            'item'=>$category,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        return  $request -> all();
        if($request->category){

        }
        return response() -> json([
           'success'=>false
        ]);
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
        $photo_name = $request->photoName;
        if($request->file("photo")){
            if(Storage::disk("public")->exists("/categories/".$photo_name))
            {
                Storage::disk("public")->delete("/categories/".$photo_name);
            }
            $photo_name = time().$request->photo->getClientOriginalName();
            $request->photo->storeAs(
                "public/categories/",$photo_name
            );
        }

        $to_update = array(
            'name'=>$request->name,
            'order'=>$request->order,
            'parent'=>$request->parent,
            'photo'=>$photo_name,
        );
        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent = $request->parent;
        $category->order = $request->order;
        $category->image = $photo_name;
        $category->save();
        return response()->json([
            'success'=>true,
            'data'=>$category,
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
        Category::where("id",$id)->delete();
        return response()->json([
            'success'=>true
        ]);
    }
}
