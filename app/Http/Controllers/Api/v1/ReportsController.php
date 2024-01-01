<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['status']))
            if ($_GET['status'] == 1)
            {
                $status = 1;
            }
        else {
            $status = null;
        }

        return response()->json([
            "reports"=>Report::where('deleted', '=', $status)->orderBy("created_at","desc")->paginate(30)
        ]);
    }

    public function getCount(){
        return response()->json(Report::where("view","=",false)->count());
    }

    public function readReport($id){
        Report::where("id",$id)->update(["view"=>true]);
        return response()->json(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $report = Report::insert($request->all());
        if($report)
        {
            return response()->json([
                "success"=>true
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $title
     * @return \Illuminate\Http\Response
     */
    public function show($title)
    {
        $reports = Report::where("title","LIKE","%".$title."%")->where('deleted', '!=', 1)
        ->orderBy("updated_at","desc")->paginate("30");
        return response()->json([
            "reports"=>$reports
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
        $re = Report::find($id)->update(['deleted' => 1]);

        return response()->json([
            "deleted"=>true
        ]);
    }

    public function recover($id)
    {
        $re = Report::find($id)->update(['deleted' => null]);

        return response()->json([
            "deleted"=>true
        ]);
    }
}
