<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticController extends Controller
{
    private $subdays;
    public function __construct(){
        $this->subdays = Carbon::now()->subDays(150);

    }

    public function statisticGetter($name){
        $class_name = "\App\Models\\".$name;
        $query = $class_name::query();
        $query = $query->select([
            DB::raw('count(id) as `count`'),
            DB::raw("CONCAT(month(created_at), '.', year(created_at)) as day"),
        ])->groupBy("day")->orderBy('day','ASC')
            ->get()->toArray();
        return $query;
    }

    public function statisticGetterUser($time, $name){
        if ($time == 'month')
        {
            $this->subdays = Carbon::now()->subDays(365);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'day'){
            $this->subdays = Carbon::now()->subDays(30);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'year') {
            $this->subdays = Carbon::now()->subDays(1200);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'week') {
            $this->subdays = Carbon::now()->subDays(14);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        }
    }

    public function getStatistics(){
        $time = ($_GET['time']);
        $users = $this->statisticGetterUser($time, "User"); //
        $blog = $this->statisticGetter("Blog"); //
        $products = $this->getModels($time, "Product");
        $gallery = $this->statisticGetter("Gallery"); //
        $uploadedProducts = $this->uploadedModels($time, "Product");
        $accountRefill = $this->accountRefill($time, 'Payment');

        return response()->json([
            "users" => $users,
            "products" => $products,
            "blog" => $blog,
            "gallery" => $gallery,
            "uploaded" => $uploadedProducts,
            "refill"=> $accountRefill
        ]);
    }

    public function uploadedModels($time, $name)
    {
        if ($time == 'month')
        {
            $this->subdays = Carbon::now()->subDays(365);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('updated_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(updated_at, "%Y.%m") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'day'){
            $this->subdays = Carbon::now()->subDays(30);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('updated_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(updated_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'year') {
            $this->subdays = Carbon::now()->subDays(1200);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('updated_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(updated_at, "%Y") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'week') {
            $this->subdays = Carbon::now()->subDays(14);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('updated_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(updated_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        }
    }

    public function getModels($time, $name)
    {
        if ($time == 'month')
        {
            $this->subdays = Carbon::now()->subDays(365);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'day'){
            $this->subdays = Carbon::now()->subDays(30);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'year') {
            $this->subdays = Carbon::now()->subDays(1200);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'week') {
            $this->subdays = Carbon::now()->subDays(14);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        }
    }

    public function accountRefill($time, $name)
    {
        if ($time == 'month')
        {
            $this->subdays = Carbon::now()->subDays(365);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'day'){
            $this->subdays = Carbon::now()->subDays(30);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'year') {
            $this->subdays = Carbon::now()->subDays(1200);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        } else if ($time == 'week') {
            $this->subdays = Carbon::now()->subDays(14);

            $class_name = "\App\Models\\".$name;
            $query = $class_name::query();
            $query = $query->where('created_at', '>=', $this->subdays)->select([
                DB::raw('count(id) as `count`'),
                DB::raw('DATE_FORMAT(created_at, "%Y.%m.%d") as `day`'),
            ])->orderBy('day','ASC')->groupBy('day')
                ->get()->toArray();

            return $query;
        }
    }
}
