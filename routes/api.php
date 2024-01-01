<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get("/get-version-client",function (){
    $md5 = md5_file('client.zip');
    $size = filesize('client.zip');

    return response()->json(['md5' => $md5, 'size'=>$size]);
});
Route::get("/get-user/{id}",[\App\Http\Controllers\Api\v1\AuthController::class,"getUserData"]);
// Route::post("/photo-test",[App\Http\Controllers\Api\v1\GalleryController::class,'photos']);
Route::get("/products/get-rules",[\App\Http\Controllers\Api\v1\ProductController::class,"getRules"]);
Route::get("/gallery/get-rules",[\App\Http\Controllers\Api\v1\GalleryController::class,"getRules"]);


Route::get("/test", function(){
    $user = \App\Models\User::find(7)->rating;
    return \App\Models\Rang::whereBetween("balls",[$user,30000])->get()->first()->id-1;
});
//-----------------------------Counts----------------------------------
Route::get("/counts",[\App\Http\Controllers\Api\v1\InfoController::class,"getCounts"]);
// --- Bookmarks
Route::get("/gallery/{alias}",[\App\Http\Controllers\Api\v1\GalleryController::class,"show"]);
//------------------Gallery------------------------
Route::get("/gallery/moderation/user/{id}",[\App\Http\Controllers\Api\v1\GalleryController::class,"getModerations"]);
Route::get("/gallery/byuser/{id}",[\App\Http\Controllers\Api\v1\GalleryController::class,"getByUser"]);
//--------------------------------------------------
Route::post("/login",[\App\Http\Controllers\Api\v1\AuthController::class,"login"]);
Route::post("/social-login",[\App\Http\Controllers\Api\v1\AuthController::class,"socialLogin"]);
Route::post("/logout",[\App\Http\Controllers\Api\v1\AuthController::class,"logout"]);
Route::get('/topics/byparent',[\App\Http\Controllers\Api\v1\TopicsController::class,"topicsByParent"]);
Route::get("/blog/byuser/{id}",[\App\Http\Controllers\Api\v1\BlogController::class,"blogByUser"]);

Route::get('/sections-all',[\App\Http\Controllers\Api\v1\SectionController::class, "getAll"]);
Route::get("/challenges-get",[\App\Http\Controllers\Api\v1\ChallengeController::class,"get"]);
Route::get("/challenges",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getAll"]);
Route::get("/challenge/{alias}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getByAlias"]);
//Admin routes
//-----Challenges---
Route::get("/challenges/old",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getOldChallenges"]);
Route::get("/challenge-users/{id}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getMaterials"]);
Route::get("/challenge/{alias}/cards",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getAllGalleries"]);
//----avards
Route::get("/users",function(){
    return \App\Models\User::find(6)->with("gallery")->get();
});
Route::get("/avards",[\App\Http\Controllers\Api\v1\AvardsController::class,"getAvardsList"]);
Route::get("/avard-banner/{id}",[\App\Http\Controllers\Api\v1\AvardsController::class,"avardBanner"]);
Route::get("/get-forum/{title}",[\App\Http\Controllers\Api\v1\TopicsController::class,"getResultsByTitle"]);
//-------Cabinet----
Route::get("/user-forums",[\App\Http\Controllers\Api\v1\ForumController::class,"userComments"]);
Route::get("/faq",[\App\Http\Controllers\FaqController::class, "getFaq"]);
//----------Report-------------
Route::get("/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"index"]);
Route::get("/avards-check",[\App\Http\Controllers\Api\v1\AvardsController::class,"checkAndAdd"]);
//Meta
Route::get("/get-main-meta",[\App\http\Controllers\Api\v1\InfoController::class,"getMainMeta"]);
//plugin
Route::post("/oauth/token",[\App\Http\Controllers\Plugin\Api\AuthController::class,"getToken"]);
Route::post("/oauth/social/token",[\App\Http\Controllers\Plugin\Api\AuthController::class,"getSocialToken"]);
Route::get("/models/list",[\App\Http\Controllers\Plugin\Api\ProductsController::class,"modelsList"]);
Route::get("/models/get/{alias}",[\App\Http\Controllers\Plugin\Api\ProductsController::class,"show"]);
Route::get("/categories/get",[\App\Http\Controllers\Api\v1\CategoryController::class,"index"]);
//!!!
Route::get("/user/purchases-tt",[\App\Http\Controllers\Plugin\Api\PurchasesController::class,"getPurchasesListTt"]);

//Route::middleware("auth:api")->group(
Route::middleware("auth:sanctum")->group(
    function(){
        Route::prefix("user")->group(
            function(){
                Route::get("/data",[\App\Http\Controllers\Plugin\Api\AuthController::class,"getUserData"]);
                Route::get("/purchases",[\App\Http\Controllers\Plugin\Api\PurchasesController::class,"getPurchasesList"]);
                Route::get("/logout",[\App\Http\Controllers\Plugin\Api\AuthController::class,"logout"]);
                Route::post("/support",[\App\Http\Controllers\Plugin\Api\AuthController::class,"support"]);
            }
        );
        Route::prefix("comments")->group(
            function(){
                Route::get("/get",[\App\Http\Controllers\Plugin\Api\CommentsController::class,"getComments"]);
                Route::post("/set",[\App\Http\Controllers\Plugin\Api\CommentsController::class,"store"]);
            }
        );
        Route::get("/free-model/download/{id}",[\App\Http\Controllers\Api\v1\DownloadsController::class,"downloadFreeModel"]);
        Route::post("/rating/set",[\App\Http\Controllers\Api\v1\RatingController::class,"rating"]);

        Route::prefix("models")->group(
            function(){
                // Route::get("/list",[\App\Http\Controllers\Plugin\Api\ProductsController::class,"modelsList"]);
                Route::post("/buy/{id}",[\App\Http\Controllers\Plugin\Api\PurchasesController::class,"buyModel"]);
                Route::get("/bookёmarks",[\App\Http\Controllers\Plugin\Api\ProductsController::class,"getBookmarks"]);
                Route::post("/check-purchase/{id}",[\App\Http\Controllers\Plugin\Api\PurchasesController::class,"checkPurchase"]);
                Route::get("/download/{id}",[\App\Http\Controllers\Plugin\Api\DownloadController::class,"download"]);
                Route::get("/buyed",[\App\Http\Controllers\Plugin\Api\ProductsController::class,"getPurchasesPage"]);
            }
        );
   }
);
