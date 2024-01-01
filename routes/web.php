<?php


use App\Events\PrivateChat;
use Illuminate\Support\Facades\Route;


Route::get("/test", function () {
    return \App\Models\Product::query()->where('id', 49)->update(['user_id' => 6239]);
});

Route::get('redis-test', function (){
    if(!Redis::get('test_time')){
        Redis::set('test_time', date(DATE_RFC822));
    }
    return "Date inserted in database: ".Redis::get('test_time');
});

Broadcast::routes();

//--------------------------Gallery----------------------------

Route::get("/gallery", [App\Http\Controllers\Api\v1\GalleryController::class, "getGalleryPage"]);
Route::get("/gallery-get", [App\Http\Controllers\Api\v1\GalleryController::class, "index"]);
Route::get("m{alias}", [App\Http\Controllers\Api\v1\GalleryController::class, "getGalleryItem"]);
Route::get("/gallery/avards/{id}", [App\Http\Controllers\Api\v1\AvardsController::class, "getAvardPage"]);

//-------------------------------------------------------------
Route::post("/reset-passowrd", [\App\Http\Controllers\Auth\ForgotPasswordController::class, "doReset"]);

/** POST routes */

Route::resource("products", \App\Http\Controllers\Api\v1\ProductController::class);  //-----Products
Route::resource('comments', \App\Http\Controllers\Api\v1\CommentsController::class);  //-----Comments
Route::post('gallery', [\App\Http\Controllers\Api\v1\GalleryController::class, "store"]);  //-----Gallery
Route::resource('topics', \App\Http\Controllers\Api\v1\TopicsController::class); //-----Topics
Route::post("/upload-img",[\App\Http\Controllers\UserController::class,"uploadArticleImg"]); //-----Upload image
// Route::get("/gallery/get",[App\Http\Controllers\Api\v1\GalleryController::class,"index"]);
Route::resource('blog',\App\Http\Controllers\Api\v1\BlogController::class);

/***************************************************/
Route::get("/categories-get",[\App\Http\Controllers\Api\v1\CategoryController::class,"index"]);
Route::post("/webhook/checkout/confirm",[\App\Http\Controllers\PaymentsController::class,"confirmPaymentWH"]);

Route::any('/bepaid/get', [\App\Http\Controllers\Api\v1\BePaid\BePaidController::class, 'getDonate']);
Route::get('/wallet', [\App\Http\Controllers\Api\v1\BePaid\BePaidController::class, 'wallet']);

Route::get('garanty', function() {
    return view('garanty');
});

Route::middleware(["auth"])->group(function(){
    Route::post("/basket/add/{product_id}", [App\Http\Controllers\Api\v1\BasketController::class, "addToBasket"]);
    Route::get("/basket", [App\Http\Controllers\Api\v1\BasketController::class, "getBasketPage"])->middleware(\App\Http\Middleware\fixCors::class);


    Route::get("/linker-basket", [App\Http\Controllers\Api\v1\BasketController::class, "getLinkerBasketPage"]);
    Route::delete("/basket/deleteItem/{product_id}", [App\Http\Controllers\Api\v1\BasketController::class, "deleteFromBasket"]);


    Route::prefix('bepaid')->group(function () {
        Route::get('/store', [\App\Http\Controllers\Api\v1\BePaid\BePaidController::class, 'store']);
        Route::get('getURL', [\App\Http\Controllers\Api\v1\BePaid\BePaidController::class, 'getURL']);
        Route::get('/wallet', [\App\Http\Controllers\Api\v1\BePaid\BePaidController::class, 'wallet']);
    });

    Route::post("/change-location",[\App\Http\Controllers\UserController::class,"changeLocation"]);
    /**
     * Stripe payments
     */
    // Route::get("/stripe/test",[\App\Http\Controllers\PaymentsController::class,"test"]);
    Route::prefix("stripe")->group(
        function(){
            Route::get("check",[\App\Http\Controllers\PaymentsController::class,"checkAccount"]);
            Route::post("/checkout/create",[\App\Http\Controllers\PaymentsController::class,"autoCheckout"]);
            // Route::get("/account/{account}",[\App\Http\Controllers\PaymentsController::class,"getClientInfo"]);
            Route::get("/checkout/cancel/{token}",[\App\Http\Controllers\PaymentsController::class,"cancelPayment"]);
            Route::get("/checkout/confirm/{token}",[\App\Http\Controllers\PaymentsController::class,"confirmPayment"]);
            Route::get("/stripe/{account}",[\App\Http\Controllers\PaymentsController::class,"deleteAccount"])->middleware("admin");
            Route::post("/transfer",[\App\Http\Controllers\PaymentsController::class,"transfer"]);
            Route::get("/register",[\App\Http\Controllers\PaymentsController::class,"register"]);
            Route::get("/wallet",[\App\Http\Controllers\PaymentsController::class,"finish_register"]);
        }
    );

    //--------------

    Route::get("/free-model/download/{id}",[\App\Http\Controllers\Api\v1\DownloadsController::class,"downloadFreeModel"]);
    Route::get("/gallery/{alias}",[\App\Http\Controllers\Api\v1\GalleryController::class,"getGalleryItem"]);
    /**
        * Bookmarks routes
     *
    */
    Route::prefix("bookmarks")->group(
        function(){
            Route::get("/gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"bookmarksGet"]);
            Route::get("/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"blogForUser"]);
            Route::get("/products",[\App\Http\Controllers\Api\v1\ProductController::class,"productsForUser"]);
            Route::get("/forum",[\App\Http\Controllers\Api\v1\SectionController::class, "forumsForUser"]);
        }
    );

    /**
     * Cabinet pagination
     */
    Route::prefix("user")->group(
        function(){
            Route::prefix("moderation")->group(
                function(){
                    Route::get("/gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"getModerations"]);
                    // Route::get("/blogs",[\App\Http\Controllers\Api\v1\BlogController::class,"getModerations"]);
                    Route::get("/products",[\App\Http\Controllers\Api\v1\ProductController::class,"getModerations"]);
                }
            );
            Route::prefix("moderated")->group(
                function(){
                    Route::get("/gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"getModerated"]);
                    // Route::get("/blogs",[\App\Http\Controllers\Api\v1\GalleryController::class,"getModerated"]);
                    Route::get("/products",[\App\Http\Controllers\Api\v1\ProductController::class,"getModerated"]);
                }
            );
        }
    );

    //editPage route
    Route::post("/chat/read/{channel}",[\App\Http\Controllers\ChatController::class,"readMessanges"]);
    Route::get("/edit/blog/{alias}",[\App\Http\Controllers\Api\v1\BlogController::class,"editPage"]);
    Route::post("/edit/blog/{id}",[\App\Http\Controllers\Api\v1\BlogController::class,"editBlog"]);

    //----

    Route::post("/reports",[\App\Http\Controllers\Api\v1\ReportsController::class,"store"]);
    Route::get("/user-notification-count",[\App\Http\Controllers\UserController::class,"getNotificationsList"]);
    Route::get("/notifications-get",[\App\Http\Controllers\Api\v1\NotificationController::class,"notifications"]);
    //-------------------------Strike-----------------------------
    Route::get("/blog-byuser",[\App\Http\Controllers\Api\v1\BlogController::class,"blogByUser"]);
    Route::post("/payment-request/{count}",[App\Http\Controllers\PaymentsController::class,"setPaymentRequest"]);

    //------------------Upload routes-----------------------

        Route::post("/update-profile-photo",[App\Http\Controllers\UserController::class,"uploadAvatar"]);

        Route::post('/forum/showing/{id}',[\App\Http\Controllers\Api\v1\ForumController::class, "changeShowing"]);
        Route::post('/sections/showing/{id}',[\App\Http\Controllers\Api\v1\SectionController::class, "changeShowing"]);

        Route::get("/upload-model",[\App\Http\Controllers\Api\v1\ProductController::class,"getUploadPage"]);
        Route::get("/upload-art",[\App\Http\Controllers\Api\v1\GalleryController::class,"getUploadPage"]);
        Route::get("/upload-article",[\App\Http\Controllers\Api\v1\BlogController::class,"getUploadPage"]);
        Route::get("/forum/{alias}/create-topic",[\App\Http\Controllers\Api\v1\TopicsController::class,"getUploadPage"]);
    //------------------------------------------------------
    Route::get("/challenge/{alias}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getChallengePage"]);
    Route::get("/challenge/{alias}/cards",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getChallengeResultsPage"]);

    Route::get("/cabinet",function(){
        return redirect("/cabinet/notifications");
    });

    Route::prefix("/cabinet")->group(function(){
        Route::get("/notifications",[App\Http\Controllers\CabinetController::class,"notificationsGet"])->name("cabinet-notifications");
        Route::get("/forum",[App\Http\Controllers\Api\v1\ForumController::class,"forumGet"]);
        Route::get("/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"getCabinetBlogPage"]);
        Route::get("/gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"getGalleryCabinetPage"]);
        Route::get("/models",[\App\Http\Controllers\Api\v1\ProductController::class,"getModelsCabinetPage"]);
        Route::get("/bookmarks", function(){
            return redirect()->route("bookmarks-forum");
        });

        Route::get("/messages", [App\Http\Controllers\CabinetController::class,"messangerGet"]);
        Route::get("/purchase",[\App\Http\Controllers\Api\v1\PurchaseController::class,"getPurchasesPage"]);
        Route::get("/profit",[\App\Http\Controllers\Api\v1\PurchaseController::class,"getProfitPage"]);
        Route::prefix("/bookmarks")->group(function(){
            Route::get("/forum",[\App\Http\Controllers\Api\v1\ForumController::class,"bookmarksPage"])->name("bookmarks-forum");
            Route::get("/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"bookmarksPage"]);
            Route::get("/gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"bookmarksPage"]);
            Route::get("/models",[\App\Http\Controllers\Api\v1\ProductController::class,"bookmarksPage"]);
        });
    });
    Route::post("/new-message",[\App\Http\Controllers\ChatController::class,"newMessages"]);
    Route::get("/get-messages",[\App\Http\Controllers\ChatController::class,"getMessages"]);

    //-------------------bookmarks--------------------

    Route::post("/bookmark-add",[\App\Http\Controllers\Api\v1\BookmarksController::class,"addNew"]);
    Route::get("/blog/bookmarks-get",[\App\Http\Controllers\Api\v1\BlogController::class,"getBookmarks"]);

    //------------------------------------------------

    Route::get("/load-more-chats",[App\Http\Controllers\CabinetController::class,"getMoreChannels"]);
});

Route::get("/get-video",[\App\Http\Controllers\SettingsController::class, "getVideo"]);
Route::get("/get-main-meta",[\App\Http\Controllers\SettingsController::class,"getMainMeta"]);


Route::get("/faq",[\App\Http\Controllers\FaqController::class,"getFaqPage"]);

//----------------Auth routes----------------------------------

Route::post("/login-standart",[App\Http\Controllers\Api\v1\AuthController::class,"loginStandart"]);

Route::get("/logout",function(){
    Auth::logout();
    return redirect()->route("home");
})->middleware("auth");

Route::get('/register/confirm/{token}', [App\Http\Controllers\Api\v1\AuthController::class,'confirmEmail']);
Route::get("/confirm-email", function(){
    return view("confirm-email");
});
Route::middleware(["guest"])->group(function(){
    Route::get("/password-reset",[\App\Http\Controllers\Auth\ForgotPasswordController::class,"getResetPage"]);
    Route::post("/reset-password-email",[\App\Http\Controllers\Auth\ForgotPasswordController::class,"resetSend"]);
    Route::get('/password/reset/{token}',[\App\Http\Controllers\Auth\ForgotPasswordController::class,"getReset"]);
    Route::post('/password/reset/{token}',[\App\Http\Controllers\Auth\ForgotPasswordController::class,"doReset"]);
    Route::post("/register",[\App\Http\Controllers\Api\v1\AuthController::class,"register"]);
    Route::get("/login",[App\Http\Controllers\Api\v1\AuthController::class,"getLoginPage"])->name("login");
    Route::get("/register",[App\Http\Controllers\Api\v1\AuthController::class,"getRegisterPage"])->name("register");
});

//--------------------------------------------------------------

//-------------------------------Forum routes------------------

Route::get("/forum",[App\Http\Controllers\Api\v1\ForumController::class,"getPage"]);
Route::get("/forum/{alias}",[App\Http\Controllers\Api\v1\TopicsController::class,"getTopicsPage"]);
Route::get("/forum/{sectionAlias}/{topic}",[App\Http\Controllers\Api\v1\TopicsController::class,"getTopicPage"]);
Route::get("/search-forum/{title}",[\App\Http\Controllers\Api\v1\TopicsController::class,"getResultsByTitle"]);

//-------------------------------------------------------------

Route::get("/products-get",[\App\Http\Controllers\Api\v1\ProductController::class,"index_get"]);


//-------------------------------Index route-------------------

Route::get("/",function(){
    $blog = \App\Models\Blog::where("id",'!=',0)->where("moderation","=",true)->with(["user" => function($query){$query->select("id","name");},
        "userAssessment"=>function($query){
            if(Auth::check()){
                $user_id = Auth::id();
                $query->where("user_id","=",$user_id);
            }else{
                $query->where("user_id","=","-1");

            }
        }
        ])->withCount(["likes AS likes_count" => function($query){
            $query->where("like","=",true);
        },"likes as dislikes_count" => function($query){
            $query->where("like","=",false);
        }])->withCount(["userBookmarks"])->orderBy("first","desc")->orderBy("created_at","desc")
        ->paginate(3);

    $products = \App\Models\Product::query()
    ->with(["users","userPurchases"])
    ->withCount(["userBookmarks"])
    ->orderBy("created_at","desc")->where("moderation","=",1)
    ->paginate(6);


    $gallery = \App\Models\Gallery::query()->where("moderation","=",1)->with([
        "user" => function($query){
            $query->select("id","name");
        },
        "userAssessment"=>function($query){
                if(Auth::check()){
                    $user_id = Auth::id();
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
        },'userBookmarks'])->orderBy("created_at","desc")->paginate(3);
    return view("index",[
        "blog"=>$blog,
        "products"=>$products,
        'gallery'=>$gallery
    ]);
})->name("home");
Route::get("/home",function(){
    return redirect()->route("home");
});
//-------------------------------------------------------------

//-----------------------------Store---------------------------

Route::get("/store",[\App\Http\Controllers\Api\v1\ProductController::class,"getStorePage"]);

//-------------------------------------------------------------

//---------------------Blog------------------------

Route::get("/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"getBlogPage"]);
Route::get("/blog/{alias}",[\App\Http\Controllers\Api\v1\BlogController::class,"getBlogItem"]);

//-------------------------------------------------

// Broadcasts
Route::get('/send', function () {
    // broadcast(new App\Events\PrivateChat(1));
    PrivateChat::dispatch([
        "room_id"=>2,
        "messange_body"=>312,
    ]);

    return response('Sent');
});

Route::get('/receiver', function () {
    return view('tests.receiver');
});

Route::get("/models/{alias}", [App\Http\Controllers\Api\v1\ProductController::class, "getProductPage"]);
//----------------------Cabinet------------------------------
Route::get("/user/{id}", [App\Http\Controllers\UserController::class, "getUserPage"]);
Route::get("/start-chatting",[\App\Http\Controllers\Api\v1\UserController::class,"startChatting"]);

Route::get("/user/forum/{user}", [App\Http\Controllers\Api\v1\ForumController::class, "forumGet"]);
Route::get("/user/blog/{user}", [\App\Http\Controllers\Api\v1\BlogController::class, "getCabinetBlogPage"]);
Route::get("/user/gallery/{user}", [\App\Http\Controllers\Api\v1\GalleryController::class, "getGalleryCabinetPage"]);
Route::get("/user/models/{user}", [\App\Http\Controllers\Api\v1\ProductController::class, "getModelsCabinetPage"]);

//----------------------------------------------------------

//----------------------Basket------------------------------


Route::post("/model-buy",[App\Http\Controllers\Api\v1\DownloadsController::class,"buyModel"])->middleware("auth");
//----------------------------------------------------------

Route::get("/comments-get",[\App\Http\Controllers\Api\v1\CommentsController::class,"getComments"]);

//------------------------Cabinet-----------------------------

Route::post("/create-dialog",[\App\Http\Controllers\ChatController::class,"newChat"])->middleware("auth");

//------------------------------------------------------------

Route::get('/auth/{driver}/redirect', [App\Http\Controllers\Api\v1\AuthController::class,'redirectTo']);
Route::get('/auth/{driver}/callback', [App\Http\Controllers\Api\v1\AuthController::class,'handleCallback']);

Route::get("/commands", function(){
 	Artisan::call('config:cache');
 	Artisan::call('cache:clear');
});

Route::get("/admin",function () {
    return view("layouts.admin");
})->middleware("admin");

// Route::get("/cabinet/{query}",function () {
//     return view("cabinet");
// })->where('query', '.*');

Route::get("/admin/{query}",function (){
    return view("layouts.admin");
})->where('query', '.*')->middleware("admin");
Route::get("/checkout", function(){return view("test.test-payment");});
Route::post("/user/change",[\App\Http\Controllers\UserController::class,"changeData"])->middleware('auth');

// Route::any('{query}', function() {
//     return view('index');
// })
// ->where('query', '.*');
Route::get("/banned",function(){
    return view("layouts.banned");
})->name("ban");
Route::get("/categories-get",[\App\Http\Controllers\Api\v1\CategoryController::class,"index"]);
Route::get("/politics/{policy}",[\App\Http\Controllers\SettingsController::class,"getPolicyPage"]);
Route::get("/service",[\App\Http\Controllers\SettingsController::class,"getServicePage"]);
Route::middleware("admin")->group(function(){
    Route::get("/products-admin",[\App\Http\Controllers\Api\v1\ProductController::class,"index_admin"]);
    Route::post("/meta/set",[\App\Http\Controllers\SettingsController::class,"setMeta"]);
    Route::get("/get-policy",[\App\Http\Controllers\SettingsController::class,"getPolicy"]);
    Route::post("/set-policy",[\App\Http\Controllers\SettingsController::class,"setPolicy"]);
    Route::get('/forum-get',[\App\Http\Controllers\Api\v1\ForumController::class,"index"]);
    Route::post('/forum-add',[\App\Http\Controllers\Api\v1\ForumController::class,"store"]);
    Route::resource('sections',\App\Http\Controllers\Api\v1\SectionController::class);
    Route::post('/forum-edit',[\App\Http\Controllers\Api\v1\ForumController::class,"update"]);
    Route::delete('/forum/{id}',[\App\Http\Controllers\Api\v1\ForumController::class,"destroy"]);
    Route::post('/forum-showing/{id}',[\App\Http\Controllers\Api\v1\ForumController::class,"changeShowing"]);
    Route::get("/admin-challenges",[\App\Http\Controllers\Api\v1\ChallengeController::class,"getAllChallenges"]);
    Route::post("/save-links",[\App\Http\Controllers\SettingsController::class,"socialsSave"]);
    Route::get("/social-links",[\App\Http\Controllers\SettingsController::class,"getLinks"]);
    Route::get("/site-status",[\App\Http\Controllers\SettingsController::class,"getStatus"]);
    Route::get("/change-status",[\App\Http\Controllers\SettingsController::class,"changeStatus"]);
    Route::post("/challenge-add",[\App\Http\Controllers\Api\v1\ChallengeController::class,"store"]);
    Route::delete("/challenge/{id}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"destroy"]);
    Route::get("/user-info/{id}",[\App\Http\Controllers\UserController::class,"getUser"]);
    Route::post("/challenge-edit/{id}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"editChallenge"]);
    Route::post("/challenge-remove-win",[\App\Http\Controllers\Api\v1\ChallengeController::class,"removeWin"]);
    Route::post("/challenge-winner",[\App\Http\Controllers\Api\v1\ChallengeController::class,"winFunc"]);

    // Route::resource('blog',\App\Http\Controllers\Api\v1\BlogController::class);
    Route::delete('/blog-delete/{id}',[\App\Http\Controllers\Api\v1\BlogController::class,"destroy"]);
    Route::get('/admin-blog',[\App\Http\Controllers\Api\v1\BlogController::class,"adminBlog"]);
    Route::get('/blog-get/{alias}',[\App\Http\Controllers\Api\v1\BlogController::class,"show"]);

    Route::get('/get-logs',[\App\Http\Controllers\Api\v1\UserController::class,"getLogs"]);

    Route::resource("category",\App\Http\Controllers\Api\v1\CategoryController::class);
//----Edit
    Route::post('/edit/blog',[\App\Http\Controllers\Api\v1\BlogController::class,"editBlog"]);
    Route::post('/edit/gallery',[\App\Http\Controllers\Api\v1\GalleryController::class,"editGallery"]);
    Route::post('/edit/topic/{id}',[\App\Http\Controllers\Api\v1\TopicsController::class,"update"]);
    Route::get("/admin-gallery",[\App\Http\Controllers\Api\v1\GalleryController::class,"adminGallery"]);
    Route::post('/get-admin-link',[\App\Http\Controllers\Api\v1\ProductController::class,'getAdminFile']);

    Route::get("/admin/blog",[\App\Http\Controllers\Api\v1\BlogController::class,"adminBlog"]);
    Route::get("/admin-topics",[\App\Http\Controllers\Api\v1\TopicsController::class,"adminTopics"]);

    Route::get("/statistic/get",[\App\Http\Controllers\StatisticController::class,"getStatistics"]);
    Route::post("/challenge-photo/delete/{id}",[\App\Http\Controllers\Api\v1\ChallengeController::class,"deletePhoto"]);
    // ----------------- Moderation Methods ------------------
    Route::post('/approve/gallery/{id}',[\App\Http\Controllers\Api\v1\GalleryController::class,"approve"]);
    Route::post('/moderate/blog/{id}',[\App\Http\Controllers\Api\v1\BlogController::class,"moderateArticle"]);
    Route::post('/moderate/topic/{id}',[\App\Http\Controllers\Api\v1\TopicsController::class,"moderateArticle"]);
    Route::post("/product/change-user",[\App\Http\Controllers\Api\v1\ProductController::class,"changeUser"]);
    Route::get("/users",[\App\Http\Controllers\UserController::class,"queryUser"]);

    Route::get("/users/get",[\App\Http\Controllers\UserController::class,"getUsers"]);
    Route::post("/users/ban/{id}",[\App\Http\Controllers\UserController::class,"setBanned"]);
    Route::delete("/user/{id}",[\App\Http\Controllers\UserController::class,"deleteUser"]);
    Route::get("/user-find/{id}",[\App\Http\Controllers\UserController::class,"getUser"]);
    Route::post("/user/edit",[\App\Http\Controllers\UserController::class,"editUser"]);
    Route::delete("/user/force-delete/{id}",[\App\Http\Controllers\UserController::class,"forceDeleteUser"]);
    Route::post("/user/restore/{id}",[\App\Http\Controllers\UserController::class,"restoreUser"]);

    Route::post("/user/add-model-user/{id}/{models}",[\App\Http\Controllers\UserController::class,"AddModelToUser"]);

    Route::post("/admin/products/edit",[\App\Http\Controllers\Api\v1\ProductController::class,"adminEdit"]);
    Route::get("/user/products/",[\App\Http\Controllers\Api\v1\ProductController::class,"getForUser"]);
    Route::post("/products/moderate/{id}",[\App\Http\Controllers\Api\v1\ProductController::class,"moderate"]);
    Route::post("/products/fast-edit",[\App\Http\Controllers\Api\v1\ProductController::class,"fastEdit"]);
    Route::post("/product/change-mode",[\App\Http\Controllers\Api\v1\ProductController::class,"changeMode"]);
    Route::post("/product/change-vr",[\App\Http\Controllers\Api\v1\ProductController::class,"changeVR"]);

    Route::post("/send-message",[\App\Http\Controllers\MailsController::class,"sendEmail"]);
    //-------
    Route::post("/products/save-rules",[\App\Http\Controllers\Api\v1\ProductController::class,"saveRules"]);
    Route::post("/gallery/save-rules",[\App\Http\Controllers\Api\v1\GalleryController::class,"saveRules"]);

    Route::post("/save-video",[\App\Http\Controllers\SettingsController::class, "saveVideo"]);
    Route::post("/to-user/{id}",[\App\Http\Controllers\UserController::class, "removePermission"]);
    Route::post("/user-{id}/add-permission",[\App\Http\Controllers\UserController::class, "setPermission"]);
    Route::get("/permited-users",[\App\Http\Controllers\UserController::class, "getPermitedUsers"]);
    Route::get("/find-user",[\App\Http\Controllers\UserController::class, "getUserByName"]);

    Route::get("/comments-admin",[\App\Http\Controllers\Api\v1\CommentsController::class,"getAdminComments"]);
    Route::post("/edit-comment/{id}",[\App\Http\Controllers\Api\v1\CommentsController::class,"edit"]);
    Route::delete("/comment/{id}",[\App\Http\Controllers\Api\v1\CommentsController::class,"destroy"]);
    Route::delete("/gallery/{id}",[\App\Http\Controllers\Api\v1\GalleryController::class,"destroy"]);
    Route::delete("/models/{id}",[\App\Http\Controllers\Api\v1\ProductController::class,"destroy"]);

});
//-----------faq-------------

Route::post("/faq-control",[\App\Http\Controllers\FaqController::class,"faqControl"])->middleware("admin");

Route::post("/rating-post", [\App\Http\Controllers\Api\v1\RatingController::class,"rating"]);

Route::get("/reports",[\App\Http\Controllers\Api\v1\ReportsController::class,"index"])->middleware("admin");
Route::get("/reports-count",[\App\Http\Controllers\Api\v1\ReportsController::class,"getCount"])->middleware("admin");
Route::post("/reports/read/{id}",[\App\Http\Controllers\Api\v1\ReportsController::class,"readReport"])->middleware("admin");
Route::delete("/report/{id}",[\App\Http\Controllers\Api\v1\ReportsController::class,"destroy"])->middleware("admin");
Route::patch("/recover/{id}",[\App\Http\Controllers\Api\v1\ReportsController::class,"recover"])->middleware("admin");

//--------------Download-------------------

Route::get("/downloads/model/id-{id}",[\App\Http\Controllers\Api\v1\ProductController::class,"downloadFile"]);

//----------------------------------------

/** Paymetns */

Route::get('/payment', function () {
    return view('payment.payment-start');
});

Route::get('make-payment', [\App\Http\Controllers\SkrillPaymentController::class, 'makePayment']);
Route::get('do-refund', [\App\Http\Controllers\SkrillPaymentController::class, 'doRefund']);
Route::post('ipn', [\App\Http\Controllers\SkrillPaymentController::class, 'ipn']);

Route::get('payment-completed', function () {
    return view('payment.payment-completed');
});
Route::get('payment-cancelled', function () {
    return view('payment.payment-cancelled');
});

Route::get("unauthenticate", function () {
    return response()->json(['error' => 'Unauthorized.'], 401);
})->name("unauthenticate");

