<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
    /**
     * Return articles count of all categories
     *
     *
     * @return Response
     */
    public function getCounts(Request $request){
        $title = null;
        if($request->get("title"))
            $title = $request->get("title");

        $result = DB::select("
            SELECT (SELECT COUNT(*) FROM `blogs` WHERE (`moderation` = '1' AND `title` LIKE '%$title%') OR (`moderation` = '1' AND `tags` LIKE '%$title%') ) as blogs_count,
            (SELECT COUNT(*) FROM `products` WHERE (`moderation` = '1' AND `title` LIKE '%$title%') OR (`moderation` = '1' AND `tags` LIKE '%$title%')) as products_count,
            (SELECT COUNT(*) FROM `topics` WHERE (`moderate` = '1' AND `title` LIKE '%$title%') OR (`moderate` = '1' AND `tags` LIKE '%$title%')) as topics_count,
            (SELECT COUNT(*) FROM `galleries` WHERE (`moderation` = '1' AND `title` LIKE '%$title%') OR (`moderation` = '1' AND `tags` LIKE '%$title%') ) as gallery_count
        ");
        return response()->json([
            $result
        ]);

    }

    /**
     * Return meta data for main page
     *
     * @return Response
     */
    public function getMainMeta(){
        dd(123);
        return response()->json([
            "meta"=>\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt")
        ]);
    }
}
