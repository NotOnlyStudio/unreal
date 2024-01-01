<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\v1\User\UserUpdateController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('language')) {
            App::setLocale(Session::get('language'));
        }

        $user = new UserUpdateController();
        $user->update_status();

        return $next($request);
    }
}
